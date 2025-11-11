@extends('layouts.app')

@section('title', 'Campañas de Email')

@section('content')
<div class="email-campaigns-page">
  <div class="container-fluid py-4">
    {{-- Header --}}
    <div class="page-header">
      <div class="header-content">
        <div class="header-icon">
          <i class="fas fa-paper-plane"></i>
        </div>
        <div class="header-text">
          <h1 class="header-title">Campañas de Email</h1>
          <p class="header-subtitle">Gestiona tus campañas de email marketing</p>
        </div>
      </div>
      <div class="header-actions">
        <a href="{{ route('portal.admin.email-campaigns.create') }}" class="btn-create">
          <i class="fas fa-plus"></i>
          <span>Nueva Campaña</span>
        </a>
      </div>
    </div>

    {{-- Success Alert --}}
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

    {{-- Campaigns Content --}}
    @if($campaigns->isEmpty())
      <div class="empty-state">
        <div class="empty-icon">
          <i class="fas fa-paper-plane"></i>
        </div>
        <h3 class="empty-title">No hay campañas creadas</h3>
        <p class="empty-text">Crea tu primera campaña de email marketing</p>
        <a href="{{ route('portal.admin.email-campaigns.create') }}" class="btn-empty">
          <i class="fas fa-plus"></i>
          Crear Campaña
        </a>
      </div>
    @else
      <div class="campaigns-grid">
        @foreach($campaigns as $campaign)
          @php
            $statusClasses = [
              'draft' => 'status-draft',
              'sending' => 'status-sending',
              'sent' => 'status-sent',
              'failed' => 'status-failed',
            ];
            $statusLabels = [
              'draft' => 'Borrador',
              'sending' => 'Enviando',
              'sent' => 'Enviada',
              'failed' => 'Fallida',
            ];
            $statusIcons = [
              'draft' => 'fa-file-alt',
              'sending' => 'fa-spinner fa-spin',
              'sent' => 'fa-check-circle',
              'failed' => 'fa-exclamation-circle',
            ];
          @endphp

          <div class="campaign-card {{ $statusClasses[$campaign->status] ?? 'status-draft' }}">
            {{-- Status Badge --}}
            <div class="campaign-status-badge">
              <i class="fas {{ $statusIcons[$campaign->status] ?? 'fa-file-alt' }}"></i>
              {{ $statusLabels[$campaign->status] ?? ucfirst($campaign->status) }}
            </div>

            {{-- Card Header --}}
            <div class="campaign-header">
              <div class="campaign-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="campaign-info">
                <h3 class="campaign-name">{{ $campaign->name }}</h3>
                <p class="campaign-creator">
                  <i class="fas fa-user"></i>
                  Por {{ $campaign->creator->name }}
                </p>
              </div>
            </div>

            {{-- Card Body --}}
            <div class="campaign-body">
              <div class="campaign-meta">
                <div class="meta-item">
                  <span class="meta-label">Plantilla</span>
                  <span class="meta-value">
                    <i class="fas fa-file-alt"></i>
                    {{ $campaign->template->name }}
                  </span>
                </div>

                <div class="meta-item">
                  <span class="meta-label">Creada</span>
                  <span class="meta-value">
                    <i class="far fa-calendar"></i>
                    {{ $campaign->created_at->format('d/m/Y H:i') }}
                  </span>
                </div>
              </div>

              {{-- Statistics --}}
              <div class="campaign-stats">
                <div class="stat-item stat-total">
                  <div class="stat-icon">
                    <i class="fas fa-users"></i>
                  </div>
                  <div class="stat-info">
                    <div class="stat-value">{{ $campaign->total_recipients }}</div>
                    <div class="stat-label">Destinatarios</div>
                  </div>
                </div>

                <div class="stat-item stat-sent">
                  <div class="stat-icon">
                    <i class="fas fa-check"></i>
                  </div>
                  <div class="stat-info">
                    <div class="stat-value">{{ $campaign->sent_count }}</div>
                    <div class="stat-label">Enviados</div>
                  </div>
                </div>

                <div class="stat-item stat-failed">
                  <div class="stat-icon">
                    <i class="fas fa-times"></i>
                  </div>
                  <div class="stat-info">
                    <div class="stat-value">{{ $campaign->failed_count }}</div>
                    <div class="stat-label">Fallidos</div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Card Actions --}}
            <div class="campaign-actions">
              <a href="{{ route('portal.admin.email-campaigns.show', $campaign) }}" class="action-btn action-view">
                <i class="fas fa-eye"></i>
                <span>Ver detalles</span>
              </a>

              @if($campaign->status === 'draft')
                <a href="{{ route('portal.admin.email-campaigns.confirm', $campaign) }}" class="action-btn action-send">
                  <i class="fas fa-paper-plane"></i>
                  <span>Enviar</span>
                </a>
              @endif

              @if($campaign->status !== 'sending')
                <form method="POST" action="{{ route('portal.admin.email-campaigns.destroy', $campaign) }}" 
                      style="display:inline;" 
                      onsubmit="return confirm('¿Eliminar esta campaña?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="action-btn action-delete">
                    <i class="fas fa-trash"></i>
                    <span>Eliminar</span>
                  </button>
                </form>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($campaigns->hasPages())
        <div class="pagination-wrapper">
          {{ $campaigns->links() }}
        </div>
      @endif
    @endif
  </div>
</div>

<style>
.email-campaigns-page {
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

.btn-create {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 24px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.25);
}

.btn-create:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.35);
  color: white;
}

.btn-create i {
  font-size: 16px;
}

/* ========== Alert ========== */
.alert-success {
  background: white;
  border-left: 4px solid #4CAF50;
  border-radius: 12px;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 2px 12px rgba(76, 175, 80, 0.15);
  margin-bottom: 24px;
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

/* ========== Empty State ========== */
.empty-state {
  background: white;
  border-radius: 20px;
  padding: 80px 40px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
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

.btn-empty {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 14px 32px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.25);
}

.btn-empty:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.35);
  color: white;
}

/* ========== Campaigns Grid ========== */
.campaigns-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
  gap: 24px;
  margin-bottom: 32px;
}

.campaign-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  display: flex;
  flex-direction: column;
  border: 2px solid transparent;
}

.campaign-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.1);
  border-color: #115DFC;
}

/* Status-specific backgrounds */
.status-draft {
  background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%);
}

.status-sending {
  background: linear-gradient(135deg, #fffbf0 0%, #ffffff 100%);
}

.status-sent {
  background: linear-gradient(135deg, #f0fff4 0%, #ffffff 100%);
}

.status-failed {
  background: linear-gradient(135deg, #fff5f5 0%, #ffffff 100%);
}

/* Status Badge */
.campaign-status-badge {
  position: absolute;
  top: 16px;
  right: 16px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  z-index: 1;
}

.campaign-status-badge i {
  font-size: 11px;
}

.status-draft .campaign-status-badge {
  background: linear-gradient(135deg, #9E9E9E 0%, #757575 100%);
  color: white;
}

.status-sending .campaign-status-badge {
  background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
  color: white;
}

.status-sent .campaign-status-badge {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  color: white;
}

.status-failed .campaign-status-badge {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
  color: white;
}

/* Campaign Header */
.campaign-header {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  margin-bottom: 20px;
  padding-right: 80px;
}

.campaign-icon {
  width: 56px;
  height: 56px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.3);
  transition: all 0.3s ease;
}

.campaign-card:hover .campaign-icon {
  transform: scale(1.1) rotate(5deg);
}

.campaign-info {
  flex: 1;
  min-width: 0;
}

.campaign-name {
  font-size: 18px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 8px 0;
  line-height: 1.3;
}

.campaign-creator {
  font-size: 13px;
  color: #757575;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 6px;
}

.campaign-creator i {
  font-size: 12px;
}

/* Campaign Body */
.campaign-body {
  flex: 1;
  margin-bottom: 20px;
}

.campaign-meta {
  display: flex;
  flex-direction: column;
  gap: 14px;
  margin-bottom: 20px;
}

.meta-item {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.meta-label {
  font-size: 11px;
  font-weight: 700;
  color: #9e9e9e;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.meta-value {
  font-size: 14px;
  color: #424242;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}

.meta-value i {
  color: #115DFC;
  font-size: 13px;
}

/* Statistics */
.campaign-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
}

.stat-item {
  background: #fafafa;
  border-radius: 12px;
  padding: 14px;
  display: flex;
  align-items: center;
  gap: 12px;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.stat-item:hover {
  background: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  color: white;
  flex-shrink: 0;
}

.stat-total .stat-icon {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
}

.stat-sent .stat-icon {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.stat-failed .stat-icon {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
}

.stat-info {
  flex: 1;
  min-width: 0;
}

.stat-value {
  font-size: 22px;
  font-weight: 700;
  color: #1a1a1a;
  line-height: 1;
  margin-bottom: 4px;
}

.stat-label {
  font-size: 11px;
  color: #757575;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.3px;
}

/* Campaign Actions */
.campaign-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  padding-top: 20px;
  border-top: 1px solid #f0f0f0;
}

.action-btn {
  flex: 1;
  min-width: 120px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 16px;
  border: none;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.action-btn i {
  font-size: 13px;
}

.action-view {
  background: linear-gradient(135deg, #2196F3 0%, #42A5F5 100%);
  color: white;
}

.action-view:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(33, 150, 243, 0.3);
  color: white;
}

.action-send {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  color: white;
}

.action-send:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(76, 175, 80, 0.3);
  color: white;
}

.action-delete {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
  color: white;
}

.action-delete:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(244, 67, 54, 0.3);
}

/* ========== Pagination ========== */
.pagination-wrapper {
  display: flex;
  justify-content: center;
}

/* ========== Responsive ========== */
@media (max-width: 992px) {
  .campaigns-grid {
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
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
  }

  .btn-create {
    width: 100%;
    justify-content: center;
    padding: 14px 20px;
  }

  .campaigns-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .campaign-card {
    padding: 20px;
  }

  .campaign-header {
    padding-right: 90px;
  }

  .campaign-icon {
    width: 48px;
    height: 48px;
    font-size: 20px;
  }

  .campaign-name {
    font-size: 16px;
  }

  .campaign-stats {
    grid-template-columns: 1fr;
  }

  .stat-item {
    padding: 12px;
  }

  .stat-icon {
    width: 36px;
    height: 36px;
    font-size: 14px;
  }

  .stat-value {
    font-size: 20px;
  }

  .campaign-actions {
    flex-direction: column;
  }

  .action-btn {
    width: 100%;
    min-width: auto;
  }
}

@media (max-width: 480px) {
  .empty-state {
    padding: 60px 24px;
  }

  .empty-icon {
    font-size: 60px;
  }

  .campaign-header {
    padding-right: 70px;
  }

  .campaign-status-badge {
    font-size: 10px;
    padding: 5px 10px;
  }
}
</style>
@endsection
