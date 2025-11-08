<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Support\Phone;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        // separar el phone E.164 a código y local para el form
        [$code, $local] = Phone::fromE164($user->phone);

        // lista corta de prefijos (puedes ampliar)
        $codes = [
            '506' => 'Costa Rica (+506)',
            '507' => 'Panamá (+507)',
            '505' => 'Nicaragua (+505)',
            '502' => 'Guatemala (+502)',
            '503' => 'El Salvador (+503)',
            '504' => 'Honduras (+504)',
            '52'  => 'México (+52)',
            '1'   => 'EE.UU. / Canadá (+1)',
        ];

        return view('profile.edit', [
            'user'  => $user,
            'codes' => $codes,
            'code'  => $code,
            'local' => $local,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255'],
            'phone_code'  => ['nullable', 'regex:/^\d{1,4}$/'],
            'phone_local' => ['nullable', 'regex:/^[\d\s\-\(\)\.]{4,20}$/'],
        ]);

        // Normalizamos el teléfono a E.164 (si se envía algo de teléfono)
        $phone = $user->phone; // valor actual por defecto
        $code  = $data['phone_code']  ?? '';
        $local = $data['phone_local'] ?? '';

        if (trim($code) !== '' || trim($local) !== '') {
            try {
                // si no mandan code, por defecto Costa Rica
                $code  = $code !== '' ? $code : '506';
                $phone = Phone::toE164($code, $local);
            } catch (\InvalidArgumentException $e) {
                return back()
                    ->withErrors(['phone_local' => $e->getMessage()])
                    ->withInput();
            }
        }

        // Validar que el teléfono sea único (excepto para el usuario actual)
        if ($phone !== $user->phone) {
            $exists = \App\Models\User::where('phone', $phone)
                ->where('id', '!=', $user->id)
                ->exists();

            if ($exists) {
                return back()
                    ->withErrors(['phone_local' => "El teléfono {$phone} ya está registrado por otro usuario."])
                    ->withInput();
            }
        }

        try {
            $user->name  = $data['name'];
            $user->email = $data['email'];
            $user->phone = $phone;
            $user->save();

            return back()->with('status', 'Perfil actualizado.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Capturar cualquier error de DB (por si acaso)
            if ($e->getCode() === '23000') {
                return back()
                    ->withErrors(['phone_local' => "El teléfono {$phone} ya está en uso."])
                    ->withInput();
            }
            throw $e;
        }
    }


    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'Contraseña actualizada.');
    }
}
