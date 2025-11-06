@php
    use App\Models\Plan;
    $oneTimePlans = Plan::active()->oneTime()->orderBy('pets_included')->get();
    $subscriptionPlans = Plan::active()->subscription()->orderBy('duration_months')->orderBy('pets_included')->get()->groupBy('duration_months');
@endphp

<section class="py-5 plans-section" id="planes">
    <div class="container container-narrow">
        <!-- Header -->
        <div class="text-center mb-5 reveal">
            <div class="plan-badge">
                <span class="plan-badge-icon">💳</span>
                <span>PLANES</span>
            </div>
            <h2 class="section-title mb-3">
                Elige el <span class="plan-highlight">plan perfecto</span> para ti
            </h2>
            <p class="text-muted-2">Protege a tus mascotas con el plan que mejor se adapte a tus necesidades</p>
        </div>

        <!-- Toggle Moderno: Pago Único vs Suscripción -->
        <div class="plan-toggle-wrapper reveal">
            <div class="plan-toggle-container">
                <div class="plan-toggle-option" data-plan-type="oneTime">
                    <div class="plan-toggle-icon">
                        <i class="fa-solid fa-money-bill-1-wave"></i>
                    </div>
                    <div class="plan-toggle-text">
                        <h5>Pago Único</h5>
                        <p>Compra una vez, úsalo siempre</p>
                    </div>
                </div>
                <div class="plan-toggle-switch" id="planToggle">
                    <div class="plan-toggle-slider"></div>
                </div>
                <div class="plan-toggle-option" data-plan-type="subscription">
                    <div class="plan-toggle-icon">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                    <div class="plan-toggle-text">
                        <h5>Suscripción</h5>
                        <p>Renueva automáticamente</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido de las pestañas -->
        <div class="tab-content">

            <!-- PAGO ÚNICO -->
            <div class="tab-pane fade show active" id="oneTime" role="tabpanel">
                <div class="row g-4 justify-content-center mb-4">
                    @foreach($oneTimePlans as $plan)
                    <div class="col-md-4 reveal">
                        <div class="plan-card h-100" data-plan-type="onetime">
                            @if($plan->pets_included == 3)
                            <div class="plan-popular-badge">
                                <i class="fa-solid fa-star"></i> Popular
                            </div>
                            @endif

                            <div class="plan-header">
                                <div class="plan-icon">
                                    <i class="fa-solid fa-dog"></i>
                                </div>
                                <h4 class="plan-name">{{ $plan->pets_included }} {{ Str::plural('Mascota', $plan->pets_included) }}</h4>
                                <div class="plan-price">
                                    <span class="currency">₡</span>
                                    <span class="amount">{{ number_format($plan->price, 0, ',', '.') }}</span>
                                </div>
                                <p class="plan-period">Pago único</p>
                            </div>

                            <div class="plan-features">
                                <ul>
                                    <li><i class="fa-solid fa-check"></i> {{ $plan->pets_included }} {{ Str::plural('placa', $plan->pets_included) }} con QR</li>
                                    <li><i class="fa-solid fa-check"></i> Perfil digital completo</li>
                                    <li><i class="fa-solid fa-check"></i> Actualizaciones ilimitadas</li>
                                    <li><i class="fa-solid fa-check"></i> Sistema de recompensas</li>
                                    <li><i class="fa-solid fa-check"></i> Soporte por WhatsApp</li>
                                </ul>
                            </div>

                            <div class="plan-footer">
                                <a href="{{ route('checkout.show', $plan->id) }}" class="btn btn-plan">
                                    <i class="fa-solid fa-cart-shopping me-2"></i> Elegir plan
                                </a>
                                @if($plan->pets_included >= 3)
                                <p class="plan-additional">
                                    <small>
                                        <i class="fa-solid fa-plus"></i>
                                        Mascota adicional: ₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}
                                    </small>
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="plan-note reveal">
                    <i class="fa-solid fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Después de 3 mascotas, cada mascota adicional tiene un costo de ₡10,000
                </div>
            </div>

            <!-- SUSCRIPCIONES -->
            <div class="tab-pane fade" id="subscription" role="tabpanel">
                <!-- Tabs internos para duración -->
                <ul class="nav nav-pills duration-tabs justify-content-center mb-4">
                    @foreach($subscriptionPlans as $months => $plans)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }} duration-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#duration{{ $months }}"
                                type="button"
                                role="tab">
                            {{ $months }} {{ Str::plural('mes', $months) }}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach($subscriptionPlans as $months => $plans)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                         id="duration{{ $months }}"
                         role="tabpanel">

                        <div class="row g-4 justify-content-center mb-4">
                            @foreach($plans as $plan)
                            <div class="col-md-4 reveal">
                                <div class="plan-card plan-subscription h-100">
                                    @if($plan->pets_included == 3)
                                    <div class="plan-popular-badge">
                                        <i class="fa-solid fa-star"></i> Popular
                                    </div>
                                    @endif

                                    <div class="plan-header">
                                        <div class="plan-icon subscription">
                                            <i class="fa-solid fa-paw"></i>
                                        </div>
                                        <h4 class="plan-name">{{ $plan->pets_included }} {{ Str::plural('Mascota', $plan->pets_included) }}</h4>
                                        <div class="plan-price">
                                            <span class="currency">₡</span>
                                            <span class="amount">{{ number_format($plan->price, 0, ',', '.') }}</span>
                                        </div>
                                        <p class="plan-period">Cada {{ $months }} {{ Str::plural('mes', $months) }}</p>
                                    </div>

                                    <div class="plan-features">
                                        <ul>
                                            <li><i class="fa-solid fa-check"></i> {{ $plan->pets_included }} {{ Str::plural('placa', $plan->pets_included) }} con QR</li>
                                            <li><i class="fa-solid fa-check"></i> Perfil digital completo</li>
                                            <li><i class="fa-solid fa-check"></i> Actualizaciones ilimitadas</li>
                                            <li><i class="fa-solid fa-check"></i> Sistema de recompensas</li>
                                            <li><i class="fa-solid fa-check"></i> Soporte prioritario</li>
                                            <li><i class="fa-solid fa-check"></i> Renovación automática</li>
                                        </ul>
                                    </div>

                                    <div class="plan-footer">
                                        <a href="{{ route('checkout.show', $plan->id) }}" class="btn btn-plan">
                                            <i class="fa-solid fa-cart-shopping me-2"></i> Elegir plan
                                        </a>
                                        @if($plan->pets_included >= 3)
                                        <p class="plan-additional">
                                            <small>
                                                <i class="fa-solid fa-plus"></i>
                                                Mascota adicional: ₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}
                                            </small>
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="plan-note reveal">
                            <i class="fa-solid fa-info-circle me-2"></i>
                            <strong>Nota:</strong> Después de 3 mascotas, cada mascota adicional cuesta
                            ₡{{ number_format($plans->first()->additional_pet_price, 0, ',', '.') }}
                            por {{ $months }} {{ Str::plural('mes', $months) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Garantía/Beneficios -->
        <div class="row g-3 mt-4 reveal">
            <div class="col-md-4">
                <div class="plan-benefit">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Garantía 30 días</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="plan-benefit">
                    <i class="fa-solid fa-truck-fast"></i>
                    <span>Envío incluido</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="plan-benefit">
                    <i class="fa-solid fa-headset"></i>
                    <span>Soporte 24/7</span>
                </div>
            </div>
        </div>

    </div>
</section>

<style>
/* Sección de planes */
.plans-section {
    background: linear-gradient(135deg,
        rgba(79, 137, 232, 0.02) 0%,
        rgba(30, 124, 242, 0.05) 50%,
        rgba(14, 97, 198, 0.02) 100%);
}

/* Badge */
.plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, rgba(79, 137, 232, 0.1), rgba(30, 124, 242, 0.15));
    border: 1px solid rgba(79, 137, 232, 0.2);
    border-radius: 50px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 24px;
}

.plan-badge-icon {
    font-size: 18px;
}

.plan-highlight {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Toggle Moderno */
.plan-toggle-wrapper {
    display: flex;
    justify-content: center;
    margin-bottom: 48px;
    padding: 0 20px;
}

.plan-toggle-container {
    display: flex;
    align-items: center;
    gap: 32px;
    padding: 24px;
    background: white;
    border-radius: 24px;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
    border: 2px solid rgba(79, 137, 232, 0.15);
}

.plan-toggle-option {
    display: flex;
    align-items: center;
    gap: 16px;
    cursor: pointer;
    padding: 16px 20px;
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.plan-toggle-option:hover {
    background: rgba(79, 137, 232, 0.05);
    transform: translateY(-2px);
}

.plan-toggle-option.active {
    background: linear-gradient(135deg, rgba(79, 137, 232, 0.1), rgba(30, 124, 242, 0.15));
    box-shadow: 0 8px 24px rgba(79, 137, 232, 0.2);
}

.plan-toggle-option.active .plan-toggle-icon {
    background: linear-gradient(135deg, var(--primary), var(--brand-900));
    color: white;
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 8px 24px rgba(79, 137, 232, 0.4);
}

.plan-toggle-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: rgba(79, 137, 232, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--primary);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.plan-toggle-text {
    text-align: left;
}

.plan-toggle-text h5 {
    font-size: 18px;
    font-weight: 800;
    margin: 0 0 4px 0;
    color: var(--ink);
}

.plan-toggle-text p {
    font-size: 13px;
    color: var(--muted);
    margin: 0;
}

.plan-toggle-switch {
    position: relative;
    width: 80px;
    height: 40px;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.4s ease;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
}

.plan-toggle-switch.active {
    background: linear-gradient(135deg, #10b981, #059669);
}

.plan-toggle-slider {
    position: absolute;
    top: 4px;
    left: 4px;
    width: 32px;
    height: 32px;
    background: white;
    border-radius: 50%;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.plan-toggle-switch.active .plan-toggle-slider {
    transform: translateX(40px);
}

/* Animación de aparición */
@keyframes togglePulse {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(79, 137, 232, 0.4);
    }
    50% {
        box-shadow: 0 0 0 15px rgba(79, 137, 232, 0);
    }
}

.plan-toggle-container {
    animation: togglePulse 2s ease-in-out infinite;
}

/* Duration tabs (subtabs para suscripciones) */
.duration-tabs {
    gap: 8px;
}

.duration-tab {
    border-radius: 10px !important;
    padding: 8px 20px;
    font-size: 14px;
    font-weight: 600;
    background: rgba(79, 137, 232, 0.08);
    border: 1px solid rgba(79, 137, 232, 0.1);
    color: var(--primary);
}

.duration-tab.active {
    background: var(--primary);
    color: white !important;
}

/* Plan cards */
.plan-card {
    background: white;
    border-radius: 20px;
    padding: 32px 24px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    border: 2px solid rgba(79, 137, 232, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.plan-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s ease;
}

.plan-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    border-color: rgba(79, 137, 232, 0.3);
}

.plan-card:hover::before {
    transform: scaleX(1);
}

.plan-subscription {
    border-color: rgba(16, 185, 129, 0.2);
}

.plan-subscription:hover {
    border-color: rgba(16, 185, 129, 0.4);
}

.plan-subscription::before {
    background: linear-gradient(90deg, #10b981, #059669);
}

/* Popular badge */
.plan-popular-badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: linear-gradient(135deg, #ff7e30, #ff6b1a);
    color: white;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(255, 126, 48, 0.3);
}

/* Plan header */
.plan-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    background: linear-gradient(135deg, var(--primary), var(--brand-900));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    margin: 0 auto 20px;
    box-shadow: 0 10px 25px rgba(79, 137, 232, 0.3);
}

.plan-icon.subscription {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
}

.plan-name {
    font-size: 22px;
    font-weight: 700;
    color: var(--ink);
    margin-bottom: 16px;
}

.plan-price {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 4px;
    margin-bottom: 8px;
}

.plan-price .currency {
    font-size: 20px;
    font-weight: 600;
    color: var(--muted);
}

.plan-price .amount {
    font-size: 42px;
    font-weight: 800;
    color: var(--ink);
    line-height: 1;
}

.plan-period {
    color: var(--muted);
    font-size: 14px;
    margin-bottom: 24px;
}

/* Features */
.plan-features {
    margin: 24px 0;
    padding: 24px 0;
    border-top: 1px solid rgba(79, 137, 232, 0.1);
    border-bottom: 1px solid rgba(79, 137, 232, 0.1);
}

.plan-features ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.plan-features li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    color: #374151;
    font-size: 15px;
}

.plan-features i {
    color: #10b981;
    font-size: 14px;
    width: 16px;
}

/* Footer */
.plan-footer {
    margin-top: auto;
}

.btn-plan {
    width: 100%;
    padding: 14px 24px;
    font-weight: 700;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--brand-900));
    border: none;
    color: white;
    transition: all 0.3s ease;
    box-shadow: 0 8px 20px rgba(79, 137, 232, 0.3);
}

.btn-plan:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(79, 137, 232, 0.4);
    color: white;
}

.plan-additional {
    text-align: center;
    margin-top: 12px;
    margin-bottom: 0;
    color: var(--muted);
}

/* Notes */
.plan-note {
    background: rgba(79, 137, 232, 0.08);
    border: 1px solid rgba(79, 137, 232, 0.2);
    border-radius: 12px;
    padding: 16px 20px;
    color: var(--primary);
    font-size: 14px;
    text-align: center;
}

/* Benefits */
.plan-benefit {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    transition: all 0.3s ease;
}

.plan-benefit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.plan-benefit i {
    color: var(--primary);
    font-size: 20px;
}

.plan-benefit span {
    font-weight: 600;
    color: var(--ink);
}

/* Responsive */
@media (max-width: 768px) {
    .plan-card {
        padding: 24px 20px;
    }

    .plan-price .amount {
        font-size: 36px;
    }

    .plan-tabs {
        flex-direction: column;
    }

    .plan-tab {
        width: 100%;
    }

    /* MODERN Mobile Toggle Design */
    .plan-toggle-wrapper {
        padding: 0 16px;
    }

    .plan-toggle-container {
        flex-direction: column;
        gap: 16px;
        padding: 20px 16px;
        border-radius: 20px;
        max-width: 400px;
        margin: 0 auto;
    }

    .plan-toggle-option {
        width: 100%;
        padding: 12px 16px;
        border-radius: 14px;
        background: rgba(79, 137, 232, 0.03);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .plan-toggle-option.active {
        background: linear-gradient(135deg, rgba(79, 137, 232, 0.12), rgba(30, 124, 242, 0.18));
        border-color: rgba(79, 137, 232, 0.3);
        box-shadow: 0 4px 16px rgba(79, 137, 232, 0.15);
    }

    .plan-toggle-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
        border-radius: 12px;
    }

    .plan-toggle-text h5 {
        font-size: 16px;
    }

    .plan-toggle-text p {
        font-size: 12px;
    }

    /* Hide switch on mobile for cleaner look */
    .plan-toggle-switch {
        order: -1;
        width: 100%;
        height: 50px;
        border-radius: 16px;
        background: white;
        border: 2px solid rgba(79, 137, 232, 0.1);
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        gap: 6px;
    }

    .plan-toggle-switch::before {
        content: 'Pago Único';
        position: absolute;
        left: 12px;
        font-size: 13px;
        font-weight: 700;
        color: white;
        transition: all 0.3s ease;
        z-index: 1;
    }

    .plan-toggle-switch::after {
        content: 'Suscripción';
        position: absolute;
        right: 12px;
        font-size: 13px;
        font-weight: 700;
        color: var(--muted);
        transition: all 0.3s ease;
        z-index: 1;
    }

    .plan-toggle-switch.active::before {
        color: var(--muted);
    }

    .plan-toggle-switch.active::after {
        color: white;
    }

    .plan-toggle-slider {
        position: absolute;
        width: calc(50% - 6px);
        height: calc(100% - 12px);
        background: linear-gradient(135deg, var(--primary), var(--brand-900));
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        left: 6px;
        top: 6px;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .plan-toggle-switch.active .plan-toggle-slider {
        transform: translateX(calc(100% + 6px));
        background: linear-gradient(135deg, #10b981, #059669);
    }

    /* MODERN Horizontal Scroll for Plans */
    .tab-pane .row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        gap: 16px;
        padding: 20px 16px;
        margin: 0 -16px;
    }

    .tab-pane .row > [class*="col-"] {
        flex: 0 0 85%;
        max-width: 85%;
        scroll-snap-align: center;
    }

    /* Hide scrollbar but keep functionality */
    .tab-pane .row::-webkit-scrollbar {
        display: none;
    }

    .tab-pane .row {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Scroll indicator dots */
    .tab-pane {
        position: relative;
    }

    /* Add visual hint for horizontal scroll */
    .tab-pane .row::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 40px;
        background: linear-gradient(to left, rgba(248, 250, 252, 0.95), transparent);
        pointer-events: none;
        z-index: 1;
    }

    .plan-card:hover {
        transform: translateY(-4px);
    }

    /* Duration tabs for subscriptions - also horizontal scroll */
    .duration-tabs {
        overflow-x: auto;
        overflow-y: hidden;
        flex-wrap: nowrap;
        padding: 8px 16px;
        margin: 0 -16px;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .duration-tabs::-webkit-scrollbar {
        display: none;
    }

    .duration-tab {
        flex-shrink: 0;
        white-space: nowrap;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('planToggle');
    const options = document.querySelectorAll('.plan-toggle-option');
    const oneTimeTab = document.getElementById('oneTime');
    const subscriptionTab = document.getElementById('subscription');

    let currentType = 'oneTime'; // Estado inicial

    // Marcar la opción inicial como activa
    document.querySelector('[data-plan-type="oneTime"]').classList.add('active');

    // Función para cambiar el tipo de plan
    function switchPlanType(type) {
        currentType = type;

        // Actualizar clases de las opciones
        options.forEach(option => {
            if (option.dataset.planType === type) {
                option.classList.add('active');
            } else {
                option.classList.remove('active');
            }
        });

        // Actualizar toggle switch
        if (type === 'subscription') {
            toggle.classList.add('active');
        } else {
            toggle.classList.remove('active');
        }

        // Cambiar tabs
        if (type === 'oneTime') {
            oneTimeTab.classList.add('show', 'active');
            subscriptionTab.classList.remove('show', 'active');
        } else {
            oneTimeTab.classList.remove('show', 'active');
            subscriptionTab.classList.add('show', 'active');
        }
    }

    // Event listeners para las opciones
    options.forEach(option => {
        option.addEventListener('click', () => {
            const type = option.dataset.planType;
            switchPlanType(type);
        });
    });

    // Event listener para el switch
    toggle.addEventListener('click', () => {
        const newType = currentType === 'oneTime' ? 'subscription' : 'oneTime';
        switchPlanType(newType);
    });

    /* ========= ENHANCED SWIPE SUPPORT FOR HORIZONTAL SCROLL ========= */
    // Add swipe support for plan cards on mobile
    const planRows = document.querySelectorAll('.tab-pane .row');

    planRows.forEach(row => {
        let startX = 0;
        let scrollLeft = 0;
        let isDown = false;
        let hasMoved = false;

        row.addEventListener('mousedown', (e) => {
            isDown = true;
            hasMoved = false;
            row.style.cursor = 'grabbing';
            startX = e.pageX - row.offsetLeft;
            scrollLeft = row.scrollLeft;
        });

        row.addEventListener('mouseleave', () => {
            isDown = false;
            row.style.cursor = 'grab';
        });

        row.addEventListener('mouseup', () => {
            isDown = false;
            row.style.cursor = 'grab';
        });

        row.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            hasMoved = true;
            const x = e.pageX - row.offsetLeft;
            const walk = (x - startX) * 2;
            row.scrollLeft = scrollLeft - walk;
        });

        // Touch events for mobile
        let touchStartX = 0;
        let touchScrollLeft = 0;

        row.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].pageX - row.offsetLeft;
            touchScrollLeft = row.scrollLeft;
        }, { passive: true });

        row.addEventListener('touchmove', (e) => {
            const x = e.touches[0].pageX - row.offsetLeft;
            const walk = (x - touchStartX) * 2;
            row.scrollLeft = touchScrollLeft - walk;
        }, { passive: true });

        // Add snap to center on scroll end
        let scrollTimeout;
        row.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                const cards = row.querySelectorAll('[class*="col-"]');
                const rowWidth = row.offsetWidth;
                const scrollPosition = row.scrollLeft + rowWidth / 2;

                let closestCard = null;
                let closestDistance = Infinity;

                cards.forEach(card => {
                    const cardCenter = card.offsetLeft + card.offsetWidth / 2;
                    const distance = Math.abs(scrollPosition - cardCenter);

                    if (distance < closestDistance) {
                        closestDistance = distance;
                        closestCard = card;
                    }
                });

                if (closestCard) {
                    const targetScroll = closestCard.offsetLeft - (rowWidth - closestCard.offsetWidth) / 2;
                    row.scrollTo({
                        left: targetScroll,
                        behavior: 'smooth'
                    });
                }
            }, 100);
        });
    });
});
</script>
