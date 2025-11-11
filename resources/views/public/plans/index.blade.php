@extends('layouts.app')

@section('title', 'Planes - QR Pet Tag')

@section('content')
<div class="plans-page-modern">
    <!-- Header -->
    <div class="container py-5">
        <div class="text-center mb-5 reveal">
            <div class="plan-badge-modern">
                <span class="plan-badge-icon"></span>
                <span>PLANES</span>
            </div>
            <h1 class="display-title-modern mb-3">
                Elige el <span class="plan-highlight-modern">plan perfecto</span> para ti
            </h1>
            <p class="lead-modern">Protege a tus mascotas con el plan que mejor se adapte a tus necesidades</p>
        </div>

        <!-- Toggle Minimalista -->
        <div class="plan-type-selector reveal">
            <div class="selector-track">
                <div class="selector-background onetime-active" id="selectorBackground"></div>
                <button class="selector-option active" data-type="oneTime">
                    <span class="selector-icon"></span>
                    <span class="selector-text">
                        <strong>Pago Unico</strong>
                        <small>Una sola vez</small>
                    </span>
                </button>
                <button class="selector-option" data-type="subscription">
                    <span class="selector-icon"></span>
                    <span class="selector-text">
                        <strong>Suscripción</strong>
                        <small>Renovación automatica</small>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- PAGO ÃšNICO -->
    <div class="plans-container active" id="oneTimePlans">
        <div class="container-fluid">
            <div class="plans-scroll-wrapper">
                <button class="scroll-nav scroll-nav-left" data-direction="left">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                
                <div class="plans-scroll">
                    @foreach($oneTimePlans as $plan)
                    <div class="plan-card-modern onetime-plan">
                        @if($plan->pets_included == 3)
                        <div class="popular-badge-modern">
                            <i class="fa-solid fa-crown"></i>
                            <span>Popular</span>
                        </div>
                        @endif

                        <div class="plan-header-modern">
                            <div class="plan-icon-modern onetime-icon">
                                <i class="fa-solid fa-dog"></i>
                            </div>
                            <h3 class="plan-title-modern">{{ $plan->pets_included }} {{ Str::plural('Mascota', $plan->pets_included) }}</h3>
                        </div>

                        <div class="plan-price-modern">
                            <span class="price-currency">â‚¡</span>
                            <span class="price-amount">{{ number_format($plan->price, 0, ',', '.') }}</span>
                        </div>
                        <p class="plan-billing-modern">Pago Ãºnico</p>

                        <div class="plan-divider-modern"></div>

                        <ul class="plan-features-modern">
                            <li>
                                <i class="fa-solid fa-check-circle"></i>
                                <span>{{ $plan->pets_included }} {{ Str::plural('placa', $plan->pets_included) }} con QR</span>
                            </li>
                            <li>
                                <i class="fa-solid fa-check-circle"></i>
                                <span>Perfil digital completo</span>
                            </li>
                            <li>
                                <i class="fa-solid fa-check-circle"></i>
                                <span>Actualizaciones ilimitadas</span>
                            </li>
                            <li>
                                <i class="fa-solid fa-check-circle"></i>
                                <span>Sistema de recompensas</span>
                            </li>
                            <li>
                                <i class="fa-solid fa-check-circle"></i>
                                <span>Soporte por WhatsApp</span>
                            </li>
                        </ul>

                        <div class="plan-footer-modern">
                            <a href="{{ route('checkout.show', $plan) }}" class="btn-plan-modern onetime-btn">
                                <i class="fa-solid fa-shopping-cart"></i>
                                <span>Elegir plan</span>
                            </a>
                            @if($plan->pets_included >= 3)
                            <p class="plan-extra-info">
                                <i class="fa-solid fa-plus-circle"></i>
                                Mascota adicional: â‚¡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <button class="scroll-nav scroll-nav-right" data-direction="right">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <div class="scroll-indicators" id="oneTimeIndicators"></div>

            <div class="plan-info-box">
                <i class="fa-solid fa-info-circle"></i>
                <span>DespuÃ©s de 3 mascotas, cada mascota adicional tiene un costo de â‚¡10,000</span>
            </div>
        </div>
    </div>

    <!-- SUSCRIPCIONES -->
    <div class="plans-container" id="subscriptionPlans">
        <div class="container-fluid">
            <!-- Duration selector -->
            <div class="duration-selector">
                @foreach($subscriptionPlans as $months => $plans)
                <button class="duration-btn {{ $loop->first ? 'active' : '' }}" data-duration="{{ $months }}">
                    {{ $months }} {{ Str::plural('mes', $months) }}
                </button>
                @endforeach
            </div>

            @foreach($subscriptionPlans as $months => $plans)
            <div class="duration-content {{ $loop->first ? 'active' : '' }}" data-duration="{{ $months }}">
                <div class="plans-scroll-wrapper">
                    <button class="scroll-nav scroll-nav-left" data-direction="left">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    
                    <div class="plans-scroll">
                        @foreach($plans as $plan)
                        <div class="plan-card-modern subscription-plan">
                            @if($plan->pets_included == 3)
                            <div class="popular-badge-modern">
                                <i class="fa-solid fa-crown"></i>
                                <span>Popular</span>
                            </div>
                            @endif

                            <div class="plan-header-modern">
                                <div class="plan-icon-modern subscription-icon">
                                    <i class="fa-solid fa-paw"></i>
                                </div>
                                <h3 class="plan-title-modern">{{ $plan->pets_included }} {{ Str::plural('Mascota', $plan->pets_included) }}</h3>
                            </div>

                            <div class="plan-price-modern">
                                <span class="price-currency">â‚¡</span>
                                <span class="price-amount">{{ number_format($plan->price, 0, ',', '.') }}</span>
                            </div>
                            <p class="plan-billing-modern">Cada {{ $months }} {{ Str::plural('mes', $months) }}</p>

                            <div class="plan-divider-modern"></div>

                            <ul class="plan-features-modern">
                                <li>
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>{{ $plan->pets_included }} {{ Str::plural('placa', $plan->pets_included) }} con QR</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>Perfil digital completo</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>Actualizaciones ilimitadas</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>Sistema de recompensas</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>Soporte prioritario</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-check-circle"></i>
                                    <span>RenovaciÃ³n automÃ¡tica</span>
                                </li>
                            </ul>

                            <div class="plan-footer-modern">
                                <a href="{{ route('checkout.show', $plan) }}" class="btn-plan-modern subscription-btn">
                                    <i class="fa-solid fa-shopping-cart"></i>
                                    <span>Elegir plan</span>
                                </a>
                                @if($plan->pets_included >= 3)
                                <p class="plan-extra-info">
                                    <i class="fa-solid fa-plus-circle"></i>
                                    Mascota adicional: â‚¡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button class="scroll-nav scroll-nav-right" data-direction="right">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <div class="scroll-indicators" data-duration="{{ $months }}"></div>

                <div class="plan-info-box">
                    <i class="fa-solid fa-info-circle"></i>
                    <span>DespuÃ©s de 3 mascotas, cada mascota adicional cuesta â‚¡{{ number_format($plans->first()->additional_pet_price, 0, ',', '.') }} por {{ $months }} {{ Str::plural('mes', $months) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Benefits -->
    <div class="container py-5">
        <div class="benefits-grid reveal">
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <span>GarantÃ­a 30 dÃ­as</span>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <span>EnvÃ­o incluido</span>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <span>Soporte 24/7</span>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   MODERN MINIMALIST PLANS PAGE
   ============================================ */

:root {
    --plan-primary: #4F89E8;
    --plan-primary-dark: #1E7CF2;
    --plan-subscription: #10B981;
    --plan-subscription-dark: #059669;
    --plan-popular: #F59E0B;
    --plan-text: #1F2937;
    --plan-text-light: #6B7280;
    --plan-border: #E5E7EB;
    --plan-bg: #FFFFFF;
    --plan-bg-light: #F9FAFB;
}

.plans-page-modern {
    background: white;
    min-height: 100vh;
}

/* Header Styles */
.plan-badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: var(--plan-bg-light);
    border: 1px solid var(--plan-border);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 700;
    color: var(--plan-text);
    letter-spacing: 0.5px;
}

.plan-badge-icon {
    font-size: 16px;
}

.display-title-modern {
    font-size: 42px;
    font-weight: 800;
    color: var(--plan-text);
    line-height: 1.2;
}

.plan-highlight-modern {
    background: linear-gradient(135deg, var(--plan-primary), var(--plan-primary-dark));
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.lead-modern {
    font-size: 16px;
    color: var(--plan-text-light);
    max-width: 600px;
    margin: 0 auto;
}

/* Plan Type Selector (Minimalist Toggle) */
.plan-type-selector {
    max-width: 500px;
    margin: 0 auto 60px;
}

.selector-track {
    position: relative;
    display: flex;
    gap: 8px;
    padding: 6px;
    background: var(--plan-bg-light);
    border-radius: 16px;
    border: 1px solid var(--plan-border);
}

.selector-background {
    position: absolute;
    top: 6px;
    left: 6px;
    width: calc(50% - 10px);
    height: calc(100% - 12px);
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 0;
}

.selector-background.subscription-active {
    transform: translateX(calc(100% + 8px));
    background: linear-gradient(135deg, var(--plan-subscription), var(--plan-subscription-dark));
}

.selector-background.onetime-active {
    background: linear-gradient(135deg, var(--plan-primary), var(--plan-primary-dark));
}

.selector-option {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px 20px;
    background: transparent;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.selector-icon {
    font-size: 24px;
    transition: all 0.3s ease;
}

.selector-text {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
}

.selector-text strong {
    font-size: 15px;
    font-weight: 700;
    color: var(--plan-text);
    transition: color 0.3s ease;
}

.selector-text small {
    font-size: 12px;
    color: var(--plan-text-light);
    transition: color 0.3s ease;
}

.selector-option.active .selector-text strong,
.selector-option.active .selector-text small {
    color: white;
}

/* Plans Container */
.plans-container {
    display: none;
    margin-bottom: 40px;
    padding: 20px 0;
}

.plans-container.active {
    display: block;
    animation: fadeInUp 0.5s ease;
}

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

/* Scroll Wrapper */
.plans-scroll-wrapper {
    position: relative;
    margin: 0 auto;
    padding: 20px 60px;
    max-width: 1400px;
}

.plans-scroll {
    display: flex;
    gap: 24px;
    overflow-x: auto;
    overflow-y: hidden;
    scroll-behavior: smooth;
    scroll-snap-type: x mandatory;
    padding: 10px 20px 30px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.plans-scroll::-webkit-scrollbar {
    display: none;
}

/* Scroll Navigation Buttons */
.scroll-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: white;
    border: 1px solid var(--plan-border);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.3s ease;
    color: var(--plan-text);
}

.scroll-nav:hover {
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    border-color: var(--plan-primary);
    color: var(--plan-primary);
}

.scroll-nav-left {
    left: 10px;
}

.scroll-nav-right {
    right: 10px;
}

.scroll-nav:disabled {
    opacity: 0.3;
    cursor: not-allowed;
    transform: translateY(-50%) scale(0.9);
}

/* Plan Card Modern */
.plan-card-modern {
    min-width: 320px;
    max-width: 320px;
    background: white;
    border-radius: 24px;
    border: 2px solid var(--plan-border);
    padding: 32px 28px;
    scroll-snap-align: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    display: flex;
    flex-direction: column;
}

.plan-card-modern:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.onetime-plan {
    border-color: rgba(79, 137, 232, 0.3);
}

.onetime-plan:hover {
    border-color: var(--plan-primary);
    box-shadow: 0 20px 40px rgba(79, 137, 232, 0.15);
}

.subscription-plan {
    border-color: rgba(16, 185, 129, 0.3);
}

.subscription-plan:hover {
    border-color: var(--plan-subscription);
    box-shadow: 0 20px 40px rgba(16, 185, 129, 0.15);
}

/* Popular Badge */
.popular-badge-modern {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: linear-gradient(135deg, var(--plan-popular), #F97316);
    color: white;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

/* Plan Header */
.plan-header-modern {
    text-align: center;
    margin-bottom: 24px;
}

.plan-icon-modern {
    width: 72px;
    height: 72px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    margin: 0 auto 16px;
    color: white;
}

.onetime-icon {
    background: linear-gradient(135deg, var(--plan-primary), var(--plan-primary-dark));
    box-shadow: 0 8px 24px rgba(79, 137, 232, 0.3);
}

.subscription-icon {
    background: linear-gradient(135deg, var(--plan-subscription), var(--plan-subscription-dark));
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
}

.plan-title-modern {
    font-size: 22px;
    font-weight: 800;
    color: var(--plan-text);
    margin: 0;
}

/* Plan Price */
.plan-price-modern {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 4px;
    margin: 16px 0 8px;
}

.price-currency {
    font-size: 20px;
    font-weight: 600;
    color: var(--plan-text-light);
}

.price-amount {
    font-size: 48px;
    font-weight: 900;
    color: var(--plan-text);
    line-height: 1;
    letter-spacing: -1px;
}

.plan-billing-modern {
    text-align: center;
    font-size: 14px;
    color: var(--plan-text-light);
    margin: 0 0 24px;
}

/* Plan Divider */
.plan-divider-modern {
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--plan-border), transparent);
    margin: 24px 0;
}

/* Plan Features */
.plan-features-modern {
    list-style: none;
    padding: 0;
    margin: 0 0 32px;
    flex: 1;
}

.plan-features-modern li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    font-size: 14px;
    color: var(--plan-text);
}

.plan-features-modern i {
    color: var(--plan-subscription);
    font-size: 16px;
    margin-top: 2px;
    flex-shrink: 0;
}

/* Plan Footer */
.plan-footer-modern {
    margin-top: auto;
}

.btn-plan-modern {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 16px 24px;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 700;
    color: white;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.onetime-btn {
    background: linear-gradient(135deg, var(--plan-primary), var(--plan-primary-dark));
    box-shadow: 0 8px 20px rgba(79, 137, 232, 0.3);
}

.onetime-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(79, 137, 232, 0.4);
    color: white;
}

.subscription-btn {
    background: linear-gradient(135deg, var(--plan-subscription), var(--plan-subscription-dark));
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
}

.subscription-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(16, 185, 129, 0.4);
    color: white;
}

.plan-extra-info {
    text-align: center;
    font-size: 12px;
    color: var(--plan-text-light);
    margin: 12px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.plan-extra-info i {
    color: var(--plan-primary);
}

/* Scroll Indicators */
.scroll-indicators {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin: 20px 0;
}

.scroll-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--plan-border);
    transition: all 0.3s ease;
    cursor: pointer;
}

.scroll-indicator.active {
    width: 32px;
    border-radius: 4px;
    background: var(--plan-primary);
}

/* Duration Selector */
.duration-selector {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.duration-btn {
    padding: 12px 28px;
    background: var(--plan-bg-light);
    border: 1px solid var(--plan-border);
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    color: var(--plan-text);
    cursor: pointer;
    transition: all 0.3s ease;
}

.duration-btn:hover {
    border-color: var(--plan-subscription);
    color: var(--plan-subscription);
}

.duration-btn.active {
    background: linear-gradient(135deg, var(--plan-subscription), var(--plan-subscription-dark));
    color: white;
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.duration-content {
    display: none;
}

.duration-content.active {
    display: block;
    animation: fadeInUp 0.5s ease;
}

/* Info Box */
.plan-info-box {
    max-width: 800px;
    margin: 0 auto;
    padding: 16px 24px;
    background: var(--plan-bg-light);
    border: 1px solid var(--plan-border);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    font-size: 14px;
    color: var(--plan-text);
}

.plan-info-box i {
    color: var(--plan-primary);
    font-size: 18px;
}

/* Benefits Grid */
.benefits-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    max-width: 900px;
    margin: 0 auto;
}

.benefit-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 20px;
    background: var(--plan-bg-light);
    border: 1px solid var(--plan-border);
    border-radius: 16px;
    transition: all 0.3s ease;
}

.benefit-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.benefit-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: var(--plan-primary);
}

.benefit-item span {
    font-size: 14px;
    font-weight: 600;
    color: var(--plan-text);
}

/* Reveal Animation */
.reveal {
    opacity: 0;
    transform: translateY(30px);
    animation: revealAnimation 0.8s ease forwards;
}

@keyframes revealAnimation {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .display-title-modern {
        font-size: 32px;
    }

    .selector-track {
        flex-direction: column;
        gap: 6px;
    }

    .selector-background {
        width: calc(100% - 12px);
        height: calc(50% - 9px);
    }

    .selector-background.subscription-active {
        transform: translateY(calc(100% + 6px));
    }

    .selector-background.onetime-active {
        transform: translateY(0);
    }

    .selector-option {
        padding: 14px 16px;
    }

    .plan-card-modern {
        min-width: 280px;
        max-width: 280px;
        padding: 28px 24px;
    }

    .plans-scroll-wrapper {
        padding: 20px 20px;
    }

    .scroll-nav {
        display: none;
    }

    .plans-scroll {
        scroll-snap-type: x proximity;
        gap: 16px;
        padding: 10px 10px 30px;
    }

    .benefits-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .price-amount {
        font-size: 40px;
    }
}

@media (max-width: 480px) {
    .plan-card-modern {
        min-width: 260px;
        max-width: 260px;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Plan Type Toggle
    const selectorOptions = document.querySelectorAll('.selector-option');
    const selectorBackground = document.getElementById('selectorBackground');
    const oneTimePlans = document.getElementById('oneTimePlans');
    const subscriptionPlans = document.getElementById('subscriptionPlans');

    // Initialize state on load
    function initializeSelector() {
        const activeOption = document.querySelector('.selector-option.active');
        if (activeOption) {
            const type = activeOption.dataset.type;
            if (type === 'oneTime') {
                selectorBackground.classList.add('onetime-active');
                selectorBackground.classList.remove('subscription-active');
            } else {
                selectorBackground.classList.add('subscription-active');
                selectorBackground.classList.remove('onetime-active');
            }
        }
    }
    
    // Call on load
    initializeSelector();

    selectorOptions.forEach(option => {
        option.addEventListener('click', () => {
            const type = option.dataset.type;
            
            // Update active states
            selectorOptions.forEach(opt => opt.classList.remove('active'));
            option.classList.add('active');
            
            // Update background
            if (type === 'subscription') {
                selectorBackground.classList.add('subscription-active');
                selectorBackground.classList.remove('onetime-active');
                oneTimePlans.classList.remove('active');
                subscriptionPlans.classList.add('active');
            } else {
                selectorBackground.classList.add('onetime-active');
                selectorBackground.classList.remove('subscription-active');
                oneTimePlans.classList.add('active');
                subscriptionPlans.classList.remove('active');
            }
        });
    });

    // Duration Toggle for Subscriptions
    const durationBtns = document.querySelectorAll('.duration-btn');
    const durationContents = document.querySelectorAll('.duration-content');

    durationBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const duration = btn.dataset.duration;
            
            // Update buttons
            durationBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Update content
            durationContents.forEach(content => {
                if (content.dataset.duration === duration) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });

            // Update scroll indicators
            setTimeout(() => {
                setupScrollIndicators(document.querySelector(`.duration-content[data-duration="${duration}"] .plans-scroll`));
            }, 100);
        });
    });

    // Horizontal Scroll Navigation
    function setupScrollNavigation() {
        const scrollWrappers = document.querySelectorAll('.plans-scroll-wrapper');
        
        scrollWrappers.forEach(wrapper => {
            const scrollContainer = wrapper.querySelector('.plans-scroll');
            const leftBtn = wrapper.querySelector('.scroll-nav-left');
            const rightBtn = wrapper.querySelector('.scroll-nav-right');
            
            if (!scrollContainer || !leftBtn || !rightBtn) return;

            const scrollAmount = 344; // card width + gap

            leftBtn.addEventListener('click', () => {
                scrollContainer.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            });

            rightBtn.addEventListener('click', () => {
                scrollContainer.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            });

            // Update button states
            function updateButtonStates() {
                const isAtStart = scrollContainer.scrollLeft <= 10;
                const isAtEnd = scrollContainer.scrollLeft >= scrollContainer.scrollWidth - scrollContainer.clientWidth - 10;
                
                leftBtn.disabled = isAtStart;
                rightBtn.disabled = isAtEnd;
            }

            scrollContainer.addEventListener('scroll', updateButtonStates);
            updateButtonStates();
        });
    }

    // Scroll Indicators
    function setupScrollIndicators(scrollContainer) {
        if (!scrollContainer) return;
        
        const wrapper = scrollContainer.closest('.plans-scroll-wrapper').parentElement;
        const indicatorsContainer = wrapper.querySelector('.scroll-indicators');
        
        if (!indicatorsContainer) return;

        const cards = scrollContainer.querySelectorAll('.plan-card-modern');
        indicatorsContainer.innerHTML = '';

        cards.forEach((card, index) => {
            const indicator = document.createElement('div');
            indicator.classList.add('scroll-indicator');
            if (index === 0) indicator.classList.add('active');
            
            indicator.addEventListener('click', () => {
                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            });
            
            indicatorsContainer.appendChild(indicator);
        });

        // Update active indicator on scroll
        scrollContainer.addEventListener('scroll', () => {
            const containerCenter = scrollContainer.scrollLeft + scrollContainer.clientWidth / 2;
            let activeIndex = 0;
            let minDistance = Infinity;

            cards.forEach((card, index) => {
                const cardCenter = card.offsetLeft + card.offsetWidth / 2;
                const distance = Math.abs(containerCenter - cardCenter);
                
                if (distance < minDistance) {
                    minDistance = distance;
                    activeIndex = index;
                }
            });

            const indicators = indicatorsContainer.querySelectorAll('.scroll-indicator');
            indicators.forEach((indicator, index) => {
                if (index === activeIndex) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });
        });
    }

    // Initialize
    setupScrollNavigation();
    setupScrollIndicators(document.querySelector('#oneTimePlans .plans-scroll'));
    
    // Setup indicators for first subscription duration
    const firstDuration = document.querySelector('.duration-content.active .plans-scroll');
    if (firstDuration) {
        setupScrollIndicators(firstDuration);
    }

    // Touch swipe enhancement
    const scrollContainers = document.querySelectorAll('.plans-scroll');
    scrollContainers.forEach(container => {
        let isDown = false;
        let startX;
        let scrollLeft;

        container.addEventListener('mousedown', (e) => {
            isDown = true;
            container.style.cursor = 'grabbing';
            startX = e.pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });

        container.addEventListener('mouseleave', () => {
            isDown = false;
            container.style.cursor = 'grab';
        });

        container.addEventListener('mouseup', () => {
            isDown = false;
            container.style.cursor = 'grab';
        });

        container.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 2;
            container.scrollLeft = scrollLeft - walk;
        });
    });
});
</script>
@endpush
@endsection
