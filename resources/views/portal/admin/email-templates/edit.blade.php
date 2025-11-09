@extends('layouts.app')

@section('title', 'Editar Plantilla de Email')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-edit me-2"></i>Editar Plantilla de Email
      </h1>
      <p class="text-muted mb-0">{{ $emailTemplate->name }}</p>
    </div>
    <div class="col-auto">
      <a href="{{ route('portal.admin.email-templates.preview', $emailTemplate) }}"
         class="btn btn-info"
         target="_blank">
        <i class="fas fa-eye me-1"></i>Vista Previa
      </a>
      <a href="{{ route('portal.admin.email-templates.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Volver
      </a>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <strong>¡Error!</strong> Por favor corrige los siguientes problemas:
      <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card shadow">
    <div class="card-body">
      <form method="POST" action="{{ route('portal.admin.email-templates.update', $emailTemplate) }}">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="mb-4">
          <label for="name" class="form-label fw-bold">
            Nombre de la Plantilla*
          </label>
          <input type="text"
                 class="form-control @error('name') is-invalid @enderror"
                 id="name"
                 name="name"
                 value="{{ old('name', $emailTemplate->name) }}"
                 required
                 placeholder="Ej: Recordatorio de Pago Mensual">
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div class="form-text">Un nombre descriptivo para identificar la plantilla</div>
        </div>

        {{-- Categoría --}}
        <div class="mb-4">
          <label for="category" class="form-label fw-bold">
            Categoría*
          </label>
          <select class="form-select @error('category') is-invalid @enderror"
                  id="category"
                  name="category"
                  required>
            <option value="">Seleccionar categoría...</option>
            @foreach($categories as $key => $label)
              <option value="{{ $key }}"
                      {{ old('category', $emailTemplate->category) == $key ? 'selected' : '' }}>
                {{ $label }}
              </option>
            @endforeach
          </select>
          @error('category')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Asunto --}}
        <div class="mb-4">
          <label for="subject" class="form-label fw-bold">
            Asunto del Email*
          </label>
          <input type="text"
                 class="form-control @error('subject') is-invalid @enderror"
                 id="subject"
                 name="subject"
                 value="{{ old('subject', $emailTemplate->subject) }}"
                 required
                 placeholder="Ej: Recordatorio: Tu plan vence pronto">
          @error('subject')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div class="form-text">El asunto que verán los destinatarios en su bandeja de entrada</div>
        </div>

        {{-- Descripción --}}
        <div class="mb-4">
          <label for="description" class="form-label fw-bold">
            Descripción (opcional)
          </label>
          <textarea class="form-control @error('description') is-invalid @enderror"
                    id="description"
                    name="description"
                    rows="2"
                    placeholder="Descripción interna de la plantilla">{{ old('description', $emailTemplate->description) }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div class="form-text">Nota interna sobre el propósito de esta plantilla</div>
        </div>

        {{-- Contenido HTML --}}
        <div class="mb-4">
          <label for="html_content" class="form-label fw-bold">
            Contenido HTML*
          </label>
          <textarea class="form-control @error('html_content') is-invalid @enderror"
                    id="html_content"
                    name="html_content"
                    rows="15"
                    required
                    style="font-family: monospace;">{{ old('html_content', $emailTemplate->html_content) }}</textarea>
          @error('html_content')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <div class="form-text">
            El HTML completo del email. Puedes usar variables como <code>@{{ name }}</code>, <code>@{{ email }}</code>, etc.
          </div>
        </div>

        {{-- Estado --}}
        <div class="mb-4">
          <div class="form-check form-switch">
            <input class="form-check-input"
                   type="checkbox"
                   id="is_active"
                   name="is_active"
                   value="1"
                   {{ old('is_active', $emailTemplate->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
              <strong>Plantilla Activa</strong>
              <div class="text-muted small">Solo las plantillas activas estarán disponibles para usar en campañas</div>
            </label>
          </div>
        </div>

        {{-- Información adicional --}}
        @if($emailTemplate->campaigns()->count() > 0)
          <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Esta plantilla está siendo usada en <strong>{{ $emailTemplate->campaigns()->count() }}</strong>
            {{ $emailTemplate->campaigns()->count() == 1 ? 'campaña' : 'campañas' }}.
          </div>
        @endif

        {{-- Botones --}}
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Guardar Cambios
          </button>
          <a href="{{ route('portal.admin.email-templates.index') }}" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i>Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>

  {{-- Panel de ayuda con variables --}}
  <div class="card shadow mt-4">
    <div class="card-header bg-info text-white">
      <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Variables Disponibles</h6>
    </div>
    <div class="card-body">
      <p class="mb-3">Puedes usar las siguientes variables en el contenido HTML. Serán reemplazadas automáticamente al enviar:</p>
      <div class="row">
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li class="mb-2">
              <code>@{{ name }}</code>
              <span class="text-muted">- Nombre del usuario</span>
            </li>
            <li class="mb-2">
              <code>@{{ email }}</code>
              <span class="text-muted">- Email del usuario</span>
            </li>
            <li class="mb-2">
              <code>@{{ phone }}</code>
              <span class="text-muted">- Teléfono del usuario</span>
            </li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li class="mb-2">
              <code>@{{ year }}</code>
              <span class="text-muted">- Año actual</span>
            </li>
            <li class="mb-2">
              <code>@{{ site_name }}</code>
              <span class="text-muted">- Nombre del sitio</span>
            </li>
            <li class="mb-2">
              <code>@{{ site_url }}</code>
              <span class="text-muted">- URL del sitio</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="alert alert-light mt-3 mb-0">
        <strong>Ejemplo de uso:</strong>
        <pre class="mb-0 mt-2" style="background: #f8f9fa; padding: 10px; border-radius: 4px;"><code>&lt;p&gt;Hola @{{ name }} 👋&lt;/p&gt;
&lt;p&gt;Tu email es: @{{ email }}&lt;/p&gt;</code></pre>
      </div>
    </div>
  </div>
</div>
@endsection
