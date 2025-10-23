<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Order;
use App\Models\AdminNotification;
use App\Models\EmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar página de checkout para un plan
     */
    public function show(Request $request, Plan $plan)
    {
        if (!$plan->is_active) {
            return redirect()->route('home')
                ->with('error', 'El plan seleccionado no está disponible');
        }

        $petsQuantity = (int) $request->input('pets', $plan->pets_included);

        if ($petsQuantity < 1) {
            $petsQuantity = 1;
        }

        $total = $plan->calculateTotal($petsQuantity);
        $additionalPets = max(0, $petsQuantity - $plan->pets_included);
        $additionalCost = $additionalPets * $plan->additional_pet_price;

        return view('public.checkout', compact(
            'plan',
            'petsQuantity',
            'total',
            'additionalPets',
            'additionalCost'
        ));
    }

    /**
     * Crear pedido inicial
     */
    public function createOrder(Request $request, Plan $plan)
    {
        $request->validate([
            'pets_quantity' => 'required|integer|min:1',
        ]);

        if (!$plan->is_active) {
            return back()->with('error', 'El plan seleccionado no está disponible');
        }

        $petsQuantity = $request->input('pets_quantity');
        $additionalPets = max(0, $petsQuantity - $plan->pets_included);
        $additionalCost = $additionalPets * $plan->additional_pet_price;
        $total = $plan->calculateTotal($petsQuantity);

        // Crear el pedido
        $order = Order::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'pets_quantity' => $petsQuantity,
            'subtotal' => $plan->price,
            'additional_pets_cost' => $additionalCost,
            'total' => $total,
            'status' => 'pending',
            'expires_at' => $plan->type === 'subscription'
                ? now()->addMonths($plan->duration_months)
                : null,
        ]);

        // Crear notificación para admin
        AdminNotification::createNewOrderNotification($order);

        return redirect()->route('checkout.payment', $order)
            ->with('success', 'Pedido creado exitosamente');
    }

    /**
     * Mostrar página para subir comprobante de pago
     */
    public function payment(Order $order)
    {
        // Verificar que el pedido pertenezca al usuario autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        return view('public.checkout-payment', compact('order'));
    }

    /**
     * Procesar upload de comprobante de pago
     */
    public function uploadPayment(Request $request, Order $order)
    {
        // Verificar que el pedido pertenezca al usuario autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
        ]);

        try {
            DB::beginTransaction();

            // Eliminar comprobante anterior si existe
            if ($order->payment_proof && Storage::disk('public')->exists($order->payment_proof)) {
                Storage::disk('public')->delete($order->payment_proof);
            }

            // Guardar nuevo comprobante
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Actualizar pedido
            $order->update([
                'payment_proof' => $path,
                'payment_uploaded_at' => now(),
                'status' => 'payment_uploaded',
            ]);

            // Crear notificación para admin
            AdminNotification::createPaymentUploadedNotification($order);

            // Enviar email al admin
            $this->sendAdminNotificationEmail($order);

            // Enviar email de confirmación al cliente
            $this->sendClientConfirmationEmail($order);

            DB::commit();

            return redirect()->route('checkout.confirmation', $order)
                ->with('success', 'Comprobante subido exitosamente. Te contactaremos pronto.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Error al subir el comprobante: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar página de confirmación
     */
    public function confirmation(Order $order)
    {
        // Verificar que el pedido pertenezca al usuario autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        return view('public.checkout-confirmation', compact('order'));
    }

    /**
     * Enviar email de notificación al admin
     */
    protected function sendAdminNotificationEmail(Order $order)
    {
        try {
            $adminEmail = config('mail.from.address');

            Mail::send('emails.admin.new-payment', ['order' => $order], function ($message) use ($adminEmail, $order) {
                $message->to($adminEmail)
                    ->subject("Nuevo Comprobante de Pago - Pedido #{$order->order_number}");
            });

            EmailLog::logEmail(
                recipient: $adminEmail,
                subject: "Nuevo Comprobante de Pago - Pedido #{$order->order_number}",
                type: 'admin_payment_notification',
                orderId: $order->id,
                userId: $order->user_id,
                status: 'sent'
            );

        } catch (\Exception $e) {
            EmailLog::logEmail(
                recipient: $adminEmail ?? 'unknown',
                subject: "Nuevo Comprobante de Pago - Pedido #{$order->order_number}",
                type: 'admin_payment_notification',
                orderId: $order->id,
                userId: $order->user_id,
                status: 'failed',
                errorMessage: $e->getMessage()
            );
        }
    }

    /**
     * Enviar email de confirmación al cliente
     */
    protected function sendClientConfirmationEmail(Order $order)
    {
        try {
            Mail::send('emails.client.payment-received', ['order' => $order], function ($message) use ($order) {
                $message->to($order->user->email)
                    ->subject("Comprobante Recibido - Pedido #{$order->order_number}");
            });

            EmailLog::logEmail(
                recipient: $order->user->email,
                subject: "Comprobante Recibido - Pedido #{$order->order_number}",
                type: 'client_payment_confirmation',
                orderId: $order->id,
                userId: $order->user_id,
                status: 'sent'
            );

        } catch (\Exception $e) {
            EmailLog::logEmail(
                recipient: $order->user->email,
                subject: "Comprobante Recibido - Pedido #{$order->order_number}",
                type: 'client_payment_confirmation',
                orderId: $order->id,
                userId: $order->user_id,
                status: 'failed',
                errorMessage: $e->getMessage()
            );
        }
    }
}
