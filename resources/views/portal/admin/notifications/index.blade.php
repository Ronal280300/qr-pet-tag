@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<div class="container-fluid py-4">
  {{-- Header --}}
  <div class="row align-items-center mb-4">
    <div class="col">
      <h1 class="h3 mb-2 text-gray-800 d-flex align-items-center">
        <div class="notification-icon-header me-3">
          <i class="fas fa-bell"></i>
        </div>
        <div>
          <div>Notificaciones</div>
          <small class="text-muted fw-normal">Centro de notificaciones administrativas</small>
        </div>
      </h1>
    </div>
    <div class="col-auto d-flex gap-2">
      @php
        $unreadNotifications = $notifications->where('is_read', false);
        $unreadCount = $unreadNotifications->count();
      @endphp

      {{-- Botón de acciones masivas --}}
      <div class="btn-group" id="bulkActionsGroup" style="display: none;">
        <button type="button" class="btn btn-success btn-sm shadow-sm" onclick="markSelectedAsRead()">
          <i class="fas fa-check-double me-1"></i>Marcar seleccionadas como leídas
        </button>
      </div>

      @if($unreadCount > 0)
        <button type="button" class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#confirmReadAllModal">
          <i class="fas fa-check-double me-1"></i>Marcar todas como leídas ({{ $unreadCount }})
        </button>
      @endif
    </div>
  </div>

  {{-- Alert de éxito --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Stats Cards --}}
  <div class="row mb-4">
    <div class="col-md-3">
      <div class="stats-card stats-card-primary">
        <div class="stats-icon">
          <i class="fas fa-bell"></i>
        </div>
        <div class="stats-content">
          <div class="stats-number">{{ $notifications->total() }}</div>
          <div class="stats-label">Total</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stats-card stats-card-warning">
        <div class="stats-icon">
          <i class="fas fa-envelope"></i>
        </div>
        <div class="stats-content">
          <div class="stats-number">{{ $unreadCount }}</div>
          <div class="stats-label">Sin Leer</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stats-card stats-card-success">
        <div class="stats-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stats-content">
          <div class="stats-number">{{ $notifications->where('is_read', true)->count() }}</div>
          <div class="stats-label">Leídas</div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stats-card stats-card-info">
        <div class="stats-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stats-content">
          <div class="stats-number">{{ \App\Models\AdminNotification::whereDate('created_at', today())->count() }}</div>
          <div class="stats-label">Hoy</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Notificaciones --}}
  @if($notifications->isEmpty())
    <div class="empty-state">
      <div class="empty-state-icon">
        <i class="fas fa-bell-slash"></i>
      </div>
      <h4 class="empty-state-title">No hay notificaciones</h4>
      <p class="empty-state-text">Cuando recibas notificaciones, aparecerán aquí</p>
    </div>
  @else
    {{-- Sección: Notificaciones Nuevas --}}
    @if($unreadNotifications->count() > 0)
      <div class="section-header mb-3">
        <h5 class="section-title">
          <i class="fas fa-envelope-open-text me-2"></i>Nuevas
          <span class="badge bg-warning text-dark ms-2">{{ $unreadNotifications->count() }}</span>
        </h5>
        @if($unreadNotifications->count() > 1)
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="selectAllNew" onchange="toggleAllCheckboxes('new', this.checked)">
            <label class="form-check-label" for="selectAllNew">
              Seleccionar todas
            </label>
          </div>
        @endif
      </div>

      <div class="notifications-container mb-5">
        @foreach($unreadNotifications as $notification)
          @php
            $typeClass = match($notification->type) {
              'new_order' => 'notification-new-order',
              'payment_uploaded' => 'notification-payment',
              default => 'notification-default'
            };
          @endphp

          <div class="notification-card {{ $typeClass }} notification-unread" data-id="{{ $notification->id }}">
            <div class="notification-checkbox">
              <input type="checkbox" class="form-check-input notification-select new-notification"
                     value="{{ $notification->id }}" onchange="updateBulkActions()">
            </div>

            <div class="notification-icon-wrapper">
              <div class="notification-icon">
                <i class="fas {{ $notification->icon ?? 'fa-bell' }}"></i>
              </div>
              <div class="notification-pulse"></div>
            </div>

            <div class="notification-content">
              <div class="notification-header">
                <h6 class="notification-title">{{ $notification->title }}</h6>
                <span class="badge-new">Nuevo</span>
              </div>

              <p class="notification-message">{{ $notification->message }}</p>

              <div class="notification-meta">
                <span class="notification-time">
                  <i class="far fa-clock me-1"></i>
                  {{ $notification->created_at->diffForHumans() }}
                </span>
                @if($notification->order)
                  <span class="notification-order">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Orden #{{ $notification->order->order_number }}
                  </span>
                @endif
                @if($notification->user)
                  <span class="notification-user">
                    <i class="fas fa-user me-1"></i>
                    {{ $notification->user->name }}
                  </span>
                @endif
              </div>
            </div>

            <div class="notification-actions">
              @if($notification->url)
                <a href="{{ $notification->url }}" class="btn-action btn-action-primary" title="Ver detalles">
                  <i class="fas fa-external-link-alt"></i>
                  <span>Ver</span>
                </a>
              @endif

              <form method="POST" action="{{ route('portal.admin.notifications.read', $notification) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-action btn-action-success" title="Marcar como leída">
                  <i class="fas fa-check"></i>
                  <span>Marcar leída</span>
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    {{-- Sección: Historial (Notificaciones Leídas) --}}
    @php
      $readNotifications = $notifications->where('is_read', true);
    @endphp

    @if($readNotifications->count() > 0)
      <div class="section-header mb-3 mt-5">
        <h5 class="section-title">
          <i class="fas fa-history me-2"></i>Historial
          <span class="badge bg-secondary ms-2">{{ $readNotifications->count() }}</span>
        </h5>
      </div>

      <div class="notifications-container">
        @foreach($readNotifications as $notification)
          @php
            $typeClass = match($notification->type) {
              'new_order' => 'notification-new-order',
              'payment_uploaded' => 'notification-payment',
              default => 'notification-default'
            };
          @endphp

          <div class="notification-card {{ $typeClass }} notification-read" data-id="{{ $notification->id }}">
            <div class="notification-icon-wrapper">
              <div class="notification-icon notification-icon-muted">
                <i class="fas {{ $notification->icon ?? 'fa-bell' }}"></i>
              </div>
            </div>

            <div class="notification-content">
              <div class="notification-header">
                <h6 class="notification-title text-muted">{{ $notification->title }}</h6>
              </div>

              <p class="notification-message text-muted">{{ $notification->message }}</p>

              <div class="notification-meta">
                <span class="notification-time">
                  <i class="far fa-clock me-1"></i>
                  {{ $notification->created_at->diffForHumans() }}
                </span>
                @if($notification->order)
                  <span class="notification-order">
                    <i class="fas fa-shopping-cart me-1"></i>
                    Orden #{{ $notification->order->order_number }}
                  </span>
                @endif
                @if($notification->user)
                  <span class="notification-user">
                    <i class="fas fa-user me-1"></i>
                    {{ $notification->user->name }}
                  </span>
                @endif
              </div>
            </div>

            <div class="notification-actions">
              @if($notification->url)
                <a href="{{ $notification->url }}" class="btn-action btn-action-primary" title="Ver detalles">
                  <i class="fas fa-external-link-alt"></i>
                  <span>Ver</span>
                </a>
              @endif

              <span class="text-muted small">
                <i class="fas fa-check-double"></i> Leída
              </span>
            </div>
          </div>
        @endforeach
      </div>
    @endif

    {{-- Paginación --}}
    <div class="mt-4">
      {{ $notifications->links() }}
    </div>
  @endif
</div>

{{-- Modal de confirmación para marcar todas como leídas --}}
<div class="modal fade" id="confirmReadAllModal" tabindex="-1" aria-labelledby="confirmReadAllModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmReadAllModalLabel">
          <i class="fas fa-check-double me-2"></i>Confirmar acción
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">¿Estás seguro de que deseas marcar todas las notificaciones como leídas?</p>
        <p class="text-muted small mb-0 mt-2">Se marcarán {{ $unreadCount }} notificaciones como leídas.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Cancelar
        </button>
        <form method="POST" action="{{ route('portal.admin.notifications.readAll') }}" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-check-double me-1"></i>Sí, marcar todas
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Actualizar visibilidad del botón de acciones masivas
function updateBulkActions() {
  const selectedCheckboxes = document.querySelectorAll('.notification-select:checked');
  const bulkActionsGroup = document.getElementById('bulkActionsGroup');

  if (selectedCheckboxes.length > 0) {
    bulkActionsGroup.style.display = 'block';
  } else {
    bulkActionsGroup.style.display = 'none';
  }
}

// Seleccionar/deseleccionar todas las notificaciones de una sección
function toggleAllCheckboxes(section, checked) {
  const checkboxes = document.querySelectorAll(`.${section}-notification`);
  checkboxes.forEach(checkbox => {
    checkbox.checked = checked;
  });
  updateBulkActions();
}

// Marcar notificaciones seleccionadas como leídas
function markSelectedAsRead() {
  const selectedCheckboxes = document.querySelectorAll('.notification-select:checked');
  const notificationIds = Array.from(selectedCheckboxes).map(cb => cb.value);

  if (notificationIds.length === 0) {
    return;
  }

  // Crear formulario y enviarlo
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '{{ route("portal.admin.notifications.readAll") }}';

  // Token CSRF
  const csrfInput = document.createElement('input');
  csrfInput.type = 'hidden';
  csrfInput.name = '_token';
  csrfInput.value = '{{ csrf_token() }}';
  form.appendChild(csrfInput);

  // IDs de notificaciones
  const idsInput = document.createElement('input');
  idsInput.type = 'hidden';
  idsInput.name = 'notification_ids';
  idsInput.value = JSON.stringify(notificationIds);
  form.appendChild(idsInput);

  document.body.appendChild(form);
  form.submit();
}
</script>

<style>
/* Section Headers */
.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0;
  border-bottom: 2px solid #e5e7eb;
}

.section-title {
  font-size: 18px;
  font-weight: 600;
  color: #1a1a1a;
  margin: 0;
  display: flex;
  align-items: center;
}

/* Header Icon */
.notification-icon-header {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: white;
  box-shadow: 0 8px 16px rgba(17, 93, 252, 0.25);
}

/* Stats Cards */
.stats-card {
  background: white;
  border-radius: 16px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  margin-bottom: 16px;
}

.stats-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.stats-icon {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  flex-shrink: 0;
}

.stats-card-primary .stats-icon {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  color: white;
}

.stats-card-warning .stats-icon {
  background: linear-gradient(135deg, #FFA726 0%, #FF9800 100%);
  color: white;
}

.stats-card-success .stats-icon {
  background: linear-gradient(135deg, #66BB6A 0%, #4CAF50 100%);
  color: white;
}

.stats-card-info .stats-icon {
  background: linear-gradient(135deg, #42A5F5 0%, #2196F3 100%);
  color: white;
}

.stats-content {
  flex: 1;
}

.stats-number {
  font-size: 28px;
  font-weight: 700;
  color: #1a1a1a;
  line-height: 1;
  margin-bottom: 4px;
}

.stats-label {
  font-size: 13px;
  color: #6c757d;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Empty State */
.empty-state {
  background: white;
  border-radius: 20px;
  padding: 80px 40px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.empty-state-icon {
  font-size: 80px;
  color: #e0e0e0;
  margin-bottom: 24px;
}

.empty-state-title {
  color: #424242;
  font-weight: 600;
  margin-bottom: 8px;
}

.empty-state-text {
  color: #9e9e9e;
  margin: 0;
}

/* Notifications Container */
.notifications-container {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

/* Notification Card */
.notification-card {
  background: white;
  border-radius: 16px;
  padding: 24px;
  display: flex;
  align-items: flex-start;
  gap: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

/* Notification Checkbox */
.notification-checkbox {
  flex-shrink: 0;
  padding-top: 4px;
}

.notification-checkbox .form-check-input {
  width: 20px;
  height: 20px;
  cursor: pointer;
  border: 2px solid #d1d5db;
  transition: all 0.2s ease;
}

.notification-checkbox .form-check-input:checked {
  background-color: #115DFC;
  border-color: #115DFC;
}

.notification-checkbox .form-check-input:hover {
  border-color: #115DFC;
}

/* Icon Muted for Read Notifications */
.notification-icon-muted {
  opacity: 0.6;
  filter: grayscale(40%);
}

.notification-card::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  width: 4px;
  background: #e0e0e0;
  transition: all 0.3s ease;
}

.notification-unread {
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.notification-unread::before {
  background: linear-gradient(180deg, #115DFC 0%, #3466ff 100%);
}

.notification-card:hover {
  transform: translateX(4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

/* Type-specific colors */
.notification-new-order .notification-icon {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
}

.notification-payment .notification-icon {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.notification-default .notification-icon {
  background: linear-gradient(135deg, #9E9E9E 0%, #757575 100%);
}

/* Icon Wrapper */
.notification-icon-wrapper {
  position: relative;
  flex-shrink: 0;
}

.notification-icon {
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  color: white;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.notification-pulse {
  position: absolute;
  top: 0;
  right: 0;
  width: 14px;
  height: 14px;
  background: #FF5252;
  border-radius: 50%;
  border: 3px solid white;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.15); opacity: 0.8; }
}

/* Content */
.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 8px;
}

.notification-title {
  font-size: 16px;
  font-weight: 600;
  color: #1a1a1a;
  margin: 0;
}

.badge-new {
  background: linear-gradient(135deg, #FF5252 0%, #FF1744 100%);
  color: white;
  font-size: 11px;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.notification-message {
  color: #616161;
  font-size: 14px;
  line-height: 1.6;
  margin: 0 0 12px 0;
}

.notification-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  font-size: 13px;
  color: #9e9e9e;
}

.notification-meta span {
  display: flex;
  align-items: center;
}

/* Actions */
.notification-actions {
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex-shrink: 0;
}

.btn-action {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border: none;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none;
  white-space: nowrap;
}

.btn-action i {
  font-size: 14px;
}

.btn-action-primary {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(17, 93, 252, 0.25);
}

.btn-action-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.35);
  color: white;
}

.btn-action-success {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  color: white;
  box-shadow: 0 2px 8px rgba(76, 175, 80, 0.25);
}

.btn-action-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.35);
}

/* Responsive */
@media (max-width: 768px) {
  .notification-card {
    flex-direction: column;
    gap: 16px;
  }

  .notification-actions {
    flex-direction: row;
    width: 100%;
  }

  .btn-action {
    flex: 1;
    justify-content: center;
  }

  .stats-card {
    margin-bottom: 12px;
  }
}
</style>
@endsection
