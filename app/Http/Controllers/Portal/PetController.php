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
    public function index()
    {
        $pets = Pet::with(['qrCode', 'reward'])
            ->where('user_id', Auth::id())
            ->latest('id')
            ->paginate(12);

        return view('portal.pets.index', compact('pets'));
    }

    public function create()
    {
        return view('portal.pets.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:120'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['nullable', 'image', 'max:4096'],
        ]);

        $data['user_id'] = Auth::id();
        $data['is_lost'] = false;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('pets', 'public');
        }

        $pet = Pet::create($data);

        return redirect()
            ->route('portal.pets.show', $pet)
            ->with('status', 'Mascota creada correctamente.');
    }

    public function show(Pet $pet)
    {
        $this->authorizePet($pet);

        $pet->load(['qrCode', 'reward']);

        return view('portal.pets.show', compact('pet'));
    }

    public function edit(Pet $pet)
    {
        $this->authorizePet($pet);

        return view('portal.pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $this->authorizePet($pet);

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
        $this->authorizePet($pet);

        if ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
            Storage::disk('public')->delete($pet->photo);
        }

        $pet->delete();

        return redirect()
            ->route('portal.pets.index')
            ->with('status', 'Mascota eliminada correctamente.');
    }

    /**
     * Alterna el estado de pérdida/robo.
     */
    public function toggleLost(Pet $pet)
    {
        $this->authorizePet($pet);

        $pet->is_lost = ! $pet->is_lost;
        $pet->save();

        return back()->with('status', $pet->is_lost
            ? 'Mascota marcada como perdida/robada.'
            : 'La mascota ya no está marcada como perdida.');
    }

    /**
     * Crear/actualizar recompensa para la mascota (1:1).
     */
    public function updateReward(Request $request, Pet $pet)
    {
        $this->authorizePet($pet);

        $data = $request->validate([
            'active'  => ['required', Rule::in([0,1,'0','1',true,false,'true','false'])],
            'amount'  => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'message' => ['nullable', 'string', 'max:200'],
        ]);

        $data['active'] = filter_var($data['active'], FILTER_VALIDATE_BOOLEAN);

        $reward = Reward::firstOrNew(['pet_id' => $pet->id]);
        $reward->active  = $data['active'];
        $reward->amount  = $data['amount'] ?? null;
        $reward->message = $data['message'] ?? null;
        $reward->save();

        return back()->with('status', 'Recompensa actualizada.');
    }

    /**
     * Genera (o regenera) el QR de la mascota y slug público.
     */
    public function generateQR(Pet $pet, PetQrService $qrService)
    {
        $this->authorizePet($pet);

        $qr = QrCodeModel::firstOrNew(['pet_id' => $pet->id]);

        $qrService->ensureSlugAndImage($qr, $pet);

        return back()->with('status', 'QR generado correctamente.');
    }

    private function authorizePet(Pet $pet): void
    {
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para esta mascota.');
        }
    }
}