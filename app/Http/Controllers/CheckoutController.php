<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Order;
use App\Models\AdminNotification;
use App\Models\EmailLog;
use App\Models\Setting;
use App\Services\WhatsAppService;
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
     * Proceder a la página de pago (sin crear pedido todavía)
     */
    public function proceedToPayment(Request $request, Plan $plan)
    {
        $request->validate([
            'pets_quantity' => 'required|integer|min:1',
        ]);

        if (!$plan->is_active) {
            return back()->with('error', 'El plan seleccionado no está disponible');
        }

        $petsQuantity = $request->input('pets_quantity');

        // Guardar datos en sesión para evitar manipulación de URL
        session([
            'checkout_plan_id' => $plan->id,
            'checkout_pets_quantity' => $petsQuantity,
            'checkout_timestamp' => now()->timestamp
        ]);

        // Redirigir a la página de pago sin parámetros en URL
        return redirect()->route('checkout.payment');
    }

    /**
     * Mostrar página para subir comprobante de pago
     */
    public function payment(Request $request)
    {
        // Leer desde sesión en lugar de query parameters
        $planId = session('checkout_plan_id');
        $petsQuantity = session('checkout_pets_quantity');
        $timestamp = session('checkout_timestamp');

        // Validar que existan datos en sesión y no sean muy antiguos (30 minutos)
        if (!$planId || !$petsQuantity || !$timestamp || (now()->timestamp - $timestamp) > 1800) {
            return redirect()->route('plans.index')
                ->with('error', 'Tu sesión expiró. Por favor, selecciona un plan nuevamente.');
        }

        $plan = Plan::find($planId);

        if (!$plan || !$plan->is_active) {
            session()->forget(['checkout_plan_id', 'checkout_pets_quantity', 'checkout_timestamp']);
            return redirect()->route('home')
                ->with('error', 'El plan seleccionado no está disponible');
        }

        $additionalPets = max(0, $petsQuantity - $plan->pets_included);
        $additionalCost = $additionalPets * $plan->additional_pet_price;
        $total = $plan->calculateTotal($petsQuantity);

        return view('public.checkout-payment', compact(
            'plan',
            'petsQuantity',
            'total',
            'additionalPets',
            'additionalCost'
        ));
    }

    /**
     * Procesar upload de comprobante de pago y crear el pedido
     */
    public function uploadPayment(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
            'payment_method' => 'required|in:transfer,sinpe',
            'shipping_zone' => 'required|in:gam,fuera_gam',
            'shipping_address' => 'required|string|max:1000',
        ]);

        // Leer datos desde sesión para mayor seguridad
        $planId = session('checkout_plan_id');
        $petsQuantity = session('checkout_pets_quantity');

        if (!$planId || !$petsQuantity) {
            return redirect()->route('plans.index')
                ->with('error', 'Tu sesión expiró. Por favor, selecciona un plan nuevamente.');
        }

        try {
            DB::beginTransaction();

            $plan = Plan::findOrFail($planId);

            if (!$plan->is_active) {
                return back()->with('error', 'El plan seleccionado no está disponible');
            }
            $additionalPets = max(0, $petsQuantity - $plan->pets_included);
            $additionalCost = $additionalPets * $plan->additional_pet_price;
            $planTotal = $plan->calculateTotal($petsQuantity);

            // Calcular costo de envío
            $shippingCost = $request->shipping_zone === 'gam' ? 1500 : 3500;

            // Total final = plan + envío
            $total = $planTotal + $shippingCost;

            // Guardar comprobante de pago
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Crear el pedido con el comprobante ya incluido
            $order = Order::create([
                'user_id' => Auth::id(),
                'plan_id' => $plan->id,
                'pets_quantity' => $petsQuantity,
                'subtotal' => $plan->price,
                'additional_pets_cost' => $additionalCost,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'payment_uploaded',
                'payment_proof' => $path,
                'payment_uploaded_at' => now(),
                'payment_method' => $request->payment_method,
                'sinpe_phone' => $request->payment_method === 'sinpe' ? Auth::user()->phone : null,
                'payment_description' => $request->payment_method === 'sinpe' ? 'Plan QR-Pet Tag' : null,
                'shipping_zone' => $request->shipping_zone,
                'shipping_address' => $request->shipping_address,
                'expires_at' => $plan->type === 'subscription'
                    ? now()->addMonths($plan->duration_months)
                    : null,
            ]);

            // Crear notificación para admin
            AdminNotification::createPaymentUploadedNotification($order);

            // Enviar email al admin
            $this->sendAdminNotificationEmail($order);

            // Enviar email de confirmación al cliente
            $this->sendClientConfirmationEmail($order);

            DB::commit();

            // Limpiar sesión de checkout
            session()->forget(['checkout_plan_id', 'checkout_pets_quantity', 'checkout_timestamp']);

            return redirect()->route('checkout.confirmation', $order)
                ->with('success', 'Comprobante subido exitosamente. Te contactaremos pronto.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Eliminar archivo si se subió pero falló la creación del pedido
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()
                ->with('error', 'Error al procesar el pago: ' . $e->getMessage())
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

        // Cargar mascotas asociadas a esta orden
        $order->load('pets');

        return view('public.checkout-confirmation', compact('order'));
    }

    /**
     * Mostrar formulario completo para registrar mascota (nueva página sin modal)
     */
    public function showRegisterPetForm(Order $order)
    {
        // Verificar que el pedido pertenezca al usuario autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        // Verificar que la orden esté en estado correcto
        if (!in_array($order->status, ['payment_uploaded', 'pending'])) {
            return redirect()->route('checkout.confirmation', $order)
                ->with('error', 'No puedes agregar mascotas a esta orden en su estado actual.');
        }

        // Cargar mascotas asociadas
        $order->load('pets');

        $totalPets = $order->pets_quantity;
        $registeredPets = $order->pets->count();
        $currentPetNumber = $registeredPets + 1;

        // Si ya se registraron todas las mascotas, redirigir
        if ($registeredPets >= $totalPets) {
            return redirect()->route('checkout.confirmation', $order)
                ->with('info', 'Ya has registrado todas las mascotas de tu plan.');
        }

        return view('public.checkout-register-pet', compact('order', 'totalPets', 'registeredPets', 'currentPetNumber'));
    }

    /**
     * Crear mascota desde el checkout (pendiente de activación)
     * MISMO PROCESO que PetController@store pero sin enlazar a usuario
     */
    public function storePetFromCheckout(Request $request, Order $order, \App\Services\PetQrService $qrService)
    {
        // Verificar que el pedido pertenezca al usuario autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        // Verificar que la orden esté en estado correcto
        if (!in_array($order->status, ['payment_uploaded', 'pending'])) {
            return back()->with('error', 'No puedes agregar mascotas a esta orden en su estado actual.');
        }

        // Validación exacta al formulario del admin
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:255'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['nullable', 'image', 'max:102400'], // 100MB
            'photos.*'           => ['nullable', 'image', 'max:102400'], // 100MB
            'sex'                => 'nullable|in:male,female,unknown',
            'is_neutered'        => 'nullable|boolean',
            'rabies_vaccine'     => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Guardar foto principal si existe
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('pets', 'public');
            }

            // Crear mascota sin enlazar (user_id = null), marcada como pendiente
            $data['user_id'] = null; // No se enlaza hasta que admin verifique
            $data['order_id'] = $order->id;
            $data['pending_activation'] = true;
            $data['is_lost'] = false;

            $pet = \App\Models\Pet::create($data);

            // Guardar fotos múltiples
            $sort = 1;
            foreach ($request->file('photos', []) as $file) {
                if (!$file || !$file->isValid()) continue;
                $path = $file->store('pets/photos', 'public');
                \App\Models\PetPhoto::create([
                    'pet_id'     => $pet->id,
                    'path'       => $path,
                    'sort_order' => $sort++,
                ]);
            }

            // Si no subieron fotos múltiples pero sí 'photo' legacy, usarla como primera
            if ($sort === 1 && !empty($data['photo'])) {
                \App\Models\PetPhoto::create([
                    'pet_id'     => $pet->id,
                    'path'       => $data['photo'],
                    'sort_order' => $sort++,
                ]);
            }

            // Generar QR code (mismo proceso que el admin)
            $qr = \App\Models\QrCode::firstOrNew(['pet_id' => $pet->id]);
            $qrService->ensureSlugAndImage($qr, $pet);

            DB::commit();

            return redirect()->route('checkout.confirmation', $order)
                ->with('success', "Mascota '{$pet->name}' registrada exitosamente. Será enlazada a tu cuenta cuando tu pago sea verificado.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear mascota desde checkout', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout.confirmation', $order)
                ->with('error', 'Error al registrar mascota: ' . $e->getMessage());
        }
    }

    /**
     * Enviar email de notificación al admin
     */
    protected function sendAdminNotificationEmail(Order $order)
    {
        try {
            $adminEmail = Setting::get('admin_email') ?: Setting::get('contact_email') ?: config('mail.from.address');

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

            // Enviar WhatsApp al admin
            $whatsapp = app(WhatsAppService::class);
            $whatsapp->sendPaymentUploadedToAdmin($order);

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

            // Enviar WhatsApp al cliente
            $whatsapp = app(WhatsAppService::class);
            $whatsapp->sendPaymentReceived($order);

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
