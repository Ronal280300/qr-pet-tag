@extends('layouts.app')

@section('title', 'Plantillas de Email')

@section('content')
<div class="email-templates-page">
  <div class="container-fluid py-4">
    {{-- Header --}}
    <div class="page-header">
      <div class="header-content">
        <div class="header-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <div class="header-text">
          <h1 class="header-title">Plantillas de Email</h1>
          <p class="header-subtitle">Gestiona las plantillas para tus campañas de correo</p>
        </div>
      </div>
      <div class="header-actions">
        <a href="{{ route('portal.admin.email-templates.create') }}" class="btn-create">
          <i class="fas fa-plus"></i>
          <span>Nueva Plantilla</span>
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

    {{-- Templates Content --}}
    @if($templates->isEmpty())
      <div class="empty-state">
        <div class="empty-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <h3 class="empty-title">No hay plantillas creadas</h3>
        <p class="empty-text">Crea tu primera plantilla de email para usar en campañas</p>
        <a href="{{ route('portal.admin.email-templates.create') }}" class="btn-empty">
          <i class="fas fa-plus"></i>
          Crear Plantilla
        </a>
      </div>
    @else
      <div class="templates-grid">
        @foreach($templates as $template)
          <div class="template-card {{ $template->is_active ? 'template-active' : 'template-inactive' }}">
            {{-- Status Badge --}}
            <div class="template-status">
              @if($template->is_active)
                <span class="status-badge status-active">
                  <i class="fas fa-check-circle"></i>
                  Activa
                </span>
              @else
                <span class="status-badge status-inactive">
                  <i class="fas fa-pause-circle"></i>
                  Inactiva
                </span>
              @endif
            </div>

            {{-- Card Header --}}
            <div class="template-header">
              <div class="template-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="template-info">
                <h3 class="template-name">{{ $template->name }}</h3>
                @if($template->description)
                  <p class="template-description">{{ Str::limit($template->description, 80) }}</p>
                @endif
              </div>
            </div>

            {{-- Card Body --}}
            <div class="template-body">
              <div class="template-meta">
                <div class="meta-item">
                  <span class="meta-label">Categoría</span>
                  <span class="meta-value">
                    <i class="fas fa-tag"></i>
                    {{ ucfirst(str_replace('_', ' ', $template->category)) }}
                  </span>
                </div>

                <div class="meta-item">
                  <span class="meta-label">Asunto</span>
                  <span class="meta-value meta-subject">{{ Str::limit($template->subject, 50) }}</span>
                </div>

                <div class="meta-row">
                  <div class="meta-item">
                    <span class="meta-label">Campañas</span>
                    <span class="meta-value">
                      <i class="fas fa-paper-plane"></i>
                      {{ $template->campaigns()->count() }}
                    </span>
                  </div>

                  <div class="meta-item">
                    <span class="meta-label">Creada</span>
                    <span class="meta-value">
                      <i class="far fa-calendar"></i>
                      {{ $template->created_at->format('d/m/Y') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Card Actions --}}
            <div class="template-actions">
              <a href="{{ route('portal.admin.email-templates.preview', $template) }}"
                 class="action-btn action-preview"
                 target="_blank"
                 title="Previsualizar">
                <i class="fas fa-eye"></i>
                <span>Vista previa</span>
              </a>

              <a href="{{ route('portal.admin.email-templates.edit', $template) }}"
                 class="action-btn action-edit"
                 title="Editar">
                <i class="fas fa-edit"></i>
                <span>Editar</span>
              </a>

              <form method="POST" action="{{ route('portal.admin.email-templates.duplicate', $template) }}" style="display:inline;">
                @csrf
                <button type="submit" class="action-btn action-duplicate" title="Duplicar">
                  <i class="fas fa-copy"></i>
                  <span>Duplicar</span>
                </button>
              </form>

              <form method="POST" action="{{ route('portal.admin.email-templates.destroy', $template) }}"
                    class="delete-form"
                    style="display:inline;"
                    data-template-name="{{ $template->name }}"
                    data-campaigns-count="{{ $template->campaigns()->count() }}">
                @csrf
                @method('DELETE')
                <button type="button" class="action-btn action-delete" title="Eliminar" onclick="confirmDelete(this)">
                  <i class="fas fa-trash"></i>
                  <span>Eliminar</span>
                </button>
              </form>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      @if($templates->hasPages())
        <div class="pagination-wrapper">
          {{ $templates->links() }}
        </div>
      @endif
    @endif

    {{-- Variables Info Card --}}
    <div class="variables-card">
      <div class="variables-header">
        <div class="variables-title">
          <i class="fas fa-code"></i>
          Variables Disponibles
        </div>
        <span class="variables-badge">Para usar en plantillas</span>
      </div>
      <div class="variables-body">
        <p class="variables-intro">Puedes usar las siguientes variables en tus plantillas de email:</p>
        
        <div class="variables-grid">
          <div class="variable-group">
            <h4 class="variable-group-title">
              <i class="fas fa-user"></i>
              Datos del Usuario
            </h4>
            <div class="variable-list">
              <div class="variable-item">
                <code class="variable-code">@{{ name }}</code>
                <span class="variable-desc">Nombre del usuario</span>
              </div>
              <div class="variable-item">
                <code class="variable-code">@{{ email }}</code>
                <span class="variable-desc">Email del usuario</span>
              </div>
              <div class="variable-item">
                <code class="variable-code">@{{ phone }}</code>
                <span class="variable-desc">Teléfono del usuario</span>
              </div>
            </div>
          </div>

          <div class="variable-group">
            <h4 class="variable-group-title">
              <i class="fas fa-globe"></i>
              Datos del Sistema
            </h4>
            <div class="variable-list">
              <div class="variable-item">
                <code class="variable-code">@{{ year }}</code>
                <span class="variable-desc">Año actual</span>
              </div>
              <div class="variable-item">
                <code class="variable-code">@{{ site_name }}</code>
                <span class="variable-desc">Nombre del sitio</span>
              </div>
              <div class="variable-item">
                <code class="variable-code">@{{ site_url }}</code>
                <span class="variable-desc">URL del sitio</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(button) {
  const form = button.closest('.delete-form');
  const templateName = form.dataset.templateName;
  const campaignsCount = parseInt(form.dataset.campaignsCount);
  
  let warningMessage = '';
  if (campaignsCount > 0) {
    warningMessage = `<div style="background: #fff3e0; border-left: 4px solid #FF9800; padding: 16px; border-radius: 8px; margin-top: 16px; text-align: left;">
      <p style="margin: 0; font-size: 14px; color: #E65100;">
        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
        <strong>Advertencia:</strong> Esta plantilla está siendo usada en <strong>${campaignsCount}</strong> ${campaignsCount === 1 ? 'campaña' : 'campañas'}.
      </p>
    </div>`;
  }

  Swal.fire({
    icon: 'warning',
    title: '¿Eliminar plantilla?',
    html: `
      <div style="text-align: left; padding: 20px 0;">
        <p style="font-size: 15px; margin-bottom: 12px; color: #424242;">
          Estás a punto de eliminar la plantilla:
        </p>
        <p style="font-size: 16px; font-weight: 700; color: #115DFC; margin-bottom: 16px;">
          "${templateName}"
        </p>
        <p style="font-size: 14px; color: #616161; margin-bottom: 0;">
          Esta acción no se puede deshacer.
        </p>
        ${warningMessage}
      </div>
    `,
    showCancelButton: true,
    confirmButtonText: '<i class="fas fa-trash" style="margin-right: 8px;"></i>Sí, eliminar',
    cancelButtonText: '<i class="fas fa-times" style="margin-right: 8px;"></i>Cancelar',
    confirmButtonColor: '#F44336',
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
      // Mostrar loading mientras se procesa
      Swal.fire({
        title: 'Eliminando plantilla...',
        html: 'Por favor espera un momento.',
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
      form.submit();
    }
  });
}
</script>

<style>
.email-templates-page {
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

/* ========== Templates Grid ========== */
.templates-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
  gap: 24px;
  margin-bottom: 32px;
}

.template-card {
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

.template-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(0,0,0,0.1);
  border-color: #115DFC;
}

.template-active {
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

/* Status Badge */
.template-status {
  position: absolute;
  top: 16px;
  right: 16px;
  z-index: 1;
}

.status-badge {
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
}

.status-active {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
  color: white;
}

.status-inactive {
  background: linear-gradient(135deg, #9E9E9E 0%, #757575 100%);
  color: white;
}

.status-badge i {
  font-size: 11px;
}

/* Template Header */
.template-header {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  margin-bottom: 20px;
  padding-right: 80px;
}

.template-icon {
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

.template-card:hover .template-icon {
  transform: scale(1.1) rotate(5deg);
}

.template-info {
  flex: 1;
  min-width: 0;
}

.template-name {
  font-size: 18px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 8px 0;
  line-height: 1.3;
}

.template-description {
  font-size: 13px;
  color: #757575;
  line-height: 1.5;
  margin: 0;
}

/* Template Body */
.template-body {
  flex: 1;
  margin-bottom: 20px;
}

.template-meta {
  display: flex;
  flex-direction: column;
  gap: 14px;
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

.meta-subject {
  font-weight: 500;
  color: #616161;
  line-height: 1.5;
}

.meta-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

/* Template Actions */
.template-actions {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 8px;
  padding-top: 20px;
  border-top: 1px solid #f0f0f0;
}

.action-btn {
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

.action-preview {
  background: linear-gradient(135deg, #2196F3 0%, #42A5F5 100%);
  color: white;
}

.action-preview:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(33, 150, 243, 0.3);
  color: white;
}

.action-edit {
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  color: white;
}

.action-edit:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.3);
  color: white;
}

.action-duplicate {
  background: linear-gradient(135deg, #9C27B0 0%, #BA68C8 100%);
  color: white;
}

.action-duplicate:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(156, 39, 176, 0.3);
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
  margin-bottom: 32px;
}

/* ========== Variables Card ========== */
.variables-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  overflow: hidden;
}

.variables-header {
  padding: 20px 24px;
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  flex-wrap: wrap;
}

.variables-title {
  font-size: 18px;
  font-weight: 700;
  color: #1a1a1a;
  display: flex;
  align-items: center;
  gap: 12px;
}

.variables-title i {
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

.variables-badge {
  padding: 6px 16px;
  background: #115DFC;
  color: white;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.variables-body {
  padding: 24px;
}

.variables-intro {
  font-size: 14px;
  color: #616161;
  margin: 0 0 24px 0;
  line-height: 1.6;
}

.variables-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 24px;
}

.variable-group {
  background: #fafafa;
  border-radius: 12px;
  padding: 20px;
  border: 2px solid #f0f0f0;
  transition: all 0.3s ease;
}

.variable-group:hover {
  border-color: #115DFC;
  background: white;
}

.variable-group-title {
  font-size: 15px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 16px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.variable-group-title i {
  width: 32px;
  height: 32px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 14px;
}

.variable-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.variable-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.variable-code {
  background: white;
  border: 2px solid #e0e0e0;
  padding: 8px 12px;
  border-radius: 8px;
  font-family: 'Courier New', monospace;
  font-size: 13px;
  font-weight: 600;
  color: #115DFC;
  display: inline-block;
  transition: all 0.2s ease;
}

.variable-code:hover {
  background: #f8f9ff;
  border-color: #115DFC;
}

.variable-desc {
  font-size: 12px;
  color: #757575;
  padding-left: 12px;
}

/* ========== SweetAlert2 Custom Styles ========== */
.swal-popup {
  border-radius: 16px !important;
  padding: 32px !important;
}

.swal-title {
  font-size: 24px !important;
  font-weight: 700 !important;
  color: #1a1a1a !important;
  margin-bottom: 16px !important;
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
  .templates-grid {
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
  }

  .variables-grid {
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

  .btn-create {
    width: 100%;
    justify-content: center;
    padding: 14px 20px;
  }

  .templates-grid {
    grid-template-columns: 1fr;
    gap: 16px;
  }

  .template-card {
    padding: 20px;
  }

  .template-header {
    padding-right: 90px;
  }

  .template-icon {
    width: 48px;
    height: 48px;
    font-size: 20px;
  }

  .template-name {
    font-size: 16px;
  }

  .template-description {
    font-size: 12px;
  }

  .template-actions {
    grid-template-columns: 1fr 1fr;
    gap: 8px;
  }

  .action-btn span {
    display: none;
  }

  .action-btn {
    justify-content: center;
  }

  .variables-title {
    font-size: 16px;
  }

  .variables-badge {
    font-size: 11px;
    padding: 5px 12px;
  }
}

@media (max-width: 480px) {
  .empty-state {
    padding: 60px 24px;
  }

  .empty-icon {
    font-size: 60px;
  }

  .template-actions {
    grid-template-columns: 1fr;
  }

  .action-btn {
    padding: 12px 16px;
  }

  .meta-row {
    grid-template-columns: 1fr;
  }
}
</style>
@endsection
