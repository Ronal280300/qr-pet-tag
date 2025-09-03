<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/portal/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    // La vista para mostrar el formulario de restablecer (con token) la toma la ruta y este método del trait
}
