@extends('layouts.app')

@section('title', 'Configuración del Sistema')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col">
            <h1 class="h3 mb-0">
                <i class="fa-solid fa-cog me-2"></i>
                Configuración del Sistema
            </h1>
            <p class="text-muted mb-0">Configure todos los aspectos del sitio desde aquí</p>
        </div>
        <div class="col-auto">
            <form action="{{ route('portal.admin.settings.clear-cache') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-refresh me-1"></i>
                    Limpiar Cache
                </button>
            </form>
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#resetModal">
                <i class="fa-solid fa-rotate-left me-1"></i>
                Resetear
            </button>
        </div>
    </div>

    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulario con Tabs --}}
    <form action="{{ route('portal.admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    @foreach($groups as $groupKey => $groupLabel)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if($loop->first) active @endif"
                                    id="{{ $groupKey }}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#{{ $groupKey }}"
                                    type="button"
                                    role="tab">
                                @if($groupKey === 'general')
                                    <i class="fa-solid fa-home me-1"></i>
                                @elseif($groupKey === 'contact')
                                    <i class="fa-solid fa-envelope me-1"></i>
                                @elseif($groupKey === 'theme')
                                    <i class="fa-solid fa-palette me-1"></i>
                                @elseif($groupKey === 'twilio')
                                    <i class="fa-brands fa-whatsapp me-1"></i>
                                @elseif($groupKey === 'notifications')
                                    <i class="fa-solid fa-bell me-1"></i>
                                @elseif($groupKey === 'email')
                                    <i class="fa-solid fa-at me-1"></i>
                                @elseif($groupKey === 'social')
                                    <i class="fa-solid fa-share-nodes me-1"></i>
                                @endif
                                {{ $groupLabel }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">
                    @foreach($groups as $groupKey => $groupLabel)
                        <div class="tab-pane fade @if($loop->first) show active @endif"
                             id="{{ $groupKey }}"
                             role="tabpanel">

                            <h5 class="mb-4">
                                <i class="fa-solid fa-gear me-2 text-muted"></i>
                                {{ $groupLabel }}
                            </h5>

                            @if(isset($settings[$groupKey]) && $settings[$groupKey]->count() > 0)
                                <div class="row g-3">
                                    @foreach($settings[$groupKey] as $setting)
                                        <div class="col-12 @if(in_array($setting->type, ['text', 'email', 'tel', 'url', 'number', 'password'])) col-md-6 @endif">
                                            <div class="mb-3">
                                                <label for="{{ $setting->key }}" class="form-label fw-semibold">
                                                    {{ $setting->label ?? $setting->key }}
                                                    @if($setting->description)
                                                        <i class="fa-solid fa-circle-info text-muted ms-1"
                                                           data-bs-toggle="tooltip"
                                                           title="{{ $setting->description }}"></i>
                                                    @endif
                                                </label>

                                                @if($setting->type === 'textarea')
                                                    <textarea
                                                        name="{{ $setting->key }}"
                                                        id="{{ $setting->key }}"
                                                        class="form-control"
                                                        rows="3">{{ $setting->value }}</textarea>

                                                @elseif($setting->type === 'boolean')
                                                    <div class="form-check form-switch">
                                                        <input
                                                            type="checkbox"
                                                            class="form-check-input"
                                                            name="{{ $setting->key }}"
                                                            id="{{ $setting->key }}"
                                                            value="1"
                                                            {{ $setting->value == '1' || $setting->value === true ? 'checked' : '' }}>
                                                        <label class="form-check-label text-muted" for="{{ $setting->key }}">
                                                            {{ $setting->description }}
                                                        </label>
                                                    </div>

                                                @elseif($setting->type === 'color')
                                                    <div class="input-group">
                                                        <input
                                                            type="color"
                                                            name="{{ $setting->key }}"
                                                            id="{{ $setting->key }}"
                                                            class="form-control form-control-color"
                                                            value="{{ $setting->value ?? '#3b82f6' }}">
                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            value="{{ $setting->value ?? '#3b82f6' }}"
                                                            readonly>
                                                    </div>

                                                @elseif($setting->type === 'password')
                                                    <div class="input-group">
                                                        <input
                                                            type="password"
                                                            name="{{ $setting->key }}"
                                                            id="{{ $setting->key }}"
                                                            class="form-control"
                                                            value="{{ $setting->value }}"
                                                            placeholder="••••••••">
                                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('{{ $setting->key }}')">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </div>

                                                @else
                                                    <input
                                                        type="{{ $setting->type }}"
                                                        name="{{ $setting->key }}"
                                                        id="{{ $setting->key }}"
                                                        class="form-control"
                                                        value="{{ $setting->value }}"
                                                        @if($setting->type === 'email') placeholder="ejemplo@dominio.com" @endif
                                                        @if($setting->type === 'tel') placeholder="+50688888888" @endif
                                                        @if($setting->type === 'url') placeholder="https://..." @endif>
                                                @endif

                                                @if($setting->description && $setting->type !== 'boolean')
                                                    <small class="form-text text-muted">{{ $setting->description }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle me-2"></i>
                                    No hay configuraciones disponibles en esta sección.
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-footer bg-white text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-2"></i>
                    Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Modal de Confirmación para Reset --}}
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Confirmar Reset
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">¿Estás seguro de que deseas resetear todas las configuraciones a sus valores por defecto?</p>
                <p class="text-danger mb-0 mt-2"><strong>Esta acción no se puede deshacer.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('portal.admin.settings.reset') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-rotate-left me-1"></i>
                        Resetear
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Sincronizar color picker con input de texto
    document.querySelectorAll('input[type="color"]').forEach(colorInput => {
        const textInput = colorInput.nextElementSibling;
        if (textInput && textInput.type === 'text') {
            colorInput.addEventListener('input', function() {
                textInput.value = this.value;
            });
        }
    });
});

// Toggle password visibility
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
@endsection
