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
                            <td>
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
