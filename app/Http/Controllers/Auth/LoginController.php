<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Order;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    // Removed static $redirectTo - using dynamic redirectTo() method instead

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect inteligente: nuevos usuarios → planes, existentes → dashboard
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        // Si el usuario tiene mascotas o pedidos confirmados, ir al dashboard
        $hasPets = Pet::where('user_id', $user->id)->exists();
        $hasOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'payment_uploaded'])
            ->exists();

        if ($hasPets || $hasOrders) {
            return route('portal.dashboard');
        }

        // Usuarios nuevos van a planes
        return route('plans.index');
    }

    /**
     * Opcional: si quieres controlar manualmente el remember:
     * El trait ya hace: guard()->attempt(credentials, $request->boolean('remember'))
     * pero lo dejamos explícito por claridad.
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->boolean('remember')
        );
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
