<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\User;
use App\Models\Order;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PetActivationController extends Controller
{
    /**
     * Activar mascota con token de invitación
     *
     * Este método maneja 3 escenarios:
     * 1. Usuario autenticado y email coincide: liga la mascota directamente
     * 2. Usuario autenticado pero email NO coincide: muestra error
     * 3. Usuario no registrado: redirige a registro con el token
     */
    public function activate(Request $request, $token)
    {
        // Buscar mascota por token
        $pet = Pet::where('pending_token', $token)
            ->where('is_pending_registration', true)
            ->whereNull('pending_completed_at')
            ->first();

        if (!$pet) {
            Log::warning('Token de invitación inválido o ya usado', ['token' => $token]);
            return redirect()->route('login')
                ->with('error', 'El link de invitación es inválido o ya fue usado.');
        }

        // Verificar que no haya expirado (30 días)
        if ($pet->pending_sent_at && $pet->pending_sent_at->diffInDays(now()) > 30) {
            Log::warning('Token de invitación expirado', [
                'token' => $token,
                'sent_at' => $pet->pending_sent_at,
                'pet_id' => $pet->id
            ]);
            return redirect()->route('login')
                ->with('error', 'El link de invitación ha expirado (más de 30 días). Contacta al administrador para que te envíe uno nuevo.');
        }

        // Caso 1: Usuario ya autenticado
        if (Auth::check()) {
            $user = Auth::user();

            // IMPORTANTE: Validar que el email coincida
            if (strtolower(trim($user->email)) !== strtolower(trim($pet->pending_email))) {
                Log::warning('Email no coincide en activación', [
                    'user_email' => $user->email,
                    'pending_email' => $pet->pending_email,
                    'pet_id' => $pet->id,
                    'user_id' => $user->id
                ]);

                return redirect()->route('portal.dashboard')
                    ->with('error', 'Este link de invitación fue enviado a ' . $pet->pending_email . ', pero estás autenticado como ' . $user->email . '. Por favor cierra sesión y regístrate con el email correcto, o contacta al administrador.');
            }

            // Email coincide, proceder con activación
            return $this->completePetActivation($user, $pet);
        }

        // Caso 2: Verificar si el email ya existe en el sistema
        $existingUser = User::where('email', $pet->pending_email)->first();

        if ($existingUser) {
            // Usuario existe pero no está autenticado
            session(['pet_activation_token' => $token]);
            session(['pet_activation_email' => $pet->pending_email]);

            return redirect()->route('login')
                ->with('status', 'Ya tienes una cuenta con el email ' . $pet->pending_email . '. Por favor inicia sesión para reclamar tu mascota: ' . $pet->name);
        }

        // Caso 3: Usuario no registrado - guardar token en sesión y redirigir a registro
        session(['pet_activation_token' => $token]);
        session(['pet_activation_email' => $pet->pending_email]);

        return redirect()->route('register')
            ->with('status', 'Por favor completa tu registro con el email ' . $pet->pending_email . ' para reclamar tu mascota: ' . $pet->name);
    }

    /**
     * Completar activación de mascota (asignar al usuario y crear orden)
     */
    protected function completePetActivation(User $user, Pet $pet)
    {
        DB::beginTransaction();

        try {
            Log::info('Iniciando activación de mascota', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'pet_id' => $pet->id,
                'pet_name' => $pet->name,
                'pending_plan_id' => $pet->pending_plan_id
            ]);

            // 1. Asignar mascota al usuario
            $pet->user_id = $user->id;
            $pet->is_pending_registration = false;
            $pet->pending_completed_at = now();
            $pet->save();

            Log::info('Mascota asignada al usuario', ['pet_id' => $pet->id, 'user_id' => $user->id]);

            // 2. Crear orden con el plan seleccionado
            if ($pet->pending_plan_id) {
                $plan = Plan::find($pet->pending_plan_id);

                if ($plan) {
                    $order = Order::create([
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'status' => 'verified', // Directamente verificada
                        'total_price' => $plan->price,
                        'payment_method' => 'admin_assignment', // Método especial
                        'payment_status' => 'paid',
                        'verified_at' => now(),
                        'verified_by' => 'system', // Indicar que fue el sistema
                        'notes' => 'Orden creada automáticamente por invitación de admin para mascota: ' . $pet->name,
                    ]);

                    // Ligar mascota a la orden
                    $pet->order_id = $order->id;
                    $pet->save();

                    Log::info('Orden creada y ligada a mascota', [
                        'order_id' => $order->id,
                        'pet_id' => $pet->id,
                        'plan_id' => $plan->id
                    ]);
                } else {
                    Log::warning('Plan no encontrado', ['pending_plan_id' => $pet->pending_plan_id]);
                }
            }

            DB::commit();

            // Limpiar token de sesión si existe
            session()->forget('pet_activation_token');
            session()->forget('pet_activation_email');

            Log::info('Activación completada exitosamente', [
                'pet_id' => $pet->id,
                'user_id' => $user->id
            ]);

            return redirect()->route('portal.pets.show', $pet)
                ->with('status', '¡Bienvenido! Tu mascota ' . $pet->name . ' ha sido ligada exitosamente a tu cuenta.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error activando mascota', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'pet_id' => $pet->id
            ]);

            return redirect()->route('portal.dashboard')
                ->with('error', 'Hubo un error al activar tu mascota: ' . $e->getMessage() . '. Por favor contacta al administrador.');
        }
    }

    /**
     * Procesar activación después del registro
     * Este método se llama desde el RegisterController
     */
    public static function processAfterRegistration(User $user)
    {
        $token = session('pet_activation_token');
        $expectedEmail = session('pet_activation_email');

        if (!$token) {
            Log::info('No hay token de activación en sesión para el usuario registrado', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            return false;
        }

        // IMPORTANTE: Validar que el email del usuario registrado coincida con el esperado
        if ($expectedEmail && strtolower(trim($user->email)) !== strtolower(trim($expectedEmail))) {
            Log::warning('Email de registro no coincide con email de invitación', [
                'user_email' => $user->email,
                'expected_email' => $expectedEmail,
                'user_id' => $user->id
            ]);

            session()->forget('pet_activation_token');
            session()->forget('pet_activation_email');

            // Agregar mensaje de error
            session()->flash('error', 'Te registraste con un email diferente al de la invitación. Por favor contacta al administrador.');
            return false;
        }

        $pet = Pet::where('pending_token', $token)
            ->where('is_pending_registration', true)
            ->whereNull('pending_completed_at')
            ->first();

        if (!$pet) {
            Log::warning('No se encontró mascota con el token en processAfterRegistration', [
                'token' => $token,
                'user_id' => $user->id
            ]);
            session()->forget('pet_activation_token');
            session()->forget('pet_activation_email');
            return false;
        }

        // Validar email adicional
        if (strtolower(trim($user->email)) !== strtolower(trim($pet->pending_email))) {
            Log::warning('Email del usuario no coincide con pending_email de la mascota', [
                'user_email' => $user->email,
                'pending_email' => $pet->pending_email,
                'pet_id' => $pet->id,
                'user_id' => $user->id
            ]);

            session()->forget('pet_activation_token');
            session()->forget('pet_activation_email');
            return false;
        }

        DB::beginTransaction();
        try {
            Log::info('Procesando activación después del registro', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'pet_id' => $pet->id,
                'pet_name' => $pet->name
            ]);

            // Asignar mascota al usuario
            $pet->user_id = $user->id;
            $pet->is_pending_registration = false;
            $pet->pending_completed_at = now();
            $pet->save();

            // Crear orden con el plan seleccionado
            if ($pet->pending_plan_id) {
                $plan = Plan::find($pet->pending_plan_id);

                if ($plan) {
                    $order = Order::create([
                        'user_id' => $user->id,
                        'plan_id' => $plan->id,
                        'status' => 'verified',
                        'total_price' => $plan->price,
                        'payment_method' => 'admin_assignment',
                        'payment_status' => 'paid',
                        'verified_at' => now(),
                        'verified_by' => 'system',
                        'notes' => 'Orden creada automáticamente por invitación de admin para mascota: ' . $pet->name,
                    ]);

                    $pet->order_id = $order->id;
                    $pet->save();

                    Log::info('Orden creada en processAfterRegistration', [
                        'order_id' => $order->id,
                        'pet_id' => $pet->id,
                        'user_id' => $user->id
                    ]);
                }
            }

            DB::commit();
            session()->forget('pet_activation_token');
            session()->forget('pet_activation_email');

            Log::info('Activación después de registro completada', [
                'user_id' => $user->id,
                'pet_id' => $pet->id
            ]);

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en processAfterRegistration', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'pet_id' => $pet->id ?? null
            ]);
            return false;
        }
    }
}
