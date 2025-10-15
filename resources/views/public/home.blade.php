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
  .faq-modern {
  background: linear-gradient(135deg, 
    rgba(79, 137, 232, 0.02) 0%, 
    rgba(30, 124, 242, 0.05) 50%, 
    rgba(14, 97, 198, 0.02) 100%);
  position: relative;
  overflow: hidden;
}

.faq-modern::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(79, 137, 232, 0.03) 0%, transparent 70%);
  animation: faqFloat 20s ease-in-out infinite;
}

/* Header */
.faq-header {
  position: relative;
  z-index: 2;
}

.faq-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, rgba(79, 137, 232, 0.1), rgba(30, 124, 242, 0.15));
  border: 1px solid rgba(79, 137, 232, 0.2);
  border-radius: 50px;
  padding: 10px 20px;
  font-size: 14px;
  font-weight: 600;
  color: var(--primary);
  margin-bottom: 24px;
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 20px rgba(79, 137, 232, 0.1);
  animation: badgePulse 3s ease-in-out infinite;
}

.faq-badge-icon {
  font-size: 18px;
  animation: iconRotate 4s ease-in-out infinite;
}

.faq-title {
  font-size: clamp(28px, 4vw, 42px);
  font-weight: 800;
  color: var(--ink);
  margin: 0 0 16px 0;
  line-height: 1.2;
}

.faq-highlight {
  background: linear-gradient(135deg, var(--primary), var(--secondary));
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
}

.faq-subtitle {
  font-size: 18px;
  color: var(--muted);
  margin: 0;
  opacity: 0.9;
}

/* Container */
.faq-container {
  max-width: 800px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

/* Items */
.faq-item {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  margin-bottom: 20px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.3);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.faq-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
  border-color: rgba(79, 137, 232, 0.2);
}

.faq-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--primary), var(--secondary));
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.4s ease;
}

.faq-item:hover::before {
  transform: scaleX(1);
}

/* Question */
.faq-question {
  display: flex;
  align-items: center;
  padding: 24px 28px;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
}

.faq-question:hover {
  background: rgba(79, 137, 232, 0.03);
}

.faq-icon {
  width: 50px;
  height: 50px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--primary), var(--brand-900));
  color: white;
  font-size: 20px;
  margin-right: 20px;
  box-shadow: 0 8px 20px rgba(79, 137, 232, 0.3);
  transition: all 0.3s ease;
}

.faq-question:hover .faq-icon {
  transform: scale(1.1) rotate(5deg);
}

.faq-text {
  flex: 1;
}

.faq-text h5 {
  font-size: 18px;
  font-weight: 700;
  color: var(--ink);
  margin: 0 0 4px 0;
  line-height: 1.3;
}

.faq-text p {
  font-size: 14px;
  color: var(--muted);
  margin: 0;
  opacity: 0.8;
}

.faq-arrow {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(79, 137, 232, 0.08);
  color: var(--primary);
  font-size: 14px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.faq-question[aria-expanded="true"] .faq-arrow {
  transform: rotate(180deg);
  background: var(--primary);
  color: white;
}

/* Answer */
.faq-answer {
  border-top: 1px solid rgba(79, 137, 232, 0.1);
}

.faq-content {
  padding: 28px;
  animation: fadeInUp 0.4s ease-out;
}

.faq-content p {
  font-size: 16px;
  line-height: 1.6;
  color: #374151;
  margin: 0 0 20px 0;
}

/* Lists y features */
.faq-list {
  list-style: none;
  padding: 0;
  margin: 20px 0;
}

.faq-list li {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 0;
  font-size: 15px;
  color: #374151;
}

.faq-list i {
  color: #10b981;
  font-size: 14px;
  width: 16px;
}

.faq-features {
  display: grid;
  gap: 12px;
  margin: 20px 0;
}

.faq-feature {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  background: rgba(79, 137, 232, 0.05);
  border-radius: 12px;
  font-size: 14px;
  color: #374151;
  transition: all 0.3s ease;
}

.faq-feature:hover {
  background: rgba(79, 137, 232, 0.1);
  transform: translateX(4px);
}

.faq-feature i {
  color: var(--primary);
  font-size: 16px;
  width: 20px;
}

/* Tips y warnings */
.faq-tip {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(5, 150, 105, 0.12));
  border: 1px solid rgba(16, 185, 129, 0.2);
  border-radius: 12px;
  margin-top: 20px;
  font-size: 14px;
  color: #059669;
}

.faq-tip i {
  color: #10b981;
  font-size: 16px;
}

.faq-warning {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(217, 119, 6, 0.12));
  border: 1px solid rgba(245, 158, 11, 0.2);
  border-radius: 12px;
  margin-top: 20px;
  font-size: 14px;
  color: #d97706;
}

.faq-warning i {
  color: #f59e0b;
  font-size: 16px;
}

/* Call to Action */
.faq-cta-content {
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(20px);
  border-radius: 24px;
  padding: 40px 32px;
  text-align: center;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.3);
  position: relative;
  overflow: hidden;
}

.faq-cta-content::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(79, 137, 232, 0.1), transparent);
  animation: shimmer 3s infinite;
}

.faq-cta h4 {
  font-size: 24px;
  font-weight: 700;
  color: var(--ink);
  margin: 0 0 8px 0;
}

.faq-cta p {
  color: var(--muted);
  margin: 0 0 24px 0;
  font-size: 16px;
}

.faq-contact-options {
  display: flex;
  gap: 16px;
  justify-content: center;
  flex-wrap: wrap;
}

.faq-contact-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 12px 24px;
  border-radius: 14px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.faq-contact-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

.faq-contact-btn:hover::before {
  left: 100%;
}

.faq-contact-btn.whatsapp {
  background: linear-gradient(135deg, #25d366, #128c7e);
  color: white;
  box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
}

.faq-contact-btn.whatsapp:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(37, 211, 102, 0.4);
  color: white;
}

.faq-contact-btn.email {
  background: linear-gradient(135deg, var(--primary), var(--brand-900));
  color: white;
  box-shadow: 0 8px 20px rgba(79, 137, 232, 0.3);
}

.faq-contact-btn.email:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 30px rgba(79, 137, 232, 0.4);
  color: white;
}

/* Animaciones */
@keyframes faqFloat {
  0%, 100% { transform: translate(0, 0) rotate(0deg); }
  33% { transform: translate(-20px, -20px) rotate(1deg); }
  66% { transform: translate(20px, -10px) rotate(-1deg); }
}

@keyframes badgePulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.05); }
}

@keyframes iconRotate {
  0%, 100% { transform: rotate(0deg); }
  25% { transform: rotate(-10deg); }
  75% { transform: rotate(10deg); }
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes shimmer {
  0% { left: -100%; }
  100% { left: 100%; }
}

/* Responsive */
@media (max-width: 768px) {
  .faq-question {
    padding: 20px 16px;
  }
  
  .faq-icon {
    width: 40px;
    height: 40px;
    margin-right: 16px;
    font-size: 18px;
  }
  
  .faq-text h5 {
    font-size: 16px;
  }
  
  .faq-content {
    padding: 20px 16px;
  }
  
  .faq-cta-content {
    padding: 32px 20px;
  }
  
  .faq-contact-options {
    flex-direction: column;
  }
}

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
  .social-cta .wa {background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);}
  .social-cta .fb{ background:linear-gradient(135deg,#1877F2,#0e5ad6) }
  .social-cta .tt{ background:linear-gradient(135deg,#000000,#111827) }
  .social-cta .tt i{ color:#fff }
  .social-cta i{ font-size:1.2rem }
  
   .cta{ position:relative; overflow:hidden; color:#fff;
        background:linear-gradient(rgba(30,124,242,.94),rgba(14,97,198,.94)),
                   url("https://images.unsplash.com/photo-1558788353-f76d92427f16?q=80&w=1920&auto=format&fit=crop") center/cover fixed;
        padding:90px 0; }
  .cta h2{ font-weight:800; letter-spacing:.3px; animation:pulse 2.5s infinite }
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


<!-- REEMPLAZA ESTA SECCIÓN COMPLETA EN TU ARCHIVO -->
<section class="py-5 faq-modern">
  <div class="container container-narrow">
    
    <!-- Header mejorado -->
    <div class="faq-header text-center mb-5 reveal">
      <div class="faq-badge">
        <span class="faq-badge-icon">❓</span>
        <span>FAQ</span>
      </div>
      <h2 class="faq-title">
        Preguntas <span class="faq-highlight">frecuentes</span>
      </h2>
      <p class="faq-subtitle">Todo lo que necesitas saber sobre QR-Pet Tag</p>
    </div>

    <!-- Accordion mejorado -->
    <div class="faq-container" id="faqModern">
      
      <div class="faq-item reveal" data-aos="fade-up" data-aos-delay="100">
        <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true">
          <div class="faq-icon">
            <i class="fa-solid fa-mobile-screen"></i>
          </div>
          <div class="faq-text">
            <h5>¿Necesito una app para escanear?</h5>
            <p>Compatibilidad con dispositivos</p>
          </div>
          <div class="faq-arrow">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div id="faq1" class="collapse show faq-answer" data-bs-parent="#faqModern">
          <div class="faq-content">
            <p>No necesitas descargar ninguna aplicación. Cualquier cámara moderna (iPhone, Android, tablets) puede leer el código QR y abrir automáticamente el perfil público de tu mascota en el navegador.</p>
            <div class="faq-tip">
              <i class="fa-solid fa-lightbulb"></i>
              <span>Funciona incluso con cámaras básicas de teléfonos de hace 5+ años</span>
            </div>
          </div>
        </div>
      </div>

      <div class="faq-item reveal" data-aos="fade-up" data-aos-delay="200">
        <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
          <div class="faq-icon">
            <i class="fa-solid fa-user-shield"></i>
          </div>
          <div class="faq-text">
            <h5>¿Qué datos son públicos?</h5>
            <p>Privacidad y seguridad</p>
          </div>
          <div class="faq-arrow">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div id="faq2" class="collapse faq-answer" data-bs-parent="#faqModern">
          <div class="faq-content">
            <p>Solo compartimos la información mínima necesaria para el reencuentro:</p>
            <ul class="faq-list">
              <li><i class="fa-solid fa-check"></i> Nombre y foto de la mascota</li>
              <li><i class="fa-solid fa-check"></i> Tu nombre de contacto</li>
              <li><i class="fa-solid fa-check"></i> Teléfono principal</li>
              <li><i class="fa-solid fa-check"></i> Zona general (no dirección exacta)</li>
            </ul>
            <div class="faq-warning">
              <i class="fa-solid fa-shield-halved"></i>
              <span>Nunca compartimos tu dirección exacta, email o datos personales adicionales</span>
            </div>
          </div>
        </div>
      </div>

      <div class="faq-item reveal" data-aos="fade-up" data-aos-delay="300">
        <div class="faq-question collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">
          <div class="faq-icon">
            <i class="fa-solid fa-gift"></i>
          </div>
          <div class="faq-text">
            <h5>¿Puedo activar una recompensa?</h5>
            <p>Incentivos para el rescate</p>
          </div>
          <div class="faq-arrow">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div id="faq3" class="collapse faq-answer" data-bs-parent="#faqModern">
          <div class="faq-content">
            <p>¡Por supuesto! Desde tu panel de control puedes:</p>
            <div class="faq-features">
              <div class="faq-feature">
                <i class="fa-solid fa-toggle-on"></i>
                <span>Activar/desactivar recompensa cuando quieras</span>
              </div>
              <div class="faq-feature">
                <i class="fa-solid fa-coins"></i>
                <span>Establecer el monto que consideres apropiado</span>
              </div>
              <div class="faq-feature">
                <i class="fa-solid fa-eye"></i>
                <span>La recompensa aparece destacada en el perfil público</span>
              </div>
            </div>
          </div>
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
      <a class="social-btn wa" href="https://www.instagram.com/qrpettag?igsh=MWRzdG1kMWVsZ2F0cQ%3D%3D&utm_source=qr" target="_blank" rel="noopener">
        <i class="fa-brands fa-instagram"></i> Instagram
      </a>
      <a class="social-btn fb" href="https://www.facebook.com/share/17VnVJfcxr/?mibextid=wwXIfr" target="_blank" rel="noopener">
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
