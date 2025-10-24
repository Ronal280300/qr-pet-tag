@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@push('styles')
<style>
    .checkout-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .plan-header {
        background: linear-gradient(135deg, #4e89e8 0%, #0e61c6 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 40px;
        text-align: center;
    }

    .price-display {
        font-size: 4rem;
        font-weight: 800;
        line-height: 1;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .price-currency {
        font-size: 2rem;
        vertical-align: super;
        margin-right: 5px;
    }

    .plan-period {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-top: 10px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin: 30px 0;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .feature-item:hover {
        background: #e3f2fd;
        transform: translateX(5px);
    }

    .feature-icon {
        width: 35px;
        height: 35px;
        background: #10b981;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .quantity-selector {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin: 20px 0;
    }

    .quantity-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid #4e89e8;
        background: white;
        color: #4e89e8;
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: #4e89e8;
        color: white;
        transform: scale(1.1);
    }

    .quantity-display {
        font-size: 3rem;
        font-weight: 800;
        color: #4e89e8;
        min-width: 80px;
        text-align: center;
    }

    .total-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #4e89e8;
        border-radius: 15px;
        padding: 25px;
        margin-top: 30px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(78, 137, 232, 0.2);
    }

    .total-row:last-child {
        border-bottom: none;
        padding-top: 20px;
        margin-top: 10px;
        border-top: 2px solid #4e89e8;
    }

    .total-final {
        font-size: 2.5rem;
        font-weight: 800;
        color: #4e89e8;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-checkout {
        flex: 1;
        padding: 18px;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
</style>
@endpush

@section('content')
<div class="container py-5 checkout-container">

    <!-- Header del Plan -->
    <div class="plan-header">
        <div class="mb-3">
            <span class="badge bg-white text-primary px-4 py-2">
                {{ $plan->type === 'one_time' ? 'Pago Único' : 'Suscripción' }}
            </span>
        </div>
        <h1 class="fw-bold mb-3">{{ $plan->name }}</h1>
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
    <div class="card border-0 shadow-sm" style="border-radius: 0 0 20px 20px;">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-star text-warning me-2"></i>Incluye:</h5>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span>{{ $plan->pets_included }} {{ Str::plural('placa', $plan->pets_included) }} QR</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span>Perfil digital completo</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span>Actualizaciones ilimitadas</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span>Sistema de recompensas</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span>Soporte por WhatsApp</span>
                </div>
                @if($plan->type === 'subscription')
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <span>Renovación automática</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <form action="{{ route('checkout.create', $plan) }}" method="POST" id="checkoutForm">
        @csrf

        <!-- Selector de cantidad SOLO para planes de 3 mascotas -->
        @if($plan->pets_included == 3)
        <div class="quantity-selector mt-4">
            <h4 class="text-center fw-bold mb-4">
                <i class="fa-solid fa-paw text-primary me-2"></i>
                ¿Cuántas mascotas deseas registrar?
            </h4>

            <div class="quantity-control">
                <button type="button" class="quantity-btn" onclick="decreaseQuantity()">-</button>
                <div class="quantity-display" id="quantity-display">3</div>
                <button type="button" class="quantity-btn" onclick="increaseQuantity()">+</button>
            </div>

            <input type="hidden" name="pets_quantity" id="pets_quantity" value="3">

            <p class="text-center text-muted mb-0">
                <small>Mascota adicional: <strong>₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}</strong></small>
            </p>

            <!-- Cálculo del total -->
            <div class="total-box">
                <div class="total-row">
                    <span>Plan base (3 mascotas):</span>
                    <strong>₡{{ number_format($plan->price, 0, ',', '.') }}</strong>
                </div>
                <div class="total-row" id="additional-row" style="display: none;">
                    <span>
                        <span id="additional-count">0</span> {{ Str::plural('mascota', 1) }} adicional(es):
                    </span>
                    <strong>₡<span id="additional-amount">0</span></strong>
                </div>
                <div class="total-row">
                    <span class="h4 mb-0">TOTAL:</span>
                    <span class="total-final">₡<span id="total-amount">{{ number_format($plan->price, 0, ',', '.') }}</span></span>
                </div>
            </div>
        </div>
        @else
        <!-- Para planes de 1 o 2 mascotas, cantidad fija -->
        <input type="hidden" name="pets_quantity" value="{{ $plan->pets_included }}">

        <div class="total-box mt-4">
            <div class="total-row">
                <span class="h4 mb-0">TOTAL A PAGAR:</span>
                <span class="total-final">₡{{ number_format($plan->price, 0, ',', '.') }}</span>
            </div>
        </div>
        @endif

        <!-- Botones de acción -->
        <div class="action-buttons">
            <a href="{{ route('home') }}#planes" class="btn btn-outline-secondary btn-checkout">
                <i class="fa-solid fa-arrow-left me-2"></i> Volver
            </a>
            <button type="submit" class="btn btn-primary btn-checkout">
                Continuar al Pago <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </div>
    </form>
</div>

@if($plan->pets_included == 3)
<script>
const planData = {
    basePrice: {{ $plan->price }},
    additionalPrice: {{ $plan->additional_pet_price }},
    minQuantity: 3
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
}

function increaseQuantity() {
    if (currentQuantity < 10) {
        currentQuantity++;
        updateTotal();
    }
}

function decreaseQuantity() {
    if (currentQuantity > 3) {
        currentQuantity--;
        updateTotal();
    }
}

// Inicializar
updateTotal();
</script>
@endif
@endsection
