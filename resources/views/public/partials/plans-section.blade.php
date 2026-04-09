@php
    use App\Models\Plan;
    // Precargamos los planes en arrays
    $oneTimePlans = Plan::active()->oneTime()->orderBy('pets_included')->get();
    
    // Simplificaremos la lógica tomando el primer grupo de suscripción para la UI limpia
    // (generalmente es suscripción anual o mensual, tomamos la primera disponible)
    $subGroups = Plan::active()->subscription()->orderBy('duration_months')->orderBy('pets_included')->get()->groupBy('duration_months');
    $subscriptionPlans = $subGroups->first() ?? collect(); 
    $subDuration = $subGroups->keys()->first() ?? 1;
@endphp

<section class="guided-pricing-section" id="planes">
    <div class="guided-container">
        
        <!-- Encabezado Fuerte -->
        <div class="guided-header">
            <span class="guided-eyebrow">PROTECCIÓN PETSCAN</span>
            <h2 class="guided-title">Construye la armadura perfecta para tu familia.</h2>
            <p class="guided-subtitle">Selecciona cuántos miembros de tu manada deseas proteger. Cobertura inmediata, tecnología permanente.</p>
        </div>

        <div class="guided-layout">
            <!-- Columna Izquierda: Configurador Guiado -->
            <div class="guided-configurator">
                
                <div class="config-step">
                    <h3 class="step-title">1. ¿Qué tipo de cobertura prefieres?</h3>
                    <div class="premium-segmented-control" id="billingSelector">
                        <div class="segmented-indicator"></div>
                        <button class="segment-btn active" data-type="onetime">
                            Pago Único
                        </button>
                        <button class="segment-btn" data-type="subscription">
                            Renovación Continua
                        </button>
                    </div>
                </div>

                <div class="config-step">
                    <h3 class="step-title">2. ¿Cuántas mascotas tienes?</h3>
                    <div class="pet-selector-grid" id="petSelector">
                        <!-- Generado por JS o con los botones base acá -->
                    </div>
                    <div class="pet-limit-note">
                        <i class="fa-solid fa-circle-info"></i> Puedes agregar más mascotas en el siguiente paso si tienes una familia numerosa.
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Tarjeta de Resumen Dinámica -->
            <div class="guided-summary">
                <div class="summary-card" id="summaryCard">
                    <!-- Badge Dinámico -->
                    <div class="summary-badge-wrapper" id="summaryBadgeWrapper">
                        <span class="summary-badge" id="summaryBadge">MEJOR OPCIÓN</span>
                    </div>

                    <!-- Cabecera -->
                    <div class="summary-header">
                        <div class="summary-icon">
                            <i class="fa-solid fa-shield-dog"></i>
                        </div>
                        <div class="summary-title-group">
                            <h3 id="summaryTitle">Cargando...</h3>
                            <p id="summaryDesc">Preparando tu protección</p>
                        </div>
                    </div>

                    <!-- Precio Animado -->
                    <div class="summary-price-wrapper">
                        <span class="summary-currency">₡</span>
                        <div class="summary-price" id="summaryPrice">0</div>
                    </div>
                    <p class="summary-billing" id="summaryBillingMode">Pago único</p>

                    <!-- Beneficios -->
                    <div class="summary-features-container">
                        <p class="features-title">Tu cobertura incluye:</p>
                        <ul class="summary-features" id="summaryFeatures">
                            <!-- Inyectado por JS -->
                        </ul>
                    </div>

                    <!-- Botón CTA de alto impacto -->
                    <a href="javascript:void(0);" class="summary-cta" id="summaryCtaBtn">
                        <span id="ctaText">Proteger a mi mascota</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    
                    <p class="summary-guarantee">
                        <i class="fa-solid fa-lock"></i> Compra segura y encriptada
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pasando la data de PHP a JS para hacer la UI inmediata sin recargas -->
<script>
    window.PetScanPlansData = {
        onetime: @json($oneTimePlans),
        subscription: @json($subscriptionPlans),
        subDuration: {{ $subDuration }},
        routes: {
            checkout: "{{ route('checkout.show', ':id') }}"
        }
    };
</script>

<style>
/* ==========================================================================
   ENFOQUE GUIADO - ESTILO APPLE/AIRBNB (SaaS Storytelling)
   ========================================================================== */

:root {
    --vp-bg: #FAFAFA;
    --vp-surface: #FFFFFF;
    --vp-text-primary: #1D1D1F;
    --vp-text-secondary: #86868B;
    --vp-accent: #0066CC;
    --vp-accent-hover: #0055AA;
    --vp-border: #D2D2D7;
    --vp-border-light: #E5E5EA;
    --vp-success: #34C759;
    --vp-popular: #FF9500;
}

.guided-pricing-section {
    background-color: var(--vp-bg);
    padding: 100px 0 120px;
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, Helvetica, sans-serif;
    color: var(--vp-text-primary);
}

.guided-container {
    max-width: 1080px;
    margin: 0 auto;
    padding: 0 24px;
}

/* --- Header Fuerte --- */
.guided-header {
    text-align: center;
    max-width: 680px;
    margin: 0 auto 60px;
}

.guided-eyebrow {
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    color: var(--vp-accent);
    margin-bottom: 20px;
    display: inline-block;
}

.guided-title {
    font-size: 48px;
    font-weight: 700;
    line-height: 1.05;
    letter-spacing: -1.5px;
    margin-bottom: 20px;
}

.guided-subtitle {
    font-size: 20px;
    line-height: 1.5;
    color: var(--vp-text-secondary);
    font-weight: 400;
}

/* --- Layout Principal --- */
.guided-layout {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    gap: 60px;
    align-items: start;
}

/* --- Opciones Izquierda (Configurador) --- */
.config-step {
    margin-bottom: 48px;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeUp 0.6s ease forwards;
}

.config-step:nth-child(2) {
    animation-delay: 0.15s;
}

.step-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 24px;
    color: var(--vp-text-primary);
}

/* Selector Segmentado (Billing) */
.premium-segmented-control {
    display: flex;
    position: relative;
    background-color: #E3E3E8;
    padding: 4px;
    border-radius: 14px;
}

.segmented-indicator {
    position: absolute;
    top: 4px;
    bottom: 4px;
    left: 4px;
    width: calc(50% - 4px);
    background-color: var(--vp-surface);
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.04);
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    z-index: 1;
}

.segment-btn {
    flex: 1;
    position: relative;
    z-index: 2;
    background: transparent;
    border: none;
    padding: 14px 20px;
    font-size: 15px;
    font-weight: 600;
    color: var(--vp-text-secondary);
    cursor: pointer;
    border-radius: 10px;
    transition: color 0.3s;
}

.segment-btn.active {
    color: var(--vp-text-primary);
}

/* Selector Cajas (Pets) */
.pet-selector-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 16px;
}

.pet-btn {
    background: var(--vp-surface);
    border: 2px solid var(--vp-border-light);
    border-radius: 16px;
    padding: 24px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    outline: none;
}

.pet-btn:hover {
    border-color: var(--vp-border);
    transform: translateY(-2px);
}

.pet-btn.active {
    border-color: var(--vp-accent);
    background-color: #F5FAFF;
    box-shadow: 0 4px 12px rgba(0, 102, 204, 0.15);
}

.pet-btn i {
    font-size: 28px;
    color: var(--vp-text-secondary);
    transition: color 0.3s;
}

.pet-btn.active i {
    color: var(--vp-accent);
}

.pet-btn span {
    font-size: 16px;
    font-weight: 600;
    color: var(--vp-text-primary);
}

.pet-limit-note {
    margin-top: 16px;
    font-size: 13px;
    color: var(--vp-text-secondary);
    display: flex;
    align-items: flex-start;
    gap: 6px;
}

.pet-limit-note i {
    margin-top: 3px;
}

/* --- Resumen Derecha (Tarjeta Final) --- */
.guided-summary {
    position: sticky;
    top: 40px;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeUp 0.6s ease forwards;
    animation-delay: 0.3s;
}

.summary-card {
    background-color: var(--vp-surface);
    border-radius: 28px;
    padding: 48px;
    box-shadow: 0 30px 60px rgba(0,0,0,0.06), 0 10px 20px rgba(0,0,0,0.03);
    border: 1px solid rgba(0,0,0,0.04);
    position: relative;
    overflow: hidden;
    transition: all 0.4s ease;
}

.summary-badge-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(90deg, #FF9500, #FF5E3A);
    color: #FFF;
    text-align: center;
    padding: 8px 0;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1px;
    opacity: 0;
    transform: translateY(-100%);
    transition: all 0.3s ease;
}

.summary-badge-wrapper.visible {
    opacity: 1;
    transform: translateY(0);
}

.summary-card.has-badge {
    padding-top: 56px; /* Compensar la aparición de la bandera */
}

/* Header Resumen */
.summary-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 32px;
}

.summary-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: #F2F8FF;
    color: var(--vp-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
}

.summary-title-group h3 {
    font-size: 24px;
    font-weight: 700;
    margin: 0 0 4px;
}

.summary-title-group p {
    font-size: 15px;
    color: var(--vp-text-secondary);
    margin: 0;
}

/* Precio Animable */
.summary-price-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 4px;
}

.summary-currency {
    font-size: 24px;
    font-weight: 600;
    color: var(--vp-text-secondary);
    margin-top: 8px;
}

.summary-price {
    font-size: 64px;
    font-weight: 700;
    letter-spacing: -2px;
    line-height: 1;
    color: var(--vp-text-primary);
    transition: opacity 0.2s;
}

.summary-price.updating {
    opacity: 0;
    transform: translateY(-10px);
}

.summary-billing {
    font-size: 15px;
    color: var(--vp-text-secondary);
    margin: 12px 0 40px;
    font-weight: 500;
}

/* Lista de Beneficios */
.features-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--vp-text-secondary);
    margin-bottom: 20px;
}

.summary-features {
    list-style: none;
    padding: 0;
    margin: 0 0 40px;
}

.summary-features li {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 16px;
    font-size: 16px;
    line-height: 1.5;
    color: var(--vp-text-primary);
    animation: slideInRight 0.3s ease forwards;
    opacity: 0;
}

/* Escalado para retraso en listas animadas */
.summary-features li:nth-child(1) { animation-delay: 0.1s; }
.summary-features li:nth-child(2) { animation-delay: 0.15s; }
.summary-features li:nth-child(3) { animation-delay: 0.2s; }
.summary-features li:nth-child(4) { animation-delay: 0.25s; }
.summary-features li:nth-child(5) { animation-delay: 0.3s; }

.summary-features li i {
    color: var(--vp-accent);
    font-size: 18px;
    margin-top: 3px;
}

/* CTA */
.summary-cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    background-color: var(--vp-accent);
    color: #FFFFFF;
    text-decoration: none;
    padding: 20px;
    border-radius: 16px;
    font-size: 18px;
    font-weight: 600;
    transition: all 0.3s;
    box-shadow: 0 8px 16px rgba(0, 102, 204, 0.25);
}

.summary-cta:hover {
    background-color: var(--vp-accent-hover);
    transform: translateY(-2px);
    box-shadow: 0 12px 24px rgba(0, 102, 204, 0.35);
    color: #FFFFFF;
}

.summary-cta i {
    transition: transform 0.3s;
}

.summary-cta:hover i {
    transform: translateX(4px);
}

.summary-guarantee {
    text-align: center;
    font-size: 13px;
    color: var(--vp-text-secondary);
    margin: 20px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(10px); }
    to { opacity: 1; transform: translateX(0); }
}

/* Responsividad */
@media (max-width: 1024px) {
    .guided-layout {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .guided-summary {
        position: relative;
        top: 0;
    }
    .summary-card {
        padding: 32px;
    }
}

@media (max-width: 768px) {
    .guided-pricing-section {
        padding: 60px 0 80px;
    }
    .guided-title {
        font-size: 38px;
        letter-spacing: -1px;
    }
    .premium-segmented-control {
        flex-direction: column;
    }
    .segmented-indicator {
        width: calc(100% - 8px);
        height: calc(50% - 4px);
    }
    .pet-selector-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .summary-price {
        font-size: 56px;
    }
    .summary-card {
        padding: 24px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // Configuración base de la lógica dinámica
    const data = window.PetScanPlansData;
    let currentType = 'onetime'; // 'onetime' o 'subscription'
    let currentPets = 1;         // default initial
    let activePlan = null;
    
    // Referencias a los elementos del DOM
    const billingBtns = document.querySelectorAll('.segment-btn');
    const segIndicator = document.querySelector('.segmented-indicator');
    const petSelectorContainer = document.getElementById('petSelector');
    
    // Panel Resumen DOM
    const sumCard = document.getElementById('summaryCard');
    const sumBadgeWrap = document.getElementById('summaryBadgeWrapper');
    const sumTitle = document.getElementById('summaryTitle');
    const sumDesc = document.getElementById('summaryDesc');
    const sumPrice = document.getElementById('summaryPrice');
    const sumBilling = document.getElementById('summaryBillingMode');
    const sumFeatures = document.getElementById('summaryFeatures');
    const ctaBtn = document.getElementById('summaryCtaBtn');
    const ctaText = document.getElementById('ctaText');

    // 1. Inicializar Interface
    function init() {
        // Encontrar la cantidad de mascotas base (normalmente 1)
        if (data.onetime && data.onetime.length > 0) {
            currentPets = data.onetime[0].pets_included;
        }
        buildPetButtons();
        updateUI();
    }

    // 2. Control de Selector Segmentado (Pago único / Suscripción)
    billingBtns.forEach((btn, index) => {
        btn.addEventListener('click', () => {
            if(btn.classList.contains('active')) return;
            
            // Animación Segment Indicator
            if (index === 0) {
                // Desktop: horizontal, Mobile: vertical
                if(window.innerWidth > 768) {
                    segIndicator.style.transform = 'translateX(0)';
                } else {
                    segIndicator.style.transform = 'translateY(0)';
                }
            } else {
                if(window.innerWidth > 768) {
                    segIndicator.style.transform = 'translateX(100%)';
                } else {
                    segIndicator.style.transform = 'translateY(100%)';
                }
            }

            billingBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            currentType = btn.getAttribute('data-type');
            
            // Reconstruir botones de mascota por si difieren y actualizar UI central
            buildPetButtons();
            updateUI();
        });
    });

    // Handle resize para el indicator position
    window.addEventListener('resize', () => {
        const activeIdx = Array.from(billingBtns).findIndex(b => b.classList.contains('active'));
        if(window.innerWidth > 768) {
            segIndicator.style.transform = activeIdx === 1 ? 'translateX(100%)' : 'translateX(0)';
        } else {
            segIndicator.style.transform = activeIdx === 1 ? 'translateY(100%)' : 'translateY(0)';
        }
    });

    // 3. Reconstruir botones de selección de mascotas
    function buildPetButtons() {
        const plansList = currentType === 'onetime' ? data.onetime : data.subscription;
        petSelectorContainer.innerHTML = '';
        
        let foundCurrentPets = false;

        plansList.forEach(plan => {
            if(plan.pets_included == currentPets) foundCurrentPets = true;

            const btn = document.createElement('button');
            btn.className = `pet-btn ${plan.pets_included == currentPets ? 'active' : ''}`;
            btn.innerHTML = `
                <i class="fa-solid fa-dog"></i>
                <span>${plan.pets_included} ${plan.pets_included == 1 ? 'Mascota' : 'Mascotas'}</span>
            `;
            
            btn.addEventListener('click', () => {
                document.querySelectorAll('.pet-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentPets = plan.pets_included;
                updateUI();
            });

            petSelectorContainer.appendChild(btn);
        });

        // Fallback if current pets choice isn't in new array
        if(!foundCurrentPets && plansList.length > 0) {
            currentPets = plansList[0].pets_included;
            document.querySelector('.pet-btn').classList.add('active'); // select first
        }
    }

    // 4. Lógica de UI - Animar y mostrar los datos del plan actual
    function updateUI() {
        const plansList = currentType === 'onetime' ? data.onetime : data.subscription;
        activePlan = plansList.find(p => p.pets_included == currentPets);
        
        if(!activePlan) return;

        // Animar salida del precio
        sumPrice.classList.add('updating');

        setTimeout(() => {
            // Asignar nuevas variables
            const isPopular = activePlan.pets_included == 3; // Lógica del negocio
            
            // Badge popular
            if(isPopular) {
                sumBadgeWrap.classList.add('visible');
                sumCard.classList.add('has-badge');
            } else {
                sumBadgeWrap.classList.remove('visible');
                sumCard.classList.remove('has-badge');
            }

            // Textos Principales
            sumTitle.textContent = `Cobertura para ${activePlan.pets_included} ${activePlan.pets_included == 1 ? 'Mascota' : 'Mascotas'}`;
            
            // Precio
            const formattedPrice = Number(activePlan.price).toLocaleString('es-CR');
            sumPrice.textContent = formattedPrice;

            // Billing Period
            if(currentType === 'onetime') {
                sumDesc.textContent = "La armadura digital completa para siempre.";
                sumBilling.textContent = "Pago único. Sin cargos ocultos.";
                ctaText.textContent = "Elegir Plan";
            } else {
                sumDesc.textContent = "Protección con esteroides y soporte premium.";
                sumBilling.textContent = `Renovación cada ${data.subDuration} meses. Cancela cuando quieras.`;
                ctaText.textContent = "Iniciar suscripción";
            }

            // Recrear Lista de Beneficios (Para disparar la animación de cascada)
            sumFeatures.innerHTML = '';
            
            let features = [];
            if(currentType === 'onetime') {
                features = [
                    `<strong>${activePlan.pets_included} ${activePlan.pets_included == 1 ? 'Placa inteligente' : 'Placas inteligentes'} QR</strong>`,
                    "Perfil digital siempre disponible",
                    "Alertas de escaneo al WhatsApp",
                    "Acceso ilimitado a actualizaciones médicas",
                    "Sistema de reporte automático en portal"
                ];
            } else {
                features = [
                    `<strong>${activePlan.pets_included} ${activePlan.pets_included == 1 ? 'Placa Premium' : 'Placas Premium'} QR</strong>`,
                    "Reemplazo físico de placa gratis (1 anual)",
                    "Mantenimiento VIP de perfil",
                    "Notificaciones SMS de escaneo",
                    "Soporte 24/7 sin filas de espera"
                ];
            }

            // Extras
            if(activePlan.pets_included >= 3 && activePlan.additional_pet_price > 0) {
                const addPrice = Number(activePlan.additional_pet_price).toLocaleString('es-CR');
                features.push(`Mascota extra por solo ₡${addPrice}`);
            }

            features.forEach(feat => {
                const li = document.createElement('li');
                li.innerHTML = `<i class="fa-solid fa-check-circle"></i> <span>${feat}</span>`;
                sumFeatures.appendChild(li);
            });

            // Actualizar Enlace CTA
            const checkoutUrl = data.routes.checkout.replace(':id', activePlan.id);
            ctaBtn.href = checkoutUrl;

            // Retornar animación precio
            sumPrice.classList.remove('updating');
            
        }, 200); // 200ms duration for fade down
    }

    // Levantar todo
    init();
});
</script>
