<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
     * Redirect: clientes existentes (login) → mascotas
     */
    protected function redirectTo()
    {
        // Todo cliente que hace LOGIN va a sus mascotas
        // (si no tiene mascotas verá lista vacía con botón para crear)
        return route('portal.pets.index');
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
