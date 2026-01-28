<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Panel Admin') - QR-Pet Tag</title>

  {{-- Favicon --}}
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='grad' x1='0%25' y1='0%25' x2='100%25' y2='100%25'><stop offset='0%25' style='stop-color:%23115DFC;stop-opacity:1' /><stop offset='100%25' style='stop-color:%233466ff;stop-opacity:1' /></linearGradient></defs><rect width='100' height='100' rx='20' fill='url(%23grad)'/><g fill='white' transform='translate(50,50)'><circle cx='0' cy='-12' r='8'/><circle cx='-12' cy='0' r='6'/><circle cx='12' cy='0' r='6'/><circle cx='-6' cy='12' r='6'/><circle cx='6' cy='12' r='6'/><ellipse cx='0' cy='5' rx='18' ry='20' opacity='0.9'/></g></svg>">

  {{-- Bootstrap + FontAwesome --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />

  <style>
    :root {
      --sidebar-width: 280px;
      --topbar-height: 70px;
      --brand: #115DFC;
      --brand-dark: #0e4ac4;
      --brand-light: #3466ff;
      --sidebar-bg: #1a1d2e;
      --sidebar-hover: #252943;
      --sidebar-active: #2d3250;
      --text-muted: #8b92a7;
      --bg-light: #f8f9fb;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: var(--bg-light);
      overflow-x: hidden;
    }

    /* ========== SIDEBAR ========== */
    .admin-sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: var(--sidebar-bg);
      color: #fff;
      overflow-y: auto;
      overflow-x: hidden;
      z-index: 1040;
      transition: transform 0.3s ease;
      box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }

    .admin-sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .admin-sidebar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
    }

    .admin-sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 3px;
    }

    /* Logo en sidebar */
    .sidebar-brand {
      padding: 24px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      text-decoration: none;
      color: #fff;
      transition: all 0.3s ease;
    }

    .sidebar-brand:hover {
      background: rgba(255, 255, 255, 0.05);
      color: #fff;
    }

    .sidebar-brand .logo-icon {
      width: 44px;
      height: 44px;
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-light) 100%);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      box-shadow: 0 4px 12px rgba(17, 93, 252, 0.4);
    }

    .sidebar-brand .brand-text {
      font-size: 20px;
      font-weight: 800;
      letter-spacing: -0.5px;
    }

    .sidebar-brand .brand-badge {
      font-size: 10px;
      font-weight: 700;
      background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
      color: #fff;
      padding: 3px 8px;
      border-radius: 6px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-left: auto;
    }

    /* Menú de navegación */
    .sidebar-nav {
      padding: 20px 0;
    }

    .nav-section-title {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--text-muted);
      padding: 16px 20px 8px;
    }

    .nav-link-sidebar {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 20px;
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      transition: all 0.2s ease;
      border-left: 3px solid transparent;
    }

    .nav-link-sidebar i {
      width: 20px;
      text-align: center;
      font-size: 16px;
    }

    .nav-link-sidebar:hover {
      background: var(--sidebar-hover);
      color: #fff;
      border-left-color: var(--brand);
    }

    .nav-link-sidebar.active {
      background: var(--sidebar-active);
      color: #fff;
      border-left-color: var(--brand);
    }

    .nav-link-sidebar .badge {
      margin-left: auto;
      font-size: 10px;
      padding: 4px 8px;
    }

    /* Separador */
    .sidebar-divider {
      height: 1px;
      background: rgba(255, 255, 255, 0.1);
      margin: 16px 20px;
    }

    /* ========== MAIN CONTENT ========== */
    .admin-wrapper {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      transition: margin-left 0.3s ease;
    }

    /* Topbar */
    .admin-topbar {
      height: var(--topbar-height);
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      padding: 0 32px;
      position: sticky;
      top: 0;
      z-index: 1030;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .topbar-title {
      font-size: 24px;
      font-weight: 800;
      color: #1a1d2e;
      margin: 0;
    }

    .topbar-actions {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .topbar-btn {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      background: #f8f9fb;
      border: 1px solid #e5e7eb;
      border-radius: 10px;
      color: #4b5563;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.2s ease;
    }

    .topbar-btn:hover {
      background: #fff;
      border-color: var(--brand);
      color: var(--brand);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(17, 93, 252, 0.15);
    }

    .topbar-btn i {
      font-size: 16px;
    }

    /* Notificaciones */
    .topbar-notifications {
      position: relative;
    }

    .topbar-notifications .badge {
      position: absolute;
      top: -4px;
      right: -4px;
      font-size: 10px;
      padding: 3px 6px;
    }

    /* User dropdown */
    .user-dropdown {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 14px;
      background: #f8f9fb;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .user-dropdown:hover {
      background: #fff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-light) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-weight: 700;
      font-size: 14px;
    }

    /* Content área */
    .admin-content {
      padding: 32px;
      min-height: calc(100vh - var(--topbar-height));
    }

    /* Mobile toggle */
    .sidebar-toggle {
      display: none;
      width: 40px;
      height: 40px;
      background: var(--brand);
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 18px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .sidebar-toggle:hover {
      background: var(--brand-dark);
      transform: scale(1.05);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 992px) {
      :root {
        --sidebar-width: 260px;
      }

      .admin-sidebar {
        transform: translateX(-100%);
      }

      .admin-sidebar.show {
        transform: translateX(0);
      }

      .admin-wrapper {
        margin-left: 0;
      }

      .sidebar-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .admin-topbar {
        padding: 0 16px;
      }

      .admin-content {
        padding: 20px 16px;
      }

      .topbar-title {
        font-size: 20px;
      }
    }

    @media (max-width: 576px) {
      .topbar-actions {
        gap: 8px;
      }

      .topbar-btn span {
        display: none;
      }

      .user-dropdown span {
        display: none;
      }
    }
  </style>

  @stack('styles')
</head>

<body>
  {{-- SIDEBAR --}}
  <aside class="admin-sidebar" id="adminSidebar">
    <a href="{{ route('portal.admin.dashboard') }}" class="sidebar-brand">
      <span class="logo-icon"><i class="fa-solid fa-paw"></i></span>
      <span class="brand-text">Admin Panel</span>
      <span class="brand-badge">Pro</span>
    </a>

    <nav class="sidebar-nav">
      {{-- Dashboard --}}
      <div class="nav-section-title">Principal</div>
      <a href="{{ route('portal.admin.dashboard') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-chart-line"></i>
        <span>Dashboard</span>
      </a>

      {{-- Gestión --}}
      <div class="nav-section-title">Gestión</div>
      <a href="{{ route('portal.admin.orders.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.orders.*') ? 'active' : '' }}">
        <i class="fa-solid fa-shopping-cart"></i>
        <span>Órdenes</span>
      </a>
      <a href="{{ route('portal.admin.clients.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.clients.*') ? 'active' : '' }}">
        <i class="fa-solid fa-users"></i>
        <span>Clientes</span>
      </a>
      <a href="{{ route('portal.admin.plan-settings.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.plan-settings.*') ? 'active' : '' }}">
        <i class="fa-solid fa-gear"></i>
        <span>Planes</span>
      </a>

      {{-- Email Marketing --}}
      <div class="nav-section-title">Email Marketing</div>
      <a href="{{ route('portal.admin.email-campaigns.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.email-campaigns.*') ? 'active' : '' }}">
        <i class="fa-solid fa-paper-plane"></i>
        <span>Campañas</span>
      </a>
      <a href="{{ route('portal.admin.email-templates.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.email-templates.*') ? 'active' : '' }}">
        <i class="fa-solid fa-file-code"></i>
        <span>Plantillas</span>
      </a>
      <a href="{{ route('portal.admin.email-logs.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.email-logs.*') ? 'active' : '' }}">
        <i class="fa-solid fa-envelope"></i>
        <span>Logs de Correos</span>
      </a>

      {{-- Inventario --}}
      <div class="nav-section-title">Inventario</div>
      <a href="{{ route('portal.admin.tags.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.tags.index') ? 'active' : '' }}">
        <i class="fa-solid fa-tags"></i>
        <span>TAGs</span>
      </a>
      <a href="{{ route('portal.admin.activate-tag') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.activate-tag') ? 'active' : '' }}">
        <i class="fa-solid fa-bolt"></i>
        <span>Activar TAG</span>
      </a>
      <a href="{{ route('portal.admin.tags.export') }}" class="nav-link-sidebar">
        <i class="fa-solid fa-file-csv"></i>
        <span>Exportar TAGs</span>
      </a>

      <div class="sidebar-divider"></div>

      {{-- Sistema --}}
      <div class="nav-section-title">Sistema</div>
      <a href="{{ route('portal.admin.notifications.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.notifications.*') ? 'active' : '' }}">
        <i class="fa-solid fa-bell"></i>
        <span>Notificaciones</span>
        @php
          try {
            $unreadCount = Auth::user()->unreadNotifications->count() ?? 0;
          } catch (\Exception $e) {
            $unreadCount = 0;
          }
        @endphp
        @if($unreadCount > 0)
          <span class="badge bg-danger">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
      </a>
      <a href="{{ route('portal.admin.settings.index') }}" class="nav-link-sidebar {{ request()->routeIs('portal.admin.settings.*') ? 'active' : '' }}">
        <i class="fa-solid fa-cog"></i>
        <span>Configuración</span>
      </a>

      <div class="sidebar-divider"></div>

      {{-- Salir del panel admin --}}
      <a href="{{ route('portal.dashboard') }}" class="nav-link-sidebar">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Volver a Mi Portal</span>
      </a>
    </nav>
  </aside>

  {{-- MAIN WRAPPER --}}
  <div class="admin-wrapper">
    {{-- TOPBAR --}}
    <header class="admin-topbar">
      <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fa-solid fa-bars"></i>
      </button>

      <h1 class="topbar-title d-none d-md-block">@yield('page-title', 'Panel de Administración')</h1>

      <div class="topbar-actions">
        {{-- Volver a Mi Portal --}}
        <a href="{{ route('portal.dashboard') }}" class="topbar-btn">
          <i class="fa-solid fa-home"></i>
          <span>Mi Portal</span>
        </a>

        {{-- User dropdown --}}
        <div class="dropdown">
          <div class="user-dropdown" data-bs-toggle="dropdown">
            <div class="user-avatar">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="fw-bold text-dark">{{ Str::limit(Auth::user()->name, 15) }}</span>
            <i class="fa-solid fa-chevron-down text-muted" style="font-size: 12px;"></i>
          </div>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2">
            <li><a class="dropdown-item" href="{{ route('portal.profile.edit') }}"><i class="fa-solid fa-user-gear me-2"></i> Mi perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                  <i class="fa-solid fa-right-from-bracket me-2"></i> Cerrar sesión
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </header>

    {{-- CONTENT --}}
    <main class="admin-content">
      {{-- Flash messages --}}
      @include('partials.flash')

      @yield('content')
    </main>
  </div>

  {{-- Scripts --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>

  <script>
    // Toggle sidebar en móvil
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');

    if (sidebarToggle && sidebar) {
      sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
      });

      // Cerrar sidebar al hacer clic fuera en móvil
      document.addEventListener('click', (e) => {
        if (window.innerWidth <= 992) {
          if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('show');
          }
        }
      });
    }
  </script>

  @stack('scripts')
</body>
</html>
