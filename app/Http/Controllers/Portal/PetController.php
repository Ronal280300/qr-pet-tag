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
        // Si es admin y envía invitación con múltiples mascotas, usar la lógica nueva
        $sendInvitation = $request->boolean('send_invitation') && Auth::user()->is_admin;

        if ($sendInvitation && $request->has('pets')) {
            return $this->storeMultiplePetsWithInvitation($request, $qrService, $photoService);
        }

        // Flujo original para una sola mascota (sin invitación o registros directos)
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['required', 'string', 'max:120'],
            'zone'               => ['required', 'string', 'max:255'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'], // mantener por compatibilidad
            'age_years'          => [
                'nullable',
                'integer',
                'min:0',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    $years = $request->input('age_years');
                    $months = $request->input('age_months');
                    // Al menos uno debe tener un valor mayor a 0
                    if (($years === null || $years == 0) && ($months === null || $months == 0)) {
                        $fail('Debe ingresar la edad en años, meses o ambos.');
                    }
                },
            ],
            'age_months'         => ['nullable', 'integer', 'min:0', 'max:11'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['required', 'image', 'max:10240'],
            'photos.*'           => ['nullable', 'image', 'max:10240'],

            // NUEVOS CAMPOS
            'sex'            => 'nullable|in:male,female,unknown',

            // CONTACTO DE EMERGENCIA
            'has_emergency_contact'    => 'nullable|boolean',
            'emergency_contact_name'   => 'nullable|string|max:120',
            'emergency_contact_phone'  => 'nullable|string|max:20',
            'is_neutered'    => 'nullable|boolean',
            'rabies_vaccine' => 'nullable|boolean',

            // INVITACIÓN (Solo admin)
            'send_invitation'  => 'nullable|boolean',
            'pending_email'    => 'nullable|required_if:send_invitation,1|email',
            'pending_plan_id'  => 'nullable|required_if:send_invitation,1|exists:plans,id',
        ]);

        // Mascota nueva SIEMPRE sin dueño (queda para que el cliente la ligue al activar el TAG)
        $data['user_id'] = null;
        $data['is_lost'] = false;

        // Si es admin y marcó enviar invitación, configurar campos pending
        $sendInvitation = $request->boolean('send_invitation') && Auth::user()->is_admin;

        if ($sendInvitation) {
            $data['pending_email'] = $request->input('pending_email');
            $data['pending_plan_id'] = $request->input('pending_plan_id');
            $data['pending_token'] = \Illuminate\Support\Str::random(64);
            $data['is_pending_registration'] = true;
            $data['pending_sent_at'] = now();
        }

        DB::transaction(function () use ($request, &$data, $qrService, $photoService, &$pet, $sendInvitation) {
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

                // FIX: Generar thumbnail síncronamente para evitar error de serialización
                // Los thumbnails son pequeños y rápidos, no necesitan async
                $photoService->generateThumb($mediumPath);
            }

            // FIX: Foto principal ya está en $data['photo'], NO crear PetPhoto adicional
            // La foto principal va en Pet::photo, las adicionales en PetPhoto
            if (!empty($data['photo'])) {
                // Generar thumbnail para foto principal síncronamente
                $photoService->generateThumb($data['photo']);
            }

            // Generar/asegurar slug + imagen del QR
            $qr = \App\Models\QrCode::firstOrNew(['pet_id' => $pet->id]);
            $qrService->ensureSlugAndImage($qr, $pet);

            // Si es invitación, enviar email
            if ($sendInvitation) {
                try {
                    \Illuminate\Support\Facades\Mail::to($data['pending_email'])
                        ->send(new \App\Mail\PetInvitationMail($pet));
                } catch (\Exception $e) {
                    \Log::error('Error enviando invitación de mascota: ' . $e->getMessage());
                }
            }
        });

        if ($sendInvitation) {
            return redirect()
                ->route('portal.pets.show', $pet)
                ->with('status', 'Mascota creada e invitación enviada a ' . $data['pending_email']);
        }

        return redirect()
            ->route('portal.pets.show', $pet)
            ->with('status', 'Mascota creada sin dueño. Entrega el TAG al cliente para que lo active.');
    }

    /**
     * Crear múltiples mascotas con invitación (flujo nuevo para planes con múltiples mascotas)
     *
     * El formulario principal es la mascota #1, los formularios dinámicos son #2, #3, etc.
     */
    protected function storeMultiplePetsWithInvitation(Request $request, PetQrService $qrService, PetPhotoOptimizationService $photoService)
    {
        // Validar formulario principal (mascota #1)
        $mainData = $request->validate([
            'name'               => ['required', 'string', 'max:120'],
            'breed'              => ['required', 'string', 'max:120'],
            'zone'               => ['required', 'string', 'max:255'],
            'age_years'          => ['nullable', 'integer', 'min:0', 'max:50'],
            'age_months'         => ['nullable', 'integer', 'min:0', 'max:11'],
            'medical_conditions' => ['nullable', 'string', 'max:500'],
            'photo'              => ['required', 'image', 'max:10240'],
            'sex'                => 'nullable|in:male,female,unknown',
            'is_neutered'        => 'nullable|boolean',
            'rabies_vaccine'     => 'nullable|boolean',
            'has_emergency_contact'    => 'nullable|boolean',
            'emergency_contact_name'   => 'nullable|string|max:120',
            'emergency_contact_phone'  => 'nullable|string|max:20',

            // Datos de invitación
            'pending_email'    => 'required|email',
            'pending_plan_id'  => 'required|exists:plans,id',
        ]);

        // Validar mascotas adicionales (pets[1], pets[2], etc.)
        $request->validate([
            'pets'             => 'nullable|array',
            'pets.*.name'      => 'required|string|max:120',
            'pets.*.breed'     => 'required|string|max:120',
            'pets.*.zone'      => 'required|string|max:255',
            'pets.*.photo'     => 'required|image|max:10240',
            'pets.*.sex'       => 'nullable|in:male,female,unknown',
            'pets.*.age_years' => 'nullable|integer|min:0|max:50',
            'pets.*.age_months' => 'nullable|integer|min:0|max:11',
            'pets.*.is_neutered' => 'nullable|boolean',
            'pets.*.rabies_vaccine' => 'nullable|boolean',
            'pets.*.medical_conditions' => 'nullable|string|max:500',
            'pets.*.has_emergency_contact' => 'nullable|boolean',
            'pets.*.emergency_contact_name' => 'nullable|string|max:120',
            'pets.*.emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        $additionalPetsData = $request->input('pets', []);
        $pendingEmail = $request->input('pending_email');
        $pendingPlanId = $request->input('pending_plan_id');

        // Generar tokens únicos
        $pendingGroupToken = \Illuminate\Support\Str::random(64);
        $pendingToken = \Illuminate\Support\Str::random(64);

        $createdPets = [];

        DB::transaction(function () use ($request, $mainData, $additionalPetsData, $pendingEmail, $pendingPlanId, $pendingGroupToken, $pendingToken, $qrService, $photoService, &$createdPets) {

            // 1. Crear mascota principal (del formulario original)
            $mainPetData = [
                'user_id' => null,
                'is_lost' => false,
                'name' => $mainData['name'],
                'breed' => $mainData['breed'],
                'zone' => $mainData['zone'],
                'sex' => $mainData['sex'] ?? null,
                'age_years' => $mainData['age_years'] ?? 0,
                'age_months' => $mainData['age_months'] ?? 0,
                'is_neutered' => $request->boolean('is_neutered'),
                'rabies_vaccine' => $request->boolean('rabies_vaccine'),
                'medical_conditions' => $mainData['medical_conditions'] ?? null,
                'has_emergency_contact' => $request->boolean('has_emergency_contact'),
                'emergency_contact_name' => $mainData['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $mainData['emergency_contact_phone'] ?? null,

                // Campos de invitación
                'pending_email' => $pendingEmail,
                'pending_plan_id' => $pendingPlanId,
                'pending_group_token' => $pendingGroupToken,
                'pending_token' => $pendingToken, // Solo la primera tiene token
                'is_pending_registration' => true,
                'pending_sent_at' => now(),
            ];

            // Procesar foto principal
            if ($request->hasFile('photo')) {
                $mainPetData['photo'] = $photoService->optimizeQuick($request->file('photo'), 'pets');
            }

            $mainPet = \App\Models\Pet::create($mainPetData);

            // Generar thumbnail para foto principal
            if (!empty($mainPetData['photo'])) {
                $photoService->generateThumb($mainPetData['photo']);
            }

            // Generar QR code
            $qr = \App\Models\QrCode::firstOrNew(['pet_id' => $mainPet->id]);
            $qrService->ensureSlugAndImage($qr, $mainPet);

            $createdPets[] = $mainPet;

            // 2. Crear mascotas adicionales (del array dinámico)
            foreach ($additionalPetsData as $index => $petData) {
                $additionalPetData = [
                    'user_id' => null,
                    'is_lost' => false,
                    'name' => $petData['name'],
                    'breed' => $petData['breed'],
                    'zone' => $petData['zone'],
                    'sex' => $petData['sex'] ?? null,
                    'age_years' => $petData['age_years'] ?? 0,
                    'age_months' => $petData['age_months'] ?? 0,
                    'is_neutered' => isset($petData['is_neutered']) && $petData['is_neutered'] == '1',
                    'rabies_vaccine' => isset($petData['rabies_vaccine']) && $petData['rabies_vaccine'] == '1',
                    'medical_conditions' => $petData['medical_conditions'] ?? null,
                    'has_emergency_contact' => isset($petData['has_emergency_contact']) && $petData['has_emergency_contact'] == '1',
                    'emergency_contact_name' => $petData['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $petData['emergency_contact_phone'] ?? null,

                    // Campos de invitación (mismo grupo, sin token individual)
                    'pending_email' => $pendingEmail,
                    'pending_plan_id' => $pendingPlanId,
                    'pending_group_token' => $pendingGroupToken,
                    'pending_token' => null, // Solo la primera mascota tiene token
                    'is_pending_registration' => true,
                    'pending_sent_at' => now(),
                ];

                // Procesar foto principal de la mascota adicional
                if (isset($petData['photo']) && $petData['photo'] instanceof \Illuminate\Http\UploadedFile) {
                    $additionalPetData['photo'] = $photoService->optimizeQuick($petData['photo'], 'pets');
                }

                $additionalPet = \App\Models\Pet::create($additionalPetData);

                // Generar thumbnail para foto principal
                if (!empty($additionalPetData['photo'])) {
                    $photoService->generateThumb($additionalPetData['photo']);
                }

                // Generar QR code
                $qr = \App\Models\QrCode::firstOrNew(['pet_id' => $additionalPet->id]);
                $qrService->ensureSlugAndImage($qr, $additionalPet);

                $createdPets[] = $additionalPet;
            }

            // 3. Crear orden automáticamente
            $plan = \App\Models\Plan::find($pendingPlanId);
            $petsCount = count($createdPets);

            // Calcular costos
            $subtotal = $plan->price;
            $additionalPetsCost = 0;
            if ($petsCount > $plan->max_pets) {
                $extraPets = $petsCount - $plan->max_pets;
                $additionalPetsCost = $extraPets * ($plan->additional_pet_price ?? 0);
            }
            $total = $subtotal + $additionalPetsCost;

            $order = \App\Models\Order::create([
                'user_id' => null, // Se llenará cuando el cliente se registre
                'plan_id' => $pendingPlanId,
                'pets_quantity' => $petsCount,
                'subtotal' => $subtotal,
                'additional_pets_cost' => $additionalPetsCost,
                'total' => $total,
                'status' => 'pending',
                'pending_group_token' => $pendingGroupToken,
                'pending_email' => $pendingEmail,
                'admin_notes' => 'Orden creada automáticamente al enviar invitación. Mascotas: ' . collect($createdPets)->pluck('name')->join(', '),
            ]);

            // 4. Enviar email de invitación (solo una vez) con TODAS las mascotas
            try {
                \Illuminate\Support\Facades\Mail::to($pendingEmail)
                    ->send(new \App\Mail\PetInvitationMail($createdPets[0], $createdPets));
            } catch (\Exception $e) {
                \Log::error('Error enviando invitación de mascotas: ' . $e->getMessage());
            }
        });

        $count = count($createdPets);
        return redirect()
            ->route('portal.pets.index')
            ->with('status', "{$count} mascota" . ($count > 1 ? 's' : '') . " creada" . ($count > 1 ? 's' : '') . " e invitación enviada a {$pendingEmail}");
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
            'breed'              => ['required', 'string', 'max:120'],
            'zone'               => ['required', 'string', 'max:255'],
            'age'                => ['nullable', 'integer', 'min:0', 'max:50'], // mantener por compatibilidad
            'age_years'          => [
                'nullable',
                'integer',
                'min:0',
                'max:50',
                function ($attribute, $value, $fail) use ($request) {
                    $years = $request->input('age_years');
                    $months = $request->input('age_months');
                    // Al menos uno debe tener un valor mayor a 0
                    if (($years === null || $years == 0) && ($months === null || $months == 0)) {
                        $fail('Debe ingresar la edad en años, meses o ambos.');
                    }
                },
            ],
            'age_months'         => ['nullable', 'integer', 'min:0', 'max:11'],
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

                // OPTIMIZADO: Solo genera medium (rápido), thumb después
                $mediumPath = $photoService->optimizeQuick($request->file('photo'), 'pets');
                $data['photo'] = $mediumPath;

                // FIX: NO crear PetPhoto aquí - la foto principal solo va en Pet::photo
                // Las fotos adicionales van en PetPhoto (tabla pet_photos)

                // Generar thumbnail síncronamente
                $photoService->generateThumb($mediumPath);
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

                // Generar thumbnail síncronamente
                $photoService->generateThumb($mediumPath);
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
        $qr = QrCodeModel::firstOrNew(['pet_id' => $pet->id]);

        // Si el QR ya existe (tiene slug), REGENERARLO completamente
        if ($qr->exists && !blank($qr->slug)) {
            $qrService->regenerateQR($qr, $pet);
            return back()->with('status', 'QR regenerado correctamente. Nuevo código único creado.');
        }

        // Si no existe o no tiene slug, generar por primera vez
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
