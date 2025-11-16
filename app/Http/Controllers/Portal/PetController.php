<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Reward;
use App\Models\QrCode as QrCodeModel;
use App\Models\PetPhoto;
use App\Services\PetQrService;
use App\Services\PetPhotoOptimizationService;
use App\Jobs\OptimizePetPhotoJob;
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

    public function store(Request $request, PetQrService $qrService, PetPhotoOptimizationService $photoService)
    {
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:255'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['nullable', 'image', 'max:10240'],
            'photos.*'           => ['nullable', 'image', 'max:10240'],

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

        DB::transaction(function () use ($request, $data, $qrService, $photoService, &$pet) {
            if ($request->hasFile('photo')) {
                // OPTIMIZADO: Solo genera medium (rápido), thumb en background
                $data['photo'] = $photoService->optimizeQuick($request->file('photo'), 'pets');
            }

            $pet = \App\Models\Pet::create($data);

            // Fotos múltiples ADICIONALES (MODO RÁPIDO) - máximo 3
            $sort = 1;
            foreach ($request->file('photos', []) as $file) {
                if (!$file || !$file->isValid()) continue;

                // Solo medium primero
                $mediumPath = $photoService->optimizeQuick($file, 'pets/photos');

                \App\Models\PetPhoto::create([
                    'pet_id'     => $pet->id,
                    'path'       => $mediumPath,
                    'sort_order' => $sort++,
                ]);

                // Generar thumbnail en background
                dispatch(function () use ($photoService, $mediumPath) {
                    $photoService->generateThumb($mediumPath);
                });
            }

            // FIX: Foto principal ya está en $data['photo'], NO crear PetPhoto adicional
            // La foto principal va en Pet::photo, las adicionales en PetPhoto
            if (!empty($data['photo'])) {
                // Generar thumbnail para foto principal en background
                dispatch(function () use ($photoService, $data) {
                    $photoService->generateThumb($data['photo']);
                });
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

    public function update(Request $request, Pet $pet, PetPhotoOptimizationService $photoService)
    {
        $this->authorizePetOrAdmin($pet);

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['nullable', 'string', 'max:120'],
            'zone'               => ['nullable', 'string', 'max:255'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],

            'photo'              => ['nullable', 'image', 'max:10240'],
            'photos.*'           => ['nullable', 'image', 'max:10240'],
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

        DB::transaction(function () use ($request, $pet, $data, $photoService) {
            // 1) Eliminar fotos marcadas (incluyendo todas sus versiones)
            if (!empty($data['delete_photos'])) {
                $ids = array_filter(explode(',', $data['delete_photos']));
                foreach ($pet->photos()->whereIn('id', $ids)->get() as $photo) {
                    $photoService->deleteAllVersions($photo->path);
                    $photo->delete();
                }
            }

            // 2) Reemplazar foto principal (solo actualiza campo 'photo', NO crea PetPhoto)
            if ($request->hasFile('photo')) {
                // Eliminar versiones anteriores si existían
                if ($pet->photo) {
                    $photoService->deleteAllVersions($pet->photo);
                }

                // OPTIMIZADO: Solo genera medium (rápido), thumb en background
                $mediumPath = $photoService->optimizeQuick($request->file('photo'), 'pets');
                $data['photo'] = $mediumPath;

                // FIX: NO crear PetPhoto aquí - la foto principal solo va en Pet::photo
                // Las fotos adicionales van en PetPhoto (tabla pet_photos)

                // Generar thumbnail en background
                dispatch(function () use ($photoService, $mediumPath) {
                    $photoService->generateThumb($mediumPath);
                });
            }

            // 3) Actualizar datos
            $pet->update($data);

            // 4) Guardar nuevas fotos múltiples (MODO RÁPIDO)
            $sort = (int) $pet->photos()->max('sort_order');
            foreach ($request->file('photos', []) as $file) {
                if (!$file || !$file->isValid()) continue;

                // OPTIMIZADO: Solo medium primero
                $mediumPath = $photoService->optimizeQuick($file, 'pets/photos');

                $petPhoto = PetPhoto::create([
                    'pet_id'     => $pet->id,
                    'path'       => $mediumPath,
                    'sort_order' => ++$sort,
                ]);

                // Generar thumbnail en background
                dispatch(function () use ($photoService, $mediumPath) {
                    $photoService->generateThumb($mediumPath);
                });
            }
        });

        return redirect()
            ->route('portal.pets.show', $pet)
            ->with('status', 'Mascota actualizada correctamente.');
    }


    public function destroy(Pet $pet)
    {
        $this->authorizePetOrAdmin($pet);
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
