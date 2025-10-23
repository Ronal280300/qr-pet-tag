@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="fw-bold">Finalizar compra</h1>
                <p class="text-muted">Revisa los detalles de tu plan antes de continuar</p>
            </div>

            <!-- Plan Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="fw-bold mb-3">{{ $plan->name }}</h3>
                            <p class="text-muted mb-3">{{ $plan->description }}</p>

                            <h6 class="fw-bold text-primary mb-3">Incluye:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> {{ $plan->pets_included }} {{ Str::plural('placa', $plan->pets_included) }} con QR personalizado</li>
                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Perfil digital completo para cada mascota</li>
                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Actualizaciones ilimitadas</li>
                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Sistema de recompensas</li>
                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Soporte por WhatsApp</li>
                                @if($plan->type === 'subscription')
                                <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Renovación automática</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center">
                                    <div class="display-4 fw-bold text-primary">
                                        ₡{{ number_format($plan->price, 0, ',', '.') }}
                                    </div>
                                    <p class="text-muted mb-0">
                                        @if($plan->type === 'one_time')
                                        Pago único
                                        @else
                                        Cada {{ $plan->duration_months }} {{ Str::plural('mes', $plan->duration_months) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selector de mascotas -->
            <form action="{{ route('checkout.create', $plan) }}" method="POST">
                @csrf
                <div class="card shadow border-0 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">¿Cuántas mascotas deseas registrar?</h5>

                        <div class="mb-3">
                            <label for="pets_quantity" class="form-label fw-bold">Cantidad de mascotas</label>
                            <select name="pets_quantity" id="pets_quantity" class="form-select form-select-lg" onchange="updateTotal()">
                                @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $i == $petsQuantity ? 'selected' : '' }}>
                                    {{ $i }} {{ Str::plural('mascota', $i) }}
                                </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Cálculo del total -->
                        <div id="calculation-breakdown" class="alert alert-info">
                            <h6 class="fw-bold mb-2">Detalle del costo:</h6>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Plan base ({{ $plan->pets_included }} {{ Str::plural('mascota', $plan->pets_included) }}):</span>
                                <span class="fw-bold">₡{{ number_format($plan->price, 0, ',', '.') }}</span>
                            </div>
                            <div id="additional-cost" class="d-flex justify-content-between mb-1" style="display: {{ $additionalPets > 0 ? 'flex' : 'none' }} !important;">
                                <span><span id="additional-pets-count">{{ $additionalPets }}</span> {{ Str::plural('mascota', $additionalPets) }} adicional(es):</span>
                                <span class="fw-bold">₡<span id="additional-cost-amount">{{ number_format($additionalCost, 0, ',', '.') }}</span></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="h5 fw-bold">Total:</span>
                                <span class="h4 fw-bold text-primary">₡<span id="total-amount">{{ number_format($total, 0, ',', '.') }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex gap-3">
                    <a href="{{ route('home') }}#planes" class="btn btn-outline-secondary btn-lg">
                        <i class="fa-solid fa-arrow-left me-2"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1">
                        Continuar <i class="fa-solid fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const plan = @json($plan);

function updateTotal() {
    const quantity = parseInt(document.getElementById('pets_quantity').value);
    const included = plan.pets_included;
    const additionalPets = Math.max(0, quantity - included);
    const additionalCost = additionalPets * plan.additional_pet_price;
    const total = plan.price + additionalCost;

    // Actualizar UI
    document.getElementById('additional-pets-count').textContent = additionalPets;
    document.getElementById('additional-cost-amount').textContent = additionalCost.toLocaleString('es-CR');
    document.getElementById('total-amount').textContent = total.toLocaleString('es-CR');

    // Mostrar/ocultar costo adicional
    const additionalCostDiv = document.getElementById('additional-cost');
    if (additionalPets > 0) {
        additionalCostDiv.style.display = 'flex';
    } else {
        additionalCostDiv.style.display = 'none';
    }
}
</script>
@endsection
