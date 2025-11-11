@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@push('styles')
<style>
    /* === Variables === */
    :root {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --primary-light: #3b82f6;
        --success: #10b981;
        --warning: #f59e0b;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --bg-page: #f8fafc;
        --bg-card: #ffffff;
        --border: #e5e7eb;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
        --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 20px 40px rgba(37, 99, 235, 0.15);
        --radius: 16px;
    }

    /* === Animations === */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-8px);
        }
    }

    @keyframes checkPop {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
    }

    * {
        -webkit-tap-highlight-color: transparent;
    }

    body {
        background: var(--bg-page);
        margin: 0;
        padding: 0;
    }

    .checkout-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 1rem;
        animation: fadeInUp 0.6s ease-out;
    }

    /* === Progress Bar Simple === */
    .progress-bar-wrapper {
        background: white;
        border-radius: var(--radius);
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .progress-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        margin-bottom: 0.75rem;
    }

    .progress-line {
        position: absolute;
        top: 20px;
        left: 30px;
        right: 30px;
        height: 4px;
        background: var(--border);
        z-index: 0;
    }

    .progress-line-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--success) 0%, var(--primary) 100%);
        width: 33%;
        transition: width 0.5s ease;
        border-radius: 4px;
    }

    .progress-step {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .progress-dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 3px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-secondary);
        transition: all 0.3s ease;
    }

    .progress-step.active .progress-dot {
        background: var(--success);
        border-color: var(--success);
        color: white;
        animation: checkPop 0.4s ease-out;
    }

    .progress-step.current .progress-dot {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
        box-shadow: 0 0 0 6px rgba(37, 99, 235, 0.1);
    }

    .progress-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-align: center;
        max-width: 80px;
    }

    .progress-step.active .progress-label,
    .progress-step.current .progress-label {
        color: var(--primary);
    }

    /* === Hero Card === */
    .hero-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 24px;
        padding: 2rem;
        color: white;
        text-align: center;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    .hero-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 8s ease-in-out infinite;
    }

    .hero-card > * {
        position: relative;
        z-index: 1;
    }

    .plan-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .hero-title {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .hero-price {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .price-currency {
        font-size: 1.5rem;
        font-weight: 700;
        opacity: 0.9;
    }

    .price-amount {
        font-size: 3.5rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -1px;
    }

    .hero-subtitle {
        font-size: 1rem;
        opacity: 0.95;
        font-weight: 400;
    }

    /* === Features Minimal === */
    .features-card {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .features-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .features-title i {
        color: var(--warning);
    }

    .features-grid {
        display: grid;
        gap: 0.75rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .feature-item:hover {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        transform: translateX(4px);
    }

    .feature-check {
        width: 24px;
        height: 24px;
        background: var(--success);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .feature-text {
        font-size: 0.9375rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* === Quantity Selector === */
    .quantity-card {
        background: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-sm);
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }

    .quantity-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .quantity-header h3 {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .quantity-header p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 1.5rem;
    }

    .quantity-btn {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    .quantity-btn:active:not(:disabled) {
        transform: translateY(0);
    }

    .quantity-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .quantity-display {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--primary);
        min-width: 60px;
        text-align: center;
    }

    .quantity-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 0.875rem;
        text-align: center;
    }

    .quantity-info small {
        color: #1e40af;
        font-weight: 600;
        font-size: 0.875rem;
    }

    /* === Summary Card - Destacada === */
    .summary-card {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 2px solid #93c5fd;
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--shadow-md);
        animation: fadeInUp 0.6s ease-out 0.4s both;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        color: #1e40af;
        font-size: 0.9375rem;
    }

    .summary-row:not(:last-child) {
        border-bottom: 1px solid rgba(37, 99, 235, 0.1);
    }

    .summary-row.total {
        padding: 1rem 0 0;
        margin-top: 0.5rem;
        border-top: 2px solid #60a5fa;
        border-bottom: none;
    }

    .summary-label {
        font-weight: 600;
    }

    .summary-value {
        font-weight: 700;
    }

    .summary-row.total .summary-label {
        font-size: 1.125rem;
        font-weight: 800;
    }

    .summary-row.total .summary-value {
        font-size: 2rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* === CTA Button - El más importante === */
    .cta-section {
        animation: fadeInUp 0.6s ease-out 0.5s both;
    }

    .btn-primary-cta {
        width: 100%;
        padding: 1.25rem 2rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: var(--radius);
        font-size: 1.125rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.4);
        position: relative;
        overflow: hidden;
    }

    .btn-primary-cta::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-primary-cta:hover::before {
        width: 400px;
        height: 400px;
    }

    .btn-primary-cta:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(37, 99, 235, 0.5);
    }

    .btn-primary-cta:active {
        transform: translateY(-2px);
    }

    .btn-primary-cta i {
        font-size: 1.25rem;
        animation: bounce 2s ease-in-out infinite;
    }

    .btn-secondary-cta {
        width: 100%;
        padding: 1rem;
        background: white;
        color: var(--text-secondary);
        border: 2px solid var(--border);
        border-radius: var(--radius);
        font-size: 0.9375rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 0.75rem;
        text-decoration: none;
    }

    .btn-secondary-cta:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        text-decoration: none;
        color: var(--text-secondary);
    }

    /* === Trust Badges === */
    .trust-badges {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
        padding: 1rem;
        animation: fadeInUp 0.6s ease-out 0.6s both;
    }

    .trust-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .trust-badge i {
        color: var(--success);
        font-size: 1rem;
    }

    /* === Responsive === */
    @media (max-width: 768px) {
        .checkout-container {
            padding: 0.75rem;
        }

        .progress-bar-wrapper {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .progress-dot {
            width: 36px;
            height: 36px;
            font-size: 0.875rem;
        }

        .progress-label {
            font-size: 0.6875rem;
            max-width: 70px;
        }

        .progress-line {
            left: 20px;
            right: 20px;
        }

        .hero-card {
            padding: 1.5rem;
            border-radius: 20px;
            margin-bottom: 1rem;
        }

        .hero-title {
            font-size: 1.5rem;
        }

        .price-amount {
            font-size: 2.5rem;
        }

        .price-currency {
            font-size: 1.25rem;
        }

        .hero-subtitle {
            font-size: 0.875rem;
        }

        .features-card,
        .quantity-card,
        .summary-card {
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .quantity-control {
            gap: 1.5rem;
        }

        .quantity-btn {
            width: 44px;
            height: 44px;
            font-size: 1.25rem;
        }

        .quantity-display {
            font-size: 2rem;
        }

        .summary-row.total .summary-value {
            font-size: 1.75rem;
        }

        .btn-primary-cta {
            padding: 1rem 1.5rem;
            font-size: 1rem;
        }

        .trust-badges {
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1rem;
        }
    }

    @media (max-width: 480px) {
        .checkout-container {
            padding: 0.5rem;
        }

        .progress-bar-wrapper {
            padding: 0.875rem;
        }

        .progress-dot {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }

        .progress-label {
            font-size: 0.625rem;
            max-width: 60px;
        }

        .hero-card {
            padding: 1.25rem;
        }

        .hero-title {
            font-size: 1.25rem;
        }

        .price-amount {
            font-size: 2rem;
        }

        .features-card,
        .quantity-card,
        .summary-card {
            padding: 1rem;
        }

        .features-title {
            font-size: 0.9375rem;
        }

        .feature-item {
            padding: 0.625rem;
        }

        .feature-text {
            font-size: 0.875rem;
        }

        .quantity-header h3 {
            font-size: 1rem;
        }

        .quantity-header p {
            font-size: 0.8125rem;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            font-size: 1.125rem;
        }

        .quantity-display {
            font-size: 1.75rem;
            min-width: 50px;
        }

        .summary-row {
            font-size: 0.875rem;
        }

        .summary-row.total .summary-label {
            font-size: 1rem;
        }

        .summary-row.total .summary-value {
            font-size: 1.5rem;
        }

        .btn-primary-cta {
            padding: 0.875rem 1.25rem;
            font-size: 0.9375rem;
        }
    }
</style>
@endpush

@section('content')
<div class="checkout-container">

    <!-- Progress Bar Simple -->
    <div class="progress-bar-wrapper">
        <div class="progress-steps">
            <div class="progress-line">
                <div class="progress-line-fill"></div>
            </div>

            <div class="progress-step current">
                <div class="progress-dot">1</div>
                <span class="progress-label">Plan</span>
            </div>

            <div class="progress-step">
                <div class="progress-dot">2</div>
                <span class="progress-label">Realizar Pago</span>
            </div>

            <div class="progress-step">
                <div class="progress-dot">3</div>
                <span class="progress-label">Confirmación</span>
            </div>
        </div>
        <div style="text-align: center; margin-top: 0.5rem;">
            <small style="color: var(--text-secondary); font-weight: 600;">Paso 2 de 4</small>
        </div>
    </div>

    <!-- Hero Card con Plan -->
    <div class="hero-card">
        <span class="plan-badge">
            <i class="fa-solid fa-{{ $plan->type === 'one_time' ? 'tag' : 'rotate' }}"></i>
            <span>{{ $plan->type === 'one_time' ? 'Pago Único' : 'Suscripción' }}</span>
        </span>
        
        <h1 class="hero-title">{{ $plan->name }}</h1>
        
        <div class="hero-price">
            <span class="price-currency">₡</span>
            <span class="price-amount">{{ number_format($plan->price, 0, ',', '.') }}</span>
        </div>
        
        <p class="hero-subtitle">
            @if($plan->type === 'one_time')
                Un solo pago, sin renovaciones
            @else
                Cada {{ $plan->duration_months }} {{ Str::plural('mes', $plan->duration_months) }}
            @endif
        </p>
    </div>

    <!-- Features -->
    <div class="features-card">
        <div class="features-title">
            <i class="fa-solid fa-sparkles"></i>
            <span>Lo que incluye:</span>
        </div>
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-check">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">{{ $plan->pets_included }} {{ Str::plural('placa', $plan->pets_included) }} QR inteligente</span>
            </div>
            <div class="feature-item">
                <div class="feature-check">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Perfil digital completo</span>
            </div>
            <div class="feature-item">
                <div class="feature-check">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Actualizaciones ilimitadas</span>
            </div>
            <div class="feature-item">
                <div class="feature-check">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Sistema de recompensas</span>
            </div>
            <div class="feature-item">
                <div class="feature-check">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span class="feature-text">Soporte por WhatsApp</span>
            </div>
            @if($plan->type === 'subscription')
            <div class="feature-item">
                <div class="feature-check">
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
        <div class="quantity-card">
            <div class="quantity-header">
                <h3>¿Cuántas mascotas quieres proteger?</h3>
                <p>Puedes agregar más mascotas ahora</p>
            </div>

            <div class="quantity-control">
                <button type="button" class="quantity-btn" onclick="decreaseQuantity()">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <div class="quantity-display" id="quantity-display">3</div>
                <button type="button" class="quantity-btn" onclick="increaseQuantity()">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>

            <input type="hidden" name="pets_quantity" id="pets_quantity" value="3">

            <div class="quantity-info">
                <small>
                    <i class="fa-solid fa-info-circle"></i>
                    Cada mascota adicional: <strong>₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}</strong>
                </small>
            </div>
        </div>

        <!-- Summary con cálculo -->
        <div class="summary-card">
            <div class="summary-row">
                <span class="summary-label">Plan base (3 mascotas)</span>
                <span class="summary-value">₡{{ number_format($plan->price, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row" id="additional-row" style="display: none;">
                <span class="summary-label">
                    <span id="additional-count">0</span> adicional(es)
                </span>
                <span class="summary-value">₡<span id="additional-amount">0</span></span>
            </div>
            <div class="summary-row total">
                <span class="summary-label">Total a pagar</span>
                <span class="summary-value">₡<span id="total-amount">{{ number_format($plan->price, 0, ',', '.') }}</span></span>
            </div>
        </div>
        @else
        <!-- Para planes de 1 o 2 mascotas, cantidad fija -->
        <input type="hidden" name="pets_quantity" value="{{ $plan->pets_included }}">

        <div class="summary-card">
            <div class="summary-row total">
                <span class="summary-label">Total a pagar</span>
                <span class="summary-value">₡{{ number_format($plan->price, 0, ',', '.') }}</span>
            </div>
        </div>
        @endif

        <!-- CTA Section -->
        <div class="cta-section">
            <button type="submit" class="btn-primary-cta">
                <span>Continuar al pago seguro</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>

            <a href="{{ route('home') }}#planes" class="btn-secondary-cta">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Volver a planes</span>
            </a>

            <!-- Trust Badges -->
            <div class="trust-badges">
                <div class="trust-badge">
                    <i class="fa-solid fa-shield-check"></i>
                    <span>Pago seguro</span>
                </div>
                <div class="trust-badge">
                    <i class="fa-solid fa-lock"></i>
                    <span>Datos protegidos</span>
                </div>
                <div class="trust-badge">
                    <i class="fa-solid fa-undo"></i>
                    <span>Garantía total</span>
                </div>
            </div>
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
    const btns = document.querySelectorAll('.quantity-btn');
    const decreaseBtn = btns[0];
    const increaseBtn = btns[1];
    
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
