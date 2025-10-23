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

        <!-- Pestañas: Pago Único vs Suscripción -->
        <ul class="nav nav-pills plan-tabs justify-content-center mb-4 reveal" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active plan-tab"
                        data-bs-toggle="pill"
                        data-bs-target="#oneTime"
                        type="button"
                        role="tab">
                    <i class="fa-solid fa-money-bill-1-wave me-2"></i>
                    Pago Único
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link plan-tab"
                        data-bs-toggle="pill"
                        data-bs-target="#subscription"
                        type="button"
                        role="tab">
                    <i class="fa-solid fa-calendar-days me-2"></i>
                    Suscripciones
                </button>
            </li>
        </ul>

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
                                <p class="plan-additional">
                                    <small>
                                        <i class="fa-solid fa-plus"></i>
                                        Mascota adicional: ₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}
                                    </small>
                                </p>
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
                                        <p class="plan-additional">
                                            <small>
                                                <i class="fa-solid fa-plus"></i>
                                                Mascota adicional: ₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}
                                            </small>
                                        </p>
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

/* Tabs principales */
.plan-tabs {
    gap: 12px;
}

.plan-tab {
    border-radius: 14px !important;
    padding: 12px 28px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid rgba(79, 137, 232, 0.1);
    background: white;
    color: var(--ink);
}

.plan-tab:hover {
    background: rgba(79, 137, 232, 0.05);
    border-color: rgba(79, 137, 232, 0.2);
}

.plan-tab.active {
    background: linear-gradient(135deg, var(--primary), var(--brand-900));
    border-color: transparent;
    color: white !important;
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
}
</style>
