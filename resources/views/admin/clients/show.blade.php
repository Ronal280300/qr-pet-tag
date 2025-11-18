@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="container client-detail py-4">

    <a href="{{ route('portal.admin.clients.index') }}" class="btn btn-link ps-0 mb-4 back-link">
        <i class="fa-solid fa-arrow-left-long me-2"></i> Volver a clientes
    </a>

    <div class="row g-4">
        {{-- ========= Columna izquierda: FORM ========= --}}
        <div class="col-lg-5">
            <div class="card shadow-card card-modern">
                <div class="card-body p-4">

                    {{-- Header cliente --}}
                    <div class="client-header mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-initials-modern xlg">
                                <span class="avatar-text">{{ Str::of($user->name)->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode('') }}</span>
                                <div class="avatar-ring"></div>
                            </div>
                            <div class="flex-grow-1">
                                <h2 class="h3 mb-1 fw-bold">{{ $user->name }}</h2>
                                <div class="text-muted small d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-fingerprint"></i>
                                    <span>ID: {{ $user->id }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="quick-actions d-flex gap-2">
                            <a class="btn btn-action" href="mailto:{{ $user->email }}" data-bs-toggle="tooltip" title="Enviar email">
                                <i class="fa-solid fa-envelope"></i>
                            </a>
                            @if($user->phone)
                            <a class="btn btn-action" href="tel:{{ preg_replace('/\D+/','', $user->phone) }}" data-bs-toggle="tooltip" title="Llamar">
                                <i class="fa-solid fa-phone"></i>
                            </a>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-action" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" title="Más opciones">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-modern" aria-labelledby="dropdownMenuButton">
                                    @if($user->currentPlan && $user->plan_is_active && $user->plan_expires_at)
                                    <li>
                                        <form method="POST" action="{{ route('portal.admin.clients.send-reminder', $user) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fa-solid fa-bell me-2 text-warning"></i>
                                                Enviar recordatorio de pago
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <button type="button" class="dropdown-item js-delete-client">
                                            <i class="fa-solid fa-trash-can me-2 text-danger"></i>
                                            Eliminar cliente
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Mensajes flash --}}
                    @if(session('success'))
                    <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    {{-- Indicador cambios --}}
                    <div id="dirtyBadge" class="alert alert-warning alert-modern py-2 px-3 d-none pulse-animation">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <span class="fw-semibold">Cambios sin guardar</span>
                    </div>

                    {{-- ======= FORM ======= --}}
                    <form id="clientForm" method="POST" action="{{ route('portal.admin.clients.update', $user) }}" class="needs-validation" novalidate>
                        @csrf @method('PUT')

                        {{-- Nombre --}}
                        <div class="mb-3 form-group-modern">
                            <label class="form-label-modern">
                                <i class="fa-regular fa-id-card label-icon"></i>
                                Nombre completo
                            </label>
                            <div class="input-wrapper">
                                <input class="form-control form-control-modern" name="name" value="{{ old('name',$user->name) }}" required>
                                <div class="input-focus-effect"></div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3 form-group-modern">
                            <label class="form-label-modern">
                                <i class="fa-solid fa-envelope label-icon"></i>
                                Correo electrónico
                            </label>
                            <div class="input-wrapper">
                                <input class="form-control form-control-modern" type="email" name="email" value="{{ old('email',$user->email) }}" required>
                                <div class="input-focus-effect"></div>
                            </div>
                        </div>

                        <div class="row g-3">
                            {{-- Teléfono (CR mask) --}}
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fa-solid fa-phone label-icon"></i>
                                        Teléfono
                                    </label>
                                    <div class="input-wrapper">
                                        <input
                                            id="phoneCR"
                                            class="form-control form-control-modern"
                                            name="phone"
                                            inputmode="numeric"
                                            autocomplete="tel"
                                            placeholder="+506 0000 0000"
                                            value="{{ old('phone', $user->phone) }}"
                                            maxlength="14"
                                            pattern="^\+506(\s)?\d{4}(\s)?\d{4}$">
                                        <div class="input-focus-effect"></div>
                                    </div>
                                    <div class="form-text-modern">
                                        <i class="fa-solid fa-info-circle me-1"></i>
                                        Formato: <span class="text-primary">+506 0000 0000</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Estado (ring dinámico) --}}
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-circle-dot label-icon"></i>
                                        Estado
                                        <i class="fa-regular fa-circle-question text-muted tooltip-icon" data-bs-toggle="tooltip"
                                            title="Pendiente permite gestionar mascotas por 5 días; luego pasa a Inactivo automáticamente."></i>
                                    </label>
                                    <div class="status-select-wrapper" data-state-wrapper>
                                        <select name="status" class="form-select form-select-modern" data-state-select>
                                            <option value="active" @selected($user->status==='active')>✓ Activo</option>
                                            <option value="pending" @selected($user->status==='pending')>⏳ Pendiente</option>
                                            <option value="inactive" @selected($user->status==='inactive')>✕ Inactivo</option>
                                        </select>
                                        <div class="status-indicator"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fa-solid fa-location-dot label-icon"></i>
                                        Dirección
                                    </label>
                                    <div class="input-wrapper">
                                        <input class="form-control form-control-modern" name="address" value="{{ old('address',$user->address) }}">
                                        <div class="input-focus-effect"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label class="form-label-modern">
                                        <i class="fa-solid fa-user-shield label-icon"></i>
                                        Contacto emergencia
                                    </label>
                                    <div class="input-wrapper">
                                        <input class="form-control form-control-modern" name="emergency_contact" value="{{ old('emergency_contact',$user->emergency_contact) }}">
                                        <div class="input-focus-effect"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-box mt-4">
                            <i class="fa-regular fa-clock me-2"></i>
                            <span>Estado desde: <strong>{{ optional($user->status_changed_at ?? $user->created_at)->format('d/m/Y H:i') }}</strong></span>
                        </div>

                        <div class="d-grid mt-4">
                            <button id="saveBtn" class="btn btn-primary btn-modern">
                                <span class="btn-content">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                    <span class="btn-text">Guardar cambios</span>
                                </span>
                                <span class="btn-loading d-none">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    <span>Guardando...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- ========= /Columna izquierda ========= --}}

        {{-- ========= Columna derecha: Mascotas ========= --}}
        <div class="col-lg-7">
            <div class="card shadow-card card-modern">
                <div class="card-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="header-icon">
                                <i class="fa-solid fa-paw"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Mascotas vinculadas</h5>
                                <small class="text-muted">{{ $pets->count() }} {{ $pets->count() === 1 ? 'mascota' : 'mascotas' }}</small>
                            </div>
                        </div>
                        <button class="btn btn-toggle" data-bs-toggle="collapse" data-bs-target="#petsCollapse" aria-expanded="true">
                            <i class="fa-solid fa-chevron-down toggle-icon"></i>
                        </button>
                    </div>
                </div>

                <div id="petsCollapse" class="collapse show">
                    <div class="card-body p-0">
                        {{-- TABLA PARA DESKTOP --}}
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="th-content">
                                                <i class="fa-solid fa-paw me-2"></i>Nombre
                                            </div>
                                        </th>
                                        <th>
                                            <div class="th-content">
                                                <i class="fa-solid fa-dna me-2"></i>Especie
                                            </div>
                                        </th>
                                        <th class="d-none d-lg-table-cell">
                                            <div class="th-content">
                                                <i class="fa-regular fa-calendar me-2"></i>Enlace
                                            </div>
                                        </th>
                                        <th>
                                            <div class="th-content">
                                                <i class="fa-solid fa-qrcode me-2"></i>QR
                                            </div>
                                        </th>
                                        <th class="text-end">
                                            <div class="th-content justify-content-end">
                                                Acciones
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pets as $p)
                                    <tr class="pet-row">
                                        <td>
                                            <div class="pet-name">
                                                <i class="fa-solid fa-circle pet-indicator"></i>
                                                <span class="fw-semibold">{{ $p->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="species-badge">{{ $p->species ?? '—' }}</span>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            @if($p->activated_at)
                                            <span class="date-badge">
                                                <i class="fa-regular fa-calendar-check me-1"></i>
                                                {{ \Carbon\Carbon::parse($p->activated_at)->format('d/m/Y H:i') }}
                                            </span>
                                            @else
                                            <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($p->is_activated)
                                            <span class="status-badge status-active">
                                                <i class="fa-solid fa-qrcode me-1"></i>Activo
                                            </span>
                                            @else
                                            <span class="status-badge status-inactive">
                                                <i class="fa-solid fa-qrcode me-1"></i>Sin activar
                                            </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group-actions">
                                                <button class="btn btn-transfer"
                                                    data-bs-toggle="modal" data-bs-target="#transferModal"
                                                    data-pet-id="{{ $p->id }}" data-pet-name="{{ $p->name }}"
                                                    title="Transferir a otro cliente">
                                                    <i class="fa-solid fa-right-left me-1"></i>
                                                    <span class="d-none d-xl-inline">Transferir</span>
                                                </button>
                                                <button class="btn btn-detach"
                                                    data-bs-toggle="modal" data-bs-target="#detachModal"
                                                    data-pet-id="{{ $p->id }}" data-pet-name="{{ $p->name }}"
                                                    title="Desvincular del cliente">
                                                    <i class="fa-solid fa-link-slash me-1"></i>
                                                    <span class="d-none d-xl-inline">Desvincular</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="empty-state">
                                            <div class="empty-content">
                                                <i class="fa-solid fa-inbox fa-3x mb-3 text-muted"></i>
                                                <p class="mb-0 text-muted">No hay mascotas vinculadas</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- VISTA DE TARJETAS PARA MÓVILES --}}
                        <div class="d-md-none">
                            @forelse($pets as $p)
                            <div class="pet-card-mobile">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="fa-solid fa-circle pet-indicator"></i>
                                            <h6 class="fw-bold mb-0">{{ $p->name }}</h6>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="species-badge">
                                                <i class="fa-solid fa-dna me-1"></i>{{ $p->species ?? '—' }}
                                            </span>
                                            @if($p->is_activated)
                                            <span class="status-badge status-active" style="font-size:0.75rem;">
                                                <i class="fa-solid fa-qrcode me-1"></i>QR Activo
                                            </span>
                                            @else
                                            <span class="status-badge status-inactive" style="font-size:0.75rem;">
                                                <i class="fa-solid fa-qrcode me-1"></i>Sin activar
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if($p->activated_at)
                                <div class="pet-info-mobile mb-3">
                                    <i class="fa-regular fa-calendar-check text-primary"></i>
                                    <span class="text-muted small">Enlazado: {{ \Carbon\Carbon::parse($p->activated_at)->format('d/m/Y H:i') }}</span>
                                </div>
                                @endif

                                <div class="d-flex gap-2">
                                    <button class="btn btn-transfer flex-fill"
                                        data-bs-toggle="modal" data-bs-target="#transferModal"
                                        data-pet-id="{{ $p->id }}" data-pet-name="{{ $p->name }}">
                                        <i class="fa-solid fa-right-left me-1"></i>Transferir
                                    </button>
                                    <button class="btn btn-detach flex-fill"
                                        data-bs-toggle="modal" data-bs-target="#detachModal"
                                        data-pet-id="{{ $p->id }}" data-pet-name="{{ $p->name }}">
                                        <i class="fa-solid fa-link-slash me-1"></i>Desvincular
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state-mobile">
                                <i class="fa-solid fa-inbox fa-3x mb-3 text-muted"></i>
                                <p class="mb-0 text-muted">No hay mascotas vinculadas</p>
                            </div>
                            @endforelse
                        </div>

                        @if($pets->count() > 0)
                        <div class="table-footer">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <span>La fecha de enlace corresponde a la activación del código QR</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- ========= /Columna derecha ========= --}}
    </div>

    {{-- ========= Estadísticas y Órdenes ========= --}}
    <div class="row g-4 mt-2">
        {{-- Estadísticas --}}
        <div class="col-12">
            <div class="card shadow-card card-modern">
                <div class="card-header-modern">
                    <div class="d-flex align-items-center gap-2">
                        <div class="header-icon">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Estadísticas del cliente</h5>
                            <small class="text-muted">Resumen de actividad</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="stat-box">
                                <div class="stat-icon-sm bg-primary-subtle">
                                    <i class="fa-solid fa-shopping-cart text-primary"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-value-sm">{{ $stats['total_orders'] }}</div>
                                    <div class="stat-label-sm">Órdenes totales</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-box">
                                <div class="stat-icon-sm bg-success-subtle">
                                    <i class="fa-solid fa-dollar-sign text-success"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-value-sm">₡{{ number_format($stats['total_spent'], 0, ',', '.') }}</div>
                                    <div class="stat-label-sm">Total gastado</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-box">
                                <div class="stat-icon-sm bg-warning-subtle">
                                    <i class="fa-solid fa-clock text-warning"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-value-sm">{{ $stats['pending_orders'] }}</div>
                                    <div class="stat-label-sm">Órdenes pendientes</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="stat-box">
                                <div class="stat-icon-sm bg-info-subtle">
                                    <i class="fa-solid fa-paw text-info"></i>
                                </div>
                                <div class="stat-details">
                                    <div class="stat-value-sm">{{ $stats['active_pets'] }}/{{ $stats['total_pets'] }}</div>
                                    <div class="stat-label-sm">Mascotas activas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Órdenes --}}
        <div class="col-12">
            <div class="card shadow-card card-modern">
                <div class="card-header-modern">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="header-icon">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Historial de órdenes</h5>
                                <small class="text-muted">Últimas 10 órdenes</small>
                            </div>
                        </div>
                        @if($orders->count() > 0)
                        <a href="{{ route('portal.admin.orders.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                            Ver todas <i class="fa-solid fa-arrow-right ms-1"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-modern mb-0">
                            <thead>
                                <tr>
                                    <th><div class="th-content"><i class="fa-solid fa-hashtag me-2"></i>Orden</div></th>
                                    <th><div class="th-content"><i class="fa-solid fa-box me-2"></i>Plan</div></th>
                                    <th><div class="th-content"><i class="fa-solid fa-dollar-sign me-2"></i>Monto</div></th>
                                    <th><div class="th-content"><i class="fa-solid fa-circle-check me-2"></i>Estado</div></th>
                                    <th><div class="th-content"><i class="fa-regular fa-calendar me-2"></i>Fecha</div></th>
                                    <th class="text-end"><div class="th-content justify-content-end">Acciones</div></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td><span class="fw-semibold">#{{ $order->order_number }}</span></td>
                                    <td>
                                        @if($order->plan)
                                        <span class="badge bg-primary-subtle text-primary border border-primary">
                                            {{ $order->plan->name }}
                                        </span>
                                        @else
                                        <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td><span class="fw-semibold">₡{{ number_format($order->total_amount, 0, ',', '.') }}</span></td>
                                    <td>
                                        @if($order->payment_verified)
                                        <span class="status-badge status-active">
                                            <i class="fa-solid fa-circle-check me-1"></i>Verificado
                                        </span>
                                        @else
                                        <span class="status-badge status-inactive">
                                            <i class="fa-solid fa-clock me-1"></i>Pendiente
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="date-badge">
                                            <i class="fa-regular fa-calendar me-1"></i>
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('portal.admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa-regular fa-eye me-1"></i>Ver
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="empty-content">
                            <i class="fa-solid fa-inbox fa-3x mb-3 text-muted"></i>
                            <p class="mb-0 text-muted">No hay órdenes registradas</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- ========= /Estadísticas y Órdenes ========= --}}
</div>

{{-- Formulario oculto para eliminar cliente --}}
<form id="deleteClientForm" method="POST" action="{{ route('portal.admin.clients.destroy', $user) }}" style="display: none;">
  @csrf
  @method('DELETE')
</form>


{{-- Modal Desenlazar --}}
<div class="modal fade" id="detachModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="detachForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-content modal-modern">
                <div class="modal-header border-0 pb-0">
                    <div class="modal-icon-wrapper">
                        <div class="modal-icon modal-icon-danger">
                            <i class="fa-solid fa-link-slash"></i>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center pt-2">
                    <h5 class="modal-title-modern mb-3">Desenlazar mascota</h5>
                    <p class="text-muted mb-4">
                        ¿Estás seguro que deseas desenlazar a <strong id="petNameLabel" class="text-dark">esta mascota</strong> del cliente?
                    </p>
                    <div class="form-check-modern text-start">
                        <input class="form-check-input-modern" type="checkbox" name="reset_qr" id="resetQr">
                        <label class="form-check-label-modern" for="resetQr">
                            <span class="check-title">Restablecer código QR</span>
                            <span class="check-description">Dejar la mascota sin activar</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger btn-modal">
                        <i class="fa-solid fa-link-slash me-2"></i>Desenlazar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Transferir --}}
<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="transferForm" method="POST">
            @csrf
            <div class="modal-content modal-modern">
                <div class="modal-header border-0 pb-0">
                    <div class="modal-icon-wrapper">
                        <div class="modal-icon modal-icon-primary">
                            <i class="fa-solid fa-right-left"></i>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center pt-2">
                    <h5 class="modal-title-modern mb-3">Transferir mascota</h5>
                    <p class="text-muted mb-4">
                        Transfiere a <strong id="transferPetNameLabel" class="text-dark">esta mascota</strong> a otro cliente
                    </p>
                    <div class="form-group-modern text-start mb-3">
                        <label class="form-label-modern">
                            <i class="fa-solid fa-user label-icon"></i>
                            Seleccionar cliente destino
                        </label>
                        <select name="to_user_id" id="transferClientSelect" class="form-select form-select-modern" required>
                            <option value="">Selecciona un cliente...</option>
                            @php
                                $otherClients = \App\Models\User::where('is_admin', false)
                                    ->where('id', '!=', $user->id)
                                    ->orderBy('name')
                                    ->get(['id', 'name', 'email']);
                            @endphp
                            @foreach($otherClients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check-modern text-start">
                        <input class="form-check-input-modern" type="checkbox" name="keep_qr" id="keepQr" checked>
                        <label class="form-check-label-modern" for="keepQr">
                            <span class="check-title">Mantener código QR activo</span>
                            <span class="check-description">La mascota mantiene su activación actual</span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary btn-modal">
                        <i class="fa-solid fa-right-left me-2"></i>Transferir
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ===== ESTILOS COMPLETOS ===== --}}
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

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
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

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }

    @keyframes gradient-rotate {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .client-detail {
        animation: fadeIn 0.4s ease-out;
    }

    .pulse-animation {
        animation: pulse 2s ease-in-out infinite;
    }

    .back-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: var(--border-radius-sm);
    }

    .back-link:hover {
        background: rgba(13, 110, 253, 0.1);
        transform: translateX(-4px);
        color: var(--primary);
    }

    .back-link i {
        transition: transform 0.3s ease;
    }

    .back-link:hover i {
        transform: translateX(-4px);
    }

    .shadow-card {
        box-shadow: var(--shadow-md);
        transition: var(--transition);
    }

    .card-modern {
        border: none;
        border-radius: var(--border-radius);
        overflow: hidden;
        animation: fadeInUp 0.5s ease-out;
    }

    .card-modern:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .client-header {
        animation: fadeInUp 0.6s ease-out;
    }

    .avatar-initials-modern {
        position: relative;
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
        transition: var(--transition);
    }

    .avatar-initials-modern:hover {
        transform: scale(1.05) rotate(-2deg);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    }

    .avatar-text {
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        z-index: 2;
    }

    .avatar-ring {
        position: absolute;
        inset: -4px;
        border-radius: 22px;
        background: linear-gradient(135deg, #667eea, #764ba2, #f093fb, #667eea);
        background-size: 300% 300%;
        animation: gradient-rotate 4s ease infinite;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .avatar-initials-modern:hover .avatar-ring {
        opacity: 0.5;
    }

    .quick-actions {
        animation: fadeInUp 0.7s ease-out;
    }

    .btn-action {
        width: 42px;
        height: 42px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e9ecef;
        border-radius: var(--border-radius-sm);
        background: white;
        color: var(--secondary);
        transition: var(--transition);
        font-size: 1rem;
    }

    .btn-action:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .alert-modern {
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        animation: scaleIn 0.3s ease-out;
        font-size: 0.95rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        color: #856404;
    }

    .form-group-modern {
        animation: fadeInUp 0.5s ease-out backwards;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        letter-spacing: 0.3px;
    }

    .label-icon {
        color: var(--primary);
        margin-right: 8px;
        font-size: 1rem;
    }

    .tooltip-icon {
        cursor: help;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .tooltip-icon:hover {
        color: var(--primary);
        transform: scale(1.1);
    }

    .input-wrapper {
        position: relative;
    }

    .form-control-modern {
        border: 2px solid #e9ecef;
        border-radius: var(--border-radius-sm);
        padding: 12px 16px;
        transition: var(--transition);
        font-size: 0.95rem;
        background: white;
    }

    .form-control-modern:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }

    .input-focus-effect {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--primary), #667eea);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .form-control-modern:focus+.input-focus-effect {
        transform: scaleX(1);
    }

    .form-text-modern {
        margin-top: 6px;
        font-size: 0.85rem;
        color: var(--secondary);
        display: flex;
        align-items: center;
    }

    .status-select-wrapper {
        position: relative;
    }

    .form-select-modern {
        border: 2px solid #e9ecef;
        border-radius: var(--border-radius-sm);
        padding: 12px 40px 12px 16px;
        transition: var(--transition);
        font-weight: 500;
        background: white;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px 12px;
    }

    .form-select-modern:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }

    .status-indicator {
        position: absolute;
        top: 50%;
        right: 38px;
        transform: translateY(-50%);
        width: 10px;
        height: 10px;
        border-radius: 50%;
        transition: var(--transition);
        pointer-events: none;
    }

    .status-select-wrapper.ring-active .status-indicator {
        background: var(--success);
        box-shadow: 0 0 8px rgba(32, 201, 151, 0.6);
    }

    .status-select-wrapper.ring-pending .status-indicator {
        background: var(--warning);
        box-shadow: 0 0 8px rgba(255, 193, 7, 0.6);
    }

    .status-select-wrapper.ring-inactive .status-indicator {
        background: var(--secondary);
        box-shadow: 0 0 8px rgba(108, 117, 125, 0.6);
    }

    .info-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid var(--primary);
        border-radius: var(--border-radius-sm);
        padding: 12px 16px;
        font-size: 0.9rem;
        color: var(--secondary);
        animation: fadeInUp 0.5s ease-out;
    }

    .btn-modern {
        border-radius: var(--border-radius-sm);
        padding: 14px 24px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn-modern::before {
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

    .btn-modern:not(:disabled):hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-modern:not(:disabled):hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
    }

    .btn-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-content,
    .btn-loading {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-bottom: 2px solid #e9ecef;
        padding: 20px 24px;
    }

    .header-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--primary), #667eea);
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .btn-toggle {
        width: 36px;
        height: 36px;
        padding: 0;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .btn-toggle:hover {
        background: var(--light);
        border-color: var(--primary);
    }

    .toggle-icon {
        transition: transform 0.3s ease;
    }

    .btn-toggle[aria-expanded="true"] .toggle-icon {
        transform: rotate(180deg);
    }

    .table-modern {
        font-size: 0.9rem;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }

    .table-modern th {
        border-bottom: 2px solid #e9ecef;
        padding: 16px 20px;
        font-weight: 600;
        color: var(--dark);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }

    .th-content {
        display: flex;
        align-items: center;
    }

    .table-modern tbody tr {
        transition: var(--transition);
    }

    .table-modern tbody tr:hover {
        background: #f8f9fa;
    }

    .pet-row td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }

    .pet-name {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pet-indicator {
        font-size: 8px;
        color: var(--success);
        animation: pulse 2s ease-in-out infinite;
    }

    .species-badge {
        background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 500;
        color: var(--dark);
        font-size: 0.85rem;
    }

    .date-badge {
        background: white;
        border: 1px solid #e9ecef;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--secondary);
        display: inline-flex;
        align-items: center;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        border: 1px solid;
    }

    .status-active {
        background: rgba(32, 201, 151, 0.1);
        color: var(--success);
        border-color: var(--success);
    }

    .status-inactive {
        background: rgba(108, 117, 125, 0.1);
        color: var(--secondary);
        border-color: var(--secondary);
    }

    .btn-group-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .btn-transfer {
        padding: 8px 16px;
        border: 2px solid #c9d1ff;
        border-radius: 10px;
        background: white;
        color: var(--primary);
        font-weight: 500;
        font-size: 0.85rem;
        transition: var(--transition);
    }

    .btn-transfer:hover {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }

    .btn-detach {
        padding: 8px 16px;
        border: 2px solid #ffc9c9;
        border-radius: 10px;
        background: white;
        color: var(--danger);
        font-weight: 500;
        font-size: 0.85rem;
        transition: var(--transition);
    }

    .btn-detach:hover {
        background: var(--danger);
        border-color: var(--danger);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    .empty-state {
        padding: 60px 20px !important;
        text-align: center;
    }

    .empty-content {
        animation: fadeInUp 0.5s ease-out;
    }

    .table-footer {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-top: 2px solid #e9ecef;
        padding: 12px 20px;
        font-size: 0.85rem;
        color: var(--secondary);
        display: flex;
        align-items: center;
    }

    .modal-modern {
        border: none;
        border-radius: var(--border-radius);
        overflow: hidden;
        animation: scaleIn 0.3s ease-out;
    }

    .modal-icon-wrapper {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-bottom: 16px;
    }

    .modal-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        animation: scaleIn 0.5s ease-out;
    }

    .modal-icon-danger {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.2) 100%);
        color: var(--danger);
    }

    .modal-icon-primary {
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.2) 100%);
        color: var(--primary);
    }

    .modal-title-modern {
        font-weight: 700;
        color: var(--dark);
    }

    .form-check-modern {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: var(--border-radius-sm);
        padding: 16px;
        transition: var(--transition);
    }

    .form-check-modern:hover {
        border-color: var(--primary);
        background: white;
    }

    .form-check-input-modern {
        width: 20px;
        height: 20px;
        border: 2px solid #e9ecef;
        border-radius: 6px;
        cursor: pointer;
        transition: var(--transition);
        float: left;
        margin-right: 12px;
    }

    .form-check-input-modern:checked {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .form-check-label-modern {
        display: flex;
        flex-direction: column;
        cursor: pointer;
        margin-left: 32px;
    }

    .check-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .check-description {
        font-size: 0.85rem;
        color: var(--secondary);
    }

    .btn-modal {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-modal:hover {
        transform: translateY(-2px);
    }

    .dropdown-modern {
        border: none;
        border-radius: var(--border-radius-sm);
        box-shadow: var(--shadow-lg);
        padding: 8px;
        animation: scaleIn 0.2s ease-out;
    }

    .dropdown-modern .dropdown-item {
        border-radius: 8px;
        padding: 10px 16px;
        transition: var(--transition);
        display: flex;
        align-items: center;
        font-size: 0.9rem;
    }

    .dropdown-modern .dropdown-item:hover {
        background: #f8f9fa;
        transform: translateX(4px);
    }

    .swal-modern {
        border-radius: var(--border-radius) !important;
        font-family: inherit !important;
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

    @media (max-width: 991px) {
        .avatar-initials-modern {
            width: 60px;
            height: 60px;
        }

        .avatar-text {
            font-size: 1.25rem;
        }

        .quick-actions {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 767px) {
        .card-body {
            padding: 20px !important;
        }

        .card-header-modern {
            padding: 16px 20px;
        }

        .table-modern {
            font-size: 0.85rem;
        }

        .pet-row td {
            padding: 12px 16px;
        }

        .btn-group-actions {
            gap: 6px;
        }

        .btn-transfer span,
        .btn-detach span {
            display: none !important;
        }

        .btn-transfer,
        .btn-detach {
            padding: 8px 12px;
        }
    }

    @media (max-width: 576px) {
        .avatar-initials-modern {
            width: 56px;
            height: 56px;
        }

        .avatar-text {
            font-size: 1.1rem;
        }

        .form-label-modern {
            font-size: 0.85rem;
        }

        .form-control-modern,
        .form-select-modern {
            padding: 10px 14px;
            font-size: 0.9rem;
        }
    }

    /* ===== ESTADÍSTICAS ===== */
    .stat-box {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border: 2px solid #f0f0f0;
        border-radius: var(--border-radius-sm);
        transition: var(--transition);
    }

    .stat-box:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
    }

    .stat-icon-sm {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .stat-details {
        flex-grow: 1;
        min-width: 0;
    }

    .stat-value-sm {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
        color: var(--dark);
    }

    .stat-label-sm {
        font-size: 0.8rem;
        color: var(--secondary);
        font-weight: 500;
        margin-top: 0.25rem;
    }

    /* ===== VISTA MÓVIL - TARJETAS DE MASCOTAS ===== */
    .pet-card-mobile {
        padding: 1.25rem;
        border-bottom: 2px solid #f0f0f0;
        transition: var(--transition);
        background: white;
    }

    .pet-card-mobile:hover {
        background: linear-gradient(90deg, rgba(13, 110, 253, 0.03), transparent);
    }

    .pet-card-mobile:last-child {
        border-bottom: none;
    }

    .pet-info-mobile {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .empty-state-mobile {
        padding: 3rem 1.5rem;
        text-align: center;
    }

    @media (max-width: 767px) {
        .btn-transfer,
        .btn-detach {
            padding: 10px 16px;
            font-size: 0.85rem;
        }
    }
</style>

{{-- ===== JAVASCRIPT COMPLETO ===== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function() {
        // Inicializar tooltips de Bootstrap
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

        // ==========================================
        // MÁSCARA TELÉFONO COSTA RICA (+506 0000 0000)
        // ==========================================
        const phoneInput = document.getElementById('phoneCR');

        function formatPhoneCR(value) {
            value = String(value || '');
            // Extraer solo dígitos, ignorando el prefijo 506 inicial si existe
            const digits = value.replace(/\D+/g, '').replace(/^506/, '').slice(0, 8);
            const part1 = digits.slice(0, 4);
            const part2 = digits.slice(4);
            return '+506 ' + part1 + (part2 ? ' ' + part2 : '');
        }

        function applyPhoneFormat() {
            if ((phoneInput.value || '').trim() === '') {
                phoneInput.value = '+506 ';
            } else {
                phoneInput.value = formatPhoneCR(phoneInput.value);
            }
        }

        phoneInput.addEventListener('focus', () => {
            if (phoneInput.value.trim() === '') phoneInput.value = '+506 ';
        });

        phoneInput.addEventListener('input', applyPhoneFormat);

        phoneInput.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            phoneInput.value = formatPhoneCR(pastedText);
        });

        phoneInput.addEventListener('blur', applyPhoneFormat);

        // Aplicar formato inicial
        applyPhoneFormat();

        // ==========================================
        // INDICADOR DE ESTADO DINÁMICO (ring-active/pending/inactive)
        // ==========================================
        const stateWrapper = document.querySelector('[data-state-wrapper]');
        const stateSelect = document.querySelector('[data-state-select]');

        function updateStateRing() {
            stateWrapper.classList.remove('ring-active', 'ring-pending', 'ring-inactive');

            const selectedValue = stateSelect.value;
            if (selectedValue === 'active') {
                stateWrapper.classList.add('ring-active');
            } else if (selectedValue === 'pending') {
                stateWrapper.classList.add('ring-pending');
            } else {
                stateWrapper.classList.add('ring-inactive');
            }
        }

        // Aplicar estado inicial
        updateStateRing();

        // Actualizar cuando cambie el select
        stateSelect.addEventListener('change', updateStateRing);

        // ==========================================
        // DETECCIÓN DE CAMBIOS EN EL FORMULARIO
        // ==========================================
        const clientForm = document.getElementById('clientForm');
        const saveButton = document.getElementById('saveBtn');
        const dirtyBadge = document.getElementById('dirtyBadge');

        // Capturar estado inicial del formulario después de cargar
        let initialValues = {};

        function captureInitialValues() {
            const formData = new FormData(clientForm);
            initialValues = {};
            for (const [key, value] of formData.entries()) {
                initialValues[key] = (value || '').trim();
            }
        }

        // Capturar valores iniciales al cargar
        window.addEventListener('load', () => {
            setTimeout(captureInitialValues, 100);
        });

        function checkFormChanges() {
            const currentFormData = new FormData(clientForm);
            let hasChanges = false;

            for (const [key, value] of currentFormData.entries()) {
                const currentValue = (value || '').trim();
                const initialValue = initialValues[key] || '';

                if (currentValue !== initialValue) {
                    hasChanges = true;
                    break;
                }
            }

            // Habilitar/deshabilitar botón y mostrar badge
            saveButton.disabled = !hasChanges;

            if (hasChanges) {
                dirtyBadge.classList.remove('d-none');
            } else {
                dirtyBadge.classList.add('d-none');
            }

            return hasChanges;
        }

        // Escuchar cambios en todos los inputs del formulario
        clientForm.addEventListener('input', checkFormChanges, true);
        clientForm.addEventListener('change', checkFormChanges, true);

        // ==========================================
        // ANIMACIÓN AL ENVIAR FORMULARIO CON CONFIRMACIÓN
        // ==========================================
        clientForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevenir envío inmediato

            // Asegurar que el teléfono esté formateado
            applyPhoneFormat();

            // Mostrar confirmación con Sweet Alert
            Swal.fire({
                title: '¿Guardar cambios?',
                text: "Se actualizará la información del cliente",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-check me-2"></i>Sí, guardar',
                cancelButtonText: '<i class="fa-solid fa-xmark me-2"></i>Cancelar',
                customClass: {
                    popup: 'swal-modern',
                    confirmButton: 'btn-swal-confirm',
                    cancelButton: 'btn-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Deshabilitar botón y mostrar spinner
                    saveButton.disabled = true;
                    saveButton.querySelector('.btn-content').classList.add('d-none');
                    saveButton.querySelector('.btn-loading').classList.remove('d-none');

                    // Enviar formulario
                    clientForm.submit();
                }
            });
        });

        // ==========================================
        // MODAL DESENLAZAR MASCOTA CON CONFIRMACIÓN
        // ==========================================
        const detachModal = document.getElementById('detachModal');
        const detachForm = document.getElementById('detachForm');
        const petNameLabel = document.getElementById('petNameLabel');

        detachModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // Botón que abrió el modal
            const petId = button.getAttribute('data-pet-id');
            const petName = button.getAttribute('data-pet-name');

            // Actualizar el nombre de la mascota en el modal
            petNameLabel.textContent = petName;

            // Construir la URL dinámica para el formulario
            const baseUrl = "{{ route('portal.admin.clients.pets.detach', ['user' => $user->id, 'pet' => '__PET__']) }}";
            const finalUrl = baseUrl.replace('__PET__', petId);

            // Actualizar la acción del formulario
            detachForm.setAttribute('action', finalUrl);
        });

        // Confirmar antes de desenlazar
        detachForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const petName = petNameLabel.textContent;
            const resetQr = document.getElementById('resetQr').checked;

            Swal.fire({
                title: '¿Desenlazar mascota?',
                html: `Se desenlazará <strong>${petName}</strong> del cliente.${resetQr ? '<br><br><span class="text-warning">⚠️ El código QR será restablecido</span>' : ''}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-link-slash me-2"></i>Sí, desenlazar',
                cancelButtonText: '<i class="fa-solid fa-xmark me-2"></i>Cancelar',
                customClass: {
                    popup: 'swal-modern',
                    confirmButton: 'btn-swal-confirm',
                    cancelButton: 'btn-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cerrar modal y enviar formulario
                    bootstrap.Modal.getInstance(detachModal).hide();
                    detachForm.submit();
                }
            });
        });

        // Limpiar checkbox al cerrar modal
        detachModal.addEventListener('hidden.bs.modal', function() {
            const resetQrCheckbox = document.getElementById('resetQr');
            if (resetQrCheckbox) {
                resetQrCheckbox.checked = false;
            }
        });

        // ==========================================
        // MODAL TRANSFERIR MASCOTA CON CONFIRMACIÓN
        // ==========================================
        const transferModal = document.getElementById('transferModal');
        const transferForm = document.getElementById('transferForm');
        const transferPetNameLabel = document.getElementById('transferPetNameLabel');
        const transferClientSelect = document.getElementById('transferClientSelect');

        transferModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const petId = button.getAttribute('data-pet-id');
            const petName = button.getAttribute('data-pet-name');

            // Actualizar el nombre de la mascota en el modal
            transferPetNameLabel.textContent = petName;

            // Construir la URL dinámica para el formulario
            const baseUrl = "{{ route('portal.admin.clients.pets.transfer', ['user' => $user->id, 'pet' => '__PET__']) }}";
            const finalUrl = baseUrl.replace('__PET__', petId);

            // Actualizar la acción del formulario
            transferForm.setAttribute('action', finalUrl);

            // Resetear select
            transferClientSelect.value = '';
        });

        // Confirmar antes de transferir
        transferForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const petName = transferPetNameLabel.textContent;
            const selectedClient = transferClientSelect.options[transferClientSelect.selectedIndex];
            const keepQr = document.getElementById('keepQr').checked;

            if (!transferClientSelect.value) {
                Swal.fire({
                    title: 'Cliente requerido',
                    text: 'Debes seleccionar un cliente destino',
                    icon: 'warning',
                    confirmButtonColor: '#0d6efd',
                    confirmButtonText: 'Entendido',
                    customClass: {
                        popup: 'swal-modern',
                        confirmButton: 'btn-swal-confirm'
                    }
                });
                return;
            }

            Swal.fire({
                title: '¿Transferir mascota?',
                html: `Se transferirá <strong>${petName}</strong> a:<br><br><strong>${selectedClient.text}</strong>${!keepQr ? '<br><br><span class="text-warning">⚠️ El código QR será restablecido</span>' : ''}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-right-left me-2"></i>Sí, transferir',
                cancelButtonText: '<i class="fa-solid fa-xmark me-2"></i>Cancelar',
                customClass: {
                    popup: 'swal-modern',
                    confirmButton: 'btn-swal-confirm',
                    cancelButton: 'btn-swal-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cerrar modal y enviar formulario
                    bootstrap.Modal.getInstance(transferModal).hide();
                    transferForm.submit();
                }
            });
        });

        // Limpiar modal al cerrar
        transferModal.addEventListener('hidden.bs.modal', function() {
            transferClientSelect.value = '';
            document.getElementById('keepQr').checked = true;
        });

        // ==========================================
        // ANIMACIONES SUAVES AL HACER SCROLL
        // ==========================================
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observar elementos que deben animarse
        document.querySelectorAll('.form-group-modern').forEach(el => {
            observer.observe(el);
        });

        // ==========================================
        // EFECTO DE FOCUS EN INPUTS
        // ==========================================
        const modernInputs = document.querySelectorAll('.form-control-modern, .form-select-modern');

        modernInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.closest('.input-wrapper, .status-select-wrapper')?.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.closest('.input-wrapper, .status-select-wrapper')?.classList.remove('focused');
            });
        });

    })();

    (function() {
        // --- Eliminar cliente (delegación de eventos) ---
        const deleteForm = document.getElementById('deleteClientForm');

        function confirmAndDelete() {
            const run = (typeof Swal !== 'undefined') ?
                () => Swal.fire({
                    title: '¿Eliminar cliente?',
                    html: 'Esta acción <b>no se puede deshacer</b>.<br>Solo se pueden eliminar clientes <b>sin mascotas vinculadas</b>.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc3545'
                }).then(r => {
                    if (r.isConfirmed) deleteForm.submit();
                }) :
                () => {
                    if (confirm('¿Eliminar cliente?')) deleteForm.submit();
                };

            run();
        }

        // Delegación: captura clicks del item del menú o de cualquier botón equivalente
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.js-delete-client, [data-action="delete-client"], [data-delete-client], #deleteClientBtn');
            if (!trigger) return;

            e.preventDefault();
            e.stopPropagation();

            const deleteForm = document.getElementById('deleteClientForm');
            const run = (typeof Swal !== 'undefined') ?
                () => Swal.fire({
                    title: '¿Eliminar cliente?',
                    html: 'Esta acción <b>no se puede deshacer</b>.<br>Solo se pueden eliminar clientes <b>sin mascotas vinculadas</b>.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc3545'
                }).then(r => {
                    if (r.isConfirmed) deleteForm.submit();
                }) :
                () => {
                    if (confirm('¿Eliminar cliente?')) deleteForm.submit();
                };

            run();
        }, true);



        // (opcional) si algún HTML dejó el botón con disabled por cache, lo quitamos
        document.getElementById('saveBtn')?.removeAttribute('disabled');
    })();
</script>
@endsection