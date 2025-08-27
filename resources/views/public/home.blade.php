@extends('layouts.app')
@section('title', 'QR-Pet Tag — Nunca pierdas a tu mascota')

@push('styles')
<style>
  :root{
    --primary:#4e89e8; --secondary:#ff7e30; --accent:#34c759;
    --light:#f8f9fa; --dark:#212529;
  }
  .hero-qr{background:linear-gradient(135deg,#f9fafb 0%,#e5e7eb 100%); padding:96px 0 72px;}
  .hero-qr h1{font-weight:800; letter-spacing:.2px;}
  .hero-qr .lead{color:#506176}
  .feature-icon{font-size:2.2rem; color:var(--primary); margin-bottom:.75rem;}
  .pet-card{border:0; border-radius:16px; box-shadow:0 8px 28px rgba(0,0,0,.06); transition:transform .25s;}
  .pet-card:hover{transform:translateY(-4px)}
  .howit{background:var(--light)}
  .price-card .card-header{border:0}
  .price-card .card{border:0; box-shadow:0 8px 28px rgba(0,0,0,.06); border-radius:16px;}
  .price-card.pop .card-header{background:var(--primary); color:#fff; border-top-left-radius:16px; border-top-right-radius:16px;}
  .cta-bar{background:linear-gradient(90deg,#4e89e8,#0e61c6); color:#fff;}
  .cta-bar .btn-light{font-weight:600}
  .faq .accordion-button{font-weight:600}
  .shadow-soft{box-shadow:0 6px 18px rgba(0,0,0,.08)}
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="hero-qr">
  <div class="container container-narrow">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <h1 class="display-5 mb-3">Nunca más pierdas a tu mejor amigo</h1>
        <p class="lead mb-4">Collares con <strong>QR único</strong> que permiten contactarte inmediatamente si alguien encuentra a tu mascota perdida.</p>
        <div class="d-flex flex-wrap gap-2">
          @guest
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg shadow-soft">
              <i class="fa-solid fa-id-badge me-2"></i>Crear mi cuenta
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Ya tengo cuenta</a>
          @else
            <a href="{{ route('portal.pets.create') }}" class="btn btn-primary btn-lg shadow-soft">
              <i class="fa-solid fa-plus me-2"></i>Registrar mascota
            </a>
            <a href="#how-it-works" class="btn btn-outline-primary btn-lg">Cómo funciona</a>
          @endguest
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <img src="https://images.unsplash.com/photo-1507146426996-ef05306b995a?q=80&w=1200&auto=format&fit=crop"
             alt="Collares QR para mascotas" class="img-fluid rounded-4 shadow-soft">
      </div>
    </div>
  </div>
</section>

{{-- CÓMO FUNCIONA --}}
<section id="how-it-works" class="howit py-5">
  <div class="container container-narrow py-4">
    <div class="text-center mb-5">
      <h2 class="fw-bold">¿Cómo funciona QR-Pet Tag?</h2>
      <p class="lead text-muted">Un sistema simple en 3 pasos</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4 text-center">
        <div class="feature-icon"><i class="fas fa-dog"></i></div>
        <h5 class="fw-semibold">1. Registra a tu mascota</h5>
        <p class="text-muted">Crea un perfil con foto, nombre y tus datos de contacto.</p>
      </div>
      <div class="col-md-4 text-center">
        <div class="feature-icon"><i class="fas fa-qrcode"></i></div>
        <h5 class="fw-semibold">2. Recibe tu QR único</h5>
        <p class="text-muted">Genera el código y colócalo en su placa o collar.</p>
      </div>
      <div class="col-md-4 text-center">
        <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
        <h5 class="fw-semibold">3. Escanean y te contactan</h5>
        <p class="text-muted">WhatsApp/llamada en un toque; puedes activar recompensa.</p>
      </div>
    </div>
  </div>
</section>

{{-- BENEFICIOS --}}
<section id="features" class="py-5">
  <div class="container container-narrow py-3">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Beneficios de QR-Pet Tag</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <div class="card pet-card h-100">
          <div class="card-body text-center p-4">
            <i class="fas fa-shield-alt feature-icon"></i>
            <h5 class="fw-semibold">Privacidad y seguridad</h5>
            <p class="text-muted mb-0">Compartes solo lo necesario (teléfono y zona), no tu dirección exacta.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="card pet-card h-100">
          <div class="card-body text-center p-4">
            <i class="fas fa-edit feature-icon"></i>
            <h5 class="fw-semibold">Datos siempre al día</h5>
            <p class="text-muted mb-0">Actualiza tu número o zona sin cambiar la placa.</p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-4">
        <div class="card pet-card h-100">
          <div class="card-body text-center p-4">
            <i class="fas fa-gift feature-icon"></i>
            <h5 class="fw-semibold">Recompensa opcional</h5>
            <p class="text-muted mb-0">Actívala desde tu portal cuando la necesites.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- PRECIOS (placeholder; ajusta a tu oferta real) --}}
<section id="pricing" class="py-5 bg-light">
  <div class="container container-narrow py-3">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Planes y precios</h2>
      <p class="lead text-muted">Elige el plan perfecto para tu mascota</p>
    </div>
    <div class="row g-4 price-card">
      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-header text-center py-4">
            <h5>Básico</h5>
            <h2 class="fw-bold">₡5,000</h2>
            <p class="text-muted mb-0">una vez + envío</p>
          </div>
          <div class="card-body">
            <ul class="list-unstyled mb-0">
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Placa con QR</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Perfil digital</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> WhatsApp directo</li>
              <li class="mb-2"><i class="fas fa-times text-secondary me-2"></i> Recompensa</li>
            </ul>
          </div>
          <div class="card-footer text-center bg-white">
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Seleccionar</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 pop">
          <div class="card-header text-center py-4">
            <h5 class="mb-1">Premium</h5>
            <h2 class="fw-bold">₡8,000</h2>
            <p class="mb-0">una vez + envío</p>
          </div>
          <div class="card-body">
            <ul class="list-unstyled mb-0">
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Collar/placa premium</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Perfil avanzado</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Múltiples contactos</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Recompensa</li>
            </ul>
          </div>
          <div class="card-footer text-center bg-white">
            <a href="{{ route('register') }}" class="btn btn-primary">Seleccionar</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100">
          <div class="card-header text-center py-4">
            <h5>Multi-mascota</h5>
            <h2 class="fw-bold">₡12,000</h2>
            <p class="text-muted mb-0">para 3 mascotas + envío</p>
          </div>
          <div class="card-body">
            <ul class="list-unstyled mb-0">
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> 3 placas premium</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Gestión múltiple</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Todas las funciones</li>
              <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Ahorro del 20%</li>
            </ul>
          </div>
          <div class="card-footer text-center bg-white">
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Seleccionar</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- FAQ --}}
<section id="faq" class="faq py-5">
  <div class="container container-narrow py-3">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Preguntas frecuentes</h2>
    </div>

    <div class="accordion" id="faqAcc">
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
            ¿Qué información se muestra cuando escanean el QR?
          </button>
        </h2>
        <div id="q1" class="accordion-collapse collapse show" data-bs-parent="#faqAcc">
          <div class="accordion-body">
            Solo la que decidas: nombre de la mascota, tu WhatsApp/teléfono y zona (no dirección exacta). Puedes agregar notas médicas.
          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
            ¿Qué pasa si cambio de número telefónico?
          </button>
        </h2>
        <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqAcc">
          <div class="accordion-body">
            Editas el perfil en tu portal y listo. El mismo QR mostrará la información actualizada.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="cta-bar py-5">
  <div class="container container-narrow text-center">
    <h2 class="fw-bold mb-2">Protege a tu mascota hoy mismo</h2>
    <p class="lead mb-4">Regístrate y crea su QR-Pet Tag en minutos</p>
    @guest
      <a href="{{ route('register') }}" class="btn btn-light btn-lg">Comenzar ahora</a>
    @else
      <a href="{{ route('portal.pets.create') }}" class="btn btn-light btn-lg">Registrar mascota</a>
    @endguest
  </div>
</section>

{{-- Footer simple dentro de la landing (puede moverse a un layout parcial si quieres) --}}
<section class="py-4 text-center text-muted">
  <div class="container container-narrow small">
    © {{ date('Y') }} QR-Pet Tag — Hecho con ❤️ en Costa Rica
  </div>
</section>

@endsection