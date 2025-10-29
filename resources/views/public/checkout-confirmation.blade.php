@extends('layouts.app')

@section('title', 'Confirmación - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progreso completado -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-success">Paso 1: Plan seleccionado</span>
                    <span class="badge bg-success">Paso 2: Pago</span>
                    <span class="badge bg-success">Paso 3: Confirmación</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                </div>
            </div>

            <!-- Icono de éxito -->
            <div class="text-center mb-4">
                <div class="mb-4">
                    <div class="success-icon mx-auto">
                        <i class="fa-solid fa-circle-check text-success"></i>
                    </div>
                </div>
                <h1 class="fw-bold mb-3">¡Comprobante recibido!</h1>
                <p class="lead text-muted">
                    Gracias por tu compra. Hemos recibido tu comprobante de pago.
                </p>
            </div>

            <!-- Información del pedido -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-receipt me-2 text-primary"></i>
                        Detalles de tu pedido
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Número de pedido</small>
                                <strong class="fs-5">{{ $order->order_number }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Plan seleccionado</small>
                                <strong>{{ $order->plan->name }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Cantidad de mascotas</small>
                                <strong>{{ $order->pets_quantity }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Total pagado</small>
                                <strong class="fs-5 text-primary">₡{{ number_format($order->total, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 mb-0">
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-clock me-3 fs-4"></i>
                            <div>
                                <strong class="d-block mb-1">¿Qué sigue ahora?</strong>
                                <p class="mb-0">
                                    Verificaremos tu pago en un plazo máximo de <strong>24 horas hábiles</strong>.
                                    Una vez verificado, te contactaremos para coordinar la personalización de tus placas QR.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Siguiente paso: Info de mascotas -->
            <div class="card shadow border-0 mb-4">
                <div class="card-body p-4">
                    @php
                        $registeredPets = $order->pets ? $order->pets->count() : 0;
                        $totalPets = $order->pets_quantity;
                        $remainingPets = $totalPets - $registeredPets;
                        $allPetsRegistered = $remainingPets <= 0;
                    @endphp

                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-dog me-2 text-success"></i>
                        @if($allPetsRegistered)
                            ¡Todas tus mascotas están registradas!
                        @else
                            ¿Quieres adelantar el proceso?
                        @endif
                    </h5>

                    @if(!$allPetsRegistered)
                    <p class="text-muted mb-4">
                        Mientras verificamos tu pago, puedes empezar a registrar la información de tus mascotas
                        para agilizar el proceso de personalización de las placas.
                    </p>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Progreso de registro de mascotas -->
                    @if($totalPets > 1)
                    <div class="alert alert-primary mb-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <strong><i class="fa-solid fa-list-check me-2"></i>Progreso de registro:</strong>
                                <span class="ms-2">{{ $registeredPets }} de {{ $totalPets }} mascotas registradas</span>
                            </div>
                            <div class="progress" style="height: 8px; min-width: 150px; flex: 1; max-width: 300px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width: {{ $totalPets > 0 ? ($registeredPets / $totalPets * 100) : 0 }}%"
                                     aria-valuenow="{{ $registeredPets }}"
                                     aria-valuemin="0"
                                     aria-valuemax="{{ $totalPets }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Mostrar mascotas ya registradas para esta orden -->
                    @if($registeredPets > 0)
                    <div class="alert alert-{{ $allPetsRegistered ? 'success' : 'info' }} mb-4">
                        <strong>
                            <i class="fa-solid fa-check-circle me-2"></i>
                            @if($allPetsRegistered)
                                ¡Perfecto! Has registrado todas tus mascotas:
                            @else
                                Mascotas registradas hasta ahora:
                            @endif
                        </strong>
                        <ul class="mb-0 mt-2">
                            @foreach($order->pets as $pet)
                            <li>{{ $pet->name }}@if($pet->breed) - {{ $pet->breed }}@endif</li>
                            @endforeach
                        </ul>
                        @if(!$allPetsRegistered)
                        <div class="mt-2 pt-2 border-top">
                            <small class="text-muted">
                                <i class="fa-solid fa-info-circle me-1"></i>
                                Aún puedes registrar <strong>{{ $remainingPets }} mascota(s) más</strong>
                            </small>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="row g-3">
                        @if(!$allPetsRegistered)
                        <div class="col-md-6">
                            <div class="option-card h-100">
                                <div class="option-icon">
                                    <i class="fa-solid fa-paw"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Registrar ahora</h6>
                                <p class="text-muted small mb-3">
                                    @if($registeredPets > 0)
                                        Registra la siguiente mascota ({{ $registeredPets + 1 }} de {{ $totalPets }})
                                    @else
                                        Llena el formulario con los datos de tu{{ $totalPets > 1 ? 's' : '' }} mascota{{ $totalPets > 1 ? 's' : '' }}
                                    @endif
                                </p>
                                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#registerPetModal" id="btnRegisterPet">
                                    <i class="fa-solid fa-plus me-2"></i>
                                    @if($registeredPets > 0)
                                        Registrar mascota {{ $registeredPets + 1 }}
                                    @else
                                        Registrar mascota
                                    @endif
                                </button>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-{{ $allPetsRegistered ? '12' : '6' }}">
                            <div class="option-card h-100">
                                <div class="option-icon" style="background: #25d366;">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <h6 class="fw-bold mb-2">
                                    @if($allPetsRegistered)
                                        ¿Necesitas ayuda?
                                    @else
                                        Continuar por WhatsApp
                                    @endif
                                </h6>
                                <p class="text-muted small mb-3">
                                    @if($allPetsRegistered)
                                        Contáctanos si necesitas hacer algún cambio
                                    @else
                                        Te ayudamos paso a paso con el registro de tus mascotas
                                    @endif
                                </p>
                                <a href="https://wa.me/50670000000?text=Hola,%20quiero%20ayuda%20para%20registrar%20mis%20mascotas.%20Mi%20pedido%20es%20{{ $order->order_number }}"
                                   target="_blank"
                                   class="btn btn-success w-100">
                                    <i class="fa-brands fa-whatsapp me-2"></i> Abrir WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(!$allPetsRegistered)
                    <div class="alert alert-light mt-4 mb-0">
                        <i class="fa-solid fa-info-circle me-2 text-primary"></i>
                        <strong>Nota:</strong> No te preocupes, puedes registrar tus mascotas más tarde.
                        Te enviaremos un recordatorio por correo.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline de proceso -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Próximos pasos</h5>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker done">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Pago realizado</h6>
                                <p class="text-muted mb-0">Comprobante recibido exitosamente</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker pending">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Verificación de pago</h6>
                                <p class="text-muted mb-0">En proceso (máx. 24 horas)</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker pending">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Personalización</h6>
                                <p class="text-muted mb-0">Diseñaremos tus placas QR</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker pending">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Envío</h6>
                                <p class="text-muted mb-0">Recibirás tus placas en 3-5 días</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones finales -->
            <div class="text-center">
                <a href="{{ route('portal.dashboard') }}" class="btn btn-primary btn-lg me-2">
                    <i class="fa-solid fa-home me-2"></i> Ir al panel
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Incluir modal con formulario completo de mascota (mismo que admin) --}}
@include('public._pet-form-modal')

<style>
.success-icon {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.15));
    border-radius: 50%;
}

.success-icon i {
    font-size: 60px;
}

.option-card {
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    transition: all 0.3s ease;
}

.option-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.1);
    transform: translateY(-4px);
}

.option-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary), var(--brand-900));
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: white;
    font-size: 28px;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 12px;
    bottom: 12px;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    margin-bottom: 24px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -40px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    z-index: 1;
}

.timeline-marker.done {
    background: linear-gradient(135deg, #10b981, #059669);
}

.timeline-marker.pending {
    background: #9ca3af;
}

.timeline-content h6 {
    margin-bottom: 4px;
}

.timeline-content p {
    font-size: 14px;
}

/* ===== ESTILOS DEL FORMULARIO DE MASCOTA (mismo que admin) ===== */
.section-card {
    border: 1px solid #eef1f5;
    border-radius: 16px;
    padding: 18px;
    margin-bottom: 18px;
    background: #fff;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.section-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    font-size: 18px;
}

.section-title {
    font-weight: 800;
    margin: 0;
    font-size: 1.1rem;
}

.section-sub {
    color: #6b7280;
    font-size: .95rem;
}

.form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 8px 0;
}

.ft-switch {
    position: relative;
    display: inline-flex;
    width: 52px;
    height: 30px;
    flex: 0 0 auto;
    cursor: pointer;
}

.ft-switch input {
    position: absolute;
    inline-size: 100%;
    block-size: 100%;
    opacity: 0;
    margin: 0;
    cursor: pointer;
}

.ft-switch .track {
    position: relative;
    inline-size: 100%;
    block-size: 100%;
    background: #e5e7eb;
    border-radius: 999px;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, .12);
    transition: background .2s ease;
}

.ft-switch .thumb {
    position: absolute;
    inset-block-start: 50%;
    inset-inline-start: 3px;
    transform: translateY(-50%);
    inline-size: 24px;
    block-size: 24px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
    transition: left .2s ease, inset-inline-start .2s ease;
}

.ft-switch input:checked+.track {
    background: #2563eb;
}

.ft-switch input:checked+.track .thumb {
    inset-inline-start: calc(100% - 27px);
}

@media (max-width: 480px) {
    .ft-switch {
        width: 46px;
        height: 26px;
    }
    .ft-switch .thumb {
        inline-size: 20px;
        block-size: 20px;
        inset-inline-start: 3px;
    }
    .ft-switch input:checked+.track .thumb {
        inset-inline-start: calc(100% - 23px);
    }
}

.segmented {
    display: inline-grid;
    grid-auto-flow: column;
    gap: 6px;
    background: #f6f7fb;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid #eef1f5;
}

.segmented .seg {
    display: none;
}

.segmented label {
    padding: .45rem .8rem;
    border-radius: 10px;
    cursor: pointer;
    user-select: none;
    color: #374151;
    background: transparent;
}

.segmented .seg:checked+label {
    background: #115DFC;
    color: #fff;
    font-weight: 700;
}

.input-icon {
    position: relative;
}

.input-icon>i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aa0aa;
}

.input-icon>.form-control,
.input-icon>.form-select {
    padding-left: 40px;
}

.photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: .75rem;
}

.photos-grid .ph {
    position: relative;
    border: 1px solid #e5e7eb;
    border-radius: .5rem;
    overflow: hidden;
    background: #f8fafc;
    aspect-ratio: 1 / 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.photos-grid .ph img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photos-grid .ph .ph-remove {
    position: absolute;
    top: .35rem;
    right: .35rem;
    border: 0;
    border-radius: 999px;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, .55);
    color: #fff;
}

.photo-uploader {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 12px;
    align-items: center;
}

.photo-uploader__preview {
    border: 1px dashed #e5e7eb;
    border-radius: 12px;
    background: #f8fafc;
    min-height: 160px;
    display: grid;
    place-items: center;
    position: relative;
    overflow: hidden;
}

.photo-uploader__preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-uploader__preview.is-dragover {
    outline: 2px dashed #2563eb;
    outline-offset: -8px;
}

.photo-uploader__overlay {
    color: #9aa0aa;
    font-size: .95rem;
    padding: 6px 10px;
    text-align: center;
}

.photo-uploader__actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(() => {
    // ===== Observaciones toggle
    const $noMedical = document.getElementById('no-medical');
    const $medical = document.getElementById('medical_conditions');

    function toggleMedical() {
        if ($noMedical.checked) {
            $medical.value = '';
            $medical.setAttribute('disabled', 'disabled');
        } else {
            $medical.removeAttribute('disabled');
        }
    }
    $noMedical.addEventListener('change', toggleMedical);
    toggleMedical();
})();

// ===== Cascada CR provincias/cantones/distritos
(() => {
    const API = 'https://ubicaciones.paginasweb.cr';
    const $prov = document.getElementById('cr-province');
    const $cant = document.getElementById('cr-canton');
    const $dist = document.getElementById('cr-district');
    const $zone = document.getElementById('zone');
    const $zonePreview = document.getElementById('zone-preview');

    async function getJSON(path) {
        const r = await fetch(`${API}${path}`);
        if (!r.ok) throw 0;
        return await r.json();
    }

    function fillSelect($sel, map, placeholder) {
        $sel.innerHTML = `<option value="">${placeholder}</option>`;
        for (const [id, name] of Object.entries(map)) {
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = name;
            $sel.appendChild(opt);
        }
    }

    function setZone() {
        const pName = $prov.options[$prov.selectedIndex]?.text || '';
        const cName = $cant.options[$cant.selectedIndex]?.text || '';
        const dName = $dist.options[$dist.selectedIndex]?.text || '';
        if (pName && cName && dName) {
            const z = `${dName}, ${cName}, ${pName}`;
            $zone.value = z;
            $zonePreview.textContent = z;
        } else {
            $zone.value = '';
            $zonePreview.textContent = '—';
        }
    }

    (async () => {
        try {
            const provincias = await getJSON('/provincias.json');
            fillSelect($prov, provincias, 'Provincia');
            $prov.disabled = false;
        } catch (e) {
            const wrap = $prov.closest('.row');
            wrap.outerHTML = `
            <div class="col-12">
                <div class="alert alert-warning small mb-2">No se pudo cargar la lista de ubicaciones. Ingresa manualmente la zona.</div>
                <input class="form-control" placeholder="Ej: San Juan, Grecia, Alajuela"
                       oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
            </div>`;
        }
    })();

    $prov.addEventListener('change', async () => {
        $cant.disabled = true;
        $dist.disabled = true;
        $dist.innerHTML = `<option value="">Distrito</option>`;
        setZone();
        if (!$prov.value) {
            $cant.innerHTML = `<option value="">Cantón</option>`;
            return;
        }
        const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
        fillSelect($cant, cantones, 'Cantón');
        $cant.disabled = false;
    });

    $cant.addEventListener('change', async () => {
        $dist.disabled = true;
        $dist.innerHTML = `<option value="">Distrito</option>`;
        setZone();
        if (!$prov.value || !$cant.value) return;
        const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
        fillSelect($dist, distritos, 'Distrito');
        $dist.disabled = false;
    });

    $dist.addEventListener('change', setZone);
})();

// ===== Uploader principal (legacy)
(function() {
    const input = document.getElementById('photo');
    const preview = document.getElementById('photoPreview');
    const drop = document.getElementById('photoDrop');
    const clear = document.getElementById('btnClearPhoto');
    const form = document.getElementById('checkout-pet-form');
    const submit = form.querySelector('button[type="submit"]');

    function hasMain() {
        return !!preview.src && !preview.classList.contains('d-none');
    }

    function syncSubmit() {
        const ok = hasMain();
        submit.disabled = !ok;
        submit.classList.toggle('disabled', !ok);
        submit.style.pointerEvents = ok ? '' : 'none';
        submit.style.opacity = ok ? '' : '.65';
    }

    function show(file) {
        if (!file) return;
        const url = URL.createObjectURL(file);
        preview.src = url;
        preview.classList.remove('d-none');
        drop.classList.remove('is-dragover');
        syncSubmit();
    }

    input.addEventListener('change', e => show(e.target.files[0]));
    ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => {
        e.preventDefault();
        drop.classList.add('is-dragover');
    }));
    ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => {
        e.preventDefault();
        drop.classList.remove('is-dragover');
    }));
    drop.addEventListener('drop', e => {
        const f = e.dataTransfer.files && e.dataTransfer.files[0];
        if (f) {
            input.files = e.dataTransfer.files;
            show(f);
        }
    });
    clear.addEventListener('click', () => {
        preview.src = '';
        preview.classList.add('d-none');
        input.value = '';
        syncSubmit();
    });

    form.addEventListener('submit', (e) => {
        if (!hasMain()) {
            e.preventDefault();
        }
    });
    syncSubmit();
})();

// ===== Previews de fotos múltiples + LÍMITE 3
(function() {
    const MAX = 3;
    const input = document.getElementById('photos');
    const grid = document.getElementById('photosPreviewGrid');
    const btnClear = document.getElementById('btnClearPhotos');
    let filesBuffer = [];

    function refreshGrid() {
        grid.innerHTML = '';
        if (filesBuffer.length === 0) {
            grid.classList.add('d-none');
            btnClear.classList.add('d-none');
            return;
        }
        grid.classList.remove('d-none');
        btnClear.classList.remove('d-none');

        filesBuffer.forEach((file, idx) => {
            const url = URL.createObjectURL(file);
            const cell = document.createElement('div');
            cell.className = 'ph';
            const img = document.createElement('img');
            img.src = url;
            img.alt = `Foto ${idx+1}`;
            const rm = document.createElement('button');
            rm.type = 'button';
            rm.className = 'ph-remove';
            rm.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            rm.addEventListener('click', () => removeAt(idx));
            cell.appendChild(img);
            cell.appendChild(rm);
            grid.appendChild(cell);
        });
    }

    function applyBufferToInput() {
        const dt = new DataTransfer();
        filesBuffer.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    function removeAt(i) {
        filesBuffer.splice(i, 1);
        applyBufferToInput();
        refreshGrid();
    }

    input.addEventListener('change', (e) => {
        const incoming = Array.from(e.target.files || []);
        const totalIfAdded = filesBuffer.length + incoming.length;
        if (totalIfAdded > MAX) {
            const allowed = Math.max(0, MAX - filesBuffer.length);
            Swal.fire({
                icon: 'warning',
                title: 'Máximo 3 fotos adicionales',
                text: `Puedes añadir ${allowed} foto(s) más.`,
                confirmButtonText: 'Entendido'
            });
            if (allowed > 0) filesBuffer = filesBuffer.concat(incoming.slice(0, allowed));
        } else {
            filesBuffer = filesBuffer.concat(incoming);
        }
        applyBufferToInput();
        refreshGrid();
        input.value = '';
    });

    btnClear.addEventListener('click', () => {
        filesBuffer = [];
        input.value = '';
        refreshGrid();
    });

    document.getElementById('checkout-pet-form').addEventListener('submit', (e) => {
        if (filesBuffer.length > MAX) e.preventDefault();
    });
})();

// ===== Auto-abrir modal si hay mascotas pendientes y se acaba de registrar una
(() => {
    @if(session('success') && !$allPetsRegistered)
        // Pequeño delay para que el usuario vea el mensaje de éxito primero
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('registerPetModal'));
            modal.show();
        }, 2000);
    @endif

    // Resetear formulario cuando se cierra el modal
    const modal = document.getElementById('registerPetModal');
    modal.addEventListener('hidden.bs.modal', () => {
        const form = document.getElementById('checkout-pet-form');
        form.reset();

        // Limpiar preview de foto principal
        const photoPreview = document.getElementById('photoPreview');
        const photoDrop = document.getElementById('photoDrop');
        if (photoPreview) {
            photoPreview.src = '';
            photoPreview.classList.add('d-none');
        }

        // Limpiar fotos múltiples
        const grid = document.getElementById('photosPreviewGrid');
        const btnClearPhotos = document.getElementById('btnClearPhotos');
        if (grid) {
            grid.innerHTML = '';
            grid.classList.add('d-none');
        }
        if (btnClearPhotos) {
            btnClearPhotos.classList.add('d-none');
        }

        // Resetear textarea de observaciones
        const medicalTextarea = document.getElementById('medical_conditions');
        if (medicalTextarea) {
            medicalTextarea.removeAttribute('disabled');
        }

        // Resetear zone preview
        const zonePreview = document.getElementById('zone-preview');
        if (zonePreview) {
            zonePreview.textContent = '—';
        }
    });
})();
</script>

@endsection
