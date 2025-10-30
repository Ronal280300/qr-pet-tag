<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\EmailLog;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
    /**
     * Listar todos los pedidos
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'plan'])
            ->orderBy('created_at', 'desc');

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por búsqueda (nombre de cliente o número de orden)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filtro por fecha desde
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        // Filtro por fecha hasta
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::pending()->count(),
            'payment_uploaded' => Order::paymentUploaded()->count(),
            'verified' => Order::verified()->count(),
            'completed' => Order::completed()->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Ver detalle de un pedido
     */
    public function show(Order $order)
    {
        $order->load(['user', 'plan', 'verifiedBy']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Verificar pago de un pedido
     */
    public function verify(Request $request, Order $order)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $order->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'admin_notes' => $request->admin_notes,
            ]);

            // Actualizar datos del usuario
            $user = $order->user;
            $expiresAt = null;

            if ($order->plan->type === 'subscription') {
                $expiresAt = now()->addMonths($order->plan->duration_months);
            }

            $user->update([
                'current_plan_id' => $order->plan_id,
                'current_order_id' => $order->id,
                'pets_limit' => $order->pets_quantity,
                'plan_started_at' => now(),
                'plan_expires_at' => $expiresAt,
                'plan_is_active' => true,
            ]);

            // Enlazar mascotas pendientes creadas desde el checkout
            $pendingPets = \App\Models\Pet::where('order_id', $order->id)
                ->where('pending_activation', true)
                ->whereNull('user_id')
                ->get();

            $linkedPetsCount = 0;
            foreach ($pendingPets as $pet) {
                $pet->update([
                    'user_id' => $user->id,
                    'pending_activation' => false,
                ]);
                $linkedPetsCount++;
            }

            // Enviar email al cliente
            $this->sendVerificationEmail($order);

            DB::commit();

            $message = 'Pago verificado y plan activado exitosamente';
            if ($linkedPetsCount > 0) {
                $message .= ". Se enlazaron automáticamente {$linkedPetsCount} mascota(s) registrada(s) desde el checkout";
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error al verificar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar pago de un pedido
     */
    public function reject(Request $request, Order $order)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $order->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'admin_notes' => $request->admin_notes,
        ]);

        // Enviar email al cliente
        $this->sendRejectionEmail($order);

        return back()->with('success', 'Pago rechazado');
    }

    /**
     * Marcar pedido como completado
     */
    public function complete(Order $order)
    {
        $order->update([
            'status' => 'completed',
        ]);

        return back()->with('success', 'Pedido marcado como completado');
    }

    /**
     * Enviar email de verificación al cliente
     */
    protected function sendVerificationEmail(Order $order)
    {
        try {
            Mail::send('emails.client.payment-verified', ['order' => $order], function ($message) use ($order) {
                $message->to($order->user->email)
                    ->subject("Pago Verificado - Pedido #{$order->order_number}");
            });

            EmailLog::logEmail(
                recipient: $order->user->email,
                subject: "Pago Verificado - Pedido #{$order->order_number}",
                type: 'payment_verified',
                orderId: $order->id,
                userId: $order->user_id,
                status: 'sent'
            );

            // Enviar WhatsApp al cliente
            $whatsapp = app(WhatsAppService::class);
            $whatsapp->sendPaymentVerified($order);

        } catch (\Exception $e) {
            EmailLog::logEmail(
                recipient: $order->user->email,
                subject: "Pago Verificado - Pedido #{$order->order_number}",
                type: 'payment_verified',
                orderId: $order->id,
                userId: $order->user_id,
                status: 'failed',
                errorMessage: $e->getMessage()
            );
        }
    }

    /**
     * Enviar email de rechazo al cliente
     */
    protected function sendRejectionEmail(Order $order)
    {
        try {
            Mail::send('emails.client.payment-rejected', ['order' => $order], function ($message) use ($order) {
                $message->to($order->user->email)
                    ->subject("Pago Rechazado - Pedido #{$order->order_number}");
            });

            EmailLog::logEmail(
                recipient: $order->user->email,
                subject: "Pago Rechazado - Pedido #{$order->order_number}",
                type: 'payment_rejected',
                orderId: $order->id,
                userId: $order->user_id,
                status: 'sent'
            );

            // Enviar WhatsApp al cliente
            $whatsapp = app(WhatsAppService::class);
            $whatsapp->sendPaymentRejected($order);

        } catch (\Exception $e) {
            EmailLog::logEmail(
                recipient: $order->user->email,
                subject: "Pago Rechazado - Pedido #{$order->order_number}",
                type: 'payment_rejected',
                orderId: $order->id,
                userId: $order->user_id,
                status: 'failed',
                errorMessage: $e->getMessage()
            );
        }
    }
}
