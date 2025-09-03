<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Support\Phone;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * A dónde redirigir tras registro.
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Valida el formulario de registro.
     * Nota: aquí validamos email y el formato básico de phone_*,
     * y además hacemos un AFTER para validar longitud por país y unicidad real del E.164.
     */
    protected function validator(array $data)
    {
        $v = Validator::make($data, [
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_code'  => ['nullable', 'regex:/^\d{1,4}$/'],               // prefijo (solo dígitos)
            'phone_local' => ['nullable', 'regex:/^[\d\s\-\(\)\.]{4,20}$/'],  // local (flexible)
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Validaciones derivadas (longitud por país + unicidad del E.164)
        $v->after(function ($validator) use ($data) {
            $code  = $data['phone_code']  ?? '';
            $local = $data['phone_local'] ?? '';

            // Si el usuario no envía teléfono, no forzamos nada extra.
            if (trim((string)$local) === '') {
                return;
            }

            // 1) Longitud/validez local según el país
            if (!Phone::isValidLocal($code, $local)) {
                $validator->errors()->add(
                    'phone_local',
                    'Verifica la longitud del número para el país seleccionado.'
                );
                return; // no sigas con unicidad si ya es inválido el formato
            }

            // 2) E.164 y unicidad real en BD
            $e164 = Phone::toE164($code, $local);
            if ($e164 !== '' && User::where('phone', $e164)->exists()) {
                // Adjuntamos el error al grupo del teléfono (puedes mostrarlo bajo el input)
                $validator->errors()->add('phone', 'Este número de teléfono ya está registrado.');
            }
        });

        return $v;
    }

    /**
     * Crea el usuario. Aquí consolidamos el E.164 (o vacío si no envió teléfono).
     */
    protected function create(array $data)
    {
        $code  = !empty($data['phone_code']) ? $data['phone_code'] : '506';
        $local = $data['phone_local'] ?? '';

        $phone = '';
        if (!empty($local)) {
            $phone = Phone::toE164($code, $local);
        }

        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $phone, // guardado en E.164
        ]);
    }
}
