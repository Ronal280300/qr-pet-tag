@extends('layouts.app')

@section('title', 'Subir comprobante - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Progreso -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-success">Paso 1: Plan seleccionado</span>
                    <span class="badge bg-primary">Paso 2: Pago</span>
                    <span class="badge bg-secondary">Paso 3: Confirmación</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 66%"></div>
                </div>
            </div>

            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="fw-bold">Realiza el pago</h1>
                <p class="text-muted">Sigue las instrucciones y sube tu comprobante</p>
            </div>

            <div class="row g-4">
                <!-- Columna izquierda: Instrucciones de pago -->
                <div class="col-lg-6">
                    <div class="card shadow border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="fa-solid fa-money-bill-transfer me-2 text-primary"></i>
                                Datos para transferencia
                            </h4>

                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                                <i class="fa-solid fa-info-circle me-3 fs-4"></i>
                                <div>
                                    <strong>Importante:</strong> Realiza la transferencia por el monto exacto para agilizar la verificación.
                                </div>
                            </div>

                            <div class="payment-info mb-4">
                                <h6 class="text-muted mb-3">Datos bancarios:</h6>
                                <div class="p-3 bg-light rounded mb-2">
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>Banco:</strong>
                                        <span>Banco Nacional de Costa Rica</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>Cuenta IBAN:</strong>
                                        <span class="user-select-all">CR00 0000 0000 0000 0000</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>Titular:</strong>
                                        <span>QR Pet Tag S.A.</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>Cédula:</strong>
                                        <span>3-101-XXXXXX</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <strong>Monto a transferir:</strong>
                                        <span class="fs-4 fw-bold text-primary">₡{{ number_format($order->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="alert alert-warning mb-0">
                                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                    <strong>SINPE Móvil:</strong> También puedes usar el número 8888-8888
                                </div>
                            </div>

                            <h6 class="text-muted mb-3">Resumen del pedido:</h6>
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Pedido:</span>
                                    <strong>{{ $order->order_number }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Plan:</span>
                                    <strong>{{ $order->plan->name }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Mascotas:</span>
                                    <strong>{{ $order->pets_quantity }}</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold">Total:</span>
                                    <span class="fs-5 fw-bold text-primary">₡{{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Upload de comprobante -->
                <div class="col-lg-6">
                    <div class="card shadow border-0 h-100">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">
                                <i class="fa-solid fa-cloud-arrow-up me-2 text-success"></i>
                                Sube tu comprobante
                            </h4>

                            <form action="{{ route('checkout.upload', $order) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Comprobante de pago</label>
                                    <input type="file"
                                           name="payment_proof"
                                           id="payment_proof"
                                           class="form-control @error('payment_proof') is-invalid @enderror"
                                           accept="image/*,.pdf"
                                           onchange="previewImage(event)"
                                           required>
                                    @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Formatos: JPG, PNG, PDF (máx. 5MB)</small>
                                </div>

                                <!-- Preview -->
                                <div id="preview-container" class="mb-4" style="display:none;">
                                    <label class="form-label fw-bold">Vista previa:</label>
                                    <div class="border rounded p-3 text-center">
                                        <img id="preview-image" src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                                        <div id="preview-pdf" style="display:none;">
                                            <i class="fa-solid fa-file-pdf text-danger" style="font-size: 60px;"></i>
                                            <p class="mt-2 mb-0" id="pdf-name"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <h6 class="fw-bold mb-2"><i class="fa-solid fa-lightbulb me-2"></i>¿Qué hacer después?</h6>
                                    <ol class="mb-0 ps-3">
                                        <li>Sube una foto clara del comprobante de pago</li>
                                        <li>Verificaremos tu pago en un plazo máximo de 24 horas</li>
                                        <li>Te contactaremos para coordinar la personalización de tus placas</li>
                                        <li>Podrás llenar la información de tus mascotas o hacerlo por WhatsApp</li>
                                    </ol>
                                </div>

                                <!-- Botones -->
                                <div class="d-grid gap-2">
                                    <button type="submit"
                                            id="submitBtn"
                                            class="btn btn-success btn-lg"
                                            disabled>
                                        <i class="fa-solid fa-paper-plane me-2"></i>
                                        Enviar comprobante
                                    </button>
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                        Cancelar
                                    </a>
                                </div>

                                <small class="text-muted d-block mt-3 text-center">
                                    <i class="fa-solid fa-shield-halved me-1"></i>
                                    Verificaremos que la transferencia se haya realizado correctamente
                                </small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opciones adicionales -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body p-4 text-center">
                    <h5 class="fw-bold mb-3">¿Necesitas ayuda?</h5>
                    <p class="text-muted">Si tienes alguna duda sobre el proceso de pago, contáctanos:</p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="https://wa.me/50670000000?text=Hola,%20necesito%20ayuda%20con%20mi%20pedido%20{{ $order->order_number }}"
                           target="_blank"
                           class="btn btn-success">
                            <i class="fa-brands fa-whatsapp me-2"></i> WhatsApp
                        </a>
                        <a href="mailto:soporte@qrpettag.com?subject=Ayuda con pedido {{ $order->order_number }}"
                           class="btn btn-primary">
                            <i class="fa-solid fa-envelope me-2"></i> Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const submitBtn = document.getElementById('submitBtn');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const previewPdf = document.getElementById('preview-pdf');

    if (file) {
        // Habilitar botón
        submitBtn.disabled = false;

        // Mostrar preview
        previewContainer.style.display = 'block';

        if (file.type === 'application/pdf') {
            previewImage.style.display = 'none';
            previewPdf.style.display = 'block';
            document.getElementById('pdf-name').textContent = file.name;
        } else {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                previewPdf.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    } else {
        submitBtn.disabled = true;
        previewContainer.style.display = 'none';
    }
}
</script>
@endsection
