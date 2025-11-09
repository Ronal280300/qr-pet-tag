<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','QR-Pet Tag')</title>

  {{-- Favicon --}}
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' style='stop-color:%23115DFC;stop-opacity:1' /><stop offset='100%25' style='stop-color:%233466ff;stop-opacity:1' /></linearGradient></defs><rect width='100' height='100' rx='20' fill='url(%23grad)'/><g fill='white' transform='translate(50,50)'><circle cx='0' cy='-12' r='8'/><circle cx='-12' cy='0' r='6'/><circle cx='12' cy='0' r='6'/><circle cx='-6' cy='12' r='6'/><circle cx='6' cy='12' r='6'/><ellipse cx='0' cy='5' rx='18' ry='20' opacity='0.9'/></g></svg>">
  <link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 180 180'><defs><linearGradient id='grad2' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' style='stop-color:%23115DFC;stop-opacity:1' /><stop offset='100%25' style='stop-color:%233466ff;stop-opacity:1' /></linearGradient></defs><rect width='180' height='180' rx='40' fill='url(%23grad2)'/><g fill='white' transform='translate(90,90) scale(1.5)'><circle cx='0' cy='-12' r='8'/><circle cx='-12' cy='0' r='6'/><circle cx='12' cy='0' r='6'/><circle cx='-6' cy='12' r='6'/><circle cx='6' cy='12' r='6'/><ellipse cx='0' cy='5' rx='18' ry='20' opacity='0.9'/></g></svg>">
  <meta name="theme-color" content="#115DFC">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-capable" content="yes">

  {{-- Bootstrap + FontAwesome --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet"/>

  <style>
    :root{
      --brand:#1e7cf2;
      --brand-900:#0e61c6;
      --brand-light:#3b8cff;
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

    /* ====== NAVBAR MODERNA ====== */
    .nav-shell{
      position: sticky;
      top: 0;
      z-index: 1030;
      background: #ffffff;
      border-bottom: 1px solid rgba(15,23,42,.08);
      transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nav-shell.scrolled{
      box-shadow: 0 4px 24px rgba(15,23,42,.08);
      border-bottom-color: transparent;
    }

    .navbar{
      --bs-navbar-color:#334155;
      --bs-navbar-hover-color:#0e61c6;
      --bs-navbar-active-color:#0e61c6;
      padding: 1rem 0;
      transition: padding .3s ease;
    }
    .nav-shell.scrolled .navbar{ padding: .75rem 0; }

    .container-narrow{ max-width: 1120px; }

    /* Brand con paw icon */
    .navbar-brand{
      display:flex; align-items:center; gap:.65rem;
      font-weight:800; font-size:1.25rem; letter-spacing:-.02em;
      color:#0f172a !important;
      transition: transform .2s ease;
    }
    .navbar-brand:hover{ transform: translateY(-1px); }

    .navbar-brand .logo-icon{
      width:40px; height:40px;
      display:grid; place-items:center;
      background: linear-gradient(135deg, #1e7cf2, #0e61c6);
      border-radius:12px;
      color:#fff; font-size:1.15rem;
      box-shadow: 0 2px 8px rgba(30,124,242,.25);
      transition: all .3s ease;
    }
    .navbar-brand:hover .logo-icon{
      transform: rotate(-5deg) scale(1.05);
      box-shadow: 0 4px 12px rgba(30,124,242,.35);
    }

    /* Nav links modernos */
    .nav-link{
      position:relative;
      font-weight:600;
      font-size:.95rem;
      color:#475569 !important;
      padding: .5rem 1rem !important;
      border-radius:10px;
      transition: all .2s ease;
    }
    .nav-link:hover{
      color:#0e61c6 !important;
      background: rgba(30,124,242,.06);
    }
    .nav-link.active{
      color:#0e61c6 !important;
      background: rgba(30,124,242,.1);
    }

    /* Mi Portal - estilo destacado pero elegante */
    .nav-link-portal{
      position:relative;
      font-weight:700;
      font-size:.95rem;
      color:#0e61c6 !important;
      padding: .5rem 1.25rem !important;
      background: linear-gradient(135deg, rgba(30,124,242,.08), rgba(14,97,198,.08));
      border: 1.5px solid rgba(30,124,242,.2);
      border-radius:10px;
      transition: all .25s ease;
    }
    .nav-link-portal:hover{
      background: linear-gradient(135deg, rgba(30,124,242,.14), rgba(14,97,198,.14));
      border-color: rgba(30,124,242,.35);
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(30,124,242,.15);
    }

    /* Botón CTA para invitados */
    .btn-cta{
      font-weight:700;
      font-size:.95rem;
      padding: .5rem 1.25rem;
      background: linear-gradient(135deg, #1e7cf2, #0e61c6);
      border:0;
      color:#fff !important;
      border-radius:10px;
      box-shadow: 0 2px 8px rgba(30,124,242,.25);
      transition: all .25s ease;
    }
    .btn-cta:hover{
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(30,124,242,.35);
    }

    /* Dropdown elegante */
    .dropdown-menu{
      border:0;
      border-radius:12px;
      padding:.5rem;
      box-shadow:0 8px 32px rgba(15,23,42,.12);
      margin-top:.5rem !important;
      animation: menuSlide .2s ease-out;
    }
    @keyframes menuSlide{
      from{ opacity:0; transform:translateY(-8px); }
      to{   opacity:1; transform:translateY(0); }
    }
    .dropdown-item{
      border-radius:8px;
      padding:.65rem 1rem;
      font-weight:600;
      font-size:.9rem;
      transition: all .15s ease;
    }
    .dropdown-item i{
      width:20px;
      text-align:center;
      margin-right:.65rem;
      opacity:.75;
    }
    .dropdown-item:hover{
      background:#f1f5ff;
      color:#0e61c6;
    }
    .dropdown-item:hover i{ opacity:1; }

    /* Avatar del usuario */
    .avatar{
      width:36px; height:36px;
      border-radius:50%;
      background: linear-gradient(135deg, rgba(30,124,242,.12), rgba(14,97,198,.12));
      border:2px solid rgba(30,124,242,.2);
      display:grid; place-items:center;
      color:#0e61c6;
      font-size:.9rem;
      transition: all .2s ease;
    }
    .nav-link.dropdown-toggle:hover .avatar{
      border-color: rgba(30,124,242,.4);
      transform: scale(1.05);
    }

    /* Navbar toggler para fondo blanco */
    .navbar-toggler{
      border: 2px solid rgba(30,124,242,.2);
      border-radius:8px;
      padding: .35rem .65rem;
      transition: all .2s ease;
    }
    .navbar-toggler:hover{
      border-color: rgba(30,124,242,.4);
      background: rgba(30,124,242,.05);
    }
    .navbar-toggler:focus{
      box-shadow: 0 0 0 3px rgba(30,124,242,.15);
    }
    .navbar-toggler-icon{
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%230e61c6' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2.5' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
      width:22px;
      height:22px;
    }

    /* Mobile menu spacing */
    @media (max-width: 991px) {
      #topnav .navbar-nav{
        padding-top: 1rem;
        gap: .35rem;
      }
      #topnav .nav-item{
        margin-bottom: .25rem;
      }
    }

    /* Tarjetas y botones generales */
    .card{ border:0; box-shadow:0 10px 30px rgba(31,41,55,.06); }
    .card-title{ font-weight:700; }
    .btn-primary{ background:var(--brand); border-color:var(--brand); }
    .btn-primary:hover{ background:var(--brand-900); border-color:var(--brand-900); }

    /* Hero helpers, listas key-value (por compatibilidad con tu UI) */
    .hero{ padding:3rem 0 2rem; }
    .hero h1{ font-weight:800; letter-spacing:.3px; }
    .list-kv .list-group-item{ display:flex; justify-content:space-between; align-items:center;}
    .list-kv .key{ font-weight:600; color:#506176;}
    .list-kv .val{ text-align:right;}
    @media (max-width:576px){
      .list-kv .list-group-item{ flex-direction:column; align-items:flex-start;}
      .list-kv .val{ text-align:left;}
    }
  </style>

  @stack('styles')
</head>
<body>
  {{-- ===== NAVBAR MODERNA ===== --}}
  <div class="nav-shell" id="navShell">
    <nav class="navbar navbar-expand-lg">
      <div class="container container-narrow">

        {{-- Brand con paw icon --}}
        <a class="navbar-brand" href="{{ url('/') }}">
          <span class="logo-icon"><i class="fa-solid fa-paw"></i></span>
          <span>QR-Pet Tag</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav" aria-controls="topnav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div id="topnav" class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

            @guest
              {{-- Invitados: Inicio + Planes + Login + Registro --}}
              <li class="nav-item d-lg-none">
                <a class="nav-link" href="{{ url('/') }}">
                  <i class="fa-solid fa-home me-1"></i> Inicio
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('plans.index') }}">
                  <i class="fa-solid fa-tags me-1"></i> Planes
                </a>
              </li>
              <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket me-1"></i> Ingresar</a></li>
              <li class="nav-item ms-lg-1">
                <a class="btn btn-cta btn-sm ms-lg-1" href="{{ route('register') }}"><i class="fa-solid fa-user-plus me-1"></i> Crear cuenta</a>
              </li>
            @else
              {{-- Usuarios autenticados: Inicio (móvil) + Mi Portal + Planes + Mascotas --}}
              <li class="nav-item d-lg-none">
                <a class="nav-link" href="{{ url('/') }}">
                  <i class="fa-solid fa-home me-2"></i>Inicio
                </a>
              </li>

              {{-- Campana de Notificaciones (Admin) - A LA IZQUIERDA --}}
              @if(Auth::user()->is_admin)
                <li class="nav-item">
                  <a class="nav-link position-relative" href="{{ route('portal.admin.notifications.index') }}" id="notificationBell" title="Notificaciones">
                    <i class="fa-solid fa-bell"></i>
                    <span id="notif-badge" class="badge bg-danger position-absolute top-0 start-100 translate-middle badge-sm rounded-pill" style="display: none; font-size: 0.65rem;">0</span>
                  </a>
                </li>
              @endif

              {{-- Mi Portal - SIEMPRE PRIMERO con estilo especial --}}
              <li class="nav-item">
                <a href="{{ route('portal.dashboard') }}" class="nav-link nav-link-portal">
                  <i class="fa-solid fa-paw me-2"></i>Mi Portal
                </a>
              </li>

              {{-- Planes - DESPUÉS DE MI PORTAL --}}
              <li class="nav-item">
                <a class="nav-link" href="{{ route('plans.index') }}">
                  <i class="fa-solid fa-tags me-2"></i>Planes
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="{{ route('portal.pets.index') }}">
                  <i class="fa-solid fa-dog me-2"></i>Mascotas
                </a>
              </li>

              {{-- Admin --}}
              @if(Auth::user()->is_admin)
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-screwdriver-wrench me-1"></i> Admin
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('portal.admin.dashboard') }}"><i class="fa-solid fa-chart-simple"></i> Panel de administración</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.orders.index') }}"><i class="fa-solid fa-shopping-cart"></i> Gestionar Órdenes</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.plan-settings.index') }}"><i class="fa-solid fa-gear"></i> Configurar Planes</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.clients.index') }}"><i class="fa-solid fa-users"></i> Gestionar Clientes</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.email-logs.index') }}"><i class="fa-solid fa-envelope"></i> Logs de Correos</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.tags.index') }}"><i class="fa-solid fa-tags"></i> Inventario de TAGs</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.activate-tag') }}"><i class="fa-solid fa-bolt"></i> Activar TAG</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.tags.export') }}"><i class="fa-solid fa-file-csv"></i> Exportar TAGs (CSV)</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('portal.admin.settings.index') }}"><i class="fa-solid fa-cog"></i> Configuración</a></li>
                  </ul>
                </li>
              @endif


              {{-- Usuario --}}
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
      {{-- SweetAlerts centralizados (éxito/error/validaciones) --}}
      @include('partials.flash')

      @yield('content')
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>

<script>
(() => {
  const shell = document.getElementById('navShell');
  if (!shell) return;

  // Histeresis: añade a los 12px, quita por debajo de 6px (evita parpadeo)
  const ADD_AT = 12;
  const REMOVE_AT = 6;

  let isScrolled = false;
  let ticking = false;

  function update() {
    ticking = false;
    const y = Math.max(0, window.scrollY || document.documentElement.scrollTop || 0);

    if (!isScrolled && y > ADD_AT) {
      shell.classList.add('scrolled');
      isScrolled = true;
    } else if (isScrolled && y < REMOVE_AT) {
      shell.classList.remove('scrolled');
      isScrolled = false;
    }
  }

  function onScroll() {
    if (!ticking) {
      requestAnimationFrame(update);
      ticking = true;
    }
  }

  update();
  window.addEventListener('scroll', onScroll, { passive: true });
})();
</script>

{{-- Sistema de Notificaciones en Tiempo Real --}}
@if(Auth::check() && Auth::user()->is_admin)
<script>
(function() {
  const badge = document.getElementById('notif-badge');
  const bell = document.getElementById('notificationBell');

  if (!badge || !bell) return;

  // Función para actualizar el contador de notificaciones
  function updateNotificationCount() {
    fetch('{{ route("portal.admin.notifications.unread") }}')
      .then(response => response.json())
      .then(data => {
        const count = data.count || 0;

        if (count > 0) {
          badge.textContent = count > 99 ? '99+' : count;
          badge.style.display = 'inline-block';
          bell.classList.add('notification-active');
        } else {
          badge.style.display = 'none';
          bell.classList.remove('notification-active');
        }
      })
      .catch(error => {
        console.log('Error al obtener notificaciones:', error);
      });
  }

  // Actualizar inmediatamente
  updateNotificationCount();

  // Polling cada 30 segundos
  setInterval(updateNotificationCount, 30000);
})();
</script>

<style>
#notificationBell {
  position: relative;
}
#notificationBell.notification-active i {
  animation: bellRing 2s ease-in-out infinite;
}
@keyframes bellRing {
  0%, 100% { transform: rotate(0deg); }
  10%, 30% { transform: rotate(14deg); }
  20%, 40% { transform: rotate(-14deg); }
  50% { transform: rotate(0deg); }
}
#notif-badge {
  min-width: 18px;
  height: 18px;
  padding: 0 5px;
  line-height: 18px;
}
</style>
@endif

  @stack('scripts')
</body>
</html>
