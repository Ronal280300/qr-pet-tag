@extends('layouts.app')
@section('title', $pet->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">{{ $pet->name }}</h3>
  <div class="d-flex gap-2">
    <a href="{{ route('portal.pets.edit', $pet) }}" class="btn btn-sm btn-outline-primary">Editar</a>
    <form action="{{ route('portal.pets.destroy', $pet) }}" class="d-inline" method="POST" onsubmit="return confirm('¿Eliminar mascota? Esta acción no se puede deshacer.')">
      @csrf @method('DELETE')
      <button class="btn btn-sm btn-outline-danger">Eliminar</button>
    </form>
  </div>
</div>

<div class="row g-4">
  <div class="col-md-5">
    <div class="card">
      <div class="card-body">
        @if($pet->photo)
          <img src="{{ asset('storage/'.$pet->photo) }}" class="img-fluid rounded mb-3" alt="">
        @else
          <div class="rounded bg-light d-flex align-items-center justify-content-center mb-3" style="width:100%;height:220px">🐾</div>
        @endif

        <ul class="list-group list-group-flush list-kv mb-3">
          <li class="list-group-item"><span class="key">Raza</span><span class="val">{{ $pet->breed ?: '—' }}</span></li>
          <li class="list-group-item"><span class="key">Zona</span><span class="val">{{ $pet->zone ?: '—' }}</span></li>
          <li class="list-group-item"><span class="key">Edad</span><span class="val">{{ $pet->age !== null ? $pet->age.' años' : '—' }}</span></li>
          <li class="list-group-item"><span class="key">Condiciones</span><span class="val">{{ $pet->medical_conditions ?: '—' }}</span></li>
          <li class="list-group-item">
            <span class="key">Estado</span>
            <span class="val">
              @if($pet->is_lost) <span class="badge text-bg-danger">Perdida</span> @else <span class="badge text-bg-success">Normal</span> @endif
            </span>
          </li>
        </ul>

        <form method="POST" action="{{ route('portal.pets.toggle-lost', $pet) }}" class="d-grid">
          @csrf
          <button class="btn btn-warning">
            @if($pet->is_lost) Quitar estado de perdida @else Marcar como perdida/robada @endif
          </button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7">
    <div class="card mb-3">
      <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
          <h5 class="card-title mb-0">QR de la mascota</h5>
          @if($pet->qrCode && $pet->qrCode->qr_code)
            <a target="_blank" href="{{ $pet->qrCode->qr_code }}" class="small">Ver perfil público</a>
          @endif
        </div>
        <p class="text-muted mt-2 mb-2">
          @if($pet->qrCode)
            Esta es la URL y la imagen del QR de tu mascota.
          @else
            Aún no has generado el QR.
          @endif
        </p>

        @if($pet->qrCode)
          <p class="mb-2"><strong>URL:</strong> <a target="_blank" href="{{ $pet->qrCode->qr_code }}">{{ $pet->qrCode->qr_code }}</a></p>
          @if($pet->qrCode->image)
            <img src="{{ asset('storage/'.$pet->qrCode->image) }}" alt="QR" class="img-thumbnail qr-image">
          @endif
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

  </div>
</div>
@endsection