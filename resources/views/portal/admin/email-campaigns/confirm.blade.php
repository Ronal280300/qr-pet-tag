@extends('layouts.app')

@section('title', 'Confirmar Envío de Campaña')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Confirmar Envío de Campaña
      </h1>
      <p class="text-muted mb-0">Revisa cuidadosamente antes de enviar</p>
    </div>
    <div class="col-auto">
      <a href="{{ route('portal.admin.email-campaigns.show', $emailCampaign) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver
      </a>
    </div>
  </div>

  {{-- Alerta de confirmación --}}
  <div class="alert alert-warning border-warning">
    <h5 class="alert-heading">
      <i class="fas fa-exclamation-triangle me-2"></i>¡Atención!
    </h5>
    <p class="mb-0">
      Estás a punto de enviar esta campaña a <strong>{{ $recipients->count() }}</strong>
      {{ $recipients->count() == 1 ? 'destinatario' : 'destinatarios' }}.
      Esta acción no se puede deshacer.
    </p>
  </div>

  <div class="row">
    {{-- Resumen de la campaña --}}
    <div class="col-md-6 mb-4">
      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Resumen de la Campaña</h5>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tr>
              <th style="width: 180px;">Nombre:</th>
              <td>{{ $emailCampaign->name }}</td>
            </tr>
            <tr>
              <th>Plantilla:</th>
              <td>{{ $emailCampaign->template->name }}</td>
            </tr>
            <tr>
              <th>Asunto:</th>
              <td><strong>{{ $emailCampaign->template->subject }}</strong></td>
            </tr>
            <tr>
              <th>Filtro:</th>
              <td>
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
              </td>
            </tr>
            <tr>
              <th>Total Destinatarios:</th>
              <td><span class="badge bg-info fs-6">{{ $recipients->count() }}</span></td>
            </tr>
          </table>

          <div class="mt-3">
            <a href="{{ route('portal.admin.email-templates.preview', $emailCampaign->template) }}"
               target="_blank"
               class="btn btn-sm btn-outline-primary w-100">
              <i class="fas fa-eye me-1"></i>Ver Vista Previa de la Plantilla
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Lista de destinatarios --}}
    <div class="col-md-6 mb-4">
      <div class="card shadow">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
          <h5 class="mb-0">
            <i class="fas fa-users me-2"></i>Destinatarios
            (<span id="selectedCount">{{ $recipients->count() }}</span>/{{ $recipients->count() }})
          </h5>
          @if(!$recipients->isEmpty())
            <div>
              <button type="button" class="btn btn-sm btn-light" onclick="selectAllRecipients()">
                <i class="fas fa-check-double"></i> Todos
              </button>
              <button type="button" class="btn btn-sm btn-light" onclick="deselectAllRecipients()">
                <i class="fas fa-times"></i> Ninguno
              </button>
            </div>
          @endif
        </div>
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
          @if($recipients->isEmpty())
            <div class="text-center py-4">
              <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
              <p class="text-muted mb-0">No hay destinatarios que cumplan con los filtros seleccionados</p>
            </div>
          @else
            <ul class="list-group list-group-flush" id="recipientsList">
              @foreach($recipients as $user)
                <li class="list-group-item px-0">
                  <div class="d-flex justify-content-between align-items-start">
                    <div class="form-check flex-grow-1">
                      <input class="form-check-input recipient-checkbox"
                             type="checkbox"
                             name="selected_recipients[]"
                             value="{{ $user->id }}"
                             id="recipient_{{ $user->id }}"
                             checked
                             onchange="updateRecipientCount()">
                      <label class="form-check-label w-100" for="recipient_{{ $user->id }}">
                        <div class="d-flex justify-content-between">
                          <div>
                            <strong>{{ $user->name }}</strong>
                            <br><small class="text-muted">{{ $user->email }}</small>
                            @if($user->phone)
                              <br><small class="text-muted"><i class="fas fa-phone fa-xs"></i> {{ $user->phone }}</small>
                            @endif
                          </div>
                          <span class="badge bg-secondary h-25">{{ $user->pets->count() }} {{ $user->pets->count() == 1 ? 'mascota' : 'mascotas' }}</span>
                        </div>
                      </label>
                    </div>
                  </div>
                </li>
              @endforeach
            </ul>
            <div class="alert alert-warning mt-3 mb-0">
              <i class="fas fa-info-circle me-2"></i>
              <small><strong>Desmarca</strong> usuarios para <strong>excluirlos</strong> del envío. Solo se enviarán emails a los seleccionados.</small>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Botones de acción --}}
  @if($recipients->isNotEmpty())
    <div class="card shadow">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h5 class="mb-2">¿Estás seguro de enviar esta campaña?</h5>
            <p class="text-muted mb-0">
              Se enviarán <strong>{{ $recipients->count() }}</strong> emails con el asunto
              "<strong>{{ $emailCampaign->template->subject }}</strong>".
              Este proceso puede tardar varios minutos dependiendo del número de destinatarios.
            </p>
          </div>
          <div class="col-md-4 text-end">
            <form method="POST" action="{{ route('portal.admin.email-campaigns.send', $emailCampaign) }}"
                  id="sendCampaignForm"
                  onsubmit="return validateAndConfirmSend();">
              @csrf

              {{-- Los checkboxes de usuarios seleccionados se incluirán aquí --}}
              <div id="selectedRecipientsContainer"></div>

              <a href="{{ route('portal.admin.email-campaigns.show', $emailCampaign) }}"
                 class="btn btn-secondary me-2">
                <i class="fas fa-times me-1"></i>Cancelar
              </a>
              <button type="submit" class="btn btn-success btn-lg" id="sendBtn">
                <i class="fas fa-paper-plane me-2"></i>Confirmar y Enviar
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="card shadow">
      <div class="card-body text-center py-4">
        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
        <h5>No se puede enviar esta campaña</h5>
        <p class="text-muted">No hay destinatarios que cumplan con los filtros seleccionados.</p>
        <a href="{{ route('portal.admin.email-campaigns.show', $emailCampaign) }}" class="btn btn-primary">
          <i class="fas fa-arrow-left me-1"></i>Volver a la Campaña
        </a>
      </div>
    </div>
  @endif

  {{-- Información adicional --}}
  <div class="alert alert-info mt-4">
    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Información Importante</h6>
    <ul class="mb-0">
      <li>El envío se realizará de forma asíncrona en segundo plano</li>
      <li>Podrás ver el progreso en la página de detalles de la campaña</li>
      <li>Se crearán logs detallados de cada email enviado</li>
      <li>Los emails fallidos se registrarán con su respectivo error</li>
      <li><strong>Usuarios inactivos son excluidos automáticamente</strong></li>
    </ul>
  </div>
</div>

<script>
// Actualizar contador de destinatarios seleccionados
function updateRecipientCount() {
  const checkboxes = document.querySelectorAll('.recipient-checkbox');
  const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
  document.getElementById('selectedCount').textContent = selectedCount;

  // Actualizar mensaje de confirmación
  const sendBtn = document.getElementById('sendBtn');
  if (selectedCount === 0) {
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-ban me-2"></i>Sin destinatarios';
  } else {
    sendBtn.disabled = false;
    sendBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Confirmar y Enviar';
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

// Validar y confirmar envío
function validateAndConfirmSend() {
  const checkboxes = document.querySelectorAll('.recipient-checkbox');
  const selectedCount = Array.from(checkboxes).filter(cb => cb.checked).length;

  if (selectedCount === 0) {
    alert('❌ Debes seleccionar al menos un destinatario para enviar la campaña.');
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

  // Confirmación final
  return confirm(`⚠️ CONFIRMACIÓN FINAL\n\nSe enviarán ${selectedCount} emails.\n\n¿Continuar?`);
}

// Inicializar contador al cargar la página
document.addEventListener('DOMContentLoaded', function() {
  updateRecipientCount();
});
</script>
@endsection
