@extends('layouts.admin')

@section('title', 'Detalles de Campaña')
@section('page-title', 'Detalles de Campaña')

@section('content')
<div class="email-campaign-details-page">
  <div class="container-fluid py-4">
    {{-- Header --}}
    <div class="page-header">
      <div class="header-content">
        <div class="header-icon">
          <i class="fas fa-paper-plane"></i>
        </div>
        <div class="header-text">
          <h1 class="header-title">{{ $emailCampaign->name }}</h1>
          <p class="header-subtitle">
            Creada por {{ $emailCampaign->creator->name }} el {{ $emailCampaign->created_at->format('d/m/Y H:i') }}
          </p>
        </div>
      </div>
      <div class="header-actions">
        @if($emailCampaign->status === 'draft')
          <a href="{{ route('portal.admin.email-campaigns.confirm', $emailCampaign) }}" class="btn-send">
            <i class="fas fa-paper-plane"></i>
            <span>Enviar Campaña</span>
          </a>
        @endif
        @if(in_array($emailCampaign->status, ['sending', 'sent']))
          <form method="POST" action="{{ route('portal.admin.email-campaigns.stop', $emailCampaign) }}" style="display: inline;">
            @csrf
            <button type="button" class="btn-stop" onclick="confirmStop(this.form)">
              <i class="fas fa-stop-circle"></i>
              <span>Detener Campaña</span>
            </button>
          </form>
        @endif
        <a href="{{ route('portal.admin.email-campaigns.index') }}" class="btn-back">
          <i class="fas fa-arrow-left"></i>
          <span>Volver</span>
        </a>
      </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
      <div class="alert-success">
        <div class="alert-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="alert-content">{{ session('success') }}</div>
        <button type="button" class="alert-close" data-bs-dismiss="alert">
          <i class="fas fa-times"></i>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert-danger">
        <div class="alert-icon">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="alert-content">{{ session('error') }}</div>
        <button type="button" class="alert-close" data-bs-dismiss="alert">
          <i class="fas fa-times"></i>
        </button>
      </div>
    @endif

    <div class="content-grid">
      {{-- Información General --}}
      <div class="info-card">
        <div class="card-header">
          <i class="fas fa-info-circle"></i>
          <h2 class="card-title">Información de la Campaña</h2>
        </div>
        <div class="card-body">
          <div class="info-list">
            <div class="info-item">
              <span class="info-label">Nombre:</span>
              <span class="info-value">{{ $emailCampaign->name }}</span>
            </div>
            
            <div class="info-item">
              <span class="info-label">Plantilla:</span>
              <div class="info-value-with-button">
                <span class="info-value">{{ $emailCampaign->template->name }}</span>
                <a href="{{ route('portal.admin.email-templates.preview', $emailCampaign->template) }}"
                   target="_blank"
                   class="btn-view-template">
                  <i class="fas fa-eye"></i>
                  Ver Plantilla
                </a>
              </div>
            </div>
            
            <div class="info-item">
              <span class="info-label">Asunto:</span>
              <span class="info-value info-subject">{{ $emailCampaign->template->subject }}</span>
            </div>
            
            <div class="info-item">
              <span class="info-label">Estado:</span>
              <span class="info-value">
                @php
                  $statusClasses = [
                    'draft' => 'status-draft',
                    'sending' => 'status-sending',
                    'sent' => 'status-sent',
                    'failed' => 'status-failed',
                    'stopped' => 'status-stopped',
                  ];
                  $statusLabels = [
                    'draft' => 'Borrador',
                    'sending' => 'Enviando',
                    'sent' => 'Enviada',
                    'failed' => 'Fallida',
                    'stopped' => 'Detenida',
                  ];
                  $statusIcons = [
                    'draft' => 'fa-file-alt',
                    'sending' => 'fa-spinner fa-spin',
                    'sent' => 'fa-check-circle',
                    'failed' => 'fa-exclamation-circle',
                    'stopped' => 'fa-stop-circle',
                  ];
                @endphp
                <span class="status-badge {{ $statusClasses[$emailCampaign->status] ?? 'status-draft' }}">
                  <i class="fas {{ $statusIcons[$emailCampaign->status] ?? 'fa-file-alt' }}"></i>
                  {{ $statusLabels[$emailCampaign->status] ?? ucfirst($emailCampaign->status) }}
                </span>
              </span>
            </div>
            
            <div class="info-item">
              <span class="info-label">Tipo de Filtro:</span>
              <span class="info-value">
                @switch($emailCampaign->filter_type)
                  @case('all')
                    <span class="filter-badge filter-all">Todos los Clientes</span>
                    @break
                  @case('no_scans')
                    <span class="filter-badge filter-warning">Sin Escaneos ({{ $emailCampaign->no_scans_days }} días)</span>
                    @break
                  @case('payment_due')
                    <span class="filter-badge filter-info">Pago por Vencer ({{ $emailCampaign->payment_due_days }} días)</span>
                    @break
                  @case('custom')
                    <span class="filter-badge filter-custom">Filtro Personalizado</span>
                    @break
                @endswitch
              </span>
            </div>
            
            @if($emailCampaign->started_at)
              <div class="info-item">
                <span class="info-label">Iniciada:</span>
                <span class="info-value">{{ $emailCampaign->started_at->format('d/m/Y H:i:s') }}</span>
              </div>
            @endif
            
            @if($emailCampaign->completed_at)
              <div class="info-item">
                <span class="info-label">Completada:</span>
                <span class="info-value">{{ $emailCampaign->completed_at->format('d/m/Y H:i:s') }}</span>
              </div>
              <div class="info-item">
                <span class="info-label">Duración:</span>
                <span class="info-value">{{ $emailCampaign->started_at->diffForHumans($emailCampaign->completed_at, true) }}</span>
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Estadísticas --}}
      <div class="stats-card">
        <div class="card-header">
          <i class="fas fa-chart-bar"></i>
          <h2 class="card-title">Estadísticas</h2>
        </div>
        <div class="card-body">
          <div class="stats-summary">
            <div class="stat-item">
              <div class="stat-icon stat-icon-blue">
                <i class="fas fa-users"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ $emailCampaign->total_recipients ?? 0 }}</div>
                <div class="stat-label">Total</div>
              </div>
            </div>
            
            <div class="stat-item">
              <div class="stat-icon stat-icon-green">
                <i class="fas fa-check"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ $emailCampaign->sent_count }}</div>
                <div class="stat-label">Enviados</div>
              </div>
            </div>
            
            <div class="stat-item">
              <div class="stat-icon stat-icon-red">
                <i class="fas fa-times"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value">{{ $emailCampaign->failed_count }}</div>
                <div class="stat-label">Fallidos</div>
              </div>
            </div>
          </div>

          @if($emailCampaign->total_recipients > 0)
            @php
              $successRate = ($emailCampaign->sent_count / $emailCampaign->total_recipients) * 100;
              $failedRate = ($emailCampaign->failed_count / $emailCampaign->total_recipients) * 100;
              $pendingRate = 100 - $successRate - $failedRate;
            @endphp
            
            <div class="progress-section">
              <div class="progress-bar-container">
                @if($successRate > 0)
                  <div class="progress-segment progress-success" style="width: {{ $successRate }}%">
                    <span class="progress-label">{{ round($successRate) }}%</span>
                  </div>
                @endif
                @if($failedRate > 0)
                  <div class="progress-segment progress-failed" style="width: {{ $failedRate }}%">
                    <span class="progress-label">{{ round($failedRate) }}%</span>
                  </div>
                @endif
                @if($pendingRate > 0)
                  <div class="progress-segment progress-pending" style="width: {{ $pendingRate }}%">
                    <span class="progress-label">{{ round($pendingRate) }}%</span>
                  </div>
                @endif
              </div>
              
              <div class="progress-legend">
                <div class="legend-item">
                  <span class="legend-color legend-success"></span>
                  <span class="legend-text">Enviados ({{ round($successRate, 1) }}%)</span>
                </div>
                @if($failedRate > 0)
                  <div class="legend-item">
                    <span class="legend-color legend-failed"></span>
                    <span class="legend-text">Fallidos ({{ round($failedRate, 1) }}%)</span>
                  </div>
                @endif
                @if($pendingRate > 0)
                  <div class="legend-item">
                    <span class="legend-color legend-pending"></span>
                    <span class="legend-text">Pendientes ({{ round($pendingRate, 1) }}%)</span>
                  </div>
                @endif
              </div>
              
              <div class="success-rate">
                <i class="fas fa-chart-line"></i>
                Tasa de éxito: <strong>{{ round($successRate, 1) }}%</strong>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Lista de Destinatarios --}}
    @if($emailCampaign->recipients->isNotEmpty())
      <div class="recipients-card">
        <div class="card-header">
          <i class="fas fa-users"></i>
          <h2 class="card-title">Destinatarios ({{ $emailCampaign->recipients->count() }})</h2>
        </div>
        <div class="card-body">
          <div class="recipients-table-wrapper">
            @foreach($emailCampaign->recipients as $recipient)
              <div class="recipient-row">
                <div class="recipient-user">
                  <div class="recipient-avatar">
                    <i class="fas fa-user"></i>
                  </div>
                  <div class="recipient-info">
                    @if($recipient->user)
                      <a href="{{ route('portal.admin.clients.show', $recipient->user) }}" class="recipient-name">
                        {{ $recipient->user->name }}
                      </a>
                    @else
                      <span class="recipient-name recipient-deleted">Usuario eliminado</span>
                    @endif
                    <span class="recipient-email">{{ $recipient->email }}</span>
                  </div>
                </div>

                <div class="recipient-status">
                  @switch($recipient->status)
                    @case('pending')
                      <span class="recipient-badge badge-pending">
                        <i class="fas fa-clock"></i>
                        Pendiente
                      </span>
                      @break
                    @case('sent')
                      <span class="recipient-badge badge-sent">
                        <i class="fas fa-check"></i>
                        Enviado
                      </span>
                      @break
                    @case('failed')
                      <span class="recipient-badge badge-failed">
                        <i class="fas fa-times"></i>
                        Fallido
                      </span>
                      @break
                    @case('stopped')
                      <span class="recipient-badge badge-stopped">
                        <i class="fas fa-stop-circle"></i>
                        Detenido
                      </span>
                      @break
                  @endswitch
                </div>

                <div class="recipient-meta">
                  @if($recipient->sent_at)
                    <span class="meta-date">
                      <i class="far fa-clock"></i>
                      {{ $recipient->sent_at->format('d/m/Y H:i:s') }}
                    </span>
                  @else
                    <span class="meta-empty">-</span>
                  @endif
                  
                  @if($recipient->error_message)
                    <span class="meta-error" title="{{ $recipient->error_message }}">
                      <i class="fas fa-exclamation-circle"></i>
                      {{ Str::limit($recipient->error_message, 50) }}
                    </span>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @else
      <div class="empty-recipients-card">
        <div class="empty-icon">
          <i class="fas fa-users"></i>
        </div>
        <h3 class="empty-title">No se han procesado destinatarios aún</h3>
        @if($emailCampaign->status === 'draft')
          <p class="empty-text">La campaña está en estado de borrador. Envíala para ver los destinatarios.</p>
          <a href="{{ route('portal.admin.email-campaigns.confirm', $emailCampaign) }}" class="btn-send-empty">
            <i class="fas fa-paper-plane"></i>
            Enviar Campaña
          </a>
        @else
          <p class="empty-text">Esta campaña no tiene destinatarios asignados.</p>
        @endif
      </div>
    @endif
  </div>
</div>

<style>
.email-campaign-details-page {
  background: #f8f9fa;
  min-height: 100vh;
}

/* ========== Header ========== */
.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 32px;
  flex-wrap: wrap;
}

.header-content {
  display: flex;
  align-items: center;
  gap: 20px;
}

.header-icon {
  width: 64px;
  height: 64px;
  background: linear-gradient(135deg, #115DFC 0%, #0047CC 100%);
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
  box-shadow: 0 8px 20px rgba(17, 93, 252, 0.3);
  flex-shrink: 0;
}

.header-text {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.header-title {
  font-size: 28px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
  line-height: 1.2;
}

.header-subtitle {
  font-size: 14px;
  color: #6c757d;
  margin: 0;
  font-weight: 500;
}

.header-actions {
  display: flex;
  gap: 12px;
}

.btn-send {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 24px;
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.25);
}

.btn-send:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(76, 175, 80, 0.35);
  color: white;
}

.btn-stop {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 24px;
  background: linear-gradient(135deg, #F44336 0%, #E57373 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(244, 67, 54, 0.25);
}

.btn-stop:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(244, 67, 54, 0.35);
  background: linear-gradient(135deg, #E53935 0%, #EF5350 100%);
}

.btn-stop i {
  font-size: 16px;
}

.btn-back {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  background: white;
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  color: #424242;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.btn-back:hover {
  background: #f5f5f5;
  border-color: #115DFC;
  color: #115DFC;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0,0,0,0.1);
}

.btn-send i,
.btn-back i {
  font-size: 16px;
}

/* ========== Alerts ========== */
.alert-success,
.alert-danger {
  border-radius: 12px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 24px;
}

.alert-success {
  background: white;
  border-left: 4px solid #4CAF50;
  box-shadow: 0 2px 12px rgba(76, 175, 80, 0.15);
}

.alert-danger {
  background: white;
  border-left: 4px solid #F44336;
  box-shadow: 0 2px 12px rgba(244, 67, 54, 0.15);
}

.alert-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 18px;
  flex-shrink: 0;
}

.alert-success .alert-icon {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.alert-danger .alert-icon {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
}

.alert-content {
  flex: 1;
  color: #424242;
  font-size: 14px;
  font-weight: 500;
}

.alert-close {
  width: 32px;
  height: 32px;
  border: none;
  background: #f5f5f5;
  border-radius: 8px;
  color: #757575;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.alert-close:hover {
  background: #eeeeee;
  color: #424242;
}

/* ========== Content Grid ========== */
.content-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 24px;
  margin-bottom: 24px;
}

/* ========== Cards ========== */
.info-card,
.stats-card,
.recipients-card,
.empty-recipients-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  overflow: hidden;
}

.card-header {
  padding: 20px 24px;
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  align-items: center;
  gap: 12px;
}

.card-header i {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 18px;
}

.card-title {
  font-size: 18px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
}

.card-body {
  padding: 24px;
}

/* ========== Info List ========== */
.info-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.info-item {
  display: grid;
  grid-template-columns: 180px 1fr;
  gap: 16px;
  padding-bottom: 20px;
  border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.info-label {
  font-size: 13px;
  font-weight: 700;
  color: #757575;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.info-value {
  font-size: 14px;
  color: #424242;
  font-weight: 500;
}

.info-subject {
  font-weight: 700;
  color: #1a1a1a;
}

.info-value-with-button {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.btn-view-template {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  background: linear-gradient(135deg, #2196F3 0%, #42A5F5 100%);
  border: none;
  border-radius: 8px;
  color: white;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(33, 150, 243, 0.25);
}

.btn-view-template:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(33, 150, 243, 0.35);
  color: white;
}

/* Status Badges */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 12px;
  font-size: 14px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.status-draft {
  background: linear-gradient(135deg, #9E9E9E 0%, #757575 100%);
  color: white;
}

.status-sending {
  background: linear-gradient(135deg, #FF9800 0%, #FFB74D 100%);
  color: white;
}

.status-sent {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  color: white;
}

.status-failed {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
  color: white;
}

.status-stopped {
  background: linear-gradient(135deg, #FF6F00 0%, #FF8F00 100%);
  color: white;
}

/* Filter Badges */
.filter-badge {
  display: inline-flex;
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
}

.filter-all {
  background: #e3f2fd;
  color: #1976D2;
}

.filter-warning {
  background: #fff3e0;
  color: #E65100;
}

.filter-info {
  background: #e1f5fe;
  color: #0277BD;
}

.filter-custom {
  background: #f3e5f5;
  color: #7B1FA2;
}

/* ========== Stats ========== */
.stats-summary {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 24px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  background: #fafafa;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.stat-item:hover {
  background: white;
  transform: translateX(4px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: white;
  flex-shrink: 0;
}

.stat-icon-blue {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
}

.stat-icon-green {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.stat-icon-red {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
}

.stat-info {
  flex: 1;
}

.stat-value {
  font-size: 28px;
  font-weight: 700;
  color: #1a1a1a;
  line-height: 1;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 12px;
  color: #757575;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Progress Section */
.progress-section {
  padding-top: 24px;
  border-top: 1px solid #f0f0f0;
}

.progress-bar-container {
  display: flex;
  width: 100%;
  height: 32px;
  background: #f0f0f0;
  border-radius: 16px;
  overflow: hidden;
  margin-bottom: 16px;
}

.progress-segment {
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  position: relative;
}

.progress-success {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.progress-failed {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
}

.progress-pending {
  background: linear-gradient(135deg, #9E9E9E 0%, #BDBDBD 100%);
}

.progress-label {
  color: white;
  font-size: 12px;
  font-weight: 700;
}

.progress-legend {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  margin-bottom: 16px;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 8px;
}

.legend-color {
  width: 16px;
  height: 16px;
  border-radius: 4px;
}

.legend-success {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.legend-failed {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
}

.legend-pending {
  background: linear-gradient(135deg, #9E9E9E 0%, #BDBDBD 100%);
}

.legend-text {
  font-size: 13px;
  color: #616161;
  font-weight: 500;
}

.success-rate {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px;
  background: #e8f5e9;
  border-radius: 8px;
  font-size: 14px;
  color: #2E7D32;
}

.success-rate i {
  font-size: 16px;
}

.success-rate strong {
  font-size: 16px;
}

/* ========== Recipients ========== */
.recipients-card {
  grid-column: 1 / -1;
}

.recipients-table-wrapper {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.recipient-row {
  display: grid;
  grid-template-columns: 2fr 1fr 2fr;
  gap: 20px;
  padding: 16px;
  background: #fafafa;
  border-radius: 12px;
  transition: all 0.3s ease;
  align-items: center;
}

.recipient-row:hover {
  background: white;
  transform: translateX(4px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.recipient-user {
  display: flex;
  align-items: center;
  gap: 12px;
}

.recipient-avatar {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 16px;
  flex-shrink: 0;
}

.recipient-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 0;
}

.recipient-name {
  font-size: 14px;
  font-weight: 700;
  color: #115DFC;
  text-decoration: none;
  transition: all 0.2s ease;
}

.recipient-name:hover {
  color: #0047CC;
  text-decoration: underline;
}

.recipient-deleted {
  color: #9e9e9e;
  cursor: default;
}

.recipient-deleted:hover {
  text-decoration: none;
}

.recipient-email {
  font-size: 12px;
  color: #757575;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.recipient-status {
  display: flex;
  justify-content: center;
}

.recipient-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-pending {
  background: #f5f5f5;
  color: #757575;
}

.badge-sent {
  background: #e8f5e9;
  color: #2E7D32;
}

.badge-failed {
  background: #ffebee;
  color: #C62828;
}

.badge-stopped {
  background: #fff3e0;
  color: #E65100;
}

.recipient-meta {
  display: flex;
  flex-direction: column;
  gap: 6px;
  font-size: 12px;
}

.meta-date {
  color: #616161;
  display: flex;
  align-items: center;
  gap: 6px;
}

.meta-date i {
  font-size: 11px;
}

.meta-empty {
  color: #9e9e9e;
}

.meta-error {
  color: #C62828;
  display: flex;
  align-items: flex-start;
  gap: 6px;
  line-height: 1.4;
}

.meta-error i {
  font-size: 11px;
  flex-shrink: 0;
  margin-top: 2px;
}

/* ========== Empty State ========== */
.empty-recipients-card {
  grid-column: 1 / -1;
  padding: 80px 40px;
  text-align: center;
}

.empty-icon {
  font-size: 72px;
  color: #e0e0e0;
  margin-bottom: 24px;
}

.empty-title {
  font-size: 22px;
  font-weight: 700;
  color: #424242;
  margin: 0 0 12px 0;
}

.empty-text {
  font-size: 15px;
  color: #9e9e9e;
  margin: 0 0 24px 0;
}

.btn-send-empty {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 14px 32px;
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.25);
}

.btn-send-empty:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(76, 175, 80, 0.35);
  color: white;
}

/* ========== Responsive ========== */
@media (max-width: 1200px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: stretch;
    gap: 16px;
  }

  .header-content {
    gap: 16px;
  }

  .header-icon {
    width: 56px;
    height: 56px;
    font-size: 24px;
  }

  .header-title {
    font-size: 24px;
  }

  .header-subtitle {
    font-size: 13px;
  }

  .header-actions {
    width: 100%;
    flex-wrap: wrap;
  }

  .btn-send,
  .btn-back {
    flex: 1;
    justify-content: center;
    padding: 14px 20px;
  }

  .info-item {
    grid-template-columns: 1fr;
    gap: 8px;
  }

  .info-value-with-button {
    flex-direction: column;
    align-items: flex-start;
  }

  .btn-view-template {
    width: 100%;
    justify-content: center;
  }

  .recipient-row {
    grid-template-columns: 1fr;
    gap: 12px;
  }

  .recipient-status {
    justify-content: flex-start;
  }
}

@media (max-width: 480px) {
  .empty-recipients-card {
    padding: 60px 24px;
  }

  .empty-icon {
    font-size: 60px;
  }

  .progress-legend {
    flex-direction: column;
    gap: 8px;
  }

  .stats-summary {
    gap: 12px;
  }

  .stat-item {
    padding: 12px;
  }

  .stat-icon {
    width: 40px;
    height: 40px;
    font-size: 18px;
  }

  .stat-value {
    font-size: 24px;
  }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmStop(form) {
  Swal.fire({
    title: '¿Detener campaña?',
    text: 'Se detendrá el envío de emails pendientes. Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#F44336',
    cancelButtonColor: '#9E9E9E',
    confirmButtonText: 'Sí, detener',
    cancelButtonText: 'Cancelar',
    reverseButtons: true
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit();
    }
  });
}
</script>
@endpush
@endsection
