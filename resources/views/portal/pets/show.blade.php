{{-- resources/views/portal/pets/show.blade.php --}}
@extends('layouts.app')

@section('title', $pet->name . ' | Mascota')

@section('content')
@php
    /** Vars de ayuda */
    $isAdmin = (bool) (auth()->user()->is_admin ?? false);

    // Relación del QR de la mascota
    $qr = $pet->qrCode; // asegúrate que existe $pet->qrCode()

    // Slug y URL pública: SOLO si ya existe el slug (se crea cuando generas el QR)
    $slug = $qr->slug ?? null;
    $publicUrl = $slug ? route('public.pet.show', $slug) : null;

    // Imagen del QR si fue generada
    $qrImageUrl = ($qr && $qr->image && Storage::disk('public')->exists($qr->image))
        ? Storage::url($qr->image)
        : null;

    // ¿Puede descargar QR? — dueño o admin
    $canDownloadQr = $qrImageUrl && (auth()->id() === $pet->user_id || $isAdmin);

    // === NUEVO: fotos múltiples ===
    $photos = $pet->photos;              // colección (puede estar vacía)
    $mainPhotoUrl = $pet->main_photo_url; // accesor del modelo (primera múltiple | 'photo' legacy | placeholder)
@endphp
<div class="row g-4">
    {{-- Columna izquierda: foto y ficha --}}
    <div class="col-12 col-lg-8">
        <div class="card card-elevated mb-3">
            {{-- === NUEVO: Carrusel si hay varias fotos (o 1), sin recortes === --}}
            <div class="rounded-top overflow-hidden">
                <div id="petPhotosCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @if($photos->isEmpty())
                            <div class="carousel-item active">
                                <div class="ratio ratio-16x9">
                                    <img src="{{ $mainPhotoUrl }}" class="w-100 h-100 object-contain bg-light" alt="Mascota">
                                </div>
                            </div>
                        @else
                            @foreach($photos as $i => $ph)
                                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                    <div class="ratio ratio-16x9">
                                        <img src="{{ asset('storage/'.$ph->path) }}" class="w-100 h-100 object-contain bg-light" alt="Mascota {{ $i+1 }}">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if($photos->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#petPhotosCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#petPhotosCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    @endif
                </div>
            </div>
            {{-- === FIN carrusel === --}}

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h2 class="mb-2 fw-bold">{{ $pet->name }}</h2>

                    <div class="d-flex gap-2">
                        <a href="{{ route('portal.pets.edit',$pet) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa-solid fa-pen-to-square me-1"></i> Editar
                        </a>

                        @if($isAdmin)
                            <form action="{{ route('portal.pets.destroy',$pet) }}" method="POST" onsubmit="return confirmDelete(event)">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fa-solid fa-trash-can me-1"></i> Eliminar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="meta mb-3">
                    @if($pet->breed)
                        <span class="chip"><i class="fa-solid fa-dog me-1"></i> {{ $pet->breed }}</span>
                    @else
                        <span class="chip"><i class="fa-solid fa-dog me-1"></i> No definida</span>
                    @endif

                    @if($pet->zone)
                        <span class="chip"><i class="fa-solid fa-location-dot me-1"></i> {{ $pet->zone }}</span>
                    @endif

                    @if(!is_null($pet->age))
                        <span class="chip"><i class="fa-solid fa-cake-candles me-1"></i> {{ $pet->age }} {{ Str::plural('año',$pet->age) }}</span>
                    @endif

                    <span class="chip"><i class="fa-solid fa-user me-1"></i> {{ optional($pet->user)->name }}</span>
                </div>

                <div class="card card-soft">
                    <div class="card-body">
                        <div class="fw-semibold mb-1">
                            <i class="fa-solid fa-notes-medical me-1"></i> Condiciones médicas
                        </div>
                        <div class="text-muted">
                            {{ $pet->medical_conditions ?: 'No tiene condiciones médicas registradas.' }}
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <form action="{{ route('portal.pets.toggle-lost', $pet) }}" method="POST" class="d-inline" id="toggleLostForm">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm" id="toggleLostBtn">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                            {{ $pet->is_lost ? 'Quitar marca de perdida/robada' : 'Marcar como perdida/robada' }}
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Columna derecha: QR + recompensa --}}
    <div class="col-12 col-lg-4">
        <div class="card card-elevated mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title mb-0">QR de la mascota</h5>

                    {{-- El TAG solo lo ve el admin --}}
                    @if($isAdmin && $qr && $qr->activation_code)
                        <span class="badge rounded-pill text-bg-light fw-semibold">
                            TAG: <span class="text-primary ms-1">{{ $qr->activation_code }}</span>
                        </span>
                    @endif
                </div>

                {{-- PREVISUALIZACIÓN DEL QR --}}
                <div class="qr-preview mb-2 d-flex justify-content-center">
                    @if($qrImageUrl)
                        <img src="{{ $qrImageUrl }}" alt="QR" class="qr-img">
                    @else
                        <div class="qr-placeholder text-muted small text-center">
                            <i class="fa-solid fa-qrcode fa-2x mb-2"></i>
                            <div>Aún no se ha generado el QR.</div>
                        </div>
                    @endif
                </div>

                {{-- URL pública (solo si hay slug) --}}
                <div class="small text-muted mb-2" style="word-break: break-all;">
                    @if($publicUrl)
                        {{ $publicUrl }}
                    @else
                        <em>Genera el QR para obtener la URL pública.</em>
                    @endif
                </div>

                {{-- Acciones --}}
                <div class="vstack gap-2">
                    @if($isAdmin)
                        <form action="{{ route('portal.pets.generate-qr',$pet) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary w-100">
                                <i class="fa-solid fa-qrcode me-2"></i> Generar / Regenerar QR
                            </button>
                        </form>
                    @endif

                    <a class="btn btn-outline-secondary w-100 {{ $canDownloadQr ? '' : 'disabled opacity-50' }}"
                       href="{{ $canDownloadQr ? route('portal.pets.download-qr', $pet) : '#' }}">
                        <i class="fa-solid fa-download me-2"></i> Descargar QR
                    </a>

                    <a class="btn btn-outline-info w-100 {{ $publicUrl ? '' : 'disabled opacity-50' }}"
                       href="{{ $publicUrl ?: '#' }}" target="_blank" rel="noopener">
                        <i class="fa-solid fa-up-right-from-square me-2"></i> Ver perfil público
                    </a>

                    <button type="button"
                            class="btn btn-outline-secondary w-100 copy-url {{ $publicUrl ? '' : 'disabled opacity-50' }}"
                            data-url="{{ $publicUrl ?? '' }}">
                        <i class="fa-solid fa-link me-2"></i> Copiar URL
                    </button>

                    {{-- Regenerar código/tag: solo admin --}}
                    @if($isAdmin && $qr)
                        <form action="{{ route('portal.pets.regen-code', $pet) }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-warning w-100">
                                <i class="fa-solid fa-rotate me-2"></i> Regenerar código (TAG)
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

      {{-- ======================= RECOMPENSA ======================= --}}
<div class="card shadow-sm">
  <div class="card-body">
    <h5 class="card-title mb-3 d-flex align-items-center gap-2">
      Recompensa
      <i class="fa-solid fa-circle-info text-muted"
         style="cursor:pointer;"
         data-bs-toggle="tooltip"
         title="Para activar la recompensa, selecciona 'Sí' y define un monto mayor a 0.">
      </i>
    </h5>

    <form action="{{ route('portal.pets.reward.update', $pet) }}" method="POST" id="rewardForm">
      @csrf
      @method('PUT')

      <div class="row g-3 align-items-end">
        <div class="col-12 col-sm-4">
          <label class="form-label">Activa</label>
          <select name="active" id="rwActive" class="form-select">
            <option value="0" {{ optional($pet->reward)->active ? '' : 'selected' }}>No</option>
            <option value="1" {{ optional($pet->reward)->active ? 'selected' : '' }}>Sí</option>
          </select>
        </div>

        <div class="col-12 col-sm-4">
          <label class="form-label">Monto</label>
          <input
            type="number"
            step="0.01"
            min="0"
            name="amount"
            id="rwAmount"
            class="form-control"
            value="{{ number_format((float) (optional($pet->reward)->amount ?? 0), 2, '.', '') }}"
          >
        </div>

        <div class="col-12">
          <label class="form-label">Mensaje</label>
          <input
            type="text"
            name="message"
            id="rwMessage"
            class="form-control"
            maxlength="200"
            value="{{ optional($pet->reward)->message }}"
            placeholder="Gracias por tu ayuda 🙏"
          >
        </div>

        <div class="col-12 mt-2">
          <button type="submit" id="rwSave" class="btn btn-success">
            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar recompensa
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const form   = document.getElementById('rewardForm');
  const active = document.getElementById('rwActive');
  const amount = document.getElementById('rwAmount');
  const save   = document.getElementById('rwSave');

  function isActive(){ return String(active.value) === '1'; }
  function amountOk(){
    const v = parseFloat((amount.value || '').toString().replace(',', '.'));
    return !isNaN(v) && v > 0;
  }

  let canDeactivateNow = isActive() && amountOk();

  function setEnabled(ok){
    save.disabled = !ok;
    save.classList.toggle('disabled', !ok);
    save.style.pointerEvents = ok ? '' : 'none';
    save.style.opacity = ok ? '' : '.65';
  }

  function refresh(){
    if (isActive() && amountOk()) canDeactivateNow = true;
    const ok = isActive() ? amountOk() : canDeactivateNow;
    setEnabled(ok);
  }

  refresh();
  active.addEventListener('change', refresh);
  amount.addEventListener('input', refresh);

  form.addEventListener('submit', (e) => {
    if (isActive() && !amountOk()) {
      e.preventDefault();
      return false;
    }
  });
})();

// inicializar tooltips Bootstrap 5
document.addEventListener('DOMContentLoaded', () => {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
});
</script>
@endpush

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
    .card-elevated { border: 0; box-shadow: 0 10px 30px rgba(31, 41, 55, .06); }
    .card-soft { border: 1px solid #eef1f5 }
    .chip { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem .7rem; background: #f5f7fb; border-radius: 999px; margin-right: .35rem }
    .object-cover { object-fit: cover }

    /* NUEVO: para que el carrusel no recorte imágenes */
    .object-contain { object-fit: contain }

    /* Tamaño del QR responsive */
    :root { --qr-size: 180px; }
    @media (max-width: 576px) { :root { --qr-size: 150px; } }

    .qr-preview {
        display: flex; align-items: center; justify-content: center;
        background: #f5f7fb; border: 1px dashed #e3e8ef; border-radius: 1rem;
        padding: .5rem; min-height: calc(var(--qr-size) + 20px);
    }
    .qr-img { width: var(--qr-size); height: var(--qr-size); object-fit: contain; image-rendering: crisp-edges; image-rendering: pixelated; }
</style>
@endpush

@push('scripts')
<script>
    // Copiar URL (sin JS inline)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.copy-url');
        if (!btn || btn.classList.contains('disabled')) return;

        const url = btn.dataset.url || '';
        if (!url) return;

        (navigator.clipboard?.writeText(url) || Promise.resolve()).then(() => {
            const original = btn.innerHTML;
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="fa-solid fa-check me-2"></i> Copiado';
            setTimeout(() => {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
                btn.innerHTML = original;
            }, 1500);
        }).catch(() => {
            alert('No se pudo copiar la URL. Copiala manualmente:\n' + url);
        });
    });

    // Confirmación de borrado (simple)
    function confirmDelete(e) {
        e = e || window.event;
        if (!confirm('¿Eliminar la mascota? Esta acción no se puede deshacer.')) {
            e.preventDefault();
            return false;
        }
        return true;
    }
</script>
@endpush
