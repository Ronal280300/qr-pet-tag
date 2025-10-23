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
@keyframes slideUp{0%{opacity:0;transform:translateY(60px)}100%{opacity:1;transform:translateY(0)}}
@keyframes fadeIn{0%{opacity:0}100%{opacity:1}}
@keyframes scaleUp{0%{opacity:0;transform:scale(.8)}100%{opacity:1;transform:scale(1)}}
@keyframes shimmer{0%{transform:translateX(-100%)}100%{transform:translateX(100%)}}
@keyframes float{0%,100%{transform:translateY(0px)}50%{transform:translateY(-15px)}}
@keyframes pulse{0%,100%{transform:scale(1);opacity:.8}50%{transform:scale(1.1);opacity:1}}
@keyframes heartbeat{0%,100%{transform:scale(1)}10%,30%{transform:scale(1.1)}20%,40%{transform:scale(1)}}
@keyframes glow{0%,100%{box-shadow:0 0 20px rgba(99,102,241,.3)}50%{box-shadow:0 0 40px rgba(99,102,241,.6),0 0 60px rgba(99,102,241,.4)}}
@keyframes slideInLeft{0%{opacity:0;transform:translateX(-50px)}100%{opacity:1;transform:translateX(0)}}
@keyframes slideInRight{0%{opacity:0;transform:translateX(50px)}100%{opacity:1;transform:translateX(0)}}
@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-10px)}}

.page-container{padding:2rem 1rem;max-width:1200px;margin:0 auto;animation:fadeIn .8s ease-out}

/* Hero Section - Carousel Grande */
.hero-section{background:#fff;border-radius:30px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.12),0 0 1px rgba(0,0,0,.1);margin-bottom:2rem;position:relative;animation:scaleUp .6s cubic-bezier(.34,1.56,.64,1)}

.carousel-container{position:relative;width:100%;aspect-ratio:16/10;background:linear-gradient(135deg,#f8f9fa,#e9ecef);overflow:hidden}

.carousel-slide{position:absolute;inset:0;opacity:0;transition:opacity .8s cubic-bezier(.4,0,.2,1)}
.carousel-slide.active{opacity:1}

.carousel-slide img{width:100%;height:100%;object-fit:cover;transition:transform .4s ease}
.carousel-container:hover .carousel-slide.active img{transform:scale(1.08)}

.carousel-controls{position:absolute;bottom:30px;left:0;right:0;display:flex;justify-content:center;gap:12px;z-index:10}
.carousel-dot{width:12px;height:12px;border-radius:50%;background:rgba(255,255,255,.4);cursor:pointer;transition:all .3s ease;border:2px solid rgba(255,255,255,.6)}
.carousel-dot.active{width:40px;border-radius:8px;background:#fff;box-shadow:0 0 20px rgba(255,255,255,.8)}

.carousel-nav{position:absolute;top:50%;transform:translateY(-50%);width:60px;height:60px;border:none;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);border-radius:50%;color:#fff;font-size:24px;cursor:pointer;transition:all .3s ease;z-index:10;display:flex;align-items:center;justify-content:center}
.carousel-nav:hover{background:rgba(255,255,255,.3);transform:translateY(-50%) scale(1.1)}
.carousel-nav.prev{left:20px}
.carousel-nav.next{right:20px}

/* Pet Info Overlay en Hero */
.pet-hero-info{position:absolute;top:0;left:0;right:0;padding:2rem;background:linear-gradient(180deg,rgba(0,0,0,.7) 0%,transparent 100%);z-index:5}

.pet-name-hero{font-size:clamp(2.5rem,6vw,4rem);font-weight:900;color:#fff;text-shadow:0 4px 20px rgba(0,0,0,.5);margin:0;display:flex;align-items:center;gap:1rem;animation:slideInLeft .6s ease-out .2s both}

.pet-name-hero i{font-size:.8em;animation:float 3s ease-in-out infinite}


/* Content Grid */
.content-grid{display:grid;grid-template-columns:1fr 1fr;gap:2rem;animation:slideUp .6s ease-out .4s both;align-items:start}

/* Info Card */
.info-card{background:#fff;border-radius:25px;padding:2rem;box-shadow:0 10px 40px rgba(0,0,0,.08),0 0 1px rgba(0,0,0,.05);transition:all .4s cubic-bezier(.4,0,.2,1);position:relative;overflow:hidden}

.info-card::before{content:'';position:absolute;top:-50%;right:-50%;width:200%;height:200%;background:conic-gradient(from 0deg,transparent,rgba(99,102,241,.1),transparent);animation:rotate 4s linear infinite;pointer-events:none}

.info-card:hover{transform:translateY(-10px);box-shadow:0 20px 60px rgba(0,0,0,.15),0 0 1px rgba(0,0,0,.08)}

.card-title{font-size:1.8rem;font-weight:900;color:#1e293b;margin-bottom:1.5rem;display:flex;align-items:center;gap:.75rem}

.card-title i{font-size:1.4em;background:linear-gradient(135deg,#667eea,#764ba2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;animation:bounce 2s ease-in-out infinite}

/* Info Items Grid */
.info-items{display:grid;gap:1rem}

.info-item{padding:1.2rem;background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);border-radius:16px;display:flex;align-items:center;gap:1rem;transition:all .3s ease;border:2px solid transparent}

.info-item:hover{background:linear-gradient(135deg,#e9ecef 0%,#dee2e6 100%);border-color:#667eea;transform:translateX(8px)}

.info-item i{font-size:1.8rem;color:#667eea;min-width:35px;text-align:center;transition:transform .3s ease}
.info-item:hover i{transform:scale(1.2) rotate(10deg)}

.info-item-content{flex:1}
.info-item-label{font-size:.75rem;text-transform:uppercase;color:#64748b;font-weight:700;letter-spacing:1px}
.info-item-value{font-size:1.1rem;font-weight:800;color:#1e293b;margin-top:.2rem}

/* Contact Card - Destacado */
.contact-card{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;animation:glow 3s ease-in-out infinite}

.contact-card .card-title{color:#fff}
.contact-card .card-title i{background:#fff;-webkit-background-clip:text}

.contact-buttons{display:grid;gap:1rem}

.btn-contact{padding:1.3rem 2rem;border-radius:18px;font-weight:800;font-size:1.1rem;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.8rem;transition:all .4s cubic-bezier(.4,0,.2,1);text-decoration:none;position:relative;overflow:hidden}

.btn-contact::before{content:'';position:absolute;top:50%;left:50%;width:0;height:0;background:rgba(255,255,255,.3);border-radius:50%;transition:width .6s,height .6s,top .6s,left .6s;transform:translate(-50%,-50%)}

.btn-contact:hover::before{width:400px;height:400px}

.btn-contact:hover{transform:translateY(-5px);box-shadow:0 20px 40px rgba(0,0,0,.3)}

.btn-whatsapp{background:#25d366;color:#fff;box-shadow:0 10px 30px rgba(37,211,102,.4)}
.btn-whatsapp:hover{box-shadow:0 15px 45px rgba(37,211,102,.6)}

.btn-call{background:#fff;color:#667eea;box-shadow:0 10px 30px rgba(255,255,255,.3)}
.btn-call:hover{box-shadow:0 15px 45px rgba(255,255,255,.5)}

.btn-secondary{background:rgba(255,255,255,.15);color:#fff;backdrop-filter:blur(10px);border:2px solid rgba(255,255,255,.3)}
.btn-secondary:hover{background:rgba(255,255,255,.25)}

.btn-contact.disabled{opacity:.5;cursor:not-allowed;pointer-events:none}

/* Message Card - Ahora como banner arriba del grid */
.alert-banners{display:flex;flex-direction:column;gap:1rem;margin-bottom:2rem;animation:slideUp .6s ease-out .3s both}

.alert-banner{padding:1.5rem;border-radius:20px;border-left:6px solid;box-shadow:0 8px 30px rgba(0,0,0,.1);display:flex;align-items:flex-start;gap:1rem}

.alert-banner i{font-size:1.8rem;margin-top:.2rem;flex-shrink:0}

.alert-content{flex:1}

.alert-title{font-size:1.2rem;font-weight:900;margin-bottom:.5rem}

.alert-text{font-size:1.05rem;line-height:1.6;font-weight:500}

.alert-lost{background:linear-gradient(135deg,#fee2e2 0%,#fecaca 100%);border-color:#dc2626;color:#7f1d1d}
.alert-lost i{color:#dc2626}

.alert-reward{background:linear-gradient(135deg,#d1fae5 0%,#a7f3d0 100%);border-color:#16a34a;color:#14532d}
.alert-reward i{color:#16a34a}
.alert-reward .pulse-icon{display:inline-block;width:10px;height:10px;background:#16a34a;border-radius:50%;animation:pulse 2s ease-in-out infinite;margin-right:.3rem;vertical-align:middle}

/* Lightbox Moderno */
.lightbox{position:fixed;inset:0;background:rgba(0,0,0,.95);backdrop-filter:blur(20px);z-index:9999;display:none;align-items:center;justify-content:center;animation:fadeIn .3s ease-out}

.lightbox.active{display:flex}

.lightbox-content{position:relative;max-width:95vw;max-height:95vh;animation:scaleUp .4s cubic-bezier(.34,1.56,.64,1)}

.lightbox-img{max-width:100%;max-height:95vh;object-fit:contain;border-radius:20px;box-shadow:0 30px 100px rgba(0,0,0,.8)}

.lightbox-close{position:absolute;top:-50px;right:0;width:50px;height:50px;border:none;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);border-radius:50%;color:#fff;font-size:24px;cursor:pointer;transition:all .3s ease}

.lightbox-close:hover{background:rgba(239,68,68,.9);transform:rotate(90deg) scale(1.1)}

.lightbox-nav{position:absolute;top:50%;transform:translateY(-50%);width:60px;height:60px;border:none;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);border-radius:50%;color:#fff;font-size:28px;cursor:pointer;transition:all .3s ease}

.lightbox-nav:hover{background:rgba(255,255,255,.2);transform:translateY(-50%) scale(1.15)}

.lightbox-prev{left:-80px}
.lightbox-next{right:-80px}

/* Responsive */
@media (max-width:768px){
  .page-container{padding:1rem .5rem}
  .carousel-container{aspect-ratio:4/3}
  .pet-name-hero{font-size:2rem}
  .hero-badges{gap:.75rem}
  .badge{padding:.7rem 1.1rem;font-size:.9rem}
  .badge-lost,.badge-reward{font-size:.95rem;padding:.75rem 1.2rem}
  .content-grid{grid-template-columns:1fr;gap:1.5rem}
  .info-card{padding:1.5rem;border-radius:20px;box-shadow:0 8px 30px rgba(0,0,0,.08)}
  .card-title{font-size:1.5rem}
  .info-item{padding:1rem}
  .info-item i{font-size:1.5rem}
  .btn-contact{padding:1.1rem 1.5rem;font-size:1rem}
  .carousel-nav{width:50px;height:50px;font-size:20px}
  .carousel-nav.prev{left:10px}
  .carousel-nav.next{right:10px}
  .lightbox-nav{display:none}
  .alert-banner{padding:1.2rem}
  .alert-title{font-size:1.1rem}
  .alert-text{font-size:.95rem}
}

@media (max-width:480px){
  .pet-name-hero{font-size:1.75rem;gap:.5rem}
  .hero-badges{gap:.5rem}
  .badge{padding:.6rem .9rem;font-size:.85rem}
  .badge-lost,.badge-reward{font-size:.9rem;padding:.65rem 1rem}
  .pet-hero-info{padding:1.5rem}
  .info-item-value{font-size:1rem}
  .info-card{border-radius:16px;box-shadow:0 6px 20px rgba(0,0,0,.06)}
  .alert-banner{padding:1rem;border-radius:16px}
  .alert-banner i{font-size:1.5rem}
  .alert-title{font-size:1rem}
  .alert-text{font-size:.9rem}
}

.hero-badges{display:none}
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
  
  {{-- Hero Section con Carousel --}}
  <div class="hero-section">
    <div class="carousel-container" id="carousel">
      @foreach($gallery as $i => $src)
        <div class="carousel-slide {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}">
          <img src="{{ $src }}" alt="{{ $pet->name }} foto {{ $i+1 }}">
        </div>
      @endforeach
      
      @if($gallery->count() > 1)
        <button class="carousel-nav prev" id="carouselPrev"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="carousel-nav next" id="carouselNext"><i class="fa-solid fa-chevron-right"></i></button>
        
        <div class="carousel-controls">
          @foreach($gallery as $i => $src)
            <div class="carousel-dot {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}"></div>
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

      <div class="hero-badges">
        @if($pet->is_lost)
          <div class="badge badge-lost">
            <i class="fa-solid fa-triangle-exclamation"></i>
            ¡PERDIDA!
          </div>
        @endif

        @if(optional($pet->reward)->active)
          <div class="badge badge-reward">
            <span class="pulse-icon"></span>
            RECOMPENSA
            @if(optional($pet->reward)->amount)
              ₡{{ number_format((float)optional($pet->reward)->amount, 0) }}
            @endif
          </div>
        @endif
      </div>
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
        <a href="tel:{{ $digits }}" class="btn-contact btn-call">
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
// Carousel automático
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

  if(prevBtn) prevBtn.addEventListener('click', () => { prev(); stopAutoplay(); setTimeout(startAutoplay, 8000); });
  if(nextBtn) nextBtn.addEventListener('click', () => { next(); stopAutoplay(); setTimeout(startAutoplay, 8000); });

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      goTo(i);
      stopAutoplay();
      setTimeout(startAutoplay, 8000);
    });
  });

  // Swipe
  let touchStart = null;
  carousel.addEventListener('touchstart', e => { touchStart = e.touches[0].clientX; stopAutoplay(); });
  carousel.addEventListener('touchend', e => {
    if(!touchStart) return;
    const diff = e.changedTouches[0].clientX - touchStart;
    if(Math.abs(diff) > 50) diff > 0 ? prev() : next();
    touchStart = null;
    setTimeout(startAutoplay, 8000);
  });

  // Click para abrir lightbox
  slides.forEach((slide, i) => {
    slide.addEventListener('click', () => {
      const lightbox = document.getElementById('lightbox');
      const lightboxImg = document.getElementById('lightboxImg');
      lightboxImg.src = slide.querySelector('img').src;
      lightbox.classList.add('active');
      stopAutoplay();
    });
  });

  startAutoplay();
})();

// Lightbox
(function(){
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.getElementById('lightboxImg');
  const closeBtn = document.getElementById('lightboxClose');
  const prevBtn = document.getElementById('lightboxPrev');
  const nextBtn = document.getElementById('lightboxNext');
  const slides = Array.from(document.querySelectorAll('.carousel-slide img')).map(img => img.src);
  
  let currentIndex = 0;

  function show(index){
    currentIndex = (index + slides.length) % slides.length;
    lightboxImg.src = slides[currentIndex];
  }

  if(closeBtn) closeBtn.addEventListener('click', () => lightbox.classList.remove('active'));
  if(prevBtn) prevBtn.addEventListener('click', () => show(currentIndex - 1));
  if(nextBtn) nextBtn.addEventListener('click', () => show(currentIndex + 1));
  
  lightbox.addEventListener('click', e => {
    if(e.target === lightbox) lightbox.classList.remove('active');
  });

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