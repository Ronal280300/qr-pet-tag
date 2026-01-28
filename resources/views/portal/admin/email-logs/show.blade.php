@extends('layouts.admin')

@section('title', 'Detalle Email Log')
@section('page-title', 'Detalle Email Log')

@section('content')
  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <a href="{{ route('portal.admin.email-logs.index') }}" class="text-decoration-none text-muted d-inline-flex align-items-center mb-3">
        <i class="fas fa-arrow-left me-2"></i>
        <span>Volver</span>
      </a>
      <h1 class="h2 mb-0 fw-bold">Detalle del Email</h1>
    </div>
    @if($log->status === 'sent')
      <span class="badge rounded-pill bg-primary px-4 py-2 fs-6">
        <i class="fas fa-check-circle me-2"></i>Enviado
      </span>
    @else
      <span class="badge rounded-pill bg-white text-primary border border-primary px-4 py-2 fs-6">
        <i class="fas fa-times-circle me-2"></i>Error
      </span>
    @endif
  </div>

  <div class="row g-4">
    {{-- Columna Principal --}}
    <div class="col-lg-8">
      {{-- Card Principal --}}
      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-4">Información del Correo</h5>
          
          <div class="row g-4">
            <div class="col-12">
              <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                  <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" 
                       style="width: 48px; height: 48px;">
                    <i class="fas fa-calendar text-primary"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <p class="text-muted small mb-1">Fecha de Envío</p>
                  <p class="mb-0 fw-semibold">{{ $log->sent_at?->format('d/m/Y H:i:s') ?? $log->created_at->format('d/m/Y H:i:s') }}</p>
                  <small class="text-muted">{{ $log->sent_at?->diffForHumans() ?? $log->created_at->diffForHumans() }}</small>
                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                  <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" 
                       style="width: 48px; height: 48px;">
                    <i class="fas fa-envelope text-primary"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <p class="text-muted small mb-1">Destinatario</p>
                  <p class="mb-0 fw-semibold">{{ $log->recipient }}</p>
                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                  <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" 
                       style="width: 48px; height: 48px;">
                    <i class="fas fa-heading text-primary"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <p class="text-muted small mb-1">Asunto</p>
                  <p class="mb-0 fw-semibold">{{ $log->subject }}</p>
                </div>
              </div>
            </div>

            <div class="col-12">
              <div class="d-flex align-items-start">
                <div class="flex-shrink-0">
                  <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" 
                       style="width: 48px; height: 48px;">
                    <i class="fas fa-tag text-primary"></i>
                  </div>
                </div>
                <div class="flex-grow-1 ms-3">
                  <p class="text-muted small mb-1">Tipo</p>
                  <span class="badge bg-primary rounded-pill px-3">{{ ucfirst($log->type ?? 'general') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Card de Error --}}
      @if($log->error_message)
        <div class="card border-0 shadow-sm rounded-4 mb-4">
          <div class="card-body p-4">
            <h5 class="fw-bold mb-3 text-primary">
              <i class="fas fa-exclamation-circle me-2"></i>Mensaje de Error
            </h5>
            <div class="bg-light rounded-3 p-3">
              <code class="text-dark d-block">{{ $log->error_message }}</code>
            </div>
          </div>
        </div>
      @endif
    </div>

    {{-- Columna Sidebar --}}
    <div class="col-lg-4">
      {{-- Orden Relacionada --}}
      @if($log->order)
        <div class="card border-0 shadow-sm rounded-4 mb-4">
          <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
              <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" 
                   style="width: 40px; height: 40px;">
                <i class="fas fa-shopping-cart text-white"></i>
              </div>
              <h6 class="mb-0 fw-bold">Orden Relacionada</h6>
            </div>
            
            <div class="mb-3">
              <p class="text-muted small mb-1">Número</p>
              <p class="h5 mb-0 text-primary fw-bold">#{{ $log->order->order_number }}</p>
            </div>
            
            <div class="mb-3">
              <p class="text-muted small mb-1">Plan</p>
              <p class="mb-0 fw-semibold">{{ $log->order->plan->name }}</p>
            </div>
            
            <div class="mb-3">
              <p class="text-muted small mb-1">Total</p>
              <p class="h5 mb-0 fw-bold">₡{{ number_format($log->order->total, 2) }}</p>
            </div>
            
            <div class="mb-4">
              <p class="text-muted small mb-1">Estado</p>
              <span class="badge bg-{{ $log->order->status === 'completed' ? 'primary' : 'white border border-primary text-primary' }} rounded-pill px-3">
                {{ ucfirst($log->order->status) }}
              </span>
            </div>
            
            <a href="{{ route('portal.admin.orders.show', $log->order) }}" 
               class="btn btn-primary w-100 rounded-pill">
              Ver Orden
            </a>
          </div>
        </div>
      @endif

      {{-- Usuario Relacionado --}}
      @if($log->user)
        <div class="card border-0 shadow-sm rounded-4 mb-4">
          <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
              <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" 
                   style="width: 40px; height: 40px;">
                <i class="fas fa-user text-white"></i>
              </div>
              <h6 class="mb-0 fw-bold">Usuario</h6>
            </div>
            
            <div class="text-center mb-4">
              <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" 
                   style="width: 80px; height: 80px;">
                <i class="fas fa-user text-primary fs-3"></i>
              </div>
              <h6 class="mb-0 fw-bold">{{ $log->user->name }}</h6>
            </div>
            
            <div class="mb-3">
              <p class="text-muted small mb-1">Email</p>
              <p class="mb-0 small">{{ $log->user->email }}</p>
            </div>
            
            <div class="mb-4">
              <p class="text-muted small mb-1">Teléfono</p>
              <p class="mb-0">{{ $log->user->phone ?? 'N/A' }}</p>
            </div>
            
            <a href="{{ route('portal.admin.clients.show', $log->user) }}" 
               class="btn btn-outline-primary w-100 rounded-pill">
              Ver Perfil
            </a>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>

@push('styles')
<style>
  body {
    background-color: #f8f9fa;
  }
  
  .card {
    transition: all 0.3s ease;
  }
  
  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08) !important;
  }
  
  .btn {
    transition: all 0.3s ease;
    font-weight: 600;
  }
  
  .btn-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
  }
  
  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(13, 110, 253, 0.3);
  }
  
  .btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(13, 110, 253, 0.2);
  }
  
  .rounded-pill {
    border-radius: 50rem !important;
  }
  
  .rounded-4 {
    border-radius: 1rem !important;
  }
  
  code {
    background: none;
    padding: 0;
    font-size: 0.875rem;
  }
  
  .badge {
    font-weight: 600;
    letter-spacing: 0.5px;
  }
  
  h1, h2, h3, h4, h5, h6 {
    color: #1e293b;
  }
  
  .text-muted {
    color: #64748b !important;
  }
  
  .bg-light {
    background-color: #f1f5f9 !important;
  }
</style>
@endpush
@endsection
