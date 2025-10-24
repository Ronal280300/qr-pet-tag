@extends('layouts.app')

@section('title', 'Gestión de Pedidos - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestión de Pedidos</h1>

        <!-- Filtros -->
        <form method="GET" class="d-flex gap-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="payment_uploaded" {{ request('status') == 'payment_uploaded' ? 'selected' : '' }}>Pago Subido</option>
                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verificado</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
            </select>

            <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
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
