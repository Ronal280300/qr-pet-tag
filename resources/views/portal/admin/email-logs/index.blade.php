@extends('layouts.app')

@section('title', 'Logs de Correos')

@section('content')
<div class="email-logs-page">
  <div class="container-fluid py-4">
    {{-- Header --}}
    <div class="page-header">
      <div class="header-content">
        <div class="header-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <div class="header-text">
          <h1 class="header-title">Logs de Correos</h1>
          <p class="header-subtitle">Registro de correos enviados</p>
        </div>
      </div>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
      <div class="stat-card stat-total">
        <div class="stat-icon-wrapper">
          <div class="stat-icon">
            <i class="fas fa-envelope"></i>
          </div>
        </div>
        <div class="stat-info">
          <div class="stat-value">{{ $stats['total'] }}</div>
          <div class="stat-label">Total</div>
        </div>
      </div>

      <div class="stat-card stat-sent">
        <div class="stat-icon-wrapper">
          <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
          </div>
        </div>
        <div class="stat-info">
          <div class="stat-value">{{ $stats['sent'] }}</div>
          <div class="stat-label">Enviados</div>
        </div>
      </div>

      <div class="stat-card stat-failed">
        <div class="stat-icon-wrapper">
          <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
        </div>
        <div class="stat-info">
          <div class="stat-value">{{ $stats['failed'] }}</div>
          <div class="stat-label">Fallidos</div>
        </div>
      </div>

      <div class="stat-card stat-today">
        <div class="stat-icon-wrapper">
          <div class="stat-icon">
            <i class="fas fa-calendar-day"></i>
          </div>
        </div>
        <div class="stat-info">
          <div class="stat-value">{{ $stats['today'] }}</div>
          <div class="stat-label">Hoy</div>
        </div>
      </div>

      <div class="stat-card stat-month">
        <div class="stat-icon-wrapper">
          <div class="stat-icon">
            <i class="fas fa-calendar-alt"></i>
          </div>
        </div>
        <div class="stat-info">
          <div class="stat-value">{{ $stats['month'] }}</div>
          <div class="stat-label">Este Mes</div>
        </div>
      </div>
    </div>

    {{-- Filtros --}}
    <div class="filters-card">
      <div class="filters-header">
        <h2 class="filters-title">
          <i class="fas fa-filter"></i>
          Filtros
        </h2>
      </div>
      <div class="filters-body">
        <form method="GET" action="{{ route('portal.admin.email-logs.index') }}" class="filters-form">
          <div class="filter-group">
            <label for="status" class="filter-label">Estado</label>
            <select name="status" id="status" class="filter-select">
              <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos</option>
              <option value="sent" {{ $status === 'sent' ? 'selected' : '' }}>Enviados</option>
              <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Fallidos</option>
            </select>
          </div>

          <div class="filter-group filter-search">
            <label for="search" class="filter-label">Buscar</label>
            <div class="search-input-wrapper">
              <i class="fas fa-search search-icon"></i>
              <input type="text" name="search" id="search" class="filter-input"
                     placeholder="Email, asunto, tipo..." value="{{ $search }}">
            </div>
          </div>

          <div class="filter-group filter-button">
            <label class="filter-label">&nbsp;</label>
            <button type="submit" class="btn-filter">
              <i class="fas fa-search"></i>
              <span>Filtrar</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    {{-- Tabla --}}
    <div class="logs-card">
      <div class="logs-header">
        <h2 class="logs-title">
          <i class="fas fa-list"></i>
          Registros
        </h2>
        <span class="logs-count">{{ $logs->total() }} registros</span>
      </div>

      <div class="logs-body">
        @forelse($logs as $log)
          <div class="log-item">
            <div class="log-main">
              <div class="log-status">
                @if($log->status === 'sent')
                  <div class="status-badge status-success">
                    <i class="fas fa-check"></i>
                  </div>
                @else
                  <div class="status-badge status-danger">
                    <i class="fas fa-times"></i>
                  </div>
                @endif
              </div>

              <div class="log-content">
                <div class="log-header-row">
                  <h3 class="log-recipient">{{ $log->recipient }}</h3>
                  <span class="log-date">
                    <i class="far fa-clock"></i>
                    {{ $log->sent_at?->format('d/m/Y H:i') ?? $log->created_at->format('d/m/Y H:i') }}
                  </span>
                </div>

                <p class="log-subject">{{ $log->subject }}</p>

                <div class="log-meta">
                  <span class="meta-badge meta-type">
                    <i class="fas fa-tag"></i>
                    {{ $log->type ?? 'general' }}
                  </span>

                  @if($log->order)
                    <a href="{{ route('portal.admin.orders.show', $log->order) }}" class="meta-badge meta-order">
                      <i class="fas fa-shopping-cart"></i>
                      Orden #{{ $log->order->order_number }}
                    </a>
                  @endif

                  <span class="meta-badge meta-status {{ $log->status === 'sent' ? 'meta-status-sent' : 'meta-status-failed' }}">
                    @if($log->status === 'sent')
                      <i class="fas fa-check-circle"></i>
                      Enviado
                    @else
                      <i class="fas fa-exclamation-circle"></i>
                      Fallido
                    @endif
                  </span>
                </div>
              </div>
            </div>

            <div class="log-actions">
              <a href="{{ route('portal.admin.email-logs.show', $log) }}" class="btn-view">
                <i class="fas fa-eye"></i>
                <span>Ver detalles</span>
              </a>
            </div>
          </div>
        @empty
          <div class="empty-state">
            <div class="empty-icon">
              <i class="fas fa-inbox"></i>
            </div>
            <h3 class="empty-title">No se encontraron registros</h3>
            <p class="empty-text">Intenta ajustar los filtros de b迆squeda</p>
          </div>
        @endforelse
      </div>

      @if($logs->hasPages())
        <div class="logs-footer">
          {{ $logs->appends(request()->query())->links() }}
        </div>
      @endif
    </div>
  </div>
</div>

<style>
.email-logs-page {
  background: #f8f9fa;
  min-height: 100vh;
}

/* ========== Header ========== */
.page-header {
  margin-bottom: 32px;
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

/* ========== Stats Grid ========== */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 32px;
}

.stat-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #115DFC 0%, #3466ff 100%);
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.1);
}

.stat-card:hover::before {
  transform: scaleX(1);
}

.stat-icon-wrapper {
  position: relative;
}

.stat-icon {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
  transition: all 0.3s ease;
}

.stat-total .stat-icon {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.3);
}

.stat-sent .stat-icon {
  background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
}

.stat-failed .stat-icon {
  background: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
  box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
}

.stat-today .stat-icon {
  background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
  box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.stat-month .stat-icon {
  background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
  box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
}

.stat-card:hover .stat-icon {
  transform: scale(1.1) rotate(5deg);
}

.stat-info {
  flex: 1;
}

.stat-value {
  font-size: 32px;
  font-weight: 700;
  color: #1a1a1a;
  line-height: 1;
  margin-bottom: 6px;
}

.stat-label {
  font-size: 13px;
  color: #757575;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* ========== Filters Card ========== */
.filters-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  margin-bottom: 24px;
  overflow: hidden;
}

.filters-header {
  padding: 20px 24px;
  border-bottom: 1px solid #f0f0f0;
}

.filters-title {
  font-size: 16px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.filters-title i {
  color: #115DFC;
  font-size: 18px;
}

.filters-body {
  padding: 24px;
}

.filters-form {
  display: grid;
  grid-template-columns: 200px 1fr auto;
  gap: 20px;
  align-items: end;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.filter-label {
  font-size: 13px;
  font-weight: 600;
  color: #424242;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin: 0;
}

.filter-select,
.filter-input {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  font-size: 14px;
  color: #424242;
  transition: all 0.3s ease;
  background: white;
}

.filter-select:focus,
.filter-input:focus {
  outline: none;
  border-color: #115DFC;
  box-shadow: 0 0 0 3px rgba(17, 93, 252, 0.1);
}

.search-input-wrapper {
  position: relative;
}

.search-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #9e9e9e;
  font-size: 14px;
  pointer-events: none;
}

.filter-input {
  padding-left: 44px;
}

.btn-filter {
  padding: 12px 28px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border: none;
  border-radius: 10px;
  color: white;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.25);
  white-space: nowrap;
}

.btn-filter:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.35);
}

/* ========== Logs Card ========== */
.logs-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  overflow: hidden;
}

.logs-header {
  padding: 20px 24px;
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
}

.logs-title {
  font-size: 16px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.logs-title i {
  color: #115DFC;
  font-size: 18px;
}

.logs-count {
  font-size: 13px;
  font-weight: 600;
  color: #757575;
  padding: 6px 14px;
  background: #f5f5f5;
  border-radius: 20px;
}

.logs-body {
  padding: 24px;
}

/* ========== Log Item ========== */
.log-item {
  background: #fafafa;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 16px;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.log-item:last-child {
  margin-bottom: 0;
}

.log-item:hover {
  background: white;
  border-color: #115DFC;
  transform: translateX(4px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.log-main {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 16px;
  min-width: 0;
}

.log-status {
  flex-shrink: 0;
}

.status-badge {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.status-success {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.status-danger {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
}

.log-content {
  flex: 1;
  min-width: 0;
}

.log-header-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 8px;
  flex-wrap: wrap;
}

.log-recipient {
  font-size: 16px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
}

.log-date {
  font-size: 13px;
  color: #757575;
  display: flex;
  align-items: center;
  gap: 6px;
  white-space: nowrap;
}

.log-date i {
  font-size: 12px;
}

.log-subject {
  font-size: 14px;
  color: #616161;
  margin: 0 0 12px 0;
  line-height: 1.5;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.log-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.meta-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s ease;
}

.meta-badge i {
  font-size: 11px;
}

.meta-type {
  background: #e3f2fd;
  color: #1976D2;
}

.meta-order {
  background: #f3e5f5;
  color: #7B1FA2;
}

.meta-order:hover {
  background: #7B1FA2;
  color: white;
  transform: translateY(-2px);
}

.meta-status {
  border: 2px solid;
}

.meta-status-sent {
  background: #E8F5E9;
  color: #388E3C;
  border-color: #4CAF50;
}

.meta-status-failed {
  background: #FFEBEE;
  color: #C62828;
  border-color: #F44336;
}

.log-actions {
  flex-shrink: 0;
}

.btn-view {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border: none;
  border-radius: 10px;
  color: white;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(17, 93, 252, 0.25);
  white-space: nowrap;
}

.btn-view:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.35);
  color: white;
}

.btn-view i {
  font-size: 14px;
}

/* ========== Empty State ========== */
.empty-state {
  padding: 60px 24px;
  text-align: center;
}

.empty-icon {
  font-size: 64px;
  color: #e0e0e0;
  margin-bottom: 20px;
}

.empty-title {
  font-size: 20px;
  font-weight: 700;
  color: #424242;
  margin: 0 0 8px 0;
}

.empty-text {
  font-size: 14px;
  color: #9e9e9e;
  margin: 0;
}

/* ========== Pagination ========== */
.logs-footer {
  padding: 20px 24px;
  border-top: 1px solid #f0f0f0;
  display: flex;
  justify-content: center;
}

/* ========== Responsive ========== */
@media (max-width: 992px) {
  .filters-form {
    grid-template-columns: 1fr 1fr;
  }

  .filter-search {
    grid-column: 1 / -1;
  }

  .filter-button {
    grid-column: 1 / -1;
  }

  .btn-filter {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 768px) {
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

  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }

  .stat-card {
    padding: 20px 16px;
    gap: 12px;
  }

  .stat-icon {
    width: 48px;
    height: 48px;
    font-size: 20px;
  }

  .stat-value {
    font-size: 26px;
  }

  .stat-label {
    font-size: 11px;
  }

  .filters-form {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .filter-search,
  .filter-button {
    grid-column: 1;
  }

  .log-item {
    flex-direction: column;
    align-items: stretch;
    padding: 16px;
  }

  .log-main {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .log-header-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }

  .status-badge {
    width: 44px;
    height: 44px;
    font-size: 18px;
  }

  .log-recipient {
    font-size: 15px;
  }

  .log-date {
    font-size: 12px;
  }

  .log-subject {
    font-size: 13px;
  }

  .log-actions {
    width: 100%;
  }

  .btn-view {
    width: 100%;
    justify-content: center;
    padding: 12px 20px;
  }
}

@media (max-width: 576px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .logs-header {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>
@endsection
