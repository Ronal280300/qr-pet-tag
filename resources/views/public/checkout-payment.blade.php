@extends('layouts.app')

@section('title', 'Realizar Pago - ' . config('app.name'))

@push('styles')
<style>
    .payment-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Progress Steps Modernizado */
    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 50px;
        position: relative;
        padding: 0 20px;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 22px;
        left: 20px;
        right: 20px;
        height: 3px;
        background: linear-gradient(to right, #e5e7eb, #f3f4f6);
        z-index: 0;
        border-radius: 10px;
    }

    .progress-line {
        position: absolute;
        top: 22px;
        left: 20px;
        height: 3px;
        background: linear-gradient(90deg, #10b981 0%, #4e89e8 100%);
        width: 50%;
        z-index: 1;
        transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(78, 137, 232, 0.4);
    }

    .step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }

    .step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e5e7eb;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .step.completed .step-circle {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
        color: white;
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    .step.active .step-circle {
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        border-color: #4e89e8;
        color: white;
        animation: pulse-glow 2s ease-in-out infinite;
        transform: scale(1.1);
        box-shadow: 0 6px 24px rgba(78, 137, 232, 0.4);
    }

    @keyframes pulse-glow {
        0%, 100% { 
            box-shadow: 0 6px 24px rgba(78, 137, 232, 0.4), 0 0 0 0 rgba(78, 137, 232, 0.4);
        }
        50% { 
            box-shadow: 0 6px 24px rgba(78, 137, 232, 0.4), 0 0 0 12px rgba(78, 137, 232, 0);
        }
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #9ca3af;
        transition: color 0.3s ease;
    }

    .step.completed .step-label,
    .step.active .step-label {
        color: #1f2937;
    }

    /* Cards Modernizadas */
    .payment-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .payment-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
    }

    .payment-header {
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        color: white;
        padding: 36px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .payment-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .payment-header h2 {
        position: relative;
        z-index: 1;
        font-weight: 800;
        font-size: 1.75rem;
        margin: 0;
    }

    /* Alert Modernizado */
    .modern-alert {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border: 2px solid #93c5fd;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: start;
        gap: 16px;
        margin-bottom: 30px;
    }

    .modern-alert.warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-color: #fbbf24;
    }

    .modern-alert-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.5rem;
    }

    .modern-alert .modern-alert-icon {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .modern-alert.warning .modern-alert-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .modern-alert-content {
        flex: 1;
        color: #1e40af;
        line-height: 1.6;
    }

    .modern-alert.warning .modern-alert-content {
        color: #92400e;
    }

    /* Bank Info Box Mejorado */
    .bank-info-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 24px;
        border: 2px solid #e2e8f0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 24px;
        color: #1a202c;
    }

    .section-title i {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
    }

    .bank-detail {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: white;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .bank-detail:last-child {
        margin-bottom: 0;
    }

    .bank-detail:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(78, 137, 232, 0.15);
        border-color: #93c5fd;
    }

    .bank-detail-label {
        color: #64748b;
        font-weight: 500;
        font-size: 0.9375rem;
    }

    .bank-detail-value {
        font-weight: 700;
        color: #1f2937;
        user-select: all;
        cursor: pointer;
        transition: color 0.3s ease;
        font-size: 1rem;
    }

    .bank-detail-value:hover {
        color: #4e89e8;
    }

    .amount-highlight {
        font-size: 2.25rem;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 900;
        letter-spacing: -0.5px;
    }

    /* Upload Zone Mejorada */
    .upload-zone {
        border: 3px dashed #cbd5e1;
        border-radius: 20px;
        padding: 50px 30px;
        text-align: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .upload-zone::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(78, 137, 232, 0.05) 0%, rgba(16, 185, 129, 0.05) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .upload-zone:hover {
        border-color: #4e89e8;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(78, 137, 232, 0.15);
    }

    .upload-zone:hover::before {
        opacity: 1;
    }

    .upload-zone.active {
        border-color: #10b981;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-style: solid;
    }

    .upload-icon {
        width: 90px;
        height: 90px;
        margin: 0 auto 24px;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        box-shadow: 0 10px 30px rgba(78, 137, 232, 0.3);
        transition: transform 0.3s ease;
    }

    .upload-zone:hover .upload-icon {
        transform: scale(1.1) rotateZ(5deg);
    }

    .upload-zone.active .upload-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    /* Preview Mejorado */
    .preview-container {
        border-radius: 20px;
        overflow: hidden;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 30px;
        border: 2px solid #e2e8f0;
    }

    .preview-image {
        max-width: 100%;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .preview-pdf-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        box-shadow: 0 10px 30px rgba(239, 68, 68, 0.3);
    }

    /* Order Summary Mejorado */
    .order-summary {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 20px;
        padding: 32px;
        border: 2px solid #93c5fd;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid rgba(78, 137, 232, 0.2);
        color: #1e40af;
        font-weight: 500;
    }

    .summary-row:last-child {
        border-bottom: none;
        padding-top: 24px;
        margin-top: 16px;
        border-top: 3px solid #60a5fa;
    }

    .summary-total {
        font-size: 2.25rem;
        font-weight: 900;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: -1px;
    }

    /* Info Card Mejorada */
    .info-card {
        background: white;
        border-left: 5px solid #4e89e8;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .info-card ol li {
        padding: 10px 0;
        color: #475569;
        line-height: 1.6;
    }

    .info-card ol li strong {
        color: #1e293b;
    }

    /* Help Buttons Mejorados */
    .help-buttons {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .help-btn {
        flex: 1;
        min-width: 180px;
        padding: 18px 28px;
        border-radius: 16px;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 1rem;
    }

    .help-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .btn-whatsapp {
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.3);
    }

    .btn-whatsapp:hover {
        box-shadow: 0 15px 35px rgba(37, 211, 102, 0.4);
    }

    .btn-email {
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 8px 24px rgba(78, 137, 232, 0.3);
    }

    .btn-email:hover {
        box-shadow: 0 15px 35px rgba(78, 137, 232, 0.4);
    }

    /* Botones Principales */
    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 16px;
        padding: 20px;
        font-weight: 700;
        font-size: 1.125rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-submit:hover:not(:disabled) {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-cancel {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        font-weight: 700;
        font-size: 1.125rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    @media (max-width: 768px) {
        .progress-steps {
            padding: 0 10px;
        }

        .step-label {
            font-size: 0.75rem;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .help-buttons {
            flex-direction: column;
        }

        .help-btn {
            min-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5 payment-container">

    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="progress-line"></div>
        <div class="step completed">
            <div class="step-circle">
                <i class="fa-solid fa-check"></i>
            </div>
            <div class="step-label">Plan Seleccionado</div>
        </div>
        <div class="step active">
            <div class="step-circle">2</div>
            <div class="step-label">Realizar Pago</div>
        </div>
        <div class="step">
            <div class="step-circle">3</div>
            <div class="step-label">Confirmación</div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Izquierda: Instrucciones -->
        <div class="col-lg-7">
            <div class="payment-card">
                <div class="payment-header">
                    <h2><i class="fa-solid fa-credit-card me-2"></i>Información de Pago</h2>
                </div>
                <div class="p-4">
                    <div class="modern-alert">
                        <div class="modern-alert-icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div class="modern-alert-content">
                            <strong>Importante:</strong> Realiza la transferencia por el monto exacto y luego sube tu comprobante. Te contactaremos en menos de 24 horas.
                        </div>
                    </div>

                    <div class="section-title">
                        <i class="fa-solid fa-building-columns"></i>
                        <span>Datos Bancarios</span>
                    </div>

                    <div class="bank-info-box">
                        <div class="bank-detail">
                            <span class="bank-detail-label">Banco:</span>
                            <span class="bank-detail-value">Banco Nacional de Costa Rica</span>
                        </div>
                        <div class="bank-detail">
                            <span class="bank-detail-label">Cuenta IBAN:</span>
                            <span class="bank-detail-value" title="Click para copiar">CR00 0000 0000 0000 0000</span>
                        </div>
                        <div class="bank-detail">
                            <span class="bank-detail-label">Titular:</span>
                            <span class="bank-detail-value">QR Pet Tag S.A.</span>
                        </div>
                        <div class="bank-detail">
                            <span class="bank-detail-label">Cédula:</span>
                            <span class="bank-detail-value">3-101-XXXXXX</span>
                        </div>
                        <div class="bank-detail">
                            <span class="bank-detail-label">Monto a transferir:</span>
                            <span class="bank-detail-value amount-highlight">₡{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="modern-alert warning">
                        <div class="modern-alert-icon">
                            <i class="fa-solid fa-mobile-screen"></i>
                        </div>
                        <div class="modern-alert-content">
                            <strong>SINPE Móvil:</strong> También puedes usar el número <strong>6290-1184</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('checkout.upload') }}" method="POST" enctype="multipart/form-data" id="paymentForm" class="mt-4">
                @csrf
                {{-- Los datos del plan y cantidad ahora se manejan en sesión para mayor seguridad --}}

                <div class="payment-card">
                    <div class="p-4">
                        <div class="section-title">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Subir Comprobante</span>
                        </div>

                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('payment_proof').click()">
                            <div class="upload-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Click para seleccionar archivo</h5>
                            <p class="text-muted mb-0">JPG, PNG o PDF (máx. 5MB)</p>
                            <p class="text-muted mt-2 mb-0"><small>O arrastra tu archivo aquí</small></p>
                        </div>

                        <input type="file"
                               name="payment_proof"
                               id="payment_proof"
                               class="d-none"
                               accept="image/*,.pdf"
                               onchange="previewFile(event)"
                               required>

                        @error('payment_proof')
                        <div class="alert alert-danger mt-3">{{ $message }}</div>
                        @enderror

                        <!-- Preview -->
                        <div id="previewContainer" class="mt-4" style="display: none;">
                            <h6 class="fw-bold mb-3">Vista Previa:</h6>
                            <div class="preview-container text-center">
                                <img id="previewImage" src="" class="preview-image" style="display: none;">
                                <div id="previewPDF" style="display: none;">
                                    <div class="preview-pdf-icon">
                                        <i class="fa-solid fa-file-pdf"></i>
                                    </div>
                                    <p class="fw-bold mb-0" id="pdfName"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Información de qué sigue -->
                        <div class="info-card mt-4">
                            <h6 class="fw-bold mb-3">
                                <i class="fa-solid fa-list-check me-2 text-primary"></i>
                                ¿Qué sigue después?
                            </h6>
                            <ol class="mb-0 ps-3">
                                <li>Verificaremos tu pago en <strong>máximo 24 horas</strong></li>
                                <li>Te contactaremos para coordinar tus placas personalizadas</li>
                                <li>Podrás registrar la información de tus mascotas</li>
                                <li>Recibirás tus placas en 3-5 días hábiles</li>
                            </ol>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-3 mt-4">
                            <button type="submit"
                                    id="submitBtn"
                                    class="btn btn-submit"
                                    disabled>
                                <i class="fa-solid fa-paper-plane"></i>
                                <span>Enviar Comprobante</span>
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-cancel text-center">
                                Cancelar
                            </a>
                        </div>

                        <small class="text-muted d-block mt-3 text-center">
                            <i class="fa-solid fa-shield-halved me-1"></i>
                            Tu información está segura y será verificada por nuestro equipo
                        </small>
                    </div>
                </div>
            </form>
        </div>

        <!-- Columna Derecha: Resumen -->
        <div class="col-lg-5">
            <!-- Order Summary -->
            <div class="payment-card mb-4">
                <div class="p-4">
                    <div class="section-title">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Resumen del Pedido</span>
                    </div>

                    <div class="order-summary">
                        <div class="summary-row">
                            <span>Plan:</span>
                            <strong>{{ $plan->name }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Tipo:</span>
                            <strong>{{ $plan->type === 'one_time' ? 'Pago Único' : 'Suscripción' }}</strong>
                        </div>
                        <div class="summary-row">
                            <span>Mascotas:</span>
                            <strong>{{ $petsQuantity }}</strong>
                        </div>
                        @if($additionalPets > 0)
                        <div class="summary-row">
                            <span>Mascotas adicionales:</span>
                            <strong>{{ $additionalPets }} x ₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}</strong>
                        </div>
                        @endif
                        <div class="summary-row">
                            <span class="h5 mb-0 fw-bold">TOTAL:</span>
                            <span class="summary-total">₡{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="payment-card">
                <div class="p-4">
                    <div class="section-title">
                        <i class="fa-solid fa-headset"></i>
                        <span>¿Necesitas Ayuda?</span>
                    </div>
                    <p class="text-muted mb-4">Estamos aquí para ayudarte en cada paso</p>

                    <div class="help-buttons">
                        <a href="https://wa.me/50662901184?text=Hola,%20necesito%20ayuda%20con%20mi%20pago%20del%20plan%20{{ urlencode($plan->name) }}"
                           target="_blank"
                           class="help-btn btn-whatsapp">
                            <i class="fa-brands fa-whatsapp fs-5"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="mailto:info.qrpettag@gmail.com?subject=Ayuda con pago - {{ $plan->name }}"
                           class="help-btn btn-email">
                            <i class="fa-solid fa-envelope fs-5"></i>
                            <span>Email</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay Moderno --}}
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.95); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(10px);">
    <div style="text-align: center; color: white;">
        <div style="width: 120px; height: 120px; margin: 0 auto 32px; position: relative;">
            <div style="width: 120px; height: 120px; border: 6px solid rgba(78, 137, 232, 0.2); border-radius: 50%; position: absolute;"></div>
            <div style="width: 120px; height: 120px; border: 6px solid transparent; border-top-color: #4e89e8; border-radius: 50%; animation: spin 1s linear infinite; position: absolute;"></div>
            <div style="width: 90px; height: 90px; border: 4px solid transparent; border-top-color: #10b981; border-radius: 50%; animation: spin 1.5s linear infinite reverse; position: absolute; top: 15px; left: 15px;"></div>
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 40px;">
                <i class="fa-solid fa-cloud-arrow-up" style="animation: pulse 2s ease-in-out infinite;"></i>
            </div>
        </div>
        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 12px;">Subiendo tu comprobante...</h3>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.7); margin: 0;">Por favor espera, esto tomará solo unos segundos</p>
        <div style="margin-top: 24px;">
            <div style="width: 200px; height: 4px; background: rgba(255, 255, 255, 0.1); border-radius: 2px; margin: 0 auto; overflow: hidden;">
                <div style="width: 100%; height: 100%; background: linear-gradient(90deg, #4e89e8, #10b981); animation: progress 2s ease-in-out infinite;"></div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}
@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.8; }
}
@keyframes progress {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
</style>

<script>
function previewFile(event) {
    const file = event.target.files[0];
    const submitBtn = document.getElementById('submitBtn');
    const previewContainer = document.getElementById('previewContainer');
    const previewImage = document.getElementById('previewImage');
    const previewPDF = document.getElementById('previewPDF');
    const uploadZone = document.getElementById('uploadZone');

    if (file) {
        // Habilitar botón
        submitBtn.disabled = false;
        uploadZone.classList.add('active');

        // Mostrar preview
        previewContainer.style.display = 'block';

        if (file.type === 'application/pdf') {
            previewImage.style.display = 'none';
            previewPDF.style.display = 'block';
            document.getElementById('pdfName').textContent = file.name;
        } else {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                previewPDF.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    } else {
        submitBtn.disabled = true;
        previewContainer.style.display = 'none';
        uploadZone.classList.remove('active');
    }
}

// Drag & Drop support
const uploadZone = document.getElementById('uploadZone');

uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('active');
});

uploadZone.addEventListener('dragleave', () => {
    uploadZone.classList.remove('active');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('active');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('payment_proof').files = files;
        previewFile({ target: { files: files } });
    }
});

// Loading Overlay cuando se envía el formulario
const paymentForm = document.getElementById('paymentForm');
const loadingOverlay = document.getElementById('loadingOverlay');

paymentForm.addEventListener('submit', function(e) {
    // Mostrar el overlay de carga
    loadingOverlay.style.display = 'flex';

    // Deshabilitar el botón de submit
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Subiendo...';
});
</script>
@endsection
