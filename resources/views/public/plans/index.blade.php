@extends('layouts.app')

@section('title', 'Planes - QR Pet Tag')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Nuestros Planes</h1>
        <p class="lead text-muted">Elige el plan perfecto para proteger a tus mascotas</p>
    </div>

    <!-- Pestañas -->
    <ul class="nav nav-pills justify-content-center mb-5" id="planTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="onetime-tab" data-bs-toggle="pill" data-bs-target="#onetime" type="button" role="tab">
                Pago Único
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="subscription-tab" data-bs-toggle="pill" data-bs-target="#subscription" type="button" role="tab">
                Suscripciones
            </button>
        </li>
    </ul>

    <div class="tab-content" id="planTabsContent">
        <!-- Pago Único -->
        <div class="tab-pane fade show active" id="onetime" role="tabpanel">
            <div class="row g-4">
                @foreach($oneTimePlans as $plan)
                <div class="col-md-4">
                    <div class="card plan-card h-100">
                        <div class="card-body">
                            <h3 class="card-title">{{ $plan->pets_included }} {{ Str::plural('Mascota', $plan->pets_included) }}</h3>
                            <div class="price">₡{{ number_format($plan->price, 0, ',', '.') }}</div>
                            <p class="text-muted">Pago único</p>
                            <ul class="list-unstyled">
                                <li><i class="fa-solid fa-check text-success"></i> {{ $plan->pets_included }} placas QR</li>
                                <li><i class="fa-solid fa-check text-success"></i> Perfil digital</li>
                                <li><i class="fa-solid fa-check text-success"></i> Actualizaciones ilimitadas</li>
                            </ul>
                            <a href="{{ route('checkout.show', $plan) }}" class="btn btn-primary w-100">Elegir Plan</a>
                            <small class="text-muted d-block mt-2">Mascota adicional: ₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Suscripciones -->
        <div class="tab-pane fade" id="subscription" role="tabpanel">
            @foreach($subscriptionPlans as $months => $plans)
            <h4 class="mb-3">{{ $months }} {{ Str::plural('mes', $months) }}</h4>
            <div class="row g-4 mb-5">
                @foreach($plans as $plan)
                <div class="col-md-4">
                    <div class="card plan-card h-100">
                        <div class="card-body">
                            <h3 class="card-title">{{ $plan->pets_included }} {{ Str::plural('Mascota', $plan->pets_included) }}</h3>
                            <div class="price">₡{{ number_format($plan->price, 0, ',', '.') }}</div>
                            <p class="text-muted">Cada {{ $months }} {{ Str::plural('mes', $months) }}</p>
                            <ul class="list-unstyled">
                                <li><i class="fa-solid fa-check text-success"></i> {{ $plan->pets_included }} placas QR</li>
                                <li><i class="fa-solid fa-check text-success"></i> Perfil digital</li>
                                <li><i class="fa-solid fa-check text-success"></i> Renovación automática</li>
                            </ul>
                            <a href="{{ route('checkout.show', $plan) }}" class="btn btn-primary w-100">Elegir Plan</a>
                            <small class="text-muted d-block mt-2">Mascota adicional: ₡{{ number_format($plan->additional_pet_price, 0, ',', '.') }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
