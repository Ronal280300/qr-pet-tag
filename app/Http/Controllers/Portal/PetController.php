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
use Illuminate\Validation\Rule;

class PetController extends Controller
{
    public function __construct()
    {
        // Rutas que sí requieren ADMIN (solo estas)
        $this->middleware(\App\Http\Middleware\AdminOnly::class)
            ->only(['create', 'store', 'destroy', 'generateQR', 'regenCode']);
    }

    public function index()
    {
        $query = Pet::with(['qrCode', 'reward', 'user'])->latest('id');

        if (!$this->isAdmin()) {
            $query->where('user_id', Auth::id());
        }

        $pets = $query->paginate(12);

        return view('portal.pets.index', compact('pets'));
    }

    public function create()
    {
        // Solo admin (enforced via middleware), aquí no necesitamos lógica extra
        return view('portal.pets.create');
    }

    public function store(Request $request, PetQrService $qrService)
    {
        // Solo admin (enforced via middleware)
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:255'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['nullable', 'image', 'max:4096'],
            'user_id'            => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $data['is_lost'] = false;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('pets', 'public');
        }

        $pet = Pet::create($data);

        // Generar TAG/QR automáticamente
        $qr = QrCodeModel::firstOrNew(['pet_id' => $pet->id]);
        $qrService->ensureSlugAndImage($qr, $pet);

        return redirect()
            ->route('portal.pets.show', $pet)
            ->with('status', 'Mascota y TAG creados. Código de activación: ' . $qr->activation_code);
    }

    public function show(Pet $pet)
    {
        $this->authorizePetOrAdmin($pet);

        $qr = $pet->qrCode()->first();
        return view('portal.pets.show', [
            'pet' => $pet,
            'qr'  => $qr,
        ]);
    }

    public function edit(Pet $pet)
    {
        // DUEÑO O ADMIN pueden editar
        $this->authorizePetOrAdmin($pet);

        return view('portal.pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        // DUEÑO O ADMIN
        $this->authorizePetOrAdmin($pet);

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:255'],
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
        // Solo admin (enforced via middleware)
        $pet->delete();
        return redirect()->route('portal.pets.index')->with('status', 'Mascota eliminada.');
    }

    public function toggleLost(Pet $pet)
    {
        // DUEÑO O ADMIN
        $this->authorizePetOrAdmin($pet);

        $pet->is_lost = ! $pet->is_lost;
        $pet->save();

        return back()->with('status', $pet->is_lost
            ? 'Mascota marcada como perdida/robada.'
            : 'La mascota ya no está marcada como perdida.');
    }

    public function updateReward(Request $request, Pet $pet)
    {
        // DUEÑO O ADMIN
        $this->authorizePetOrAdmin($pet);

        $activeBool = filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $activeBool = $activeBool === null ? false : $activeBool;

        $rules = [
            'active'  => ['required', Rule::in([0, 1, '0', '1', true, false, 'true', 'false'])],
            'message' => ['nullable', 'string', 'max:200'],
        ];

        if ($activeBool) {
            $rules['amount'] = ['required', 'numeric', 'min:0.01', 'max:999999.99'];
        } else {
            $rules['amount'] = ['nullable', 'numeric', 'min:0', 'max:999999.99'];
        }

        $data = $request->validate($rules);
        $data['active'] = $activeBool;

        $reward = Reward::firstOrNew(['pet_id' => $pet->id]);
        $reward->active  = $data['active'];
        $reward->amount  = $data['active'] ? (float) ($data['amount'] ?? 0) : 0.00;
        $reward->message = $data['message'] ?? null;
        $reward->save();

        return back()->with('status', 'Recompensa actualizada.');
    }

    public function generateQR(Pet $pet, PetQrService $qrService)
    {
        // Solo admin (enforced via middleware)
        $qr = QrCodeModel::firstOrCreate(
            ['pet_id' => $pet->id],
            [
                'slug'            => null,
                'image'           => null,
                'activation_code' => null,
            ]
        );

        $qrService->ensureSlugAndImage($qr, $pet);

        return back()->with('status', 'QR generado correctamente.');
    }

    public function downloadQr(Pet $pet)
    {
        // DUEÑO O ADMIN
        $this->authorizePetOrAdmin($pet);

        $qr = QrCodeModel::firstWhere('pet_id', $pet->id);
        if (!$qr || !$qr->image || !Storage::disk('public')->exists($qr->image)) {
            return back()->with('danger', 'No hay imagen del QR para descargar.');
        }

        $absolutePath = Storage::disk('public')->path($qr->image);
        $filename     = 'qr-' . $pet->name . '-' . $qr->slug . '.' . pathinfo($absolutePath, PATHINFO_EXTENSION);

        return response()->download($absolutePath, $filename, [
            'Content-Type'  => mime_content_type($absolutePath) ?: 'application/octet-stream',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    public function regenCode(Pet $pet)
    {
        // Solo admin (enforced via middleware)
        $qr = QrCodeModel::firstOrCreate(
            ['pet_id' => $pet->id],
            ['slug' => null, 'image' => null, 'activation_code' => null]
        );

        // Cambiamos solo el código de activación
        $qr->activation_code = QrCodeModel::generateActivationCode();
        $qr->save();

        return back()->with('status', 'Código de activación regenerado: ' . $qr->activation_code);
    }

    /* ===================== Helpers ===================== */

    private function authorizePetOrAdmin(Pet $pet): void
    {
        if ($this->isAdmin()) return;

        // Dueño
        if (!is_null($pet->user_id) && $pet->user_id === Auth::id()) {
            return;
        }

        abort(403, 'No tienes permiso para esta mascota.');
    }

    private function isAdmin(): bool
    {
        return (bool) (Auth::user()->is_admin ?? false);
    }
}
