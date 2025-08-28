<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'QR-Pet Tag')</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />

  <style>
    :root { --brand:#1e7cf2; --brand-900:#0e61c6; }
    body { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"; }
    .navbar{ background:linear-gradient(90deg,var(--brand),var(--brand-900)); }
    .navbar-brand{ font-weight:700; letter-spacing:.2px; }
    .container-narrow{ max-width:1080px; }
    .card{ border:0; box-shadow:0 4px 20px rgba(0,0,0,.06); }
    .card-title{ font-weight:600; }
    .badge{ font-weight:600; }
    .btn-primary{ background-color:var(--brand); border-color:var(--brand); }
    .btn-primary:hover{ background-color:var(--brand-900); border-color:var(--brand-900); }
    .hero{ padding:3rem 0 2rem; }
    .hero h1{ font-weight:800; letter-spacing:.3px; }
    .qr-image{ max-width:240px; }
    .list-kv .list-group-item{ display:flex; justify-content:space-between; align-items:center; }
    .list-kv .key{ font-weight:600; color:#506176; }
    .list-kv .val{ text-align:right; }
    @media (max-width:576px){
      .list-kv .list-group-item{ flex-direction:column; align-items:flex-start; }
      .list-kv .val{ text-align:left; }
    }
  </style>

  @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container container-narrow">
    <a class="navbar-brand" href="{{ url('/') }}">
      <i class="fa-solid fa-paw me-2"></i>QR-Pet Tag
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="topnav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Crear cuenta</a></li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('portal.dashboard') }}">Mi Portal</a></li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('portal.pets.index') }}">
              <i class="fa-solid fa-dog me-1"></i> Mascotas
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">
              {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <form method="POST" action="{{ route('logout') }}" class="px-3">
                  @csrf
                  <button class="btn btn-link p-0 text-danger">Cerrar sesión</button>
                </form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

<main class="py-4">
  <div class="container container-narrow">

    {{-- Avisos flash centralizados (UNA sola vez) --}}
    @if(
      session()->has('success') ||
      session()->has('status')  ||
      session()->has('error')   ||
      session()->has('danger')  ||
      session()->has('warning') ||
      session()->has('info')    ||
      $errors->any()
    )
      @include('partials.flash')
    @endif

    @yield('content')
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
