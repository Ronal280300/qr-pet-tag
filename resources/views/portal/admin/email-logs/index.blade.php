@extends('layouts.app')

@section('title', 'Logs de Correos')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-envelope me-2"></i>Logs de Correos Electrónicos
      </h1>
    </div>
  </div>

  {{-- Estadísticas --}}
  <div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-envelope fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Enviados</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['sent'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-check-circle fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-2 col-sm-6 mb-3">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Fallidos</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['failed'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Hoy</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Este Mes</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['month'] }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Filtros --}}
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('portal.admin.email-logs.index') }}">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="status">Estado</label>
              <select name="status" id="status" class="form-control">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos</option>
                <option value="sent" {{ $status === 'sent' ? 'selected' : '' }}>Enviados</option>
                <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Fallidos</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="search">Buscar</label>
              <input type="text" name="search" id="search" class="form-control"
                     placeholder="Email, asunto, tipo..." value="{{ $search }}">
            </div>
          </div>
          <div class="col-md-2">
            <label class="d-block">&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fas fa-search"></i> Filtrar
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Tabla --}}
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Registros ({{ $logs->total() }})</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Destinatario</th>
              <th>Asunto</th>
              <th>Tipo</th>
              <th>Estado</th>
              <th>Orden</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($logs as $log)
              <tr>
                <td>
                  <small class="text-muted">{{ $log->sent_at?->format('d/m/Y H:i') ?? $log->created_at->format('d/m/Y H:i') }}</small>
                </td>
                <td>{{ $log->recipient }}</td>
                <td>
                  <span class="d-inline-block text-truncate" style="max-width: 300px;" title="{{ $log->subject }}">
                    {{ $log->subject }}
                  </span>
                </td>
                <td>
                  <span class="badge badge-secondary">{{ $log->type ?? 'general' }}</span>
                </td>
                <td>
                  @if($log->status === 'sent')
                    <span class="badge badge-success">
                      <i class="fas fa-check"></i> Enviado
                    </span>
                  @else
                    <span class="badge badge-danger">
                      <i class="fas fa-times"></i> Fallido
                    </span>
                  @endif
                </td>
                <td>
                  @if($log->order)
                    <a href="{{ route('portal.admin.orders.show', $log->order) }}" class="btn btn-sm btn-outline-primary">
                      #{{ $log->order->order_number }}
                    </a>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('portal.admin.email-logs.show', $log) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  <i class="fas fa-inbox fa-3x mb-3"></i>
                  <p>No se encontraron registros</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      {{ $logs->appends(request()->query())->links() }}
    </div>
  </div>
</div>
@endsection
