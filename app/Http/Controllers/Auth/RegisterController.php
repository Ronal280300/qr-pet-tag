<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Support\Phone;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
            'phone_code'  => ['nullable','regex:/^\d{1,4}$/'],              // prefijo
            'phone_local' => ['nullable','regex:/^\+?[\d\s\-().]{4,20}$/'], // número local
        ]);
    }

    protected function create(array $data)
    {
        // Si el usuario no mandó código, por defecto +506
        $code  = !empty($data['phone_code']) ? $data['phone_code'] : '506';
        $local = $data['phone_local'] ?? '';

        $phone = '';
        if (!empty($local)) {
            $phone = \App\Support\Phone::toE164($code, $local);
        }

        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $phone, // guardado en E.164
        ]);
    }
}
