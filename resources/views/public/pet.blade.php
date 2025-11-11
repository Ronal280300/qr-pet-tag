{{-- resources/views/public/pet.blade.php --}}
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
<style>
/* ===== RESET & VARIABLES ===== */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

:root {
  --primary: #4e89e8;
  --primary-dark: #3a6bb8;
  --blue-light: #3466ff;
  --blue-accent: #115DFC;
  --success: #10b981;
  --danger: #ef4444;
  --warning: #f59e0b;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-800: #1f2937;
  --gray-900: #111827;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  background: #fff;
  color: var(--gray-900);
  overflow-x: hidden;
}

/* ===== HERO FULLSCREEN ===== */
.hero-full {
  position: relative;
  height: 100vh;
  min-height: 600px;
  max-height: 900px;
  width: 100%;
  overflow: hidden;
  background: #000;
}

.hero-image {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  filter: brightness(0.85);
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(0, 0, 0, 0.3) 0%,
    rgba(0, 0, 0, 0.1) 50%,
    rgba(0, 0, 0, 0.8) 100%
  );
  z-index: 1;
}

.hero-content {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 2;
  padding: 60px 40px;
  color: #fff;
}

.hero-name {
  font-size: clamp(3rem, 8vw, 6rem);
  font-weight: 900;
  letter-spacing: -0.03em;
  line-height: 0.9;
  margin-bottom: 20px;
  text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
}

.hero-meta {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  margin-bottom: 30px;
}

.meta-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(10px);
  border-radius: 50px;
  font-size: 16px;
  font-weight: 600;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.meta-tag i {
  font-size: 1.2em;
}

.hero-scroll {
  position: absolute;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  animation: bounce 2s ease-in-out infinite;
}

.scroll-indicator {
  width: 30px;
  height: 50px;
  border: 2px solid rgba(255, 255, 255, 0.5);
  border-radius: 50px;
  position: relative;
}

.scroll-indicator::before {
  content: '';
  position: absolute;
  top: 10px;
  left: 50%;
  transform: translateX(-50%);
  width: 6px;
  height: 10px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 10px;
  animation: scroll 1.5s ease-in-out infinite;
}

@keyframes scroll {
  0%, 100% { opacity: 1; transform: translateX(-50%) translateY(0); }
  50% { opacity: 0; transform: translateX(-50%) translateY(15px); }
}

@keyframes bounce {
  0%, 100% { transform: translateX(-50%) translateY(0); }
  50% { transform: translateX(-50%) translateY(-10px); }
}

/* ===== ALERT BANNERS ===== */
.alert-section {
  padding: 20px;
  max-width: 1400px;
  margin: 0 auto;
}

.alert-card {
  padding: 30px;
  border-radius: 20px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 20px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.alert-card i {
  font-size: 40px;
  flex-shrink: 0;
}

.alert-lost {
  background: linear-gradient(135deg, #fee2e2, #fca5a5);
  color: #7f1d1d;
}

.alert-lost i {
  color: var(--danger);
  animation: pulse 2s ease-in-out infinite;
}

.alert-reward {
  background: linear-gradient(135deg, #d1fae5, #6ee7b7);
  color: #14532d;
}

.alert-reward i {
  color: var(--success);
}

@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

.alert-title {
  font-size: 24px;
  font-weight: 900;
  margin-bottom: 5px;
}

.alert-text {
  font-size: 16px;
  opacity: 0.9;
}

/* ===== MAIN CONTENT ===== */
.content-wrapper {
  max-width: 1400px;
  margin: 0 auto;
  padding: 80px 40px;
}

/* ===== BENTO GRID LAYOUT ===== */
.bento-grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 20px;
  margin-bottom: 60px;
}

.bento-item {
  background: var(--gray-50);
  border-radius: 30px;
  padding: 40px;
  border: 1px solid var(--gray-200);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.bento-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--primary), var(--blue-accent));
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.3s ease;
}

.bento-item:hover::before {
  transform: scaleX(1);
}

.bento-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

/* Grid positions */
.bento-info { grid-column: span 4; }
.bento-contact { grid-column: span 4; }
.bento-emergency { grid-column: span 4; }
.bento-health { grid-column: span 6; }
.bento-location { grid-column: span 6; }
.bento-gallery { grid-column: span 12; }
.bento-medical { grid-column: span 12; }
.bento-reward { grid-column: span 12; }
.bento-share { grid-column: span 12; }

.bento-title {
  font-size: 28px;
  font-weight: 900;
  margin-bottom: 30px;
  display: flex;
  align-items: center;
  gap: 15px;
  color: var(--gray-900);
}

.bento-title i {
  font-size: 1.2em;
  color: var(--primary);
}

/* ===== INFO ITEMS ===== */
.info-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.info-row {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  background: #fff;
  border-radius: 15px;
  transition: all 0.2s ease;
}

.info-row:hover {
  background: var(--gray-100);
  transform: translateX(5px);
}

.info-row i {
  font-size: 24px;
  color: var(--primary);
  width: 30px;
  text-align: center;
}

.info-label {
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-weight: 700;
  color: var(--gray-800);
  opacity: 0.6;
  margin-bottom: 2px;
}

.info-value {
  font-size: 18px;
  font-weight: 700;
  color: var(--gray-900);
}

/* ===== CONTACT CARDS ===== */
.contact-primary {
  background: linear-gradient(135deg, var(--primary), var(--blue-accent));
  color: #fff;
  box-shadow: 0 10px 40px rgba(78, 137, 232, 0.3);
}

.contact-primary .bento-title,
.contact-primary .info-label,
.contact-primary .info-value {
  color: #fff;
}

.contact-primary .bento-title i {
  color: #fff;
}

.contact-emergency {
  background: linear-gradient(135deg, var(--warning), #f97316);
  color: #fff;
  box-shadow: 0 10px 40px rgba(245, 158, 11, 0.3);
}

.contact-emergency .bento-title,
.contact-emergency .info-label,
.contact-emergency .info-value {
  color: #fff;
}

.contact-emergency .bento-title i {
  color: #fff;
}

.emergency-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  border-radius: 50px;
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 20px;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.contact-buttons {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 20px;
}

.btn-contact {
  padding: 18px 24px;
  border-radius: 15px;
  font-weight: 800;
  font-size: 16px;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-contact:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.btn-whatsapp {
  background: #25d366;
  color: #fff;
}

.btn-call {
  background: #fff;
  color: var(--primary);
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.2);
  color: #fff;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-contact.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}

/* ===== HEALTH BADGES ===== */
.health-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 20px;
}

.health-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  border-radius: 50px;
  font-size: 14px;
  font-weight: 700;
  border: 2px solid;
}

.badge-success {
  background: #d1fae5;
  color: #14532d;
  border-color: var(--success);
}

.badge-danger {
  background: #fee2e2;
  color: #7f1d1d;
  border-color: var(--danger);
}

/* ===== GALLERY MASONRY ===== */
.gallery-masonry {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
  margin-top: 30px;
}

.gallery-item {
  position: relative;
  border-radius: 20px;
  overflow: hidden;
  aspect-ratio: 1;
  cursor: pointer;
  transition: all 0.3s ease;
}

.gallery-item:hover {
  transform: scale(1.03);
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
}

.gallery-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.gallery-item:first-child {
  grid-column: span 2;
  grid-row: span 2;
}

/* ===== MEDICAL & REWARD ===== */
.medical-content,
.reward-content {
  background: #fff;
  padding: 25px;
  border-radius: 20px;
  border: 2px solid var(--gray-200);
  font-size: 16px;
  line-height: 1.8;
}

.reward-amount {
  font-size: 48px;
  font-weight: 900;
  color: var(--success);
  margin: 20px 0;
}

/* ===== SHARE SECTION ===== */
.share-buttons {
  display: flex;
  gap: 15px;
  margin-top: 30px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-share {
  padding: 18px 32px;
  border-radius: 15px;
  font-weight: 800;
  font-size: 16px;
  border: 2px solid;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  transition: all 0.3s ease;
  text-decoration: none;
}

.btn-share:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.btn-share-primary {
  background: var(--primary);
  color: #fff;
  border-color: var(--primary);
}

.btn-share-secondary {
  background: #fff;
  color: var(--primary);
  border-color: var(--primary);
}

/* ===== LIGHTBOX ===== */
.lightbox {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.95);
  z-index: 9999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.lightbox.active {
  display: flex;
}

.lightbox-content {
  position: relative;
  max-width: 90vw;
  max-height: 90vh;
}

.lightbox-img {
  max-width: 100%;
  max-height: 90vh;
  object-fit: contain;
  border-radius: 10px;
}

.lightbox-close {
  position: absolute;
  top: -50px;
  right: 0;
  width: 45px;
  height: 45px;
  border: none;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 50%;
  color: #fff;
  font-size: 24px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.lightbox-close:hover {
  background: var(--danger);
  transform: rotate(90deg);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .bento-info,
  .bento-contact,
  .bento-emergency,
  .bento-health,
  .bento-location {
    grid-column: span 6;
  }
}

@media (max-width: 768px) {
  .hero-content {
    padding: 40px 20px;
  }

  .hero-name {
    font-size: 3rem;
  }

  .hero-meta {
    gap: 10px;
  }

  .meta-tag {
    font-size: 14px;
    padding: 8px 16px;
  }

  .content-wrapper {
    padding: 40px 20px;
  }

  .bento-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .bento-info,
  .bento-contact,
  .bento-emergency,
  .bento-health,
  .bento-location,
  .bento-gallery,
  .bento-medical,
  .bento-reward,
  .bento-share {
    grid-column: span 1;
  }

  .bento-item {
    padding: 30px 25px;
  }

  .bento-title {
    font-size: 24px;
    margin-bottom: 20px;
  }

  .gallery-item:first-child {
    grid-column: span 1;
    grid-row: span 1;
  }

  .share-buttons {
    flex-direction: column;
  }

  .btn-share {
    width: 100%;
  }

  .alert-card {
    flex-direction: column;
    text-align: center;
    padding: 25px 20px;
  }

  .reward-amount {
    font-size: 36px;
  }
}

@media (max-width: 480px) {
  .hero-name {
    font-size: 2.5rem;
  }

  .bento-item {
    padding: 25px 20px;
    border-radius: 20px;
  }

  .bento-title {
    font-size: 20px;
  }

  .info-row {
    padding: 12px;
  }

  .info-value {
    font-size: 16px;
  }

  .btn-contact {
    padding: 15px 20px;
    font-size: 15px;
  }
}

/* ===== SCROLL ANIMATIONS ===== */
.reveal {
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.6s ease;
}

.reveal.active {
  opacity: 1;
  transform: translateY(0);
}
</style>
@endpush

@section('content')

{{-- ===== HERO FULLSCREEN ===== --}}
<section class="hero-full">
  <img src="{{ $mainPhoto }}" alt="{{ $pet->name }}" class="hero-image">
  <div class="hero-overlay"></div>

  <div class="hero-content">
    <h1 class="hero-name">{{ $pet->name }}</h1>

    <div class="hero-meta">
      <span class="meta-tag">
        <i class="fa-solid {{ $sexIcon }}" style="color: {{ $sexColor }}"></i>
        {{ $sexText }}
      </span>

      <span class="meta-tag">
        <i class="fa-solid {{ $speciesIcon }}"></i>
        {{ $speciesText }}
      </span>

      @if($pet->breed)
      <span class="meta-tag">
        <i class="fa-solid fa-bone"></i>
        {{ $pet->breed }}
      </span>
      @endif

      @if($pet->age !== null)
      <span class="meta-tag">
        <i class="fa-solid fa-cake-candles"></i>
        {{ $pet->age }} {{ Str::plural('año', $pet->age) }}
      </span>
      @endif
    </div>
  </div>

  <div class="hero-scroll">
    <div class="scroll-indicator"></div>
  </div>
</section>

{{-- ===== ALERT BANNERS ===== --}}
@if($pet->is_lost || optional($pet->reward)->active)
<section class="alert-section">
  @if($pet->is_lost)
  <div class="alert-card alert-lost reveal">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <div>
      <div class="alert-title">¡Mascota reportada como perdida!</div>
      <div class="alert-text">Si tienes información sobre su paradero, contacta a su dueño de inmediato.</div>
    </div>
  </div>
  @endif

  @if(optional($pet->reward)->active)
  <div class="alert-card alert-reward reveal">
    <i class="fa-solid fa-medal"></i>
    <div>
      <div class="alert-title">
        Recompensa activa
        @if(optional($pet->reward)->amount)
          - ₡{{ number_format((float)optional($pet->reward)->amount, 2) }}
        @endif
      </div>
      @if(optional($pet->reward)->message)
      <div class="alert-text">{{ optional($pet->reward)->message }}</div>
      @endif
    </div>
  </div>
  @endif
</section>
@endif

{{-- ===== MAIN CONTENT ===== --}}
<section class="content-wrapper">

  <div class="bento-grid">

    {{-- INFORMACIÓN --}}
    <div class="bento-item bento-info reveal">
      <h2 class="bento-title">
        <i class="fa-solid fa-paw"></i>
        Información
      </h2>

      <div class="info-list">
        @if($pet->breed)
        <div class="info-row">
          <i class="fa-solid fa-bone"></i>
          <div>
            <div class="info-label">Raza</div>
            <div class="info-value">{{ $pet->breed }}</div>
          </div>
        </div>
        @endif

        <div class="info-row">
          <i class="fa-solid {{ $sexIcon }}"></i>
          <div>
            <div class="info-label">Sexo</div>
            <div class="info-value">{{ $sexText }}</div>
          </div>
        </div>

        @if($pet->age !== null)
        <div class="info-row">
          <i class="fa-solid fa-cake-candles"></i>
          <div>
            <div class="info-label">Edad</div>
            <div class="info-value">{{ $pet->age }} {{ Str::plural('año', $pet->age) }}</div>
          </div>
        </div>
        @endif

        @if($size)
        <div class="info-row">
          <i class="fa-solid fa-ruler-vertical"></i>
          <div>
            <div class="info-label">Tamaño</div>
            <div class="info-value">{{ $sizeText }}</div>
          </div>
        </div>
        @endif

        @if($color)
        <div class="info-row">
          <i class="fa-solid fa-palette"></i>
          <div>
            <div class="info-label">Color</div>
            <div class="info-value">{{ $color }}</div>
          </div>
        </div>
        @endif

        @if($ownerName)
        <div class="info-row">
          <i class="fa-solid fa-user"></i>
          <div>
            <div class="info-label">Dueño</div>
            <div class="info-value">{{ $ownerName }}</div>
          </div>
        </div>
        @endif
      </div>
    </div>

    {{-- CONTACTO PRINCIPAL --}}
    <div class="bento-item bento-contact contact-primary reveal">
      <h2 class="bento-title">
        <i class="fa-solid fa-phone"></i>
        Contacto Principal
      </h2>

      <p style="opacity: 0.9; margin-bottom: 20px;">
        Contacta al dueño de {{ $pet->name }} si lo has encontrado o tienes información.
      </p>

      <div class="contact-buttons">
        <a href="{{ $hasWhats ? 'https://wa.me/'.$digits : '#' }}"
           class="btn-contact btn-whatsapp {{ $hasWhats ? '' : 'disabled' }}"
           target="_blank" rel="noopener">
          <i class="fa-brands fa-whatsapp"></i>
          WhatsApp
        </a>

        @if($ownerPhone)
        <a href="tel:{{ $digits }}" class="btn-contact btn-call">
          <i class="fa-solid fa-phone"></i>
          Llamar ahora
        </a>
        @endif

        <button class="btn-contact btn-secondary" id="shareBtn">
          <i class="fa-solid fa-share-nodes"></i>
          Compartir
        </button>
      </div>
    </div>

    {{-- CONTACTO EMERGENCIA --}}
    @if($hasEmergency && $emergencyName && $emergencyPhone)
    <div class="bento-item bento-emergency contact-emergency reveal">
      <div class="emergency-badge">
        <i class="fa-solid fa-user-nurse"></i>
        Contacto de Emergencia
      </div>

      <h2 class="bento-title">
        <i class="fa-solid fa-phone-volume"></i>
        {{ $emergencyName }}
      </h2>

      <p style="opacity: 0.9; margin-bottom: 20px;">
        Si el dueño no responde, contacta a este número de emergencia.
      </p>

      <div class="contact-buttons">
        <a href="{{ $hasEmergencyWhats ? 'https://wa.me/'.$emergencyDigits : '#' }}"
           class="btn-contact btn-whatsapp {{ $hasEmergencyWhats ? '' : 'disabled' }}"
           target="_blank" rel="noopener">
          <i class="fa-brands fa-whatsapp"></i>
          WhatsApp
        </a>

        <a href="tel:{{ $emergencyDigits }}" class="btn-contact btn-call">
          <i class="fa-solid fa-phone"></i>
          Llamar ahora
        </a>
      </div>
    </div>
    @else
    {{-- Si no hay emergencia, mostrar salud aquí --}}
    <div class="bento-item bento-health reveal">
      <h2 class="bento-title">
        <i class="fa-solid fa-heart-pulse"></i>
        Estado de Salud
      </h2>

      <div class="health-badges">
        <span class="health-badge {{ $neut ? 'badge-success' : 'badge-danger' }}">
          <i class="fa-solid fa-scissors"></i>
          {{ $neut ? 'Esterilizado' : 'No esterilizado' }}
        </span>

        <span class="health-badge {{ $rabies ? 'badge-success' : 'badge-danger' }}">
          <i class="fa-solid fa-syringe"></i>
          Antirrábica: {{ $rabies ? 'Al día' : 'No' }}
        </span>
      </div>

      @if($zoneForMaps)
      <div class="info-list" style="margin-top: 20px;">
        <div class="info-row">
          <i class="fa-solid fa-location-dot"></i>
          <div>
            <div class="info-label">Ubicación</div>
            <div class="info-value">{{ $zoneForMaps }}</div>
          </div>
        </div>
      </div>

      @if($mapsUrl)
      <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="btn-contact btn-secondary" style="margin-top: 15px; background: var(--warning); border-color: var(--warning);">
        <i class="fa-solid fa-map-location-dot"></i>
        Ver en Maps
      </a>
      @endif
      @endif
    </div>
    @endif

    {{-- Si hay contacto emergencia, salud va abajo --}}
    @if($hasEmergency && $emergencyName && $emergencyPhone)
    <div class="bento-item bento-health reveal">
      <h2 class="bento-title">
        <i class="fa-solid fa-heart-pulse"></i>
        Estado de Salud
      </h2>

      <div class="health-badges">
        <span class="health-badge {{ $neut ? 'badge-success' : 'badge-danger' }}">
          <i class="fa-solid fa-scissors"></i>
          {{ $neut ? 'Esterilizado' : 'No esterilizado' }}
        </span>

        <span class="health-badge {{ $rabies ? 'badge-success' : 'badge-danger' }}">
          <i class="fa-solid fa-syringe"></i>
          Antirrábica: {{ $rabies ? 'Al día' : 'No' }}
        </span>
      </div>
    </div>

    @if($zoneForMaps)
    <div class="bento-item bento-location reveal">
      <h2 class="bento-title">
        <i class="fa-solid fa-location-dot"></i>
        Ubicación
      </h2>

      <div class="info-list">
        <div class="info-row">
          <i class="fa-solid fa-map-marker-alt"></i>
          <div>
            <div class="info-label">Zona</div>
            <div class="info-value">{{ $zoneForMaps }}</div>
          </div>
        </div>
      </div>

      @if($mapsUrl)
      <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="btn-contact btn-secondary" style="margin-top: 15px; background: var(--warning); border-color: var(--warning); color: #fff;">
        <i class="fa-solid fa-map-location-dot"></i>
        Ver en Maps
      </a>
      @endif
    </div>
    @endif
    @endif

    {{-- GALERÍA --}}
    @if($gallery->count() > 1)
    <div class="bento-item bento-gallery reveal">
      <h2 class="bento-title">
        <i class="fa-solid fa-images"></i>
        Galería de Fotos
      </h2>

      <div class="gallery-masonry">
        @foreach($gallery as $photo)
        <div class="gallery-item" data-src="{{ $photo }}">
          <img src="{{ $photo }}" alt="{{ $pet->name }}">
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- CONDICIONES MÉDICAS --}}
    @if($medicalConditions)
    <div class="bento-item bento-medical reveal">
      <h2 class="bento-title">
        <i class="fa-solid fa-notes-medical"></i>
        Observaciones Médicas
      </h2>
      <div class="medical-content">
        {{ $medicalConditions }}
      </div>
    </div>
    @endif

    {{-- RECOMPENSA --}}
    @if(optional($pet->reward)->active)
    <div class="bento-item bento-reward reveal">
      <h2 class="bento-title">
        <i class="fa-solid fa-medal"></i>
        Recompensa Activa
      </h2>

      @if(optional($pet->reward)->amount)
      <div class="reward-amount">
        ₡{{ number_format((float)optional($pet->reward)->amount, 2) }}
      </div>
      @endif

      @if(optional($pet->reward)->message)
      <div class="reward-content">
        {{ optional($pet->reward)->message }}
      </div>
      @endif
    </div>
    @endif

    {{-- COMPARTIR --}}
    <div class="bento-item bento-share reveal" style="text-align: center;">
      <h2 class="bento-title" style="justify-content: center;">
        <i class="fa-solid fa-share-nodes"></i>
        Ayuda a Difundir
      </h2>
      <p style="font-size: 18px; color: var(--gray-800); opacity: 0.8;">
        Comparte este perfil para ayudar a reunir a {{ $pet->name }} con su familia
      </p>
      <div class="share-buttons">
        <button class="btn-share btn-share-primary" id="shareBtn2">
          <i class="fa-solid fa-share"></i>
          Compartir perfil
        </button>
        <a href="{{ $mapsUrl ?? '#' }}"
           target="_blank"
           rel="noopener"
           class="btn-share btn-share-secondary {{ $mapsUrl ? '' : 'disabled' }}">
          <i class="fa-solid fa-map"></i>
          Ver ubicación
        </a>
      </div>
    </div>

  </div>

</section>

{{-- ===== LIGHTBOX ===== --}}
<div class="lightbox" id="lightbox">
  <button class="lightbox-close" id="lightboxClose">
    <i class="fa-solid fa-xmark"></i>
  </button>
  <div class="lightbox-content">
    <img src="" alt="Foto ampliada" class="lightbox-img" id="lightboxImg">
  </div>
</div>

@endsection

@push('scripts')
<script>
// ===== SCROLL REVEAL =====
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('active');
    }
  });
}, { threshold: 0.1 });

reveals.forEach(reveal => observer.observe(reveal));

// ===== GALLERY LIGHTBOX =====
const galleryItems = document.querySelectorAll('.gallery-item');
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightboxImg');
const lightboxClose = document.getElementById('lightboxClose');

galleryItems.forEach(item => {
  item.addEventListener('click', () => {
    const src = item.dataset.src || item.querySelector('img').src;
    lightboxImg.src = src;
    lightbox.classList.add('active');
  });
});

lightboxClose.addEventListener('click', () => {
  lightbox.classList.remove('active');
});

lightbox.addEventListener('click', (e) => {
  if (e.target === lightbox) {
    lightbox.classList.remove('active');
  }
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && lightbox.classList.contains('active')) {
    lightbox.classList.remove('active');
  }
});

// ===== COMPARTIR =====
const shareButtons = [document.getElementById('shareBtn'), document.getElementById('shareBtn2')].filter(Boolean);
const shareUrl = @json($shareUrl);
const shareTitle = @json($pet->name . ' - Perfil de Mascota');

function copyToClipboard(text) {
  if (navigator.clipboard && window.isSecureContext) {
    return navigator.clipboard.writeText(text);
  }
  const textarea = document.createElement('textarea');
  textarea.value = text;
  textarea.style.position = 'fixed';
  textarea.style.left = '-999999px';
  document.body.appendChild(textarea);
  textarea.select();
  return new Promise((res, rej) => {
    document.execCommand('copy') ? res() : rej();
    textarea.remove();
  });
}

shareButtons.forEach(btn => {
  btn.addEventListener('click', async () => {
    if (navigator.share) {
      try {
        await navigator.share({ title: shareTitle, url: shareUrl });
        return;
      } catch {}
    }

    try {
      await copyToClipboard(shareUrl);
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> ¡Copiado!';
      setTimeout(() => btn.innerHTML = orig, 2500);
    } catch {
      alert('Copia este enlace:\n' + shareUrl);
    }
  });
});
</script>

{{-- ===== AUTO-PING CON GEOLOCALIZACIÓN ===== --}}
<script>
(function autoPing(){
  const url  = @json(route('public.pet.ping', ['slug' => $slug], false));
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  function send(body){
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type':'application/json',
        'X-CSRF-TOKEN': csrf,
        'Accept':'application/json'
      },
      body: JSON.stringify(body)
    }).catch(()=>{});
  }

  if (navigator.geolocation && (window.isSecureContext || location.protocol === 'https:' || ['localhost','127.0.0.1'].includes(location.hostname))) {
    let done = false;
    const timer = setTimeout(() => { if (!done) { done = true; send({ method:'ip' }); } }, 6000);

    navigator.geolocation.getCurrentPosition(
      pos => {
        if (done) return; done = true; clearTimeout(timer);
        const c = pos.coords || {};
        send({ method:'gps', lat:c.latitude, lng:c.longitude, accuracy: Math.round(c.accuracy || 0) });
      },
      _ => { if (done) return; done = true; clearTimeout(timer); send({ method:'ip' }); },
      { enableHighAccuracy:true, timeout:12000, maximumAge:0 }
    );
  } else {
    send({ method:'ip' });
  }
})();
</script>
@endpush
