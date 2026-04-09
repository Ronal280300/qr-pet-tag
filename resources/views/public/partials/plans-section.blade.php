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
            <h2 class="guided-title">Construye lo mejor para tu compañero</h2>
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

                    <hr class="summary-divider">

                    <!-- Beneficios -->
                    <div class="summary-features-container">
                        <p class="features-title">TU COBERTURA INCLUYE:</p>
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
   ENFOQUE GUIADO - REDISEÑO MODERNO Y ELEGANTE
   ========================================================================== */

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

:root {
    --vp-font: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, sans-serif;
    --vp-bg: #F8FAFC; 
    --vp-surface: #FFFFFF;
    --vp-text-primary: #0F172A;
    --vp-text-secondary: #64748B;
    --vp-accent: #2563EB;
    --vp-accent-hover: #1D4ED8;
    --vp-border: #E2E8F0;
    --vp-border-light: #F1F5F9;
    --vp-popular: #3B82F6; 
}

.guided-pricing-section {
    background-color: var(--vp-bg);
    padding: 120px 0;
    font-family: var(--vp-font);
    color: var(--vp-text-primary);
}

.guided-container {
    max-width: 1140px;
    margin: 0 auto;
    padding: 0 24px;
}

/* --- Header Fuerte --- */
.guided-header {
    text-align: center;
    max-width: 720px;
    margin: 0 auto 60px;
}

.guided-eyebrow {
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 1.5px;
    color: var(--vp-accent);
    margin-bottom: 16px;
    display: inline-block;
    text-transform: uppercase;
}

.guided-title {
    font-size: 44px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -1.5px;
    margin-bottom: 20px;
    color: var(--vp-text-primary);
}

.guided-subtitle {
    font-size: 18px;
    line-height: 1.6;
    color: var(--vp-text-secondary);
    font-weight: 400;
}

/* --- Layout Principal --- */
.guided-layout {
    display: grid;
    grid-template-columns: 1fr 480px; /* Columna derecha más ancha para una tarjeta premium */
    gap: 80px;
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
    animation-delay: 0.1s;
}

.step-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--vp-text-primary);
}

/* Selector Segmentado (Billing) */
.premium-segmented-control {
    display: flex;
    position: relative;
    background-color: var(--vp-border-light);
    padding: 6px;
    border-radius: 16px;
}

.segmented-indicator {
    position: absolute;
    top: 6px;
    bottom: 6px;
    left: 6px;
    width: calc(50% - 6px);
    background-color: var(--vp-surface);
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(15,23,42,0.08), 0 1px 3px rgba(15,23,42,0.04);
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    z-index: 1;
}

.segment-btn {
    flex: 1;
    position: relative;
    z-index: 2;
    background: transparent;
    border: none;
    padding: 16px 20px;
    font-size: 15px;
    font-weight: 600;
    color: var(--vp-text-secondary);
    cursor: pointer;
    border-radius: 12px;
    transition: color 0.3s;
    font-family: inherit;
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
    border: 2px solid var(--vp-border);
    border-radius: 20px;
    padding: 24px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    outline: none;
    font-family: inherit;
}

.pet-btn:hover {
    border-color: #CBD5E1;
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(15,23,42,0.05);
}

.pet-btn.active {
    border-color: var(--vp-accent);
    background-color: #EFF6FF;
    box-shadow: 0 4px 14px rgba(37,99,235,0.15);
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
    font-weight: 700;
    color: var(--vp-text-primary);
}

.pet-limit-note {
    margin-top: 16px;
    font-size: 13px;
    color: var(--vp-text-secondary);
    display: flex;
    align-items: flex-start;
    gap: 6px;
    line-height: 1.5;
}

.pet-limit-note i {
    margin-top: 2px;
}

/* --- MÁS ELEGANTE: Tarjeta de Resumen (Derecha) --- */
.guided-summary {
    position: sticky;
    top: 40px;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeUp 0.6s ease forwards;
    animation-delay: 0.2s;
}

.summary-card {
    background-color: var(--vp-surface);
    border-radius: 24px;
    padding: 48px;
    box-shadow: 0 20px 40px -10px rgba(15,23,42,0.08);
    border: 1px solid rgba(15,23,42,0.05);
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.summary-card:hover {
    box-shadow: 0 25px 50px -12px rgba(15,23,42,0.12);
}

/* Badge Popular Mejorado */
.summary-badge-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, var(--vp-accent), #60A5FA);
    color: #FFF;
    text-align: center;
    padding: 8px 0;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    opacity: 0;
    transform: translateY(-100%);
    transition: all 0.3s ease;
}

.summary-badge-wrapper.visible {
    opacity: 1;
    transform: translateY(0);
}

.summary-card.has-badge {
    padding-top: 56px; 
}

/* Header Resumen Elegante */
.summary-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 32px;
}

.summary-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: var(--vp-accent);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 10px 15px -3px rgba(37,99,235,0.3);
}

.summary-title-group h3 {
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 4px;
    color: var(--vp-text-primary);
    letter-spacing: -0.5px;
}

.summary-title-group p {
    font-size: 14px;
    color: var(--vp-text-secondary);
    margin: 0;
}

/* Precio Estilo Stripe */
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
    font-size: 72px; /* Más grande e imponente */
    font-weight: 900;
    letter-spacing: -3px;
    line-height: 1;
    color: var(--vp-text-primary);
    transition: opacity 0.2s, transform 0.2s;
}

.summary-price.updating {
    opacity: 0;
    transform: translateY(-10px) scale(0.95);
}

.summary-billing {
    font-size: 15px;
    color: var(--vp-text-secondary);
    margin: 16px 0 32px;
    font-weight: 500;
}

.summary-divider {
    border: 0;
    height: 1px;
    background: var(--vp-border);
    margin: 0 0 32px 0;
}

/* Lista de Beneficios Pulcra */
.features-title {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--vp-text-secondary);
    margin-bottom: 24px;
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
    font-size: 15px;
    line-height: 1.5;
    color: var(--vp-text-secondary);
    font-weight: 500;
    animation: slideInRight 0.3s ease forwards;
    opacity: 0;
}

.summary-features li strong {
    color: var(--vp-text-primary);
    font-weight: 700;
}

/* Escalado para retraso en listas animadas */
.summary-features li:nth-child(1) { animation-delay: 0.1s; }
.summary-features li:nth-child(2) { animation-delay: 0.15s; }
.summary-features li:nth-child(3) { animation-delay: 0.2s; }
.summary-features li:nth-child(4) { animation-delay: 0.25s; }
.summary-features li:nth-child(5) { animation-delay: 0.3s; }

.summary-features li i {
    color: var(--vp-accent);
    font-size: 20px;
    margin-top: 1px;
}

/* CTA Super Premium */
.summary-cta {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    background-color: var(--vp-text-primary);
    color: #FFFFFF;
    text-decoration: none;
    padding: 20px;
    border-radius: 16px;
    font-size: 16px;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(15,23,42,0.15);
}

.summary-cta:hover {
    background-color: #000000;
    transform: translateY(-2px);
    box-shadow: 0 15px 25px rgba(15,23,42,0.25);
    color: #FFFFFF;
}

.summary-cta i {
    transition: transform 0.3s;
}

.summary-cta:hover i {
    transform: translateX(6px);
}

.summary-guarantee {
    text-align: center;
    font-size: 13px;
    color: var(--vp-text-secondary);
    margin: 24px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-weight: 500;
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
        gap: 60px;
        max-width: 600px;
        margin: 0 auto;
    }
    .guided-summary {
        position: relative;
        top: 0;
    }
}

@media (max-width: 768px) {
    .guided-pricing-section {
        padding: 60px 0 80px;
    }
    .guided-title {
        font-size: 34px;
        letter-spacing: -1px;
    }
    .premium-segmented-control {
        flex-direction: column;
    }
    .segmented-indicator {
        width: calc(100% - 12px);
        height: calc(50% - 6px);
    }
    .pet-selector-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .summary-price {
        font-size: 56px;
    }
    .summary-card {
        padding: 32px 24px;
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
            // Solo si hay elementos renderizados
            const firstBtn = document.querySelector('.pet-btn');
            if(firstBtn) firstBtn.classList.add('active');
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
            // Mantenemos solo el número entero, separando con espacio si prefieres visualmente, 
            // pero toLocaleString ya lo hace según el locale.
            sumPrice.textContent = formattedPrice.replace(/,/g, ' ');

            // Billing Period
            if(currentType === 'onetime') {
                sumDesc.textContent = "La armadura digital completa para siempre.";
                sumBilling.textContent = "Pago único. Sin cargos ocultos.";
                ctaText.textContent = "Elegir Plan";
            } else {
                sumDesc.textContent = "Protección superior y soporte premium.";
                sumBilling.textContent = `Renovación cada ${data.subDuration} meses. Cancela cuando quieras.`;
                ctaText.textContent = "Iniciar suscripción";
            }

            // Recrear Lista de Beneficios (Para disparar la animación de cascada)
            sumFeatures.innerHTML = '';
            
            let features = [];
            if(currentType === 'onetime') {
                features = [
                    `<strong>${activePlan.pets_included} ${activePlan.pets_included == 1 ? 'Placa Inteligente' : 'Placas Inteligentes'} QR</strong>`,
                    "Perfil digital siempre disponible",
                    "Alertas de escaneo al correo",
                    "Sistema de reporte rápido en plataforma"
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
                // Icono sólido moderno azul
                li.innerHTML = `<i class="fa-solid fa-circle-check"></i> <span>${feat}</span>`;
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
