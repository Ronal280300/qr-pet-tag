@extends('layouts.admin')

@section('title', 'Crear Campaña de Email')
@section('page-title', 'Crear Campaña de Email')

@section('content')
<div class="email-campaign-create-page">
  <div class="container-fluid py-4">
    {{-- Header --}}
    <div class="page-header mb-4">
      <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
          <div class="header-icon">
            <i class="fas fa-paper-plane"></i>
          </div>
          <div>
            <h1 class="h3 mb-1">Crear Campaña de Email</h1>
            <p class="text-muted mb-0">Configura y envía emails masivos a tus clientes</p>
          </div>
        </div>
        <a href="{{ route('portal.admin.email-campaigns.index') }}" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-2"></i>
          Volver
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <form method="POST" action="{{ route('portal.admin.email-campaigns.store') }}" id="campaignForm">
              @csrf

              {{-- Nombre de campaña --}}
              <div class="mb-4">
                <label for="name" class="form-label fw-bold">
                  <i class="fas fa-tag text-primary me-2"></i>
                  Nombre de la Campaña
                  <span class="text-danger">*</span>
                </label>
                <input type="text"
                       class="form-control form-control-lg"
                       id="name"
                       name="name"
                       required
                       placeholder="Ej: Recordatorio de Pago Mayo 2025">
                <small class="form-text text-muted">Un nombre descriptivo para identificar esta campaña internamente</small>
              </div>

              {{-- Plantilla --}}
              <div class="mb-4">
                <label for="email_template_id" class="form-label fw-bold">
                  <i class="fas fa-file-alt text-primary me-2"></i>
                  Plantilla de Email
                  <span class="text-danger">*</span>
                </label>
                <select class="form-select form-select-lg" id="email_template_id" name="email_template_id" required>
                  <option value="">Selecciona una plantilla...</option>
                  @foreach($templates as $template)
                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                  @endforeach
                </select>
                <small class="form-text text-muted">El contenido que se enviará a los destinatarios</small>
              </div>

              <hr class="my-4">

              {{-- FILTROS DE DESTINATARIOS --}}
              <h5 class="fw-bold mb-3">
                <i class="fas fa-users text-primary me-2"></i>
                Filtros de Destinatarios
              </h5>

              <div class="row g-3">
                {{-- Columna 1: Filtros Generales --}}
                <div class="col-md-6">
                  <div class="filter-section mb-3">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Generales</h6>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_all" value="all" checked>
                      <label class="form-check-label" for="filter_all">
                        <strong>Todos los clientes activos</strong>
                        <small class="d-block text-muted">Enviar a todos los usuarios registrados</small>
                      </label>
                    </div>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_has_pets" value="has_pets">
                      <label class="form-check-label" for="filter_has_pets">
                        <strong>Clientes con mascotas</strong>
                        <small class="d-block text-muted">Solo usuarios que tienen mascotas registradas</small>
                      </label>
                    </div>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_no_pets" value="no_pets">
                      <label class="form-check-label" for="filter_no_pets">
                        <strong>Clientes sin mascotas</strong>
                        <small class="d-block text-muted">Usuarios que aún no han registrado mascotas</small>
                      </label>
                    </div>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_lost_pets" value="with_lost_pets">
                      <label class="form-check-label" for="filter_lost_pets">
                        <strong>Clientes con mascotas perdidas</strong>
                        <small class="d-block text-muted">Usuarios con mascotas marcadas como perdidas</small>
                      </label>
                    </div>
                  </div>

                  <div class="filter-section mb-3">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Actividad</h6>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_no_scans" value="no_scans">
                      <label class="form-check-label" for="filter_no_scans">
                        <strong>Sin escaneos recientes</strong>
                        <small class="d-block text-muted">QR sin escanear en X días</small>
                      </label>
                    </div>
                    <div class="ms-4 mt-2" id="no_scans_config" style="display:none;">
                      <input type="number" class="form-control form-control-sm" name="no_scans_days" min="1" value="30" placeholder="Días">
                    </div>
                  </div>
                </div>

                {{-- Columna 2: Email, Pedidos, Manual --}}
                <div class="col-md-6">
                  <div class="filter-section mb-3">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Email y Verificación</h6>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_verified" value="verified_email">
                      <label class="form-check-label" for="filter_verified">
                        <strong>Email verificado</strong>
                        <small class="d-block text-muted">Solo usuarios con email confirmado</small>
                      </label>
                    </div>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_unverified" value="unverified_email">
                      <label class="form-check-label" for="filter_unverified">
                        <strong>Email no verificado</strong>
                        <small class="d-block text-muted">Usuarios que no han verificado su email</small>
                      </label>
                    </div>
                  </div>

                  <div class="filter-section mb-3">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Pedidos y Pagos</h6>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_payment_due" value="payment_due">
                      <label class="form-check-label" for="filter_payment_due">
                        <strong>Pago próximo a vencer</strong>
                        <small class="d-block text-muted">Suscripción vence en X días</small>
                      </label>
                    </div>
                    <div class="ms-4 mt-2" id="payment_due_config" style="display:none;">
                      <input type="number" class="form-control form-control-sm" name="payment_due_days" min="1" value="5" placeholder="Días">
                    </div>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_has_orders" value="has_orders">
                      <label class="form-check-label" for="filter_has_orders">
                        <strong>Clientes con pedidos</strong>
                        <small class="d-block text-muted">Usuarios que han realizado compras</small>
                      </label>
                    </div>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_no_orders" value="no_orders">
                      <label class="form-check-label" for="filter_no_orders">
                        <strong>Clientes sin pedidos</strong>
                        <small class="d-block text-muted">Usuarios que no han comprado</small>
                      </label>
                    </div>
                  </div>

                  <div class="filter-section">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Selección Manual</h6>

                    <div class="form-check filter-option">
                      <input class="form-check-input" type="radio" name="filter_type" id="filter_manual" value="manual">
                      <label class="form-check-label" for="filter_manual">
                        <strong>Selección manual de usuarios</strong>
                        <small class="d-block text-muted">Elige manualmente los destinatarios</small>
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              {{-- SELECCIÓN MANUAL --}}
              <div id="manual_selection_area" class="mt-4" style="display:none;">
                <div class="card bg-light border-0">
                  <div class="card-body">
                    <h6 class="fw-bold mb-3">
                      <i class="fas fa-search me-2"></i>
                      Buscar y Seleccionar Usuarios
                    </h6>

                    <div class="input-group mb-3">
                      <span class="input-group-text"><i class="fas fa-search"></i></span>
                      <input type="text"
                             class="form-control"
                             id="user_search_input"
                             placeholder="Buscar por nombre o email..."
                             autocomplete="off">
                    </div>

                    <div id="search_results" class="mb-3" style="display:none;">
                      <div class="list-group" id="search_results_list"></div>
                    </div>

                    <div id="selected_users_area">
                      <h6 class="fw-bold mb-2">Usuarios seleccionados: <span id="selected_count" class="badge bg-primary">0</span></h6>
                      <div id="selected_users_list" class="d-flex flex-wrap gap-2">
                        <p class="text-muted mb-0">Ningún usuario seleccionado. Usa el buscador arriba.</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <hr class="my-4">

              {{-- Botones de Acción --}}
              <div class="d-flex gap-3">
                <button type="button" class="btn btn-lg btn-primary" id="previewBtn">
                  <i class="fas fa-eye me-2"></i>
                  Previsualizar Destinatarios
                </button>
                <button type="submit" name="send_now" value="0" class="btn btn-lg btn-outline-secondary">
                  <i class="fas fa-save me-2"></i>
                  Guardar como Borrador
                </button>
                <button type="submit" name="send_now" value="1" class="btn btn-lg btn-success">
                  <i class="fas fa-paper-plane me-2"></i>
                  Crear y Enviar Ahora
                </button>
              </div>

              {{-- Hidden field for manual user IDs --}}
              <input type="hidden" name="manual_user_ids" id="manual_user_ids_input" value="[]">
            </form>
          </div>
        </div>
      </div>

      {{-- Panel lateral con preview --}}
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm sticky-top" style="top: 20px;">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
              <i class="fas fa-users me-2"></i>
              Destinatarios
            </h5>
          </div>
          <div class="card-body">
            <div id="recipients_preview">
              <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                <p class="mb-0">Selecciona un filtro y haz clic en "Previsualizar Destinatarios" para ver la lista</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* ========== Página Principal ========== */
.email-campaign-create-page {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
  padding-bottom: 40px;
}

/* ========== Header ========== */
.page-header {
  background: white;
  padding: 24px;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  margin-bottom: 32px;
}

.page-header .header-icon {
  width: 56px;
  height: 56px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 28px;
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.page-header h1 {
  font-size: 32px;
  font-weight: 800;
  color: #1a1a2e;
  margin: 0;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.page-header p {
  color: #6c757d;
  font-size: 15px;
  font-weight: 500;
}

/* ========== Cards ========== */
.card {
  border: none;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
}

.card-body {
  padding: 32px;
  background: white;
}

.card-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px 24px;
  border: none;
}

.card-header h5 {
  font-weight: 700;
  font-size: 20px;
  letter-spacing: 0.5px;
}

/* ========== Formularios ========== */
.form-label {
  font-weight: 700;
  color: #2d3436;
  font-size: 14px;
  margin-bottom: 10px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.form-control,
.form-select {
  border: 2px solid #e9ecef;
  border-radius: 12px;
  padding: 12px 16px;
  font-size: 15px;
  transition: all 0.3s ease;
  background: white;
}

.form-control:focus,
.form-select:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
  outline: none;
}

.form-control-lg {
  padding: 16px 20px;
  font-size: 16px;
  border-radius: 14px;
}

.form-select-lg {
  padding: 16px 20px;
  font-size: 16px;
  border-radius: 14px;
}

small.form-text {
  font-size: 13px;
  color: #6c757d;
  font-weight: 500;
  margin-top: 8px;
}

/* ========== Filtros ========== */
.filter-option {
  padding: 16px;
  border-radius: 12px;
  margin-bottom: 12px;
  transition: all 0.3s ease;
  border: 2px solid #e9ecef;
  background: white;
  cursor: pointer;
}

.filter-option:hover {
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  border-color: #667eea;
  transform: translateX(4px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.filter-option input[type="radio"] {
  width: 20px;
  height: 20px;
  cursor: pointer;
  accent-color: #667eea;
}

.filter-option input[type="radio"]:checked ~ label {
  color: #667eea;
  font-weight: 700;
}

.filter-option label {
  cursor: pointer;
  margin-left: 8px;
  transition: all 0.2s ease;
}

.filter-option strong {
  font-size: 15px;
  color: #2d3436;
}

.filter-option small {
  font-size: 13px;
  color: #636e72;
  font-weight: 500;
}

.filter-section {
  padding: 20px;
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  border-radius: 16px;
  border: 2px solid #e9ecef;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.filter-section h6 {
  font-size: 12px;
  font-weight: 800;
  color: #667eea;
  letter-spacing: 1px;
  margin-bottom: 16px;
}

/* ========== Búsqueda y Selección Manual ========== */
#manual_selection_area .card {
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  border: 2px solid #e9ecef;
  box-shadow: 0 4px 16px rgba(102, 126, 234, 0.1);
}

.input-group {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-radius: 12px;
  overflow: hidden;
}

.input-group-text {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  font-size: 16px;
  padding: 12px 16px;
}

#search_results_list {
  max-height: 350px;
  overflow-y: auto;
  border-radius: 12px;
}

#search_results_list::-webkit-scrollbar {
  width: 8px;
}

#search_results_list::-webkit-scrollbar-track {
  background: #f1f3f5;
  border-radius: 4px;
}

#search_results_list::-webkit-scrollbar-thumb {
  background: #667eea;
  border-radius: 4px;
}

.search-user-item {
  cursor: pointer;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  margin-bottom: 8px;
  border-radius: 12px;
}

.search-user-item:hover {
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%) !important;
  border-color: #667eea !important;
  transform: translateX(4px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.search-user-item strong {
  color: #2d3436;
  font-size: 15px;
}

.selected-user-badge {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 10px 16px;
  border-radius: 25px;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
}

.selected-user-badge:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.selected-user-badge .remove-user {
  cursor: pointer;
  opacity: 0.9;
  transition: all 0.2s;
  width: 20px;
  height: 20px;
  background: rgba(255, 255, 255, 0.2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
}

.selected-user-badge .remove-user:hover {
  opacity: 1;
  background: rgba(255, 255, 255, 0.3);
  transform: rotate(90deg);
}

#selected_count {
  font-size: 18px;
  padding: 8px 16px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* ========== Preview Recipients ========== */
.recipient-item {
  border-left: 4px solid #667eea;
  transition: all 0.3s ease;
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.recipient-item:hover {
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  transform: translateX(4px);
  box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
}

.recipient-item input[type="checkbox"] {
  width: 20px;
  height: 20px;
  cursor: pointer;
  accent-color: #667eea;
}

.recipient-item input[type="checkbox"]:checked ~ label {
  opacity: 1;
}

.recipient-item input[type="checkbox"]:not(:checked) ~ label {
  opacity: 0.5;
}

.recipient-item label {
  cursor: pointer;
  margin-bottom: 0;
}

.recipient-item strong {
  font-size: 15px;
  color: #2d3436;
}

/* ========== Botones ========== */
.btn {
  border-radius: 12px;
  padding: 12px 24px;
  font-weight: 700;
  font-size: 15px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
}

.btn-success {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  border: none;
}

.btn-success:hover {
  background: linear-gradient(135deg, #43A047 0%, #5CB860 100%);
}

.btn-outline-secondary {
  background: white;
  border: 2px solid #e9ecef;
  color: #6c757d;
}

.btn-outline-secondary:hover {
  background: #f8f9fa;
  border-color: #667eea;
  color: #667eea;
}

.btn-outline-primary,
.btn-outline-secondary.btn-group-sm {
  border: 2px solid #667eea;
  color: #667eea;
  background: white;
}

.btn-outline-primary:hover {
  background: #667eea;
  color: white;
}

.btn-lg {
  padding: 16px 32px;
  font-size: 16px;
  border-radius: 14px;
}

.btn-group-sm .btn {
  font-size: 13px;
  padding: 8px 16px;
  font-weight: 600;
}

/* ========== Iconos ========== */
.fas,
.far {
  transition: transform 0.2s ease;
}

.btn:hover .fas,
.btn:hover .far {
  transform: scale(1.1);
}

/* ========== Sticky Sidebar ========== */
.sticky-top {
  position: sticky;
  top: 20px;
  z-index: 100;
}

/* ========== Scrollbar personalizado ========== */
::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background: #f1f3f5;
  border-radius: 5px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
}

/* ========== Animations ========== */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.card,
.page-header {
  animation: fadeInUp 0.5s ease;
}

/* ========== Responsive ========== */
@media (max-width: 992px) {
  .card-body {
    padding: 24px;
  }

  .btn-lg {
    padding: 14px 24px;
    font-size: 15px;
  }
}

@media (max-width: 768px) {
  .page-header h1 {
    font-size: 26px;
  }

  .filter-option {
    padding: 12px;
  }

  .btn {
    width: 100%;
    margin-bottom: 12px;
  }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Variables globales
  let selectedManualUsers = [];
  let recipientsData = [];

  // Referencias a elementos
  const filterRadios = document.querySelectorAll('input[name="filter_type"]');
  const noScansConfig = document.getElementById('no_scans_config');
  const paymentDueConfig = document.getElementById('payment_due_config');
  const manualSelectionArea = document.getElementById('manual_selection_area');
  const userSearchInput = document.getElementById('user_search_input');
  const searchResults = document.getElementById('search_results');
  const searchResultsList = document.getElementById('search_results_list');
  const selectedUsersList = document.getElementById('selected_users_list');
  const selectedCount = document.getElementById('selected_count');
  const manualUserIdsInput = document.getElementById('manual_user_ids_input');
  const previewBtn = document.getElementById('previewBtn');
  const recipientsPreview = document.getElementById('recipients_preview');

  // Mostrar/ocultar configuraciones según filtro
  filterRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      // Ocultar todas las configs
      noScansConfig.style.display = 'none';
      paymentDueConfig.style.display = 'none';
      manualSelectionArea.style.display = 'none';

      // Mostrar config específica
      if (this.value === 'no_scans') {
        noScansConfig.style.display = 'block';
      } else if (this.value === 'payment_due') {
        paymentDueConfig.style.display = 'block';
      } else if (this.value === 'manual') {
        manualSelectionArea.style.display = 'block';
      }
    });
  });

  // Búsqueda de usuarios (debounced)
  let searchTimeout;
  userSearchInput.addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();

    if (query.length < 2) {
      searchResults.style.display = 'none';
      return;
    }

    searchTimeout = setTimeout(() => {
      searchUsers(query);
    }, 300);
  });

  // Función para buscar usuarios
  function searchUsers(query) {
    fetch(`{{ route('portal.admin.email-campaigns.search-users') }}?q=${encodeURIComponent(query)}`)
      .then(res => res.json())
      .then(data => {
        displaySearchResults(data.users);
      })
      .catch(err => {
        console.error('Error searching users:', err);
      });
  }

  // Mostrar resultados de búsqueda
  function displaySearchResults(users) {
    if (users.length === 0) {
      searchResultsList.innerHTML = '<div class="list-group-item text-muted">No se encontraron usuarios</div>';
      searchResults.style.display = 'block';
      return;
    }

    searchResultsList.innerHTML = users.map(user => {
      const isSelected = selectedManualUsers.includes(user.id);
      return `
        <a href="javascript:void(0)"
           class="list-group-item list-group-item-action search-user-item d-flex justify-content-between align-items-center ${isSelected ? 'bg-light' : ''}"
           data-user-id="${user.id}"
           data-user-name="${user.name}"
           data-user-email="${user.email}">
          <div>
            <strong>${user.name}</strong>
            <br>
            <small class="text-muted">${user.email}</small>
            <br>
            <small class="text-info"><i class="fas fa-paw me-1"></i>${user.pets_count} mascota(s)</small>
          </div>
          ${isSelected ? '<span class="badge bg-success">Seleccionado</span>' : '<i class="fas fa-plus-circle text-primary"></i>'}
        </a>
      `;
    }).join('');

    searchResults.style.display = 'block';

    // Agregar eventos a los resultados
    document.querySelectorAll('.search-user-item').forEach(item => {
      item.addEventListener('click', function() {
        const userId = parseInt(this.dataset.userId);
        const userName = this.dataset.userName;
        const userEmail = this.dataset.userEmail;

        if (selectedManualUsers.includes(userId)) {
          removeUser(userId);
        } else {
          addUser(userId, userName, userEmail);
        }

        // Actualizar display
        displaySearchResults(users);
      });
    });
  }

  // Agregar usuario a la selección
  function addUser(id, name, email) {
    if (!selectedManualUsers.includes(id)) {
      selectedManualUsers.push(id);
      updateSelectedUsersDisplay();
      updateManualUserIdsInput();
    }
  }

  // Remover usuario de la selección
  function removeUser(id) {
    selectedManualUsers = selectedManualUsers.filter(userId => userId !== id);
    updateSelectedUsersDisplay();
    updateManualUserIdsInput();
  }

  // Actualizar display de usuarios seleccionados
  function updateSelectedUsersDisplay() {
    selectedCount.textContent = selectedManualUsers.length;

    if (selectedManualUsers.length === 0) {
      selectedUsersList.innerHTML = '<p class="text-muted mb-0">Ningún usuario seleccionado. Usa el buscador arriba.</p>';
      return;
    }

    // Obtener nombres de usuarios seleccionados
    const badges = selectedManualUsers.map((userId, index) => {
      // Buscar info del usuario en el DOM o en resultados anteriores
      const userItem = document.querySelector(`.search-user-item[data-user-id="${userId}"]`);
      const userName = userItem ? userItem.dataset.userName : `Usuario #${userId}`;

      return `
        <span class="selected-user-badge">
          <span>${userName}</span>
          <i class="fas fa-times remove-user" data-user-id="${userId}"></i>
        </span>
      `;
    }).join('');

    selectedUsersList.innerHTML = badges;

    // Agregar eventos de remover
    document.querySelectorAll('.remove-user').forEach(btn => {
      btn.addEventListener('click', function() {
        const userId = parseInt(this.dataset.userId);
        removeUser(userId);
      });
    });
  }

  // Actualizar input hidden con IDs
  function updateManualUserIdsInput() {
    manualUserIdsInput.value = JSON.stringify(selectedManualUsers);
  }

  // PREVIEW DE DESTINATARIOS
  previewBtn.addEventListener('click', function() {
    const filterType = document.querySelector('input[name="filter_type"]:checked').value;
    const noScansDays = document.querySelector('input[name="no_scans_days"]')?.value || 30;
    const paymentDueDays = document.querySelector('input[name="payment_due_days"]')?.value || 5;

    // Mostrar loading
    recipientsPreview.innerHTML = `
      <div class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <p class="mt-3 text-muted">Obteniendo destinatarios...</p>
      </div>
    `;

    // Preparar parámetros
    const params = new URLSearchParams({
      filter_type: filterType,
      no_scans_days: noScansDays,
      payment_due_days: paymentDueDays
    });

    // Si es manual, agregar los IDs
    if (filterType === 'manual') {
      params.append('manual_user_ids', JSON.stringify(selectedManualUsers));
    }

    // Hacer fetch
    fetch(`{{ route('portal.admin.email-campaigns.preview-recipients') }}?${params}`)
      .then(res => res.json())
      .then(data => {
        recipientsData = data.recipients;
        displayRecipients(data);
      })
      .catch(err => {
        console.error('Error loading recipients:', err);
        recipientsPreview.innerHTML = `
          <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Error al cargar destinatarios. Intenta nuevamente.
          </div>
        `;
      });
  });

  // Mostrar destinatarios con checkboxes
  function displayRecipients(data) {
    if (data.count === 0) {
      recipientsPreview.innerHTML = `
        <div class="alert alert-warning">
          <i class="fas fa-info-circle me-2"></i>
          No se encontraron destinatarios con este filtro.
        </div>
      `;
      return;
    }

    let html = `
      <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="mb-0">
            <span class="badge bg-primary fs-6">${data.count}</span>
            <span class="ms-2">destinatario(s)</span>
          </h6>
          <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-primary" id="selectAllRecipients">
              <i class="fas fa-check-square me-1"></i>
              Todos
            </button>
            <button type="button" class="btn btn-outline-secondary" id="deselectAllRecipients">
              <i class="fas fa-square me-1"></i>
              Ninguno
            </button>
          </div>
        </div>
        <small class="text-muted d-block mb-3">
          <i class="fas fa-info-circle me-1"></i>
          Desmarca los usuarios a los que NO quieras enviar el email
        </small>
      </div>
      <div class="recipients-list">
    `;

    data.recipients.forEach(user => {
      html += `
        <div class="recipient-item p-3 mb-2 border rounded">
          <div class="form-check">
            <input class="form-check-input recipient-checkbox"
                   type="checkbox"
                   value="${user.id}"
                   id="recipient_${user.id}"
                   checked>
            <label class="form-check-label d-flex justify-content-between align-items-start w-100" for="recipient_${user.id}">
              <div>
                <strong>${user.name}</strong>
                <br>
                <small class="text-muted">${user.email}</small>
                <br>
                <small class="text-info"><i class="fas fa-paw me-1"></i>${user.pets_count} mascota(s)</small>
              </div>
            </label>
          </div>
        </div>
      `;
    });

    html += '</div>';
    recipientsPreview.innerHTML = html;

    // Eventos para seleccionar/deseleccionar todos
    document.getElementById('selectAllRecipients').addEventListener('click', function() {
      document.querySelectorAll('.recipient-checkbox').forEach(cb => cb.checked = true);
    });

    document.getElementById('deselectAllRecipients').addEventListener('click', function() {
      document.querySelectorAll('.recipient-checkbox').forEach(cb => cb.checked = false);
    });
  }

  // Antes de enviar el formulario, agregar selected_recipients
  document.getElementById('campaignForm').addEventListener('submit', function(e) {
    // Si hay preview activo, obtener destinatarios seleccionados
    const checkedRecipients = Array.from(document.querySelectorAll('.recipient-checkbox:checked'))
      .map(cb => cb.value);

    if (checkedRecipients.length > 0) {
      // Crear input hidden con los IDs seleccionados
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'selected_recipients';
      input.value = JSON.stringify(checkedRecipients);
      this.appendChild(input);
    }
  });
});
</script>
@endpush
@endsection
