@extends('layouts.app')
@section('title', 'PetScan — La identificación inteligente para tu mascota')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
  :root {
    --ps-font: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    
    --ps-bg: #FFFFFF;
    --ps-bg-alt: #F8FAFC;
    --ps-bg-accent: #EFF6FF;
    
    --ps-text-900: #0F172A;
    --ps-text-600: #475569;
    --ps-text-500: #64748B;
    
    --ps-primary: #0F172A;
    --ps-primary-hover: #1E293B;
    
    --ps-accent: #2563EB;
    --ps-accent-hover: #1D4ED8;
    
    --ps-border: #E2E8F0;
  }

  body {
    font-family: var(--ps-font);
    background-color: var(--ps-bg);
    color: var(--ps-text-900);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  .ps-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
  }

  /* ============================================
     SISTEMA DE ANIMACIÓN (SCROLL REVEAL SUTIL)
     ============================================ */
  .ps-reveal {
    opacity: 0;
    transform: translateY(16px); /* Muy sutil, no 30px */
    transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
  }
  .ps-reveal.ps-visible {
    opacity: 1;
    transform: translateY(0);
  }
  .ps-delay-1 { transition-delay: 0.1s; }
  .ps-delay-2 { transition-delay: 0.2s; }
  .ps-delay-3 { transition-delay: 0.3s; }

  /* ============================================
     HERO SECTION
     ============================================ */
  .ps-hero {
    position: relative;
    padding: 120px 0 80px;
    background: radial-gradient(circle at top, #FFFFFF 0%, var(--ps-bg-alt) 100%);
    border-bottom: 1px solid var(--ps-border);
    overflow: hidden;
  }

  .ps-hero-content {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
    z-index: 2;
  }

  .ps-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: var(--ps-bg-accent);
    color: var(--ps-accent);
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 0.5px;
    border-radius: 9999px;
    margin-bottom: 24px;
    text-transform: uppercase;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.05);
    border: 1px solid rgba(37, 99, 235, 0.1);
  }

  .ps-hero-title {
    font-size: 56px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -2px;
    color: var(--ps-text-900);
    margin-bottom: 24px;
  }

  .ps-hero-subtitle {
    font-size: 20px;
    color: var(--ps-text-600);
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
  }

  .ps-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 16px 36px;
    background-color: var(--ps-primary);
    color: #FFFFFF !important;
    font-size: 16px;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .ps-btn-primary:hover {
    background-color: #000000;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.15);
  }

  .ps-hero-image-perspective {
    margin-top: 60px;
    perspective: 1500px; /* Profundidad extra sutil */
  }

  .ps-hero-image-wrapper {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
    border: 1px solid rgba(0,0,0,0.05);
    max-width: 1000px;
    margin: 0 auto;
    background: #FFF;
    transform-style: preserve-3d;
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .ps-hero-image-wrapper img {
    width: 100%;
    height: auto;
    display: block;
    object-fit: cover;
    pointer-events: none;
  }

  /* ============================================
     TRUST BAR
     ============================================ */
  .ps-trust-bar {
    padding: 32px 0;
    background: #FFFFFF;
    border-bottom: 1px solid var(--ps-border);
  }

  .ps-trust-grid {
    display: flex;
    justify-content: space-around;
    align-items: center;
    flex-wrap: wrap;
    gap: 24px;
  }

  .ps-trust-item {
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--ps-text-600);
    font-weight: 600;
    font-size: 14px;
  }

  .ps-trust-item i {
    color: var(--ps-accent);
    font-size: 20px;
    padding: 8px;
    background: var(--ps-bg-accent);
    border-radius: 8px;
  }

  /* ============================================
     HOW IT WORKS
     ============================================ */
  .ps-section {
    padding: 120px 0;
    background: #FFFFFF;
  }

  .ps-section-header {
    text-align: center;
    max-width: 600px;
    margin: 0 auto 64px;
  }

  .ps-section-title {
    font-size: 40px;
    font-weight: 800;
    letter-spacing: -1px;
    color: var(--ps-text-900);
    margin-bottom: 16px;
  }

  .ps-section-subtitle {
    font-size: 18px;
    color: var(--ps-text-600);
  }

  .ps-steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
  }

  .ps-step-card {
    background: var(--ps-bg-alt);
    padding: 40px 32px;
    border-radius: 24px;
    border: 1px solid var(--ps-border);
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
  }

  .ps-step-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--ps-accent);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .ps-step-card:hover {
    transform: translateY(-4px); /* Suave ascenso */
    box-shadow: 0 15px 30px -10px rgba(0,0,0,0.04);
    background: #FFFFFF;
    border-color: rgba(37,99,235,0.1);
  }

  .ps-step-card:hover::before {
    transform: scaleX(1);
  }

  .ps-step-icon {
    width: 56px;
    height: 56px;
    background: var(--ps-primary);
    color: #FFF;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    font-size: 24px;
    margin-bottom: 24px;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }
  
  .ps-step-card:hover .ps-step-icon {
    transform: translateY(-2px);
    background: var(--ps-accent);
  }

  .ps-step-title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 12px;
    color: var(--ps-text-900);
  }

  .ps-step-desc {
    color: var(--ps-text-600);
    font-size: 15px;
    line-height: 1.6;
    margin: 0;
  }

  /* ============================================
     VALUE PROP (Dark Mode Asimétrico)
     ============================================ */
  .ps-features-bg {
    background-color: var(--ps-primary);
    color: #FFFFFF;
    padding: 120px 0;
    position: relative;
    overflow: hidden;
  }

  .ps-features-bg .ps-section-title {
    color: #FFFFFF;
  }

  .ps-features-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
    position: relative;
    z-index: 2;
  }

  .ps-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 24px;
    border-radius: 16px;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid transparent;
  }

  .ps-feature-item:hover {
    background: rgba(255,255,255,0.02);
    border-color: rgba(255,255,255,0.05);
    transform: translateX(8px);
  }

  .ps-feature-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.05); /* Más sutil */
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .ps-feature-item:hover .ps-feature-icon {
    background: var(--ps-accent);
    color: white;
  }

  .ps-feature-text h4 {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 8px;
    color: #FFF;
  }

  .ps-feature-text p {
    color: #94A3B8;
    font-size: 15px;
    margin: 0;
    line-height: 1.6;
  }

  .ps-features-visual-wrapper {
    position: relative;
  }

  .ps-features-image {
    width: 100%;
    border-radius: 24px;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
    position: relative;
    z-index: 2;
  }
  
  .ps-floating-card {
    position: absolute;
    bottom: 40px;
    left: -40px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.1);
    padding: 20px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 3;
    animation: float 8s ease-in-out infinite; /* Animación más lenta y elegante */
  }

  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); } /* Leve flotación */
  }

  /* ============================================
     FAQ (Acordión Interactivo)
     ============================================ */
  .ps-faq-section {
    background: var(--ps-bg-alt);
    padding: 120px 0;
  }
     
  .ps-faq-container {
    max-width: 800px;
    margin: 0 auto;
  }

  .ps-faq-item {
    background: #FFFFFF;
    border: 1px solid var(--ps-border);
    border-radius: 16px;
    margin-bottom: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .ps-faq-item:hover {
    border-color: rgba(37,99,235,0.2);
  }

  .ps-faq-item.active {
    border-color: rgba(37,99,235,0.3);
  }

  .ps-faq-header {
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-size: 18px;
    font-weight: 700;
    color: var(--ps-text-900);
    user-select: none;
    transition: color 0.3s ease;
  }

  .ps-faq-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    color: var(--ps-text-500);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), color 0.3s;
    flex-shrink: 0;
  }

  .ps-faq-item.active .ps-faq-icon {
    transform: rotate(180deg);
    color: var(--ps-accent);
  }

  .ps-faq-body {
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    padding: 0 24px;
    transition: max-height 0.5s cubic-bezier(0.16, 1, 0.3, 1), padding 0.5s ease, opacity 0.4s ease;
  }

  .ps-faq-item.active .ps-faq-body {
    padding: 0 24px 24px;
    opacity: 1;
  }

  .ps-faq-body p {
    color: var(--ps-text-600);
    margin: 0;
    line-height: 1.6;
    font-size: 15px;
  }

  /* Redes, Footer */
  .ps-community-card {
    background: linear-gradient(135deg, var(--ps-accent), var(--ps-primary));
    border-radius: 32px;
    padding: 64px 32px;
    text-align: center;
    color: #FFFFFF;
    box-shadow: 0 20px 40px -15px rgba(37, 99, 235, 0.2); /* Sombra menos densa */
    position: relative;
    overflow: hidden;
  }

  .ps-community-content {
    position: relative;
    z-index: 2;
    max-width: 600px;
    margin: 0 auto;
  }

  .ps-community-content h2 {
    font-size: 36px;
    font-weight: 800;
    margin-bottom: 16px;
    letter-spacing: -1px;
    color: #FFFFFF;
  }

  .ps-community-content p {
    font-size: 16px;
    color: rgba(255,255,255,0.8);
    margin-bottom: 32px;
    line-height: 1.6;
  }

  .ps-social-grid {
    display: flex;
    justify-content: center;
    gap: 16px;
  }

  .ps-social-btn {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    background: rgba(255,255,255,0.05); /* Más translúcido */
    border: 1px solid rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #FFFFFF;
    text-decoration: none;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .ps-social-btn:hover {
    background: #FFFFFF;
    transform: translateY(-4px); /* Hover sutil */
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
  }

  .ps-social-btn.instagram:hover { color: #E1306C; }
  .ps-social-btn.facebook:hover { color: #1877F2; }
  .ps-social-btn.tiktok:hover { color: #0f172a; }

  .ps-footer {
    background-color: var(--ps-bg-alt);
    border-top: 1px solid var(--ps-border);
    padding: 80px 0 32px;
  }

  .ps-footer-top {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 64px;
    margin-bottom: 64px;
  }

  .ps-footer-brand { max-width: 320px; }

  .ps-footer-logo {
    font-size: 24px;
    font-weight: 800;
    color: var(--ps-text-900);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
  }
  .ps-footer-logo i { color: var(--ps-accent); }

  .ps-footer-desc {
    color: var(--ps-text-600);
    font-size: 14px;
    line-height: 1.6;
  }

  .ps-footer-links-group {
    display: flex;
    justify-content: flex-end;
    gap: 80px;
  }

  .ps-footer-column h4 {
    font-size: 14px;
    font-weight: 700;
    color: var(--ps-text-900);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 24px;
  }

  .ps-footer-column a {
    display: block;
    color: var(--ps-text-600);
    text-decoration: none;
    font-size: 15px;
    margin-bottom: 16px;
    transition: opacity 0.2s ease;
  }

  .ps-footer-column a:hover {
    color: var(--ps-accent);
  }

  .ps-footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 32px;
    border-top: 1px solid var(--ps-border);
    color: var(--ps-text-500);
    font-size: 13px;
    flex-wrap: wrap;
    gap: 16px;
  }
  
  .ps-footer-made i {
     color: #EF4444; margin: 0 4px;
  }

  /* MEDIA QUERIES */
  @media (max-width: 1024px) {
    .ps-steps-grid { grid-template-columns: repeat(2, 1fr); }
    .ps-features-grid { gap: 40px; }
    .ps-floating-card { display: none; }
  }

  @media (max-width: 768px) {
    .ps-hero { padding: 80px 0 60px; }
    .ps-hero-title { font-size: 40px; letter-spacing: -1px; }
    .ps-trust-grid { flex-direction: column; align-items: flex-start; padding: 0 24px; }
    .ps-steps-grid, .ps-features-grid { grid-template-columns: 1fr; }
    .ps-section { padding: 80px 0; }
    .ps-features-bg { padding: 80px 0; }
    .ps-features-image { margin-top: 40px; }
    .ps-faq-section { padding: 80px 0; }
    .ps-community-card { padding: 48px 24px; }
    .ps-footer-top { grid-template-columns: 1fr; gap: 40px; }
    .ps-footer-links-group { justify-content: flex-start; flex-direction: column; gap: 40px; }
    .ps-footer-bottom { flex-direction: column; text-align: center; }
  }
</style>
@endpush

@section('content')

<!-- HERO SECTION -->
<section class="ps-hero" id="heroSection">
    <div class="ps-container">
        <div class="ps-hero-content ps-reveal">
            <span class="ps-hero-badge">
                <i class="fa-solid fa-satellite-dish"></i> Identificación Pasiva Segura
            </span>
            <h1 class="ps-hero-title">El enlace directo entre tú y la seguridad de tu mascota</h1>
            <p class="ps-hero-subtitle">Manejamos su identidad en la nube. Placas inteligentes ultraligeras. Un perfil que habla por ellos cuando no pueden hacerlo.</p>
            
            <a href="#planes" class="ps-btn-primary">
                Protege a tu mascota <i class="fa-solid fa-shield-cat ms-2"></i>
            </a>
        </div>

        <div class="ps-hero-image-perspective ps-reveal ps-delay-1">
            <div class="ps-hero-image-wrapper" id="heroImageWrapper">
                <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&q=80&w=1200&h=600" alt="Placa Inteligente PetScan en uso">
            </div>
        </div>
    </div>
</section>

<!-- TRUST BAR -->
<div class="ps-trust-bar">
    <div class="ps-container ps-trust-grid ps-reveal">
        <div class="ps-trust-item"><i class="fa-solid fa-bolt"></i> Escaneo mundial sin apps</div>
        <div class="ps-trust-item"><i class="fa-solid fa-shield-halved"></i> Datos 100% encriptados</div>
        <div class="ps-trust-item"><i class="fa-solid fa-battery-full"></i> Tecnología permanente</div>
        <div class="ps-trust-item"><i class="fa-solid fa-truck-fast"></i> Envíos nacionales</div>
    </div>
</div>

<!-- HOW IT WORKS -->
<section class="ps-section">
    <div class="ps-container">
        <div class="ps-section-header ps-reveal">
            <h2 class="ps-section-title">Diseñado para la urgencia</h2>
            <p class="ps-section-subtitle">Cuando cada segundo cuenta, el flujo debe ser instantáneo y sin fricción para quien la encuentra.</p>
        </div>

        <div class="ps-steps-grid">
            <!-- Step 1 -->
            <div class="ps-step-card ps-reveal ps-delay-1">
                <div class="ps-step-icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
                <h3 class="ps-step-title">1. Alguien la encuentra</h3>
                <p class="ps-step-desc">La persona responsable escanea la tecnología pasiva de la placa. No necesita descargar nada, la lectura es instantánea con su cámara nativa.</p>
            </div>
            
            <!-- Step 2 -->
            <div class="ps-step-card ps-reveal ps-delay-2">
                <div class="ps-step-icon"><i class="fa-solid fa-address-card"></i></div>
                <h3 class="ps-step-title">2. Perfil vital revelado</h3>
                <p class="ps-step-desc">Ven de inmediato su nombre, historial médico (alergias críticas) y los canales de comunicación de emergencia pre-autorizados por ti.</p>
            </div>
            
            <!-- Step 3 -->
            <div class="ps-step-card ps-reveal ps-delay-3">
                <div class="ps-step-icon"><i class="fa-solid fa-location-crosshairs"></i></div>
                <h3 class="ps-step-title">3. Alerta y ubicación</h3>
                <p class="ps-step-desc">Se captura discretamente el ping de geolocalización en el momento del escaneo, enviándote un mapa silencioso directo a tus notificaciones.</p>
            </div>
        </div>
    </div>
</section>

<!-- VALUE PROP / FEATURES -->
<section class="ps-features-bg">
    <div class="ps-container">
        <div class="ps-features-grid">
            
            <div class="ps-features-content ps-reveal">
                <h2 class="ps-section-title" style="text-align: left; margin-bottom: 48px;">No es solo una placa. Es un pasaporte de salud encriptado</h2>
                
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div class="ps-feature-item">
                        <div class="ps-feature-icon"><i class="fa-solid fa-notes-medical"></i></div>
                        <div class="ps-feature-text">
                            <h4>Historial Médico Centralizado</h4>
                            <p>Actualiza vacunas, medicamentos y dietas desde la comodidad de tu portal en cualquier momento.</p>
                        </div>
                    </div>

                    <div class="ps-feature-item">
                        <div class="ps-feature-icon"><i class="fa-solid fa-bell-concierge"></i></div>
                        <div class="ps-feature-text">
                            <h4>Notificaciones de Eventos</h4>
                            <p>Registro activo detallado. Sabrás en el segundo exacto cuando la placa fue interactuada.</p>
                        </div>
                    </div>

                    <div class="ps-feature-item">
                        <div class="ps-feature-icon"><i class="fa-solid fa-eye-slash"></i></div>
                        <div class="ps-feature-text">
                            <h4>Arquitectura Privada</h4>
                            <p>Datos confidenciales resguardados. Tu información de contacto se abre al mundo solamente al declararlo perdido.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ps-features-visual-wrapper ps-reveal ps-delay-2">
                <div class="ps-floating-card">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #2563EB; display:flex; align-items:center; justify-content:center; color:white;"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <div style="font-weight: 600; color: white; font-size: 14px;">Localización detectada</div>
                        <div style="color: #94A3B8; font-size: 12px;">Vía coordenadas pasivas</div>
                    </div>
                </div>
                <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?auto=format&fit=crop&q=80&w=800&h=1000" alt="Mascota protegida" class="ps-features-image">
            </div>

        </div>
    </div>
</section>

<!-- PRICING CREADO PREVIAMENTE -->
@include('public.partials.plans-section')

<!-- PREGUNTAS FRECUENTES (Acordión Interactivo JS) -->
<section class="ps-faq-section ps-section">
    <div class="ps-container">
        <div class="ps-section-header ps-reveal">
            <h2 class="ps-section-title">Preguntas Frecuentes</h2>
            <p class="ps-section-subtitle">Claridad total antes de dar el siguiente paso.</p>
        </div>
        
        <div class="ps-faq-container ps-reveal ps-delay-1">
            
            <div class="ps-faq-item">
                <div class="ps-faq-header">
                    ¿Existen mensualidades ocultas?
                    <div class="ps-faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
                </div>
                <div class="ps-faq-body">
                    <p>No hay cargos ocultos. El plan de Pago Único te brinda la placa con su respectivo servicio online para siempre. Las modalidades de suscripción están reservadas solo si deseas envíos y hardware de reemplazo de por vida.</p>
                </div>
            </div>

            <div class="ps-faq-item">
                <div class="ps-faq-header">
                    ¿La placa necesita carga o baterías?
                    <div class="ps-faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
                </div>
                <div class="ps-faq-body">
                    <p>Esa es nuestra principal ventaja tecnológica. Operamos mediante lectura NFC y Mapeo QR de alta durabilidad, lo que significa que la placa nunca morirá sin batería durante los días que tu mascota esté fuera. Extrae la energía y conectividad del teléfono de la persona que la encuentra.</p>
                </div>
            </div>

            <div class="ps-faq-item">
                <div class="ps-faq-header">
                    ¿Cúanto tardará en llegar mi pedido?
                    <div class="ps-faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
                </div>
                <div class="ps-faq-body">
                    <p>Procesamos los datos para grabación de inmediato. Generalmente, el tránsito desde nuestras instalaciones logísticas hasta tu dirección se completa en 24 a 48 horas en días laborables.</p>
                </div>
            </div>

            <div class="ps-faq-item">
                <div class="ps-faq-header">
                    ¿Puedo actualizar mis datos después?
                    <div class="ps-faq-icon"><i class="fa-solid fa-chevron-down"></i></div>
                </div>
                <div class="ps-faq-body">
                    <p>Completamente. La belleza de la conexión en la nube es que puedes cambiar de casa, modificar teléfonos de emergencia o registrar nuevas alergias en el Portal; el perfil público enlazado a la placa física se actualizará al milisegundo exacto.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SOCIAL MEDIA / COMMUNITY -->
<section class="ps-section" style="padding: 0 0 80px 0; background: var(--ps-bg-alt);">
    <div class="ps-container">
        <div class="ps-community-card ps-reveal">
            <div class="ps-community-content">
                <span class="ps-hero-badge" style="background: rgba(255,255,255,0.1) !important; color: #FFF; margin-bottom: 16px; border:none; box-shadow:none;">COMUNIDAD</span>
                <h2>Sigue nuestro progreso</h2>
                <p>Nuestra visión tecnológica por la integridad animal documentada día a día.</p>
                
                <div class="ps-social-grid">
                    <a href="https://instagram.com/petscan" target="_blank" class="ps-social-btn instagram" aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="https://facebook.com/petscan" target="_blank" class="ps-social-btn facebook" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://tiktok.com/@petscan" target="_blank" class="ps-social-btn tiktok" aria-label="TikTok">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="ps-footer">
    <div class="ps-container">
        <div class="ps-footer-top">
            <div class="ps-footer-brand ps-reveal">
                <div class="ps-footer-logo">
                    <i class="fa-solid fa-paw"></i> PetScan
                </div>
                <p class="ps-footer-desc">Plataforma integral inteligente. Nacimos bajo un único propósito: que toda mascota encontrada pueda volver con los suyos rápidamente.</p>
            </div>
            
            <div class="ps-footer-links-group ps-reveal ps-delay-1">
                <div class="ps-footer-column">
                    <h4>Plataforma</h4>
                    <a href="#planes">Planes y Tarifas</a>
                    <a href="{{ route('login') }}">Mi Portal (Ingresar)</a>
                    <a href="{{ route('register') }}">Registar Cuenta</a>
                </div>
                
                <div class="ps-footer-column">
                    <h4>Legales</h4>
                    <a href="{{ route('legal.terms') }}">Condiciones de Uso</a>
                    <a href="{{ route('legal.privacy') }}">Privacidad de Datos</a>
                    <a href="{{ route('legal.help') }}">Soporte al Usuario</a>
                </div>
            </div>
        </div>
        
        <div class="ps-footer-bottom ps-reveal ps-delay-2">
            <p>&copy; {{ date('Y') }} PetScan. Derechos reservados operativamente.</p>
            <div class="ps-footer-made">Desarrollado con vocación para las mascotas.</div>
        </div>
    </div>
</footer>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* ========================================================
       1. ANIMACIONES ESCALA Y FADE (SCROLL REVEAL SUAVE)
       ======================================================== */
    const revealElements = document.querySelectorAll('.ps-reveal');
    const observerOptions = {
        root: null,
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('ps-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    revealElements.forEach(el => revealObserver.observe(el));

    /* ========================================================
       2. EFECTO PARALLAX 3D EXTRA SUTIL (SOLO DESKTOP)
       ======================================================== */
    const heroSection = document.getElementById('heroSection');
    const heroImageWrapper = document.getElementById('heroImageWrapper');
    
    if(heroSection && heroImageWrapper && window.innerWidth > 768) {
        heroSection.addEventListener('mousemove', (e) => {
            const centerX = window.innerWidth / 2;
            const centerY = window.innerHeight / 2;
            
            const mouseX = e.clientX - centerX;
            const mouseY = e.clientY - centerY;
            
            // Factor 180 = rotación minúscula de ~2 grados, imperceptible pero elegante
            const rotX = (mouseY / -180);
            const rotY = (mouseX / 180);

            heroImageWrapper.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg)`;
        });

        heroSection.addEventListener('mouseleave', () => {
            heroImageWrapper.style.transform = `rotateX(0deg) rotateY(0deg)`;
            heroImageWrapper.style.transition = 'transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
        });

        heroSection.addEventListener('mouseenter', () => {
            heroImageWrapper.style.transition = 'transform 0.3s cubic-bezier(0.16, 1, 0.3, 1)';
        });
    }

    /* ========================================================
       3. ACORDIÓN INTERACTIVO PARA FAQ
       ======================================================== */
    const faqItems = document.querySelectorAll('.ps-faq-item');
    
    faqItems.forEach(item => {
        const header = item.querySelector('.ps-faq-header');
        const body = item.querySelector('.ps-faq-body');
        
        header.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            
            if (isActive) {
                item.classList.remove('active');
                body.style.maxHeight = '0px';
            } else {
                item.classList.add('active');
                body.style.maxHeight = body.scrollHeight + "px";
            }
        });
    });

});
</script>
@endpush
