@extends('layouts.app')

@section('title', 'Realizar Pago - ' . config('app.name'))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

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
        letter-spacing: -0.01em;
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

    .payment-header h2 {
        position: relative;
        z-index: 1;
        font-weight: 800;
        font-size: 1.75rem;
        margin: 0;
        letter-spacing: -0.02em;
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
        letter-spacing: -0.01em;
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
        letter-spacing: -0.02em;
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
        letter-spacing: -0.01em;
    }

    .bank-detail-value {
        font-weight: 700;
        color: #1f2937;
        user-select: all;
        cursor: pointer;
        transition: color 0.3s ease;
        font-size: 1rem;
        letter-spacing: -0.01em;
        font-variant-numeric: tabular-nums;
    }

    .bank-detail-value:hover {
        color: #4e89e8;
    }

    .amount-highlight {
        font-size: 2.25rem;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 900;
        letter-spacing: -0.03em;
        font-variant-numeric: tabular-nums;
    }

    /* Upload Zone Mejorada con Estados */
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

    .upload-zone:hover {
        border-color: #4e89e8;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(78, 137, 232, 0.15);
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
        transition: all 0.3s ease;
    }

    .upload-zone:hover .upload-icon {
        transform: scale(1.05);
    }

    .upload-zone.active .upload-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        animation: successPulse 0.6s ease-out;
    }

    @keyframes successPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }

    .upload-zone h5 {
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
        letter-spacing: -0.01em;
        transition: all 0.3s ease;
    }

    .upload-zone.active h5 {
        color: #059669;
    }

    .upload-zone p {
        transition: all 0.3s ease;
    }

    /* Status Badge en Upload Zone */
    .upload-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 12px;
        color: #059669;
        font-weight: 600;
        font-size: 0.875rem;
        margin-top: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .upload-zone.active .upload-status {
        opacity: 1;
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
        letter-spacing: -0.01em;
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
        background-clip: text;
        letter-spacing: -0.03em;
        font-variant-numeric: tabular-nums;
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
        letter-spacing: -0.01em;
    }

    .info-card ol li strong {
        color: #1e293b;
        font-weight: 700;
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
        letter-spacing: -0.01em;
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
        letter-spacing: -0.01em;
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
        letter-spacing: -0.01em;
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

        .amount-highlight {
            font-size: 1.75rem;
        }

        .summary-total {
            font-size: 1.75rem;
        }
    }

    /* ===== PAYMENT METHOD CARDS ===== */
    .payment-method-card,
    .shipping-zone-card {
        display: block;
        padding: 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        background: white;
        height: 100%;
    }

    .payment-method-card:hover,
    .shipping-zone-card:hover {
        border-color: #4e89e8;
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(78, 137, 232, 0.2);
    }

    .payment-method-card.active,
    .shipping-zone-card.active {
        border-color: #4e89e8;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        box-shadow: 0 10px 30px rgba(78, 137, 232, 0.25);
    }

    .payment-method-icon,
    .shipping-zone-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: white;
        font-size: 1.75rem;
    }

    .payment-method-title,
    .shipping-zone-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0.5rem;
    }

    .payment-method-desc,
    .shipping-zone-desc {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .shipping-zone-cost {
        font-size: 1.5rem;
        font-weight: 800;
        color: #4e89e8;
        margin-bottom: 0.5rem;
    }

    .payment-info-section {
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
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
            <!-- SELECCIÓN DE MÉTODO DE PAGO -->
            <div class="payment-card mb-4">
                <div class="payment-header">
                    <h2><i class="fa-solid fa-credit-card me-2"></i>Método de Pago</h2>
                </div>
                <div class="p-4">
                    <div class="modern-alert">
                        <div class="modern-alert-icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <div class="modern-alert-content">
                            <strong>Importante:</strong> Selecciona tu método de pago preferido y realiza el pago por el monto exacto. Luego sube tu comprobante.
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label fw-bold">Selecciona tu método de pago:</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="radio" name="payment_method" id="method_transfer" value="transfer" class="d-none payment-method-radio" checked>
                                <label for="method_transfer" class="payment-method-card active">
                                    <div class="payment-method-icon">
                                        <i class="fa-solid fa-building-columns"></i>
                                    </div>
                                    <div class="payment-method-title">Transferencia Bancaria</div>
                                    <div class="payment-method-desc">Transferencia o depósito bancario</div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="radio" name="payment_method" id="method_sinpe" value="sinpe" class="d-none payment-method-radio">
                                <label for="method_sinpe" class="payment-method-card">
                                    <div class="payment-method-icon">
                                        <i class="fa-solid fa-mobile-screen"></i>
                                    </div>
                                    <div class="payment-method-title">SINPE Móvil</div>
                                    <div class="payment-method-desc">Pago inmediato desde tu celular</div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- INFORMACIÓN DE TRANSFERENCIA -->
                    <div id="transfer-info" class="payment-info-section mt-4">
                        <div class="section-title">
                            <i class="fa-solid fa-building-columns"></i>
                            <span>Datos Bancarios</span>
                        </div>

                        <div class="bank-info-box">
                            <div class="bank-detail">
                                <span class="bank-detail-label">Banco:</span>
                                <span class="bank-detail-value">BAC SAN JOSÉ</span>
                            </div>
                            <div class="bank-detail">
                                <span class="bank-detail-label">Cuenta IBAN:</span>
                                <span class="bank-detail-value" title="Click para copiar">CR00 0000 0000 0000 0000</span>
                            </div>
                            <div class="bank-detail">
                                <span class="bank-detail-label">Cuenta BAC:</span>
                                <span class="bank-detail-value" title="Click para copiar">978151618</span>
                            </div>
                            <div class="bank-detail">
                                <span class="bank-detail-label">Titular:</span>
                                <span class="bank-detail-value">Ronaldo Segura Paniagua</span>
                            </div>
                            <div class="bank-detail">
                                <span class="bank-detail-label">Monto a transferir:</span>
                                <span class="bank-detail-value amount-highlight" id="transfer-amount">₡{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- INFORMACIÓN DE SINPE -->
                    <div id="sinpe-info" class="payment-info-section mt-4" style="display: none;">
                        <div class="section-title">
                            <i class="fa-solid fa-mobile-screen"></i>
                            <span>Datos de SINPE Móvil</span>
                        </div>

                        <div class="bank-info-box">
                            <div class="bank-detail">
                                <span class="bank-detail-label">Número SINPE:</span>
                                <span class="bank-detail-value">8530-7943</span>
                            </div>
                            <div class="bank-detail">
                                <span class="bank-detail-label">Nombre:</span>
                                <span class="bank-detail-value">Ronaldo Segura Paniagua</span>
                            </div>
                            <div class="bank-detail">
                                <span class="bank-detail-label">Monto a enviar:</span>
                                <span class="bank-detail-value amount-highlight" id="sinpe-amount">₡{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="modern-alert warning mt-3">
                            <div class="modern-alert-icon">
                                <i class="fa-solid fa-exclamation-triangle"></i>
                            </div>
                            <div class="modern-alert-content">
                                <strong>Importante:</strong> En la descripción del SINPE, incluye el texto "Plan QR-Pet Tag" para poder identificar tu pago.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('checkout.upload') }}" method="POST" enctype="multipart/form-data" id="paymentForm" class="mt-4">
                @csrf
                {{-- Los datos del plan y cantidad ahora se manejan en sesión para mayor seguridad --}}
                {{-- Hidden input para payment_method (se actualiza desde los radio buttons de arriba) --}}
                <input type="hidden" name="payment_method" id="payment_method_input" value="transfer">

                <!-- OPCIONES DE ENVÍO -->
                <div class="payment-card mb-4">
                    <div class="payment-header">
                        <h2><i class="fa-solid fa-truck me-2"></i>Opciones de Envío</h2>
                    </div>
                    <div class="p-4">
                        <div class="modern-alert">
                            <div class="modern-alert-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div class="modern-alert-content">
                                <strong>Envío a través de Correos de Costa Rica</strong><br>
                                Selecciona la zona de envío. El costo se sumará automáticamente al total.
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-bold">Selecciona tu zona de envío:</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="radio" name="shipping_zone" id="zone_gam" value="gam" class="d-none shipping-zone-radio" checked>
                                    <label for="zone_gam" class="shipping-zone-card active">
                                        <div class="shipping-zone-icon">
                                            <i class="fa-solid fa-city"></i>
                                        </div>
                                        <div class="shipping-zone-title">Dentro del GAM</div>
                                        <div class="shipping-zone-cost">+ ₡1,500</div>
                                        <div class="shipping-zone-desc">Gran Área Metropolitana</div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" name="shipping_zone" id="zone_fuera_gam" value="fuera_gam" class="d-none shipping-zone-radio">
                                    <label for="zone_fuera_gam" class="shipping-zone-card">
                                        <div class="shipping-zone-icon">
                                            <i class="fa-solid fa-map"></i>
                                        </div>
                                        <div class="shipping-zone-title">Fuera del GAM</div>
                                        <div class="shipping-zone-cost">+ ₡3,500</div>
                                        <div class="shipping-zone-desc">Resto de Costa Rica</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-bold" for="shipping_address">Dirección de Envío:</label>
                            <textarea name="shipping_address"
                                      id="shipping_address"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Ejemplo: San José, Escazú, del Banco Nacional 200m oeste, casa esquinera portón negro"
                                      required></textarea>
                            <small class="text-muted">Incluye provincia, cantón, distrito y señas exactas</small>
                        </div>
                    </div>
                </div>

                <div class="payment-card">
                    <div class="p-4">
                        <div class="section-title">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Subir Comprobante</span>
                        </div>

                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('payment_proof').click()">
                            <div class="upload-icon" id="uploadIcon">
                                <i class="fa-solid fa-hand-pointer"></i>
                            </div>
                            <h5 class="fw-bold mb-2" id="uploadTitle">Toca para subir tu comprobante</h5>
                            <p class="text-muted mb-0" id="uploadSubtitle">JPG, PNG o PDF (máx. 5MB)</p>
                            <p class="text-muted mt-2 mb-0"><small>O arrastra tu archivo aquí</small></p>
                            <div class="upload-status" id="uploadStatus">
                                <i class="fa-solid fa-check-circle"></i>
                                <span>¡Archivo listo para enviar!</span>
                            </div>
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
                        <div class="summary-row" id="summary-shipping-row">
                            <span>Envío:</span>
                            <strong id="summary-shipping">₡1,500</strong>
                        </div>
                        <div class="summary-row">
                            <span class="h5 mb-0 fw-bold">TOTAL:</span>
                            <span class="summary-total" id="summary-total">₡{{ number_format($total + 1500, 0, ',', '.') }}</span>
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
                        <a href="https://wa.me/50670000000?text=Hola,%20necesito%20ayuda%20con%20mi%20pago%20del%20plan%20{{ urlencode($plan->name) }}"
                           target="_blank"
                           class="help-btn btn-whatsapp">
                            <i class="fa-brands fa-whatsapp fs-5"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="mailto:soporte@qrpettag.com?subject=Ayuda con pago - {{ $plan->name }}"
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

{{-- Loading Overlay Ultra Moderno --}}
<div id="loadingOverlay" class="loading-overlay-modern">
    <div class="loading-backdrop"></div>
    <div class="loading-content-modern">
        <!-- Animated Icon Container -->
        <div class="loading-icon-container">
            <!-- Outer Ring -->
            <div class="loading-ring loading-ring-outer"></div>
            <!-- Middle Ring -->
            <div class="loading-ring loading-ring-middle"></div>
            <!-- Inner Ring -->
            <div class="loading-ring loading-ring-inner"></div>
            <!-- Center Icon -->
            <div class="loading-center-icon">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <!-- Particles -->
            <div class="loading-particle loading-particle-1"></div>
            <div class="loading-particle loading-particle-2"></div>
            <div class="loading-particle loading-particle-3"></div>
            <div class="loading-particle loading-particle-4"></div>
        </div>

        <!-- Text Content -->
        <div class="loading-text-modern">
            <h3 class="loading-title">Subiendo tu comprobante</h3>
            <p class="loading-subtitle">Por favor espera, esto tomará solo unos segundos</p>
        </div>

        <!-- Progress Bar -->
        <div class="loading-progress-container">
            <div class="loading-progress-bar">
                <div class="loading-progress-fill"></div>
                <div class="loading-progress-glow"></div>
            </div>
            <div class="loading-percentage" id="loadingPercentage">0%</div>
        </div>

        <!-- Steps Indicator -->
        <div class="loading-steps">
            <div class="loading-step loading-step-active">
                <div class="loading-step-icon"><i class="fa-solid fa-check"></i></div>
                <span>Preparando</span>
            </div>
            <div class="loading-step loading-step-active">
                <div class="loading-step-icon"><i class="fa-solid fa-spinner fa-spin"></i></div>
                <span>Subiendo</span>
            </div>
            <div class="loading-step">
                <div class="loading-step-icon"><i class="fa-solid fa-circle"></i></div>
                <span>Finalizando</span>
            </div>
        </div>
    </div>
</div>

<style>
/* Loading Overlay Styles */
.loading-overlay-modern {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', sans-serif;
}

.loading-overlay-modern.show {
    display: flex;
}

.loading-backdrop {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(30, 41, 59, 0.98) 100%);
    backdrop-filter: blur(12px);
}

.loading-content-modern {
    position: relative;
    z-index: 1;
    text-align: center;
    max-width: 400px;
    padding: 20px;
}

/* Icon Container */
.loading-icon-container {
    width: 160px;
    height: 160px;
    margin: 0 auto 40px;
    position: relative;
}

/* Rings */
.loading-ring {
    position: absolute;
    border-radius: 50%;
    border: 3px solid transparent;
}

.loading-ring-outer {
    width: 160px;
    height: 160px;
    border-top-color: #4e89e8;
    border-right-color: #4e89e8;
    animation: spinSlow 3s cubic-bezier(0.4, 0, 0.2, 1) infinite;
    opacity: 0.6;
}

.loading-ring-middle {
    width: 120px;
    height: 120px;
    top: 20px;
    left: 20px;
    border-top-color: #10b981;
    border-left-color: #10b981;
    animation: spinMedium 2s cubic-bezier(0.4, 0, 0.2, 1) infinite reverse;
    opacity: 0.8;
}

.loading-ring-inner {
    width: 80px;
    height: 80px;
    top: 40px;
    left: 40px;
    border-top-color: #60a5fa;
    border-right-color: #60a5fa;
    border-bottom-color: #60a5fa;
    animation: spinFast 1.5s linear infinite;
}

/* Center Icon */
.loading-center-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    box-shadow: 0 8px 32px rgba(78, 137, 232, 0.4);
    animation: iconFloat 3s ease-in-out infinite;
}

/* Particles */
.loading-particle {
    position: absolute;
    width: 8px;
    height: 8px;
    background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
    border-radius: 50%;
    opacity: 0;
    animation: particleFloat 3s ease-in-out infinite;
}

.loading-particle-1 {
    top: 10%;
    left: 50%;
    animation-delay: 0s;
}

.loading-particle-2 {
    top: 50%;
    right: 10%;
    animation-delay: 0.75s;
}

.loading-particle-3 {
    bottom: 10%;
    left: 50%;
    animation-delay: 1.5s;
}

.loading-particle-4 {
    top: 50%;
    left: 10%;
    animation-delay: 2.25s;
}

/* Text Styles */
.loading-text-modern {
    margin-bottom: 32px;
}

.loading-title {
    font-size: 26px;
    font-weight: 800;
    color: white;
    margin: 0 0 12px 0;
    letter-spacing: -0.02em;
    animation: textFade 2s ease-in-out infinite;
}

.loading-subtitle {
    font-size: 15px;
    color: rgba(255, 255, 255, 0.7);
    margin: 0;
    letter-spacing: -0.01em;
    line-height: 1.5;
}

/* Progress Bar */
.loading-progress-container {
    margin-bottom: 32px;
}

.loading-progress-bar {
    position: relative;
    width: 100%;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 12px;
}

.loading-progress-fill {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #4e89e8 0%, #10b981 100%);
    border-radius: 10px;
    animation: progressFill 3s ease-in-out infinite;
}

.loading-progress-glow {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 40px;
    background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.5) 50%, transparent 100%);
    border-radius: 10px;
    animation: progressGlow 2s ease-in-out infinite;
}

.loading-percentage {
    font-size: 13px;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.8);
    letter-spacing: 0.05em;
}

/* Loading Steps */
.loading-steps {
    display: flex;
    justify-content: center;
    gap: 24px;
    flex-wrap: wrap;
}

.loading-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    opacity: 0.4;
    transition: opacity 0.3s ease;
}

.loading-step-active {
    opacity: 1;
}

.loading-step-icon {
    width: 36px;
    height: 36px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255, 255, 255, 0.6);
    font-size: 14px;
    transition: all 0.3s ease;
}

.loading-step-active .loading-step-icon {
    background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
    color: white;
    box-shadow: 0 4px 16px rgba(78, 137, 232, 0.4);
}

.loading-step span {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    letter-spacing: -0.01em;
}

.loading-step-active span {
    color: rgba(255, 255, 255, 0.9);
}

/* Animations */
@keyframes spinSlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes spinMedium {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes spinFast {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes iconFloat {
    0%, 100% { 
        transform: translate(-50%, -50%) scale(1);
    }
    50% { 
        transform: translate(-50%, -50%) scale(1.1);
    }
}

@keyframes particleFloat {
    0%, 100% {
        opacity: 0;
        transform: translate(0, 0) scale(0);
    }
    50% {
        opacity: 1;
        transform: translate(var(--tx, 0), var(--ty, -30px)) scale(1);
    }
}

@keyframes textFade {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@keyframes progressFill {
    0% { width: 0%; }
    100% { width: 100%; }
}

@keyframes progressGlow {
    0% { left: -40px; }
    100% { left: 100%; }
}

/* Responsive */
@media (max-width: 480px) {
    .loading-icon-container {
        width: 120px;
        height: 120px;
    }

    .loading-ring-outer {
        width: 120px;
        height: 120px;
    }

    .loading-ring-middle {
        width: 90px;
        height: 90px;
        top: 15px;
        left: 15px;
    }

    .loading-ring-inner {
        width: 60px;
        height: 60px;
        top: 30px;
        left: 30px;
    }

    .loading-center-icon {
        width: 48px;
        height: 48px;
        font-size: 22px;
    }

    .loading-title {
        font-size: 22px;
    }

    .loading-steps {
        gap: 16px;
    }
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
    const uploadIcon = document.getElementById('uploadIcon');
    const uploadTitle = document.getElementById('uploadTitle');
    const uploadSubtitle = document.getElementById('uploadSubtitle');

    if (file) {
        // Habilitar botón
        submitBtn.disabled = false;
        uploadZone.classList.add('active');

        // Cambiar icono y texto
        uploadIcon.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
        uploadTitle.textContent = '¡Comprobante cargado!';
        uploadSubtitle.textContent = file.name;

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
        uploadIcon.innerHTML = '<i class="fa-solid fa-hand-pointer"></i>';
        uploadTitle.textContent = 'Toca para subir tu comprobante';
        uploadSubtitle.textContent = 'JPG, PNG o PDF (máx. 5MB)';
    }
}

// Drag & Drop support
const uploadZone = document.getElementById('uploadZone');

uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.style.borderColor = '#4e89e8';
    uploadZone.style.background = 'linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%)';
});

uploadZone.addEventListener('dragleave', () => {
    if (!uploadZone.classList.contains('active')) {
        uploadZone.style.borderColor = '#cbd5e1';
        uploadZone.style.background = 'linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%)';
    }
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('payment_proof').files = files;
        previewFile({ target: { files: files } });
    }
});

// Loading Overlay cuando se envía el formulario
const paymentForm = document.getElementById('paymentForm');
const loadingOverlay = document.getElementById('loadingOverlay');
const submitBtn = document.getElementById('submitBtn');

paymentForm.addEventListener('submit', function(e) {
    // Mostrar el overlay de carga
    loadingOverlay.classList.add('show');

    // Deshabilitar el botón de submit
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Subiendo...';

    // Simular progreso de carga
    simulateProgress();
});

// Función para simular progreso realista
function simulateProgress() {
    const percentageElement = document.getElementById('loadingPercentage');
    const steps = document.querySelectorAll('.loading-step');
    let progress = 0;
    
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        
        if (progress > 100) {
            progress = 100;
            clearInterval(interval);
            
            // Activar paso final
            steps[2].classList.add('loading-step-active');
        }
        
        // Actualizar porcentaje
        percentageElement.textContent = Math.floor(progress) + '%';
        
        // Activar pasos según progreso
        if (progress > 30) {
            steps[1].classList.add('loading-step-active');
        }
        if (progress > 70) {
            steps[2].classList.add('loading-step-active');
        }
    }, 200);
}

// ===== PAYMENT METHOD TOGGLE =====
(() => {
    const paymentRadios = document.querySelectorAll('.payment-method-radio');
    const paymentCards = document.querySelectorAll('.payment-method-card');
    const transferInfo = document.getElementById('transfer-info');
    const sinpeInfo = document.getElementById('sinpe-info');
    const paymentMethodInput = document.getElementById('payment_method_input');

    paymentRadios.forEach((radio, index) => {
        radio.addEventListener('change', () => {
            // Actualizar clases active
            paymentCards.forEach(card => card.classList.remove('active'));
            paymentCards[index].classList.add('active');

            // Actualizar hidden input del formulario
            paymentMethodInput.value = radio.value;

            // Mostrar/ocultar información de pago
            if (radio.value === 'transfer') {
                transferInfo.style.display = 'block';
                sinpeInfo.style.display = 'none';
            } else if (radio.value === 'sinpe') {
                transferInfo.style.display = 'none';
                sinpeInfo.style.display = 'block';
            }
        });
    });
})();

// ===== SHIPPING ZONE TOGGLE & TOTAL CALCULATION =====
(() => {
    const shippingRadios = document.querySelectorAll('.shipping-zone-radio');
    const shippingCards = document.querySelectorAll('.shipping-zone-card');
    const transferAmount = document.getElementById('transfer-amount');
    const sinpeAmount = document.getElementById('sinpe-amount');
    const summaryTotal = document.getElementById('summary-total');
    const summaryShipping = document.getElementById('summary-shipping');

    // Total base del plan (sin envío)
    const baseTotal = {{ $total }};
    const shippingCosts = {
        'gam': 1500,
        'fuera_gam': 3500
    };

    function updateTotal() {
        const selectedZone = document.querySelector('.shipping-zone-radio:checked').value;
        const shippingCost = shippingCosts[selectedZone];
        const newTotal = baseTotal + shippingCost;

        // Actualizar todos los displays de total
        if (transferAmount) {
            transferAmount.textContent = '₡' + newTotal.toLocaleString('es-CR');
        }
        if (sinpeAmount) {
            sinpeAmount.textContent = '₡' + newTotal.toLocaleString('es-CR');
        }
        if (summaryTotal) {
            summaryTotal.textContent = '₡' + newTotal.toLocaleString('es-CR');
        }
        if (summaryShipping) {
            summaryShipping.textContent = '₡' + shippingCost.toLocaleString('es-CR');
        }
    }

    shippingRadios.forEach((radio, index) => {
        radio.addEventListener('change', () => {
            // Actualizar clases active
            shippingCards.forEach(card => card.classList.remove('active'));
            shippingCards[index].classList.add('active');

            // Recalcular total
            updateTotal();
        });
    });

    // Calcular total inicial
    updateTotal();
})();
</script>
@endsection
