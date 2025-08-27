@extends('layouts.app')
@section('title', $pet->name)

@section('content')
<div class="row g-4 align-items-start">
  <div class="col-md-5 text-center">
    @if($pet->photo)
      <img src="{{ asset('storage/'.$pet->photo) }}" class="img-fluid rounded mb-3" alt="Foto de {{ $pet->name }}">
    @else
      <div class="border rounded p-4 text-muted mb-3">Sin foto</div>
    @endif

    @if($pet->is_lost)
      <div class="alert alert-danger text-start">
        <strong>¡Atención!</strong> <br> Esta mascota está reportada como perdida/robada.
      </div>
    @endif

    @if($reward && $reward->active)
      <div class="alert alert-warning text-start">
        <i class="fa fa-gift me-2"></i>
        Recompensa disponible
        @if($reward->amount) de <strong>₡{{ number_format($reward->amount, 2) }}</strong>@endif
        @if($reward->message) — {{ $reward->message }} @endif
      </div>
    @endif
  </div>

  <div class="col-md-7">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <h2 class="mb-0">{{ $pet->name }}</h2>
      <span class="badge {{ $pet->is_lost ? 'text-bg-danger' : 'text-bg-success' }}">
        {{ $pet->is_lost ? 'Perdida' : 'Normal' }}
      </span>
    </div>

    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title">Información</h5>
        <ul class="list-group list-group-flush list-kv">
          <li class="list-group-item">
            <span class="key">Raza</span>
            <span class="val">{{ $pet->breed ?: '—' }}</span>
          </li>
          <li class="list-group-item">
            <span class="key">Zona</span>
            <span class="val">{{ $pet->zone ?: '—' }}</span>
          </li>
          <li class="list-group-item">
            <span class="key">Edad</span>
            <span class="val">{{ $pet->age !== null ? $pet->age.' años' : '—' }}</span>
          </li>
          <li class="list-group-item">
            <span class="key">Condiciones</span>
            <span class="val">{{ $pet->medical_conditions ?: '—' }}</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Contacto</h5>
        <p class="mb-1"><strong>Dueño:</strong> {{ $owner->name }}</p>
        @if($owner->phone)
          <div class="d-flex gap-2 flex-wrap mt-2">
            <a class="btn btn-success" target="_blank" href="https://wa.me/{{ preg_replace('/\D/','',$owner->phone) }}?text=Hola,%20encontr%C3%A9%20a%20{{ urlencode($pet->name) }}">
              <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
            </a>
            <a class="btn btn-outline-primary" href="tel:{{ $owner->phone }}">
              <i class="fa fa-phone me-1"></i> Llamar
            </a>
          </div>
        @else
          <p class="text-muted">El dueño no ha configurado un teléfono.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection