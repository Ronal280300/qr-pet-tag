<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\User;
use App\Models\Order;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetActivationController extends Controller
{
    /**
     * Activar mascota con token de invitación
     *
     * Este método maneja 2 escenarios:
     * 1. Usuario ya registrado: liga la mascota directamente
     * 2. Usuario no registrado: redirige a registro con el token
     */
    public function activate(Request $request, $token)
    {
        // Buscar mascota por token
        $pet = Pet::where('pending_token', $token)
            ->where('is_pending_registration', true)
            ->whereNull('pending_completed_at')
            ->first();

        if (!$pet) {
            return redirect()->route('login')
                ->with('error', 'El link de invitación es inválido o ya fue usado.');
        }

        // Verificar que no haya expirado (30 días)
        if ($pet->pending_sent_at && $pet->pending_sent_at->diffInDays(now()) > 30) {
            return redirect()->route('login')
                ->with('error', 'El link de invitación ha expirado. Contacta al administrador.');
        }

        // Caso 1: Usuario ya autenticado
        if (Auth::check()) {
            return $this->completePetActivation(Auth::user(), $pet);
        }

        // Caso 2: Usuario no registrado - guardar token en sesión y redirigir a registro
        session(['pet_activation_token' => $token]);

        return redirect()->route('register')
            ->with('status', 'Por favor completa tu registro para reclamar tu mascota: ' . $pet->name);
    }

    /**
     * Completar activación de mascota (asignar al usuario y crear orden)
     */
    protected function completePetActivation(User $user, Pet $pet)
    {
        DB::beginTransaction();

        try {
            // 1. Asignar mascota al usuario
            $pet->user_id = $user->id;
            $pet->is_pending_registration = false;
            $pet->pending_completed_at = now();
            $pet->save();

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
                        'notes' => 'Orden creada automáticamente por invitación de admin',
                    ]);

                    // Ligar mascota a la orden
                    $pet->order_id = $order->id;
                    $pet->save();
                }
            }

            DB::commit();

            // Limpiar token de sesión si existe
            session()->forget('pet_activation_token');

            return redirect()->route('portal.pets.show', $pet)
                ->with('status', '¡Bienvenido! Tu mascota ' . $pet->name . ' ha sido ligada exitosamente a tu cuenta.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error activando mascota: ' . $e->getMessage());

            return redirect()->route('portal.dashboard')
                ->with('error', 'Hubo un error al activar tu mascota. Por favor contacta al administrador.');
        }
    }

    /**
     * Procesar activación después del registro
     * Este método se llama desde el RegisterController
     */
    public static function processAfterRegistration(User $user)
    {
        $token = session('pet_activation_token');

        if (!$token) {
            return false;
        }

        $pet = Pet::where('pending_token', $token)
            ->where('is_pending_registration', true)
            ->whereNull('pending_completed_at')
            ->first();

        if (!$pet) {
            session()->forget('pet_activation_token');
            return false;
        }

        // Usar una instancia del controller para llamar al método protected
        $controller = new self();

        DB::beginTransaction();
        try {
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
                        'notes' => 'Orden creada automáticamente por invitación de admin',
                    ]);

                    $pet->order_id = $order->id;
                    $pet->save();
                }
            }

            DB::commit();
            session()->forget('pet_activation_token');

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en processAfterRegistration: ' . $e->getMessage());
            return false;
        }
    }
}
