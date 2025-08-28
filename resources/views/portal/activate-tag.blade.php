@extends('layouts.app')
@section('title','Activar un TAG')

@section('content')
<h3 class="mb-2">Activar un TAG</h3>
<p class="text-muted">Ingresa el <strong>código de activación</strong> que recibiste con tu placa. Al activarlo, la mascota quedará ligada a tu cuenta y podrás editar su información.</p>

<form class="mt-3" method="POST" action="{{ route('portal.activate-tag.store') }}">
  @csrf

  <div class="mb-3" style="max-width:460px">
    <label class="form-label">Código de activación *</label>
    <input name="activation_code" class="form-control" required value="{{ old('activation_code') }}" placeholder="Ej: ABC123-XYZ">
    @error('activation_code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>

  <div class="d-flex gap-2">
    <button class="btn btn-primary">Activar TAG</button>
    <a class="btn btn-outline-secondary" href="{{ route('portal.pets.index') }}">Cancelar</a>
  </div>
</form>

<div class="alert alert-info mt-4" style="max-width:680px">
  <i class="fa-solid fa-circle-info me-2"></i>
  Si no encuentras tu código de activación, contáctanos para reemitirlo.
</div>
@endsection