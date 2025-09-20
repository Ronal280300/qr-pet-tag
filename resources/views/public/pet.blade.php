{{-- resources/views/public/pet.blade.php --}}
@extends('layouts.public')
@section('title', $pet->name)

@php
  use Illuminate\Support\Facades\Storage;

  // Galería: fotos múltiples > foto principal > placeholder
  $photosRel = method_exists($pet,'photos') ? $pet->photos : collect();
  $gallery   = collect();

  if ($photosRel && $photosRel->count() > 0) {
      $gallery = $photosRel->map(fn($ph) => Storage::url($ph->path));
  } elseif ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
      $gallery = collect([ Storage::url($pet->photo) ]);
  } else {
      $gallery = collect(['https://placehold.co/1200x1200?text='.urlencode($pet->name)]);
  }

  // Dueño
  $ownerName  = optional($owner)->name;
  $ownerPhone = optional($owner)->phone;
  $digits     = preg_replace('/\D+/', '', (string) $ownerPhone);
  $hasWhats   = !empty($digits);

  // Maps
  $zoneForMaps = $pet->full_location ?: ($pet->zone ?: null);
  $mapsUrl     = $zoneForMaps ? ('https://www.google.com/maps/search/?api=1&query=' . urlencode($zoneForMaps)) : null;

  // Sexo
  $sex = $pet->sex ?? 'unknown';
  $sexIcon = $sex === 'male' ? 'fa-mars' : ($sex === 'female' ? 'fa-venus' : 'fa-circle-question');
  $sexText = $sex === 'male' ? 'Macho' : ($sex === 'female' ? 'Hembra' : 'Sexo N/D');

  // Salud
  $neut   = (bool) ($pet->is_neutered ?? false);
  $rabies = (bool) ($pet->rabies_vaccine ?? false);

  // URL actual para compartir
  $shareUrl = url()->current();
@endphp

@push('styles')
<style>
  :root{
    --ink:#0f172a; --muted:#64748b; --surface:#fff; --bg:#f5f7fb;
    --stroke:rgba(2,6,23,.08); --brand:#115dfc; --wa:#25d366;
  }
  .page-wrap{background:var(--bg)}
  .shell{
    max-width:960px;margin-inline:auto;background:var(--surface);
    border:1px solid var(--stroke);border-radius:28px;box-shadow:0 28px 80px rgba(2,6,23,.10);
    padding:clamp(16px,4vw,28px)
  }

  /* Banners */
  .banner{border-radius:16px;padding:.85rem 1rem;border:1px solid var(--stroke);background:#fff;margin-bottom:12px}
  .lost{background:#fff1f2;color:#9f1239;border-color:#ffe4e6}
  .reward{background:#f0fdf4;color:#065f46;border-color:#dcfce7}
  .pulse{width:10px;height:10px;border-radius:999px;background:#16a34a;margin-right:.5rem;
         box-shadow:0 0 0 0 rgba(22,163,74,.5);animation:pulse 1.8s infinite}
  @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(22,163,74,.5)}70%{box-shadow:0 0 0 16px rgba(22,163,74,0)}100%{box-shadow:0 0 0 0 rgba(22,163,74,0)}}

  /* Avatar circular con slides */
  .avatar-wrap{display:flex;justify-content:center;margin-top:.25rem}
  .avatar{
    width:clamp(220px,58vw,360px);aspect-ratio:1/1;border-radius:999px;overflow:hidden;
    border:8px solid #fff;box-shadow:0 20px 60px rgba(2,6,23,.12);position:relative;background:#fff;cursor:zoom-in
  }
  .avatar-slide{position:absolute;inset:0;opacity:0;transition:opacity .35s ease}
  .avatar-slide.active{opacity:1}
  .avatar img{width:100%;height:100%;object-fit:cover;background:#f3f6fb;-webkit-user-drag:none;user-select:none}
  .avatar-dots{position:absolute;left:0;right:0;bottom:10px;display:flex;gap:6px;justify-content:center}
  .avatar-dot{width:8px;height:8px;border-radius:999px;background:rgba(255,255,255,.55)}
  .avatar-dot.active{background:#fff}

  /* Títulos + chips */
  .pet-title{font-weight:900;letter-spacing:.3px;color:var(--ink);text-align:center;
             font-size:clamp(28px,7vw,46px);margin:16px 0 10px}
  .chips{display:flex;justify-content:center;gap:.5rem;flex-wrap:wrap;margin-bottom:10px}
  .chip{display:inline-flex;align-items:center;gap:.45rem;background:#eef2ff;color:#1e40af;border:1px solid #e0e7ff;
        padding:.5rem .8rem;border-radius:999px;font-weight:800}
  .chip i{color:#115dfc}

  /* Salud */
  .health{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin:10px 0 4px}
  @media (min-width:540px){ .health{grid-template-columns:repeat(4,minmax(0,1fr))} }
  .h-card{background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;padding:.65rem .8rem;display:flex;align-items:center;gap:.5rem;font-weight:700;color:#1f2937}
  .h-card i{color:#64748b}

  /* Secciones / CTAs */
  .section-title{font-weight:900;color:var(--ink);font-size:clamp(20px,4.8vw,28px);margin:18px 0 12px}
  .btn-wa,.btn-call,.btn-share,.btn-maps{text-decoration:none !important}
  .btn-wa{display:flex;align-items:center;justify-content:center;gap:.55rem;width:100%;
          border:0;color:#fff;background:var(--wa);padding:.9rem 1.1rem;border-radius:14px;font-weight:900;
          box-shadow:0 14px 38px rgba(37,211,102,.28)}
  .btn-call{display:flex;align-items:center;justify-content:center;gap:.55rem;width:100%;
            border:1px solid #e7ecf4;color:#1f2b3d;background:#eff4fa;padding:.9rem 1.1rem;border-radius:14px;font-weight:900}
  .btn-share,.btn-maps{display:flex;align-items:center;justify-content:center;gap:.55rem;width:100%;
            border:1px solid #e7ecf4;color:#0b3a83;background:#f0f5ff;padding:.9rem 1.1rem;border-radius:14px;font-weight:900}
  .thanks{text-align:center;color:#334155;margin-top:18px;font-size:clamp(.95rem,4vw,1.05rem)}

  /* Lightbox */
  .lb{position:fixed;inset:0;background:rgba(7,12,20,.9);display:none;z-index:1050;align-items:center;justify-content:center}
  .lb.open{display:flex}
  .lb-img{max-width:92vw;max-height:88vh;object-fit:contain;border-radius:12px;background:#000;box-shadow:0 10px 40px rgba(0,0,0,.5)}
  .lb-btn{position:absolute;top:50%;transform:translateY(-50%);border:none;width:48px;height:48px;border-radius:999px;
          background:rgba(255,255,255,.16);color:#fff;display:flex;align-items:center;justify-content:center}
  .lb-btn:hover{background:rgba(255,255,255,.24)}
  .lb-prev{left:18px} .lb-next{right:18px}
  .lb-close{position:absolute;top:14px;right:14px;width:42px;height:42px;border-radius:999px;border:none;background:rgba(255,255,255,.2);color:#fff}
  .lb-close:hover{background:rgba(255,255,255,.28)}
</style>
@endpush

@section('content')
<div class="page-wrap py-4">
  <div class="shell">

    {{-- Banners --}}
    @if($pet->is_lost)
      <div class="banner lost">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <strong>¡Mascota reportada como perdida!</strong> Si tienes información, por favor contacta a su dueño.
      </div>
    @endif

    @if(optional($pet->reward)->active)
      <div class="banner reward d-flex align-items-center">
        <span class="pulse"></span>
        <div>
          <strong>Recompensa activa.</strong>
          @if(optional($pet->reward)->amount)
            Monto: ₡{{ number_format((float)optional($pet->reward)->amount, 2) }}.
          @endif
          @if(optional($pet->reward)->message)
            <div class="small mt-1">{{ optional($pet->reward)->message }}</div>
          @endif
        </div>
      </div>
    @endif

    {{-- Avatar + dots (click => lightbox) --}}
    <div class="avatar-wrap">
      <div class="avatar" id="avatar">
        @foreach($gallery as $i => $src)
          <div class="avatar-slide {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}">
            <img src="{{ $src }}" alt="{{ $pet->name }} {{ $i+1 }}">
          </div>
        @endforeach

        @if($gallery->count() > 1)
          <div class="avatar-dots" id="avatarDots">
            @foreach($gallery as $i => $src)
              <div class="avatar-dot {{ $i===0 ? 'active' : '' }}" data-to="{{ $i }}"></div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    {{-- Nombre + chips --}}
    <h1 class="pet-title">
      @if($sex === 'male')
        <i class="fa-solid fa-mars me-1 text-primary"></i>
      @elseif($sex === 'female')
        <i class="fa-solid fa-venus me-1 text-primary"></i>
      @else
        <i class="fa-solid fa-circle-question me-1 text-secondary"></i>
      @endif
      {{ $pet->name }}
    </h1>

    <div class="chips">
      <span class="chip"><i class="fa-solid fa-bone"></i>{{ $pet->breed ?: 'Sin raza' }}</span>
      <span class="chip"><i class="fa-solid fa-location-dot"></i>{{ $zoneForMaps ?: 'Ubicación N/D' }}</span>
      @if($pet->age !== null)
        <span class="chip"><i class="fa-solid fa-cake-candles"></i>{{ $pet->age }} {{ Str::plural('año',$pet->age) }}</span>
      @endif
      <span class="chip"><i class="fa-solid {{ $sexIcon }}"></i>{{ $sexText }}</span>
    </div>

    {{-- Salud rápida --}}
    <div class="health">
      <div class="h-card" title="Esterilizado/a">
        <i class="fa-solid fa-scissors"></i>
        {{ $neut ? 'Esterilizado' : 'Sin esterilizar' }}
      </div>
      <div class="h-card" title="Vacuna antirrábica">
        <i class="fa-solid fa-syringe"></i>
        {{ $rabies ? 'Antirrábica al día' : 'Antirrábica N/D' }}
      </div>
      @if($ownerName)
        <div class="h-card" title="Dueño">
          <i class="fa-solid fa-user"></i>{{ $ownerName }}
        </div>
      @endif
      @if($zoneForMaps)
        <div class="h-card" title="Ver en Google Maps">
          <i class="fa-solid fa-map-location-dot"></i>Zona aproximada
        </div>
      @endif
    </div>

    {{-- Contacto --}}
    <div class="section-title">Contacto</div>

    <div class="d-grid gap-2">
      <a id="waBtn" class="btn-wa {{ $hasWhats ? '' : 'disabled opacity-50' }}"
         @if($hasWhats) href="https://wa.me/{{ $digits }}" target="_blank" rel="noopener" @else href="#" @endif>
        <i class="fa-brands fa-whatsapp"></i> Contactar por WhatsApp
      </a>

      @if($ownerPhone)
        <a class="btn-call" href="tel:{{ $digits }}"><i class="fa-solid fa-phone"></i> Llamar</a>
      @endif

      <div class="d-grid gap-2 gap-sm-3 grid-sm-2" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr))">
        <a class="btn-share" id="shareBtn" href="#">
          <i class="fa-solid fa-share-nodes"></i> Compartir perfil
        </a>
        @if($mapsUrl)
          <a class="btn-maps" href="{{ $mapsUrl }}" target="_blank" rel="noopener">
            <i class="fa-solid fa-map"></i> Ver en Google Maps
          </a>
        @endif
      </div>
    </div>

    <p class="thanks">
      Si encontraste a <strong>{{ $pet->name }}</strong>, ¡gracias por ayudar!
    </p>
  </div>
</div>

{{-- LIGHTBOX --}}
<div class="lb" id="lb" aria-hidden="true">
  <button class="lb-close" id="lbClose" aria-label="Cerrar"><i class="fa-solid fa-xmark"></i></button>
  <button class="lb-btn lb-prev" id="lbPrev" aria-label="Anterior"><i class="fa-solid fa-chevron-left"></i></button>
  <img class="lb-img" id="lbImg" src="" alt="Foto">
  <button class="lb-btn lb-next" id="lbNext" aria-label="Siguiente"><i class="fa-solid fa-chevron-right"></i></button>
</div>

<script>
  // Mini slider + Lightbox
  (function(){
    const slides = Array.from(document.querySelectorAll('.avatar-slide'));
    const dots   = Array.from(document.querySelectorAll('.avatar-dot'));
    const avatar = document.getElementById('avatar');
    if(!slides.length) return;

    let idx = 0;
    const lb     = document.getElementById('lb');
    const lbImg  = document.getElementById('lbImg');
    const lbPrev = document.getElementById('lbPrev');
    const lbNext = document.getElementById('lbNext');
    const lbClose= document.getElementById('lbClose');

    function goTo(i){
      idx = (i+slides.length)%slides.length;
      slides.forEach((s,k)=> s.classList.toggle('active', k===idx));
      dots.forEach((d,k)=> d.classList.toggle('active', k===idx));
    }
    function openLb(i){ goTo(i); lbImg.src = slides[idx].querySelector('img').src; lb.classList.add('open'); lb.setAttribute('aria-hidden','false'); }
    function closeLb(){ lb.classList.remove('open'); lb.setAttribute('aria-hidden','true'); setTimeout(()=>{ lbImg.src='' },120); }
    function next(){ goTo(idx+1); lbImg.src = slides[idx].querySelector('img').src; }
    function prev(){ goTo(idx-1); lbImg.src = slides[idx].querySelector('img').src; }

    dots.forEach(d=> d.addEventListener('click', e=>{ const to = +e.currentTarget.dataset.to; if(Number.isInteger(to)) goTo(to); }, {passive:true}));
    // swipe avatar
    let sx=null;
    avatar.addEventListener('touchstart', e=>{ sx=e.touches[0].clientX }, {passive:true});
    avatar.addEventListener('touchend', e=>{
      if(sx===null) return;
      const dx = e.changedTouches[0].clientX - sx;
      if(Math.abs(dx)>40) goTo(idx+(dx<0?1:-1));
      sx=null;
    }, {passive:true});
    // click abre
    avatar.addEventListener('click', e=>{
      const slideEl = e.target.closest('.avatar-slide');
      const i = slideEl ? +slideEl.dataset.index : idx;
      openLb(Number.isInteger(i)? i : idx);
    });

    // lightbox controles
    lbNext.addEventListener('click', next);
    lbPrev.addEventListener('click', prev);
    lbClose.addEventListener('click', closeLb);
    lb.addEventListener('click', e=>{ if(e.target===lb) closeLb(); });
    document.addEventListener('keydown', e=>{
      if(!lb.classList.contains('open')) return;
      if(e.key==='Escape') closeLb();
      else if(e.key==='ArrowRight') next();
      else if(e.key==='ArrowLeft') prev();
    });

    // accesibilidad: foco
    lb.addEventListener('transitionend', ()=>{ if(lb.classList.contains('open')) lbNext.focus({preventScroll:true}); }, {passive:true});
  })();

  // Compartir (Web Share API con fallback de copiar)
  (function(){
    const btn = document.getElementById('shareBtn');
    if(!btn) return;
    const url = @json($shareUrl);
    btn.addEventListener('click', async (e)=>{
      e.preventDefault();
      if(navigator.share){
        try{ await navigator.share({title: document.title, url}); }catch{}
        return;
      }
      try{
        await navigator.clipboard.writeText(url);
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Enlace copiado';
        setTimeout(()=>btn.innerHTML = '<i class="fa-solid fa-share-nodes"></i> Compartir perfil', 1400);
      }catch{
        alert('Copia este enlace:\n' + url);
      }
    });
  })();
</script>
@endsection
 
@section('content')
  {{-- ... tu contenido del perfil público ... --}}
@endsection

@php
  // Asegura tener el slug disponible en la vista
  $slug = $slug ?? ($pet->qrCode->slug ?? null);
@endphp


 @push('scripts')
<script>
(function autoPing(){
  // ⚠️ URL RELATIVA (sin http/https) para evitar mixed content en cualquier escenario
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



  ////INICIO CAMBIO GPS
  // Intentamos GPS; si se bloquea o falla → IP
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
  // Sin HTTPS o sin API → IP
  send({ method:'ip' });
}
})();
////FIN CAMBIO GPS
</script>
@endpush




