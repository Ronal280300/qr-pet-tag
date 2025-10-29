@extends('layouts.app')

@section('title', 'Configuración de Planes - Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Configuración de Planes y Precios</h1>
            <p class="text-muted mb-0">Administra los planes disponibles, precios y configuración general</p>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Email Stats Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="fa-solid fa-envelope me-2"></i>Emails Este Mes</h6>
                    <h3 class="mb-1">{{ $emailStats['current_month'] ?? 0 }}</h3>
                    <small class="text-muted">Límite mensual de Gmail</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="fa-solid fa-paper-plane me-2"></i>Emails Enviados</h6>
                    <h3 class="mb-1 text-success">{{ $emailStats['sent'] ?? 0 }}</h3>
                    <small class="text-muted">Exitosos</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>Emails Fallidos</h6>
                    <h3 class="mb-1 text-danger">{{ $emailStats['failed'] ?? 0 }}</h3>
                    <small class="text-muted">Con errores</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de Planes -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <ul class="nav nav-tabs mb-0" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="one-time-tab" data-bs-toggle="tab" data-bs-target="#one-time" type="button">
                    <i class="fa-solid fa-tag me-2"></i>Pago Único
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="subscription-tab" data-bs-toggle="tab" data-bs-target="#subscription" type="button">
                    <i class="fa-solid fa-repeat me-2"></i>Suscripciones
                </button>
            </li>
        </ul>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPlanModal">
            <i class="fa-solid fa-plus me-2"></i>Crear Nuevo Plan
        </button>
    </div>

    <div class="tab-content">
        <!-- Pago Único -->
        <div class="tab-pane fade show active" id="one-time" role="tabpanel">
            <div class="row">
                @foreach($plans->where('type', 'one_time') as $plan)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $plan->is_active ? 'border-primary' : 'border-secondary' }}">
                        <div class="card-header bg-{{ $plan->is_active ? 'primary' : 'secondary' }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $plan->name }}</h5>
                                <form action="{{ route('portal.admin.plans.toggle', $plan) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               {{ $plan->is_active ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('portal.admin.plans.update', $plan) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="{{ $plan->is_active ? 1 : 0 }}">

                                <div class="mb-3">
                                    <label class="form-label">Nombre del Plan</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ $plan->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mascotas Incluidas</label>
                                    <input type="number" name="pets_included" class="form-control"
                                           value="{{ $plan->pets_included }}" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Precio Base (₡)</label>
                                    <input type="number" name="price" class="form-control"
                                           value="{{ $plan->price }}" min="0" step="100" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Precio Mascota Adicional (₡)</label>
                                    <input type="number" name="additional_pet_price" class="form-control"
                                           value="{{ $plan->additional_pet_price }}" min="0" step="100" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="description" class="form-control" rows="2">{{ $plan->description }}</textarea>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="allows_additional_pets" value="1" id="allowsAdditional{{ $plan->id }}" {{ $plan->allows_additional_pets ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="allowsAdditional{{ $plan->id }}">
                                        Permitir mascotas adicionales
                                    </label>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fa-solid fa-save me-2"></i>Guardar Cambios
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $plan->id }}, '{{ $plan->name }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </form>
                            <form id="delete-form-{{ $plan->id }}" action="{{ route('portal.admin.plans.destroy', $plan) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Total si agrega 1 mascota extra: ₡{{ number_format($plan->price + $plan->additional_pet_price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Suscripciones -->
        <div class="tab-pane fade" id="subscription" role="tabpanel">
            <div class="row">
                @foreach($plans->where('type', 'subscription') as $plan)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 {{ $plan->is_active ? 'border-success' : 'border-secondary' }}">
                        <div class="card-header bg-{{ $plan->is_active ? 'success' : 'secondary' }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $plan->name }}</h5>
                                <form action="{{ route('portal.admin.plans.toggle', $plan) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               {{ $plan->is_active ? 'checked' : '' }}
                                               onchange="this.form.submit()">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('portal.admin.plans.update', $plan) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="{{ $plan->is_active ? 1 : 0 }}">

                                <div class="mb-3">
                                    <label class="form-label">Nombre del Plan</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ $plan->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Duración (Meses)</label>
                                    <input type="number" name="duration_months" class="form-control"
                                           value="{{ $plan->duration_months }}" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mascotas Incluidas</label>
                                    <input type="number" name="pets_included" class="form-control"
                                           value="{{ $plan->pets_included }}" min="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Precio Mensual (₡)</label>
                                    <input type="number" name="price" class="form-control"
                                           value="{{ $plan->price }}" min="0" step="100" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Precio Mascota Adicional (₡/mes)</label>
                                    <input type="number" name="additional_pet_price" class="form-control"
                                           value="{{ $plan->additional_pet_price }}" min="0" step="100" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Descripción</label>
                                    <textarea name="description" class="form-control" rows="2">{{ $plan->description }}</textarea>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="allows_additional_pets" value="1" id="allowsAdditionalSub{{ $plan->id }}" {{ $plan->allows_additional_pets ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="allowsAdditionalSub{{ $plan->id }}">
                                        Permitir mascotas adicionales
                                    </label>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success flex-grow-1">
                                        <i class="fa-solid fa-save me-2"></i>Guardar Cambios
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $plan->id }}, '{{ $plan->name }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </form>
                            <form id="delete-form-{{ $plan->id }}" action="{{ route('portal.admin.plans.destroy', $plan) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                        <div class="card-footer text-muted small">
                            <i class="fa-solid fa-info-circle me-1"></i>
                            Total período: ₡{{ number_format($plan->price * $plan->duration_months, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Configuración General -->
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fa-solid fa-gear me-2"></i>Configuración General</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('portal.admin.settings.update') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Límite de Emails por Mes</label>
                        <input type="number" name="email_monthly_limit" class="form-control"
                               value="{{ $settings['email_monthly_limit'] ?? 500 }}" min="0">
                        <small class="text-muted">Gmail tiene un límite de ~500 emails/día</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email de Soporte</label>
                        <input type="email" name="support_email" class="form-control"
                               value="{{ $settings['support_email'] ?? 'soporte@qrpettag.com' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">WhatsApp de Soporte</label>
                        <input type="text" name="support_whatsapp" class="form-control"
                               value="{{ $settings['support_whatsapp'] ?? '+50670000000' }}"
                               placeholder="+506 0000 0000">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Días de Gracia Antes de Bloqueo</label>
                        <input type="number" name="grace_days_before_block" class="form-control"
                               value="{{ $settings['grace_days_before_block'] ?? 3 }}" min="0">
                        <small class="text-muted">Días después del vencimiento antes de bloquear la cuenta</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark">
                    <i class="fa-solid fa-save me-2"></i>Guardar Configuración
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Crear Nuevo Plan -->
<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createPlanModalLabel">
                    <i class="fa-solid fa-plus-circle me-2"></i>Crear Nuevo Plan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('portal.admin.plans.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre del Plan <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="ej: Plan Básico" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Plan <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" id="planType" required>
                                <option value="">Seleccione...</option>
                                <option value="one_time">Pago Único</option>
                                <option value="subscription">Suscripción</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" id="subscriptionFields" style="display: none;">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Duración (Meses)</label>
                            <input type="number" name="duration_months" class="form-control" placeholder="ej: 12" min="1">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mascotas Incluidas <span class="text-danger">*</span></label>
                            <input type="number" name="pets_included" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Precio Base (₡) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control" placeholder="5000" min="0" step="100" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Precio Mascota Extra (₡) <span class="text-danger">*</span></label>
                            <input type="number" name="additional_pet_price" class="form-control" placeholder="2000" min="0" step="100" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Describe las características del plan..."></textarea>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="allows_additional_pets" value="1" id="allowsAdditionalPets" checked>
                        <label class="form-check-label" for="allowsAdditionalPets">
                            Permitir agregar mascotas adicionales
                        </label>
                        <small class="d-block text-muted ms-4">Si está activado, los clientes podrán comprar más mascotas del número incluido en el plan</small>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                        <label class="form-check-label" for="isActive">
                            Activar plan inmediatamente
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-save me-2"></i>Crear Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Mostrar/ocultar campo de duración según tipo de plan
document.getElementById('planType')?.addEventListener('change', function() {
    const subscriptionFields = document.getElementById('subscriptionFields');
    if (this.value === 'subscription') {
        subscriptionFields.style.display = 'block';
        subscriptionFields.querySelector('input').required = true;
    } else {
        subscriptionFields.style.display = 'none';
        subscriptionFields.querySelector('input').required = false;
    }
});

// Confirmación antes de eliminar
function confirmDelete(planId, planName) {
    if (confirm(`¿Estás seguro de que deseas eliminar el plan "${planName}"?\n\nEsta acción no se puede deshacer. Si el plan tiene órdenes o usuarios activos, no se podrá eliminar.`)) {
        document.getElementById('delete-form-' + planId).submit();
    }
}
</script>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.form-check-input {
    cursor: pointer;
    width: 3rem;
    height: 1.5rem;
}

.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 600;
}

.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 700;
}
</style>
@endsection
