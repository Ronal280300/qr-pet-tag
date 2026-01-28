@extends('layouts.admin')

@section('title', 'Configuración del Sistema')
@section('page-title', 'Configuración del Sistema')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-cog me-2"></i>Configuración del Sistema
      </h1>
      <p class="text-muted">Administra la configuración global del sitio. Los cambios se aplicarán en todo el sistema.</p>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card shadow">
    <div class="card-body">
      {{-- Tabs --}}
      <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
        @foreach($groups as $groupKey => $groupName)
          <li class="nav-item" role="presentation">
            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                    id="tab-{{ $groupKey }}"
                    data-bs-toggle="tab"
                    data-bs-target="#content-{{ $groupKey }}"
                    type="button">
              {{ $groupName }}
            </button>
          </li>
        @endforeach
      </ul>

      <form method="POST" action="{{ route('portal.admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="tab-content pt-4" id="settingsTabsContent">
          @foreach($groups as $groupKey => $groupName)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                 id="content-{{ $groupKey }}"
                 role="tabpanel">

              @if($settings[$groupKey]->isEmpty())
                <div class="alert alert-info">
                  <i class="fas fa-info-circle me-2"></i>
                  No hay configuraciones disponibles en este grupo.
                </div>
              @else
                <div class="row">
                  @foreach($settings[$groupKey] as $setting)
                    <div class="col-md-6 mb-3">
                      <div class="setting-item p-3 border rounded">
                        <label for="setting_{{ $setting->key }}" class="form-label fw-bold">
                          {{ $setting->label }}
                        </label>

                        @if($setting->description)
                          <small class="text-muted d-block mb-2">{{ $setting->description }}</small>
                        @endif

                        @if($setting->type === 'boolean')
                          <div class="form-check form-switch">
                            <input type="checkbox"
                                   class="form-check-input"
                                   name="{{ $setting->key }}"
                                   id="setting_{{ $setting->key }}"
                                   value="1"
                                   {{ $setting->value == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="setting_{{ $setting->key }}">
                              {{ $setting->value == '1' ? 'Activado' : 'Desactivado' }}
                            </label>
                          </div>
                        @elseif($setting->type === 'color')
                          <input type="color"
                                 class="form-control form-control-color"
                                 name="{{ $setting->key }}"
                                 id="setting_{{ $setting->key }}"
                                 value="{{ $setting->value ?? '#115DFC' }}">
                        @elseif($setting->type === 'textarea')
                          <textarea name="{{ $setting->key }}"
                                    id="setting_{{ $setting->key }}"
                                    class="form-control"
                                    rows="3">{{ $setting->value }}</textarea>
                        @else
                          <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}"
                                 class="form-control"
                                 name="{{ $setting->key }}"
                                 id="setting_{{ $setting->key }}"
                                 value="{{ $setting->value }}"
                                 {{ $setting->type === 'email' ? 'type=email' : '' }}>
                        @endif
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          @endforeach
        </div>

        <div class="border-top pt-3 mt-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Guardar Cambios
              </button>
              <button type="button" class="btn btn-secondary" onclick="location.reload()">
                <i class="fas fa-undo me-2"></i>Cancelar
              </button>
            </div>
            <div>
              <form method="POST" action="{{ route('portal.admin.settings.clear-cache') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-warning" onclick="return confirm('¿Limpiar cache del sistema?')">
                  <i class="fas fa-broom me-2"></i>Limpiar Cache
                </button>
              </form>
              <form method="POST" action="{{ route('portal.admin.settings.reset') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro? Esto restaurará todos los valores por defecto.')">
                  <i class="fas fa-exclamation-triangle me-2"></i>Resetear a Defaults
                </button>
              </form>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Información adicional --}}
  <div class="row mt-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-info text-white">
          <i class="fas fa-info-circle me-2"></i>Información
        </div>
        <div class="card-body">
          <p><strong>Nota importante sobre WhatsApp:</strong></p>
          <p class="mb-2">El número de WhatsApp debe incluir el código de país sin el símbolo +.</p>
          <p class="mb-0">Ejemplo: <code>50612345678</code> para Costa Rica</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header bg-success text-white">
          <i class="fas fa-palette me-2"></i>Colores del Tema
        </div>
        <div class="card-body">
          <p>Los colores del tema se aplican automáticamente en:</p>
          <ul class="mb-0">
            <li>Botones principales</li>
            <li>Enlaces y navegación</li>
            <li>Correos electrónicos</li>
            <li>Perfiles públicos de mascotas</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.setting-item {
  background: #f8f9fa;
  transition: all 0.2s;
}
.setting-item:hover {
  background: #e9ecef;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.nav-tabs .nav-link {
  color: #6c757d;
}
.nav-tabs .nav-link.active {
  color: #115DFC;
  font-weight: 600;
}
</style>
@endsection
