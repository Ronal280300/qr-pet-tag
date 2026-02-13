{{-- resources/views/public/pet.blade.php --}}
@extends('layouts.public')
@section('title', $pet->name)

@php
  use Illuminate\Support\Facades\Storage;

  // FIX: Usar all_photos que incluye foto principal + opcionales en orden correcto
  $allPhotos = $pet->all_photos ?? collect();
  $gallery   = collect();

  if ($allPhotos->count() > 0) {
      $gallery = $allPhotos->map(fn($ph) => Storage::url($ph->path));
  } else {
      $gallery = collect(['https://placehold.co/1200x1200?text='.urlencode($pet->name)]);
  }

  $ownerName  = optional($owner)->name;
  $ownerPhone = optional($owner)->phone;
  $digits     = preg_replace('/\D+/', '', (string) $ownerPhone);
  $hasWhats   = !empty($digits);

  $zoneForMaps = $pet->full_location ?: ($pet->zone ?: null);
  $mapsUrl     = $zoneForMaps ? ('https://www.google.com/maps/search/?api=1&query=' . urlencode($zoneForMaps)) : null;

  $sex = $pet->sex ?? 'unknown';
  $sexIcon = $sex === 'male' ? 'fa-mars' : ($sex === 'female' ? 'fa-venus' : 'fa-circle-question');
  $sexText = $sex === 'male' ? 'Macho' : ($sex === 'female' ? 'Hembra' : 'Sexo N/D');

  $neut   = (bool) ($pet->is_neutered ?? false);
  $rabies = (bool) ($pet->rabies_vaccine ?? false);

  $shareUrl = url()->current();
  $slug = $slug ?? ($pet->qrCode->slug ?? null);
@endphp

@push('styles')
<style>
/* === Variables === */
:root {
  --primary: #2563eb;
  --primary-dark: #1e40af;
  --primary-light: #3b82f6;
  --danger: #ef4444;
  --success: #10b981;
  --warning: #f59e0b;
  --text-primary: #111827;
  --text-secondary: #6b7280;
  --text-muted: #9ca3af;
  --border: #e5e7eb;
  --bg-page: #f8fafc;
  --bg-card: #ffffff;
  --radius: 12px;
}

/* === Animations === */
@keyframes slideUp{0%{opacity:0;transform:translateY(60px)}100%{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{0%{opacity:0}100%{opacity:1}}
@keyframes scaleUp{0%{opacity:0;transform:scale(.8)}100%{opacity:1;transform:scale(1)}}
@keyframes shimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
@keyframes float{0%,100%{transform:translateY(0px)}50%{transform:translateY(-15px)}}
@keyframes pulse{0%,100%{transform:scale(1);opacity:.8}50%{transform:scale(1.1);opacity:1}}
@keyframes heartbeat{0%,100%{transform:scale(1)}10%,30%{transform:scale(1.1)}20%,40%{transform:scale(1)}}
@keyframes glow{0%,100%{box-shadow:0 0 20px rgba(37,99,235,.3)}50%{box-shadow:0 0 40px rgba(37,99,235,.6),0 0 60px rgba(37,99,235,.4)}}
@keyframes slideInLeft{0%{opacity:0;transform:translateX(-50px)}100%{opacity:1;transform:translateX(0)}}
@keyframes slideInRight{0%{opacity:0;transform:translateX(50px)}100%{opacity:1;transform:translateX(0)}}
@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}
@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

* {
  -webkit-tap-highlight-color: transparent;
}

body {
  background: var(--bg-page);
  margin: 0;
  padding: 0;
}

.page-container{
  padding:2rem 1rem;
  max-width:1200px;
  margin:0 auto;
  animation:fadeIn .8s ease-out;
}

/* === Alert Banners === */
.alert-banners{
  display:flex;
  flex-direction:column;
  gap:1rem;
  margin-bottom:2rem;
  animation:slideUp .6s ease-out .3s both;
}

.alert-banner{
  padding:1.5rem;
  border-radius:20px;
  border-left:6px solid;
  box-shadow:0 8px 30px rgba(0,0,0,.1);
  display:flex;
  align-items:flex-start;
  gap:1rem;
}

.alert-banner i{
  font-size:1.8rem;
  margin-top:.2rem;
  flex-shrink:0;
}

.alert-content{
  flex:1;
}

.alert-title{
  font-size:1.2rem;
  font-weight:900;
  margin-bottom:.5rem;
}

.alert-text{
  font-size:1.05rem;
  line-height:1.6;
  font-weight:500;
}

.alert-lost{
  background:linear-gradient(135deg,#fee2e2 0%,#fecaca 100%);
  border-color:#dc2626;
  color:#7f1d1d;
}
.alert-lost i{
  color:#dc2626;
}

.alert-reward{
  background:linear-gradient(135deg,#d1fae5 0%,#a7f3d0 100%);
  border-color:#16a34a;
  color:#14532d;
}
.alert-reward i{
  color:#16a34a;
}
.alert-reward .pulse-icon{
  display:inline-block;
  width:10px;
  height:10px;
  background:#16a34a;
  border-radius:50%;
  animation:pulse 2s ease-in-out infinite;
  margin-right:.3rem;
  vertical-align:middle;
}

/* === Hero Section - Carousel del PRIMER código === */
.hero-section {
  background: #fff;
  border-radius: 30px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0,0,0,.12), 0 0 1px rgba(0,0,0,.1);
  margin-bottom: 2rem;
  position: relative;
  animation: scaleUp .6s cubic-bezier(.34,1.56,.64,1);
}

.carousel-wrapper {
  position: relative;
  width: 100%;
  aspect-ratio: 4/3;
  background: white;
  overflow: hidden;
}

.carousel-track {
  position: relative;
  width: 100%;
  height: 100%;
}

.carousel-slide {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity 0.5s ease-in-out;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.carousel-slide.active {
  opacity: 1;
}

.carousel-slide img {
  max-width: 100%;
  max-height: 100%;
  width: auto;
  height: auto;
  object-fit: contain;
  border-radius: 8px;
  cursor: pointer;
  transition: transform 0.3s ease;
}

.carousel-slide img:hover {
  transform: scale(1.02);
}

/* === Carousel Controls === */
.carousel-controls {
  position: absolute;
  bottom: 1rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 999px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  z-index: 10;
}

.carousel-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--border);
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  padding: 0;
}

.carousel-dot.active {
  background: var(--primary);
  width: 24px;
  border-radius: 4px;
}

.carousel-nav-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 44px;
  height: 44px;
  background: rgba(255, 255, 255, 0.95);
  border: none;
  border-radius: 50%;
  color: var(--primary);
  font-size: 1.125rem;
  cursor: pointer;
  transition: all 0.2s ease;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.carousel-nav-btn:hover {
  background: white;
  transform: translateY(-50%) scale(1.05);
}

.carousel-nav-btn.prev {
  left: 1rem;
}

.carousel-nav-btn.next {
  right: 1rem;
}

/* === Pet Info Overlay === */
.pet-hero-info{
  position:absolute;
  top:0;
  left:0;
  right:0;
  padding:2rem;
  background:linear-gradient(180deg,rgba(0,0,0,.7) 0%,transparent 100%);
  z-index:5;
}

.pet-name-hero{
  font-size:clamp(2.5rem,6vw,4rem);
  font-weight:900;
  color:#fff;
  text-shadow:0 4px 20px rgba(0,0,0,.5);
  margin:0;
  display:flex;
  align-items:center;
  gap:1rem;
  animation:slideInLeft .6s ease-out .2s both;
}

.pet-name-hero i{
  font-size:.8em;
  animation:float 3s ease-in-out infinite;
}

/* === Content Grid === */
.content-grid{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:2rem;
  animation:slideUp .6s ease-out .4s both;
  align-items:start;
}

/* === Info Card === */
.info-card{
  background:#fff;
  border-radius:25px;
  padding:2rem;
  box-shadow:0 10px 40px rgba(0,0,0,.08),0 0 1px rgba(0,0,0,.05);
  transition:all .4s cubic-bezier(.4,0,.2,1);
  position:relative;
  overflow:hidden;
}

.info-card::before{
  content:'';
  position:absolute;
  top:-50%;
  right:-50%;
  width:200%;
  height:200%;
  background:conic-gradient(from 0deg,transparent,rgba(37,99,235,.1),transparent);
  animation:rotate 4s linear infinite;
  pointer-events:none;
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.info-card:hover{
  transform:translateY(-10px);
  box-shadow:0 20px 60px rgba(0,0,0,.15),0 0 1px rgba(0,0,0,.08);
}

.card-title{
  font-size:1.8rem;
  font-weight:900;
  color:#1e293b;
  margin-bottom:1.5rem;
  display:flex;
  align-items:center;
  gap:.75rem;
}

.card-title i{
  font-size:1.4em;
  background:linear-gradient(135deg,var(--primary),var(--primary-dark));
  -webkit-background-clip:text;
  -webkit-text-fill-color:transparent;
  animation:bounce 2s ease-in-out infinite;
}

/* === Info Items Grid === */
.info-items{
  display:grid;
  gap:1rem;
}

.info-item{
  padding:1.2rem;
  background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);
  border-radius:16px;
  display:flex;
  align-items:center;
  gap:1rem;
  transition:all .3s ease;
  border:2px solid transparent;
}

.info-item:hover{
  background:linear-gradient(135deg,#e9ecef 0%,#dee2e6 100%);
  border-color:var(--primary);
  transform:translateX(8px);
}

.info-item i{
  font-size:1.8rem;
  color:var(--primary);
  min-width:35px;
  text-align:center;
  transition:transform .3s ease;
}

.info-item:hover i{
  transform:scale(1.2) rotate(10deg);
}

.info-item i.fa-venus {
  color: #ec4899;
}

.info-item-content{
  flex:1;
}

.info-item-label{
  font-size:.75rem;
  text-transform:uppercase;
  color:#64748b;
  font-weight:700;
  letter-spacing:1px;
}

.info-item-value{
  font-size:1.1rem;
  font-weight:800;
  color:#1e293b;
  margin-top:.2rem;
}

/* === Contact Card - Azul === */
.contact-card{
  background:linear-gradient(135deg,var(--primary) 0%,var(--primary-dark) 100%);
  color:#fff;
  animation:glow 3s ease-in-out infinite;
}

.contact-card .card-title{
  color:#fff;
}

.contact-card .card-title i{
  background:#fff;
  -webkit-background-clip:text;
}

.contact-buttons{
  display:grid;
  gap:1rem;
}

.btn-contact{
  padding:1.3rem 2rem;
  border-radius:18px;
  font-weight:800;
  font-size:1.1rem;
  border:none;
  cursor:pointer;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:.8rem;
  transition:all .4s cubic-bezier(.4,0,.2,1);
  text-decoration:none;
  position:relative;
  overflow:hidden;
}

.btn-contact::before{
  content:'';
  position:absolute;
  top:50%;
  left:50%;
  width:0;
  height:0;
  background:rgba(255,255,255,.3);
  border-radius:50%;
  transition:width .6s,height .6s,top .6s,left .6s;
  transform:translate(-50%,-50%);
}

.btn-contact:hover::before{
  width:400px;
  height:400px;
}

.btn-contact:hover{
  transform:translateY(-5px);
  box-shadow:0 20px 40px rgba(0,0,0,.3);
  text-decoration:none;
}

.btn-whatsapp{
  background:#25d366;
  color:#fff;
  box-shadow:0 10px 30px rgba(37,211,102,.4);
}

.btn-whatsapp:hover{
  box-shadow:0 15px 45px rgba(37,211,102,.6);
  color:#fff;
}

.btn-call{
  background:#fff;
  color:var(--primary);
  box-shadow:0 10px 30px rgba(255,255,255,.3);
}

.btn-call:hover{
  box-shadow:0 15px 45px rgba(255,255,255,.5);
  color:var(--primary);
}

.btn-secondary{
  background:rgba(255,255,255,.15);
  color:#fff;
  backdrop-filter:blur(10px);
  border:2px solid rgba(255,255,255,.3);
}

.btn-secondary:hover{
  background:rgba(255,255,255,.25);
  color:#fff;
}

.btn-contact.disabled{
  opacity:.5;
  cursor:not-allowed;
  pointer-events:none;
}

/* === Lightbox === */
.lightbox {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.95);
  backdrop-filter: blur(20px);
  z-index: 9999;
  display: none;
  align-items: center;
  justify-content: center;
  animation: fadeIn 0.3s ease-out;
}

.lightbox.active {
  display: flex;
}

.lightbox-content {
  position: relative;
  max-width: 95vw;
  max-height: 95vh;
  animation: scaleUp 0.4s cubic-bezier(.34,1.56,.64,1);
}

.lightbox-img {
  max-width: 100%;
  max-height: 95vh;
  object-fit: contain;
  border-radius: 20px;
  box-shadow: 0 30px 100px rgba(0,0,0,.8);
}

.lightbox-close {
  position: fixed;
  top: 20px;
  right: 20px;
  width: 50px;
  height: 50px;
  border: none;
  background: rgba(255,255,255,.15);
  backdrop-filter: blur(10px);
  border-radius: 50%;
  color: #fff;
  font-size: 24px;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 10001;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.lightbox-close:hover {
  background: var(--danger);
  transform: rotate(90deg) scale(1.1);
}

.lightbox-close:active {
  transform: rotate(90deg) scale(0.95);
}

.lightbox-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 60px;
  height: 60px;
  border: none;
  background: rgba(255,255,255,.1);
  backdrop-filter: blur(10px);
  border-radius: 50%;
  color: #fff;
  font-size: 28px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.lightbox-nav:hover {
  background: rgba(255,255,255,.2);
  transform: translateY(-50%) scale(1.15);
}

.lightbox-prev {
  left: 1rem;
}

.lightbox-next {
  right: 1rem;
}

/* === Responsive === */
@media (max-width:768px){
  .page-container{padding:1rem .5rem}
  .carousel-wrapper{aspect-ratio:1/1}
  .carousel-slide{padding:1.5rem}
  .carousel-nav-btn{width:40px;height:40px;font-size:1rem}
  .carousel-nav-btn.prev{left:0.75rem}
  .carousel-nav-btn.next{right:0.75rem}
  .pet-name-hero{font-size:2rem}
  .pet-hero-info{padding:1.5rem}
  .content-grid{grid-template-columns:1fr;gap:1.5rem}
  .info-card{padding:1.5rem;border-radius:20px;box-shadow:0 8px 30px rgba(0,0,0,.08)}
  .card-title{font-size:1.5rem}
  .info-item{padding:1rem}
  .info-item i{font-size:1.5rem}
  .btn-contact{padding:1.1rem 1.5rem;font-size:1rem}
  .lightbox-nav{width:50px;height:50px;font-size:20px}
  .lightbox-prev{left:10px}
  .lightbox-next{right:10px}
  .lightbox-close{top:10px;right:10px;width:44px;height:44px;font-size:20px}
  .alert-banner{padding:1.2rem}
  .alert-title{font-size:1.1rem}
  .alert-text{font-size:.95rem}
}

@media (max-width:480px){
  .alert-banners{padding:0;gap:0.625rem}
  .alert-banner{padding:1rem;border-radius:16px}
  .alert-banner i{font-size:1.5rem}
  .alert-title{font-size:1rem}
  .alert-text{font-size:.9rem}
  .carousel-slide{padding:1rem}
  .carousel-controls{padding:0.375rem 0.75rem}
  .carousel-dot{width:6px;height:6px}
  .carousel-dot.active{width:20px}
  .carousel-nav-btn{width:36px;height:36px;font-size:0.875rem}
  .pet-name-hero{font-size:1.75rem;gap:.5rem}
  .pet-hero-info{padding:1rem}
  .info-item-value{font-size:1rem}
  .info-card{border-radius:16px;box-shadow:0 6px 20px rgba(0,0,0,.06)}
}
</style>
@endpush

@section('content')
<div class="page-container">
  
  {{-- Banners de Alerta --}}
  @if($pet->is_lost || optional($pet->reward)->active)
  <div class="alert-banners">
    @if($pet->is_lost)
      <div class="alert-banner alert-lost">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div class="alert-content">
          <div class="alert-title">¡Mascota reportada como perdida!</div>
          <div class="alert-text">Si tienes información, por favor contacta a su dueño de inmediato.</div>
        </div>
      </div>
    @endif

    @if(optional($pet->reward)->active)
      <div class="alert-banner alert-reward">
        <i class="fa-solid fa-medal"></i>
        <div class="alert-content">
          <div class="alert-title">
            <span class="pulse-icon"></span>
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
  </div>
  @endif
  
  {{-- Hero Section con Carousel del PRIMER código --}}
  <div class="hero-section">
    <div class="carousel-wrapper" id="carousel">
      <div class="carousel-track">
        @foreach($gallery as $i => $src)
          <div class="carousel-slide {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}">
            <img src="{{ $src }}" alt="{{ $pet->name }} foto {{ $i+1 }}">
          </div>
        @endforeach
      </div>
      
      @if($gallery->count() > 1)
        <button class="carousel-nav-btn prev" id="carouselPrev">
          <i class="fa-solid fa-chevron-left"></i>
        </button>
        <button class="carousel-nav-btn next" id="carouselNext">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
        
        <div class="carousel-controls">
          @foreach($gallery as $i => $src)
            <button class="carousel-dot {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}"></button>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Info Overlay --}}
    <div class="pet-hero-info">
      <h1 class="pet-name-hero">
        @if($sex === 'male')
          <i class="fa-solid fa-mars" style="color:#60a5fa"></i>
        @elseif($sex === 'female')
          <i class="fa-solid fa-venus" style="color:#f472b6"></i>
        @else
          <i class="fa-solid fa-circle-question"></i>
        @endif
        {{ $pet->name }}
      </h1>
    </div>
  </div>

  {{-- Content Grid --}}
  <div class="content-grid">
    
    {{-- Info Card --}}
    <div class="info-card">
      <h2 class="card-title">
        <i class="fa-solid fa-paw"></i>
        Información
      </h2>

      <div class="info-items">
        <div class="info-item">
          <i class="fa-solid fa-bone"></i>
          <div class="info-item-content">
            <div class="info-item-label">Raza</div>
            <div class="info-item-value">{{ $pet->breed ?: 'Sin raza específica' }}</div>
          </div>
        </div>

        <div class="info-item">
          <i class="fa-solid {{ $sexIcon }}"></i>
          <div class="info-item-content">
            <div class="info-item-label">Sexo</div>
            <div class="info-item-value">{{ $sexText }}</div>
          </div>
        </div>

        @if($pet->age !== null)
        <div class="info-item">
          <i class="fa-solid fa-cake-candles"></i>
          <div class="info-item-content">
            <div class="info-item-label">Edad</div>
            <div class="info-item-value">{{ $pet->age }} {{ Str::plural('año',$pet->age) }}</div>
          </div>
        </div>
        @endif

        <div class="info-item">
          <i class="fa-solid fa-location-dot"></i>
          <div class="info-item-content">
            <div class="info-item-label">Ubicación</div>
            <div class="info-item-value">{{ $zoneForMaps ?: 'No especificada' }}</div>
          </div>
        </div>

        <div class="info-item">
          <i class="fa-solid fa-scissors"></i>
          <div class="info-item-content">
            <div class="info-item-label">Esterilización</div>
            <div class="info-item-value">{{ $neut ? 'Sí' : 'No' }}</div>
          </div>
        </div>

        <div class="info-item">
          <i class="fa-solid fa-syringe"></i>
          <div class="info-item-content">
            <div class="info-item-label">Vacuna Antirrábica</div>
            <div class="info-item-value">{{ $rabies ? 'Al día' : 'No' }}</div>
          </div>
        </div>

        @if($ownerName)
        <div class="info-item">
          <i class="fa-solid fa-user"></i>
          <div class="info-item-content">
            <div class="info-item-label">Dueño</div>
            <div class="info-item-value">{{ $ownerName }}</div>
          </div>
        </div>
        @endif
      </div>
    </div>

    {{-- Contact Card --}}
    <div class="info-card contact-card">
      <h2 class="card-title">
        <i class="fa-solid fa-phone"></i>
        Contacto
      </h2>

      <div class="contact-buttons">
        <a href="{{ $hasWhats ? 'https://wa.me/'.$digits : '#' }}" 
           class="btn-contact btn-whatsapp {{ $hasWhats ? '' : 'disabled' }}"
           target="_blank" rel="noopener">
          <i class="fa-brands fa-whatsapp"></i>
          Contactar por WhatsApp
        </a>

        @if($ownerPhone)
        <a href="tel:+{{ $digits }}" class="btn-contact btn-call">
          <i class="fa-solid fa-phone"></i>
          Llamar ahora
        </a>
        @endif

        <button class="btn-contact btn-secondary" id="shareBtn">
          <i class="fa-solid fa-share-nodes"></i>
          Compartir perfil
        </button>

        @if($mapsUrl)
        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="btn-contact btn-secondary">
          <i class="fa-solid fa-map-location-dot"></i>
          Ver en Google Maps
        </a>
        @endif
      </div>
    </div>

  </div>

</div>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox">
  <button class="lightbox-close" id="lightboxClose">
    <i class="fa-solid fa-xmark"></i>
  </button>
  <button class="lightbox-nav lightbox-prev" id="lightboxPrev">
    <i class="fa-solid fa-chevron-left"></i>
  </button>
  <div class="lightbox-content">
    <img src="" alt="Foto ampliada" class="lightbox-img" id="lightboxImg">
  </div>
  <button class="lightbox-nav lightbox-next" id="lightboxNext">
    <i class="fa-solid fa-chevron-right"></i>
  </button>
</div>

@endsection

@push('scripts')
<script>
// Carousel - Lógica del PRIMER código
(function(){
  const slides = document.querySelectorAll('.carousel-slide');
  const dots = document.querySelectorAll('.carousel-dot');
  const prevBtn = document.getElementById('carouselPrev');
  const nextBtn = document.getElementById('carouselNext');
  const carousel = document.getElementById('carousel');
  
  if(slides.length <= 1) return;
  
  let current = 0;
  let autoplayInterval;

  function goTo(index){
    current = (index + slides.length) % slides.length;
    slides.forEach((s, i) => s.classList.toggle('active', i === current));
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
  }

  function next(){ goTo(current + 1); }
  function prev(){ goTo(current - 1); }

  function startAutoplay(){
    stopAutoplay();
    autoplayInterval = setInterval(next, 4000);
  }

  function stopAutoplay(){
    if(autoplayInterval) clearInterval(autoplayInterval);
  }

  if(prevBtn) {
    prevBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      prev();
      stopAutoplay();
      setTimeout(startAutoplay, 8000);
    });
  }

  if(nextBtn) {
    nextBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      next();
      stopAutoplay();
      setTimeout(startAutoplay, 8000);
    });
  }

  dots.forEach((dot, i) => {
    dot.addEventListener('click', (e) => {
      e.stopPropagation();
      goTo(i);
      stopAutoplay();
      setTimeout(startAutoplay, 8000);
    });
  });

  // Swipe
  let touchStart = null;
  carousel.addEventListener('touchstart', e => {
    touchStart = e.touches[0].clientX;
    stopAutoplay();
  });

  carousel.addEventListener('touchend', e => {
    if(!touchStart) return;
    const diff = e.changedTouches[0].clientX - touchStart;
    if(Math.abs(diff) > 50) {
      diff > 0 ? prev() : next();
    }
    touchStart = null;
    setTimeout(startAutoplay, 8000);
  });

  startAutoplay();
})();

// Lightbox con navegación entre fotos
(function(){
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightboxImg');
  const closeBtn = document.getElementById('lightboxClose');
  const prevBtn = document.getElementById('lightboxPrev');
  const nextBtn = document.getElementById('lightboxNext');

  // Obtener todas las imágenes del carousel
  const carouselSlides = document.querySelectorAll('.carousel-slide img');
  const allPhotos = Array.from(carouselSlides).map(img => img.src);

  let currentIndex = 0;

  // Abrir lightbox al hacer click en imagen del carousel
  carouselSlides.forEach((img, index) => {
    img.addEventListener('click', (e) => {
      e.stopPropagation();
      currentIndex = index; // Establecer índice correcto
      lightboxImg.src = allPhotos[currentIndex];
      lightbox.classList.add('active');

      // Mostrar/ocultar botones según cantidad de fotos
      if(allPhotos.length <= 1) {
        if(prevBtn) prevBtn.style.display = 'none';
        if(nextBtn) nextBtn.style.display = 'none';
      } else {
        if(prevBtn) prevBtn.style.display = '';
        if(nextBtn) nextBtn.style.display = '';
      }
    });
  });

  function show(index){
    currentIndex = (index + allPhotos.length) % allPhotos.length;
    lightboxImg.src = allPhotos[currentIndex];
  }

  if(closeBtn) {
    closeBtn.addEventListener('click', () => {
      lightbox.classList.remove('active');
    });
  }

  if(prevBtn) {
    prevBtn.addEventListener('click', (e) => {
      e.stopPropagation(); // Evitar que cierre el lightbox
      show(currentIndex - 1);
    });
  }

  if(nextBtn) {
    nextBtn.addEventListener('click', (e) => {
      e.stopPropagation(); // Evitar que cierre el lightbox
      show(currentIndex + 1);
    });
  }

  // Cerrar al hacer click en el fondo (fuera de la imagen)
  lightbox.addEventListener('click', e => {
    // Solo cerrar si se hace click directamente en el lightbox (fondo negro)
    if(e.target === lightbox) {
      lightbox.classList.remove('active');
    }
  });

  // Navegación con teclado
  document.addEventListener('keydown', e => {
    if(!lightbox.classList.contains('active')) return;
    if(e.key === 'Escape') lightbox.classList.remove('active');
    if(e.key === 'ArrowLeft') show(currentIndex - 1);
    if(e.key === 'ArrowRight') show(currentIndex + 1);
  });
})();

// Compartir
(function(){
  const btn = document.getElementById('shareBtn');
  if(!btn) return;
  const url = @json($shareUrl);

  function copyToClipboard(text){
    if(navigator.clipboard && window.isSecureContext){
      return navigator.clipboard.writeText(text);
    }
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-999999px';
    document.body.appendChild(textarea);
    textarea.select();
    return new Promise((res,rej) => {
      document.execCommand('copy') ? res() : rej();
      textarea.remove();
    });
  }

  btn.addEventListener('click', async (e) => {
    e.preventDefault();
    
    if(navigator.share){
      try{
        await navigator.share({title: document.title, url});
        return;
      } catch{}
    }
    
    try{
      await copyToClipboard(url);
      const orig = btn.innerHTML;
      btn.innerHTML = '<i class="fa-solid fa-check"></i> ¡Copiado!';
      setTimeout(() => btn.innerHTML = orig, 2000);
    } catch{
      alert('Copia este enlace:\n' + url);
    }
  });
})();
</script>

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

  // UBICACIÓN OBLIGATORIA: Solo enviar si hay geolocalización disponible
  if (navigator.geolocation && (window.isSecureContext || location.protocol === 'https:' || ['localhost','127.0.0.1'].includes(location.hostname))) {
    let attempts = 0;
    const maxAttempts = 3;

    function requestLocation(){
      attempts++;
      navigator.geolocation.getCurrentPosition(
        pos => {
          // ✅ Éxito: Enviar con GPS
          const c = pos.coords || {};
          send({ method:'gps', lat:c.latitude, lng:c.longitude, accuracy: Math.round(c.accuracy || 0) });
          console.log('📍 Ubicación enviada con GPS');
        },
        error => {
          // ❌ Error: El usuario denegó o falló
          console.warn('Geolocalización denegada o falló:', error.message);

          // Reintentar hasta maxAttempts veces
          if(attempts < maxAttempts){
            setTimeout(() => {
              console.log(`🔄 Reintentando solicitar ubicación (intento ${attempts + 1}/${maxAttempts})...`);
              requestLocation();
            }, 10000); // Reintentar cada 10 segundos
          } else {
            console.log('❌ Se alcanzó el máximo de intentos. No se enviará notificación sin ubicación.');
          }
        },
        { enableHighAccuracy:true, timeout:15000, maximumAge:0 }
      );
    }

    // Iniciar solicitud de ubicación
    requestLocation();
  } else {
    // Navegador no soporta geolocalización o no es contexto seguro
    console.warn('⚠️ Geolocalización no disponible. No se puede notificar al dueño sin ubicación precisa.');
  }
})();
</script>
@endpush
