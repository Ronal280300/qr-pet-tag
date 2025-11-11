{{-- resources/views/public/pet.blade.php - OPTIMIZED --}}
@extends('layouts.public')
@section('title', $pet->name)

@php
  use Illuminate\Support\Facades\Storage;

  $photosRel = method_exists($pet,'photos') ? $pet->photos : collect();
  $gallery   = collect();

  if ($photosRel && $photosRel->count() > 0) {
      $gallery = $photosRel->map(fn($ph) => Storage::url($ph->path));
  } elseif ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
      $gallery = collect([ Storage::url($pet->photo) ]);
  } else {
      $gallery = collect(['https://placehold.co/1200x1200?text='.urlencode($pet->name)]);
  }

  $mainPhoto = $gallery->first();

  $ownerName  = optional($owner)->name;
  $ownerPhone = optional($owner)->phone;
  $digits     = preg_replace('/\D+/', '', (string) $ownerPhone);
  $hasWhats   = !empty($digits);

  $zoneForMaps = $pet->full_location ?: ($pet->zone ?: null);
  $mapsUrl     = $zoneForMaps ? ('https://www.google.com/maps/search/?api=1&query=' . urlencode($zoneForMaps)) : null;

  $sex = $pet->sex ?? 'unknown';
  $sexIcon = $sex === 'male' ? 'fa-mars' : ($sex === 'female' ? 'fa-venus' : 'fa-circle-question');
  $sexText = $sex === 'male' ? 'Macho' : ($sex === 'female' ? 'Hembra' : 'Sexo N/D');
  $sexColor = $sex === 'male' ? '#60a5fa' : ($sex === 'female' ? '#f472b6' : '#94a3b8');

  $neut   = (bool) ($pet->is_neutered ?? false);
  $rabies = (bool) ($pet->rabies_vaccine ?? false);

  // ===== CONTACTO DE EMERGENCIA =====
  $hasEmergency = (bool) ($pet->has_emergency_contact ?? false);
  $emergencyName = $hasEmergency ? ($pet->emergency_contact_name ?? null) : null;
  $emergencyPhone = $hasEmergency ? ($pet->emergency_contact_phone ?? null) : null;
  $emergencyDigits = $emergencyPhone ? preg_replace('/\D+/', '', (string) $emergencyPhone) : '';
  $hasEmergencyWhats = !empty($emergencyDigits);

  $shareUrl = url()->current();
  $slug = $slug ?? ($pet->qrCode->slug ?? null);

  $species = $pet->species ?? 'dog';
  $speciesText = $species === 'dog' ? 'Perro' : ($species === 'cat' ? 'Gato' : 'Otra especie');
  $speciesIcon = $species === 'dog' ? 'fa-dog' : ($species === 'cat' ? 'fa-cat' : 'fa-paw');

  $size = $pet->size ?? null;
  $sizeText = $size === 'small' ? 'Pequeño' : ($size === 'medium' ? 'Mediano' : ($size === 'large' ? 'Grande' : 'N/D'));

  $color = $pet->color ?? null;
  $medicalConditions = $pet->medical_conditions ?? null;
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pet-profile.css') }}">
@endpush

@section('content')

{{-- ===== HERO FULLSCREEN ===== --}}
<section class="pet-hero-full">
  <img src="{{ $mainPhoto }}"
       alt="{{ $pet->name }}"
       class="pet-hero-image"
       loading="eager"
       fetchpriority="high">
  <div class="pet-hero-overlay"></div>

  <div class="pet-hero-content">
    <h1 class="pet-hero-name">{{ $pet->name }}</h1>

    <div class="pet-hero-meta">
      <span class="pet-meta-tag">
        <i class="fa-solid {{ $sexIcon }}" style="color: {{ $sexColor }}"></i>
        {{ $sexText }}
      </span>

      <span class="pet-meta-tag">
        <i class="fa-solid {{ $speciesIcon }}"></i>
        {{ $speciesText }}
      </span>

      @if($pet->breed)
      <span class="pet-meta-tag">
        <i class="fa-solid fa-bone"></i>
        {{ $pet->breed }}
      </span>
      @endif

      @if($pet->age !== null)
      <span class="pet-meta-tag">
        <i class="fa-solid fa-cake-candles"></i>
        {{ $pet->age }} {{ Str::plural('año', $pet->age) }}
      </span>
      @endif
    </div>
  </div>

  <div class="pet-hero-scroll">
    <div class="pet-scroll-indicator"></div>
  </div>
</section>

{{-- ===== ALERT BANNERS ===== --}}
@if($pet->is_lost || optional($pet->reward)->active)
<section class="pet-alert-section">
  @if($pet->is_lost)
  <div class="pet-alert-card pet-alert-lost pet-reveal">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <div>
      <div class="pet-alert-title">¡Mascota reportada como perdida!</div>
      <div class="pet-alert-text">Si tienes información sobre su paradero, contacta a su dueño de inmediato.</div>
    </div>
  </div>
  @endif

  @if(optional($pet->reward)->active)
  <div class="pet-alert-card pet-alert-reward pet-reveal">
    <i class="fa-solid fa-medal"></i>
    <div>
      <div class="pet-alert-title">
        Recompensa activa
        @if(optional($pet->reward)->amount)
          - ₡{{ number_format((float)optional($pet->reward)->amount, 2) }}
        @endif
      </div>
      @if(optional($pet->reward)->message)
      <div class="pet-alert-text">{{ optional($pet->reward)->message }}</div>
      @endif
    </div>
  </div>
  @endif
</section>
@endif

{{-- ===== MAIN CONTENT ===== --}}
<section class="pet-content-wrapper">

  <div class="pet-bento-grid">

    {{-- INFORMACIÓN --}}
    <div class="pet-bento-item pet-bento-info pet-reveal">
      <h2 class="pet-bento-title">
        <i class="fa-solid fa-paw"></i>
        Información
      </h2>

      <div class="pet-info-list">
        @if($pet->breed)
        <div class="pet-info-row">
          <i class="fa-solid fa-bone"></i>
          <div>
            <div class="pet-info-label">Raza</div>
            <div class="pet-info-value">{{ $pet->breed }}</div>
          </div>
        </div>
        @endif

        <div class="pet-info-row">
          <i class="fa-solid {{ $sexIcon }}"></i>
          <div>
            <div class="pet-info-label">Sexo</div>
            <div class="pet-info-value">{{ $sexText }}</div>
          </div>
        </div>

        @if($pet->age !== null)
        <div class="pet-info-row">
          <i class="fa-solid fa-cake-candles"></i>
          <div>
            <div class="pet-info-label">Edad</div>
            <div class="pet-info-value">{{ $pet->age }} {{ Str::plural('año', $pet->age) }}</div>
          </div>
        </div>
        @endif

        @if($size)
        <div class="pet-info-row">
          <i class="fa-solid fa-ruler-vertical"></i>
          <div>
            <div class="pet-info-label">Tamaño</div>
            <div class="pet-info-value">{{ $sizeText }}</div>
          </div>
        </div>
        @endif

        @if($color)
        <div class="pet-info-row">
          <i class="fa-solid fa-palette"></i>
          <div>
            <div class="pet-info-label">Color</div>
            <div class="pet-info-value">{{ $color }}</div>
          </div>
        </div>
        @endif

        @if($ownerName)
        <div class="pet-info-row">
          <i class="fa-solid fa-user"></i>
          <div>
            <div class="pet-info-label">Dueño</div>
            <div class="pet-info-value">{{ $ownerName }}</div>
          </div>
        </div>
        @endif
      </div>
    </div>

    {{-- CONTACTO PRINCIPAL --}}
    <div class="pet-bento-item pet-bento-contact pet-contact-primary pet-reveal">
      <h2 class="pet-bento-title">
        <i class="fa-solid fa-phone"></i>
        Contacto Principal
      </h2>

      <p style="opacity: 0.9; margin-bottom: 20px;">
        Contacta al dueño de {{ $pet->name }} si lo has encontrado o tienes información.
      </p>

      <div class="pet-contact-buttons">
        <a href="{{ $hasWhats ? 'https://wa.me/'.$digits : '#' }}"
           class="pet-btn-contact pet-btn-whatsapp {{ $hasWhats ? '' : 'disabled' }}"
           target="_blank" rel="noopener"
           aria-label="Contactar por WhatsApp">
          <i class="fa-brands fa-whatsapp"></i>
          WhatsApp
        </a>

        @if($ownerPhone)
        <a href="tel:{{ $digits }}"
           class="pet-btn-contact pet-btn-call"
           aria-label="Llamar al dueño">
          <i class="fa-solid fa-phone"></i>
          Llamar ahora
        </a>
        @endif

        <button class="pet-btn-contact pet-btn-secondary pet-share-btn"
                aria-label="Compartir perfil">
          <i class="fa-solid fa-share-nodes"></i>
          Compartir
        </button>
      </div>
    </div>

    {{-- CONTACTO EMERGENCIA --}}
    @if($hasEmergency && $emergencyName && $emergencyPhone)
    <div class="pet-bento-item pet-bento-emergency pet-contact-emergency pet-reveal">
      <div class="pet-emergency-badge">
        <i class="fa-solid fa-user-nurse"></i>
        Contacto de Emergencia
      </div>

      <h2 class="pet-bento-title">
        <i class="fa-solid fa-phone-volume"></i>
        {{ $emergencyName }}
      </h2>

      <p style="opacity: 0.9; margin-bottom: 20px;">
        Si el dueño no responde, contacta a este número de emergencia.
      </p>

      <div class="pet-contact-buttons">
        <a href="{{ $hasEmergencyWhats ? 'https://wa.me/'.$emergencyDigits : '#' }}"
           class="pet-btn-contact pet-btn-whatsapp {{ $hasEmergencyWhats ? '' : 'disabled' }}"
           target="_blank" rel="noopener"
           aria-label="Contactar emergencia por WhatsApp">
          <i class="fa-brands fa-whatsapp"></i>
          WhatsApp
        </a>

        <a href="tel:{{ $emergencyDigits }}"
           class="pet-btn-contact pet-btn-call"
           aria-label="Llamar contacto emergencia">
          <i class="fa-solid fa-phone"></i>
          Llamar ahora
        </a>
      </div>
    </div>
    @else
    {{-- Si no hay emergencia, mostrar salud aquí --}}
    <div class="pet-bento-item pet-bento-health pet-reveal">
      <h2 class="pet-bento-title">
        <i class="fa-solid fa-heart-pulse"></i>
        Estado de Salud
      </h2>

      <div class="pet-health-badges">
        <span class="pet-health-badge {{ $neut ? 'pet-badge-success' : 'pet-badge-danger' }}">
          <i class="fa-solid fa-scissors"></i>
          {{ $neut ? 'Esterilizado' : 'No esterilizado' }}
        </span>

        <span class="pet-health-badge {{ $rabies ? 'pet-badge-success' : 'pet-badge-danger' }}">
          <i class="fa-solid fa-syringe"></i>
          Antirrábica: {{ $rabies ? 'Al día' : 'No' }}
        </span>
      </div>

      @if($zoneForMaps)
      <div class="pet-info-list" style="margin-top: 20px;">
        <div class="pet-info-row">
          <i class="fa-solid fa-location-dot"></i>
          <div>
            <div class="pet-info-label">Ubicación</div>
            <div class="pet-info-value">{{ $zoneForMaps }}</div>
          </div>
        </div>
      </div>

      @if($mapsUrl)
      <a href="{{ $mapsUrl }}"
         target="_blank" rel="noopener"
         class="pet-btn-contact pet-btn-secondary"
         style="margin-top: 15px; background: var(--warning); border-color: var(--warning);"
         aria-label="Ver ubicación en Maps">
        <i class="fa-solid fa-map-location-dot"></i>
        Ver en Maps
      </a>
      @endif
      @endif
    </div>
    @endif

    {{-- Si hay contacto emergencia, salud va abajo --}}
    @if($hasEmergency && $emergencyName && $emergencyPhone)
    <div class="pet-bento-item pet-bento-health pet-reveal">
      <h2 class="pet-bento-title">
        <i class="fa-solid fa-heart-pulse"></i>
        Estado de Salud
      </h2>

      <div class="pet-health-badges">
        <span class="pet-health-badge {{ $neut ? 'pet-badge-success' : 'pet-badge-danger' }}">
          <i class="fa-solid fa-scissors"></i>
          {{ $neut ? 'Esterilizado' : 'No esterilizado' }}
        </span>

        <span class="pet-health-badge {{ $rabies ? 'pet-badge-success' : 'pet-badge-danger' }}">
          <i class="fa-solid fa-syringe"></i>
          Antirrábica: {{ $rabies ? 'Al día' : 'No' }}
        </span>
      </div>
    </div>

    @if($zoneForMaps)
    <div class="pet-bento-item pet-bento-location pet-reveal">
      <h2 class="pet-bento-title">
        <i class="fa-solid fa-location-dot"></i>
        Ubicación
      </h2>

      <div class="pet-info-list">
        <div class="pet-info-row">
          <i class="fa-solid fa-map-marker-alt"></i>
          <div>
            <div class="pet-info-label">Zona</div>
            <div class="pet-info-value">{{ $zoneForMaps }}</div>
          </div>
        </div>
      </div>

      @if($mapsUrl)
      <a href="{{ $mapsUrl }}"
         target="_blank" rel="noopener"
         class="pet-btn-contact pet-btn-secondary"
         style="margin-top: 15px; background: var(--warning); border-color: var(--warning); color: #fff;"
         aria-label="Ver ubicación en Maps">
        <i class="fa-solid fa-map-location-dot"></i>
        Ver en Maps
      </a>
      @endif
    </div>
    @endif
    @endif

    {{-- GALERÍA --}}
    @if($gallery->count() > 1)
    <div class="pet-bento-item pet-bento-gallery pet-reveal">
      <h2 class="pet-bento-title">
        <i class="fa-solid fa-images"></i>
        Galería de Fotos
      </h2>

      <div class="pet-gallery-masonry">
        @foreach($gallery as $index => $photo)
        <div class="pet-gallery-item" data-src="{{ $photo}}">
          <img src="{{ $photo }}"
               alt="{{ $pet->name }} - Foto {{ $index + 1 }}"
               loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- CONDICIONES MÉDICAS --}}
    @if($medicalConditions)
    <div class="pet-bento-item pet-bento-medical pet-reveal">
      <h2 class="pet-bento-title">
        <i class="fa-solid fa-notes-medical"></i>
        Observaciones Médicas
      </h2>
      <div class="pet-medical-content">
        {{ $medicalConditions }}
      </div>
    </div>
    @endif

    {{-- RECOMPENSA --}}
    @if(optional($pet->reward)->active)
    <div class="pet-bento-item pet-bento-reward pet-reveal">
      <h2 class="pet-bento-title">
        <i class="fa-solid fa-medal"></i>
        Recompensa Activa
      </h2>

      @if(optional($pet->reward)->amount)
      <div class="pet-reward-amount">
        ₡{{ number_format((float)optional($pet->reward)->amount, 2) }}
      </div>
      @endif

      @if(optional($pet->reward)->message)
      <div class="pet-reward-content">
        {{ optional($pet->reward)->message }}
      </div>
      @endif
    </div>
    @endif

    {{-- COMPARTIR --}}
    <div class="pet-bento-item pet-bento-share pet-reveal" style="text-align: center;">
      <h2 class="pet-bento-title" style="justify-content: center;">
        <i class="fa-solid fa-share-nodes"></i>
        Ayuda a Difundir
      </h2>
      <p style="font-size: 18px; color: var(--gray-800); opacity: 0.8;">
        Comparte este perfil para ayudar a reunir a {{ $pet->name }} con su familia
      </p>
      <div class="pet-share-buttons">
        <button class="pet-btn-share pet-btn-share-primary pet-share-btn"
                aria-label="Compartir perfil">
          <i class="fa-solid fa-share"></i>
          Compartir perfil
        </button>
        <a href="{{ $mapsUrl ?? '#' }}"
           target="_blank"
           rel="noopener"
           class="pet-btn-share pet-btn-share-secondary {{ $mapsUrl ? '' : 'disabled' }}"
           aria-label="Ver ubicación">
          <i class="fa-solid fa-map"></i>
          Ver ubicación
        </a>
      </div>
    </div>

  </div>

</section>

{{-- ===== LIGHTBOX ===== --}}
<div class="pet-lightbox" id="petLightbox" role="dialog" aria-modal="true" aria-label="Visor de imágenes">
  <button class="pet-lightbox-close" id="petLightboxClose" aria-label="Cerrar">
    <i class="fa-solid fa-xmark"></i>
  </button>
  <div class="pet-lightbox-content">
    <img src="" alt="Foto ampliada" class="pet-lightbox-img" id="petLightboxImg">
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/pet-profile.js') }}"></script>
<script>
  // Initialize pet profile with config
  window.PetProfile.init({
    pingUrl: @json(route('public.pet.ping', ['slug' => $slug], false)),
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.content || ''
  });
</script>
@endpush
