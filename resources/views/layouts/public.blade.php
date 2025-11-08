<!DOCTYPE html>
<html lang="es">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title','QR-Pet Tag')</title>

  {{-- Favicon --}}
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' style='stop-color:%23115DFC;stop-opacity:1' /><stop offset='100%25' style='stop-color:%233466ff;stop-opacity:1' /></linearGradient></defs><rect width='100' height='100' rx='20' fill='url(%23grad)'/><g fill='white' transform='translate(50,50)'><circle cx='0' cy='-12' r='8'/><circle cx='-12' cy='0' r='6'/><circle cx='12' cy='0' r='6'/><circle cx='-6' cy='12' r='6'/><circle cx='6' cy='12' r='6'/><ellipse cx='0' cy='5' rx='18' ry='20' opacity='0.9'/></g></svg>">
  <link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 180 180'><defs><linearGradient id='grad2' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' style='stop-color:%23115DFC;stop-opacity:1' /><stop offset='100%25' style='stop-color:%233466ff;stop-opacity:1' /></linearGradient></defs><rect width='180' height='180' rx='40' fill='url(%23grad2)'/><g fill='white' transform='translate(90,90) scale(1.5)'><circle cx='0' cy='-12' r='8'/><circle cx='-12' cy='0' r='6'/><circle cx='12' cy='0' r='6'/><circle cx='-6' cy='12' r='6'/><circle cx='6' cy='12' r='6'/><ellipse cx='0' cy='5' rx='18' ry='20' opacity='0.9'/></g></svg>">

  {{-- Bootstrap + FontAwesome --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>

  <style>
    :root{
      --brand:#1e7cf2;
      --brand-900:#0e61c6;
      --ink:#0f172a;
      --muted:#6b7a90;
      --bg:#f7f9fc;
    }
    html,body{height:100%}
    body{
      font-family: system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans";
      background: var(--bg);
      color:#1b2430;
    }

    /* ===== NAVBAR (igual al del sistema) ===== */
    .nav-shell{
      position: sticky; top: 0; z-index: 1020;
      backdrop-filter: saturate(140%) blur(10px);
      background: linear-gradient(90deg,rgba(30,124,242,.92),rgba(14,97,198,.92));
      transition: all .25s ease;
    }
    .nav-shell.scrolled{
      box-shadow: 0 10px 30px rgba(15,23,42,.08);
      transform: translateY(-2px);
    }
    .navbar{
      --bs-navbar-color:#eef6ff;
      --bs-navbar-hover-color:#fff;
      --bs-navbar-active-color:#fff;
      padding: .85rem 0;
      transition: padding .25s ease;
    }
    .nav-shell.scrolled .navbar{ padding: .55rem 0; }

    .container-narrow{ max-width: 1120px; }

    .navbar-brand{
      display:flex; align-items:center; gap:.55rem;
      font-weight:800; letter-spacing:.2px; color:#fff !important;
      transform: translateZ(0);
      text-decoration: none;
    }
    .navbar-brand .logo-badge{
      width:34px;height:34px;display:grid;place-items:center;
      background: rgba(255,255,255,.15);
      border:1px solid rgba(255,255,255,.25);
      border-radius:10px;
      box-shadow: inset 0 1px 2px rgba(255,255,255,.15);
      transition: transform .25s ease;
      color:#fff;
    }
    .navbar-brand:hover .logo-badge{ transform: rotate(-8deg) scale(1.06); }

    .nav-link{
      position:relative;
      font-weight:600;
      color:#eaf3ff !important;
      opacity:.95;
    }
    .nav-link:hover{ opacity:1; }
    .nav-link::after{
      content:""; position:absolute; left:10%; right:10%; bottom:.35rem;
      height:2px; background:rgba(255,255,255,.85);
      transform:scaleX(0); transform-origin: center;
      transition: transform .25s ease;
      border-radius:2px;
    }
    .nav-link:hover::after,.nav-link.active::after{ transform:scaleX(1); }

    .btn-cta{
      --bs-btn-bg:#ffffff; --bs-btn-border-color:#ffffff;
      --bs-btn-color:#0e61c6; --bs-btn-hover-color:#0e61c6;
      font-weight:700; box-shadow:0 6px 18px rgba(255,255,255,.18);
    }

    .dropdown-menu{
      border:0; border-radius:14px; padding:.4rem;
      box-shadow:0 12px 40px rgba(15,23,42,.18);
      transform-origin: top right;
      animation: menuFade .16s ease-out;
    }
    @keyframes menuFade{
      from{ opacity:0; transform:translateY(-4px) scale(.98); }
      to{   opacity:1; transform:translateY(0)    scale(1); }
    }
    .dropdown-item{ border-radius:10px; padding:.55rem .8rem; font-weight:600; }
    .dropdown-item i{ width:18px; text-align:center; margin-right:.5rem; }
    .dropdown-item:hover{ background:#f1f5ff; color:#0e61c6; }

    /* ⛔️ FIX CRÍTICO: scopea el avatar del NAVBAR para que no afecte la foto del perfil */
    .navbar .avatar{
      width:32px;height:32px;border-radius:50%;flex:0 0 32px;
      background:rgba(255,255,255,.2);
      display:grid; place-items:center; color:#fff;
      border:1px solid rgba(255,255,255,.35);
    }

    /* ====== FIX de la FOTO del perfil (sin tocar el diseño) ======
       La vista pública usa un contenedor grande circular .avatar-wrap.
       Aseguramos proporción 1:1 y que la imagen llene ese contenedor,
       incluso si trae la clase .avatar (del navbar) u otras restricciones. */
    .avatar-wrap{
      width: min(340px, 80vw);
      aspect-ratio: 1 / 1;
      height: auto;
      border-radius: 50%;
      margin: -180px auto 0;      /* tu mismo look en desktop */
      background:#fff; padding:10px;
      box-shadow:0 20px 50px rgba(31,41,55,.12);
      position:relative; overflow: visible;
      display:grid; place-items:center;
    }
    .avatar-inner{
      width:100%; height:100%;
      border-radius:50%; overflow:hidden; background:#f3f6fb;
      display:grid; place-items:center;
    }
    /* Fuerza cualquier variante de imagen dentro del círculo */
    .avatar-wrap img,
    .avatar-wrap .avatar,
    .avatar-wrap .avatar img,
    .avatar-inner img,
    .avatar-inner picture > img{
      width:100% !important;
      height:100% !important;
      max-width:none !important;
      max-height:none !important;
      object-fit:cover !important;
      border-radius:50% !important;
      display:block !important;
    }
    /* Si alguien usa background-image en el inner, cúbrelo también */
    .avatar-inner[style*="background-image"]{
      background-size:cover !important; background-position:center !important;
    }
    /* En móvil, que no se esconda hacia arriba */
    @media (max-width:575.98px){
      .avatar-wrap{ margin: 12px auto 0 !important; }
    }

    /* Opcional: si usas carrusel Bootstrap para fotos públicas */
    .pet-carousel .ratio{ border-radius:1.25rem; overflow:hidden; box-shadow:0 1rem 2rem rgba(0,0,0,.12); }
    .pet-carousel .img-cover{ width:100%; height:100%; object-fit:cover; transform:scale(.97); transition:transform .5s ease, box-shadow .5s ease; }
    .pet-carousel .carousel-item.active .img-cover{ transform:scale(1); box-shadow:0 1.5rem 3rem rgba(0,0,0,.18); }
    .pet-carousel .carousel-indicators [data-bs-target]{ width:.7rem; height:.7rem; border-radius:50%; background:rgba(0,0,0,.3); }
    .pet-carousel .carousel-indicators .active{ background:rgba(0,0,0,.65); }
    @media (min-width:992px){ .pet-carousel .ratio{ border-radius:50%; } }
  </style>

  @stack('styles')
</head>
<body>
  {{-- ===== NAVBAR ===== --}}
  <div class="nav-shell" id="navShell">
    <nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container container-narrow">

        <a class="navbar-brand" href="{{ url('/') }}">
          <span class="logo-badge"><i class="fa-solid fa-paw"></i></span>
          <span>QR-Pet Tag</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav" aria-controls="topnav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div id="topnav" class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
            @guest
              <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket me-1"></i> Ingresar</a></li>
              <li class="nav-item ms-lg-1">
                <a class="btn btn-cta btn-sm ms-lg-1" href="{{ route('register') }}"><i class="fa-solid fa-user-plus me-1"></i> Crear cuenta</a>
              </li>
            @else
              <li class="nav-item ms-lg-2"><a href="{{ route('login') }}" class="btn btn-cta"><i class="fa-solid fa-paw me-2"></i>Mi Portal</a></li>

              <li class="nav-item">
                <a class="nav-link" href="{{ route('portal.pets.index') }}">
                  <i class="fa-solid fa-dog me-1"></i> Mascotas
                </a>
              </li>

              @if(Auth::user()->is_admin)
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-screwdriver-wrench me-1"></i> Admin
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('portal.admin.dashboard') }}"><i class="fa-solid fa-chart-simple"></i> Panel de administración</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.tags.index') }}"><i class="fa-solid fa-tags"></i> Inventario de TAGs</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.activate-tag') }}"><i class="fa-solid fa-bolt"></i> Activar TAG</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.tags.export') }}"><i class="fa-solid fa-file-csv"></i> Exportar TAGs (CSV)</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.clients.index') }}"><i class="fa-solid fa-users"></i> Gestionar Clientes</a></li>
                  </ul>
                </li>
              @endif

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" role="button" data-bs-toggle="dropdown">
                  <span class="avatar"><i class="fa-solid fa-user"></i></span>
                  <span class="d-none d-lg-inline">{{ Str::limit(Auth::user()->name, 18) }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="{{ route('portal.profile.edit') }}">
                      <i class="fa-solid fa-user-gear"></i> Mi perfil
                    </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                   <form method="POST" action="{{ route('logout') }}">
  @csrf
  <button type="submit" class="dropdown-item text-danger d-flex align-items-center fw-semibold">
    <i class="fa-solid fa-right-from-bracket me-2"></i> Cerrar sesión
  </button>
</form>

                  </li>
                </ul>
              </li>
            @endguest
          </ul>
        </div>
      </div>
    </nav>
  </div>
  {{-- ===== /NAVBAR ===== --}}

  <main class="py-4">
    <div class="container container-narrow">
      @yield('content')
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // micro-interacción: sombra/altura al hacer scroll
    (function(){
      const shell = document.getElementById('navShell');
      let lastY = 0;
      const onScroll = () => {
        const y = window.scrollY || document.documentElement.scrollTop;
        shell.classList.toggle('scrolled', y > 4 && y >= lastY);
        lastY = y;
      };
      onScroll();
      window.addEventListener('scroll', onScroll, {passive:true});
    })();
  </script>

  @stack('scripts')
</body>
</html>
