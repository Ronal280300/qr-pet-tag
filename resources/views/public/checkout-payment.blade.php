@extends('layouts.app')

@section('title', 'Realizar Pago - ' . config('app.name'))

@push('styles')
<style>
    .payment-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 40px;
        position: relative;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        width: 100%;
        height: 4px;
        background: #e5e7eb;
        z-index: 0;
    }

    .progress-line {
        position: absolute;
        top: 20px;
        left: 0;
        height: 4px;
        background: linear-gradient(90deg, #4e89e8, #10b981);
        width: 66%;
        z-index: 1;
        transition: width 0.5s ease;
    }

    .step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }

    .step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: white;
        border: 4px solid #e5e7eb;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .step.completed .step-circle {
        background: linear-gradient(135deg, #10b981, #059669);
        border-color: #10b981;
        color: white;
    }

    .step.active .step-circle {
        background: linear-gradient(135deg, #4e89e8, #0e61c6);
        border-color: #4e89e8;
        color: white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(78, 137, 232, 0.4); }
        50% { box-shadow: 0 0 0 10px rgba(78, 137, 232, 0); }
    }

    .step-label {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
    }

    .step.completed .step-label,
    .step.active .step-label {
        color: #1f2937;
    }

    .payment-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .payment-header {
        background: linear-gradient(135deg, #4e89e8 0%, #0e61c6 100%);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .bank-info-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
    }

    .bank-detail {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px dashed #dee2e6;
    }

    .bank-detail:last-child {
        border-bottom: none;
    }

    .bank-detail-value {
        font-weight: 700;
        color: #1f2937;
        user-select: all;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .bank-detail-value:hover {
        color: #4e89e8;
    }

    .amount-highlight {
        font-size: 2rem;
        color: #4e89e8;
    }

    .upload-zone {
        border: 3px dashed #cbd5e1;
        border-radius: 15px;
        padding: 40px 20px;
        text-align: center;
        background: #f8fafc;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-zone:hover {
        border-color: #4e89e8;
        background: #eff6ff;
    }

    .upload-zone.active {
        border-color: #10b981;
        background: #ecfdf5;
    }

    .upload-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #4e89e8, #0e61c6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 36px;
    }

    .preview-container {
        border-radius: 15px;
        overflow: hidden;
        background: #f8fafc;
        padding: 20px;
    }

    .preview-image {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .order-summary {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-radius: 15px;
        padding: 25px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(78, 137, 232, 0.2);
    }

    .summary-row:last-child {
        border-bottom: none;
        padding-top: 15px;
        margin-top: 10px;
        border-top: 2px solid #4e89e8;
    }

    .summary-total {
        font-size: 1.8rem;
        font-weight: 800;
        color: #4e89e8;
    }

    .info-card {
        background: white;
        border-left: 4px solid #4e89e8;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .help-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .help-btn {
        flex: 1;
        min-width: 200px;
        padding: 15px 25px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
    }

    .help-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .btn-whatsapp {
        background: linear-gradient(135deg, #25d366, #128c7e);
        color: white;
    }

    .btn-email {
        background: linear-gradient(135deg, #4e89e8, #0e61c6);
        color: white;
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
                    <h2 class="mb-0"><i class="fa-solid fa-credit-card me-2"></i>Información de Pago</h2>
                </div>
                <div class="p-4">
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fa-solid fa-circle-info fs-4 me-3"></i>
                        <div>
                            Realiza la transferencia por el monto exacto y luego sube tu comprobante.
                            Te contactaremos en menos de 24 horas.
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-building-columns me-2 text-primary"></i>Datos Bancarios</h5>
                    <div class="bank-info-box">
                        <div class="bank-detail">
                            <span class="text-muted">Banco:</span>
                            <span class="bank-detail-value">Banco Nacional de Costa Rica</span>
                        </div>
                        <div class="bank-detail">
                            <span class="text-muted">Cuenta IBAN:</span>
                            <span class="bank-detail-value" title="Click para copiar">CR00 0000 0000 0000 0000</span>
                        </div>
                        <div class="bank-detail">
                            <span class="text-muted">Titular:</span>
                            <span class="bank-detail-value">QR Pet Tag S.A.</span>
                        </div>
                        <div class="bank-detail">
                            <span class="text-muted">Cédula:</span>
                            <span class="bank-detail-value">3-101-XXXXXX</span>
                        </div>
                        <div class="bank-detail">
                            <span class="text-muted">Monto a transferir:</span>
                            <span class="bank-detail-value amount-highlight">₡{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="fa-solid fa-mobile-screen fs-4 me-3"></i>
                        <div>
                            <strong>SINPE Móvil:</strong> También puedes usar el número <strong>8888-8888</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Form -->
            <form action="{{ route('checkout.upload') }}" method="POST" enctype="multipart/form-data" id="paymentForm" class="mt-4">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <input type="hidden" name="pets_quantity" value="{{ $petsQuantity }}">

                <div class="payment-card">
                    <div class="p-4">
                        <h5 class="fw-bold mb-4"><i class="fa-solid fa-cloud-arrow-up me-2 text-success"></i>Subir Comprobante</h5>

                        <div class="upload-zone" id="uploadZone" onclick="document.getElementById('payment_proof').click()">
                            <div class="upload-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Click para seleccionar archivo</h5>
                            <p class="text-muted mb-0">JPG, PNG o PDF (máx. 5MB)</p>
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
                                    <i class="fa-solid fa-file-pdf text-danger" style="font-size: 80px;"></i>
                                    <p class="mt-3 fw-bold" id="pdfName"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Información de qué sigue -->
                        <div class="info-card mt-4">
                            <h6 class="fw-bold mb-3"><i class="fa-solid fa-list-check me-2 text-primary"></i>¿Qué sigue después?</h6>
                            <ol class="mb-0 ps-3">
                                <li class="mb-2">Verificaremos tu pago en <strong>máximo 24 horas</strong></li>
                                <li class="mb-2">Te contactaremos para coordinar tus placas personalizadas</li>
                                <li class="mb-2">Podrás registrar la información de tus mascotas</li>
                                <li>Recibirás tus placas en 3-5 días hábiles</li>
                            </ol>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-3 mt-4">
                            <button type="submit"
                                    id="submitBtn"
                                    class="btn btn-success btn-lg"
                                    style="border-radius: 12px; padding: 16px;"
                                    disabled>
                                <i class="fa-solid fa-paper-plane me-2"></i>
                                Enviar Comprobante
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg" style="border-radius: 12px;">
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
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-receipt me-2 text-primary"></i>Resumen del Pedido</h5>

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
                            <span class="h5 mb-0">TOTAL:</span>
                            <span class="summary-total">₡{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="payment-card">
                <div class="p-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-headset me-2 text-primary"></i>¿Necesitas Ayuda?</h5>
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
</script>
@endsection
