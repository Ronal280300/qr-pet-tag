@extends('layouts.app')
@section('title', $pet->name)

@section('content')
<div class="text-center mb-4">
  <h2>{{ $pet->name }}</h2>
  @if($pet->is_lost)
    <div class="alert alert-danger"><strong>¡Atención!</strong> Esta mascota está reportada como perdida/robada.</div>
  @endif
</div>

<div class="row g-4">
  <div class="col-md-4 text-center">
    @if($pet->photo)
      <img src="{{ asset('storage/'.$pet->photo) }}" class="img-fluid rounded" alt="Foto de {{ $pet->name }}">
    @else
      <div class="border rounded p-4 text-muted">Sin foto</div>
    @endif
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Información</h5>
        <p class="mb-2"><strong>Nombre:</strong> {{ $pet->name }}</p>
        <p class="mb-2"><strong>Raza:</strong> {{ $pet->breed ?: '—' }}</p>
        <p class="mb-2"><strong>Zona:</strong> {{ $pet->zone ?: '—' }}</p>
        <p class="mb-2"><strong>Edad:</strong> {{ $pet->age !== null ? $pet->age.' años' : '—' }}</p>

        <hr>

        <h5 class="card-title">Contacto</h5>
        <p class="mb-2"><strong>Dueño:</strong> {{ $owner->name }}</p>
        @if($owner->phone)
          <p class="mb-3">
            <a class="btn btn-success me-2" target="_blank" href="https://wa.me/{{ preg_replace('/\D/','',$owner->phone) }}?text=Hola,%20encontr%C3%A9%20a%20{{ urlencode($pet->name) }}">
              <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
            </a>
            <a class="btn btn-outline-primary" href="tel:{{ $owner->phone }}"><i class="fa fa-phone me-1"></i> Llamar</a>
          </p>
        @else
          <p class="text-muted">El dueño no ha configurado un teléfono.</p>
        @endif

        @if($reward && $reward->active)
          <div class="alert alert-warning mt-3">
            <i class="fa fa-gift me-2"></i>
            Recompensa disponible
            @if($reward->amount) de <strong>₡{{ number_format($reward->amount, 2) }}</strong>@endif
            @if($reward->message) — {{ $reward->message }} @endif
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection