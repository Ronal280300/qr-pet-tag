@extends('layouts.app')

@section('title', 'Confirmar Envío de Campaña')

@section('content')
<div class="email-campaign-confirm-page">
  <div class="container-fluid py-4">
    {{-- Header --}}
    <div class="page-header">
      <div class="header-content">
        <div class="header-icon header-warning">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="header-text">
          <h1 class="header-title">Confirmar Envío de Campaña</h1>
          <p class="header-subtitle">Revisa cuidadosamente antes de enviar</p>
        </div>
      </div>
      <div class="header-actions">
        <a href="{{ route('portal.admin.email-campaigns.show', $emailCampaign) }}" class="btn-back">
          <i class="fas fa-arrow-left"></i>
          <span>Volver</span>
        </a>
      </div>
    </div>

    {{-- Warning Alert --}}
    <div class="alert-warning-box">
      <div class="alert-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="alert-content">
        <h3 class="alert-title">¡Atención!</h3>
        <p class="alert-message">
          Estás a punto de enviar esta campaña a <strong>{{ $recipients->count() }}</strong>
          {{ $recipients->count() == 1 ? 'destinatario' : 'destinatarios' }}.
          Esta acción no se puede deshacer.
        </p>
      </div>
    </div>

    <div class="content-grid">
      {{-- Resumen de la campaña --}}
      <div class="summary-card">
        <div class="card-header">
          <i class="fas fa-info-circle"></i>
          <h2 class="card-title">Resumen de la Campaña</h2>
        </div>
        <div class="card-body">
          <div class="summary-list">
            <div class="summary-item">
              <span class="summary-label">Nombre:</span>
              <span class="summary-value">{{ $emailCampaign->name }}</span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Plantilla:</span>
              <span class="summary-value">{{ $emailCampaign->template->name }}</span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Asunto:</span>
              <span class="summary-value summary-subject">{{ $emailCampaign->template->subject }}</span>
            </div>
            <div class="summary-item">
              <span class="summary-label">Filtro:</span>
              <span class="summary-value">
                @switch($emailCampaign->filter_type)
                  @case('all')
                    Todos los Clientes
                    @break
                  @case('no_scans')
                    Sin Escaneos ({{ $emailCampaign->no_scans_days }} días)
                    @break
                  @case('payment_due')
                    Pago por Vencer ({{ $emailCampaign->payment_due_days }} días)
                    @break
                  @case('custom')
                    Filtro Personalizado
                    @break
                @endswitch
              </span>
            </div>
            <div class="summary-item summary-total">
              <span class="summary-label">Total Destinatarios:</span>
              <span class="summary-badge">{{ $recipients->count() }}</span>
            </div>
          </div>

          <a href="{{ route('portal.admin.email-templates.preview', $emailCampaign->template) }}"
             target="_blank"
             class="btn-preview-template">
            <i class="fas fa-eye"></i>
            <span>Ver Vista Previa de la Plantilla</span>
          </a>
        </div>
      </div>

      {{-- Lista de destinatarios --}}
      <div class="recipients-card">
        <div class="card-header">
          <div class="header-left">
            <i class="fas fa-users"></i>
            <h2 class="card-title">
              Destinatarios
              (<span id="selectedCount">{{ $recipients->count() }}</span>/{{ $recipients->count() }})
            </h2>
          </div>
          @if(!$recipients->isEmpty())
            <div class="header-actions-buttons">
              <button type="button" class="btn-select-all" onclick="selectAllRecipients()">
                <i class="fas fa-check-double"></i>
                <span>Todos</span>
              </button>
              <button type="button" class="btn-select-none" onclick="deselectAllRecipients()">
                <i class="fas fa-times"></i>
                <span>Ninguno</span>
              </button>
            </div>
          @endif
        </div>
        <div class="card-body recipients-list">
          @if($recipients->isEmpty())
            <div class="empty-recipients">
              <div class="empty-icon">
                <i class="fas fa-user-slash"></i>
              </div>
              <p class="empty-text">No hay destinatarios que cumplan con los filtros seleccionados</p>
            </div>
          @else
            <div class="recipients-container" id="recipientsList">
              @foreach($recipients as $user)
                <div class="recipient-item">
                  <div class="recipient-checkbox-wrapper">
                    <input class="recipient-checkbox"
                           type="checkbox"
                           name="selected_recipients[]"
                           value="{{ $user->id }}"
                           id="recipient_{{ $user->id }}"
                           checked
                           onchange="updateRecipientCount()">
                    <label class="recipient-label" for="recipient_{{ $user->id }}">
                      <div class="recipient-info">
                        <div class="recipient-main">
                          <strong class="recipient-name">{{ $user->name }}</strong>
                          <span class="recipient-email">{{ $user->email }}</span>
                          @if($user->phone)
                            <span class="recipient-phone">
                              <i class="fas fa-phone"></i>
                              {{ $user->phone }}
                            </span>
                          @endif
                        </div>
                        <div class="recipient-badge">
                          <i class="fas fa-paw"></i>
                          {{ $user->pets->count() }}
                        </div>
                      </div>
                    </label>
                  </div>
                </div>
              @endforeach
            </div>
            <div class="recipients-note">
              <i class="fas fa-info-circle"></i>
              <span><strong>Desmarca</strong> usuarios para <strong>excluirlos</strong> del envío. Solo se enviarán emails a los seleccionados.</span>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Botones de acción --}}
    @if($recipients->isNotEmpty())
      <div class="action-card">
        <div class="action-content">
          <div class="action-text">
            <h3 class="action-title">¿Estás seguro de enviar esta campaña?</h3>
            <p class="action-description">
              Se enviarán <strong>{{ $recipients->count() }}</strong> emails con el asunto
              "<strong>{{ $emailCampaign->template->subject }}</strong>".
              Este proceso puede tardar varios minutos dependiendo del número de destinatarios.
            </p>
          </div>
          <div class="action-buttons">
            <form method="POST" action="{{ route('portal.admin.email-campaigns.send', $emailCampaign) }}"
                  id="sendCampaignForm">
              @csrf
              <div id="selectedRecipientsContainer"></div>
              
              <a href="{{ route('portal.admin.email-campaigns.show', $emailCampaign) }}"
                 class="btn-cancel">
                <i class="fas fa-times"></i>
                <span>Cancelar</span>
              </a>
              <button type="button" class="btn-send" id="sendBtn" onclick="validateAndConfirmSend()">
                <i class="fas fa-paper-plane"></i>
                <span>Confirmar y Enviar</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    @else
      <div class="empty-action-card">
        <div class="empty-icon">
          <i class="fas fa-exclamation-circle"></i>
        </div>
        <h3 class="empty-title">No se puede enviar esta campaña</h3>
        <p class="empty-text">No hay destinatarios que cumplan con los filtros seleccionados.</p>
        <a href="{{ route('portal.admin.email-campaigns.show', $emailCampaign) }}" class="btn-back-empty">
          <i class="fas fa-arrow-left"></i>
          Volver a la Campaña
        </a>
      </div>
    @endif

    {{-- Información adicional --}}
    <div class="info-card">
      <div class="info-header">
        <i class="fas fa-info-circle"></i>
        <h3 class="info-title">Información Importante</h3>
      </div>
      <ul class="info-list">
        <li>El envío se realizará de forma asíncrona en segundo plano</li>
        <li>Podrás ver el progreso en la página de detalles de la campaña</li>
        <li>Se crearán logs detallados de cada email enviado</li>
        <li>Los emails fallidos se registrarán con su respectivo error</li>
        <li><strong>Usuarios inactivos son excluidos automáticamente</strong></li>
      </ul>
    </div>
  </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Actualizar contador de destinatarios seleccionados
function updateRecipientCount() {
  const checkboxes = document.querySelectorAll('.recipient-checkbox');
  const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
  document.getElementById('selectedCount').textContent = selectedCount;

  // Actualizar botón de envío
  const sendBtn = document.getElementById('sendBtn');
  if (selectedCount === 0) {
    sendBtn.disabled = true;
    sendBtn.classList.add('btn-disabled');
    sendBtn.innerHTML = '<i class="fas fa-ban"></i><span>Sin destinatarios</span>';
  } else {
    sendBtn.disabled = false;
    sendBtn.classList.remove('btn-disabled');
    sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i><span>Confirmar y Enviar</span>';
  }
}

// Seleccionar todos los destinatarios
function selectAllRecipients() {
  const checkboxes = document.querySelectorAll('.recipient-checkbox');
  checkboxes.forEach(cb => cb.checked = true);
  updateRecipientCount();
}

// Deseleccionar todos los destinatarios
function deselectAllRecipients() {
  const checkboxes = document.querySelectorAll('.recipient-checkbox');
  checkboxes.forEach(cb => cb.checked = false);
  updateRecipientCount();
}

// Validar y confirmar envío con SweetAlert2
function validateAndConfirmSend() {
  const checkboxes = document.querySelectorAll('.recipient-checkbox');
  const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;

  if (selectedCount === 0) {
    Swal.fire({
      icon: 'error',
      title: 'Sin destinatarios',
      text: 'Debes seleccionar al menos un destinatario para enviar la campaña.',
      confirmButtonText: 'Entendido',
      confirmButtonColor: '#115DFC',
      customClass: {
        popup: 'swal-popup',
        title: 'swal-title',
        confirmButton: 'swal-confirm'
      }
    });
    return false;
  }

  // Copiar los checkboxes seleccionados al formulario
  const container = document.getElementById('selectedRecipientsContainer');
  container.innerHTML = '';

  checkboxes.forEach(cb => {
    if (cb.checked) {
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'selected_recipients[]';
      input.value = cb.value;
      container.appendChild(input);
    }
  });

  // Confirmación final con SweetAlert2
  Swal.fire({
    icon: 'warning',
    title: '⚠️ Confirmación Final',
    html: `
      <div style="text-align: left; padding: 20px;">
        <p style="font-size: 15px; margin-bottom: 16px;">
          Se enviarán <strong style="color: #115DFC; font-size: 18px;">${selectedCount}</strong> emails.
        </p>
        <p style="font-size: 14px; color: #616161; margin-bottom: 0;">
          Esta acción no se puede deshacer y el proceso comenzará inmediatamente.
        </p>
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: '<i class="fas fa-paper-plane" style="margin-right: 8px;"></i>Sí, enviar ahora',
    cancelButtonText: '<i class="fas fa-times" style="margin-right: 8px;"></i>Cancelar',
    confirmButtonColor: '#4CAF50',
    cancelButtonColor: '#9E9E9E',
    reverseButtons: true,
    customClass: {
      popup: 'swal-popup',
      title: 'swal-title',
      htmlContainer: 'swal-html',
      confirmButton: 'swal-confirm',
      cancelButton: 'swal-cancel'
    }
  }).then((result) => {
    if (result.isConfirmed) {
      // Mostrar loading mientras se envía
      Swal.fire({
        title: 'Enviando campaña...',
        html: 'Por favor espera mientras se procesa el envío.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        },
        customClass: {
          popup: 'swal-popup',
          title: 'swal-title'
        }
      });
      
      // Submit del formulario
      document.getElementById('sendCampaignForm').submit();
    }
  });
}

// Inicializar contador al cargar la página
document.addEventListener('DOMContentLoaded', function() {
  updateRecipientCount();
});
</script>

<style>
.email-campaign-confirm-page {
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

.header-warning {
  background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
  box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
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

.btn-back i {
  font-size: 16px;
}

/* ========== Warning Alert ========== */
.alert-warning-box {
  background: white;
  border-left: 4px solid #FF9800;
  border-radius: 12px;
  padding: 20px 24px;
  display: flex;
  align-items: flex-start;
  gap: 20px;
  box-shadow: 0 2px 12px rgba(255, 152, 0, 0.15);
  margin-bottom: 32px;
}

.alert-icon {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #FF9800 0%, #FFB74D 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 22px;
  flex-shrink: 0;
}

.alert-content {
  flex: 1;
}

.alert-title {
  font-size: 18px;
  font-weight: 700;
  color: #E65100;
  margin: 0 0 8px 0;
}

.alert-message {
  font-size: 14px;
  color: #424242;
  margin: 0;
  line-height: 1.6;
}

.alert-message strong {
  color: #E65100;
}

/* ========== Content Grid ========== */
.content-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
  margin-bottom: 24px;
}

/* ========== Cards ========== */
.summary-card,
.recipients-card {
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
  justify-content: space-between;
  gap: 16px;
}

.card-header .header-left {
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

.header-actions-buttons {
  display: flex;
  gap: 8px;
}

.btn-select-all,
.btn-select-none {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  background: white;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  color: #616161;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-select-all:hover {
  background: #4CAF50;
  border-color: #4CAF50;
  color: white;
}

.btn-select-none:hover {
  background: #F44336;
  border-color: #F44336;
  color: white;
}

.card-body {
  padding: 24px;
}

/* ========== Summary ========== */
.summary-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 24px;
}

.summary-item {
  display: grid;
  grid-template-columns: 160px 1fr;
  gap: 16px;
  padding-bottom: 16px;
  border-bottom: 1px solid #f0f0f0;
}

.summary-item:last-child {
  border-bottom: none;
}

.summary-label {
  font-size: 13px;
  font-weight: 600;
  color: #757575;
}

.summary-value {
  font-size: 14px;
  color: #424242;
  font-weight: 500;
}

.summary-subject {
  font-weight: 700;
  color: #1a1a1a;
}

.summary-total {
  background: #f8f9ff;
  padding: 16px;
  border-radius: 12px;
  border: 2px solid #e3f2fd;
}

.summary-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 8px 20px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  color: white;
  border-radius: 12px;
  font-size: 20px;
  font-weight: 700;
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.3);
}

.btn-preview-template {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  width: 100%;
  padding: 12px 20px;
  background: white;
  border: 2px solid #115DFC;
  border-radius: 10px;
  color: #115DFC;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-preview-template:hover {
  background: #115DFC;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.25);
}

/* ========== Recipients ========== */
.recipients-list {
  padding: 0 !important;
}

.recipients-container {
  max-height: 400px;
  overflow-y: auto;
  padding: 24px;
  padding-bottom: 0;
}

.recipient-item {
  padding: 16px;
  background: #fafafa;
  border-radius: 10px;
  margin-bottom: 12px;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.recipient-item:hover {
  background: white;
  border-color: #115DFC;
  transform: translateX(4px);
}

.recipient-checkbox-wrapper {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.recipient-checkbox {
  width: 20px;
  height: 20px;
  margin-top: 2px;
  cursor: pointer;
  flex-shrink: 0;
}

.recipient-label {
  flex: 1;
  cursor: pointer;
}

.recipient-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.recipient-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.recipient-name {
  font-size: 14px;
  font-weight: 700;
  color: #1a1a1a;
}

.recipient-email {
  font-size: 12px;
  color: #757575;
}

.recipient-phone {
  font-size: 12px;
  color: #9e9e9e;
  display: flex;
  align-items: center;
  gap: 6px;
}

.recipient-phone i {
  font-size: 10px;
}

.recipient-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 12px;
  background: #e3f2fd;
  color: #1976D2;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 700;
  flex-shrink: 0;
}

.recipient-badge i {
  font-size: 14px;
}

.recipients-note {
  padding: 16px 24px;
  background: #fffbf0;
  border-top: 1px solid #f0f0f0;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: 13px;
  color: #616161;
  line-height: 1.6;
}

.recipients-note i {
  color: #FF9800;
  font-size: 16px;
  flex-shrink: 0;
  margin-top: 2px;
}

/* ========== Empty States ========== */
.empty-recipients {
  text-align: center;
  padding: 60px 20px;
}

.empty-icon {
  font-size: 64px;
  color: #e0e0e0;
  margin-bottom: 20px;
}

.empty-text {
  font-size: 14px;
  color: #9e9e9e;
  margin: 0;
}

/* ========== Action Card ========== */
.action-card {
  background: white;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  margin-bottom: 24px;
}

.action-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 32px;
}

.action-text {
  flex: 1;
}

.action-title {
  font-size: 20px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 12px 0;
}

.action-description {
  font-size: 14px;
  color: #616161;
  margin: 0;
  line-height: 1.6;
}

.action-description strong {
  color: #115DFC;
}

.action-buttons {
  display: flex;
  gap: 12px;
}

.btn-cancel {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 28px;
  background: white;
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  color: #616161;
  font-size: 15px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-cancel:hover {
  background: #f5f5f5;
  border-color: #bdbdbd;
  color: #424242;
}

.btn-send {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 32px;
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(76, 175, 80, 0.25);
}

.btn-send:hover:not(.btn-disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(76, 175, 80, 0.35);
}

.btn-send.btn-disabled {
  background: #bdbdbd;
  cursor: not-allowed;
  box-shadow: none;
}

.empty-action-card {
  background: white;
  border-radius: 16px;
  padding: 60px 40px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  text-align: center;
  margin-bottom: 24px;
}

.empty-action-card .empty-icon {
  font-size: 72px;
  color: #FF9800;
  margin-bottom: 24px;
}

.empty-title {
  font-size: 22px;
  font-weight: 700;
  color: #424242;
  margin: 0 0 12px 0;
}

.btn-back-empty {
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

.btn-back-empty:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.35);
  color: white;
}

/* ========== Info Card ========== */
.info-card {
  background: white;
  border: 2px solid #e3f2fd;
  border-left: 4px solid #2196F3;
  border-radius: 12px;
  padding: 24px;
}

.info-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
}

.info-header i {
  width: 32px;
  height: 32px;
  background: linear-gradient(135deg, #2196F3 0%, #42A5F5 100%);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 16px;
}

.info-title {
  font-size: 16px;
  font-weight: 700;
  color: #1976D2;
  margin: 0;
}

.info-list {
  margin: 0;
  padding-left: 24px;
  color: #616161;
  font-size: 14px;
  line-height: 1.8;
}

.info-list li {
  margin-bottom: 8px;
}

.info-list li:last-child {
  margin-bottom: 0;
}

/* ========== SweetAlert2 Custom Styles ========== */
.swal-popup {
  border-radius: 16px !important;
  padding: 32px !important;
}

.swal-title {
  font-size: 22px !important;
  font-weight: 700 !important;
  color: #1a1a1a !important;
}

.swal-html {
  font-size: 14px !important;
  color: #616161 !important;
}

.swal-confirm,
.swal-cancel {
  padding: 12px 28px !important;
  border-radius: 10px !important;
  font-size: 15px !important;
  font-weight: 600 !important;
  border: none !important;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
  transition: all 0.3s ease !important;
}

.swal-confirm:hover,
.swal-cancel:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 6px 16px rgba(0,0,0,0.2) !important;
}

/* ========== Responsive ========== */
@media (max-width: 992px) {
  .content-grid {
    grid-template-columns: 1fr;
  }

  .action-content {
    flex-direction: column;
    align-items: stretch;
  }

  .action-buttons {
    width: 100%;
    flex-direction: column;
  }

  .btn-cancel,
  .btn-send {
    width: 100%;
    justify-content: center;
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

  .btn-back {
    width: 100%;
    justify-content: center;
    padding: 14px 20px;
  }

  .alert-warning-box {
    flex-direction: column;
    gap: 16px;
  }

  .card-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .header-actions-buttons {
    width: 100%;
  }

  .btn-select-all,
  .btn-select-none {
    flex: 1;
    justify-content: center;
  }

  .summary-item {
    grid-template-columns: 1fr;
    gap: 8px;
  }

  .recipient-info {
    flex-direction: column;
    align-items: flex-start;
  }

  .action-card {
    padding: 24px;
  }

  .action-title {
    font-size: 18px;
  }
}

@media (max-width: 480px) {
  .empty-action-card {
    padding: 40px 24px;
  }

  .recipient-item {
    padding: 12px;
  }

  .recipient-name {
    font-size: 13px;
  }
}
</style>
@endsection
