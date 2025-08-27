@extends('layouts.app')
@section('title', $pet->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">{{ $pet->name }}</h3>
  <div>
    <a href="{{ route('portal.pets.edit', $pet) }}" class="btn btn-sm btn-outline-primary">Editar</a>
    <form action="{{ route('portal.pets.destroy', $pet) }}" class="d-inline" method="POST" onsubmit="return confirm('¿Eliminar mascota? Esta acción no se puede deshacer.')">
      @csrf @method('DELETE')
      <button class="btn btn-sm btn-outline-danger">Eliminar</button>
    </form>
  </div>
</div>

@if($pet->photo)
  <img src="{{ asset('storage/'.$pet->photo) }}" class="img-fluid mb-3" style="max-height:220px">
@endif

<ul class="list-group mb-3">
  <li class="list-group-item"><strong>Raza:</strong> {{ $pet->breed ?: '—' }}</li>
  <li class="list-group-item"><strong>Zona:</strong> {{ $pet->zone ?: '—' }}</li>
  <li class="list-group-item"><strong>Edad:</strong> {{ $pet->age !== null ? $pet->age.' años' : '—' }}</li>
  <li class="list-group-item"><strong>Condiciones:</strong> {{ $pet->medical_conditions ?: '—' }}</li>
  <li class="list-group-item"><strong>Estado:</strong> @if($pet->is_lost) <span class="badge text-bg-danger">Perdida</span> @else <span class="badge text-bg-success">Normal</span> @endif</li>
</ul>

<div class="mb-3">
  <form method="POST" action="{{ route('portal.pets.toggle-lost', $pet) }}" class="d-inline">
    @csrf
    <button class="btn btn-warning">@if($pet->is_lost) Quitar estado de perdida @else Marcar como perdida/robada @endif</button>
  </form>
</div>

<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">QR de la mascota</h5>
    @if($pet->qrCode)
      <p class="mb-2"><strong>URL:</strong> <a target="_blank" href="{{ $pet->qrCode->qr_code }}">{{ $pet->qrCode->qr_code }}</a></p>
      @if($pet->qrCode->image)
        <img src="{{ asset('storage/'.$pet->qrCode->image) }}" alt="QR" class="img-thumbnail" style="max-width:220px">
      @endif
    @else
      <p class="text-muted">Aún no has generado el QR.</p>
    @endif
    <form class="mt-3" method="POST" action="{{ route('portal.pets.generate-qr', $pet) }}">
      @csrf
      <button class="btn btn-primary">Generar/Regenerar QR</button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Recompensa</h5>
    <form method="POST" action="{{ route('portal.pets.update-reward', $pet) }}">
      @csrf
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Activa</label>
          <select class="form-select" name="active">
            <option value="0" @selected(optional($pet->reward)->active === false)>No</option>
            <option value="1" @selected(optional($pet->reward)->active === true)>Sí</option>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Monto</label>
          <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', optional($pet->reward)->amount) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Mensaje</label>
          <input name="message" class="form-control" value="{{ old('message', optional($pet->reward)->message) }}" placeholder="Gracias por tu ayuda 🙏">
        </div>
      </div>
      <div class="mt-3">
        <button class="btn btn-success">Guardar recompensa</button>
      </div>
    </form>
  </div>
</div>
@endsection