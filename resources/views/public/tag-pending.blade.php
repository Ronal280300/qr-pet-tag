@extends('layouts.public')
@section('title', 'TAG pendiente de activación')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="alert alert-warning">
        <strong>TAG pendiente de configuración.</strong> Aún no hay una mascota asociada a este código.
      </div>

      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-2">Información del TAG</h5>
          <p class="mb-1"><strong>Slug:</strong> <code>{{ $qr->slug }}</code></p>
          @if($qr->is_activated)
            <p class="text-success mb-0">Este TAG marca estado <strong>activado</strong>, pero no tiene mascota asociada.</p>
          @else
            <p class="text-muted mb-0">Aún no ha sido activado por su dueño.</p>
          @endif
        </div>
      </div>

      <p class="text-muted mt-3">
        Si encontraste este TAG y no ves la información de la mascota, por favor contáctanos.
      </p>
    </div>
  </div>
</div>
@endsection