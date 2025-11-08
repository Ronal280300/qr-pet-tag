@extends('layouts.app')

@section('title', 'Detalle Email Log')

@section('content')
<div class="container py-4">
  <div class="row mb-4">
    <div class="col">
      <a href="{{ route('portal.admin.email-logs.index') }}" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Logs
      </a>
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-envelope-open me-2"></i>Detalle del Email
      </h1>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-8">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Información del Correo</h6>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th width="30%">Fecha de Envío</th>
                <td>{{ $log->sent_at?->format('d/m/Y H:i:s') ?? $log->created_at->format('d/m/Y H:i:s') }}</td>
              </tr>
              <tr>
                <th>Destinatario</th>
                <td>{{ $log->recipient }}</td>
              </tr>
              <tr>
                <th>Asunto</th>
                <td>{{ $log->subject }}</td>
              </tr>
              <tr>
                <th>Tipo</th>
                <td>
                  <span class="badge badge-secondary">{{ $log->type ?? 'general' }}</span>
                </td>
              </tr>
              <tr>
                <th>Estado</th>
                <td>
                  @if($log->status === 'sent')
                    <span class="badge badge-success badge-lg">
                      <i class="fas fa-check-circle"></i> Enviado Exitosamente
                    </span>
                  @else
                    <span class="badge badge-danger badge-lg">
                      <i class="fas fa-exclamation-triangle"></i> Error al Enviar
                    </span>
                  @endif
                </td>
              </tr>
              @if($log->error_message)
                <tr>
                  <th>Mensaje de Error</th>
                  <td>
                    <div class="alert alert-danger mb-0">
                      <i class="fas fa-bug me-2"></i>
                      <code>{{ $log->error_message }}</code>
                    </div>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      {{-- Orden Relacionada --}}
      @if($log->order)
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orden Relacionada</h6>
          </div>
          <div class="card-body">
            <p><strong>Número:</strong> #{{ $log->order->order_number }}</p>
            <p><strong>Plan:</strong> {{ $log->order->plan->name }}</p>
            <p><strong>Total:</strong> ₡{{ number_format($log->order->total, 2) }}</p>
            <p><strong>Estado:</strong>
              <span class="badge badge-{{ $log->order->status === 'completed' ? 'success' : 'warning' }}">
                {{ ucfirst($log->order->status) }}
              </span>
            </p>
            <a href="{{ route('portal.admin.orders.show', $log->order) }}" class="btn btn-primary btn-block">
              <i class="fas fa-external-link-alt"></i> Ver Orden
            </a>
          </div>
        </div>
      @endif

      {{-- Usuario Relacionado --}}
      @if($log->user)
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Usuario Relacionado</h6>
          </div>
          <div class="card-body">
            <p><strong>Nombre:</strong> {{ $log->user->name }}</p>
            <p><strong>Email:</strong> {{ $log->user->email }}</p>
            <p><strong>Teléfono:</strong> {{ $log->user->phone ?? 'N/A' }}</p>
            <a href="{{ route('portal.admin.clients.show', $log->user) }}" class="btn btn-info btn-block">
              <i class="fas fa-user"></i> Ver Cliente
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
