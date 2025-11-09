@extends('layouts.app')

@section('title', 'Detalles de Campaña')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-paper-plane me-2"></i>{{ $emailCampaign->name }}
      </h1>
      <p class="text-muted mb-0">Creada por {{ $emailCampaign->creator->name }} el {{ $emailCampaign->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="col-auto">
      @if($emailCampaign->status === 'draft')
        <a href="{{ route('portal.admin.email-campaigns.confirm', $emailCampaign) }}" class="btn btn-success">
          <i class="fas fa-paper-plane me-1"></i>Enviar Campaña
        </a>
      @endif
      <a href="{{ route('portal.admin.email-campaigns.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Información General --}}
  <div class="row mb-4">
    <div class="col-md-8">
      <div class="card shadow">
        <div class="card-header">
          <h5 class="mb-0">Información de la Campaña</h5>
        </div>
        <div class="card-body">
          <table class="table table-borderless">
            <tr>
              <th style="width: 200px;">Nombre:</th>
              <td>{{ $emailCampaign->name }}</td>
            </tr>
            <tr>
              <th>Plantilla:</th>
              <td>
                {{ $emailCampaign->template->name }}
                <a href="{{ route('portal.admin.email-templates.preview', $emailCampaign->template) }}"
                   target="_blank"
                   class="btn btn-sm btn-info ms-2">
                  <i class="fas fa-eye"></i> Ver Plantilla
                </a>
              </td>
            </tr>
            <tr>
              <th>Asunto:</th>
              <td>{{ $emailCampaign->template->subject }}</td>
            </tr>
            <tr>
              <th>Estado:</th>
              <td>
                @php
                  $badges = [
                    'draft' => 'secondary',
                    'sending' => 'warning',
                    'sent' => 'success',
                    'failed' => 'danger',
                  ];
                  $labels = [
                    'draft' => 'Borrador',
                    'sending' => 'Enviando',
                    'sent' => 'Enviada',
                    'failed' => 'Fallida',
                  ];
                @endphp
                <span class="badge bg-{{ $badges[$emailCampaign->status] ?? 'secondary' }} fs-6">
                  {{ $labels[$emailCampaign->status] ?? ucfirst($emailCampaign->status) }}
                </span>
              </td>
            </tr>
            <tr>
              <th>Tipo de Filtro:</th>
              <td>
                @switch($emailCampaign->filter_type)
                  @case('all')
                    <span class="badge bg-primary">Todos los Clientes</span>
                    @break
                  @case('no_scans')
                    <span class="badge bg-warning">Sin Escaneos ({{ $emailCampaign->no_scans_days }} días)</span>
                    @break
                  @case('payment_due')
                    <span class="badge bg-info">Pago por Vencer ({{ $emailCampaign->payment_due_days }} días)</span>
                    @break
                  @case('custom')
                    <span class="badge bg-secondary">Filtro Personalizado</span>
                    @break
                @endswitch
              </td>
            </tr>
            @if($emailCampaign->started_at)
              <tr>
                <th>Iniciada:</th>
                <td>{{ $emailCampaign->started_at->format('d/m/Y H:i:s') }}</td>
              </tr>
            @endif
            @if($emailCampaign->completed_at)
              <tr>
                <th>Completada:</th>
                <td>{{ $emailCampaign->completed_at->format('d/m/Y H:i:s') }}</td>
              </tr>
              <tr>
                <th>Duración:</th>
                <td>{{ $emailCampaign->started_at->diffForHumans($emailCampaign->completed_at, true) }}</td>
              </tr>
            @endif
          </table>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      {{-- Estadísticas --}}
      <div class="card shadow mb-3">
        <div class="card-header bg-primary text-white">
          <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h6>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
              <span class="text-muted">Total Destinatarios:</span>
              <strong>{{ $emailCampaign->total_recipients ?? 0 }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="text-success">Enviados:</span>
              <strong class="text-success">{{ $emailCampaign->sent_count }}</strong>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="text-danger">Fallidos:</span>
              <strong class="text-danger">{{ $emailCampaign->failed_count }}</strong>
            </div>
          </div>

          @if($emailCampaign->total_recipients > 0)
            <div class="progress mb-2" style="height: 25px;">
              @php
                $successRate = ($emailCampaign->sent_count / $emailCampaign->total_recipients) * 100;
                $failedRate = ($emailCampaign->failed_count / $emailCampaign->total_recipients) * 100;
              @endphp
              <div class="progress-bar bg-success" style="width: {{ $successRate }}%">
                {{ round($successRate) }}%
              </div>
              @if($failedRate > 0)
                <div class="progress-bar bg-danger" style="width: {{ $failedRate }}%">
                  {{ round($failedRate) }}%
                </div>
              @endif
            </div>
            <small class="text-muted">Tasa de éxito: {{ round($successRate, 1) }}%</small>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Lista de Destinatarios --}}
  @if($emailCampaign->recipients->isNotEmpty())
    <div class="card shadow">
      <div class="card-header">
        <h5 class="mb-0">Destinatarios ({{ $emailCampaign->recipients->count() }})</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Fecha de Envío</th>
                <th>Error</th>
              </tr>
            </thead>
            <tbody>
              @foreach($emailCampaign->recipients as $recipient)
                <tr>
                  <td>
                    @if($recipient->user)
                      <a href="{{ route('portal.admin.clients.show', $recipient->user) }}">
                        {{ $recipient->user->name }}
                      </a>
                    @else
                      <span class="text-muted">Usuario eliminado</span>
                    @endif
                  </td>
                  <td>{{ $recipient->email }}</td>
                  <td>
                    @switch($recipient->status)
                      @case('pending')
                        <span class="badge bg-secondary">Pendiente</span>
                        @break
                      @case('sent')
                        <span class="badge bg-success"><i class="fas fa-check"></i> Enviado</span>
                        @break
                      @case('failed')
                        <span class="badge bg-danger"><i class="fas fa-times"></i> Fallido</span>
                        @break
                    @endswitch
                  </td>
                  <td>
                    @if($recipient->sent_at)
                      <small>{{ $recipient->sent_at->format('d/m/Y H:i:s') }}</small>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @if($recipient->error_message)
                      <small class="text-danger">{{ Str::limit($recipient->error_message, 50) }}</small>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @else
    <div class="card shadow">
      <div class="card-body text-center py-5">
        <i class="fas fa-users fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No se han procesado destinatarios aún</h5>
        @if($emailCampaign->status === 'draft')
          <p class="text-muted">La campaña está en estado de borrador. Envíala para ver los destinatarios.</p>
          <a href="{{ route('portal.admin.email-campaigns.confirm', $emailCampaign) }}" class="btn btn-success">
            <i class="fas fa-paper-plane me-1"></i>Enviar Campaña
          </a>
        @endif
      </div>
    </div>
  @endif
</div>
@endsection
