@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@push('styles')
<style>
    .checkout-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .plan-header {
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        color: white;
        border-radius: 24px;
        padding: 48px;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(78, 137, 232, 0.3);
    }

    .plan-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(-10%, -10%) scale(1.1); }
    }

    .plan-header > * {
        position: relative;
        z-index: 1;
    }

    .plan-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 10px 24px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #2563eb;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .price-display {
        font-size: 4.5rem;
        font-weight: 900;
        line-height: 1;
        text-shadow: 0 4px 20px rgba(0,0,0,0.2);
        margin: 24px 0;
        letter-spacing: -2px;
    }

    .price-currency {
        font-size: 2.2rem;
        vertical-align: super;
        margin-right: 8px;
        opacity: 0.9;
    }

    .plan-period {
        font-size: 1.125rem;
        opacity: 0.95;
        margin-top: 12px;
        font-weight: 300;
    }

    .features-card {
        background: white;
        border-radius: 24px;
        padding: 40px;
        margin-top: -20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(78, 137, 232, 0.1);
    }

    .features-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 32px;
        color: #1a202c;
    }

    .features-title i {
        font-size: 1.75rem;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin: 0;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
    }

    .feature-item:hover {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-color: #93c5fd;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(78, 137, 232, 0.15);
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        font-size: 1.125rem;
    }

    .feature-text {
        font-size: 0.9375rem;
        font-weight: 500;
        color: #334155;
        line-height: 1.4;
    }

    .quantity-selector {
        background: white;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        margin-top: 32px;
        border: 1px solid rgba(78, 137, 232, 0.1);
    }

    .quantity-title {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 32px;
        color: #1a202c;
    }

    .quantity-title i {
        color: #4e89e8;
        font-size: 1.75rem;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 28px;
        margin: 32px 0;
    }

    .quantity-btn {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        border: none;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        color: white;
        font-size: 1.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 6px 20px rgba(78, 137, 232, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn:hover:not(:disabled) {
        transform: translateY(-4px) scale(1.05);
        box-shadow: 0 12px 28px rgba(78, 137, 232, 0.4);
    }

    .quantity-btn:active:not(:disabled) {
        transform: translateY(-2px) scale(1.02);
    }

    .quantity-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .quantity-display {
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        min-width: 100px;
        text-align: center;
        letter-spacing: -1px;
    }

    .quantity-helper {
        text-align: center;
        padding: 16px;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 12px;
        margin-top: 24px;
        border: 1px solid #bae6fd;
    }

    .quantity-helper small {
        color: #0369a1;
        font-weight: 600;
        font-size: 0.9375rem;
    }

    .total-box {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 2px solid #93c5fd;
        border-radius: 20px;
        padding: 32px;
        margin-top: 32px;
        box-shadow: 0 8px 25px rgba(78, 137, 232, 0.1);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid rgba(78, 137, 232, 0.15);
        color: #1e40af;
        font-weight: 500;
    }

    .total-row:last-child {
        border-bottom: none;
        padding-top: 24px;
        margin-top: 16px;
        border-top: 2px solid #60a5fa;
    }

    .total-label {
        font-size: 1.125rem;
        font-weight: 600;
    }

    .total-final {
        font-size: 2.75rem;
        font-weight: 900;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    .action-buttons {
        display: flex;
        gap: 16px;
        margin-top: 40px;
    }

    .btn-checkout {
        flex: 1;
        padding: 20px 32px;
        font-size: 1.125rem;
        font-weight: 700;
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        letter-spacing: 0.3px;
    }

    .btn-checkout.btn-outline-secondary {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .btn-checkout.btn-outline-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .btn-checkout.btn-primary {
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(78, 137, 232, 0.4);
    }

    .btn-checkout.btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(78, 137, 232, 0.5);
    }

    .btn-checkout.btn-primary:active {
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .price-display {
            font-size: 3.5rem;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .quantity-display {
            font-size: 3rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5 checkout-container">

    <!-- Header del Plan -->
    <div class="plan-header">
        <div class="mb-4">
            <span class="plan-badge">
                {{ $plan->type === 'one_time' ? 'Pago Único' : 'Suscripción' }}
            </span>
        </div>
        <h1 class="fw-bold mb-3" style="font-size: 2.5rem;">{{ $plan->name }}</h1>
        <div class="price-display">
            <span class="price-currency">₡</span>{{ number_format($plan->price, 0, ',', '.') }}
        </div>
        <p class="plan-period">
            @if($plan->type === 'one_time')
                Pago único - Sin renovaciones
            @else
                Renovación cada {{ $plan->duration_months }} {{ Str::plural('mes', $plan->duration_months) }}
            @endif
        </p>
    </div>

    <!-- Características -->
    <div class="features-card">
        <div class="features-title">
            <i class="fa-solid fa-star"></i>
            <span>Incluye:</span>
        </div>
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">{{ $plan->pets_included }} {{ Str::plural('placa', $plan->pets_included) }} QR</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Perfil digital completo</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Actualizaciones ilimitadas</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Sistema de recompensas</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Soporte por WhatsApp</span>
            </div>
            @if($plan->type === 'subscription')
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Renovación automática</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Formulario -->
    <form action="{{ route('checkout.create', $plan) }}" method="POST" id="checkoutForm">
        @csrf

        <!-- Selector de cantidad SOLO para planes de 3 mascotas -->
        @if($plan->pets_included == 3)
        <div class="quantity-selector">
            <div class="quantity-title">
                <i class="fa-solid fa-paw"></i>
                <span>¿Cuántas mascotas deseas registrar?</span>
            </div>

            <div class="quantity-control">
                <button type="button" class="quantity-btn" onclick="decreaseQuantity()">−</button>
                <div class="quantity-display" id="quantity-display">3</div>
                <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
            </div>

            <input type="hidden" name="pets_quantity" id="pets_quantity" value="3">

            <div class="quantity-helper">
                <small>Mascota adicional: <strong>₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}</strong></small>
            </div>

            <!-- Cálculo del total -->
            <div class="total-box">
                <div class="total-row">
                    <span class="total-label">Plan base (3 mascotas):</span>
                    <strong style="font-size: 1.125rem;">₡{{ number_format($plan->price, 0, ',', '.') }}</strong>
                </div>
                <div class="total-row" id="additional-row" style="display: none;">
                    <span class="total-label">
                        <span id="additional-count">0</span> {{ Str::plural('mascota', 1) }} adicional(es):
                    </span>
                    <strong style="font-size: 1.125rem;">₡<span id="additional-amount">0</span></strong>
                </div>
                <div class="total-row">
                    <span class="h4 mb-0" style="font-weight: 800;">TOTAL:</span>
                    <span class="total-final">₡<span id="total-amount">{{ number_format($plan->price, 0, ',', '.') }}</span></span>
                </div>
            </div>
        </div>
        @else
        <!-- Para planes de 1 o 2 mascotas, cantidad fija -->
        <input type="hidden" name="pets_quantity" value="{{ $plan->pets_included }}">

        <div class="total-box" style="margin-top: 32px;">
            <div class="total-row">
                <span class="h4 mb-0" style="font-weight: 800;">TOTAL A PAGAR:</span>
                <span class="total-final">₡{{ number_format($plan->price, 0, ',', '.') }}</span>
            </div>
        </div>
        @endif

        <!-- Botones de acción -->
        <div class="action-buttons">
            <a href="{{ route('home') }}#planes" class="btn btn-outline-secondary btn-checkout">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Volver</span>
            </a>
            <button type="submit" class="btn btn-primary btn-checkout">
                <span>Continuar al Pago</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </form>
</div>

@if($plan->pets_included == 3)
<script>
const planData = {
    basePrice: {{ $plan->price }},
    additionalPrice: {{ $plan->additional_pet_price }},
    minQuantity: 3,
    maxQuantity: 10
};

let currentQuantity = 3;

function updateTotal() {
    const additionalPets = Math.max(0, currentQuantity - planData.minQuantity);
    const additionalCost = additionalPets * planData.additionalPrice;
    const total = planData.basePrice + additionalCost;

    // Actualizar display
    document.getElementById('quantity-display').textContent = currentQuantity;
    document.getElementById('pets_quantity').value = currentQuantity;
    document.getElementById('total-amount').textContent = total.toLocaleString('es-CR');

    // Mostrar/ocultar costo adicional
    const additionalRow = document.getElementById('additional-row');
    if (additionalPets > 0) {
        additionalRow.style.display = 'flex';
        document.getElementById('additional-count').textContent = additionalPets;
        document.getElementById('additional-amount').textContent = additionalCost.toLocaleString('es-CR');
    } else {
        additionalRow.style.display = 'none';
    }

    // Actualizar estado de botones
    updateButtons();
}

function updateButtons() {
    const decreaseBtn = document.querySelector('.quantity-btn:first-of-type');
    const increaseBtn = document.querySelector('.quantity-btn:last-of-type');
    
    decreaseBtn.disabled = currentQuantity <= planData.minQuantity;
    increaseBtn.disabled = currentQuantity >= planData.maxQuantity;
}

function increaseQuantity() {
    if (currentQuantity < planData.maxQuantity) {
        currentQuantity++;
        updateTotal();
    }
}

function decreaseQuantity() {
    if (currentQuantity > planData.minQuantity) {
        currentQuantity--;
        updateTotal();
    }
}

// Inicializar
updateTotal();
</script>
@endif
@endsection
