@extends('layouts.app')

@section('title', 'Crear Campaña de Email')

@section('content')
<div class="email-campaign-create-page">
  <div class="container-fluid py-4">
    {{-- Header --}}
    <div class="page-header">
      <div class="header-content">
        <div class="header-icon">
          <i class="fas fa-paper-plane"></i>
        </div>
        <div class="header-text">
          <h1 class="header-title">Crear Campaña de Email</h1>
          <p class="header-subtitle">Configura una nueva campaña de email marketing</p>
        </div>
      </div>
      <div class="header-actions">
        <a href="{{ route('portal.admin.email-campaigns.index') }}" class="btn-back">
          <i class="fas fa-arrow-left"></i>
          <span>Volver</span>
        </a>
      </div>
    </div>

    <div class="form-card">
      <form method="POST" action="{{ route('portal.admin.email-campaigns.store') }}" id="campaignForm">
        @csrf

        {{-- Nombre de campaña --}}
        <div class="form-group">
          <label for="name" class="form-label">
            <i class="fas fa-tag"></i>
            Nombre de la Campaña
            <span class="required">*</span>
          </label>
          <input type="text" 
                 class="form-input" 
                 id="name" 
                 name="name" 
                 required
                 placeholder="Ej: Recordatorio de Pago Mayo 2025">
          <div class="form-help">Un nombre descriptivo para identificar esta campaña</div>
        </div>

        {{-- Plantilla --}}
        <div class="form-group">
          <label for="email_template_id" class="form-label">
            <i class="fas fa-file-alt"></i>
            Plantilla de Email
            <span class="required">*</span>
          </label>
          <select class="form-select" id="email_template_id" name="email_template_id" required>
            <option value="">Seleccionar plantilla...</option>
            @foreach($templates as $template)
              <option value="{{ $template->id }}">{{ $template->name }} - {{ $template->category }}</option>
            @endforeach
          </select>
          <div class="form-help">
            <a href="{{ route('portal.admin.email-templates.create') }}" target="_blank" class="link-create">
              <i class="fas fa-plus"></i>
              Crear nueva plantilla
            </a>
          </div>
        </div>

        {{-- Tipo de filtro --}}
        <div class="form-group">
          <label class="form-label">
            <i class="fas fa-filter"></i>
            Filtro de Destinatarios
            <span class="required">*</span>
          </label>
          <div class="info-note">
            <i class="fas fa-info-circle"></i>
            Nota: Siempre se excluyen usuarios inactivos automáticamente
          </div>

          <div class="filters-grid">
            {{-- Column 1 --}}
            <div class="filters-column">
              <div class="filter-section">
                <h3 class="filter-section-title">
                  <i class="fas fa-users"></i>
                  Filtros Generales
                </h3>
                <div class="filter-options">
                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="all" id="filter_all" checked>
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-blue">
                          <i class="fas fa-users"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Todos los Clientes Activos</strong>
                          <span class="filter-desc">Todos los usuarios activos registrados</span>
                        </div>
                      </div>
                    </div>
                  </label>

                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="has_pets">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-green">
                          <i class="fas fa-paw"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Con Mascotas</strong>
                          <span class="filter-desc">Clientes que tienen mascotas registradas</span>
                        </div>
                      </div>
                    </div>
                  </label>

                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="no_pets">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-orange">
                          <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Sin Mascotas</strong>
                          <span class="filter-desc">Clientes sin mascotas (posible abandono)</span>
                        </div>
                      </div>
                    </div>
                  </label>

                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="with_lost_pets">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-red">
                          <i class="fas fa-search"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Con Mascotas Perdidas</strong>
                          <span class="filter-desc">Clientes con mascotas marcadas como perdidas</span>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>

              <div class="filter-section">
                <h3 class="filter-section-title">
                  <i class="fas fa-chart-line"></i>
                  Actividad
                </h3>
                <div class="filter-options">
                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="no_scans" id="filter_no_scans">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-gray">
                          <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Sin Lecturas de QR</strong>
                          <span class="filter-desc">Sin escaneos en X días</span>
                        </div>
                      </div>
                      <div class="filter-config" id="no_scans_config" style="display:none;">
                        <label class="config-label">Días sin escanear:</label>
                        <input type="number" class="config-input" name="no_scans_days" value="30" min="1">
                      </div>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            {{-- Column 2 --}}
            <div class="filters-column">
              <div class="filter-section">
                <h3 class="filter-section-title">
                  <i class="fas fa-envelope-open-text"></i>
                  Email y Verificación
                </h3>
                <div class="filter-options">
                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="verified_email">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-green">
                          <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Email Verificado</strong>
                          <span class="filter-desc">Solo con email confirmado</span>
                        </div>
                      </div>
                    </div>
                  </label>

                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="unverified_email">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-orange">
                          <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Email No Verificado</strong>
                          <span class="filter-desc">Recordatorio para verificar email</span>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>

              <div class="filter-section">
                <h3 class="filter-section-title">
                  <i class="fas fa-shopping-cart"></i>
                  Pedidos y Pagos
                </h3>
                <div class="filter-options">
                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="payment_due" id="filter_payment">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-orange">
                          <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Pago Próximo a Vencer</strong>
                          <span class="filter-desc">Suscripción vence pronto</span>
                        </div>
                      </div>
                      <div class="filter-config" id="payment_due_config" style="display:none;">
                        <label class="config-label">Días antes del vencimiento:</label>
                        <input type="number" class="config-input" name="payment_due_days" value="5" min="1">
                      </div>
                    </div>
                  </label>

                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="has_orders">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-green">
                          <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Con Pedidos</strong>
                          <span class="filter-desc">Clientes que han realizado compras</span>
                        </div>
                      </div>
                    </div>
                  </label>

                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="no_orders">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-red">
                          <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Sin Pedidos</strong>
                          <span class="filter-desc">Registrados pero sin compras</span>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>

              <div class="filter-section">
                <h3 class="filter-section-title">
                  <i class="fas fa-hand-pointer"></i>
                  Selección Manual
                </h3>
                <div class="filter-options">
                  <label class="filter-option">
                    <input class="filter-radio" type="radio" name="filter_type" value="manual" id="filter_manual">
                    <div class="filter-content">
                      <div class="filter-header">
                        <div class="filter-icon filter-icon-purple">
                          <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <div class="filter-info">
                          <strong class="filter-title">Selección Manual</strong>
                          <span class="filter-desc">Elige usuarios específicos</span>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Preview de destinatarios --}}
        <div class="form-group">
          <button type="button" class="btn-preview" id="previewBtn">
            <i class="fas fa-eye"></i>
            <span>Previsualizar Destinatarios</span>
          </button>
          
          <div id="previewResult" class="preview-result" style="display:none;">
            <div class="preview-header">
              <i class="fas fa-users"></i>
              <strong>Total de destinatarios:</strong>
              <span class="preview-count" id="recipientCount">0</span>
            </div>
            <div id="recipientList" class="preview-list"></div>
          </div>
        </div>

        {{-- Acciones --}}
        <div class="form-actions">
          <button type="submit" name="send_now" value="0" class="btn-draft">
            <i class="fas fa-save"></i>
            <span>Guardar como Borrador</span>
          </button>
          <button type="submit" name="send_now" value="1" class="btn-send">
            <i class="fas fa-paper-plane"></i>
            <span>Crear y Enviar Ahora</span>
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

      let list = '<ul class="recipient-list">';
      data.recipients.slice(0, 10).forEach(r => {
        list += `<li class="recipient-item"><i class="fas fa-user"></i> ${r.name} <span class="recipient-email">(${r.email})</span></li>`;
      });
      if (data.count > 10) {
        list += `<li class="recipient-more">... y ${data.count - 10} más</li>`;
      }
      list += '</ul>';
      document.getElementById('recipientList').innerHTML = list;
    });
  });
});
</script>

<style>
.email-campaign-create-page {
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

/* ========== Form Card ========== */
.form-card {
  background: white;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.form-group {
  margin-bottom: 32px;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 10px;
}

.form-label i {
  color: #115DFC;
  font-size: 16px;
}

.required {
  color: #F44336;
  font-weight: 700;
}

.form-input,
.form-select {
  width: 100%;
  padding: 12px 16px;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  font-size: 14px;
  color: #424242;
  transition: all 0.3s ease;
  background: white;
}

.form-input:focus,
.form-select:focus {
  outline: none;
  border-color: #115DFC;
  box-shadow: 0 0 0 3px rgba(17, 93, 252, 0.1);
}

.form-help {
  font-size: 13px;
  color: #757575;
  margin-top: 8px;
  line-height: 1.5;
}

.link-create {
  color: #115DFC;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.2s ease;
}

.link-create:hover {
  color: #0047CC;
  text-decoration: underline;
}

.link-create i {
  margin-right: 4px;
}

/* ========== Info Note ========== */
.info-note {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 16px;
  background: #e3f2fd;
  border-left: 4px solid #2196F3;
  border-radius: 8px;
  font-size: 13px;
  color: #1976D2;
  margin-bottom: 20px;
}

.info-note i {
  font-size: 14px;
}

/* ========== Filters Grid ========== */
.filters-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
}

.filters-column {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.filter-section {
  background: #fafafa;
  border-radius: 12px;
  padding: 20px;
  border: 2px solid #f0f0f0;
}

.filter-section-title {
  font-size: 14px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 16px 0;
  display: flex;
  align-items: center;
  gap: 10px;
  padding-bottom: 12px;
  border-bottom: 2px solid #e0e0e0;
}

.filter-section-title i {
  color: #115DFC;
  font-size: 16px;
}

.filter-options {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.filter-option {
  background: white;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  padding: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
  display: block;
  margin: 0;
}

.filter-option:hover {
  border-color: #115DFC;
  transform: translateX(4px);
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.1);
}

.filter-radio {
  display: none;
}

.filter-radio:checked + .filter-content {
  border-left: 4px solid #115DFC;
  padding-left: 12px;
}

.filter-radio:checked ~ .filter-content .filter-icon {
  transform: scale(1.1);
}

.filter-content {
  transition: all 0.3s ease;
}

.filter-header {
  display: flex;
  align-items: center;
  gap: 12px;
}

.filter-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  color: white;
  flex-shrink: 0;
  transition: all 0.3s ease;
}

.filter-icon-blue {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
}

.filter-icon-green {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.filter-icon-orange {
  background: linear-gradient(135deg, #FF9800 0%, #FFB74D 100%);
}

.filter-icon-red {
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
}

.filter-icon-gray {
  background: linear-gradient(135deg, #757575 0%, #9E9E9E 100%);
}

.filter-icon-purple {
  background: linear-gradient(135deg, #9C27B0 0%, #BA68C8 100%);
}

.filter-info {
  flex: 1;
}

.filter-title {
  display: block;
  font-size: 14px;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 4px;
}

.filter-desc {
  display: block;
  font-size: 12px;
  color: #757575;
  line-height: 1.4;
}

/* Filter Config */
.filter-config {
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px solid #f0f0f0;
}

.config-label {
  display: block;
  font-size: 12px;
  font-weight: 600;
  color: #616161;
  margin-bottom: 6px;
}

.config-input {
  width: 100%;
  padding: 8px 12px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 13px;
  color: #424242;
  transition: all 0.3s ease;
}

.config-input:focus {
  outline: none;
  border-color: #115DFC;
  box-shadow: 0 0 0 3px rgba(17, 93, 252, 0.1);
}

/* ========== Preview ========== */
.btn-preview {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 12px 24px;
  background: linear-gradient(135deg, #2196F3 0%, #42A5F5 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(33, 150, 243, 0.25);
}

.btn-preview:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(33, 150, 243, 0.35);
}

.btn-preview i {
  font-size: 16px;
}

.preview-result {
  margin-top: 20px;
  background: white;
  border: 2px solid #e3f2fd;
  border-left: 4px solid #2196F3;
  border-radius: 12px;
  overflow: hidden;
}

.preview-header {
  padding: 16px 20px;
  background: #e3f2fd;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  color: #1976D2;
}

.preview-header i {
  font-size: 18px;
}

.preview-count {
  margin-left: auto;
  padding: 4px 12px;
  background: #115DFC;
  color: white;
  border-radius: 12px;
  font-weight: 700;
  font-size: 16px;
}

.preview-list {
  padding: 20px;
}

.recipient-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.recipient-item {
  padding: 10px 12px;
  margin-bottom: 8px;
  background: #fafafa;
  border-radius: 8px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  color: #424242;
}

.recipient-item i {
  color: #115DFC;
  font-size: 14px;
}

.recipient-email {
  color: #757575;
  font-size: 12px;
}

.recipient-more {
  padding: 10px 12px;
  text-align: center;
  color: #9e9e9e;
  font-size: 13px;
  font-style: italic;
}

/* ========== Form Actions ========== */
.form-actions {
  display: flex;
  gap: 12px;
  padding-top: 8px;
}

.btn-draft,
.btn-send {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 32px;
  border: none;
  border-radius: 12px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-draft {
  background: white;
  color: #616161;
  border: 2px solid #e0e0e0;
}

.btn-draft:hover {
  background: #f5f5f5;
  border-color: #bdbdbd;
  color: #424242;
}

.btn-send {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.25);
}

.btn-send:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.35);
}

.btn-draft i,
.btn-send i {
  font-size: 16px;
}

/* ========== Responsive ========== */
@media (max-width: 992px) {
  .filters-grid {
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
  }

  .btn-back {
    width: 100%;
    justify-content: center;
    padding: 14px 20px;
  }

  .form-card {
    padding: 24px;
  }

  .filter-section {
    padding: 16px;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn-draft,
  .btn-send {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .form-card {
    padding: 20px;
  }

  .filter-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .form-input,
  .form-select {
    font-size: 16px; /* Prevent zoom on iOS */
  }
}
</style>
@endsection
