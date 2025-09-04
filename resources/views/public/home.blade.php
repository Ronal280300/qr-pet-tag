@extends('layouts.app')
@section('title', 'QR-Pet Tag — Protege siempre a tu mascota')

@push('styles')
{{-- Fuente moderna --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">

<style>
  :root{
    --primary:#4e89e8;
    --secondary:#ff7e30;
    --ink:#0f172a;
    --muted:#6b7280;
    --bg:#f6f9fc;
    --brand:#1e7cf2;
    --brand-900:#0e61c6;
  }

  body { font-family: "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif; }

  /* ===== HERO (parallax) ===== */
  .hero{
    position:relative; overflow:hidden; color:#fff;
    background: linear-gradient(rgba(30,124,242,.92),rgba(14,97,198,.92)),
                url("https://images.unsplash.com/photo-1560807707-8cc77767d783?q=80&w=1920&auto=format&fit=crop") center/cover fixed;
    padding:120px 0 100px;
  }
  .hero-wave{ position:absolute; left:0; bottom:-1px; width:100%; pointer-events:none; }

  .dogshot{
    border-radius:20px; box-shadow:0 18px 48px rgba(0,0,0,.25);
    transform:rotate(2deg); animation:floatImg 5s ease-in-out infinite;
    will-change: transform;
  }
  .dogshot:hover{ transform:rotate(.5deg) scale(1.02); }

  /* ===== TÍTULO (ml11) ===== */
  .ml11 {
    font-weight: 800;
    font-size: clamp(1.9rem, 1.25rem + 2.5vw, 3.2rem);
    line-height: 1.1;
  }
  .ml11 .text-wrapper {
    position: relative;
    display: inline-block;
    padding-right: .05em;
    padding-bottom: .05em;
  }
  .ml11 .line {
    opacity: 0;
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background-color: #fff;
    transform-origin: 0 50%;
  }
  .ml11 .letters { white-space: pre-wrap; }
  .ml11 .letter { display:inline-block; line-height:1em; }
  /* NUEVO: evitar cortes dentro de palabras */
  .ml11 .word{ display:inline-block; white-space:nowrap; }
  .ml11 .emoji { display:inline-block; margin-left:.25rem; opacity:0; } /* aparecerá después */

  /* ===== SUBTÍTULO (ml8 adaptado) ===== */
  .sub-hero { margin-top:.75rem; }
  .ml8{ position:relative; font-weight:400; font-size: clamp(1rem, .9rem + .6vw, 1.25rem); } /* peso normal */
  .ml8 .letters-container{
    position: relative;
    display:inline-block;
    z-index:2;
  }
  .ml8 .letters{ display:inline-block; }
  .ml8 .bang{ margin-left:.25rem; }
  .ml8 .circle{ position:absolute; left:-1.8rem; top:50%; transform:translateY(-50%); }
  .ml8 .circle-white{ width:2.2rem; height:2.2rem; border:2px dashed #fff; border-radius:2.2rem; }
  .ml8 .circle-dark{ width:1.6rem; height:1.6rem; background:rgba(255,255,255,.22); border-radius:1.6rem; }
  .ml8 .circle-container{ width:2rem; height:2rem; }
  .ml8 .circle-dark-dashed{ width:2rem; height:2rem; border:2px dashed rgba(255,255,255,.55); border-radius:2rem; }

  /* utilitarios */
  .section-title{ font-weight:800; color:var(--ink) }
  .text-muted-2{ color:#5f6b7a }
  .hover-scale{ transition:transform .18s ease, box-shadow .18s ease }
  .hover-scale:hover{ transform:translateY(-2px); box-shadow:0 10px 26px rgba(0,0,0,.08) }

  /* tarjetas, etc. (lo mismo que te dejé antes) */
  .metrics{ background:#fff; border-radius:20px; box-shadow:0 12px 30px rgba(31,41,55,.08); }
  .metric h3{ font-weight:800; margin:0; }
  .metric p{ margin:0; color:var(--muted) }
  .feature-card{ border:0; border-radius:18px; padding:2rem 1.5rem; background:#fff; box-shadow:0 10px 24px rgba(0,0,0,.06); transition:transform .25s ease, box-shadow .25s ease; }
  .feature-card:hover{ transform:translateY(-6px); box-shadow:0 18px 36px rgba(0,0,0,.1) }
  .feature-icon{ width:58px; height:58px; border-radius:14px; display:inline-flex; align-items:center; justify-content:center; background:rgba(78,137,232,.12); color:var(--primary); font-size:1.6rem; margin-bottom:.9rem; }
  .benefit .feature-icon{ background:rgba(16,185,129,.12); color:#10b981 }
  .trust-card{ border:1px solid #ecf0f6; background:#fff; border-radius:16px; padding:1.4rem 1.2rem; height:100%; }
  .mockstripe{ background:linear-gradient(180deg,#ffffff,#f3f7ff); border:1px solid #ecf0f6; border-radius:20px; padding:1rem; box-shadow:0 8px 24px rgba(31,41,55,.06); }
  .mockstripe img{ border-radius:12px; box-shadow:0 10px 26px rgba(0,0,0,.06) }
  .testimonial{ background:#fff; border-radius:16px; padding:1.4rem; box-shadow:0 8px 22px rgba(0,0,0,.06); position:relative; }
  .testimonial::before{ content:"❝"; position:absolute; top:-10px; left:-6px; font-size:1.8rem; color:var(--primary) }
  .testimonial small{ color:var(--muted) }
  .faq .accordion-button{ font-weight:600 }
  .faq .accordion-button:not(.collapsed){ color:var(--brand-900) }
  .cta{ position:relative; overflow:hidden; color:#fff;
        background:linear-gradient(rgba(30,124,242,.94),rgba(14,97,198,.94)),
                   url("https://images.unsplash.com/photo-1558788353-f76d92427f16?q=80&w=1920&auto=format&fit=crop") center/cover fixed;
        padding:90px 0; }
  .cta h2{ font-weight:800; letter-spacing:.3px; animation:pulse 2.5s infinite }

  /* reveal */
  .reveal{ opacity:0; transform:translateY(24px); transition:opacity .55s ease, transform .55s ease }
  .reveal.show{ opacity:1; transform:none }

  @keyframes floatImg{0%,100%{transform:translateY(0) rotate(2deg)}50%{transform:translateY(-12px) rotate(-2deg)}}
  @keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.03)}}

  /* === Social buttons (NUEVO) === */
  .social-cta .social-btn{
    display:inline-flex; align-items:center; gap:.6rem;
    font-weight:700; padding:.9rem 1.1rem; border-radius:14px; border:0;
    color:#fff; text-decoration:none;
    transition:transform .15s ease, box-shadow .15s ease, filter .15s ease;
    box-shadow:0 10px 24px rgba(0,0,0,.10);
  }
  .social-cta .social-btn:hover{ transform:translateY(-2px); box-shadow:0 14px 30px rgba(0,0,0,.12); filter:saturate(1.05) }
  .social-cta .wa{ background:linear-gradient(135deg,#25D366,#128C7E) }
  .social-cta .fb{ background:linear-gradient(135deg,#1877F2,#0e5ad6) }
  .social-cta .tt{ background:linear-gradient(135deg,#000000,#111827) }
  .social-cta .tt i{ color:#fff }
  .social-cta i{ font-size:1.2rem }
</style>
@endpush

@section('content')

{{-- ===== HERO ===== --}}
<section class="hero">
  <div class="container container-narrow">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">

        {{-- TÍTULO animado (ml11) --}}
        <h1 id="heroHeadline" class="ml11">
          <span class="text-wrapper">
            <span class="line line1"></span>
            <span class="letters" id="heroLetters">Nunca más pierdas a tu mejor amigo</span>
            <span class="emoji" aria-hidden="true" id="heroEmoji">🐾</span>
          </span>
        </h1>

        {{-- SUBTÍTULO animado (ml8 adaptado) --}}
        <h2 class="ml8 sub-hero">
          <span class="letters-container">
            <span class="letters letters-left">
              Placas con <strong>QR único</strong> que conectan al instante a quien encuentre a tu mascota contigo.
              Privado, simple y efectivo. <span class="letters bang">🚀</span>
            </span>
           
          </span>
          <span class="circle circle-white"></span>
          <span class="circle circle-dark"></span>
          <span class="circle circle-container"><span class="circle circle-dark-dashed"></span></span>
        </h2>

        <div class="mt-4 d-flex flex-wrap gap-3">
          @guest
            <a href="{{ route('register') }}" class="btn btn-light btn-lg shadow-sm hover-scale">
              <i class="fa-solid fa-id-badge me-2"></i> Crear mi cuenta
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg hover-scale">Ya tengo cuenta</a>
          @else
            <a href="{{ route('portal.pets.create') }}" class="btn btn-light btn-lg shadow-sm hover-scale">
              <i class="fa-solid fa-plus me-2"></i> Registrar mascota
            </a>
            <a href="#how" class="btn btn-outline-light btn-lg hover-scale">Cómo funciona</a>
          @endguest
        </div>
      </div>

      <div class="col-lg-6 text-center">
        <img class="img-fluid dogshot" width="520" height="360"
             src="https://images.unsplash.com/photo-1507146426996-ef05306b995a?q=80&w=1200&auto=format&fit=crop"
             alt="Mascota con placa QR">
      </div>
    </div>

  {{-- onda inferior --}}
  <div class="hero-wave">
    <svg viewBox="0 0 1440 200" preserveAspectRatio="none" width="100%" height="100%">
      <path fill="#fff" d="M0,160L80,138.7C160,117,320,75,480,80C640,85,800,139,960,149.3C1120,160,1280,128,1360,112L1440,96L1440,200L1360,200C1280,200,1120,200,960,200C800,200,640,200,480,200C320,200,160,200,80,200L0,200Z"/>
    </svg>
  </div>
</section>

{{-- ===== “Cómo funciona” + resto igual que antes ===== --}}
<section id="how" class="py-5 bg-light">
  <div class="container container-narrow">
    <div class="text-center mb-5 reveal">
      <h2 class="section-title">¿Cómo funciona?</h2>
      <p class="text-muted-2">Un proceso simple en 3 pasos</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4 reveal">
        <div class="feature-card h-100 text-center">
          <span class="feature-icon"><i class="fa-solid fa-dog"></i></span>
          <h5>1. Registramos a tu mascota</h5>
          <p class="text-muted-2">Creamos un perfil con foto, nombre y tus datos de contacto.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card h-100 text-center">
          <span class="feature-icon"><i class="fa-solid fa-qrcode"></i></span>
          <h5>2. Obtén su QR único</h5>
        <p class="text-muted-2">Imprímimos el QR en la placa en tu placa personalizada favorita.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card h-100 text-center">
          <span class="feature-icon"><i class="fa-solid fa-mobile-screen"></i></span>
          <h5>3. Te contactan al instante</h5>
          <p class="text-muted-2">Por medio de WhatsApp o llamada directa al escanearlo.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container container-narrow">
    <div class="text-center mb-5 reveal">
      <h2 class="section-title">Beneficios de QR-Pet Tag</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4 reveal">
        <div class="feature-card benefit h-100 text-center">
          <span class="feature-icon"><i class="fa-solid fa-user-shield"></i></span>
          <h5>Privacidad primero</h5>
          <p class="text-muted-2">Compartes solo lo necesario (teléfono y zona), nunca tu dirección exacta.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card benefit h-100 text-center">
          <span class="feature-icon"><i class="fa-solid fa-rotate"></i></span>
          <h5>Datos actualizados</h5>
          <p class="text-muted-2">Cambias tu número en el portal sin tener que reemplazar la placa.</p>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="feature-card benefit h-100 text-center">
          <span class="feature-icon"><i class="fa-solid fa-gift"></i></span>
          <h5>Recompensa opcional</h5>
          <p class="text-muted-2">Actívala solo cuando la necesites. Motiva a quien encuentre a tu peludo.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container container-narrow">
    <div class="text-center mb-4 reveal"><h2 class="section-title">¿Por qué confiar en nosotros?</h2></div>
    <div class="row g-4">
      <div class="col-md-4 reveal"><div class="trust-card"><h6 class="mb-2"><i class="fa-solid fa-lock me-2 text-primary"></i>Privacidad & seguridad</h6><p class="text-muted-2 mb-0">Cifrado de datos, perfiles públicos mínimos y control total desde tu portal.</p></div></div>
      <div class="col-md-4 reveal"><div class="trust-card"><h6 class="mb-2"><i class="fa-solid fa-headset me-2 text-primary"></i>Soporte cuando lo necesitas</h6><p class="text-muted-2 mb-0">Te acompañamos si tu mascota se pierde. Respuesta rápida.</p></div></div>
      <div class="col-md-4 reveal"><div class="trust-card"><h6 class="mb-2"><i class="fa-solid fa-globe me-2 text-primary"></i>QR universal</h6><p class="text-muted-2 mb-0">Funciona con cualquier cámara moderna. Sin apps raras ni registros extra.</p></div></div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container container-narrow">
    <div class="mockstripe p-3 reveal">
      <div class="row g-3 align-items-center">
        <div class="col-6 col-md-3">
          <img class="img-fluid" src="{{ asset('storage/images/asha.jpeg') }}" alt="asha">
        </div>
        <div class="col-6 col-md-3">
          <img class="img-fluid" src="{{ asset('storage/images/coqueta.jpeg') }}" alt="coqueta">
        </div>
        <div class="col-6 col-md-3">
          <img class="img-fluid" src="{{ asset('storage/images/morgan.jpeg') }}" alt="morgan">
        </div>
        <div class="col-6 col-md-3">
          <img class="img-fluid" src="{{ asset('storage/images/negro.jpeg') }}" alt="negro">
        </div>
      </div>
    </div>
  </div>
</section>


<section class="py-5">
  <div class="container container-narrow faq">
    <div class="text-center mb-4 reveal"><h2 class="section-title">Preguntas frecuentes</h2></div>
    <div class="accordion" id="faqAcc">
      <div class="accordion-item reveal">
        <h2 class="accordion-header" id="q1">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#a1">¿Necesito una app para escanear?</button>
        </h2>
        <div id="a1" class="accordion-collapse collapse show" data-bs-parent="#faqAcc">
          <div class="accordion-body">No. Cualquier cámara moderna lee el QR y abre el perfil público de tu mascota.</div>
        </div>
      </div>
      <div class="accordion-item reveal">
        <h2 class="accordion-header" id="q2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2">¿Qué datos son públicos?</button>
        </h2>
        <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body">Solo el nombre de la mascota, tu nombre, un teléfono y la zona. Nunca tu dirección exacta.</div>
        </div>
      </div>
      <div class="accordion-item reveal">
        <h2 class="accordion-header" id="q3">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3">¿Puedo activar una recompensa?</button>
        </h2>
        <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body">Sí, desde tu portal puedes activar/desactivar una recompensa y poner el monto.</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====== NUEVA SECCIÓN: REDES ====== --}}
<section class="py-5 social-cta bg-light">
  <div class="container container-narrow text-center">
    <h2 class="section-title mb-3">Únete a nuestra comunidad</h2>
    <p class="text-muted-2 mb-4">Muy pronto compartiremos tips, rescates y novedades. ¡Síguenos!</p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a class="social-btn wa" href="#" target="_blank" rel="noopener">
        <i class="fa-brands fa-whatsapp"></i> WhatsApp
      </a>
      <a class="social-btn fb" href="#" target="_blank" rel="noopener">
        <i class="fa-brands fa-facebook-f"></i> Facebook
      </a>
      <a class="social-btn tt" href="#" target="_blank" rel="noopener">
        <i class="fa-brands fa-tiktok"></i> TikTok
      </a>
    </div>
  </div>
</section>

<section class="cta text-center">
  <div class="container container-narrow">
    <h2 class="mb-2">Protege a tu mascota hoy mismo</h2>
    <p class="lead mb-4">Regístrate y crea su QR-Pet Tag en minutos.</p>
    @guest
      <a href="{{ route('register') }}" class="btn btn-light btn-lg shadow-sm hover-scale"><i class="fa-solid fa-paw me-2"></i> Comenzar ahora</a>
    @else
      <a href="{{ route('portal.pets.create') }}" class="btn btn-light btn-lg shadow-sm hover-scale"><i class="fa-solid fa-plus me-2"></i> Registrar mascota</a>
    @endguest
  </div>
</section>

<section class="py-4 text-center text-muted">
  <div class="container small">© {{ date('Y') }} QR-Pet Tag — Todos los derechos reservados</div>
</section>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js" defer></script>
<script>
  // Esperar a que anime.js esté cargado
  window.addEventListener('load', () => {

    /* ========= TÍTULO: frases rotativas (ml11) ========= */
    const lettersEl = document.getElementById('heroLetters');
    const emojiEl   = document.getElementById('heroEmoji');

    // Frases SIN emoji (lo añadimos aparte para que no "aparezca primero")
    const phrases = [
      'Nunca más pierdas a tu mejor amigo',
      'Tu mascota siempre vuelve a casa',
      'Un QR que conecta en segundos',
      'Más seguridad, menos estrés'
    ];
    let idx = 0;

    const wrapLetters = () => {
      // Envolver por PALABRA para evitar cortes internos
      const text = lettersEl.textContent;
      const words = text.split(' ');
      const wrapped = words.map(w => {
        const inner = w.replace(/([a-zA-Z0-9ÁÉÍÓÚáéíóúÑñ])/g, "<span class='letter'>$&</span>");
        return `<span class="word">${inner}</span>`;
      }).join(' ');
      lettersEl.innerHTML = wrapped;
    };

    const playTitle = () => {
      wrapLetters();
      emojiEl.style.opacity = 0; // ocultar patitas hasta el final

      const tl = anime.timeline({ loop: false });

      tl.add({
        targets: '.ml11 .line',
        scaleY: [0,1],
        opacity: [0.5,1],
        easing: "easeOutExpo",
        duration: 650
      })
      .add({
        targets: '.ml11 .line',
        translateX: [0, document.querySelector('.ml11 .letters').getBoundingClientRect().width + 10],
        easing: "easeOutExpo",
        duration: 650,
        delay: 80
      })
      .add({
        targets: '.ml11 .letter',
        opacity: [0,1],
        easing: "easeOutExpo",
        duration: 520,
        offset: '-=720',
        delay: (el, i) => 28 * (i+1)
      })
      .add({
        // Ahora sí mostramos el emoji después de las letras
        targets: '.ml11 .emoji',
        opacity: [0,1],
        scale: [0.8,1],
        easing: 'easeOutBack',
        duration: 350
      })
      .add({
        targets: '.ml11',
        opacity: 1,
        duration: 1000
      })
      .add({
        targets: '.ml11',
        opacity: 0,
        duration: 800,
        easing: "easeOutExpo",
        complete: () => {
          idx = (idx + 1) % phrases.length;
          lettersEl.textContent = phrases[idx];
          document.querySelector('.ml11').style.opacity = 1;
          playTitle();
        }
      });
    };

    // inicial
    lettersEl.textContent = phrases[idx];
    playTitle();

    /* ========= SUBTÍTULO: animación ml8 adaptada ========= */
    const ml8Timeline = anime.timeline({ loop:false });

    ml8Timeline
      .add({
        targets: '.ml8 .circle-white',
        scale: [0, 1.8],
        opacity: [1, 0],
        easing: "easeInOutExpo",
        rotateZ: 360,
        duration: 900
      })
      .add({
        targets: '.ml8 .circle-container',
        scale: [0, 1],
        duration: 900,
        easing: "easeInOutExpo",
        offset: '-=780'
      })
      .add({
        targets: '.ml8 .circle-dark',
        scale: [0, 1],
        duration: 900,
        easing: "easeOutExpo",
        offset: '-=520'
      })
      .add({
        targets: '.ml8 .letters-left',
        opacity: [0,1],
        translateY: ["12px", "0px"],
        duration: 700,
        easing: "easeOutExpo",
        offset: '-=480'
      })
      .add({
        targets: '.ml8 .bang',
        opacity: [0,1],
        scale: [0.8, 1],
        rotateZ: [25, 0],
        duration: 600,
        easing: "easeOutExpo",
        offset: '-=650'
      });

    anime({
      targets: '.ml8 .circle-dark-dashed',
      rotateZ: 360,
      duration: 8000,
      easing: "linear",
      loop: true
    });

    /* ========= reveal-on-scroll & counters ========= */
    const observer = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{
        if(e.isIntersecting){ e.target.classList.add('show'); observer.unobserve(e.target); }
      });
    }, {threshold:.12});
    document.querySelectorAll('.reveal').forEach(el=>observer.observe(el));

    const counters = document.querySelectorAll('.counter');
    const runCounter = (el) => {
      const target = +el.dataset.target;
      const step = Math.max(1, Math.floor(target/120)); // ~2s
      let v = 0;
      const tick = () => {
        v += step; if(v >= target){ v = target; }
        el.textContent = v.toLocaleString();
        if(v < target) requestAnimationFrame(tick);
      };
      tick();
    };
    const cObs = new IntersectionObserver((ents)=>{
      ents.forEach(e=>{
        if(e.isIntersecting){ runCounter(e.target); cObs.unobserve(e.target); }
      });
    },{threshold:.4});
    counters.forEach(c=>cObs.observe(c));
  });
</script>
@endpush
