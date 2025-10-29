@extends('layouts.app')

@section('title', 'Confirmación - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progreso completado -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-success">Paso 1: Plan seleccionado</span>
                    <span class="badge bg-success">Paso 2: Pago</span>
                    <span class="badge bg-success">Paso 3: Confirmación</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                </div>
            </div>

            <!-- Icono de éxito -->
            <div class="text-center mb-4">
                <div class="mb-4">
                    <div class="success-icon mx-auto">
                        <i class="fa-solid fa-circle-check text-success"></i>
                    </div>
                </div>
                <h1 class="fw-bold mb-3">¡Comprobante recibido!</h1>
                <p class="lead text-muted">
                    Gracias por tu compra. Hemos recibido tu comprobante de pago.
                </p>
            </div>

            <!-- Información del pedido -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-receipt me-2 text-primary"></i>
                        Detalles de tu pedido
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Número de pedido</small>
                                <strong class="fs-5">{{ $order->order_number }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Plan seleccionado</small>
                                <strong>{{ $order->plan->name }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Cantidad de mascotas</small>
                                <strong>{{ $order->pets_quantity }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block mb-1">Total pagado</small>
                                <strong class="fs-5 text-primary">₡{{ number_format($order->total, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 mb-0">
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-clock me-3 fs-4"></i>
                            <div>
                                <strong class="d-block mb-1">¿Qué sigue ahora?</strong>
                                <p class="mb-0">
                                    Verificaremos tu pago en un plazo máximo de <strong>24 horas hábiles</strong>.
                                    Una vez verificado, te contactaremos para coordinar la personalización de tus placas QR.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Siguiente paso: Info de mascotas -->
            <div class="card shadow border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-dog me-2 text-success"></i>
                        ¿Quieres adelantar el proceso?
                    </h5>

                    <p class="text-muted mb-4">
                        Mientras verificamos tu pago, puedes empezar a registrar la información de tus mascotas
                        para agilizar el proceso de personalización de las placas.
                    </p>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Mostrar mascotas ya registradas para esta orden -->
                    @if($order->pets && $order->pets->count() > 0)
                    <div class="alert alert-info mb-4">
                        <strong><i class="fa-solid fa-check-circle me-2"></i>Mascotas registradas:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($order->pets as $pet)
                            <li>{{ $pet->name }} ({{ $pet->species }})</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="option-card h-100">
                                <div class="option-icon">
                                    <i class="fa-solid fa-paw"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Registrar ahora</h6>
                                <p class="text-muted small mb-3">
                                    Llena el formulario con los datos de tu mascota
                                </p>
                                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#registerPetModal">
                                    <i class="fa-solid fa-plus me-2"></i> Registrar mascota
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="option-card h-100">
                                <div class="option-icon" style="background: #25d366;">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Continuar por WhatsApp</h6>
                                <p class="text-muted small mb-3">
                                    Te ayudamos paso a paso con el registro de tus mascotas
                                </p>
                                <a href="https://wa.me/50670000000?text=Hola,%20quiero%20ayuda%20para%20registrar%20mis%20mascotas.%20Mi%20pedido%20es%20{{ $order->order_number }}"
                                   target="_blank"
                                   class="btn btn-success w-100">
                                    <i class="fa-brands fa-whatsapp me-2"></i> Abrir WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light mt-4 mb-0">
                        <i class="fa-solid fa-info-circle me-2 text-primary"></i>
                        <strong>Nota:</strong> No te preocupes, puedes registrar tus mascotas más tarde.
                        Te enviaremos un recordatorio por correo.
                    </div>
                </div>
            </div>

            <!-- Timeline de proceso -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Próximos pasos</h5>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker done">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Pago realizado</h6>
                                <p class="text-muted mb-0">Comprobante recibido exitosamente</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker pending">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Verificación de pago</h6>
                                <p class="text-muted mb-0">En proceso (máx. 24 horas)</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker pending">
                                <i class="fa-solid fa-palette"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Personalización</h6>
                                <p class="text-muted mb-0">Diseñaremos tus placas QR</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-marker pending">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Envío</h6>
                                <p class="text-muted mb-0">Recibirás tus placas en 3-5 días</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones finales -->
            <div class="text-center">
                <a href="{{ route('portal.dashboard') }}" class="btn btn-primary btn-lg me-2">
                    <i class="fa-solid fa-home me-2"></i> Ir al panel
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para registrar mascota -->
<div class="modal fade" id="registerPetModal" tabindex="-1" aria-labelledby="registerPetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="registerPetModalLabel">
                    <i class="fa-solid fa-paw me-2"></i>Registrar Mascota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('checkout.store-pet', $order) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle me-2"></i>
                        Los datos de tu mascota serán guardados y se enlazarán a tu cuenta una vez que verifiquemos tu pago.
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre de la mascota <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="ej: Max">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Especie <span class="text-danger">*</span></label>
                            <select name="species" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="dog">Perro</option>
                                <option value="cat">Gato</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Raza</label>
                            <input type="text" name="breed" class="form-control" placeholder="ej: Labrador">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Edad (años)</label>
                            <input type="number" name="age" class="form-control" min="0" placeholder="ej: 3">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sexo</label>
                            <select name="sex" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="male">Macho</option>
                                <option value="female">Hembra</option>
                                <option value="unknown">Desconocido</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tamaño</label>
                            <select name="size" class="form-select">
                                <option value="">Seleccione...</option>
                                <option value="small">Pequeño</option>
                                <option value="medium">Mediano</option>
                                <option value="large">Grande</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Color</label>
                            <input type="text" name="color" class="form-control" placeholder="ej: Café">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_neutered" value="1" id="isNeutered">
                                <label class="form-check-label" for="isNeutered">
                                    ¿Está esterilizado/castrado?
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rabies_vaccine" value="1" id="rabiesVaccine">
                                <label class="form-check-label" for="rabiesVaccine">
                                    ¿Tiene vacuna antirrábica?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Condiciones médicas o alergias</label>
                        <textarea name="medical_conditions" class="form-control" rows="3" placeholder="Describe cualquier condición médica, alergia o tratamiento especial"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa-solid fa-times me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save me-2"></i>Guardar Mascota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.success-icon {
    width: 120px;
    height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.15));
    border-radius: 50%;
}

.success-icon i {
    font-size: 60px;
}

.option-card {
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    transition: all 0.3s ease;
}

.option-card:hover {
    border-color: #3b82f6;
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.1);
    transform: translateY(-4px);
}

.option-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary), var(--brand-900));
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    color: white;
    font-size: 28px;
}

/* Timeline */
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 12px;
    top: 12px;
    bottom: 12px;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    margin-bottom: 24px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -40px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    z-index: 1;
}

.timeline-marker.done {
    background: linear-gradient(135deg, #10b981, #059669);
}

.timeline-marker.pending {
    background: #9ca3af;
}

.timeline-content h6 {
    margin-bottom: 4px;
}

.timeline-content p {
    font-size: 14px;
}
</style>
@endsection
