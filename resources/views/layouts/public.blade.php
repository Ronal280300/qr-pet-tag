<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','QR-Pet Tag')</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome (para íconos de WhatsApp/phone si los usas en la vista pública) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  @stack('styles')
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
      <i class="fa-solid fa-paw me-2"></i>QR-Pet Tag
    </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublic" aria-controls="navPublic" aria-expanded="false" aria-label="Menú">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navPublic">
        <ul class="navbar-nav ms-auto">
          @auth
            <li class="nav-item"><a class="nav-link" href="{{ route('portal.dashboard') }}">Mi Portal</a></li>
          @else
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Ingresar</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Crear cuenta</a></li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>

  @yield('content')

  <footer class="py-4 border-top bg-white mt-5">
    <div class="container text-center text-muted small">
      © {{ date('Y') }} QR-Pet Tag
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
