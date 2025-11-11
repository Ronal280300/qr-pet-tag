@extends('layouts.app')
@section('title', 'QR-Pet Tag — Protege siempre a tu mascota')

@push('styles')
{{-- Fuentes modernas --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')

{{-- ====== HERO SECTION ====== --}}
<section class="hero-modern">
  <div class="hero-decoration">
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
    <div class="floating-circle"></div>
  </div>

  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 hero-content">
        <div class="hero-badge">
          <i class="fa-solid fa-shield-check"></i>
          <span>Tecnología de protección inteligente</span>
        </div>
        
        <h1 class="hero-title">
          <span class="hero-title-animated">
            <span class="hero-title-text" id="heroTitle">Nunca más pierdas a tu mejor amigo 🐾</span> 
            <span class="gradient-text"></span>
          </span>
        </h1>
        
        <p class="hero-subtitle">
          Con QR-Pet Tag, tu mascota lleva un código QR único que permite a cualquier persona escanear y contactarte al instante si la encuentran. Protección 24/7 en segundos.
        </p>

        <div class="hero-cta">
          @guest
            <a href="{{ route('plans.index') }}" class="btn-hero btn-primary will-change-transform">
              <i class="fa-solid fa-tags"></i>
              Ver Planes
            </a>
          @else
            <a href="{{ route('plans.index') }}" class="btn-hero btn-primary will-change-transform">
              <i class="fa-solid fa-tags"></i>
              Ver Planes
            </a>
          @endguest

          <a href="#como-funciona" class="btn-hero btn-secondary will-change-transform">
            <i class="fa-solid fa-circle-play"></i>
            Ver cómo funciona
          </a>
        </div>
      </div>

      <div class="col-lg-6 hero-image">
        <div class="hero-image-wrapper will-change-transform">
          <div class="hero-image-inner">
            <div class="hero-image-glow"></div>
            <img src="https://images.unsplash.com/photo-1507146426996-ef05306b995a?q=80&w=1200&auto=format&fit=crop" 
                 alt="Mascota con placa QR"
                 width="520"
                 height="360"
                 loading="eager">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====== TRUST BADGES ====== --}}
<section class="trust-section">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4 reveal">
        <div class="trust-badge-modern">
          <div class="trust-badge-icon">
            <i class="fa-solid fa-lock"></i>
          </div>
          <div class="trust-badge-content">
            <h4>100% Seguro</h4>
            <p>Tus datos protegidos</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="trust-badge-modern">
          <div class="trust-badge-icon">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <div class="trust-badge-content">
            <h4>Activación Instantánea</h4>
            <p>Rápida activación</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 reveal">
        <div class="trust-badge-modern">
          <div class="trust-badge-icon">
            <i class="fa-solid fa-heart"></i>
          </div>
          <div class="trust-badge-content">
            <h4>Seguridad en mascotas</h4>
            <p>Ya protegidas</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ===== SECCIÓN DE PLANES ===== --}}
@include('public.partials.plans-section')


{{-- ====== METRICS SECTION ====== --}}
<section class="metrics-section">
</section>

{{-- ====== FEATURES SECTION ====== --}}
<section class="features-section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-star"></i> Características
      </span>
      <h2 class="section-title">Todo lo que necesitas en un <span class="gradient-text">solo lugar</span></h2>
      <p class="section-subtitle">Herramientas poderosas para mantener a tu mascota segura y conectada contigo</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-qrcode"></i>
          </div>
          <h3 class="feature-title">Código QR único</h3>
          <p class="feature-description">Cada mascota tiene su propio código QR inviolable que dirige a su perfil de contacto.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-mobile-screen"></i>
          </div>
          <h3 class="feature-title">Sin app necesaria</h3>
          <p class="feature-description">Cualquier persona puede escanear el QR con la cámara de su teléfono. Simple y rápido.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-bell"></i>
          </div>
          <h3 class="feature-title">Alertas instantáneas</h3>
          <p class="feature-description">Recibe notificación al momento cuando alguien escanea el QR de tu mascota.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <h3 class="feature-title">Privacidad total</h3>
          <p class="feature-description">Tú decides qué información compartir. Tus datos personales siempre protegidos.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-pen-to-square"></i>
          </div>
          <h3 class="feature-title">Actualización fácil</h3>
          <p class="feature-description">Cambia la información de contacto cuando quieras desde tu panel de control.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-4 reveal">
        <div class="feature-card-modern will-change-transform">
          <div class="feature-icon-modern">
            <i class="fa-solid fa-medal"></i>
          </div>
          <h3 class="feature-title">Sistema de recompensas</h3>
          <p class="feature-description">Ofrece una recompensa para incentivar el retorno seguro de tu mascota.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ====== BENEFITS SECTION ====== --}}
<section class="benefits-section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-paw"></i> Beneficios
      </span>
      <h2 class="section-title">Descubre las ventajas de <span class="gradient-text">QR-Pet Tag</span></h2>
      <p class="section-subtitle">Diseñado para mantener a tu mascota siempre identificada y protegida</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 reveal">
        <div class="benefit-card-modern">
          <div class="benefit-icon-modern">
            <i class="fa-solid fa-id-badge"></i>
          </div>
          <div class="benefit-content">
            <h3>Identificación inteligente</h3>
            <p>Cada etiqueta QR contiene la información esencial de tu mascota, accesible en segundos desde cualquier dispositivo.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 reveal">
        <div class="benefit-card-modern">
          <div class="benefit-icon-modern">
            <i class="fa-solid fa-shield-heart"></i>
          </div>
          <div class="benefit-content">
            <h3>Seguridad y tranquilidad</h3>
            <p>Reduce el riesgo de pérdida al permitir que cualquier persona pueda contactarte fácilmente si encuentra a tu mascota.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 reveal">
        <div class="benefit-card-modern">
          <div class="benefit-icon-modern">
            <i class="fa-solid fa-globe"></i>
          </div>
          <div class="benefit-content">
            <h3>Disponible en cualquier lugar</h3>
            <p>Funciona globalmente sin necesidad de aplicaciones adicionales ni suscripciones.</p>
          </div>
        </div>
      </div>

      <div class="col-md-6 reveal">
        <div class="benefit-card-modern">
          <div class="benefit-icon-modern">
            <i class="fa-solid fa-headset"></i>
          </div>
          <div class="benefit-content">
            <h3>Soporte y acompañamiento</h3>
            <p>Te brindamos asistencia continua para que puedas aprovechar al máximo tu sistema QR-Pet Tag.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ====== HOW IT WORKS ====== --}}
<section class="how-it-works" id="como-funciona">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-lightbulb"></i> Proceso
      </span>
      <h2 class="section-title">Activa la protección <span class="gradient-text">QR-Pet Tag</span> en 3 pasos</h2>
      <p class="section-subtitle">Empieza hoy y mantén a tu mascota identificada y segura en menos de un día</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4 reveal">
        <div class="step-card">
          <div class="step-number">1</div>
          <div class="step-connector"></div>
          <h3 class="step-title">Elige tu plan</h3>
          <p class="step-description">
            Selecciona el plan que mejor se adapte a tus necesidades y registra la cantidad de mascotas que deseas proteger.
          </p>
        </div>
      </div>

      <div class="col-md-4 reveal">
        <div class="step-card">
          <div class="step-number">2</div>
          <div class="step-connector"></div>
          <h3 class="step-title">Sube tu comprobante</h3>
          <p class="step-description">
            Realiza el pago y carga el comprobante directamente en el sistema. Nuestro equipo verificará tu solicitud en menos de 24 horas.
          </p>
        </div>
      </div>

      <div class="col-md-4 reveal">
        <div class="step-card">
          <div class="step-number">3</div>
          <h3 class="step-title">Activa tu código QR</h3>
          <p class="step-description">
            Una vez verificado el pago, podrás acceder a tu panel, ver tus mascotas y activar sus códigos QR personalizados.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ====== MASCOTAS PROTEGIDAS - Carousel Infinito ====== --}}
<section class="pets-carousel-section">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-heart"></i> Mascotas Protegidas
      </span>
      <h2 class="section-title">Ellos ya están <span class="gradient-text">protegidos</span></h2>
      <p class="section-subtitle">Únete a las familias que ya confían en QR-Pet Tag para proteger a sus mejores amigos</p>
    </div>

    @if($pets->count() > 0)
    <div class="carousel-infinite-container">
      <div class="carousel-track">
        {{-- Renderizar las mascotas 2 veces para el efecto seamless --}}
        @foreach($pets->concat($pets) as $pet)
        <div class="carousel-pet-card">
          <div class="pet-photo-wrapper">
            <img src="{{ $pet->main_photo_url }}" alt="{{ $pet->name }}" loading="lazy">
            <div class="pet-overlay">
              <div class="pet-info">
                <i class="fa-solid fa-paw"></i>
                <span class="pet-name">{{ $pet->name }}</span>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
    @else
    {{-- Fallback si no hay mascotas --}}
    <div class="no-pets-message">
      <i class="fa-solid fa-paw"></i>
      <p>Pronto verás aquí a las mascotas protegidas con QR-Pet Tag</p>
    </div>
    @endif
  </div>
</section>

{{-- ====== FAQ SECTION ====== --}}
<section class="faq-modern">
  <div class="container">
    <div class="section-header reveal">
      <span class="section-badge">
        <i class="fa-solid fa-circle-question"></i> FAQ
      </span>
      <h2 class="section-title">Preguntas <span class="gradient-text">frecuentes</span></h2>
      <p class="section-subtitle">Resolvemos tus dudas sobre QR-Pet Tag</p>
    </div>

    <div class="faq-container">
      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-qrcode"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Cómo funciona el código QR?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>El código QR es único para cada mascota y está vinculado a su perfil. Cuando alguien lo escanea con la cámara de su teléfono, accede instantáneamente a la información de contacto que decidiste compartir.</p>
          <p>No se necesita ninguna aplicación especial: cualquier smartphone moderno puede escanearlo directamente desde la cámara.</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-shield"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Mis datos personales están seguros?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>Absolutamente. Tú tienes control total sobre qué información se muestra en el perfil público de tu mascota.</p>
          <p>Puedes elegir mostrar solo un número de teléfono, un email alternativo, o cualquier método de contacto que prefieras. Tu información personal completa nunca se comparte públicamente.</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-credit-card"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Cuánto cuesta el servicio?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>Registrarte en la plataforma es completamente gratis. Puedes crear el perfil digital de tu mascota y generar su código QR sin costo alguno.</p>
          <p>Si deseas adquirir una placa física personalizada para el collar, esta tiene un costo único. ¡No hay mensualidades ni suscripciones!</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-bell"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Recibo alertas cuando escanean el QR?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>Sí, recibes una notificación inmediata cada vez que alguien escanea el código QR de tu mascota.</p>
          <p>Esto te permite saber al instante que tu mascota ha sido encontrada y alguien está intentando contactarte.</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-pen"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Puedo actualizar la información después?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
        <div class="faq-content">
          <p>¡Por supuesto! Una de las grandes ventajas es que puedes actualizar toda la información desde tu panel de control en cualquier momento.</p>
          <p>Los cambios se reflejan inmediatamente en el perfil, sin necesidad de cambiar la placa física ni el código QR.</p>
        </div>
      </div>

      <div class="faq-item reveal">
        <div class="faq-question" onclick="toggleFaq(this)">
          <div class="faq-icon">
            <i class="fa-solid fa-coins"></i>
          </div>
          <div class="faq-question-text">
            <h3>¿Puedo ofrecer una recompensa?</h3>
          </div>
          <div class="faq-toggle">
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
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
</section>

{{-- ====== SOCIAL CTA ====== --}}
<section class="social-cta">
  <div class="container reveal">
    <h2 class="section-title mb-3">Únete a nuestra comunidad</h2>
    <p class="section-subtitle mb-4">Muy pronto compartiremos tips, rescates y novedades. ¡Síguenos!</p>
    <div class="social-buttons">
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

{{-- ====== FINAL CTA ====== --}}
<section class="cta-final">
  <div class="container cta-final-content reveal">
    <h2>Protege a tu mascota hoy mismo</h2>
    <p>Regístrate y crea su QR-Pet Tag en minutos. Protección 24/7 para tu mejor amigo.</p>
    @guest
      <a href="{{ route('plans.index') }}" class="btn-cta-final will-change-transform">
        <i class="fa-solid fa-tags"></i> Ver Planes
      </a>
    @else
      <a href="{{ route('plans.index') }}" class="btn-cta-final will-change-transform">
        <i class="fa-solid fa-tags"></i> Ver Planes
      </a>
    @endguest
  </div>
</section>

{{-- ====== FOOTER ====== --}}
<section class="py-4 text-center" style="background: var(--bg-subtle); color: var(--muted); border-top: 1px solid var(--border);">
  <div class="container small">© {{ date('Y') }} QR-Pet Tag — Todos los derechos reservados</div>
</section>

{{-- ====== WHATSAPP BUTTON ====== --}}
@php
    $whatsappNumber = config('app.whatsapp_number');
    $message = "¡Hola! Me gustaría obtener más información sobre los tags";
    $encodedMessage = str_replace('+', '%20', urlencode($message));
@endphp

<a href="https://wa.me/{{ $whatsappNumber }}?text={{ $encodedMessage }}" 
   class="whatsapp-float will-change-transform" 
   target="_blank" 
   title="Chatea con nosotros 💬">
  <i class="fa-brands fa-whatsapp"></i>
  <span class="whatsapp-tooltip">¿Necesitas ayuda? 💬</span>
</a>

@endsection

@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush
