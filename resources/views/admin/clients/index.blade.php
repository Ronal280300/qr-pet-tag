@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="container clients-page-modern py-4">

  {{-- Header con animación --}}
  <div class="page-header mb-4">
    <div class="d-flex align-items-center justify-content-between">
      <div class="header-content">
        <h1 class="page-title mb-1">
          <i class="fa-solid fa-users me-3 title-icon"></i>
          Gestión de Clientes
        </h1>
        <p class="page-subtitle mb-0">Administra y visualiza todos tus clientes</p>
      </div>
      <div class="header-actions">
        <button id="jsRefreshClients" class="btn btn-icon-modern" title="Refrescar" data-bs-toggle="tooltip">
          <i class="fa-solid fa-rotate"></i>
        </button>
      </div>
    </div>
  </div>

  {{-- Stats Cards con animación --}}
  <div class="stats-grid mb-4">
    <a href="{{ route('portal.admin.clients.index', ['status'=>'active','q'=>$q]) }}"
      class="stat-card stat-card-active">
      <div class="stat-icon-wrapper">
        <div class="stat-icon">
          <i class="fa-solid fa-circle-check"></i>
        </div>
      </div>
      <div class="stat-content">
        <div class="stat-label">Activos</div>
        <div class="stat-value">{{ $statusCounts['active'] ?? 0 }}</div>
      </div>
      <div class="stat-trend">
        <i class="fa-solid fa-arrow-trend-up"></i>
      </div>
    </a>

    <a href="{{ route('portal.admin.clients.index', ['status'=>'pending','q'=>$q]) }}"
      class="stat-card stat-card-pending">
      <div class="stat-icon-wrapper">
        <div class="stat-icon">
          <i class="fa-solid fa-hourglass-half"></i>
        </div>
      </div>
      <div class="stat-content">
        <div class="stat-label">Pendientes</div>
        <div class="stat-value">{{ $statusCounts['pending'] ?? 0 }}</div>
      </div>
      <div class="stat-trend">
        <i class="fa-solid fa-clock"></i>
      </div>
    </a>

    <a href="{{ route('portal.admin.clients.index', ['status'=>'inactive','q'=>$q]) }}"
      class="stat-card stat-card-inactive">
      <div class="stat-icon-wrapper">
        <div class="stat-icon">
          <i class="fa-solid fa-circle-xmark"></i>
        </div>
      </div>
      <div class="stat-content">
        <div class="stat-label">Inactivos</div>
        <div class="stat-value">{{ $statusCounts['inactive'] ?? 0 }}</div>
      </div>
      <div class="stat-trend">
        <i class="fa-solid fa-ban"></i>
      </div>
    </a>

    <div class="stat-card stat-card-total">
      <div class="stat-icon-wrapper">
        <div class="stat-icon">
          <i class="fa-solid fa-users"></i>
        </div>
      </div>
      <div class="stat-content">
        <div class="stat-label">Total</div>
        <div class="stat-value">{{ ($statusCounts['active'] ?? 0) + ($statusCounts['pending'] ?? 0) + ($statusCounts['inactive'] ?? 0) }}</div>
      </div>
      <div class="stat-trend">
        <i class="fa-solid fa-chart-line"></i>
      </div>
    </div>
  </div>

  {{-- Filtros modernos --}}
  <form id="filtersForm" method="GET" action="{{ route('portal.admin.clients.index') }}">
    <div class="filters-card mb-4">
      <div class="filters-header">
        <i class="fa-solid fa-filter me-2"></i>
        <span>Filtros de búsqueda</span>
      </div>
      <div class="filters-body">
        <div class="row g-3">
          <div class="col-12 col-lg-5">
            <div class="search-wrapper">
              <i class="fa-solid fa-magnifying-glass search-icon-modern"></i>
              <input type="text"
                class="form-control form-control-search"
                name="q"
                value="{{ $q }}"
                placeholder="Buscar por nombre, email o teléfono..."
                autocomplete="off">
              @if($q)
              <button type="button" class="search-clear" onclick="this.previousElementSibling.value=''; this.closest('form').submit();">
                <i class="fa-solid fa-xmark"></i>
              </button>
              @endif
            </div>
          </div>
          <div class="col-12 col-lg-3">
            <div class="select-wrapper">
              <i class="fa-solid fa-circle-dot select-icon"></i>
              <select name="status" class="form-select form-select-modern">
                <option value="">Todos los estados</option>
                @foreach (['active'=>'✓ Activo','pending'=>'⏳ Pendiente','inactive'=>'✕ Inactivo'] as $k=>$label)
                <option value="{{ $k }}" @selected($status===$k)>{{ $label }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-6 col-lg-2">
            <button type="submit" class="btn btn-filter w-100">
              <i class="fa-solid fa-magnifying-glass me-2"></i>
              <span>Buscar</span>
            </button>
          </div>
          <div class="col-6 col-lg-2">
            <a href="{{ route('portal.admin.clients.index') }}" class="btn btn-clear w-100">
              <i class="fa-solid fa-rotate-left me-2"></i>
              <span>Limpiar</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </form>

  {{-- Tabla moderna --}}
  <div class="table-card">
    <div class="table-card-header">
      <div class="d-flex align-items-center justify-content-between">
        <div class="table-title">
          <i class="fa-solid fa-table me-2"></i>
          Lista de Clientes
          <span class="table-count">({{ $clients->total() }})</span>
        </div>
        <div class="table-actions">
          <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Exportar" id="jsExportClients" title="Descargar CSV" data-export-url="{{ route('portal.admin.clients.export') }}">
            <i class="fa-solid fa-download"></i>
          </button>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-modern-clients">
        <thead>
          <tr>
            <th style="width:30%">
              <div class="th-content">
                <i class="fa-solid fa-user me-2"></i>Cliente
              </div>
            </th>
            <th style="width:25%">
              <div class="th-content">
                <i class="fa-solid fa-envelope me-2"></i>Email
              </div>
            </th>
            <th style="width:15%">
              <div class="th-content">
                <i class="fa-solid fa-phone me-2"></i>Teléfono
              </div>
            </th>
            <th style="width:12%">
              <div class="th-content">
                <i class="fa-solid fa-circle-dot me-2"></i>Estado
              </div>
            </th>
            <th style="width:10%" class="text-center">
              <div class="th-content justify-content-center">
                <i class="fa-solid fa-paw me-2"></i>Mascotas
              </div>
            </th>
            <th style="width:8%" class="text-end">
              <div class="th-content justify-content-end">
                Acciones
              </div>
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse($clients as $c)
          <tr class="client-row" data-client-id="{{ $c->id }}">
            <td>
              <a class="row-link-overlay" href="{{ route('portal.admin.clients.show',$c) }}"></a>
              <div class="client-info">
                <div class="client-avatar">
                  <span class="avatar-text">
                    {{ Str::of($c->name)->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode('') }}
                  </span>
                  <div class="avatar-status status-{{ $c->status }}"></div>
                </div>
                <div class="client-details">
                  <div class="client-name">{{ $c->name }}</div>
                  <div class="client-id">ID: {{ $c->id }}</div>
                </div>
              </div>
            </td>
            <td>
              <a href="mailto:{{ $c->email }}" class="contact-link email-link" onclick="event.stopPropagation()">
                <i class="fa-solid fa-envelope me-2"></i>
                <span>{{ $c->email }}</span>
              </a>
            </td>
            <td>
              @if($c->phone)
              <a href="tel:{{ preg_replace('/\s+/','',$c->phone) }}" class="contact-link phone-link" onclick="event.stopPropagation()">
                <i class="fa-solid fa-phone me-2"></i>
                <span>{{ $c->phone }}</span>
              </a>
              @else
              <span class="text-muted-modern">—</span>
              @endif
            </td>
            <td>
              <div class="dropdown status-dropdown" onclick="event.stopPropagation()">
                <button class="status-badge status-badge-{{ $c->status }} dropdown-toggle"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false">
                  @if($c->status==='active')
                  <i class="fa-solid fa-circle-check me-1"></i>Activo
                  @elseif($c->status==='pending')
                  <i class="fa-solid fa-hourglass-half me-1"></i>Pendiente
                  @else
                  <i class="fa-solid fa-circle-xmark me-1"></i>Inactivo
                  @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-status">
                  <li>
                    <a class="dropdown-item status-set-modern" href="#"
                      data-url="{{ route('portal.admin.clients.update',$c) }}"
                      data-status="active"
                      data-client-name="{{ $c->name }}">
                      <i class="fa-solid fa-circle-check me-2 text-success"></i>
                      Activo
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item status-set-modern" href="#"
                      data-url="{{ route('portal.admin.clients.update',$c) }}"
                      data-status="pending"
                      data-client-name="{{ $c->name }}">
                      <i class="fa-solid fa-hourglass-half me-2 text-warning"></i>
                      Pendiente
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item status-set-modern" href="#"
                      data-url="{{ route('portal.admin.clients.update',$c) }}"
                      data-status="inactive"
                      data-client-name="{{ $c->name }}">
                      <i class="fa-solid fa-circle-xmark me-2 text-secondary"></i>
                      Inactivo
                    </a>
                  </li>
                </ul>
              </div>
            </td>
            <td class="text-center">
              <div class="pets-badge">
                <i class="fa-solid fa-paw me-1"></i>
                <span>{{ $c->pets_count }}</span>
              </div>
            </td>
            <td class="text-end">
              <a class="btn btn-action-table"
                href="{{ route('portal.admin.clients.show',$c) }}"
                onclick="event.stopPropagation()">
                <i class="fa-solid fa-pen-to-square me-1"></i>
                <span class="d-none d-lg-inline">Editar</span>
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6">
              <div class="empty-state-modern">
                <div class="empty-icon">
                  <i class="fa-regular fa-folder-open"></i>
                </div>
                <div class="empty-title">No hay clientes</div>
                <div class="empty-description">
                  No se encontraron clientes con los filtros actuales
                </div>
                <a href="{{ route('portal.admin.clients.index') }}" class="btn btn-sm btn-outline-primary mt-3">
                  <i class="fa-solid fa-rotate-left me-2"></i>Limpiar filtros
                </a>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($clients->hasPages())
    <div class="table-card-footer">
      <div class="pagination-info">
        Mostrando {{ $clients->firstItem() }} a {{ $clients->lastItem() }} de {{ $clients->total() }} registros
      </div>
      <div class="pagination-wrapper">
        {{ $clients->links() }}
      </div>
    </div>
    @endif
  </div>
</div>

{{-- ===== ESTILOS MODERNOS ===== --}}
<style>
  :root {
    --primary: #0d6efd;
    --success: #20c997;
    --warning: #ffc107;
    --danger: #dc3545;
    --secondary: #6c757d;
    --light: #f8f9fa;
    --dark: #212529;
    --border-radius: 16px;
    --border-radius-sm: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
    --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
    --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  @keyframes slideInRight {
    from {
      opacity: 0;
      transform: translateX(20px);
    }

    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes scaleIn {
    from {
      opacity: 0;
      transform: scale(0.95);
    }

    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  @keyframes pulse {

    0%,
    100% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.05);
    }
  }

  @keyframes shimmer {
    0% {
      background-position: -1000px 0;
    }

    100% {
      background-position: 1000px 0;
    }
  }

  .clients-page-modern {
    animation: fadeIn 0.4s ease-out;
  }

  /* ===== PAGE HEADER ===== */
  .page-header {
    animation: fadeInUp 0.5s ease-out;
  }

  .page-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--dark);
    display: flex;
    align-items: center;
    margin: 0;
  }

  .title-icon {
    background: linear-gradient(135deg, var(--primary), #667eea);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: pulse 3s ease-in-out infinite;
  }

  .page-subtitle {
    color: var(--secondary);
    font-size: 1rem;
    font-weight: 500;
  }

  .btn-icon-modern {
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius-sm);
    border: 2px solid #e9ecef;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
    color: var(--secondary);
  }

  .btn-icon-modern:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: rotate(180deg);
  }

  /* ===== STATS CARDS ===== */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    animation: fadeInUp 0.6s ease-out;
  }

  .stat-card {
    position: relative;
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    text-decoration: none;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, transparent, currentColor, transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .stat-card:hover::before {
    opacity: 1;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
  }

  .stat-icon-wrapper {
    flex-shrink: 0;
  }

  .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: var(--transition);
  }

  .stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
  }

  .stat-card-active {
    border: 2px solid #d4f4dd;
    color: #136b3a;
  }

  .stat-card-active .stat-icon {
    background: linear-gradient(135deg, #d4f4dd, #a8e6cf);
    color: #136b3a;
  }

  .stat-card-pending {
    border: 2px solid #fff3cd;
    color: #7c5a00;
  }

  .stat-card-pending .stat-icon {
    background: linear-gradient(135deg, #fff3cd, #ffe69c);
    color: #7c5a00;
  }

  .stat-card-inactive {
    border: 2px solid #e9ecef;
    color: #495057;
  }

  .stat-card-inactive .stat-icon {
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
    color: #495057;
  }

  .stat-card-total {
    border: 2px solid #e7e9fc;
    color: var(--primary);
  }

  .stat-card-total .stat-icon {
    background: linear-gradient(135deg, #e7e9fc, #d0d5f6);
    color: var(--primary);
  }

  .stat-content {
    flex-grow: 1;
  }

  .stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.8;
    margin-bottom: 0.25rem;
  }

  .stat-value {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
  }

  .stat-trend {
    font-size: 1.25rem;
    opacity: 0.5;
    transition: var(--transition);
  }

  .stat-card:hover .stat-trend {
    opacity: 1;
    transform: translateX(4px);
  }

  /* ===== FILTERS CARD ===== */
  .filters-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    animation: fadeInUp 0.7s ease-out;
  }

  .filters-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 1rem 1.5rem;
    border-bottom: 2px solid #e9ecef;
    font-weight: 700;
    color: var(--dark);
    display: flex;
    align-items: center;
    font-size: 1.05rem;
  }

  .filters-body {
    padding: 1.5rem;
  }

  .search-wrapper {
    position: relative;
  }

  .search-icon-modern {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary);
    font-size: 1.1rem;
    z-index: 2;
    pointer-events: none;
  }

  .form-control-search {
    padding: 14px 48px 14px 48px;
    border: 2px solid #e9ecef;
    border-radius: var(--border-radius-sm);
    font-size: 0.95rem;
    transition: var(--transition);
    background: white;
  }

  .form-control-search:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    outline: none;
  }

  .search-clear {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: none;
    background: #e9ecef;
    color: var(--secondary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    z-index: 2;
  }

  .search-clear:hover {
    background: var(--danger);
    color: white;
    transform: translateY(-50%) scale(1.1);
  }

  .select-wrapper {
    position: relative;
  }

  .select-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary);
    z-index: 2;
    pointer-events: none;
  }

  .form-select-modern {
    padding: 14px 40px 14px 48px;
    border: 2px solid #e9ecef;
    border-radius: var(--border-radius-sm);
    font-size: 0.95rem;
    font-weight: 500;
    transition: var(--transition);
    background: white;
    cursor: pointer;
  }

  .form-select-modern:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    outline: none;
  }

  .btn-filter {
    padding: 14px 24px;
    background: linear-gradient(135deg, var(--primary), #667eea);
    border: none;
    border-radius: var(--border-radius-sm);
    color: white;
    font-weight: 600;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
  }

  .btn-filter::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
  }

  .btn-filter:hover::before {
    width: 300px;
    height: 300px;
  }

  .btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
  }

  .btn-clear {
    padding: 14px 24px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: var(--border-radius-sm);
    color: var(--secondary);
    font-weight: 600;
    transition: var(--transition);
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .btn-clear:hover {
    background: #f8f9fa;
    border-color: var(--secondary);
    color: var(--dark);
    transform: translateY(-2px);
  }

  /* ===== TABLE CARD ===== */
  .table-card {
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    animation: fadeInUp 0.8s ease-out;
  }

  .table-card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-bottom: 2px solid #e9ecef;
    padding: 1.25rem 1.5rem;
  }

  .table-title {
    font-weight: 700;
    color: var(--dark);
    font-size: 1.1rem;
    display: flex;
    align-items: center;
  }

  .table-count {
    color: var(--secondary);
    font-weight: 500;
    margin-left: 0.5rem;
  }

  .table-modern-clients {
    margin: 0;
    font-size: 0.9rem;
  }

  .table-modern-clients thead {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  }

  .table-modern-clients th {
    border-bottom: 2px solid #e9ecef;
    padding: 1rem 1.5rem;
    font-weight: 700;
    color: var(--dark);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
  }

  .th-content {
    display: flex;
    align-items: center;
  }

  .client-row {
    position: relative;
    transition: var(--transition);
    cursor: pointer;
  }

  .client-row::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 0;
    background: linear-gradient(90deg, var(--primary), transparent);
    transition: width 0.3s ease;
    opacity: 0.1;
  }

  .client-row:hover::before {
    width: 4px;
  }

  .client-row:hover {
    background: linear-gradient(90deg, rgba(13, 110, 253, 0.03), transparent);
    transform: translateX(2px);
  }

  .client-row td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
  }

  .row-link-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
  }

  .client-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    z-index: 2;
  }

  .client-avatar {
    position: relative;
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius-sm);
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: var(--transition);
  }

  .client-row:hover .client-avatar {
    transform: scale(1.05) rotate(-3deg);
  }

  .avatar-text {
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
  }

  .avatar-status {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid white;
  }

  .status-active {
    background: var(--success);
    box-shadow: 0 0 8px rgba(32, 201, 151, 0.6);
  }

  .status-pending {
    background: var(--warning);
    box-shadow: 0 0 8px rgba(255, 193, 7, 0.6);
  }

  .status-inactive {
    background: var(--secondary);
    box-shadow: 0 0 8px rgba(108, 117, 125, 0.6);
  }

  .client-details {
    min-width: 0;
  }

  .client-name {
    font-weight: 600;
    color: var(--dark);
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .client-id {
    font-size: 0.8rem;
    color: var(--secondary);
    font-weight: 500;
  }

  .contact-link {
    display: inline-flex;
    align-items: center;
    color: var(--dark);
    text-decoration: none;
    transition: var(--transition);
    position: relative;
    z-index: 2;
    padding: 6px 12px;
    border-radius: 8px;
    margin: -6px -12px;
  }

  .contact-link:hover {
    background: #f8f9fa;
    color: var(--primary);
    transform: translateX(4px);
  }

  .email-link i {
    color: #ea4335;
  }

  .phone-link i {
    color: #34a853;
  }

  .text-muted-modern {
    color: var(--secondary);
    font-style: italic;
  }

  /* ===== STATUS BADGE ===== */
  .status-dropdown {
    position: relative;
    z-index: 3;
  }

  .status-badge {
    padding: 8px 16px;
    border-radius: 999px;
    border: 2px solid;
    font-weight: 600;
    font-size: 0.85rem;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    cursor: pointer;
  }

  .status-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .status-badge-active {
    background: linear-gradient(135deg, #d4f4dd, #a8e6cf);
    color: #136b3a;
    border-color: #a8e6cf;
  }

  .status-badge-pending {
    background: linear-gradient(135deg, #fff3cd, #ffe69c);
    color: #7c5a00;
    border-color: #ffe69c;
  }

  .status-badge-inactive {
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
    color: #495057;
    border-color: #dee2e6;
  }

  .dropdown-menu-status {
    border: none;
    border-radius: var(--border-radius-sm);
    box-shadow: var(--shadow-lg);
    padding: 8px;
    animation: scaleIn 0.2s ease-out;
    min-width: 180px;
  }

  .dropdown-menu-status .dropdown-item {
    border-radius: 8px;
    padding: 10px 16px;
    transition: var(--transition);
    display: flex;
    align-items: center;
    font-weight: 500;
    cursor: pointer;
  }

  .dropdown-menu-status .dropdown-item:hover {
    background: #f8f9fa;
    transform: translateX(4px);
  }

  .status-set-modern {
    cursor: pointer;
  }

  /* ===== PETS BADGE ===== */
  .pets-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e7e9fc, #d0d5f6);
    color: var(--primary);
    padding: 8px 14px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 0.9rem;
    border: 2px solid #d0d5f6;
    transition: var(--transition);
  }

  .client-row:hover .pets-badge {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
  }

  .table-modern-clients td.text-center {
    text-align: center;
    vertical-align: middle;
  }

  /* ===== ACTION BUTTON ===== */
  .btn-action-table {
    padding: 8px 16px;
    border: 2px solid var(--primary);
    border-radius: 10px;
    background: white;
    color: var(--primary);
    font-weight: 600;
    font-size: 0.85rem;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    position: relative;
    z-index: 2;
  }

  .btn-action-table:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
  }

  /* ===== EMPTY STATE ===== */
  .empty-state-modern {
    text-align: center;
    padding: 4rem 2rem;
    animation: fadeInUp 0.5s ease-out;
  }

  .empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1.5rem;
    animation: pulse 2s ease-in-out infinite;
  }

  .empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: 0.5rem;
  }

  .empty-description {
    color: var(--secondary);
    font-size: 1rem;
  }

  /* ===== TABLE FOOTER ===== */
  .table-card-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-top: 2px solid #e9ecef;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .pagination-info {
    color: var(--secondary);
    font-size: 0.9rem;
    font-weight: 500;
  }

  .pagination-wrapper .pagination {
    margin: 0;
  }

  .pagination-wrapper .page-link {
    border: 2px solid #e9ecef;
    color: var(--dark);
    padding: 8px 16px;
    margin: 0 4px;
    border-radius: 10px;
    font-weight: 600;
    transition: var(--transition);
  }

  .pagination-wrapper .page-link:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
    transform: translateY(-2px);
  }

  .pagination-wrapper .page-item.active .page-link {
    background: var(--primary);
    border-color: var(--primary);
  }

  /* ===== SWEET ALERT STYLES ===== */
  .swal-modern {
    border-radius: var(--border-radius) !important;
    font-family: inherit !important;
  }

  .table-modern-clients .client-row td:first-child {
    position: absolute;
  }

  .table-modern-clients .row-link-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    display: block;
  }

  .btn-swal-confirm,
  .btn-swal-cancel {
    border-radius: 10px !important;
    padding: 10px 24px !important;
    font-weight: 600 !important;
    transition: var(--transition) !important;
  }

  .btn-swal-confirm:hover,
  .btn-swal-cancel:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
  }

  /* ===== LOADING OVERLAY ===== */
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(4px);
  }

  .loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #e9ecef;
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  /* ===== RESPONSIVE ===== */
  @media (max-width: 991px) {
    .page-title {
      font-size: 1.75rem;
    }

    .stats-grid {
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
    }

    .stat-card {
      padding: 1.25rem;
    }

    .stat-icon {
      width: 48px;
      height: 48px;
      font-size: 1.25rem;
    }

    .stat-value {
      font-size: 1.75rem;
    }

    .filters-body {
      padding: 1rem;
    }

    .table-card-header,
    .table-card-footer {
      padding: 1rem;
    }
  }

  @media (max-width: 767px) {
    .page-title {
      font-size: 1.5rem;
    }

    .page-subtitle {
      font-size: 0.9rem;
    }

    .btn-icon-modern {
      width: 40px;
      height: 40px;
    }

    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .stat-card {
      padding: 1rem;
    }

    .stat-value {
      font-size: 1.5rem;
    }

    .filters-header {
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
    }

    .client-row td {
      padding: 1rem;
    }

    .client-avatar {
      width: 40px;
      height: 40px;
    }

    .avatar-text {
      font-size: 1rem;
    }

    .table-card-footer {
      flex-direction: column;
      align-items: flex-start;
    }
  }

  @media (max-width: 576px) {
    .stats-grid {
      grid-template-columns: 1fr;
    }

    .form-control-search,
    .form-select-modern {
      padding: 12px 40px 12px 40px;
    }

    .btn-filter,
    .btn-clear {
      padding: 12px 16px;
      font-size: 0.9rem;
    }

    .table-modern-clients {
      font-size: 0.85rem;
    }

    .contact-link span {
      font-size: 0.85rem;
    }
  }
</style>

{{-- ===== JAVASCRIPT COMPLETO ===== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  (function() {
    // ==========================================
    // INICIALIZACIÓN
    // ==========================================
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // ==========================================
    // BÚSQUEDA CON ENTER
    // ==========================================
    const filtersForm = document.getElementById('filtersForm');
    const searchInput = filtersForm.querySelector('input[name="q"]');

    searchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        filtersForm.submit();
      }
    });

    // ==========================================
    // CAMBIO DE ESTADO CON SWEET ALERT
    // ==========================================
    const statusButtons = document.querySelectorAll('.status-set-modern');

    statusButtons.forEach(button => {
      button.addEventListener('click', async (e) => {
        e.preventDefault();

        const url = button.getAttribute('data-url');
        const newStatus = button.getAttribute('data-status');
        const clientName = button.getAttribute('data-client-name');

        // Determinar el label y color del nuevo estado
        let statusLabel = '';
        let statusIcon = '';
        let statusColor = '';

        if (newStatus === 'active') {
          statusLabel = 'Activo';
          statusIcon = 'success';
          statusColor = '#20c997';
        } else if (newStatus === 'pending') {
          statusLabel = 'Pendiente';
          statusIcon = 'warning';
          statusColor = '#ffc107';
        } else {
          statusLabel = 'Inactivo';
          statusIcon = 'info';
          statusColor = '#6c757d';
        }

        // Confirmar con Sweet Alert
        const result = await Swal.fire({
          title: '¿Cambiar estado?',
          html: `Se cambiará el estado de <strong>${clientName}</strong> a <strong>${statusLabel}</strong>`,
          icon: statusIcon,
          showCancelButton: true,
          confirmButtonColor: statusColor,
          cancelButtonColor: '#6c757d',
          confirmButtonText: '<i class="fa-solid fa-check me-2"></i>Sí, cambiar',
          cancelButtonText: '<i class="fa-solid fa-xmark me-2"></i>Cancelar',
          customClass: {
            popup: 'swal-modern',
            confirmButton: 'btn-swal-confirm',
            cancelButton: 'btn-swal-cancel'
          }
        });

        if (!result.isConfirmed) return;

        // Mostrar loading
        Swal.fire({
          title: 'Actualizando...',
          text: 'Por favor espera',
          allowOutsideClick: false,
          allowEscapeKey: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        try {
          const response = await fetch(url, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json',
              'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
              _method: 'PUT',
              status: newStatus
            })
          });

          if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
          }

          // Éxito
          await Swal.fire({
            title: '¡Estado actualizado!',
            text: `El cliente ahora está ${statusLabel}`,
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            customClass: {
              popup: 'swal-modern'
            }
          });

          // Recargar página
          window.location.reload();

        } catch (error) {
          console.error('Error:', error);

          Swal.fire({
            title: 'Error',
            text: 'No se pudo actualizar el estado del cliente',
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Entendido',
            customClass: {
              popup: 'swal-modern',
              confirmButton: 'btn-swal-confirm'
            }
          });
        }
      });
    });

    // ==========================================
    // ANIMACIÓN DE ENTRADA ESCALONADA
    // ==========================================
    const clientRows = document.querySelectorAll('.client-row');

    clientRows.forEach((row, index) => {
      row.style.animation = `fadeInUp 0.5s ease-out ${index * 0.05}s backwards`;
    });

    // ==========================================
    // SMOOTH SCROLL AL RECARGAR
    // ==========================================
    if (window.location.hash) {
      setTimeout(() => {
        const element = document.querySelector(window.location.hash);
        if (element) {
          element.scrollIntoView({
            behavior: 'smooth'
          });
        }
      }, 100);
    }

    // ==========================================
    // EFECTO HOVER EN CARDS
    // ==========================================
    const statCards = document.querySelectorAll('.stat-card');

    statCards.forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.zIndex = '10';
      });

      card.addEventListener('mouseleave', function() {
        this.style.zIndex = '1';
      });
    });

    // ==========================================
    // LOADING OVERLAY (para navegación)
    // ==========================================
    function showLoading() {
      const overlay = document.createElement('div');
      overlay.className = 'loading-overlay';
      overlay.innerHTML = '<div class="loading-spinner"></div>';
      document.body.appendChild(overlay);
    }

    // Mostrar loading al navegar
    document.querySelectorAll('a[href]:not([onclick]):not([target="_blank"])').forEach(link => {
      if (link.href.includes(window.location.origin)) {
        link.addEventListener('click', function(e) {
          if (!e.ctrlKey && !e.metaKey) {
            showLoading();
          }
        });
      }
    });

    // ==========================================
    // AUTO-FOCUS EN BÚSQUEDA
    // ==========================================
    if (searchInput && !searchInput.value) {
      searchInput.focus();
    }

    // ==========================================
    // ANIMACIÓN DEL BOTÓN REFRESH
    // ==========================================
    const refreshBtn = document.querySelector('.btn-icon-modern');
    if (refreshBtn) {
      refreshBtn.addEventListener('click', function(e) {
        e.preventDefault();
        showLoading();
        window.location.reload();
      });
    }


  })();
</script>
<script>
  (() => {
    // === Exportar CSV preservando los filtros actuales (?q=&status=) ===
    const exportBtn = document.getElementById('jsExportClients');
    if (exportBtn) {
      exportBtn.addEventListener('click', () => {
        const base = exportBtn.dataset.exportUrl; // route('portal.admin.clients.export')
        const params = new URLSearchParams(window.location.search); // q, status, etc.
        const url = params.toString() ? `${base}?${params.toString()}` : base;

        // feedback rápido
        exportBtn.disabled = true;
        exportBtn.classList.add('opacity-75');
        setTimeout(() => {
          exportBtn.disabled = false;
          exportBtn.classList.remove('opacity-75');
        }, 1500);

        window.location.href = url; // descarga
      });
    }

    // === Refrescar la vista (mantiene filtros y página) ===
    const refreshBtn = document.getElementById('jsRefreshClients');
    if (refreshBtn) {
      refreshBtn.addEventListener('click', () => {
        // Opcional: animación rápida
        refreshBtn.classList.add('fa-spin');
        setTimeout(() => {
          window.location.reload();
        }, 150);
      });
    }
  })();
</script>

@endsection