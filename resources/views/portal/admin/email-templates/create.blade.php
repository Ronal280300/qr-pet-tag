@extends('layouts.admin')

@section('title', 'Crear Plantilla de Email')
@section('page-title', 'Crear Plantilla de Email')

@section('content')
<div class="email-template-create-page">
  <div class="container-fluid py-4">
    {{-- Header --}}
    <div class="page-header">
      <div class="header-content">
        <div class="header-icon">
          <i class="fas fa-file-alt"></i>
        </div>
        <div class="header-text">
          <h1 class="header-title">Crear Plantilla de Email</h1>
          <p class="header-subtitle">Diseña una nueva plantilla para tus campañas</p>
        </div>
      </div>
      <div class="header-actions">
        <a href="{{ route('portal.admin.email-templates.index') }}" class="btn-back">
          <i class="fas fa-arrow-left"></i>
          <span>Volver</span>
        </a>
      </div>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
      <div class="alert-error">
        <div class="alert-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="alert-content">
          <strong class="alert-title">¡Error!</strong>
          <p class="alert-message">Por favor corrige los siguientes problemas:</p>
          <ul class="alert-list">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        <button type="button" class="alert-close" data-bs-dismiss="alert">
          <i class="fas fa-times"></i>
        </button>
      </div>
    @endif

    <div class="content-grid">
      {{-- Main Form --}}
      <div class="form-card">
        <form method="POST" action="{{ route('portal.admin.email-templates.store') }}">
          @csrf

          {{-- Nombre --}}
          <div class="form-group">
            <label for="name" class="form-label">
              <i class="fas fa-tag"></i>
              Nombre de la Plantilla
              <span class="required">*</span>
            </label>
            <input type="text"
                   class="form-input @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   placeholder="Ej: Recordatorio de Pago Mensual">
            @error('name')
              <div class="input-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
            <div class="form-help">Un nombre descriptivo para identificar la plantilla</div>
          </div>

          {{-- Categoría --}}
          <div class="form-group">
            <label for="category" class="form-label">
              <i class="fas fa-folder"></i>
              Categoría
              <span class="required">*</span>
            </label>
            <select class="form-select @error('category') is-invalid @enderror"
                    id="category"
                    name="category"
                    required>
              <option value="">Seleccionar categoría...</option>
              @foreach($categories as $key => $label)
                <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('category')
              <div class="input-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
          </div>

          {{-- Asunto --}}
          <div class="form-group">
            <label for="subject" class="form-label">
              <i class="fas fa-envelope"></i>
              Asunto del Email
              <span class="required">*</span>
            </label>
            <input type="text"
                   class="form-input @error('subject') is-invalid @enderror"
                   id="subject"
                   name="subject"
                   value="{{ old('subject') }}"
                   required
                   placeholder="Ej: Recordatorio: Tu plan vence pronto">
            @error('subject')
              <div class="input-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
            <div class="form-help">El asunto que verán los destinatarios en su bandeja de entrada</div>
          </div>

          {{-- Descripción --}}
          <div class="form-group">
            <label for="description" class="form-label">
              <i class="fas fa-align-left"></i>
              Descripción
              <span class="optional">(opcional)</span>
            </label>
            <textarea class="form-textarea @error('description') is-invalid @enderror"
                      id="description"
                      name="description"
                      rows="3"
                      placeholder="Descripción interna de la plantilla">{{ old('description') }}</textarea>
            @error('description')
              <div class="input-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
            <div class="form-help">Nota interna sobre el propósito de esta plantilla</div>
          </div>

          {{-- Contenido HTML --}}
          <div class="form-group">
            <label for="html_content" class="form-label">
              <i class="fas fa-code"></i>
              Contenido HTML
              <span class="required">*</span>
            </label>
            <textarea class="form-textarea form-code @error('html_content') is-invalid @enderror"
                      id="html_content"
                      name="html_content"
                      rows="15"
                      required
                      placeholder="Escribe o pega el HTML de tu plantilla...">{{ old('html_content') }}</textarea>
            @error('html_content')
              <div class="input-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
              </div>
            @enderror
            <div class="form-help">
              El HTML completo del email. Puedes usar variables como <code>@{{ name }}</code>, <code>@{{ email }}</code>, etc.
            </div>
          </div>

          {{-- Estado --}}
          <div class="form-group">
            <div class="switch-container">
              <input class="switch-input"
                     type="checkbox"
                     id="is_active"
                     name="is_active"
                     value="1"
                     {{ old('is_active', true) ? 'checked' : '' }}>
              <label class="switch-label" for="is_active">
                <span class="switch-slider"></span>
              </label>
              <div class="switch-text">
                <strong>Plantilla Activa</strong>
                <p>Solo las plantillas activas estarán disponibles para usar en campañas</p>
              </div>
            </div>
          </div>

          {{-- Botones --}}
          <div class="form-actions">
            <button type="submit" class="btn-submit">
              <i class="fas fa-save"></i>
              <span>Guardar Plantilla</span>
            </button>
            <a href="{{ route('portal.admin.email-templates.index') }}" class="btn-cancel">
              <i class="fas fa-times"></i>
              <span>Cancelar</span>
            </a>
          </div>
        </form>
      </div>

      {{-- Variables Help Panel --}}
      <div class="help-card">
        <div class="help-header">
          <div class="help-icon">
            <i class="fas fa-lightbulb"></i>
          </div>
          <h2 class="help-title">Variables Disponibles</h2>
        </div>

        <div class="help-body">
          <p class="help-intro">Puedes usar las siguientes variables en el contenido HTML. Serán reemplazadas automáticamente al enviar:</p>

          <div class="variables-section">
            <h3 class="variables-subtitle">
              <i class="fas fa-user"></i>
              Datos del Usuario
            </h3>
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

          <div class="variables-section">
            <h3 class="variables-subtitle">
              <i class="fas fa-globe"></i>
              Datos del Sistema
            </h3>
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

          <div class="example-box">
            <div class="example-header">
              <i class="fas fa-code"></i>
              <strong>Ejemplo de uso</strong>
            </div>
            <pre class="example-code"><code>&lt;p&gt;Hola @{{ name }} 👋&lt;/p&gt;
&lt;p&gt;Tu email es: @{{ email }}&lt;/p&gt;</code></pre>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.email-template-create-page {
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

/* ========== Error Alert ========== */
.alert-error {
  background: white;
  border-left: 4px solid #F44336;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: flex-start;
  gap: 16px;
  box-shadow: 0 2px 12px rgba(244, 67, 54, 0.15);
  margin-bottom: 24px;
}

.alert-icon {
  width: 44px;
  height: 44px;
  background: linear-gradient(135deg, #F44336 0%, #EF5350 100%);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 20px;
  flex-shrink: 0;
}

.alert-content {
  flex: 1;
}

.alert-title {
  display: block;
  color: #D32F2F;
  font-size: 15px;
  font-weight: 700;
  margin-bottom: 4px;
}

.alert-message {
  color: #424242;
  font-size: 14px;
  margin: 0 0 8px 0;
}

.alert-list {
  margin: 0;
  padding-left: 20px;
  color: #616161;
  font-size: 13px;
}

.alert-list li {
  margin-bottom: 4px;
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
  flex-shrink: 0;
}

.alert-close:hover {
  background: #eeeeee;
  color: #424242;
}

/* ========== Content Grid ========== */
.content-grid {
  display: grid;
  grid-template-columns: 1fr 400px;
  gap: 24px;
  align-items: start;
}

/* ========== Form Card ========== */
.form-card {
  background: white;
  border-radius: 16px;
  padding: 32px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.form-group {
  margin-bottom: 28px;
}

.form-group:last-of-type {
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

.optional {
  color: #9e9e9e;
  font-weight: 500;
  font-size: 13px;
}

.form-input,
.form-select,
.form-textarea {
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
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #115DFC;
  box-shadow: 0 0 0 3px rgba(17, 93, 252, 0.1);
}

.form-textarea {
  resize: vertical;
  line-height: 1.6;
}

.form-code {
  font-family: 'Courier New', monospace;
  font-size: 13px;
  background: #fafafa;
}

.form-input.is-invalid,
.form-select.is-invalid,
.form-textarea.is-invalid {
  border-color: #F44336;
}

.input-error {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #D32F2F;
  font-size: 13px;
  font-weight: 600;
  margin-top: 8px;
}

.input-error i {
  font-size: 12px;
}

.form-help {
  font-size: 13px;
  color: #757575;
  margin-top: 8px;
  line-height: 1.5;
}

.form-help code {
  background: #f5f5f5;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 12px;
  color: #115DFC;
  font-weight: 600;
}

/* ========== Switch ========== */
.switch-container {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  padding: 20px;
  background: #f8f9ff;
  border-radius: 12px;
  border: 2px solid #e3f2fd;
}

.switch-input {
  display: none;
}

.switch-label {
  position: relative;
  width: 56px;
  height: 32px;
  cursor: pointer;
  flex-shrink: 0;
}

.switch-slider {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #bdbdbd;
  border-radius: 32px;
  transition: all 0.3s ease;
}

.switch-slider::before {
  content: '';
  position: absolute;
  height: 24px;
  width: 24px;
  left: 4px;
  bottom: 4px;
  background: white;
  border-radius: 50%;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.switch-input:checked + .switch-label .switch-slider {
  background: linear-gradient(135deg, #4CAF50 0%, #66BB6A 100%);
}

.switch-input:checked + .switch-label .switch-slider::before {
  transform: translateX(24px);
}

.switch-text {
  flex: 1;
}

.switch-text strong {
  display: block;
  font-size: 15px;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 4px;
}

.switch-text p {
  font-size: 13px;
  color: #616161;
  margin: 0;
  line-height: 1.5;
}

/* ========== Form Actions ========== */
.form-actions {
  display: flex;
  gap: 12px;
  padding-top: 8px;
}

.btn-submit {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 32px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border: none;
  border-radius: 12px;
  color: white;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(17, 93, 252, 0.25);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(17, 93, 252, 0.35);
}

.btn-submit i {
  font-size: 16px;
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

/* ========== Help Card ========== */
.help-card {
  background: white;
  border-radius: 16px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  position: sticky;
  top: 24px;
  overflow: hidden;
}

.help-header {
  padding: 24px;
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  align-items: center;
  gap: 16px;
}

.help-icon {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, #FFC107 0%, #FFD54F 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 22px;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.help-title {
  font-size: 18px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0;
  line-height: 1.2;
}

.help-body {
  padding: 24px;
}

.help-intro {
  font-size: 14px;
  color: #616161;
  line-height: 1.6;
  margin: 0 0 24px 0;
}

.variables-section {
  margin-bottom: 24px;
}

.variables-section:last-of-type {
  margin-bottom: 24px;
}

.variables-subtitle {
  font-size: 14px;
  font-weight: 700;
  color: #1a1a1a;
  margin: 0 0 12px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}

.variables-subtitle i {
  width: 28px;
  height: 28px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 12px;
}

.variable-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.variable-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
  padding: 12px;
  background: #fafafa;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.variable-item:hover {
  background: #f0f0f0;
}

.variable-code {
  background: white;
  border: 2px solid #e0e0e0;
  padding: 6px 10px;
  border-radius: 6px;
  font-family: 'Courier New', monospace;
  font-size: 12px;
  font-weight: 600;
  color: #115DFC;
  display: inline-block;
  transition: all 0.2s ease;
}

.variable-item:hover .variable-code {
  border-color: #115DFC;
  background: #f8f9ff;
}

.variable-desc {
  font-size: 12px;
  color: #757575;
  padding-left: 10px;
}

.example-box {
  background: #fafafa;
  border: 2px solid #e0e0e0;
  border-radius: 12px;
  overflow: hidden;
}

.example-header {
  padding: 12px 16px;
  background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
  color: white;
  font-size: 13px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}

.example-header i {
  font-size: 14px;
}

.example-code {
  margin: 0;
  padding: 16px;
  background: white;
  border: none;
  font-family: 'Courier New', monospace;
  font-size: 12px;
  line-height: 1.6;
  color: #424242;
}

/* ========== Responsive ========== */
@media (max-width: 1200px) {
  .content-grid {
    grid-template-columns: 1fr;
  }

  .help-card {
    position: static;
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

  .form-actions {
    flex-direction: column;
  }

  .btn-submit,
  .btn-cancel {
    width: 100%;
    justify-content: center;
    padding: 14px 24px;
  }

  .switch-container {
    flex-direction: column;
    gap: 12px;
  }

  .help-body {
    padding: 20px;
  }
}

@media (max-width: 480px) {
  .header-icon {
    width: 48px;
    height: 48px;
    font-size: 20px;
  }

  .header-title {
    font-size: 20px;
  }

  .form-card {
    padding: 20px;
  }

  .form-input,
  .form-select,
  .form-textarea {
    font-size: 16px; /* Prevent zoom on iOS */
  }
}
</style>
@endsection
