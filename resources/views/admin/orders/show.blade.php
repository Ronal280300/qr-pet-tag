@extends('layouts.app')

@section('title', 'Pedido #' . $order->order_number)

@section('content')
<div class="order-detail-modern">
    <div class="container py-4">
        <!-- Back Button -->
        <div class="back-button-wrapper">
            <a href="{{ route('portal.admin.orders.index') }}" class="btn-back-modern">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Volver a pedidos</span>
            </a>
        </div>

        <!-- Header -->
        <div class="order-header-modern">
            <div class="order-header-left">
                <div class="order-icon-wrapper">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h1>Pedido {{ $order->order_number }}</h1>
                    <p>Creado el {{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="order-status-badge">
                <span class="badge-modern {{ $order->status_badge_class }}">
                    @php
                        $statusIcons = [
                            'pending' => 'fa-clock',
                            'payment_uploaded' => 'fa-file-invoice',
                            'verified' => 'fa-check-circle',
                            'completed' => 'fa-circle-check',
                            'rejected' => 'fa-times-circle'
                        ];
                        $icon = $statusIcons[$order->status] ?? 'fa-circle';
                    @endphp
                    <i class="fa-solid {{ $icon }}"></i>
                    {{ $order->status_label }}
                </span>
            </div>
        </div>

        <div class="row">
            <!-- Columna izquierda: Información del pedido -->
            <div class="col-lg-8">
                <!-- Cliente Info -->
                <div class="info-card-modern">
                    <div class="card-header-modern">
                        <div class="header-icon">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h5>Información del Cliente</h5>
                    </div>
                    <div class="card-body-modern">
                        <div class="info-grid">
                            <div class="info-item">
                                <label>
                                    <i class="fa-solid fa-user-circle"></i>
                                    Nombre
                                </label>
                                <p>{{ $order->user->name }}</p>
                            </div>
                            <div class="info-item">
                                <label>
                                    <i class="fa-solid fa-envelope"></i>
                                    Email
                                </label>
                                <p>{{ $order->user->email }}</p>
                            </div>
                            <div class="info-item">
                                <label>
                                    <i class="fa-solid fa-phone"></i>
                                    Teléfono
                                </label>
                                <p>{{ $order->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plan Details -->
                <div class="info-card-modern">
                    <div class="card-header-modern">
                        <div class="header-icon">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h5>Detalles del Plan</h5>
                    </div>
                    <div class="card-body-modern">
                        <div class="plan-details-table">
                            <div class="detail-row">
                                <span class="detail-label">Plan</span>
                                <span class="detail-value">{{ $order->plan->name }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Cantidad de mascotas</span>
                                <span class="detail-value">
                                    <span class="pets-count">{{ $order->pets_quantity }}</span>
                                </span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Subtotal</span>
                                <span class="detail-value">₡{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($order->additional_pets_cost > 0)
                            <div class="detail-row">
                                <span class="detail-label">Mascotas adicionales</span>
                                <span class="detail-value">₡{{ number_format($order->additional_pets_cost, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if($order->shipping_cost > 0)
                            <div class="detail-row">
                                <span class="detail-label">Envío ({{ $order->shipping_zone === 'gam' ? 'GAM' : 'Fuera del GAM' }})</span>
                                <span class="detail-value">₡{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <div class="detail-row total-row">
                                <span class="detail-label">TOTAL</span>
                                <span class="detail-value total-value">₡{{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment & Shipping Information -->
                @if($order->payment_method || $order->shipping_zone)
                <div class="info-card-modern">
                    <div class="card-header-modern">
                        <div class="header-icon">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <h5>Información de Pago y Envío</h5>
                    </div>
                    <div class="card-body-modern">
                        <div class="payment-shipping-grid">
                            {{-- Payment Method Section --}}
                            @if($order->payment_method)
                            <div class="info-section">
                                <div class="section-header">
                                    <i class="fa-solid fa-money-bill-transfer"></i>
                                    <strong>Método de Pago</strong>
                                </div>
                                <div class="section-content">
                                    <span class="payment-badge {{ $order->payment_method === 'sinpe' ? 'badge-sinpe' : 'badge-transfer' }}">
                                        @if($order->payment_method === 'sinpe')
                                            <i class="fa-solid fa-mobile-screen"></i>
                                            SINPE Móvil
                                        @else
                                            <i class="fa-solid fa-building-columns"></i>
                                            Transferencia Bancaria
                                        @endif
                                    </span>

                                    @if($order->payment_method === 'sinpe' && $order->sinpe_phone)
                                        <div class="payment-detail">
                                            <label>Teléfono SINPE:</label>
                                            <span>{{ $order->sinpe_phone }}</span>
                                        </div>
                                    @endif

                                    @if($order->payment_description)
                                        <div class="payment-detail">
                                            <label>Descripción:</label>
                                            <span>{{ $order->payment_description }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- Shipping Section --}}
                            @if($order->shipping_zone)
                            <div class="info-section">
                                <div class="section-header">
                                    <i class="fa-solid fa-truck"></i>
                                    <strong>Información de Envío</strong>
                                </div>
                                <div class="section-content">
                                    <span class="shipping-badge {{ $order->shipping_zone === 'gam' ? 'badge-gam' : 'badge-fuera-gam' }}">
                                        @if($order->shipping_zone === 'gam')
                                            <i class="fa-solid fa-city"></i>
                                            Dentro del GAM
                                        @else
                                            <i class="fa-solid fa-map"></i>
                                            Fuera del GAM
                                        @endif
                                        <span class="shipping-cost">+ ₡{{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                                    </span>

                                    @if($order->shipping_address)
                                        <div class="shipping-address">
                                            <label><i class="fa-solid fa-location-dot"></i> Dirección de envío:</label>
                                            <p>{{ $order->shipping_address }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($order->admin_notes)
                <div class="notes-card-modern">
                    <div class="notes-icon">
                        <i class="fa-solid fa-note-sticky"></i>
                    </div>
                    <div>
                        <strong>Notas del administrador</strong>
                        <p>{{ $order->admin_notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Comprobante de Pago -->
                @if($order->payment_proof)
                <div class="info-card-modern">
                    <div class="card-header-modern">
                        <div class="header-icon">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h5>Comprobante de Pago</h5>
                    </div>
                    <div class="card-body-modern">
                        <p class="upload-date">
                            <i class="fa-solid fa-calendar-check"></i>
                            Subido el: {{ $order->payment_uploaded_at->format('d/m/Y H:i') }}
                        </p>

                        <div class="proof-viewer">
                            @if(Str::endsWith($order->payment_proof, '.pdf'))
                                <div class="pdf-preview">
                                    <div class="pdf-icon">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </div>
                                    <p class="pdf-label">Archivo PDF</p>
                                    <div class="pdf-actions">
                                        <a href="{{ Storage::url($order->payment_proof) }}" target="_blank" class="btn-proof view">
                                            <i class="fa-solid fa-eye"></i>
                                            Ver PDF
                                        </a>
                                        <a href="{{ Storage::url($order->payment_proof) }}" download="comprobante-{{ $order->order_number }}.pdf" class="btn-proof download">
                                            <i class="fa-solid fa-download"></i>
                                            Descargar
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="image-preview">
                                    <img src="{{ Storage::url($order->payment_proof) }}"
                                         alt="Comprobante"
                                         onclick="window.open(this.src, '_blank')">
                                    <p class="image-hint">
                                        <i class="fa-solid fa-expand"></i>
                                        Click en la imagen para ampliar
                                    </p>
                                    <a href="{{ Storage::url($order->payment_proof) }}" download="comprobante-{{ $order->order_number }}.{{ pathinfo($order->payment_proof, PATHINFO_EXTENSION) }}" class="btn-proof download">
                                        <i class="fa-solid fa-download"></i>
                                        Descargar Imagen
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Mascotas Pendientes -->
                @php
                    $pendingPets = $order->pets()->where('pending_activation', true)->get();
                @endphp

                @if($pendingPets->count() > 0)
                <div class="pets-card-modern">
                    <div class="card-header-modern warning">
                        <div class="header-icon">
                            <i class="fa-solid fa-paw"></i>
                        </div>
                        <h5>Mascotas Registradas desde Checkout</h5>
                        <span class="badge-count">{{ $pendingPets->count() }}</span>
                    </div>
                    <div class="card-body-modern">
                        <div class="alert-info-modern">
                            <i class="fa-solid fa-info-circle"></i>
                            <span>El cliente registró {{ $pendingPets->count() }} mascota(s) durante el checkout. Se enlazarán automáticamente cuando verifiques este pago.</span>
                        </div>

                        <div class="pets-table">
                            @foreach($pendingPets as $pet)
                            <div class="pet-item">
                                <div class="pet-header">
                                    <div class="pet-avatar">
                                        @if($pet->species === 'dog')
                                            <i class="fa-solid fa-dog"></i>
                                        @elseif($pet->species === 'cat')
                                            <i class="fa-solid fa-cat"></i>
                                        @else
                                            <i class="fa-solid fa-paw"></i>
                                        @endif
                                    </div>
                                    <div class="pet-name">
                                        <strong>{{ $pet->name }}</strong>
                                        <small>
                                            @if($pet->species === 'dog')
                                                Perro
                                            @elseif($pet->species === 'cat')
                                                Gato
                                            @else
                                                Otro
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="pet-details">
                                    <div class="pet-detail">
                                        <i class="fa-solid fa-dna"></i>
                                        <span>{{ $pet->breed ?? '-' }}</span>
                                    </div>
                                    <div class="pet-detail">
                                        <i class="fa-solid fa-cake-candles"></i>
                                        <span>{{ $pet->age ? $pet->age . ' años' : '-' }}</span>
                                    </div>
                                    <div class="pet-detail">
                                        <i class="fa-solid fa-venus-mars"></i>
                                        <span>
                                            @if($pet->sex === 'male') Macho
                                            @elseif($pet->sex === 'female') Hembra
                                            @else - @endif
                                        </span>
                                    </div>
                                    <div class="pet-detail">
                                        <i class="fa-solid fa-ruler-vertical"></i>
                                        <span>
                                            @if($pet->size === 'small') Pequeño
                                            @elseif($pet->size === 'medium') Mediano
                                            @elseif($pet->size === 'large') Grande
                                            @else - @endif
                                        </span>
                                    </div>
                                </div>
                                @if($pet->medical_conditions)
                                <div class="pet-medical">
                                    <i class="fa-solid fa-heart-pulse"></i>
                                    <span><strong>Condiciones médicas:</strong> {{ $pet->medical_conditions }}</span>
                                </div>
                                @endif

                                {{-- Pet Actions: Edit/Delete --}}
                                <div class="pet-actions-admin">
                                    <a href="{{ route('portal.pets.edit', $pet) }}" class="btn-pet-action edit" title="Editar mascota">
                                        <i class="fa-solid fa-pen"></i>
                                        <span>Editar</span>
                                    </a>
                                    <form method="POST" action="{{ route('portal.pets.destroy', $pet) }}" class="delete-pet-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-pet-action delete" title="Eliminar mascota" onclick="confirmDeletePet(this, '{{ $pet->name }}')">
                                            <i class="fa-solid fa-trash"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Columna derecha: Acciones -->
            <div class="col-lg-4">
                <!-- Actions Card -->
                <div class="actions-card-modern">
                    <div class="card-header-modern">
                        <div class="header-icon">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                        <h5>Acciones</h5>
                    </div>
                    <div class="card-body-modern">
                        @if($order->status === 'payment_uploaded')
                            <!-- Verificar pago -->
                            <form method="POST" action="{{ route('portal.admin.orders.verify', $order) }}" class="action-form">
                                @csrf
                                <div class="form-group-modern">
                                    <label>
                                        <i class="fa-solid fa-note-sticky"></i>
                                        Notas (opcional)
                                    </label>
                                    <textarea name="admin_notes" rows="3" placeholder="Ej: Pago verificado correctamente"></textarea>
                                </div>
                                <button type="submit" class="btn-action-full success">
                                    <i class="fa-solid fa-check"></i>
                                    Verificar Pago
                                </button>
                            </form>

                            <!-- Rechazar pago -->
                            <button type="button" class="btn-action-full danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fa-solid fa-times"></i>
                                Rechazar Pago
                            </button>
                        @elseif($order->status === 'verified')
                            <form method="POST" action="{{ route('portal.admin.orders.complete', $order) }}">
                                @csrf
                                <button type="submit" class="btn-action-full primary">
                                    <i class="fa-solid fa-check-double"></i>
                                    Marcar como Completado
                                </button>
                            </form>
                        @else
                            <div class="status-info-box">
                                <i class="fa-solid fa-info-circle"></i>
                                <div>
                                    <strong>Estado actual</strong>
                                    <p>{{ $order->status_label }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Info Card -->
                @if($order->verified_at || $order->verifiedBy || ($order->plan->type === 'subscription' && $order->expires_at))
                <div class="info-card-modern small">
                    <div class="card-header-modern">
                        <div class="header-icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h6>Información Adicional</h6>
                    </div>
                    <div class="card-body-modern">
                        @if($order->verified_at)
                        <div class="additional-info-item">
                            <i class="fa-solid fa-calendar-check"></i>
                            <div>
                                <small>Verificado</small>
                                <p>{{ $order->verified_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->verifiedBy)
                        <div class="additional-info-item">
                            <i class="fa-solid fa-user-check"></i>
                            <div>
                                <small>Verificado por</small>
                                <p>{{ $order->verifiedBy->name }}</p>
                            </div>
                        </div>
                        @endif

                        @if($order->plan->type === 'subscription' && $order->expires_at)
                        <div class="additional-info-item">
                            <i class="fa-solid fa-hourglass-end"></i>
                            <div>
                                <small>Expira</small>
                                <p>{{ $order->expires_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal para rechazar -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content modern-modal">
            <form method="POST" action="{{ route('portal.admin.orders.reject', $order) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        Rechazar Pago
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert-warning-modern">
                        <i class="fa-solid fa-envelope"></i>
                        <span>El cliente recibirá un email con el motivo del rechazo.</span>
                    </div>
                    <div class="form-group-modern">
                        <label>
                            <i class="fa-solid fa-message"></i>
                            Motivo del rechazo *
                        </label>
                        <textarea name="admin_notes" rows="4" placeholder="Ej: El monto no coincide con el total del pedido" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-modal danger">
                        <i class="fa-solid fa-times"></i>
                        Rechazar Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ============================================
   MODERN ORDER DETAIL PAGE
   ============================================ */

:root {
    --order-primary: #4F89E8;
    --order-primary-dark: #1E7CF2;
    --order-success: #10B981;
    --order-warning: #F59E0B;
    --order-danger: #EF4444;
    --order-info: #3B82F6;
    --order-text: #1F2937;
    --order-text-light: #6B7280;
    --order-border: #E5E7EB;
    --order-bg: #F9FAFB;
    --order-white: #FFFFFF;
}

.order-detail-modern {
    background: var(--order-bg);
    min-height: 100vh;
    padding-bottom: 40px;
}

/* Back Button */
.back-button-wrapper {
    margin-bottom: 24px;
}

.btn-back-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    border: 1px solid var(--order-border);
    border-radius: 10px;
    color: var(--order-text);
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-back-modern:hover {
    border-color: var(--order-primary);
    color: var(--order-primary);
    transform: translateX(-4px);
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.2);
}

/* Header */
.order-header-modern {
    background: white;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.order-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.order-icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--order-primary), var(--order-primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    box-shadow: 0 8px 16px rgba(79, 137, 232, 0.3);
}

.order-header-left h1 {
    font-size: 28px;
    font-weight: 800;
    color: var(--order-text);
    margin: 0;
    line-height: 1.2;
}

.order-header-left p {
    font-size: 14px;
    color: var(--order-text-light);
    margin: 4px 0 0 0;
}

/* Status Badge - Same as list */
.order-status-badge .badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    letter-spacing: 0.3px;
}

.badge-modern.bg-warning {
    background: #FCD34D;
    color: #78350F;
    border: 2px solid #F59E0B;
}

.badge-modern.bg-info {
    background: #60A5FA;
    color: #1E3A8A;
    border: 2px solid #2563EB;
}

.badge-modern.bg-success {
    background: #34D399;
    color: #064E3B;
    border: 2px solid #059669;
}

.badge-modern.bg-danger {
    background: #F87171;
    color: #7F1D1D;
    border: 2px solid #DC2626;
}

.badge-modern.bg-secondary {
    background: #D1D5DB;
    color: #1F2937;
    border: 2px solid #6B7280;
}

.badge-modern i {
    font-size: 13px;
}

/* Info Cards */
.info-card-modern {
    background: white;
    border-radius: 16px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.info-card-modern.small {
    font-size: 14px;
}

.card-header-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 24px;
    border-bottom: 1px solid var(--order-border);
    background: var(--order-bg);
}

.card-header-modern.warning {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(245, 158, 11, 0.1));
    border-bottom-color: rgba(245, 158, 11, 0.2);
}

.header-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--order-primary);
    font-size: 18px;
}

.card-header-modern.warning .header-icon {
    color: var(--order-warning);
}

.card-header-modern h5,
.card-header-modern h6 {
    font-size: 18px;
    font-weight: 700;
    color: var(--order-text);
    margin: 0;
    flex: 1;
}

.card-header-modern h6 {
    font-size: 16px;
}

.badge-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    height: 28px;
    padding: 0 10px;
    background: var(--order-warning);
    color: white;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
}

.card-body-modern {
    padding: 24px;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: var(--order-text-light);
    margin-bottom: 8px;
}

.info-item label i {
    color: var(--order-primary);
}

.info-item p {
    font-size: 15px;
    font-weight: 600;
    color: var(--order-text);
    margin: 0;
}

/* Plan Details Table */
.plan-details-table {
    display: flex;
    flex-direction: column;
    gap: 1px;
    background: var(--order-border);
    border-radius: 12px;
    overflow: hidden;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: white;
}

.detail-row.total-row {
    background: linear-gradient(135deg, rgba(79, 137, 232, 0.1), rgba(30, 124, 242, 0.1));
    border-top: 2px solid var(--order-primary);
}

.detail-label {
    font-size: 14px;
    color: var(--order-text-light);
    font-weight: 600;
}

.detail-value {
    font-size: 15px;
    font-weight: 700;
    color: var(--order-text);
}

.total-row .detail-label,
.total-row .detail-value {
    font-size: 16px;
    color: var(--order-primary);
}

.total-value {
    font-size: 24px !important;
}

.pets-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    background: var(--order-bg);
    border: 1px solid var(--order-border);
    border-radius: 8px;
    font-weight: 700;
}

/* Notes Card */
.notes-card-modern {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1));
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    gap: 16px;
}

.notes-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--order-info);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    flex-shrink: 0;
}

.notes-card-modern strong {
    display: block;
    color: var(--order-text);
    font-size: 14px;
    margin-bottom: 4px;
}

.notes-card-modern p {
    color: var(--order-text);
    font-size: 14px;
    margin: 0;
}

/* Proof Viewer */
.upload-date {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--order-text-light);
    margin-bottom: 20px;
    padding: 10px 14px;
    background: var(--order-bg);
    border-radius: 8px;
}

.upload-date i {
    color: var(--order-success);
}

.proof-viewer {
    text-align: center;
}

/* PDF Preview */
.pdf-preview {
    padding: 40px 20px;
}

.pdf-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 20px;
    border-radius: 20px;
    background: linear-gradient(135deg, #FEE2E2, #FCA5A5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #DC2626;
    font-size: 50px;
}

.pdf-label {
    font-size: 16px;
    font-weight: 600;
    color: var(--order-text);
    margin-bottom: 24px;
}

.pdf-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-proof {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-proof.view {
    background: linear-gradient(135deg, var(--order-primary), var(--order-primary-dark));
    color: white;
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.3);
}

.btn-proof.view:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 137, 232, 0.4);
    color: white;
}

.btn-proof.download {
    background: linear-gradient(135deg, var(--order-success), #059669);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-proof.download:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    color: white;
}

/* Image Preview */
.image-preview img {
    max-width: 100%;
    max-height: 600px;
    border-radius: 12px;
    border: 2px solid var(--order-border);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 16px;
}

.image-preview img:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.image-hint {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    color: var(--order-text-light);
    margin-bottom: 16px;
}

/* Pets Card */
.pets-card-modern {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.alert-info-modern {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 10px;
    margin-bottom: 24px;
}

.alert-info-modern i {
    color: var(--order-info);
    font-size: 18px;
    margin-top: 2px;
}

.alert-info-modern span {
    flex: 1;
    font-size: 14px;
    color: var(--order-text);
    line-height: 1.5;
}

.pets-table {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.pet-item {
    border: 1px solid var(--order-border);
    border-radius: 12px;
    padding: 16px;
    transition: all 0.3s ease;
}

.pet-item:hover {
    border-color: var(--order-primary);
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.1);
}

.pet-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.pet-avatar {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--order-primary), var(--order-primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.pet-name strong {
    font-size: 16px;
    color: var(--order-text);
    display: block;
}

.pet-name small {
    font-size: 13px;
    color: var(--order-text-light);
}

.pet-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
    margin-bottom: 12px;
}

.pet-detail {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--order-text);
}

.pet-detail i {
    color: var(--order-primary);
    width: 16px;
}

.pet-medical {
    display: flex;
    gap: 10px;
    padding: 12px;
    background: rgba(239, 68, 68, 0.05);
    border: 1px solid rgba(239, 68, 68, 0.2);
    border-radius: 8px;
    font-size: 13px;
    color: var(--order-text);
}

.pet-medical i {
    color: var(--order-danger);
    margin-top: 2px;
}

/* Pet Actions (Edit/Delete) */
.pet-actions-admin {
    display: flex;
    gap: 10px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--order-border);
}

.btn-pet-action {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-pet-action.edit {
    background: linear-gradient(135deg, var(--order-primary), var(--order-primary-dark));
    color: white;
    box-shadow: 0 2px 8px rgba(79, 137, 232, 0.2);
}

.btn-pet-action.edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.3);
    color: white;
}

.btn-pet-action.delete {
    background: linear-gradient(135deg, var(--order-danger), #DC2626);
    color: white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}

.btn-pet-action.delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-pet-action i {
    font-size: 14px;
}

/* Payment & Shipping Grid */
.payment-shipping-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.info-section {
    border: 1px solid var(--order-border);
    border-radius: 12px;
    padding: 16px;
    background: var(--order-bg);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--order-border);
}

.section-header i {
    color: var(--order-primary);
    font-size: 18px;
}

.section-header strong {
    font-size: 15px;
    color: var(--order-text);
}

.section-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Payment Badges */
.payment-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    align-self: flex-start;
}

.badge-sinpe {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
}

.badge-transfer {
    background: linear-gradient(135deg, var(--order-primary), var(--order-primary-dark));
    color: white;
    box-shadow: 0 2px 8px rgba(79, 137, 232, 0.2);
}

.payment-detail {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 10px 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid var(--order-border);
}

.payment-detail label {
    font-size: 12px;
    font-weight: 600;
    color: var(--order-text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payment-detail span {
    font-size: 14px;
    font-weight: 600;
    color: var(--order-text);
}

/* Shipping Badges */
.shipping-badge {
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    width: 100%;
}

.badge-gam {
    background: linear-gradient(135deg, #3B82F6, #2563EB);
    color: white;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
}

.badge-fuera-gam {
    background: linear-gradient(135deg, #F59E0B, #D97706);
    color: white;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.2);
}

.shipping-cost {
    font-size: 16px;
    font-weight: 800;
}

.shipping-address {
    padding: 12px;
    background: white;
    border-radius: 8px;
    border: 1px solid var(--order-border);
}

.shipping-address label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 600;
    color: var(--order-text-light);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.shipping-address label i {
    color: var(--order-danger);
}

.shipping-address p {
    font-size: 14px;
    font-weight: 500;
    color: var(--order-text);
    margin: 0;
    line-height: 1.6;
}

/* Actions Card */
.actions-card-modern {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    position: sticky;
    top: 20px;
}

.action-form {
    margin-bottom: 16px;
}

.form-group-modern {
    margin-bottom: 16px;
}

.form-group-modern label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 600;
    color: var(--order-text);
    margin-bottom: 10px;
}

.form-group-modern label i {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(79, 137, 232, 0.1), rgba(30, 124, 242, 0.15));
    color: var(--order-primary);
    border-radius: 8px;
    font-size: 16px;
}

.form-group-modern textarea {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--order-border);
    border-radius: 12px;
    font-size: 14px;
    color: var(--order-text);
    font-family: inherit;
    resize: vertical;
    min-height: 100px;
    transition: all 0.3s ease;
    background: white;
}

.form-group-modern textarea::placeholder {
    color: #9CA3AF;
    font-weight: 400;
}

.form-group-modern textarea:focus {
    outline: none;
    border-color: var(--order-primary);
    box-shadow: 0 0 0 4px rgba(79, 137, 232, 0.1);
    background: white;
}

.btn-action-full {
    width: 100%;
    padding: 14px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-action-full.success {
    background: linear-gradient(135deg, var(--order-success), #059669);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    margin-bottom: 12px;
}

.btn-action-full.success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

.btn-action-full.danger {
    background: linear-gradient(135deg, var(--order-danger), #DC2626);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-action-full.danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.btn-action-full.primary {
    background: linear-gradient(135deg, var(--order-primary), var(--order-primary-dark));
    color: white;
    box-shadow: 0 4px 12px rgba(79, 137, 232, 0.3);
}

.btn-action-full.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 137, 232, 0.4);
}

.status-info-box {
    display: flex;
    gap: 12px;
    padding: 16px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 10px;
}

.status-info-box i {
    color: var(--order-info);
    font-size: 20px;
}

.status-info-box strong {
    display: block;
    font-size: 13px;
    color: var(--order-text-light);
    margin-bottom: 4px;
}

.status-info-box p {
    font-size: 15px;
    font-weight: 600;
    color: var(--order-text);
    margin: 0;
}

/* Additional Info */
.additional-info-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid var(--order-border);
}

.additional-info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.additional-info-item i {
    color: var(--order-primary);
    font-size: 18px;
    width: 20px;
    margin-top: 2px;
}

.additional-info-item small {
    display: block;
    font-size: 12px;
    color: var(--order-text-light);
    margin-bottom: 2px;
}

.additional-info-item p {
    font-size: 14px;
    font-weight: 600;
    color: var(--order-text);
    margin: 0;
}

/* Modal */
.modern-modal .modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--order-border);
    background: var(--order-bg);
}

.modern-modal .modal-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 700;
    color: var(--order-text);
}

.modern-modal .modal-title i {
    color: var(--order-danger);
}

.modern-modal .modal-body {
    padding: 24px;
}

.modern-modal .form-group-modern textarea {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--order-border);
    border-radius: 12px;
    font-size: 14px;
    color: var(--order-text);
    font-family: inherit;
    resize: vertical;
    min-height: 100px;
    transition: all 0.3s ease;
    background: white;
}

.modern-modal .form-group-modern label i {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(220, 38, 38, 0.15));
    color: var(--order-danger);
}

.modern-modal .form-group-modern textarea::placeholder {
    color: #9CA3AF;
    font-weight: 400;
}

.modern-modal .form-group-modern textarea:focus {
    outline: none;
    border-color: var(--order-danger);
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    background: white;
}

.alert-warning-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 10px;
    margin-bottom: 20px;
}

.alert-warning-modern i {
    color: var(--order-warning);
    font-size: 18px;
}

.alert-warning-modern span {
    font-size: 14px;
    color: var(--order-text);
}

.modern-modal .modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--order-border);
    background: var(--order-bg);
}

.btn-modal {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-modal.secondary {
    background: var(--order-bg);
    color: var(--order-text);
    border: 1px solid var(--order-border);
}

.btn-modal.secondary:hover {
    background: white;
}

.btn-modal.danger {
    background: linear-gradient(135deg, var(--order-danger), #DC2626);
    color: white;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-modal.danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .order-header-modern {
        flex-direction: column;
        align-items: flex-start;
    }

    .order-header-left {
        width: 100%;
    }

    .order-status-badge {
        width: 100%;
    }

    .order-status-badge .badge-modern {
        width: 100%;
        justify-content: center;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .pet-details {
        grid-template-columns: 1fr 1fr;
    }

    .pdf-actions {
        flex-direction: column;
    }

    .btn-proof {
        width: 100%;
        justify-content: center;
    }

    .actions-card-modern {
        position: static;
    }
}

@media (max-width: 480px) {
    .order-icon-wrapper {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }

    .order-header-left h1 {
        font-size: 24px;
    }

    .pet-details {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirmación para VERIFICAR pago
    const verifyForm = document.querySelector('form[action*="verify"]');
    if (verifyForm) {
        verifyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Verificar este pago?',
                text: 'El pedido será marcado como verificado y el cliente será notificado.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<i class="fa-solid fa-check"></i> Sí, verificar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: 'swal-modern',
                    confirmButton: 'swal-btn-confirm',
                    cancelButton: 'swal-btn-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Verificando...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    verifyForm.submit();
                }
            });
        });
    }

    // Confirmación para RECHAZAR pago (en el modal)
    const rejectForm = document.querySelector('form[action*="reject"]');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const notes = rejectForm.querySelector('textarea[name="admin_notes"]').value;
            
            if (!notes.trim()) {
                Swal.fire({
                    title: 'Motivo requerido',
                    text: 'Debes especificar el motivo del rechazo',
                    icon: 'warning',
                    confirmButtonColor: '#4F89E8',
                    confirmButtonText: 'Entendido'
                });
                return;
            }
            
            Swal.fire({
                title: '¿Rechazar este pago?',
                html: `<p>El cliente recibirá un email con el siguiente motivo:</p><div style="background: #FEF2F2; border: 1px solid #FCA5A5; padding: 12px; border-radius: 8px; margin-top: 12px; text-align: left;"><strong>Motivo:</strong><br>${notes}</div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<i class="fa-solid fa-times"></i> Sí, rechazar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: 'swal-modern',
                    confirmButton: 'swal-btn-danger',
                    cancelButton: 'swal-btn-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cerrar el modal de Bootstrap primero
                    const modal = bootstrap.Modal.getInstance(document.getElementById('rejectModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Mostrar loading
                    Swal.fire({
                        title: 'Rechazando...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    rejectForm.submit();
                }
            });
        });
    }

    // Confirmación para COMPLETAR pedido
    const completeForm = document.querySelector('form[action*="complete"]');
    if (completeForm) {
        completeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '¿Marcar como completado?',
                text: 'El pedido será marcado como completado definitivamente.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4F89E8',
                cancelButtonColor: '#6B7280',
                confirmButtonText: '<i class="fa-solid fa-check-double"></i> Sí, completar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                customClass: {
                    popup: 'swal-modern',
                    confirmButton: 'swal-btn-confirm',
                    cancelButton: 'swal-btn-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Completando...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    completeForm.submit();
                }
            });
        });
    }

    // Confirmación para ELIMINAR mascota
    window.confirmDeletePet = function(button, petName) {
        const form = button.closest('form');

        Swal.fire({
            title: '¿Eliminar mascota?',
            html: `<p>Estás a punto de eliminar a <strong>${petName}</strong> de esta orden.</p><p style="color: #EF4444; margin-top: 12px;"><i class="fa-solid fa-triangle-exclamation"></i> Esta acción no se puede deshacer.</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fa-solid fa-trash"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            customClass: {
                popup: 'swal-modern',
                confirmButton: 'swal-btn-danger',
                cancelButton: 'swal-btn-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Eliminando...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                form.submit();
            }
        });
    };
});
</script>

<style>
/* SweetAlert2 Custom Styles */
.swal-modern {
    border-radius: 16px !important;
    padding: 24px !important;
}

.swal-modern .swal2-title {
    font-size: 22px !important;
    font-weight: 700 !important;
    color: #1F2937 !important;
}

.swal-modern .swal2-html-container {
    font-size: 15px !important;
    color: #6B7280 !important;
    line-height: 1.6 !important;
}

.swal-btn-confirm,
.swal-btn-danger,
.swal-btn-cancel {
    border-radius: 10px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    transition: all 0.3s ease !important;
}

.swal-btn-confirm:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4) !important;
}

.swal-btn-danger:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4) !important;
}

.swal-btn-cancel {
    background: #F3F4F6 !important;
    color: #1F2937 !important;
    box-shadow: none !important;
}

.swal-btn-cancel:hover {
    background: #E5E7EB !important;
    transform: translateY(-1px) !important;
}

.swal2-icon {
    border-width: 3px !important;
}
</style>

@endsection
