{{-- resources/views/portal/pets/show.blade.php --}}
@extends('layouts.app')

@section('title', $pet->name . ' | Mascota')

@section('content')
@php
$isAdmin = (bool) (auth()->user()->is_admin ?? false);

$qr = $pet->qrCode;

$slug = $qr->slug ?? null;
$publicUrl = $slug ? route('public.pet.show', $slug) : null;

$qrImageUrl = ($qr && $qr->image && Storage::disk('public')->exists($qr->image))
? Storage::url($qr->image)
: null;

$canDownloadQr = $qrImageUrl && (auth()->id() === $pet->user_id || $isAdmin);

$photos = $pet->photos;
$mainPhotoUrl = $pet->main_photo_url;

// helpers visuales
$sexLabel = [
'male' => 'Macho',
'female' => 'Hembra',
'unknown' => 'Desconocido',
][$pet->sex ?? 'unknown'];
@endphp

<div class="row g-4">
  {{-- Columna izquierda: hero + ficha --}}
  <div class="col-12 col-lg-8">
    <div class="card card-elevated mb-3 overflow-hidden">

      {{-- HERO / carrusel (sin recortes) --}}
      <div class="position-relative">
        <div id="petPhotosCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            @if($photos->isEmpty())
            <div class="carousel-item active">
              <div class="ratio ratio-16x9 js-skel">
                <img
                  src="{{ $mainPhotoUrl }}"
                  alt="Mascota"
                  class="w-100 h-100 object-contain"
                  loading="lazy">
              </div>
            </div>
            @else
            @foreach($photos as $i => $ph)
            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
              <div class="ratio ratio-16x9 js-skel">
                <img
                  src="{{ asset('storage/'.$ph->path) }}"
                  alt="Mascota {{ $i+1 }}"
                  class="w-100 h-100 object-contain"
                  loading="lazy">
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

        {{-- Cinta con título y acciones (marcadas como .no-swipe para no disparar el slide) --}}
        <div class="hero-bar no-swipe">
          <div class="container-fluid px-3 d-flex align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <h2 class="hero-title mb-0">{{ $pet->name }}</h2>
              @if($pet->is_lost)
              <span class="chip chip-warn"><i class="fa-solid fa-triangle-exclamation me-1"></i> Reportada perdida</span>
              @endif
            </div>

            <div class="d-flex gap-2 no-swipe flex-wrap hero-actions">
              <a href="{{ route('portal.pets.edit',$pet) }}"
                class="btn btn-light btn-sm btn-compact">
                <i class="fa-solid fa-pen-to-square me-1"></i>
                <span>Editar</span>
              </a>

              @if($isAdmin)
              <form action="{{ route('portal.pets.destroy',$pet) }}" method="POST" class="pet-delete-form m-0 p-0">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm btn-compact">
                  <i class="fa-solid fa-trash-can me-1"></i>
                  <span>Eliminar</span>
                </button>
              </form>

              <button
                type="button"
                class="btn btn-primary btn-sm btn-compact"
                data-url="{{ route('portal.pets.share.facebook', $pet) }}"
                data-name="{{ $pet->name }}"
                data-page="{{ config('services.facebook.page_id') }}"
                onclick="publishToFacebook(event)">
                <i class="fa-brands fa-facebook me-1"></i> Publicar en Facebook
              </button>
              @endif
            </div>
          </div>
        </div>
      </div>

      {{-- Ficha / meta + observaciones + estado --}}
      <div class="card-body">
        {{-- Chips meta --}}
        <div class="meta mb-3">
          <span class="chip">
            <i class="fa-solid fa-dog me-1"></i>
            {{ $pet->breed ?: 'Sin raza' }}
          </span>

          {{-- sexo --}}
          <span class="chip">
            @if(($pet->sex ?? 'unknown') === 'male')
            <i class="fa-solid fa-mars me-1"></i> Macho
            @elseif(($pet->sex ?? 'unknown') === 'female')
            <i class="fa-solid fa-venus me-1"></i> Hembra
            @else
            <i class="fa-solid fa-circle-question me-1"></i> Desconocido
            @endif
          </span>

          {{-- estado de salud --}}
          <span class="chip">
            <i class="fa-solid fa-syringe me-1"></i>
            Antirrábica: {{ $pet->rabies_vaccine ? 'Sí' : 'No' }}
          </span>
          <span class="chip">
            <i class="fa-solid fa-scissors me-1"></i>
            Esterilizado: {{ $pet->is_neutered ? 'Sí' : 'No' }}
          </span>

          @if($pet->zone)
          <span class="chip"><i class="fa-solid fa-location-dot me-1"></i> {{ $pet->zone }}</span>
          @endif

          @if(!is_null($pet->age))
          <span class="chip"><i class="fa-solid fa-cake-candles me-1"></i> {{ $pet->age }} {{ Str::plural('año',$pet->age) }}</span>
          @endif

          <span class="chip"><i class="fa-solid fa-user me-1"></i> {{ optional($pet->user)->name ?: 'Sin dueño' }}</span>
        </div>

        {{-- Observaciones --}}
        <div class="card card-soft">
          <div class="card-body">
            <div class="fw-semibold mb-1">
              <i class="fa-solid fa-notes-medical me-1"></i> Observaciones
            </div>
            <div class="text-muted">
              {{ $pet->medical_conditions ?: 'No tiene observaciones registradas.' }}
            </div>
          </div>
        </div>

        {{-- Estado perdida/robada --}}
        <div class="mt-3 d-flex flex-wrap gap-2">
          <form action="{{ route('portal.pets.toggle-lost', $pet) }}" method="POST" id="toggleLostForm">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm btn-compact" id="toggleLostBtn">
              <i class="fa-solid fa-triangle-exclamation me-1"></i>
              <span class="d-none d-sm-inline">Marcar como </span>{{ $pet->is_lost ? 'Quitar pérdida' : 'Perdida/robada' }}
            </button>
          </form>

          {{-- Botón para generar la publicación (si está perdida/robada) --}}
          @if($pet->is_lost)
          <form action="{{ route('portal.pets.share-card', $pet) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-outline-danger btn-sm btn-compact">
              <i class="fa-solid fa-bullhorn me-1"></i>
              <span class="d-none d-sm-inline">Generar </span>publicación
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Columna derecha: QR + recompensa --}}
  <div class="col-12 col-lg-4">
    {{-- PREVIEW “Publicación para redes” (después de generar) --}}
    @if (session('share_card_url'))
    <div class="card card-elevated mb-3" id="shareCardPreview">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
          <h5 class="mb-0">
            <i class="fa-solid fa-bullhorn me-2 text-danger"></i>
            Publicación lista para redes
          </h5>
          <div class="d-flex gap-2">
            <a class="btn btn-primary btn-sm btn-compact"
              href="{{ session('share_card_url') }}"
              download="qr-pet-{{ $pet->id }}.png">
              <i class="fa-solid fa-download me-1"></i><span class="d-none d-sm-inline">Descargar</span><span class="d-sm-none">Descargar</span>
            </a>
            <button type="button" class="btn btn-outline-primary btn-sm btn-compact" id="btnShareCard" "
              data-url=" {{ session('share_card_url') }}"
              data-title="Mascota perdida: {{ $pet->name }}">
              <i class="fa-solid fa-share-nodes me-1"></i> Compartir
            </button>
          </div>
        </div>
        <div class="ratio ratio-4x5 bg-light rounded js-skel" style="max-width:420px">
          <img
            src="{{ session('share_card_url') }}"
            alt="Publicación de {{ $pet->name }}"
            class="w-100 h-100 object-fit-contain rounded"
            loading="lazy">
        </div>
        <small class="text-muted d-block mt-2">
          Formato 1080×1350 optimizado para historias y publicaciones.
        </small>
      </div>
    </div>
    @endif

    {{-- QR --}}
    <div class="card card-elevated mb-3">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <h5 class="card-title mb-0">QR de la mascota</h5>

          {{-- TAG visible solo para admin --}}
          @if($isAdmin && $qr && $qr->activation_code)
          <span class="badge rounded-pill text-bg-light fw-semibold">
            TAG: <span class="text-primary ms-1">{{ $qr->activation_code }}</span>
          </span>
          @endif
        </div>

        <div class="qr-preview mb-2 d-flex justify-content-center js-skel">
          @if($qrImageUrl)
          <img src="{{ $qrImageUrl }}" alt="QR" class="qr-img" loading="lazy">
          @else
          <div class="qr-placeholder text-muted small text-center w-100">
            <i class="fa-solid fa-qrcode fa-2x mb-2"></i>
            <div>Aún no se ha generado el QR.</div>
          </div>
          @endif
        </div>


        <div class="small text-muted mb-2" style="word-break: break-all;">
          @if($publicUrl)
          {{ $publicUrl }}
          @else
          <em>Genera el QR para obtener la URL pública.</em>
          @endif
        </div>

        <div class="vstack gap-2">
          @if($isAdmin)
          <form action="{{ route('portal.pets.generate-qr',$pet) }}" method="POST" class="no-swipe" data-confirm="¿Quieres generar o regenerar el QR de esta mascota?">
            @csrf
            <button class="btn btn-primary w-100 btn-compact btn-center">
              <i class="fa-solid fa-bolt me-2"></i><span class="d-none d-sm-inline">Generar / Regenerar</span><span class="d-sm-none">Generar</span> QR
            </button>
          </form>
          @endif

          <a class="btn btn-outline-secondary w-100 btn-compact btn-center {{ $canDownloadQr ? '' : 'disabled opacity-50' }}"
            href="{{ $canDownloadQr ? route('portal.pets.download-qr', $pet) : '#' }}">
            <i class="fa-solid fa-download me-2"></i> Descargar QR
          </a>

          <a class="btn btn-outline-info w-100 btn-compact btn-center {{ $publicUrl ? '' : 'disabled opacity-50' }}"
            href="{{ $publicUrl ?: '#' }}" target="_blank" rel="noopener">
            <i class="fa-solid fa-up-right-from-square me-2"></i> Ver perfil público
          </a>

          <button type="button"
            class="btn btn-outline-secondary w-100 btn-compact btn-center copy-url {{ $publicUrl ? '' : 'disabled opacity-50' }}"
            data-url="{{ $publicUrl ?? '' }}">
            <i class="fa-solid fa-link me-2"></i> Copiar URL
          </button>

          @if($isAdmin && $qr)
          <form action="{{ route('portal.pets.regen-code', $pet) }}" method="POST" class="no-swipe" data-confirm="¿Quieres regenerar el TAG de esta mascota?">
            @csrf
            <button class="btn btn-outline-warning w-100 btn-compact btn-center">
              <i class="fa-solid fa-rotate me-2"></i> Regenerar código (TAG)
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>

    {{-- RECOMPENSA --}}
    <div class="card card-elevated">
      <div class="card-body">
        <h5 class="card-title mb-3 d-flex align-items-center gap-2">
          Recompensa
          <i class="fa-solid fa-circle-info text-muted"
            style="cursor:pointer;"
            data-bs-toggle="tooltip"
            title="Activa la recompensa, define un monto mayor a 0 y un mensaje opcional."></i>
        </h5>

        <form action="{{ route('portal.pets.reward.update', $pet) }}" method="POST" id="rewardForm" class="no-swipe">
          @csrf @method('PUT')

          @php($activeVal = (int) (optional($pet->reward)->active ?? 0))
          <input type="hidden" name="active" id="rwActive" value="{{ $activeVal }}">

          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="text-muted">Mostrar recompensa en el perfil público</div>
            <div id="rwSwitch" class="switch {{ $activeVal ? 'on':'' }}" role="button" aria-pressed="{{ $activeVal ? 'true':'false' }}"></div>
          </div>

          <div class="row g-3 align-items-end">
            <div class="col-12 col-sm-5">
              <label class="form-label">Monto</label>
              <div class="input-group-modern">
                <span class="prefix">₡</span>
                <input
                  type="text"
                  inputmode="decimal"
                  pattern="[0-9.,]*"
                  name="amount"
                  id="rwAmount"
                  class="form-control modern"
                  value="{{ number_format((float) (optional($pet->reward)->amount ?? 0), 2, '.', '') }}"
                  placeholder="0.00">
              </div>
              <div class="form-text">Se requiere un monto mayor a 0 cuando está activo.</div>
            </div>

            <div class="col-12 col-sm-7" style="min-width:0">
              <label class="form-label">Mensaje</label>
              <input
                type="text"
                name="message"
                id="rwMessage"
                class="form-control modern w-100"
                maxlength="200"
                value="{{ optional($pet->reward)->message ?? 'Gracias por tu ayuda 🙏' }}"
                placeholder="Gracias por tu ayuda 🙏">
            </div>

            <div class="col-12 mt-1">
              <button type="submit" id="rwSave" class="btn btn-success w-100 btn-compact btn-center">
                <i class="fa-solid fa-floppy-disk me-1"></i> Guardar recompensa
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
  .card-elevated {
    border: 0;
    box-shadow: 0 18px 50px rgba(31, 41, 55, .08);
    border-radius: 18px
  }

  /* Botón compacto (evita “tarjeta alta”) */
  .btn-compact {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .35rem .6rem !important;
    font-size: .875rem !important;
    line-height: 1.2 !important;
    border-radius: 10px !important;
    white-space: nowrap;
    /* que no rompa palabras */
  }

  /* Contenedor de acciones en la hero */
  .hero-actions {
    justify-content: flex-end;
  }

  /* En móviles: título arriba, acciones debajo en fila compacta */
  @media (max-width: 576px) {
    .hero-bar .container-fluid {
      flex-direction: column;
      align-items: flex-start;
      gap: .5rem;
    }

    .hero-actions {
      width: 100%;
      justify-content: flex-start;
      gap: .4rem;
      flex-wrap: wrap;
    }

    .hero-actions .btn-compact {
      padding: .32rem .5rem !important;
      font-size: .82rem !important;
    }
  }

  .card-soft {
    border: 1px solid #eef1f5;
    border-radius: 14px
  }

  .hero-bar {
    z-index: 30 !important;
  }


  .hero-bar {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, .55) 70%);
    color: #fff;
    padding: 16px 0;
  }

  .hero-title {
    font-weight: 900;
    letter-spacing: .2px
  }

  .chip {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    padding: .38rem .7rem;
    background: #f5f7fb;
    border-radius: 999px;
    margin: .15rem .35rem .15rem 0;
    font-weight: 600;
    color: #263143;
    border: 1px solid #eef1f5
  }

  .chip-warn {
    background: #fff7ed;
    color: #9a3412;
    border-color: #fde7c7
  }

  .object-contain {
    object-fit: contain
  }

  :root {
    --qr-size: 200px;
  }

  @media (max-width: 576px) {
    :root {
      --qr-size: 170px
    }
  }

  .qr-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f6f8ff;
    border: 1px dashed #e3e8ef;
    border-radius: 14px;
    min-height: calc(var(--qr-size) + 26px);
    padding: .85rem
  }

  .qr-img {
    width: var(--qr-size);
    height: var(--qr-size);
    object-fit: contain;
    image-rendering: crisp-edges;
    image-rendering: pixelated
  }

  .input-group-modern {
    display: flex;
    align-items: center;
    width: 100%
  }

  .input-group-modern .prefix {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-right: 0;
    height: 48px;
    min-width: 48px;
    display: grid;
    place-items: center;
    border-radius: 12px 0 0 12px;
    font-weight: 900;
    color: #111827
  }

  .form-control.modern {
    height: 48px;
    border: 1px solid #e5e7eb;
    border-radius: 0 12px 12px 0;
    padding: 0 14px;
    width: 100%;
  }

  .form-control.modern:focus {
    border-color: #c7d2fe;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, .16)
  }

  .form-control.modern:disabled {
    background: #f5f5f7;
    opacity: .7
  }

  .switch {
    --h: 28px;
    position: relative;
    width: 56px;
    height: var(--h);
    border-radius: 999px;
    background: #e5e7eb;
    cursor: pointer;
    transition: .2s
  }

  .switch::after {
    content: "";
    position: absolute;
    top: 3px;
    left: 3px;
    width: 22px;
    height: 22px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
    transition: .2s
  }

  .switch.on {
    background: #22c55e
  }

  .switch.on::after {
    transform: translateX(28px)
  }

  /* ===== Skeleton (shimmer) ===== */
  .js-skel {
    position: relative;
    overflow: hidden;
    background: #f2f4f7;
    /* color base */
  }

  /* capa de brillo */
  .js-skel::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(110deg, #f2f4f7 8%, #e8ebf1 18%, #f2f4f7 33%);
    background-size: 200% 100%;
    animation: skel-shimmer 1.1s linear infinite;
  }

  /* ocultar imagen hasta que esté lista */
  .js-skel>img {
    opacity: 0;
    transition: opacity .25s ease;
  }

  /* cuando se carga, se muestra img y se apaga el shimmer */
  .js-skel.is-loaded::after {
    opacity: 0;
    pointer-events: none;
    animation: none;
  }

  .js-skel.is-loaded>img {
    opacity: 1;
  }

  @keyframes skel-shimmer {
    0% {
      background-position-x: 200%;
    }

    100% {
      background-position-x: -200%;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Copiar URL
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.copy-url');
    if (!btn || btn.classList.contains('disabled')) return;
    const url = btn.dataset.url || '';
    if (!url) return;

    (navigator.clipboard?.writeText(url) || Promise.resolve()).then(() => {
      const original = btn.innerHTML;
      btn.classList.remove('btn-outline-secondary');
      btn.classList.add('btn-success');
      btn.innerHTML = '<i class="fa-solid fa-check me-2"></i> Copiada';
      setTimeout(() => {
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-secondary');
        btn.innerHTML = original;
      }, 1400);
    }).catch(() => {
      alert('No se pudo copiar la URL. Copiala manualmente:\n' + url);
    });
  });

  // Tooltips Bootstrap
  (function() {
    const list = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    list.forEach(el => new bootstrap.Tooltip(el));
  })();

  // Recompensa: switch + habilitar campos + formateo
  (function() {
    const sw = document.getElementById('rwSwitch');
    const act = document.getElementById('rwActive');
    const amt = document.getElementById('rwAmount');
    const msg = document.getElementById('rwMessage');
    const save = document.getElementById('rwSave');
    const form = document.getElementById('rewardForm');

    if (!sw || !act) return;

    function isOn() {
      return act.value === '1';
    }

    function setOn(on) {
      sw.classList.toggle('on', on);
      sw.setAttribute('aria-pressed', on ? 'true' : 'false');
      act.value = on ? '1' : '0';
      setEnabled(on);
      if (on) {
        if (!parseFloat((amt.value || '').replace(',', '.'))) amt.value = '';
        amt.focus();
      }
    }

    function normalizeMoney(v) {
      v = (v || '').toString().replace(/[^\d.,]/g, '').replace(',', '.');
      const n = parseFloat(v);
      return isNaN(n) ? '' : n.toFixed(2);
    }

    function amountOk() {
      const n = parseFloat((amt.value || '').toString().replace(',', '.'));
      return !isNaN(n) && n > 0;
    }

    function setEnabled(enabled) {
      [amt, msg].forEach(el => {
        el.disabled = !enabled;
        el.style.opacity = enabled ? 1 : .65;
      });
      save.disabled = false; // permitimos desactivar
    }

    setEnabled(isOn());
    sw.addEventListener('click', () => setOn(!isOn()));
    amt.addEventListener('focus', e => {
      const raw = (e.target.value || '').replace(',', '.');
      const n = parseFloat(raw);
      if (!raw || isNaN(n) || n === 0) e.target.value = '';
      else e.target.select();
    });
    amt.addEventListener('blur', e => {
      const v = normalizeMoney(e.target.value);
      e.target.value = v || '0.00';
    });
    form.addEventListener('submit', (e) => {
      if (isOn() && !amountOk()) {
        e.preventDefault();
        amt.focus();
      }
    });
  })();

  // ====== Evitar que el carrusel deslice al interactuar con botones (Editar/Eliminar/etc) ======
  (function() {
    const stopSwipeZone = document.querySelectorAll('.no-swipe, .no-swipe *');
    const stopEvts = ['click', 'mousedown', 'mouseup', 'touchstart', 'touchmove', 'touchend', 'pointerdown', 'pointerup'];
    stopSwipeZone.forEach(el => {
      stopEvts.forEach(evt => {
        el.addEventListener(evt, e => {
          e.stopPropagation();
        }, {
          passive: false
        });
      });
    });
  })();

  // ====== Confirmar eliminación con SweetAlert ======
  (function() {
    const forms = document.querySelectorAll('.pet-delete-form');
    forms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar la mascota?',
          text: 'Esta acción no se puede deshacer.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#dc3545',
        }).then(res => {
          if (res.isConfirmed) form.submit();
        });
      });
    });
  })();

  (function() {
    const btn = document.getElementById('btnShareCard');
    if (!btn) return;
    const url = btn.dataset.url;
    const title = btn.dataset.title || document.title;

    btn.addEventListener('click', async () => {
      if (navigator.share) {
        try {
          await navigator.share({
            title,
            url
          });
        } catch (e) {
          /* cancelado */
        }
      } else {
        try {
          await navigator.clipboard.writeText(url);
          const prev = btn.innerHTML;
          btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> Enlace copiado';
          setTimeout(() => btn.innerHTML = prev, 1300);
        } catch {
          alert('Copia este enlace:\n' + url);
        }
      }
    });
  })();



  async function publishToFacebook(event) {
    const btn = event.currentTarget || event.target;
    const url = btn.dataset.url; // ruta a portal.pets.share.facebook
    const petName = btn.dataset.name || 'la mascota';
    const pageId = btn.dataset.page || ''; // opcional, para armar el link del post
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

    if (!url) return;
    if (btn.dataset.loading === '1') return; // anti-doble click

    // Confirmación
    const confirm = await Swal.fire({
      title: '¿Publicar en Facebook?',
      html: `Se publicará la ficha de <b>${petName}</b> en tu Página.`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, publicar',
      cancelButtonText: 'Cancelar'
    });
    if (!confirm.isConfirmed) return;

    btn.dataset.loading = '1';
    btn.disabled = true;

    // Timeout de seguridad (25s)
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 25000);

    try {
      Swal.fire({
        title: 'Publicando…',
        html: 'Enviando la publicación a Facebook',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrf,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        },
        credentials: 'same-origin',
        signal: controller.signal
      });

      const raw = await res.text();
      let data = null;
      try {
        data = raw ? JSON.parse(raw) : null;
      } catch {
        /* cuerpo no JSON */ }

      clearTimeout(timeoutId);
      Swal.close();

      if (!res.ok || !data || data.ok !== true) {
        const msg = (data && data.error) ||
          `HTTP ${res.status} ${res.statusText}${raw ? ' – ' + raw.slice(0, 200) : ''}`;
        return Swal.fire({
          icon: 'error',
          title: 'No se pudo publicar en Facebook.',
          text: msg,
          confirmButtonText: 'Aceptar'
        });
      }

      // Construir link a la publicación (si viene post_id)
      let fbUrl = '';
      const postId = data?.result?.post_id || data?.result?.id || '';
      if (postId && postId.includes('_')) {
        const [pid, suffix] = postId.split('_');
        fbUrl = `https://www.facebook.com/${pid}/posts/${suffix}`;
      } else if (postId && pageId) {
        const suffix = postId.split('_').pop();
        fbUrl = `https://www.facebook.com/${pageId}/posts/${suffix}`;
      }

      return Swal.fire({
        icon: 'success',
        title: `¡Publicado ${petName} en Facebook!`,
        html: fbUrl ?
          `Ver publicación:<br><a href="${fbUrl}" target="_blank" rel="noopener">${fbUrl}</a>` :
          'Se publicó correctamente.',
        confirmButtonText: 'Aceptar'
      });

    } catch (err) {
      clearTimeout(timeoutId);
      Swal.close();
      const msg = (err?.name === 'AbortError') ?
        'Se agotó el tiempo de espera. Inténtalo de nuevo.' :
        (err?.message || 'Error de red o de servidor.');
      return Swal.fire({
        icon: 'error',
        title: 'No se pudo publicar en Facebook.',
        text: msg,
        confirmButtonText: 'Aceptar'
      });
    } finally {
      btn.dataset.loading = '';
      btn.disabled = false;
    }
  }

  // si usas onclick="publishToFacebook(event)"
  window.publishToFacebook = publishToFacebook;





  // Inicializa skeletons: cuando la imagen carga, marcamos is-loaded
  (function initSkeletons() {
    const containers = document.querySelectorAll('.js-skel');
    containers.forEach(c => {
      const img = c.querySelector('img');
      if (!img) {
        c.classList.add('is-loaded');
        return;
      }

      const markLoaded = () => c.classList.add('is-loaded');
      const markError = () => c.classList.add('is-loaded'); // en error, también apaga el shimmer

      // Si ya está en caché y con dimensiones, no mostramos shimmer
      if (img.complete && img.naturalWidth > 0) {
        markLoaded();
      } else {
        img.addEventListener('load', markLoaded, {
          once: true
        });
        img.addEventListener('error', markError, {
          once: true
        });

        // Fallback por si el navegador no dispara eventos (muy raro)
        setTimeout(() => c.classList.add('is-loaded'), 10000);
      }
    });
  })();

  (function() {
    const forms = document.querySelectorAll('form[data-confirm]');
    forms.forEach(form => {
      form.addEventListener('submit', async (e) => {
        // evitar que el carousel “captura” el gesto
        e.stopPropagation();
        e.preventDefault();

        const msg = form.getAttribute('data-confirm') || '¿Confirmas la acción?';
        const res = await Swal.fire({
          title: 'Confirmar',
          text: msg,
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Sí, continuar',
          cancelButtonText: 'Cancelar'
        });
        if (res.isConfirmed) form.submit();
      }, {
        passive: false
      });
    });
  })();
</script>

@endpush