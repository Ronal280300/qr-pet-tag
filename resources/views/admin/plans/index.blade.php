@extends('layouts.app')

@section('title', 'Configuración de Planes - Admin')

@section('content')
<div class="plans-config-modern">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="page-header-config">
            <div class="header-content">
                <div class="header-icon-wrapper">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <div>
                    <h1>Configuración de Planes</h1>
                    <p>Administra los planes disponibles, precios y configuración general</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert-modern success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert-modern danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
        @endif

        <!-- Email Stats -->
        <div class="stats-grid-config">
            <div class="stat-card-config">
                <div class="stat-icon email">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Emails Este Mes</p>
                    <h3 class="stat-value">{{ $emailStats['current_month'] ?? 0 }}</h3>
                    <small>Límite mensual de Gmail</small>
                </div>
            </div>
            <div class="stat-card-config">
                <div class="stat-icon success">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Emails Enviados</p>
                    <h3 class="stat-value">{{ $emailStats['sent'] ?? 0 }}</h3>
                    <small>Exitosos</small>
                </div>
            </div>
            <div class="stat-card-config">
                <div class="stat-icon danger">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div class="stat-content">
                    <p class="stat-label">Emails Fallidos</p>
                    <h3 class="stat-value">{{ $emailStats['failed'] ?? 0 }}</h3>
                    <small>Con errores</small>
                </div>
            </div>
        </div>

        <!-- Tabs and Create Button -->
        <div class="tabs-header">
            <div class="tabs-modern">
                <button class="tab-button active" data-tab="oneTime">
                    <i class="fa-solid fa-tag"></i>
                    <span>Pago Único</span>
                </button>
                <button class="tab-button" data-tab="subscription">
                    <i class="fa-solid fa-repeat"></i>
                    <span>Suscripciones</span>
                </button>
            </div>
            <button type="button" class="btn-create-plan" data-bs-toggle="modal" data-bs-target="#createPlanModal">
                <i class="fa-solid fa-plus"></i>
                <span>Crear Nuevo Plan</span>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="tab-content-modern">
            <!-- Pago Único -->
            <div class="tab-pane-modern active" id="oneTime">
                <div class="plans-grid">
                    @foreach($plans->where('type', 'one_time') as $plan)
                    <div class="plan-card-config {{ $plan->is_active ? 'active' : 'inactive' }}">
                        <div class="plan-header-config">
                            <div class="plan-title-group">
                                <h5>{{ $plan->name }}</h5>
                                <span class="plan-type-badge onetime">Pago Único</span>
                            </div>
                            <form action="{{ route('portal.admin.plans.toggle', $plan) }}" method="POST" class="toggle-form">
                                @csrf
                                <label class="switch-modern">
                                    <input type="checkbox" {{ $plan->is_active ? 'checked' : '' }} onchange="confirmToggle(this, '{{ $plan->name }}', {{ $plan->is_active ? 'true' : 'false' }})">
                                    <span class="slider-modern"></span>
                                </label>
                            </form>
                        </div>

                        <form action="{{ route('portal.admin.plans.update', $plan) }}" method="POST" class="plan-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_active" value="{{ $plan->is_active ? 1 : 0 }}">

                            <div class="plan-body-config">
                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-tag"></i>
                                        Nombre del Plan
                                    </label>
                                    <input type="text" name="name" value="{{ $plan->name }}" required>
                                </div>

                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-paw"></i>
                                        Mascotas Incluidas
                                    </label>
                                    <input type="number" name="pets_included" value="{{ $plan->pets_included }}" min="1" required>
                                </div>

                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-coins"></i>
                                        Precio Base (₡)
                                    </label>
                                    <input type="number" name="price" value="{{ $plan->price }}" min="0" step="100" required>
                                </div>

                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-plus-circle"></i>
                                        Precio Mascota Adicional (₡)
                                    </label>
                                    <input type="number" name="additional_pet_price" value="{{ $plan->additional_pet_price }}" min="0" step="100" required>
                                </div>

                                <div class="form-field-modern full">
                                    <label>
                                        <i class="fa-solid fa-align-left"></i>
                                        Descripción
                                    </label>
                                    <textarea name="description" rows="2">{{ $plan->description }}</textarea>
                                </div>

                                <div class="checkbox-modern">
                                    <input type="hidden" name="allows_additional_pets" value="0">
                                    <input type="checkbox" name="allows_additional_pets" value="1" id="allows{{ $plan->id }}" {{ $plan->allows_additional_pets ? 'checked' : '' }}>
                                    <label for="allows{{ $plan->id }}">
                                        <i class="fa-solid fa-check-circle"></i>
                                        Permitir mascotas adicionales
                                    </label>
                                </div>
                            </div>

                            <div class="plan-footer-config">
                                <div class="plan-info-text">
                                    <i class="fa-solid fa-calculator"></i>
                                    <span>Con 1 extra: ₡{{ number_format($plan->price + $plan->additional_pet_price, 0, ',', '.') }}</span>
                                </div>
                                <div class="plan-actions">
                                    <button type="button" class="btn-action-config save" onclick="confirmSave(this.form, '{{ $plan->name }}')">
                                        <i class="fa-solid fa-save"></i>
                                        Guardar
                                    </button>
                                    <button type="button" class="btn-action-config delete" onclick="confirmDelete({{ $plan->id }}, '{{ $plan->name }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <form id="delete-form-{{ $plan->id }}" action="{{ route('portal.admin.plans.destroy', $plan) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Suscripciones -->
            <div class="tab-pane-modern" id="subscription">
                <div class="plans-grid">
                    @foreach($plans->where('type', 'subscription') as $plan)
                    <div class="plan-card-config {{ $plan->is_active ? 'active' : 'inactive' }} subscription">
                        <div class="plan-header-config">
                            <div class="plan-title-group">
                                <h5>{{ $plan->name }}</h5>
                                <span class="plan-type-badge subscription">Suscripción</span>
                            </div>
                            <form action="{{ route('portal.admin.plans.toggle', $plan) }}" method="POST" class="toggle-form">
                                @csrf
                                <label class="switch-modern">
                                    <input type="checkbox" {{ $plan->is_active ? 'checked' : '' }} onchange="confirmToggle(this, '{{ $plan->name }}', {{ $plan->is_active ? 'true' : 'false' }})">
                                    <span class="slider-modern"></span>
                                </label>
                            </form>
                        </div>

                        <form action="{{ route('portal.admin.plans.update', $plan) }}" method="POST" class="plan-form">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_active" value="{{ $plan->is_active ? 1 : 0 }}">

                            <div class="plan-body-config">
                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-tag"></i>
                                        Nombre del Plan
                                    </label>
                                    <input type="text" name="name" value="{{ $plan->name }}" required>
                                </div>

                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-calendar-days"></i>
                                        Duración (Meses)
                                    </label>
                                    <input type="number" name="duration_months" value="{{ $plan->duration_months }}" min="1" required>
                                </div>

                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-paw"></i>
                                        Mascotas Incluidas
                                    </label>
                                    <input type="number" name="pets_included" value="{{ $plan->pets_included }}" min="1" required>
                                </div>

                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-coins"></i>
                                        Precio (₡)
                                    </label>
                                    <input type="number" name="price" value="{{ $plan->price }}" min="0" step="100" required>
                                </div>

                                <div class="form-field-modern">
                                    <label>
                                        <i class="fa-solid fa-plus-circle"></i>
                                        Precio Mascota Adicional (₡)
                                    </label>
                                    <input type="number" name="additional_pet_price" value="{{ $plan->additional_pet_price }}" min="0" step="100" required>
                                </div>

                                <div class="form-field-modern full">
                                    <label>
                                        <i class="fa-solid fa-align-left"></i>
                                        Descripción
                                    </label>
                                    <textarea name="description" rows="2">{{ $plan->description }}</textarea>
                                </div>

                                <div class="checkbox-modern">
                                    <input type="hidden" name="allows_additional_pets" value="0">
                                    <input type="checkbox" name="allows_additional_pets" value="1" id="allowsSub{{ $plan->id }}" {{ $plan->allows_additional_pets ? 'checked' : '' }}>
                                    <label for="allowsSub{{ $plan->id }}">
                                        <i class="fa-solid fa-check-circle"></i>
                                        Permitir mascotas adicionales
                                    </label>
                                </div>
                            </div>

                            <div class="plan-footer-config">
                                <div class="plan-info-text">
                                    <i class="fa-solid fa-calculator"></i>
                                    <span>Total período: ₡{{ number_format($plan->price * $plan->duration_months, 0, ',', '.') }}</span>
                                </div>
                                <div class="plan-actions">
                                    <button type="button" class="btn-action-config save" onclick="confirmSave(this.form, '{{ $plan->name }}')">
                                        <i class="fa-solid fa-save"></i>
                                        Guardar
                                    </button>
                                    <button type="button" class="btn-action-config delete" onclick="confirmDelete({{ $plan->id }}, '{{ $plan->name }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <form id="delete-form-{{ $plan->id }}" action="{{ route('portal.admin.plans.destroy', $plan) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Configuración General -->
        <div class="settings-card-modern">
            <div class="settings-header">
                <div class="settings-icon">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <h5>Configuración General</h5>
            </div>
            <form action="{{ route('portal.admin.settings.update') }}" method="POST" id="settingsForm">
                @csrf
                <div class="settings-body">
                    <div class="settings-grid">
                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-envelope-circle-check"></i>
                                Límite de Emails por Mes
                            </label>
                            <input type="number" name="email_monthly_limit" value="{{ $settings['email_monthly_limit'] ?? 500 }}" min="0">
                            <small>Gmail tiene un límite de ~500 emails/día</small>
                        </div>

                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-at"></i>
                                Email de Soporte
                            </label>
                            <input type="email" name="support_email" value="{{ $settings['support_email'] ?? 'soporte@qrpettag.com' }}">
                        </div>

                        <div class="form-field-modern">
                            <label>
                                <i class="fa-brands fa-whatsapp"></i>
                                WhatsApp de Soporte
                            </label>
                            <input type="text" name="support_whatsapp" value="{{ $settings['support_whatsapp'] ?? '+50670000000' }}" placeholder="+506 0000 0000">
                        </div>

                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-clock"></i>
                                Días de Gracia Antes de Bloqueo
                            </label>
                            <input type="number" name="grace_days_before_block" value="{{ $settings['grace_days_before_block'] ?? 3 }}" min="0">
                            <small>Días después del vencimiento antes de bloquear</small>
                        </div>
                    </div>
                </div>
                <div class="settings-footer">
                    <button type="button" class="btn-settings-save" onclick="confirmSettingsSave(this.form)">
                        <i class="fa-solid fa-save"></i>
                        <span>Guardar Configuración</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Crear Nuevo Plan -->
<div class="modal fade" id="createPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modern-modal-config">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-plus-circle"></i>
                    Crear Nuevo Plan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('portal.admin.plans.store') }}" method="POST" id="createPlanForm">
                @csrf
                <div class="modal-body">
                    <div class="modal-form-grid">
                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-tag"></i>
                                Nombre del Plan
                                <span class="required">*</span>
                            </label>
                            <input type="text" name="name" placeholder="ej: Plan Básico" required>
                        </div>

                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-list"></i>
                                Tipo de Plan
                                <span class="required">*</span>
                            </label>
                            <select name="type" id="planType" required>
                                <option value="">Seleccione...</option>
                                <option value="one_time">Pago Único</option>
                                <option value="subscription">Suscripción</option>
                            </select>
                        </div>
                    </div>

                    <div id="subscriptionFields" style="display: none;">
                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-calendar-days"></i>
                                Duración (Meses)
                            </label>
                            <input type="number" name="duration_months" placeholder="ej: 12" min="1">
                        </div>
                    </div>

                    <div class="modal-form-grid">
                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-paw"></i>
                                Mascotas Incluidas
                                <span class="required">*</span>
                            </label>
                            <input type="number" name="pets_included" value="1" min="1" required>
                        </div>

                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-coins"></i>
                                Precio Base (₡)
                                <span class="required">*</span>
                            </label>
                            <input type="number" name="price" placeholder="5000" min="0" step="100" required>
                        </div>

                        <div class="form-field-modern">
                            <label>
                                <i class="fa-solid fa-plus-circle"></i>
                                Precio Mascota Extra (₡)
                                <span class="required">*</span>
                            </label>
                            <input type="number" name="additional_pet_price" placeholder="2000" min="0" step="100" required>
                        </div>
                    </div>

                    <div class="form-field-modern full">
                        <label>
                            <i class="fa-solid fa-align-left"></i>
                            Descripción
                        </label>
                        <textarea name="description" rows="3" placeholder="Describe las características del plan..."></textarea>
                    </div>

                    <div class="checkbox-group-modern">
                        <div class="checkbox-modern">
                            <input type="checkbox" name="allows_additional_pets" value="1" id="allowsAdditionalPets" checked>
                            <label for="allowsAdditionalPets">
                                <i class="fa-solid fa-check-circle"></i>
                                Permitir agregar mascotas adicionales
                            </label>
                            <small>Los clientes podrán comprar más mascotas del número incluido</small>
                        </div>

                        <div class="checkbox-modern">
                            <input type="checkbox" name="is_active" value="1" id="isActive" checked>
                            <label for="isActive">
                                <i class="fa-solid fa-toggle-on"></i>
                                Activar plan inmediatamente
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-config secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn-modal-config primary" onclick="confirmCreate(this.form)">
                        <i class="fa-solid fa-save"></i>
                        Crear Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane-modern');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.dataset.tab;

            // Remove active from all
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Add active to clicked
            button.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Show/hide subscription fields in modal
    const planTypeSelect = document.getElementById('planType');
    if (planTypeSelect) {
        planTypeSelect.addEventListener('change', function() {
            const subscriptionFields = document.getElementById('subscriptionFields');
            const durationInput = subscriptionFields.querySelector('input');
            
            if (this.value === 'subscription') {
                subscriptionFields.style.display = 'block';
                durationInput.required = true;
            } else {
                subscriptionFields.style.display = 'none';
                durationInput.required = false;
            }
        });
    }
});

// Confirm toggle plan status
function confirmToggle(checkbox, planName, currentStatus) {
    const newStatus = !currentStatus;
    const action = newStatus ? 'activar' : 'desactivar';
    
    Swal.fire({
        title: `¿${action.charAt(0).toUpperCase() + action.slice(1)} plan?`,
        html: `¿Deseas ${action} el plan "<strong>${planName}</strong>"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: newStatus ? '#10B981' : '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: `Sí, ${action}`,
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Procesando...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            checkbox.closest('form').submit();
        } else {
            checkbox.checked = currentStatus;
        }
    });
}

// Confirm save plan
function confirmSave(form, planName) {
    Swal.fire({
        title: '¿Guardar cambios?',
        html: `¿Deseas guardar los cambios del plan "<strong>${planName}</strong>"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4F89E8',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fa-solid fa-save"></i> Sí, guardar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Guardando...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            form.submit();
        }
    });
}

// Confirm delete plan
function confirmDelete(planId, planName) {
    Swal.fire({
        title: '¿Eliminar plan?',
        html: `¿Estás seguro de eliminar el plan "<strong>${planName}</strong>"?<br><br><small class="text-muted">Esta acción no se puede deshacer. Si el plan tiene órdenes activas, no se podrá eliminar.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fa-solid fa-trash"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Eliminando...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            document.getElementById('delete-form-' + planId).submit();
        }
    });
}

// Confirm create plan
function confirmCreate(form) {
    Swal.fire({
        title: '¿Crear nuevo plan?',
        text: 'Se creará un nuevo plan con la información proporcionada.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fa-solid fa-plus"></i> Sí, crear',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createPlanModal'));
            if (modal) modal.hide();
            
            Swal.fire({
                title: 'Creando plan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            form.submit();
        }
    });
}

// Confirm save settings
function confirmSettingsSave(form) {
    Swal.fire({
        title: '¿Guardar configuración?',
        text: 'Se actualizará la configuración general del sistema.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4F89E8',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="fa-solid fa-save"></i> Sí, guardar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Guardando...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            form.submit();
        }
    });
}
</script>

<style>
/* ============================================
   MODERN PLANS CONFIGURATION
   ============================================ */

:root {
    --config-primary: #4F89E8;
    --config-primary-dark: #1E7CF2;
    --config-success: #10B981;
    --config-warning: #F59E0B;
    --config-danger: #EF4444;
    --config-info: #3B82F6;
    --config-text: #1F2937;
    --config-text-light: #6B7280;
    --config-border: #E5E7EB;
    --config-bg: #F9FAFB;
    --config-white: #FFFFFF;
}

.plans-config-modern {
    background: var(--config-bg);
    min-height: 100vh;
}

/* Page Header */
.page-header-config {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--config-primary), var(--config-primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    box-shadow: 0 8px 16px rgba(79, 137, 232, 0.3);
}

.header-content h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--config-text);
    margin: 0;
    line-height: 1.2;
}

.header-content p {
    font-size: 14px;
    color: var(--config-text-light);
    margin: 4px 0 0 0;
}

/* Alerts */
.alert-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    position: relative;
    animation: slideDown 0.3s ease;
}

.alert-modern.success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #065F46;
}

.alert-modern.danger {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #991B1B;
}

.alert-modern i:first-child {
    font-size: 20px;
}

.alert-modern span {
    flex: 1;
    font-weight: 600;
}

.alert-close {
    background: none;
    border: none;
    color: inherit;
    opacity: 0.6;
    cursor: pointer;
    padding: 4px 8px;
    transition: opacity 0.3s;
}

.alert-close:hover {
    opacity: 1;
}

/* Stats Grid */
.stats-grid-config {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card-config {
    background: white;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.stat-card-config:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-icon.email {
    background: linear-gradient(135deg, var(--config-info), #2563EB);
}

.stat-icon.success {
    background: linear-gradient(135deg, var(--config-success), #059669);
}

.stat-icon.danger {
    background: linear-gradient(135deg, var(--config-danger), #DC2626);
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--config-text-light);
    margin: 0 0 4px 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 32px;
    font-weight: 800;
    color: var(--config-text);
    margin: 0 0 4px 0;
    line-height: 1;
}

.stat-content small {
    font-size: 12px;
    color: var(--config-text-light);
}

/* Tabs Header */
.tabs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 20px;
    flex-wrap: wrap;
}

.tabs-modern {
    display: flex;
    gap: 8px;
    background: white;
    padding: 6px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.tab-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: transparent;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    color: var(--config-text-light);
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-button:hover {
    background: var(--config-bg);
}

.tab-button.active {
    background: linear-gradient(135deg, var(--config-primary), var(--config-primary-dark));
    color: white;
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.3);
}

.btn-create-plan {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--config-success), #059669);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-create-plan:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

/* Tab Content */
.tab-content-modern {
    margin-bottom: 32px;
}

.tab-pane-modern {
    display: none;
}

.tab-pane-modern.active {
    display: block;
    animation: fadeInUp 0.5s ease;
}

/* Plans Grid */
.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
}

/* Plan Card */
.plan-card-config {
    background: white;
    border-radius: 16px;
    border: 2px solid var(--config-border);
    overflow: hidden;
    transition: all 0.3s ease;
}

.plan-card-config.active {
    border-color: var(--config-primary);
}

.plan-card-config.subscription.active {
    border-color: var(--config-success);
}

.plan-card-config.inactive {
    opacity: 0.7;
}

.plan-card-config:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
}

/* Plan Header */
.plan-header-config {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: linear-gradient(135deg, rgba(79, 137, 232, 0.05), rgba(30, 124, 242, 0.08));
    border-bottom: 1px solid var(--config-border);
}

.plan-card-config.subscription .plan-header-config {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(5, 150, 105, 0.08));
}

.plan-title-group h5 {
    font-size: 18px;
    font-weight: 800;
    color: var(--config-text);
    margin: 0 0 6px 0;
}

.plan-type-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.plan-type-badge.onetime {
    background: rgba(79, 137, 232, 0.15);
    color: var(--config-primary);
}

.plan-type-badge.subscription {
    background: rgba(16, 185, 129, 0.15);
    color: var(--config-success);
}

/* Modern Switch */
.switch-modern {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
}

.switch-modern input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider-modern {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #CBD5E1;
    transition: 0.4s;
    border-radius: 28px;
}

.slider-modern:before {
    position: absolute;
    content: "";
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .slider-modern {
    background: linear-gradient(135deg, var(--config-success), #059669);
}

input:checked + .slider-modern:before {
    transform: translateX(24px);
}

/* Plan Body */
.plan-body-config {
    padding: 24px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.form-field-modern {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-field-modern.full {
    grid-column: 1 / -1;
}

.form-field-modern label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--config-text);
}

.form-field-modern label i {
    color: var(--config-primary);
    width: 16px;
}

.form-field-modern input,
.form-field-modern textarea,
.form-field-modern select {
    padding: 10px 14px;
    border: 2px solid var(--config-border);
    border-radius: 10px;
    font-size: 14px;
    color: var(--config-text);
    font-family: inherit;
    transition: all 0.3s ease;
}

.form-field-modern textarea {
    resize: vertical;
    min-height: 60px;
}

.form-field-modern input:focus,
.form-field-modern textarea:focus,
.form-field-modern select:focus {
    outline: none;
    border-color: var(--config-primary);
    box-shadow: 0 0 0 3px rgba(79, 137, 232, 0.1);
}

.form-field-modern small {
    font-size: 11px;
    color: var(--config-text-light);
    margin-top: -4px;
}

/* Checkbox Modern */
.checkbox-modern {
    grid-column: 1 / -1;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.checkbox-modern input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-top: 2px;
    cursor: pointer;
    accent-color: var(--config-primary);
}

.checkbox-modern label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    font-weight: 600;
    color: var(--config-text);
    cursor: pointer;
}

.checkbox-modern label i {
    color: var(--config-success);
}

/* Plan Footer */
.plan-footer-config {
    padding: 16px 24px;
    background: var(--config-bg);
    border-top: 1px solid var(--config-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.plan-info-text {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--config-text-light);
}

.plan-info-text i {
    color: var(--config-primary);
}

.plan-actions {
    display: flex;
    gap: 8px;
}

.btn-action-config {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-action-config.save {
    background: linear-gradient(135deg, var(--config-primary), var(--config-primary-dark));
    color: white;
    box-shadow: 0 2px 8px rgba(79, 137, 232, 0.3);
}

.btn-action-config.save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.4);
}

.btn-action-config.delete {
    background: white;
    color: var(--config-danger);
    border: 2px solid var(--config-border);
    padding: 10px 14px;
}

.btn-action-config.delete:hover {
    background: var(--config-danger);
    color: white;
    border-color: var(--config-danger);
    transform: translateY(-2px);
}

/* Settings Card */
.settings-card-modern {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.settings-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: linear-gradient(135deg, #1F2937, #374151);
    color: white;
}

.settings-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.settings-header h5 {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.settings-body {
    padding: 24px;
}

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.settings-footer {
    padding: 20px 24px;
    background: var(--config-bg);
    border-top: 1px solid var(--config-border);
}

.btn-settings-save {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, #1F2937, #374151);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
}

.btn-settings-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(31, 41, 55, 0.4);
}

/* Modal */
.modern-modal-config .modal-header {
    padding: 20px 24px;
    background: linear-gradient(135deg, var(--config-success), #059669);
    color: white;
    border-bottom: none;
}

.modern-modal-config .modal-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 700;
}

.modern-modal-config .btn-close {
    filter: brightness(0) invert(1);
}

.modern-modal-config .modal-body {
    padding: 24px;
}

.modal-form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.checkbox-group-modern {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-top: 20px;
}

.checkbox-group-modern .checkbox-modern {
    padding: 16px;
    background: var(--config-bg);
    border-radius: 12px;
    border: 1px solid var(--config-border);
}

.checkbox-group-modern .checkbox-modern small {
    margin-left: 26px;
    display: block;
    margin-top: 4px;
    color: var(--config-text-light);
    font-size: 12px;
}

.modern-modal-config .modal-footer {
    padding: 16px 24px;
    background: var(--config-bg);
    border-top: 1px solid var(--config-border);
}

.btn-modal-config {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-modal-config.secondary {
    background: white;
    color: var(--config-text);
    border: 1px solid var(--config-border);
}

.btn-modal-config.secondary:hover {
    background: var(--config-bg);
}

.btn-modal-config.primary {
    background: linear-gradient(135deg, var(--config-success), #059669);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-modal-config.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

.required {
    color: var(--config-danger);
    margin-left: 4px;
}

/* Animations */
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

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
    }

    .tabs-header {
        flex-direction: column;
        align-items: stretch;
    }

    .tabs-modern {
        width: 100%;
        justify-content: center;
    }

    .btn-create-plan {
        width: 100%;
        justify-content: center;
    }

    .plans-grid {
        grid-template-columns: 1fr;
    }

    .plan-body-config {
        grid-template-columns: 1fr;
    }

    .settings-grid {
        grid-template-columns: 1fr;
    }

    .plan-footer-config {
        flex-direction: column;
        align-items: stretch;
    }

    .plan-actions {
        width: 100%;
    }

    .btn-action-config.save {
        flex: 1;
    }
}
</style>
@endsection
