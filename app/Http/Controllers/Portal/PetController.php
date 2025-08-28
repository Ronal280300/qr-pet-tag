<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Reward;
use App\Models\QrCode as QrCodeModel;
use App\Services\PetQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;




class PetController extends Controller
{
    public function index()
    {
        // Dueño ve sus mascotas; admin ve todas
        $query = Pet::with(['qrCode', 'reward']);
        if (!$this->isAdmin()) {
            $query->where('user_id', Auth::id());
        }
        $pets = $query->latest('id')->paginate(12);

        return view('portal.pets.index', compact('pets'));
    }

    public function create()
    {
        // Solo admin pre-carga mascotas
        $this->ensureAdmin();
        return view('portal.pets.create');
    }

    public function store(Request $request, PetQrService $qrService)
    {
        // Solo admin pre-carga mascotas
        $this->ensureAdmin();

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:120'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['nullable', 'image', 'max:4096'],
            'user_id'            => ['nullable', 'integer'], // opcional: asignar dueño
        ]);

        $data['is_lost'] = false;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('pets', 'public');
        }

        $pet = Pet::create($data);

        // ===== Nuevo: generar TAG automáticamente (QR + activation_code) =====
        $qr = QrCodeModel::firstOrNew(['pet_id' => $pet->id]);
        $qrService->ensureSlugAndImage($qr, $pet); // setea slug, qr_code, image y activation_code
        // ====================================================================

        // Llevar al show con un mensajito que incluya el code
        return redirect()
            ->route('portal.pets.show', $pet)
            ->with('status', 'Mascota y TAG creados. Código de activación: ' . $qr->activation_code);
    }

    private function authorizePet(Pet $pet): void
    {
        if ($this->isAdmin()) return;
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para esta mascota.');
        }
    }

    public function edit(Pet $pet)
    {
        // Dueño o admin pueden editar
        $this->authorizePetOrAdmin($pet);
        return view('portal.pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        // Dueño o admin pueden actualizar
        $this->authorizePetOrAdmin($pet);

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:120'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('photo')) {
            if ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
                Storage::disk('public')->delete($pet->photo);
            }
            $data['photo'] = $request->file('photo')->store('pets', 'public');
        }

        $pet->update($data);

        return redirect()
            ->route('portal.pets.show', $pet)
            ->with('status', 'Mascota actualizada correctamente.');
    }

    public function destroy(Pet $pet)
    {
        $this->ensureAdmin(); // SOLO ADMIN elimina
        $pet->delete();
        return redirect()->route('portal.pets.index')->with('status', 'Mascota eliminada.');
    }

    public function toggleLost(Pet $pet)
    {
        // Dueño o admin
        $this->authorizePetOrAdmin($pet);

        $pet->is_lost = ! $pet->is_lost;
        $pet->save();

        return back()->with('status', $pet->is_lost
            ? 'Mascota marcada como perdida/robada.'
            : 'La mascota ya no está marcada como perdida.');
    }

    public function updateReward(Request $request, Pet $pet)
    {
        $this->authorizePetOrAdmin($pet);

        // Normalizamos 'active' a boolean
        $activeBool = filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $activeBool = $activeBool === null ? false : $activeBool;

        // Validación condicional
        $rules = [
            'active'  => ['required', \Illuminate\Validation\Rule::in([0, 1, '0', '1', true, false, 'true', 'false'])],
            'message' => ['nullable', 'string', 'max:200'],
        ];

        if ($activeBool) {
            // Si está activa, amount es obligatorio y > 0
            $rules['amount'] = ['required', 'numeric', 'min:0.01', 'max:999999.99'];
        } else {
            // Si no está activa, amount opcional (si llega, debe ser válido)
            $rules['amount'] = ['nullable', 'numeric', 'min:0', 'max:999999.99'];
        }

        $data = $request->validate($rules);
        $data['active'] = $activeBool;

        $reward = \App\Models\Reward::firstOrNew(['pet_id' => $pet->id]);
        $reward->active  = $data['active'];
        // Importante: evitar NULL en BD cuando no está activa
        $reward->amount  = $data['active'] ? (float)$data['amount'] : 0.00;
        $reward->message = $data['message'] ?? null;
        $reward->save();

        return back()->with('status', 'Recompensa actualizada.');
    }


    public function generateQR(Pet $pet, PetQrService $qrService)
    {
        $this->ensureAdmin(); // solo admin genera/regenera

        // Garantizamos que exista registro con activation_code
        $qr = QrCodeModel::firstOrCreate(
            ['pet_id' => $pet->id],
            [
                'activation_code' => QrCodeModel::generateActivationCode(),
                'slug'            => null,
                'image'           => null,
            ]
        );

        // Genera slug (si falta) e imagen y persiste ruta en $qr->image
        $qrService->ensureSlugAndImage($qr, $pet);

        return back()->with('status', 'QR generado correctamente.');
    }

    /**
     * Descargar el archivo del QR (PNG/SVG) — solo admin.
     */
    public function downloadQr(Pet $pet)
    {
        // Dueño o admin pueden descargar si existe imagen
        if (! $this->isAdmin() && $pet->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para esta mascota.');
        }

        $qr = QrCodeModel::firstWhere('pet_id', $pet->id);
        if (!$qr || !$qr->image || !Storage::disk('public')->exists($qr->image)) {
            abort(404, 'QR no disponible.');
        }

        $absolutePath = Storage::disk('public')->path($qr->image);
        $filename     = 'qr-' . $pet->name . '-' . $qr->slug . '.' . pathinfo($absolutePath, PATHINFO_EXTENSION);

        return response()->download($absolutePath, $filename, [
            'Content-Type'  => mime_content_type($absolutePath) ?: 'application/octet-stream',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    // ...

    public function regenCode(Pet $pet)
    {
        $this->ensureAdmin(); // solo admin

        // Aseguramos que exista el registro del QR para esta mascota
        $qr = QrCodeModel::firstOrCreate(
            ['pet_id' => $pet->id],
            ['slug' => null, 'image' => null, 'activation_code' => null]
        );

        // Cambiamos ÚNICAMENTE el código de activación (el slug/imagen del QR no se tocan)
        $qr->activation_code = QrCodeModel::generateActivationCode();
        $qr->save();

        return back()->with('status', 'Código de activación regenerado: ' . $qr->activation_code);
    }


    private function authorizePetOrAdmin(Pet $pet): void
    {
        if ($this->isAdmin()) return;

        if (!is_null($pet->user_id) && $pet->user_id === Auth::id()) {
            return;
        }

        abort(403, 'No tienes permiso para esta mascota.');
    }

    private function isAdmin(): bool
    {
        return (bool) (Auth::user()->is_admin ?? false);
    }

    private function ensureAdmin(): void
    {
        if (! $this->isAdmin()) {
            abort(403, 'Solo administradores.');
        }
    }
    public function show(Pet $pet)
    {
        // dueño o admin
        $this->authorizePetOrAdmin($pet);

        $qr     = QrCodeModel::firstWhere('pet_id', $pet->id);
        $reward = Reward::firstWhere('pet_id', $pet->id);
        $owner  = $pet->user; // por si quieres mostrar nombre del dueño
        $isAdmin = $this->isAdmin();

        // Seguridad extra: si NO es admin, no exponemos activation_code por accidente
        if (!$isAdmin && $qr) {
            $qr->makeHidden(['activation_code']);
        }

        return view('portal.pets.show', compact('pet', 'qr', 'reward', 'owner', 'isAdmin'));
    }


    //método para regenerar el TAG:
    public function regenActivationCode(Pet $pet)
    {
        $this->ensureAdmin(); // solo admin

        $qr = QrCodeModel::firstOrCreate(
            ['pet_id' => $pet->id],
            ['slug' => null, 'image' => null]
        );

        $qr->activation_code = QrCodeModel::generateActivationCode();
        $qr->save();

        return back()->with('status', 'Código de activación regenerado: ' . $qr->activation_code);
    }
}
