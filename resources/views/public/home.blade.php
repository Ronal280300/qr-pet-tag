@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="text-center my-5">
    <h1 class="display-5">Placas con <span class="text-primary">QR</span> para tu mascota</h1>
    <p class="lead mt-3">
        Si alguien escanea el QR del collar, verá un perfil con foto, nombre y botón
        de contacto por WhatsApp. Opcionalmente, puedes activar recompensa.
    </p>
    <div class="mt-4">
        @guest
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">
                <i class="fa-solid fa-id-badge me-2"></i>Crear mi cuenta
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                Ya tengo cuenta
            </a>
        @else
            <a href="{{ route('portal.dashboard') }}" class="btn btn-primary btn-lg">
                Ir a mi portal
            </a>
        @endguest
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-qrcode me-2"></i>QR único</h5>
                <p class="card-text">Cada mascota tiene una URL única y editable para actualizar tus datos cuando quieras.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-brands fa-whatsapp me-2"></i>Contacto rápido</h5>
                <p class="card-text">Quien encuentre a tu mascota puede escribirte de inmediato por WhatsApp o llamarte.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-gift me-2"></i>Recompensa opcional</h5>
                <p class="card-text">Actívala desde tu portal si tu mascota está perdida para incentivar el reporte.</p>
            </div>
        </div>
    </div>
</div>
@endsection