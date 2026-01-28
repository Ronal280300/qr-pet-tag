@extends('layouts.app')

@section('title', 'Crear Campaña de Email')

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
.page-header .header-icon {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
}

.filter-option {
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 8px;
  transition: all 0.2s;
  border: 2px solid transparent;
}

.filter-option:hover {
  background: #f8f9fa;
}

.filter-option input[type="radio"]:checked ~ label {
  color: #667eea;
  font-weight: 600;
}

.filter-option input[type="radio"]:checked {
  background-color: #667eea;
  border-color: #667eea;
}

.filter-section {
  padding: 16px;
  background: #f8f9fa;
  border-radius: 12px;
}

#search_results_list {
  max-height: 300px;
  overflow-y: auto;
}

.search-user-item {
  cursor: pointer;
  transition: all 0.2s;
}

.search-user-item:hover {
  background: #e9ecef !important;
}

.selected-user-badge {
  background: #667eea;
  color: white;
  padding: 8px 12px;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
}

.selected-user-badge .remove-user {
  cursor: pointer;
  opacity: 0.8;
  transition: opacity 0.2s;
}

.selected-user-badge .remove-user:hover {
  opacity: 1;
}

.recipient-item {
  border-left: 4px solid #667eea;
  transition: all 0.2s;
}

.recipient-item:hover {
  background: #f8f9fa;
}

.recipient-item input[type="checkbox"]:checked ~ div {
  opacity: 1;
}

.recipient-item input[type="checkbox"]:not(:checked) ~ div {
  opacity: 0.6;
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
