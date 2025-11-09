@extends('layouts.app')

@section('title', 'Crear Campaña de Email')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-paper-plane me-2"></i>Crear Campaña de Email
      </h1>
    </div>
    <div class="col-auto">
      <a href="{{ route('portal.admin.email-campaigns.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver
      </a>
    </div>
  </div>

  <div class="card shadow">
    <div class="card-body">
      <form method="POST" action="{{ route('portal.admin.email-campaigns.store') }}" id="campaignForm">
        @csrf

        {{-- Nombre de campaña --}}
        <div class="mb-4">
          <label for="name" class="form-label fw-bold">Nombre de la Campaña*</label>
          <input type="text" class="form-control" id="name" name="name" required
                 placeholder="Ej: Recordatorio de Pago Mayo 2025">
        </div>

        {{-- Plantilla --}}
        <div class="mb-4">
          <label for="email_template_id" class="form-label fw-bold">Plantilla de Email*</label>
          <select class="form-select" id="email_template_id" name="email_template_id" required>
            <option value="">Seleccionar plantilla...</option>
            @foreach($templates as $template)
              <option value="{{ $template->id }}">{{ $template->name }} - {{ $template->category }}</option>
            @endforeach
          </select>
          <div class="form-text">
            <a href="{{ route('portal.admin.email-templates.create') }}" target="_blank">
              <i class="fas fa-plus"></i> Crear nueva plantilla
            </a>
          </div>
        </div>

        {{-- Tipo de filtro --}}
        <div class="mb-4">
          <label class="form-label fw-bold">Filtro de Destinatarios*</label>

          <div class="list-group">
            <label class="list-group-item">
              <input class="form-check-input me-2" type="radio" name="filter_type" value="all" id="filter_all" checked>
              <div>
                <strong>Todos los Clientes</strong>
                <div class="text-muted small">Enviar a todos los usuarios registrados</div>
              </div>
            </label>

            <label class="list-group-item">
              <input class="form-check-input me-2" type="radio" name="filter_type" value="no_scans" id="filter_no_scans">
              <div>
                <strong>Sin Lecturas de QR</strong>
                <div class="text-muted small">Clientes que no han escaneado su QR en X días</div>
                <div class="mt-2" id="no_scans_config" style="display:none;">
                  <label class="form-label small">Días sin escanear:</label>
                  <input type="number" class="form-control form-control-sm" name="no_scans_days" value="30" min="1">
                </div>
              </div>
            </label>

            <label class="list-group-item">
              <input class="form-check-input me-2" type="radio" name="filter_type" value="payment_due" id="filter_payment">
              <div>
                <strong>Pago Próximo a Vencer</strong>
                <div class="text-muted small">Clientes cuya suscripción vence pronto</div>
                <div class="mt-2" id="payment_due_config" style="display:none;">
                  <label class="form-label small">Días antes del vencimiento:</label>
                  <input type="number" class="form-control form-control-sm" name="payment_due_days" value="5" min="1">
                </div>
              </div>
            </label>
          </div>
        </div>

        {{-- Preview de destinatarios --}}
        <div class="mb-4">
          <button type="button" class="btn btn-info" id="previewBtn">
            <i class="fas fa-eye me-1"></i>Previsualizar Destinatarios
          </button>
          <div id="previewResult" class="mt-3" style="display:none;">
            <div class="alert alert-info">
              <strong>Total de destinatarios:</strong> <span id="recipientCount">0</span>
              <div id="recipientList" class="mt-2"></div>
            </div>
          </div>
        </div>

        {{-- Acciones --}}
        <div class="d-flex gap-2">
          <button type="submit" name="send_now" value="0" class="btn btn-secondary">
            <i class="fas fa-save me-1"></i>Guardar como Borrador
          </button>
          <button type="submit" name="send_now" value="1" class="btn btn-primary">
            <i class="fas fa-paper-plane me-1"></i>Crear y Enviar Ahora
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Mostrar/ocultar configuración de filtros
  document.querySelectorAll('input[name="filter_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
      document.getElementById('no_scans_config').style.display = 'none';
      document.getElementById('payment_due_config').style.display = 'none';

      if (this.value === 'no_scans') {
        document.getElementById('no_scans_config').style.display = 'block';
      } else if (this.value === 'payment_due') {
        document.getElementById('payment_due_config').style.display = 'block';
      }
    });
  });

  // Previsualizar destinatarios
  document.getElementById('previewBtn').addEventListener('click', function() {
    const filterType = document.querySelector('input[name="filter_type"]:checked').value;
    const noScansDays = document.querySelector('input[name="no_scans_days"]').value;
    const paymentDueDays = document.querySelector('input[name="payment_due_days"]').value;

    fetch('{{ route("portal.admin.email-campaigns.preview-recipients") }}?' + new URLSearchParams({
      filter_type: filterType,
      no_scans_days: noScansDays,
      payment_due_days: paymentDueDays
    }))
    .then(r => r.json())
    .then(data => {
      document.getElementById('recipientCount').textContent = data.count;
      document.getElementById('previewResult').style.display = 'block';

      let list = '<ul class="list-unstyled mb-0 mt-2 small">';
      data.recipients.slice(0, 10).forEach(r => {
        list += `<li><i class="fas fa-user text-muted me-1"></i> ${r.name} (${r.email})</li>`;
      });
      if (data.count > 10) {
        list += `<li class="text-muted">... y ${data.count - 10} más</li>`;
      }
      list += '</ul>';
      document.getElementById('recipientList').innerHTML = list;
    });
  });
});
</script>
@endsection
