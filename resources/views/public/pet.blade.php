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

  $ownerName  = optional($owner)->name;
  $ownerPhone = optional($owner)->phone;
  $hasWhats   = !empty(preg_replace('/\D+/','',$ownerPhone ?? ''));
@endphp

@push('styles')
<style>
  :root{
    --ink:#0F172A; --muted:#64748B; --surface:#FFFFFF; --bg:#F5F7FB;
    --stroke:rgba(2,6,23,.08); --brand:#1E7CF2; --wa:#25D366;
  }
  .page-wrap{background:var(--bg)}
  .card-shell{
    max-width: 860px; margin-inline: auto; background: var(--surface);
    border: 1px solid var(--stroke); border-radius: 28px;
    box-shadow: 0 30px 80px rgba(2,6,23,.10);
    padding: clamp(18px, 4vw, 28px);
  }

  /* Foto redonda + “carousel” simple (sin JS) */
  .avatar-wrap{ display:flex; justify-content:center; margin-top:.25rem; }
  .avatar{
    width: clamp(220px, 58vw, 360px); aspect-ratio:1/1; border-radius:999px;
    overflow:hidden; border:8px solid #fff; box-shadow:0 20px 60px rgba(2,6,23,.12);
    position:relative; background:#fff; cursor: zoom-in;
  }
  .avatar-inner{ width:100%; height:100%; position:relative; }
  .avatar-slide{ position:absolute; inset:0; opacity:0; transition:opacity .35s ease; }
  .avatar-slide.active{ opacity:1; }
  .avatar img{ width:100%; height:100%; object-fit:cover; background:#f3f6fb; user-select:none; -webkit-user-drag:none; }
  .avatar-dots{ position:absolute; left:0; right:0; bottom:10px; display:flex; gap:6px; justify-content:center; }
  .avatar-dot{ width:8px; height:8px; border-radius:999px; background:rgba(255,255,255,.6) }
  .avatar-dot.active{ background:#fff }

  .pet-title{
    font-weight:900; letter-spacing:.3px; color:var(--ink); text-align:center;
    font-size:clamp(28px,7vw,48px); margin:16px 0 10px;
  }
  .chips{ display:flex; justify-content:center; gap:.6rem; flex-wrap:wrap; margin-bottom:10px; }
  .chip{
    display:inline-flex; align-items:center; gap:.5rem; background:#F0F5FF; color:#0B3A83;
    border:1px solid #E5EEFF; padding:.55rem .9rem; border-radius:999px; font-weight:700;
  }
  .chip i{ color:#1E7CF2; }

  .section-title{ font-weight:900; color:var(--ink); font-size:clamp(20px,4.6vw,30px); margin:22px 0 14px; }
  .owner-row{ display:flex; align-items:center; gap:.8rem; color:var(--ink); font-weight:700; font-size:clamp(1rem,4.2vw,1.2rem); margin-bottom:12px; }

  .btn-wa,
  .btn-call{
    text-decoration:none !important;   /* pedido: sin subrayado */
  }
  .btn-wa{
    display:flex; align-items:center; justify-content:center; gap:.6rem; width:100%;
    border:0; color:white; background:var(--wa); padding:.9rem 1.1rem; border-radius:14px;
    font-weight:800; font-size:clamp(1rem,4.7vw,1.2rem); box-shadow:0 14px 38px rgba(37,211,102,.28);
  }
  .btn-call{
    display:flex; align-items:center; justify-content:center; gap:.6rem; width:100%;
    border:1px solid #E7ECF4; color:#1F2B3D; background:#EFF4FA; padding:.9rem 1.1rem;
    border-radius:14px; font-weight:800; font-size:clamp(1rem,4.7vw,1.2rem);
  }

  .thanks{ text-align:center; color:#334155; margin-top:20px; font-size:clamp(.95rem,4vw,1.05rem); }

  .banner{border-radius:16px;padding:.75rem 1rem;border:1px solid var(--stroke);background:#fff;margin-bottom:10px}
  .lost{background:#FFF4F4;color:#a61b19}
  .reward{background:#F3FBF7;color:#0c6b4b}
  .pulse{width:12px;height:12px;border-radius:999px;background:#0c6b4b;margin-right:.5rem;
         box-shadow:0 0 0 0 rgba(12,107,75,.6);animation:pulse 1.8s infinite}
  @keyframes pulse{0%{box-shadow:0 0 0 0 rgba(12,107,75,.6)}70%{box-shadow:0 0 0 18px rgba(12,107,75,0)}100%{box-shadow:0 0 0 0 rgba(12,107,75,0)}}

  /* LIGHTBOX propio (sin Bootstrap) */
  .lb{
    position:fixed; inset:0; background:rgba(7,12,20,.88); display:none; z-index: 1050;
    align-items:center; justify-content:center;
  }
  .lb.open{ display:flex; }
  .lb-img{
    max-width: 92vw; max-height: 88vh; object-fit: contain; border-radius: 12px; background:#000;
    box-shadow: 0 10px 40px rgba(0,0,0,.5);
  }
  .lb-btn{
    position:absolute; top:50%; transform:translateY(-50%); border:none; width:48px; height:48px;
    border-radius:999px; background:rgba(255,255,255,.15); color:#fff; display:flex; align-items:center; justify-content:center;
  }
  .lb-btn:hover{ background:rgba(255,255,255,.22) }
  .lb-prev{ left:18px; }
  .lb-next{ right:18px; }
  .lb-close{
    position:absolute; top:14px; right:14px; width:42px; height:42px; border-radius:999px; border:none;
    background:rgba(255,255,255,.18); color:#fff; display:flex; align-items:center; justify-content:center;
  }
  .lb-close:hover{ background:rgba(255,255,255,.26) }
</style>
@endpush

@section('content')
<div class="page-wrap py-4">
  <div class="card-shell">

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

    {{-- Foto “carousel” + dots (sin JS de terceros). Al click => Lightbox --}}
    <div class="avatar-wrap">
      <div class="avatar" id="avatar">
        <div class="avatar-inner" id="avatarInner">
          @foreach($gallery as $i => $src)
            <div class="avatar-slide {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}">
              <img src="{{ $src }}" alt="{{ $pet->name }} {{ $i+1 }}">
            </div>
          @endforeach
        </div>
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
    <h1 class="pet-title">{{ $pet->name }}</h1>
    <div class="chips">
      <span class="chip"><i class="fa-solid fa-bone"></i>{{ $pet->breed ?: 'Sin Raza' }}</span>
      <span class="chip"><i class="fa-solid fa-location-dot"></i>{{ $pet->full_location ?: ($pet->zone ?: 'Ubicación N/D') }}</span>
      @if($pet->age !== null)
        <span class="chip"><i class="fa-solid fa-cake-candles"></i>{{ $pet->age }} {{ Str::plural('año', $pet->age) }}</span>
      @endif
    </div>

    {{-- Contacto --}}
    <div class="section-title">Contacto</div>

    @if($ownerName)
      <div class="owner-row"><i class="fa-solid fa-user"></i>{{ $ownerName }}</div>
    @endif

    <div class="d-grid gap-2">
      <a id="waBtn" class="btn-wa {{ $hasWhats ? '' : 'disabled opacity-50' }}" href="#" target="_blank"
         @if($hasWhats) data-phone="{{ $ownerPhone }}" @endif>
        <i class="fa-brands fa-whatsapp"></i> Contactar por WhatsApp
      </a>

      @if($ownerPhone)
        <a class="btn-call" href="tel:{{ preg_replace('/\D+/','',$ownerPhone) }}">
          <i class="fa-solid fa-phone"></i> Llamar
        </a>
      @endif
    </div>

    <p class="thanks">
      Si encontraste a <strong>{{ $pet->name }}</strong>, ¡gracias por ayudar!
    </p>
  </div>
</div>

{{-- LIGHTBOX (sin Bootstrap) --}}
<div class="lb" id="lb" aria-hidden="true">
  <button class="lb-close" id="lbClose" aria-label="Cerrar"><i class="fa-solid fa-xmark"></i></button>
  <button class="lb-btn lb-prev" id="lbPrev" aria-label="Anterior"><i class="fa-solid fa-chevron-left"></i></button>
  <img class="lb-img" id="lbImg" src="" alt="Foto">
  <button class="lb-btn lb-next" id="lbNext" aria-label="Siguiente"><i class="fa-solid fa-chevron-right"></i></button>
</div>

<script>
  // WhatsApp (sanitiza a dígitos)
  (function(){
    const btn = document.getElementById('waBtn');
    if(!btn || btn.classList.contains('disabled')) return;
    const digits = (btn.dataset.phone||'').toString().replace(/\D+/g,'');
    if(digits){ btn.href = 'https://wa.me/'+digits; }
    else{ btn.classList.add('disabled','opacity-50'); btn.removeAttribute('href'); }
  })();

  // Mini “carousel” en el avatar (solo para mostrar/indicar) + Lightbox
  (function(){
    const slides = Array.from(document.querySelectorAll('.avatar-slide'));
    const dots   = Array.from(document.querySelectorAll('.avatar-dot'));
    const avatar = document.getElementById('avatar');
    if(!slides.length) return;

    let idx = 0;

    function goTo(i){
      idx = (i+slides.length) % slides.length;
      slides.forEach((s,k)=> s.classList.toggle('active', k===idx));
      dots.forEach((d,k)=> d.classList.toggle('active', k===idx));
    }

    // Cambiar con dots
    dots.forEach(d => d.addEventListener('click', e=>{
      const to = +e.currentTarget.dataset.to; if(Number.isInteger(to)) goTo(to);
    }, {passive:true}));

    // Swipe para avatar
    let startX=null;
    avatar.addEventListener('touchstart', e=>{ startX = e.touches[0].clientX; }, {passive:true});
    avatar.addEventListener('touchmove',  e=>{ /* no-op */ }, {passive:true});
    avatar.addEventListener('touchend',   e=>{
      if(startX===null) return;
      const dx = e.changedTouches[0].clientX - startX;
      if(Math.abs(dx) > 40){ goTo(idx + (dx<0 ? 1 : -1)); }
      startX=null;
    }, {passive:true});

    // Abrir LIGHTBOX al tocar la imagen
    const lb     = document.getElementById('lb');
    const lbImg  = document.getElementById('lbImg');
    const lbPrev = document.getElementById('lbPrev');
    const lbNext = document.getElementById('lbNext');
    const lbClose= document.getElementById('lbClose');

    function openLb(i){
      goTo(i);
      lbImg.src = slides[idx].querySelector('img').src;
      lb.classList.add('open');
      lb.setAttribute('aria-hidden','false');
    }
    function closeLb(){
      lb.classList.remove('open');
      lb.setAttribute('aria-hidden','true');
      // liberamos la imagen para ahorrar memoria en móviles
      setTimeout(()=>{ lbImg.src=''; },150);
    }
    function next(){ goTo(idx+1); lbImg.src = slides[idx].querySelector('img').src; }
    function prev(){ goTo(idx-1); lbImg.src = slides[idx].querySelector('img').src; }

    // Click en avatar = abrir lb en la foto actual
    avatar.addEventListener('click', e=>{
      const slideEl = e.target.closest('.avatar-slide');
      const i = slideEl ? +slideEl.dataset.index : idx;
      openLb(Number.isInteger(i)? i : idx);
    });

    // Controles del lightbox
    lbNext.addEventListener('click', next);
    lbPrev.addEventListener('click', prev);
    lbClose.addEventListener('click', closeLb);
    lb.addEventListener('click', e=>{ if(e.target===lb) closeLb(); }); // click fuera cierra

    // Teclado y swipe en lightbox
    document.addEventListener('keydown', e=>{
      if(lb.classList.contains('open')){
        if(e.key==='Escape') closeLb();
        else if(e.key==='ArrowRight') next();
        else if(e.key==='ArrowLeft') prev();
      }
    });

    let sx=null;
    lb.addEventListener('touchstart', e=>{ sx = e.touches[0].clientX; }, {passive:true});
    lb.addEventListener('touchend', e=>{
      if(sx===null) return;
      const dx = e.changedTouches[0].clientX - sx;
      if(Math.abs(dx) > 50){ dx<0 ? next() : prev(); }
      sx=null;
    }, {passive:true});
  })();
</script>
@endsection
