@extends('layouts.app')

@section('title', 'Confirmación - ' . config('app.name'))

@push('styles')
<style>
    /* Progress Steps Modernizado */
    .progress-steps-wrapper {
        margin-bottom: 50px;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        padding: 0 20px;
        margin-bottom: 16px;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 22px;
        left: 20px;
        right: 20px;
        height: 3px;
        background: linear-gradient(to right, #d1fae5, #a7f3d0);
        z-index: 0;
        border-radius: 10px;
    }

    .progress-line {
        position: absolute;
        top: 22px;
        left: 20px;
        height: 3px;
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        width: 100%;
        z-index: 1;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.4);
        animation: progressComplete 1s ease-out;
    }

    @keyframes progressComplete {
        from { width: 0%; }
        to { width: 100%; }
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
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: white;
        font-size: 1.125rem;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        animation: checkmarkPop 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    @keyframes checkmarkPop {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .step-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #064e3b;
    }

    /* Success Icon */
    .success-section {
        text-align: center;
        margin-bottom: 48px;
        padding: 40px 20px;
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border-radius: 24px;
        border: 2px solid #a7f3d0;
    }

    .success-icon {
        width: 140px;
        height: 140px;
        margin: 0 auto 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        box-shadow: 0 20px 60px rgba(16, 185, 129, 0.3);
        animation: successBounce 1s ease-out;
    }

    @keyframes successBounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-20px); }
        60% { transform: translateY(-10px); }
    }

    .success-icon i {
        font-size: 70px;
        color: white;
    }

    .success-section h1 {
        font-size: 2.5rem;
        font-weight: 900;
        color: #064e3b;
        margin-bottom: 16px;
    }

    .success-section .lead {
        font-size: 1.25rem;
        color: #065f46;
        margin: 0;
    }

    /* Cards Modernizadas */
    .modern-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
    }

    .card-header-custom {
        padding: 28px 32px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .card-title-custom {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        color: #1a202c;
    }

    .card-title-custom i {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .card-body-custom {
        padding: 32px;
    }

    /* Info Boxes */
    .info-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        padding: 20px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .info-box:hover {
        border-color: #cbd5e1;
        transform: translateX(4px);
    }

    .info-box-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 6px;
        display: block;
    }

    .info-box-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
    }

    .info-box-value.highlight {
        font-size: 1.75rem;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Modern Alert */
    .modern-alert {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 2px solid #93c5fd;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: start;
        gap: 16px;
        margin-top: 24px;
    }

    .modern-alert.success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-color: #6ee7b7;
    }

    .modern-alert.warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-color: #fbbf24;
    }

    .modern-alert-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.5rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .modern-alert.success .modern-alert-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .modern-alert.warning .modern-alert-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .modern-alert-content {
        flex: 1;
        color: #1e40af;
        line-height: 1.6;
    }

    .modern-alert.success .modern-alert-content {
        color: #065f46;
    }

    .modern-alert.warning .modern-alert-content {
        color: #92400e;
    }

    /* Progress Bar */
    .progress-bar-custom {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 16px;
        padding: 20px;
        border: 2px solid #93c5fd;
        margin-bottom: 24px;
    }

    .progress-bar-custom .progress {
        height: 12px;
        border-radius: 10px;
        background: #e5e7eb;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .progress-bar-custom .progress-bar {
        background: linear-gradient(90deg, #4e89e8 0%, #2563eb 100%);
        box-shadow: 0 2px 8px rgba(78, 137, 232, 0.4);
        transition: width 0.6s ease;
    }

    /* Option Cards */
    .option-card {
        border: 2px solid #e5e7eb;
        border-radius: 20px;
        padding: 28px;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .option-card:hover {
        border-color: #4e89e8;
        box-shadow: 0 12px 35px rgba(78, 137, 232, 0.15);
        transform: translateY(-6px);
    }

    .option-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 2rem;
        box-shadow: 0 8px 24px rgba(78, 137, 232, 0.3);
        transition: transform 0.3s ease;
    }

    .option-card:hover .option-icon {
        transform: scale(1.1) rotateZ(5deg);
    }

    .option-icon.whatsapp {
        background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        box-shadow: 0 8px 24px rgba(37, 211, 102, 0.3);
    }

    .option-card h6 {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #1a202c;
    }

    .option-card p {
        color: #64748b;
        font-size: 0.9375rem;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .option-card .btn {
        border-radius: 12px;
        padding: 14px 24px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .option-card .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Timeline Moderna */
    .timeline-modern {
        position: relative;
        padding-left: 50px;
    }

    .timeline-modern::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 16px;
        bottom: 16px;
        width: 3px;
        background: linear-gradient(to bottom, #10b981 0%, #e5e7eb 25%, #e5e7eb 100%);
        border-radius: 10px;
    }

    .timeline-item-modern {
        position: relative;
        margin-bottom: 32px;
    }

    .timeline-item-modern:last-child {
        margin-bottom: 0;
    }

    .timeline-marker-modern {
        position: absolute;
        left: -50px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
        z-index: 1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .timeline-marker-modern.done {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        animation: timelinePop 0.6s ease-out;
    }

    .timeline-marker-modern.pending {
        background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
    }

    @keyframes timelinePop {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .timeline-content-modern {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .timeline-item-modern.done .timeline-content-modern {
        border-color: #10b981;
        background: linear-gradient(135deg, #ecfdf5 0%, #ffffff 100%);
    }

    .timeline-content-modern:hover {
        transform: translateX(6px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .timeline-content-modern h6 {
        font-weight: 700;
        margin-bottom: 6px;
        color: #1a202c;
        font-size: 1.125rem;
    }

    .timeline-content-modern p {
        color: #64748b;
        font-size: 0.9375rem;
        margin: 0;
    }

    /* Pets List */
    .pets-list {
        list-style: none;
        padding: 0;
        margin: 16px 0 0 0;
    }

    .pets-list li {
        padding: 12px 16px;
        background: white;
        border-radius: 12px;
        margin-bottom: 8px;
        border: 2px solid #e5e7eb;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .pets-list li:hover {
        border-color: #10b981;
        transform: translateX(4px);
    }

    .pets-list li::before {
        content: '🐾';
        font-size: 1.25rem;
    }

    /* Buttons */
    .btn-modern {
        border-radius: 16px;
        padding: 16px 32px;
        font-weight: 700;
        font-size: 1.125rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-modern.btn-primary {
        background: linear-gradient(135deg, #4e89e8 0%, #2563eb 100%);
        border: none;
        box-shadow: 0 8px 24px rgba(78, 137, 232, 0.3);
    }

    .btn-modern.btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(78, 137, 232, 0.4);
    }

    .btn-modern.btn-outline-secondary {
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .btn-modern.btn-outline-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .success-section h1 {
            font-size: 2rem;
        }

        .success-icon {
            width: 110px;
            height: 110px;
        }

        .success-icon i {
            font-size: 50px;
        }

        .option-card {
            margin-bottom: 16px;
        }
    }

    /* ===== ESTILOS DEL FORMULARIO DE MASCOTA (mismo que admin) ===== */
    .section-card {
        border: 1px solid #eef1f5;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 18px;
        background: #fff;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 18px;
    }

    .section-title {
        font-weight: 800;
        margin: 0;
        font-size: 1.1rem;
    }

    .section-sub {
        color: #6b7280;
        font-size: .95rem;
    }

    .form-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 0;
    }

    .ft-switch {
        position: relative;
        display: inline-flex;
        width: 52px;
        height: 30px;
        flex: 0 0 auto;
        cursor: pointer;
    }

    .ft-switch input {
        position: absolute;
        inline-size: 100%;
        block-size: 100%;
        opacity: 0;
        margin: 0;
        cursor: pointer;
    }

    .ft-switch .track {
        position: relative;
        inline-size: 100%;
        block-size: 100%;
        background: #e5e7eb;
        border-radius: 999px;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, .12);
        transition: background .2s ease;
    }

    .ft-switch .thumb {
        position: absolute;
        inset-block-start: 50%;
        inset-inline-start: 3px;
        transform: translateY(-50%);
        inline-size: 24px;
        block-size: 24px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        transition: left .2s ease, inset-inline-start .2s ease;
    }

    .ft-switch input:checked+.track {
        background: #2563eb;
    }

    .ft-switch input:checked+.track .thumb {
        inset-inline-start: calc(100% - 27px);
    }

    @media (max-width: 480px) {
        .ft-switch {
            width: 46px;
            height: 26px;
        }
        .ft-switch .thumb {
            inline-size: 20px;
            block-size: 20px;
            inset-inline-start: 3px;
        }
        .ft-switch input:checked+.track .thumb {
            inset-inline-start: calc(100% - 23px);
        }
    }

    .segmented {
        display: inline-grid;
        grid-auto-flow: column;
        gap: 6px;
        background: #f6f7fb;
        padding: 6px;
        border-radius: 12px;
        border: 1px solid #eef1f5;
    }

    .segmented .seg {
        display: none;
    }

    .segmented label {
        padding: .45rem .8rem;
        border-radius: 10px;
        cursor: pointer;
        user-select: none;
        color: #374151;
        background: transparent;
    }

    .segmented .seg:checked+label {
        background: #115DFC;
        color: #fff;
        font-weight: 700;
    }

    .input-icon {
        position: relative;
    }

    .input-icon>i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9aa0aa;
    }

    .input-icon>.form-control,
    .input-icon>.form-select {
        padding-left: 40px;
    }

    .photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: .75rem;
    }

    .photos-grid .ph {
        position: relative;
        border: 1px solid #e5e7eb;
        border-radius: .5rem;
        overflow: hidden;
        background: #f8fafc;
        aspect-ratio: 1 / 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .photos-grid .ph img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photos-grid .ph .ph-remove {
        position: absolute;
        top: .35rem;
        right: .35rem;
        border: 0;
        border-radius: 999px;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, .55);
        color: #fff;
    }

    .photo-uploader {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        align-items: center;
    }

    .photo-uploader__preview {
        border: 1px dashed #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
        min-height: 160px;
        display: grid;
        place-items: center;
        position: relative;
        overflow: hidden;
    }

    .photo-uploader__preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-uploader__preview.is-dragover {
        outline: 2px dashed #2563eb;
        outline-offset: -8px;
    }

    .photo-uploader__overlay {
        color: #9aa0aa;
        font-size: .95rem;
        padding: 6px 10px;
        text-align: center;
    }

    .photo-uploader__actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    /* Fix modal scroll */
    #registerPetModal .modal-dialog.modal-dialog-scrollable .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }

    #registerPetModal .modal-dialog.modal-dialog-scrollable {
        max-height: calc(100vh - 3.5rem);
    }

    #registerPetModal .modal-content {
        height: 100%;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progress Steps -->
            <div class="progress-steps-wrapper">
                <div class="progress-steps">
                    <div class="progress-line"></div>
                    <div class="step">
                        <div class="step-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="step-label">Plan Seleccionado</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="step-label">Pago</div>
                    </div>
                    <div class="step">
                        <div class="step-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div class="step-label">Confirmación</div>
                    </div>
                </div>
            </div>

            <!-- Success Section -->
            <div class="success-section">
                <div class="success-icon">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h1>¡Comprobante recibido!</h1>
                <p class="lead">
                    Gracias por tu compra. Hemos recibido tu comprobante de pago.
                </p>
            </div>

            <!-- Información del pedido -->
            <div class="modern-card">
                <div class="card-header-custom">
                    <div class="card-title-custom">
                        <i class="fa-solid fa-receipt"></i>
                        <span>Detalles de tu pedido</span>
                    </div>
                </div>
                <div class="card-body-custom">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-label">Número de pedido</span>
                                <div class="info-box-value">{{ $order->order_number }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-label">Plan seleccionado</span>
                                <div class="info-box-value">{{ $order->plan->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-label">Cantidad de mascotas</span>
                                <div class="info-box-value">{{ $order->pets_quantity }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-label">Total pagado</span>
                                <div class="info-box-value highlight">₡{{ number_format($order->total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="modern-alert">
                        <div class="modern-alert-icon">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="modern-alert-content">
                            <strong class="d-block mb-1">¿Qué sigue ahora?</strong>
                            <p class="mb-0">
                                Verificaremos tu pago en un plazo máximo de <strong>24 horas hábiles</strong>.
                                Una vez verificado, te contactaremos para coordinar la personalización de tus placas QR.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Siguiente paso: Info de mascotas -->
            <div class="modern-card">
                <div class="card-body-custom">
                    @php
                        $registeredPets = $order->pets ? $order->pets->count() : 0;
                        $totalPets = $order->pets_quantity;
                        $remainingPets = $totalPets - $registeredPets;
                        $allPetsRegistered = $remainingPets <= 0;
                    @endphp

                    <div class="card-title-custom" style="margin-bottom: 20px;">
                        <i class="fa-solid fa-dog" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"></i>
                        <span>
                            @if($allPetsRegistered)
                                ¡Todas tus mascotas están registradas!
                            @else
                                ¿Quieres adelantar el proceso?
                            @endif
                        </span>
                    </div>

                    @if(!$allPetsRegistered)
                    <p class="text-muted mb-4">
                        Mientras verificamos tu pago, puedes empezar a registrar la información de tus mascotas
                        para agilizar el proceso de personalización de las placas.
                    </p>
                    @endif

                    @if(session('success'))
                    <div class="modern-alert success">
                        <div class="modern-alert-icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div class="modern-alert-content">
                            {{ session('success') }}
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="modern-alert warning">
                        <div class="modern-alert-icon">
                            <i class="fa-solid fa-circle-exclamation"></i>
                        </div>
                        <div class="modern-alert-content">
                            {{ session('error') }}
                        </div>
                    </div>
                    @endif

                    <!-- Progreso de registro de mascotas -->
                    @if($totalPets > 1)
                    <div class="progress-bar-custom">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
                            <div>
                                <strong style="color: #1e40af;"><i class="fa-solid fa-list-check me-2"></i>Progreso de registro:</strong>
                                <span class="ms-2" style="color: #1e40af;">{{ $registeredPets }} de {{ $totalPets }} mascotas registradas</span>
                            </div>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $totalPets > 0 ? ($registeredPets / $totalPets * 100) : 0 }}%"
                                 aria-valuenow="{{ $registeredPets }}"
                                 aria-valuemin="0"
                                 aria-valuemax="{{ $totalPets }}">
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Mostrar mascotas ya registradas -->
                    @if($registeredPets > 0)
                    <div class="modern-alert {{ $allPetsRegistered ? 'success' : '' }}" style="@if(!$allPetsRegistered) background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-color: #93c5fd; @endif">
                        <div class="modern-alert-icon">
                            <i class="fa-solid fa-check-circle"></i>
                        </div>
                        <div class="modern-alert-content">
                            <strong class="d-block mb-2">
                                @if($allPetsRegistered)
                                    ¡Perfecto! Has registrado todas tus mascotas:
                                @else
                                    Mascotas registradas hasta ahora:
                                @endif
                            </strong>
                            <ul class="pets-list">
                                @foreach($order->pets as $pet)
                                <li>{{ $pet->name }}@if($pet->breed) - {{ $pet->breed }}@endif</li>
                                @endforeach
                            </ul>
                            @if(!$allPetsRegistered)
                            <div class="mt-3 pt-3" style="border-top: 1px solid rgba(147, 197, 253, 0.5);">
                                <small>
                                    <i class="fa-solid fa-info-circle me-1"></i>
                                    Aún puedes registrar <strong>{{ $remainingPets }} mascota(s) más</strong>
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row g-4 mt-2">
                        @if(!$allPetsRegistered)
                        <div class="col-md-6">
                            <div class="option-card">
                                <div class="option-icon">
                                    <i class="fa-solid fa-paw"></i>
                                </div>
                                <h6>Registrar ahora</h6>
                                <p class="small">
                                    @if($registeredPets > 0)
                                        Registra la siguiente mascota ({{ $registeredPets + 1 }} de {{ $totalPets }})
                                    @else
                                        Llena el formulario con los datos de tu{{ $totalPets > 1 ? 's' : '' }} mascota{{ $totalPets > 1 ? 's' : '' }}
                                    @endif
                                </p>
                                <a href="{{ route('checkout.register-pet-form', $order) }}" class="btn btn-primary w-100" id="btnRegisterPet">
                                    <i class="fa-solid fa-plus"></i>
                                    @if($registeredPets > 0)
                                        Registrar mascota {{ $registeredPets + 1 }}
                                    @else
                                        Registrar mascota
                                    @endif
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-{{ $allPetsRegistered ? '12' : '6' }}">
                            <div class="option-card">
                                <div class="option-icon whatsapp">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <h6>
                                    @if($allPetsRegistered)
                                        ¿Necesitas ayuda?
                                    @else
                                        Continuar por WhatsApp
                                    @endif
                                </h6>
                                <p class="small">
                                    @if($allPetsRegistered)
                                        Contáctanos si necesitas hacer algún cambio
                                    @else
                                        Te ayudamos paso a paso con el registro de tus mascotas
                                    @endif
                                </p>
                                <a href="https://wa.me/50670000000?text=Hola,%20quiero%20ayuda%20para%20registrar%20mis%20mascotas.%20Mi%20pedido%20es%20{{ $order->order_number }}"
                                   target="_blank"
                                   class="btn btn-success w-100">
                                    <i class="fa-brands fa-whatsapp"></i> Abrir WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    @if(!$allPetsRegistered)
                    <div class="modern-alert" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-color: #e2e8f0; margin-top: 24px;">
                        <div class="modern-alert-icon" style="background: linear-gradient(135deg, #64748b 0%, #475569 100%);">
                            <i class="fa-solid fa-info-circle"></i>
                        </div>
                        <div class="modern-alert-content" style="color: #475569;">
                            <strong>Nota:</strong> No te preocupes, puedes registrar tus mascotas más tarde.
                            Te enviaremos un recordatorio por correo.
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline de proceso -->
            <div class="modern-card">
                <div class="card-header-custom">
                    <div class="card-title-custom">
                        <i class="fa-solid fa-list-check"></i>
                        <span>Próximos pasos</span>
                    </div>
                </div>
                <div class="card-body-custom">
                    <div class="timeline-modern">
                        <div class="timeline-item-modern done">
                            <div class="timeline-marker-modern done">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="timeline-content-modern">
                                <h6>Pago realizado</h6>
                                <p>Comprobante recibido exitosamente</p>
                            </div>
                        </div>

                        <div class="timeline-item-modern">
                            <div class="timeline-marker-modern pending">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <div class="timeline-content-modern">
                                <h6>Verificación de pago</h6>
                                <p>En proceso (máx. 24 horas)</p>
                            </div>
                        </div>

                        <div class="timeline-item-modern">
                            <div class="timeline-marker-modern pending">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div class="timeline-content-modern">
                                <h6>Personalización</h6>
                                <p>Diseñaremos tus placas QR</p>
                            </div>
                        </div>

                        <div class="timeline-item-modern">
                            <div class="timeline-marker-modern pending">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                            <div class="timeline-content-modern">
                                <h6>Envío</h6>
                                <p>Recibirás tus placas en 3-5 días</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones finales -->
            <div class="text-center">
                <a href="{{ route('portal.dashboard') }}" class="btn btn-primary btn-modern btn-lg me-2">
                    <i class="fa-solid fa-home"></i>
                    <span>Ir al panel</span>
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-modern btn-lg">
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Incluir modal con formulario completo de mascota (mismo que admin) --}}
@include('public._pet-form-modal')
@endsection

@push('scripts')
{{-- SweetAlert2 para guía interactiva --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(() => {
    // ===== Observaciones toggle
    const $noMedical = document.getElementById('no-medical');
    const $medical = document.getElementById('medical_conditions');

    function toggleMedical() {
        if ($noMedical.checked) {
            $medical.value = '';
            $medical.setAttribute('disabled', 'disabled');
        } else {
            $medical.removeAttribute('disabled');
        }
    }
    $noMedical.addEventListener('change', toggleMedical);
    toggleMedical();
})();

// ===== Cascada CR provincias/cantones/distritos
(() => {
    const API = 'https://ubicaciones.paginasweb.cr';
    const $prov = document.getElementById('cr-province');
    const $cant = document.getElementById('cr-canton');
    const $dist = document.getElementById('cr-district');
    const $zone = document.getElementById('zone');
    const $zonePreview = document.getElementById('zone-preview');

    async function getJSON(path) {
        const r = await fetch(`${API}${path}`);
        if (!r.ok) throw 0;
        return await r.json();
    }

    function fillSelect($sel, map, placeholder) {
        $sel.innerHTML = `<option value="">${placeholder}</option>`;
        for (const [id, name] of Object.entries(map)) {
            const opt = document.createElement('option');
            opt.value = id;
            opt.textContent = name;
            $sel.appendChild(opt);
        }
    }

    function setZone() {
        const pName = $prov.options[$prov.selectedIndex]?.text || '';
        const cName = $cant.options[$cant.selectedIndex]?.text || '';
        const dName = $dist.options[$dist.selectedIndex]?.text || '';
        if (pName && cName && dName) {
            const z = `${dName}, ${cName}, ${pName}`;
            $zone.value = z;
            $zonePreview.textContent = z;
        } else {
            $zone.value = '';
            $zonePreview.textContent = '—';
        }
    }

    (async () => {
        try {
            const provincias = await getJSON('/provincias.json');
            fillSelect($prov, provincias, 'Provincia');
            $prov.disabled = false;
        } catch (e) {
            const wrap = $prov.closest('.row');
            wrap.outerHTML = `
            <div class="col-12">
                <div class="alert alert-warning small mb-2">No se pudo cargar la lista de ubicaciones. Ingresa manualmente la zona.</div>
                <input class="form-control" placeholder="Ej: San Juan, Grecia, Alajuela"
                       oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
            </div>`;
        }
    })();

    $prov.addEventListener('change', async () => {
        $cant.disabled = true;
        $dist.disabled = true;
        $dist.innerHTML = `<option value="">Distrito</option>`;
        setZone();
        if (!$prov.value) {
            $cant.innerHTML = `<option value="">Cantón</option>`;
            return;
        }
        const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
        fillSelect($cant, cantones, 'Cantón');
        $cant.disabled = false;
    });

    $cant.addEventListener('change', async () => {
        $dist.disabled = true;
        $dist.innerHTML = `<option value="">Distrito</option>`;
        setZone();
        if (!$prov.value || !$cant.value) return;
        const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
        fillSelect($dist, distritos, 'Distrito');
        $dist.disabled = false;
    });

    $dist.addEventListener('change', setZone);
})();

// ===== Uploader principal (legacy) - Deprecated, using modal version
// This code is kept for compatibility but modal handles everything now

// ===== Previews de fotos múltiples + LÍMITE 3
(function() {
    const MAX = 3;
    const input = document.getElementById('photos');
    const grid = document.getElementById('photosPreviewGrid');
    const btnClear = document.getElementById('btnClearPhotos');
    let filesBuffer = [];

    function refreshGrid() {
        grid.innerHTML = '';
        if (filesBuffer.length === 0) {
            grid.classList.add('d-none');
            btnClear.classList.add('d-none');
            return;
        }
        grid.classList.remove('d-none');
        btnClear.classList.remove('d-none');

        filesBuffer.forEach((file, idx) => {
            const url = URL.createObjectURL(file);
            const cell = document.createElement('div');
            cell.className = 'ph';
            const img = document.createElement('img');
            img.src = url;
            img.alt = `Foto ${idx+1}`;
            const rm = document.createElement('button');
            rm.type = 'button';
            rm.className = 'ph-remove';
            rm.innerHTML = '<i class="fa-solid fa-xmark"></i>';
            rm.addEventListener('click', () => removeAt(idx));
            cell.appendChild(img);
            cell.appendChild(rm);
            grid.appendChild(cell);
        });
    }

    function applyBufferToInput() {
        const dt = new DataTransfer();
        filesBuffer.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    function removeAt(i) {
        filesBuffer.splice(i, 1);
        applyBufferToInput();
        refreshGrid();
    }

    input.addEventListener('change', (e) => {
        const incoming = Array.from(e.target.files || []);
        const totalIfAdded = filesBuffer.length + incoming.length;
        if (totalIfAdded > MAX) {
            const allowed = Math.max(0, MAX - filesBuffer.length);
            Swal.fire({
                icon: 'warning',
                title: 'Máximo 3 fotos adicionales',
                text: `Puedes añadir ${allowed} foto(s) más.`,
                confirmButtonText: 'Entendido'
            });
            if (allowed > 0) filesBuffer = filesBuffer.concat(incoming.slice(0, allowed));
        } else {
            filesBuffer = filesBuffer.concat(incoming);
        }
        applyBufferToInput();
        refreshGrid();
        input.value = '';
    });

    btnClear.addEventListener('click', () => {
        filesBuffer = [];
        input.value = '';
        refreshGrid();
    });

    document.getElementById('checkout-pet-form').addEventListener('submit', (e) => {
        if (filesBuffer.length > MAX) e.preventDefault();
    });
})();

// ===== GUÍA INTERACTIVA CON SWEETALERT2 - Simple y efectiva
@if(!$allPetsRegistered)
window.addEventListener('load', function() {
    // Verificar si el usuario ya vio la guía
    const hasSeenGuide = sessionStorage.getItem('hasSeenCheckoutGuide');

    if (hasSeenGuide) {
        console.log('Usuario ya vio la guía anteriormente');
        return;
    }

    // Esperar 1.5 segundos después de cargar para mejor UX
    setTimeout(function() {
        // Animar el botón antes de mostrar el tour
        const btnRegisterPet = document.getElementById('btnRegisterPet');
        if (!btnRegisterPet) return;

        // Añadir animación de pulso al botón
        btnRegisterPet.style.animation = 'pulse 1.5s infinite';
        btnRegisterPet.style.boxShadow = '0 0 0 0 rgba(78, 137, 232, 0.7)';

        // Crear estilo de animación si no existe
        if (!document.getElementById('pulse-animation')) {
            const style = document.createElement('style');
            style.id = 'pulse-animation';
            style.textContent = `
                @keyframes pulse {
                    0% {
                        box-shadow: 0 0 0 0 rgba(78, 137, 232, 0.7);
                    }
                    70% {
                        box-shadow: 0 0 0 25px rgba(78, 137, 232, 0);
                    }
                    100% {
                        box-shadow: 0 0 0 0 rgba(78, 137, 232, 0);
                    }
                }
            `;
            document.head.appendChild(style);
        }

        // PASO 1: Bienvenida y explicación
        Swal.fire({
            title: '🐾 ¡Registra tus mascotas!',
            html: `
                <div style="text-align: left; padding: 10px;">
                    <p style="font-size: 16px; margin-bottom: 16px;">
                        Según tu plan, puedes registrar <strong>{{ $totalPets }} mascota(s)</strong>.
                    </p>
                    <p style="font-size: 15px; color: #64748b; margin-bottom: 16px;">
                        💡 Mientras verificamos tu pago, puedes adelantar el proceso registrando
                        la información de tus mascotas ahora.
                    </p>
                    <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
                                border-radius: 12px; padding: 16px; border: 2px solid #93c5fd;">
                        <p style="margin: 0; font-size: 14px; color: #1e40af;">
                            <strong>⚡ Beneficio:</strong> Agiliza la personalización de tus placas QR
                        </p>
                    </div>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Ver dónde registrar',
            showCancelButton: true,
            cancelButtonText: 'Lo haré después',
            confirmButtonColor: '#4e89e8',
            cancelButtonColor: '#94a3b8',
            customClass: {
                popup: 'animated-popup',
                confirmButton: 'btn-modern',
                cancelButton: 'btn-modern'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // PASO 2: Mostrar dónde está el botón
                btnRegisterPet.scrollIntoView({ behavior: 'smooth', block: 'center' });

                setTimeout(function() {
                    Swal.fire({
                        title: '👆 ¡Aquí está!',
                        html: `
                            <div style="text-align: left; padding: 10px;">
                                <p style="font-size: 15px; margin-bottom: 16px;">
                                    Haz clic en el botón <strong style="color: #4e89e8;">"Registrar mascota"</strong>
                                    para comenzar.
                                </p>
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 12px;">
                                    📝 Te pediremos información como:
                                </p>
                                <ul style="text-align: left; font-size: 14px; color: #64748b; padding-left: 20px;">
                                    <li>Nombre y raza</li>
                                    <li>Edad y sexo</li>
                                    <li>Condiciones médicas</li>
                                    <li>Foto de tu mascota</li>
                                    <li>Ubicación (zona)</li>
                                </ul>
                                <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
                                            border-radius: 12px; padding: 12px; border: 2px solid #6ee7b7; margin-top: 12px;">
                                    <p style="margin: 0; font-size: 13px; color: #065f46;">
                                        <strong>✨ Tranquilo:</strong> También puedes registrarlas más tarde por WhatsApp
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#10b981',
                        customClass: {
                            popup: 'animated-popup'
                        },
                        showClass: {
                            popup: 'animate__animated animate__bounceIn'
                        },
                        didOpen: () => {
                            // Hacer que el botón pulse más fuerte
                            btnRegisterPet.style.animation = 'pulse 0.8s infinite';
                        },
                        didClose: () => {
                            // Quitar animación después del tour
                            setTimeout(() => {
                                btnRegisterPet.style.animation = '';
                                btnRegisterPet.style.boxShadow = '';
                            }, 3000);
                        }
                    }).then(() => {
                        // Marcar como visto
                        sessionStorage.setItem('hasSeenCheckoutGuide', 'true');
                    });
                }, 800);
            } else {
                // Si cancela, quitar animación
                btnRegisterPet.style.animation = '';
                btnRegisterPet.style.boxShadow = '';
                // Marcar como visto igual
                sessionStorage.setItem('hasSeenCheckoutGuide', 'true');
            }
        });
    }, 1500);
});
@endif

// ===== Auto-abrir modal si hay mascotas pendientes y se acaba de registrar una
(() => {
    @php
        $justRegisteredPet = session('success') && str_contains(session('success'), 'registrada exitosamente');
    @endphp
    @if($justRegisteredPet && !$allPetsRegistered)
        // Pequeño delay para que el usuario vea el mensaje de éxito primero
        setTimeout(() => {
            const modal = new bootstrap.Modal(document.getElementById('registerPetModal'));
            modal.show();
        }, 2000);
    @endif

    // Resetear formulario cuando se cierra el modal
    const modal = document.getElementById('registerPetModal');
    modal.addEventListener('hidden.bs.modal', () => {
        const form = document.getElementById('checkout-pet-form');
        form.reset();

        // Limpiar preview de foto principal
        const photoPreview = document.getElementById('photoPreview');
        const photoDrop = document.getElementById('photoDrop');
        if (photoPreview) {
            photoPreview.src = '';
            photoPreview.classList.add('d-none');
        }

        // Limpiar fotos múltiples
        const grid = document.getElementById('photosPreviewGrid');
        const btnClearPhotos = document.getElementById('btnClearPhotos');
        if (grid) {
            grid.innerHTML = '';
            grid.classList.add('d-none');
        }
        if (btnClearPhotos) {
            btnClearPhotos.classList.add('d-none');
        }

        // Resetear textarea de observaciones
        const medicalTextarea = document.getElementById('medical_conditions');
        if (medicalTextarea) {
            medicalTextarea.removeAttribute('disabled');
        }

        // Resetear zone preview
        const zonePreview = document.getElementById('zone-preview');
        if (zonePreview) {
            zonePreview.textContent = '—';
        }
    });
})();
</script>
@endpush
