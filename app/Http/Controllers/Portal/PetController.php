<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Reward;
use App\Models\QrCode as QrCodeModel;
use App\Models\PetPhoto;
use App\Services\PetQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Services\PetShareCardService;

class PetController extends Controller
{
    public function __construct()
    {
        // Solo admin en estas rutas
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
        return view('portal.pets.create');
    }

    public function store(Request $request, PetQrService $qrService)
    {
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:255'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['nullable', 'image', 'max:4096'],
            'photos.*'           => ['nullable', 'image', 'max:6144'],

            // NUEVOS CAMPOS
            'sex'            => 'nullable|in:male,female,unknown',
            
            // CONTACTO DE EMERGENCIA
            'has_emergency_contact'    => 'nullable|boolean',
            'emergency_contact_name'   => 'nullable|string|max:120',
            'emergency_contact_phone'  => 'nullable|string|max:20',
            'is_neutered'    => 'nullable|boolean',
            'rabies_vaccine' => 'nullable|boolean',
        ]);

        // Mascota nueva SIEMPRE sin dueño (queda para que el cliente la ligue al activar el TAG)
        $data['user_id'] = null;
        $data['is_lost'] = false;

        DB::transaction(function () use ($request, $data, $qrService, &$pet) {
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('pets', 'public');
            }

            $pet = \App\Models\Pet::create($data);

            // Fotos múltiples
            $sort = 1;
            foreach ($request->file('photos', []) as $file) {
                if (!$file || !$file->isValid()) continue;
                $path = $file->store('pets/photos', 'public');
                \App\Models\PetPhoto::create([
                    'pet_id'     => $pet->id,
                    'path'       => $path,
                    'sort_order' => $sort++,
                ]);
            }

            // Si no subieron fotos múltiples pero sí 'photo' legacy, la usamos como primera
            if ($sort === 1 && !empty($data['photo'])) {
                \App\Models\PetPhoto::create([
                    'pet_id'     => $pet->id,
                    'path'       => $data['photo'],
                    'sort_order' => $sort++,
                ]);
            }

            // Generar/asegurar slug + imagen del QR
            $qr = \App\Models\QrCode::firstOrNew(['pet_id' => $pet->id]);
            $qrService->ensureSlugAndImage($qr, $pet);
        });

        return redirect()
            ->route('portal.pets.show', $pet)
            ->with('status', 'Mascota creada sin dueño. Entrega el TAG al cliente para que lo active.');
    }



    public function show(Pet $pet)
    {
        $this->authorizePetOrAdmin($pet);
        $pet->load(['photos', 'qrCode']);

        $qr = $pet->qrCode;
        return view('portal.pets.show', [
            'pet' => $pet,
            'qr'  => $qr,
        ]);
    }

    public function edit(Pet $pet)
    {
        $this->authorizePetOrAdmin($pet);
        $pet->load(['photos']);
        return view('portal.pets.edit', compact('pet'));
    }

    public function update(Request $request, Pet $pet)
    {
        $this->authorizePetOrAdmin($pet);

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:255'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],

            'photo'              => ['nullable', 'image', 'max:4096'],
            'photos.*'           => ['nullable', 'image', 'max:6144'],
            'delete_photos'      => ['nullable', 'string'],

            'species'        => ['nullable', Rule::in(['dog', 'cat', 'other'])],
            'sex'            => ['nullable', Rule::in(['male', 'female', 'unknown'])],
            'size'           => ['nullable', Rule::in(['small', 'medium', 'large'])],
            'color'          => ['nullable', 'string', 'max:80'],

            // Se normalizan abajo
            'is_neutered'    => ['nullable', 'boolean'],
            'rabies_vaccine' => ['nullable', 'boolean'],
            
            // CONTACTO DE EMERGENCIA
            'has_emergency_contact'    => ['nullable', 'boolean'],
            'emergency_contact_name'   => ['nullable', 'string', 'max:120'],
            'emergency_contact_phone'  => ['nullable', 'string', 'max:20'],
        ]);

        // Normalización de toggles (aunque no lleguen en la request)
        $data['is_neutered']    = $request->boolean('is_neutered');
        $data['rabies_vaccine'] = $request->boolean('rabies_vaccine');
        
        // Contacto de emergencia
        $data['has_emergency_contact'] = $request->boolean('has_emergency_contact');
        if (!$data['has_emergency_contact']) {
            $data['emergency_contact_name'] = null;
            $data['emergency_contact_phone'] = null;
        }

        DB::transaction(function () use ($request, $pet, $data) {
            // 1) Eliminar fotos marcadas
            if (!empty($data['delete_photos'])) {
                $ids = array_filter(explode(',', $data['delete_photos']));
                foreach ($pet->photos()->whereIn('id', $ids)->get() as $photo) {
                    if (Storage::disk('public')->exists($photo->path)) {
                        Storage::disk('public')->delete($photo->path);
                    }
                    $photo->delete();
                }
            }

            // 2) Reemplazar foto principal (legado)
            if ($request->hasFile('photo')) {
                if ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
                    Storage::disk('public')->delete($pet->photo);
                }
                $data['photo'] = $request->file('photo')->store('pets', 'public');

                $maxSort = (int) $pet->photos()->max('sort_order');
                PetPhoto::create([
                    'pet_id'     => $pet->id,
                    'path'       => $data['photo'],
                    'sort_order' => $maxSort + 1,
                ]);
            }

            // 3) Actualizar datos
            $pet->update($data);

            // 4) Guardar nuevas fotos múltiples
            $sort = (int) $pet->photos()->max('sort_order');
            foreach ($request->file('photos', []) as $file) {
                if (!$file || !$file->isValid()) continue;
                $path = $file->store('pets/photos', 'public');
                PetPhoto::create([
                    'pet_id'     => $pet->id,
                    'path'       => $path,
                    'sort_order' => ++$sort,
                ]);
            }
        });

        return redirect()
            ->route('portal.pets.show', $pet)
            ->with('status', 'Mascota actualizada correctamente.');
    }


    public function destroy(Pet $pet)
    {
        $pet->delete();
        return redirect()->route('portal.pets.index')->with('status', 'Mascota eliminada.');
    }

    public function toggleLost(Pet $pet)
    {
        $this->authorizePetOrAdmin($pet);

        $pet->is_lost = ! $pet->is_lost;
        $pet->save();

        return back()->with('status', $pet->is_lost
            ? 'Mascota marcada como perdida/robada.'
            : 'La mascota ya no está marcada como perdida.');
    }

    // app/Http/Controllers/Portal/PetController.php

    public function updateReward(Request $request, Pet $pet)
    {
        // Dueño o admin
        $this->authorizePetOrAdmin($pet);

        // Normalizamos el booleano desde el select (0/1, "0"/"1", true/false, etc.)
        $activeBool = filter_var(
            $request->input('active'),
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        );
        // Si no llega nada, lo tratamos como falso
        $activeBool = $activeBool === null ? false : $activeBool;

        // Validación: si está activa => amount requerido y > 0; si no, amount opcional
        $rules = [
            'active'  => ['required', Rule::in([0, 1, '0', '1', true, false, 'true', 'false'])],
            'message' => ['nullable', 'string', 'max:200'],
        ];

        if ($activeBool) {
            $rules['amount'] = ['required', 'numeric', 'min:0.01', 'max:999999.99'];
        } else {
            // Permitimos 0 o vacío al desactivar
            $rules['amount'] = ['nullable', 'numeric', 'min:0', 'max:999999.99'];
        }

        $data = $request->validate($rules);
        $data['active'] = $activeBool;

        // Guardado/Upsert de la recompensa
        $reward = \App\Models\Reward::firstOrNew(['pet_id' => $pet->id]);
        $reward->active  = (bool) $data['active'];

        // Si está activa, guardamos el monto; si no, lo reseteamos a 0
        $reward->amount  = $reward->active ? (float) ($data['amount'] ?? 0) : 0.00;
        $reward->message = $data['message'] ?? null;
        $reward->save();

        return back()->with('status', 'Recompensa actualizada.');
    }


    public function generateQR(Pet $pet, PetQrService $qrService)
    {
        $qr = QrCodeModel::firstOrCreate(
            ['pet_id' => $pet->id],
            ['slug' => null, 'image' => null, 'activation_code' => null]
        );

        $qrService->ensureSlugAndImage($qr, $pet);

        return back()->with('status', 'QR generado correctamente.');
    }

    public function downloadQr(Pet $pet)
    {
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
        $qr = QrCodeModel::firstOrCreate(
            ['pet_id' => $pet->id],
            ['slug' => null, 'image' => null, 'activation_code' => null]
        );

        $qr->activation_code = QrCodeModel::generateActivationCode();
        $qr->save();

        return back()->with('status', 'Código de activación regenerado: ' . $qr->activation_code);
    }

    /* ===================== Helpers ===================== */

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

    public function shareCard(Pet $pet, PetShareCardService $svc)
{
    $this->authorizePetOrAdmin($pet);

    // Genera imagen
    $path = $svc->generate($pet); // ej: share/pet-12-171....png
    $url  = Storage::disk('public')->url($path);

    // Vuelve a la vista de la mascota con la previsualización y CTAs
    return redirect()
        ->route('portal.pets.show', $pet)
        ->with('share_card_path', $path)
        ->with('share_card_url', $url)
        ->with('status', 'Publicación generada. ¡Lista para compartir!');
}
}
