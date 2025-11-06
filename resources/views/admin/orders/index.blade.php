@extends('layouts.app')

@section('title', 'Gestión de Pedidos - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestión de Pedidos</h1>

        <!-- Botón para mostrar/ocultar filtros -->
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
            <i class="fa-solid fa-filter"></i> Filtros
        </button>
    </div>

    <!-- Filtros expandibles -->
    <div class="collapse {{ request()->hasAny(['status', 'search', 'date_from', 'date_to']) ? 'show' : '' }} mb-4" id="filterCollapse">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Estado</label>
                        <select name="status" class="form-select">
                            <option value="">Todos los estados</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                            <option value="payment_uploaded" {{ request('status') == 'payment_uploaded' ? 'selected' : '' }}>Pago Subido</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verificado</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="search" class="form-control" placeholder="Pedido, cliente o email..." value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Desde</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Hasta</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fa-solid fa-search"></i> Buscar
                        </button>
                        @if(request()->hasAny(['status', 'search', 'date_from', 'date_to']))
                        <a href="{{ route('portal.admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total</h6>
                    <h3>{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Pendientes</h6>
                    <h3 class="text-warning">{{ $stats['pending'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Con Pago</h6>
                    <h3 class="text-info">{{ $stats['payment_uploaded'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Verificados</h6>
                    <h3 class="text-success">{{ $stats['verified'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de pedidos -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Plan</th>
                            <th>Mascotas</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>@extends('layouts.app')

@section('title', 'Gestión de Pedidos - Admin')

@section('content')
<div class="admin-orders-modern">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="page-header-modern">
            <div class="header-content">
                <div class="header-title">
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div>
                        <h1>Gestión de Pedidos</h1>
                        <p>Administra y monitorea todos los pedidos del sistema</p>
                    </div>
                </div>
                <button type="button" class="btn-filter-modern" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filtros</span>
                </button>
            </div>
        </div>

        <!-- Filtros expandibles -->
        <div class="collapse {{ request()->hasAny(['status', 'search', 'date_from', 'date_to']) ? 'show' : '' }} mb-4" id="filterCollapse">
            <div class="filter-card-modern">
                <form method="GET" class="filter-form">
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-circle-dot"></i>
                                Estado
                            </label>
                            <select name="status" class="form-control-modern">
                                <option value="">Todos los estados</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="payment_uploaded" {{ request('status') == 'payment_uploaded' ? 'selected' : '' }}>Pago Subido</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verificado</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                            </select>
                        </div>

                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Buscar
                            </label>
                            <input type="text" name="search" class="form-control-modern" placeholder="Pedido, cliente o email..." value="{{ request('search') }}">
                        </div>

                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-calendar-days"></i>
                                Desde
                            </label>
                            <input type="date" name="date_from" class="form-control-modern" value="{{ request('date_from') }}">
                        </div>

                        <div class="filter-item">
                            <label class="filter-label">
                                <i class="fa-solid fa-calendar-days"></i>
                                Hasta
                            </label>
                            <input type="date" name="date_to" class="form-control-modern" value="{{ request('date_to') }}">
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-action-modern btn-primary-modern">
                            <i class="fa-solid fa-search"></i>
                            Buscar
                        </button>
                        @if(request()->hasAny(['status', 'search', 'date_from', 'date_to']))
                        <a href="{{ route('portal.admin.orders.index') }}" class="btn-action-modern btn-secondary-modern">
                            <i class="fa-solid fa-times"></i>
                            Limpiar
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid-modern">
            <div class="stat-card-modern stat-total">
                <div class="stat-icon">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Total Pedidos</p>
                    <h3 class="stat-value">{{ $stats['total'] }}</h3>
                </div>
                <div class="stat-badge">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>

            <div class="stat-card-modern stat-pending">
                <div class="stat-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Pendientes</p>
                    <h3 class="stat-value">{{ $stats['pending'] }}</h3>
                </div>
                <div class="stat-badge warning">
                    <i class="fa-solid fa-exclamation"></i>
                </div>
            </div>

            <div class="stat-card-modern stat-payment">
                <div class="stat-icon">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Con Pago</p>
                    <h3 class="stat-value">{{ $stats['payment_uploaded'] }}</h3>
                </div>
                <div class="stat-badge info">
                    <i class="fa-solid fa-arrow-up"></i>
                </div>
            </div>

            <div class="stat-card-modern stat-verified">
                <div class="stat-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Verificados</p>
                    <h3 class="stat-value">{{ $stats['verified'] }}</h3>
                </div>
                <div class="stat-badge success">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>
        </div>

        <!-- Tabla de pedidos -->
        <div class="table-card-modern">
            <div class="table-header">
                <h5 class="table-title">
                    <i class="fa-solid fa-list"></i>
                    Lista de Pedidos
                </h5>
                <div class="table-info">
                    <i class="fa-solid fa-circle-info"></i>
                    Mostrando {{ $orders->count() }} de {{ $orders->total() }} pedidos
                </div>
            </div>

            <div class="table-responsive-modern">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>
                                <div class="th-content">
                                    <i class="fa-solid fa-hashtag"></i>
                                    Pedido
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <i class="fa-solid fa-user"></i>
                                    Cliente
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <i class="fa-solid fa-layer-group"></i>
                                    Plan
                                </div>
                            </th>
                            <th class="text-center">
                                <div class="th-content">
                                    <i class="fa-solid fa-paw"></i>
                                    Mascotas
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <i class="fa-solid fa-coins"></i>
                                    Total
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <i class="fa-solid fa-circle-dot"></i>
                                    Estado
                                </div>
                            </th>
                            <th>
                                <div class="th-content">
                                    <i class="fa-solid fa-calendar"></i>
                                    Fecha
                                </div>
                            </th>
                            <th class="text-center">
                                <div class="th-content">
                                    <i class="fa-solid fa-bolt"></i>
                                    Acciones
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr class="table-row-modern">
                            <td>
                                <div class="order-number">
                                    <i class="fa-solid fa-receipt"></i>
                                    <strong>{{ $order->order_number }}</strong>
                                </div>
                            </td>
                            <td>
                                <div class="customer-info">
                                    <div class="customer-avatar">
                                        {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="customer-name">{{ $order->user->name }}</div>
                                        <div class="customer-email">{{ $order->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="plan-info">
                                    <i class="fa-solid fa-tag"></i>
                                    {{ $order->plan->name }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="pets-badge">{{ $order->pets_quantity }}</span>
                            </td>
                            <td>
                                <div class="price-info">
                                    <span class="currency">₡</span>
                                    <strong>{{ number_format($order->total, 0, ',', '.') }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge-modern {{ $order->status_badge_class }}">
                                    @php
                                        $statusIcons = [
                                            'pending' => 'fa-clock',
                                            'payment_uploaded' => 'fa-file-invoice',
                                            'verified' => 'fa-check-circle',
                                            'completed' => 'fa-circle-check',
                                            'rejected' => 'fa-times-circle'
                                        ];
                                        $icon = $statusIcons[$order->status] ?? 'fa-circle';
                                    @endphp
                                    <i class="fa-solid {{ $icon }}"></i>
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="date-info">
                                    <i class="fa-solid fa-calendar-day"></i>
                                    <span>{{ $order->created_at->format('d/m/Y') }}</span>
                                    <small>{{ $order->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('portal.admin.orders.show', $order) }}" class="btn-view-modern" title="Ver detalle">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Ver</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state-modern">
                                    <div class="empty-icon">
                                        <i class="fa-solid fa-inbox"></i>
                                    </div>
                                    <h4>No hay pedidos</h4>
                                    <p>No se encontraron pedidos con los filtros aplicados</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($orders->hasPages())
            <div class="pagination-modern">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
/* ============================================
   MODERN ADMIN ORDERS PAGE
   ============================================ */

:root {
    --admin-primary: #4F89E8;
    --admin-primary-dark: #1E7CF2;
    --admin-success: #10B981;
    --admin-warning: #F59E0B;
    --admin-danger: #EF4444;
    --admin-info: #3B82F6;
    --admin-text: #1F2937;
    --admin-text-light: #6B7280;
    --admin-border: #E5E7EB;
    --admin-bg: #F9FAFB;
    --admin-white: #FFFFFF;
}

.admin-orders-modern {
    background: var(--admin-bg);
    min-height: 100vh;
}

/* Page Header */
.page-header-modern {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 16px;
}

.icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    box-shadow: 0 8px 16px rgba(79, 137, 232, 0.3);
}

.header-title h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--admin-text);
    margin: 0;
    line-height: 1.2;
}

.header-title p {
    font-size: 14px;
    color: var(--admin-text-light);
    margin: 4px 0 0 0;
}

.btn-filter-modern {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: white;
    border: 2px solid var(--admin-border);
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    color: var(--admin-text);
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-filter-modern:hover {
    border-color: var(--admin-primary);
    color: var(--admin-primary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.2);
}

/* Filter Card */
.filter-card-modern {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filter-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

.filter-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--admin-text);
}

.filter-label i {
    color: var(--admin-primary);
    font-size: 14px;
}

.form-control-modern {
    padding: 10px 14px;
    border: 1px solid var(--admin-border);
    border-radius: 10px;
    font-size: 14px;
    color: var(--admin-text);
    transition: all 0.3s ease;
}

.form-control-modern:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px rgba(79, 137, 232, 0.1);
}

.filter-actions {
    display: flex;
    gap: 12px;
    padding-top: 8px;
}

.btn-action-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-primary-modern {
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
    color: white;
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.3);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 137, 232, 0.4);
    color: white;
}

.btn-secondary-modern {
    background: var(--admin-bg);
    color: var(--admin-text);
    border: 1px solid var(--admin-border);
}

.btn-secondary-modern:hover {
    background: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* Stats Grid */
.stats-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card-modern {
    background: white;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, var(--admin-primary), var(--admin-primary-dark));
}

.stat-card-modern:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.stat-pending::before {
    background: linear-gradient(180deg, var(--admin-warning), #F97316);
}

.stat-payment::before {
    background: linear-gradient(180deg, var(--admin-info), #2563EB);
}

.stat-verified::before {
    background: linear-gradient(180deg, var(--admin-success), #059669);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: var(--admin-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--admin-primary);
}

.stat-pending .stat-icon {
    color: var(--admin-warning);
}

.stat-payment .stat-icon {
    color: var(--admin-info);
}

.stat-verified .stat-icon {
    color: var(--admin-success);
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--admin-text-light);
    margin: 0 0 4px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 32px;
    font-weight: 800;
    color: var(--admin-text);
    margin: 0;
    line-height: 1;
}

.stat-badge {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(79, 137, 232, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--admin-primary);
    font-size: 16px;
}

.stat-badge.warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--admin-warning);
}

.stat-badge.info {
    background: rgba(59, 130, 246, 0.1);
    color: var(--admin-info);
}

.stat-badge.success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--admin-success);
}

/* Table Card */
.table-card-modern {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--admin-border);
}

.table-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 700;
    color: var(--admin-text);
    margin: 0;
}

.table-title i {
    color: var(--admin-primary);
}

.table-info {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--admin-text-light);
    padding: 6px 14px;
    background: var(--admin-bg);
    border-radius: 20px;
}

.table-info i {
    color: var(--admin-primary);
}

/* Modern Table */
.table-responsive-modern {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding: 0 8px;
    margin: 0 -8px;
}

/* Custom scrollbar */
.table-responsive-modern::-webkit-scrollbar {
    height: 8px;
}

.table-responsive-modern::-webkit-scrollbar-track {
    background: var(--admin-bg);
    border-radius: 10px;
    margin: 0 8px;
}

.table-responsive-modern::-webkit-scrollbar-thumb {
    background: var(--admin-primary);
    border-radius: 10px;
}

.table-responsive-modern::-webkit-scrollbar-thumb:hover {
    background: var(--admin-primary-dark);
}

.table-modern {
    width: 100%;
    min-width: 1250px;
    border-collapse: separate;
    border-spacing: 0;
    table-layout: auto;
}

.table-modern thead {
    background: var(--admin-bg);
}

.table-modern thead th {
    padding: 16px 20px;
    font-size: 12px;
    font-weight: 700;
    color: var(--admin-text);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--admin-border);
    white-space: nowrap;
}

/* Column widths */
.table-modern thead th:nth-child(1) { min-width: 150px; } /* Pedido */
.table-modern thead th:nth-child(2) { min-width: 200px; } /* Cliente */
.table-modern thead th:nth-child(3) { min-width: 160px; } /* Plan */
.table-modern thead th:nth-child(4) { min-width: 100px; } /* Mascotas */
.table-modern thead th:nth-child(5) { min-width: 120px; } /* Total */
.table-modern thead th:nth-child(6) { min-width: 140px; } /* Estado */
.table-modern thead th:nth-child(7) { min-width: 140px; } /* Fecha */
.table-modern thead th:nth-child(8) { 
    min-width: 140px; 
    width: 140px;
    text-align: center;
} /* Acciones */

.table-modern thead th.text-center {
    text-align: center;
}

.table-modern thead th.text-center .th-content {
    justify-content: center;
}

.th-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.text-center .th-content {
    justify-content: center;
}

.th-content i {
    color: var(--admin-primary);
    font-size: 14px;
}

.table-row-modern {
    transition: all 0.2s ease;
}

.table-row-modern:hover {
    background: rgba(79, 137, 232, 0.02);
}

.table-row-modern td {
    padding: 16px 20px;
    font-size: 14px;
    color: var(--admin-text);
    border-bottom: 1px solid var(--admin-border);
    vertical-align: middle;
}

/* Ensure actions column doesn't shrink */
.table-row-modern td:last-child {
    min-width: 140px;
    width: 140px;
    white-space: nowrap;
    text-align: center;
}

.table-row-modern td:nth-child(4) {
    text-align: center;
}

/* Table Cell Styles */
.order-number {
    display: flex;
    align-items: center;
    gap: 8px;
    font-family: 'Courier New', monospace;
}

.order-number i {
    color: var(--admin-primary);
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
}

.customer-name {
    font-weight: 600;
    color: var(--admin-text);
}

.customer-email {
    font-size: 12px;
    color: var(--admin-text-light);
    margin-top: 2px;
}

.plan-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.plan-info i {
    color: var(--admin-primary);
}

.pets-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    background: var(--admin-bg);
    border: 1px solid var(--admin-border);
    border-radius: 10px;
    font-weight: 700;
    color: var(--admin-text);
}

.price-info {
    display: flex;
    align-items: baseline;
    gap: 4px;
    font-size: 16px;
}

.price-info .currency {
    color: var(--admin-text-light);
    font-size: 14px;
}

/* Status Badges */
.badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}

.badge-modern.bg-warning {
    background: rgba(245, 158, 11, 0.1);
    color: var(--admin-warning);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.badge-modern.bg-info {
    background: rgba(59, 130, 246, 0.1);
    color: var(--admin-info);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.badge-modern.bg-success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--admin-success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.badge-modern.bg-danger {
    background: rgba(239, 68, 68, 0.1);
    color: var(--admin-danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.badge-modern.bg-secondary {
    background: rgba(107, 114, 128, 0.1);
    color: var(--admin-text-light);
    border: 1px solid rgba(107, 114, 128, 0.2);
}

.date-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.date-info i {
    color: var(--admin-primary);
}

.date-info small {
    color: var(--admin-text-light);
    font-size: 12px;
    margin-left: 4px;
}

.btn-view-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
    color: white;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-view-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.3);
    color: white;
}

/* Empty State */
.empty-state-modern {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: var(--admin-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: var(--admin-text-light);
}

.empty-state-modern h4 {
    font-size: 20px;
    font-weight: 700;
    color: var(--admin-text);
    margin: 0 0 8px 0;
}

.empty-state-modern p {
    font-size: 14px;
    color: var(--admin-text-light);
    margin: 0;
}

/* Pagination */
.pagination-modern {
    padding: 20px 24px;
    border-top: 1px solid var(--admin-border);
}

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid-modern {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }

    .header-title {
        flex-direction: column;
        text-align: center;
    }

    .header-title h1 {
        font-size: 24px;
    }

    .btn-filter-modern {
        justify-content: center;
    }

    .filter-grid {
        grid-template-columns: 1fr;
    }

    .stats-grid-modern {
        grid-template-columns: 1fr;
    }

    .table-responsive-modern {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        position: relative;
    }

    /* Scroll hint shadow */
    .table-responsive-modern::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 30px;
        background: linear-gradient(to left, rgba(255,255,255,0.9), transparent);
        pointer-events: none;
    }

    .table-modern {
        min-width: 1250px;
    }

    .table-row-modern td {
        font-size: 13px;
        padding: 14px 16px;
    }

    .table-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }

    .filter-actions {
        flex-direction: column;
    }

    .btn-action-modern {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .page-header-modern {
        padding: 16px;
    }

    .icon-wrapper {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }

    .stat-card-modern {
        padding: 16px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }

    .stat-value {
        font-size: 28px;
    }
}
</style>
@endsection
                                {{ $order->user->name }}<br>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </td>
                            <td>{{ $order->plan->name }}</td>
                            <td class="text-center">{{ $order->pets_quantity }}</td>
                            <td><strong>₡{{ number_format($order->total, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge {{ $order->status_badge_class }}">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('portal.admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No hay pedidos para mostrar
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
