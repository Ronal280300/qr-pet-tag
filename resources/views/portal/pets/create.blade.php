@extends('layouts.app')
@section('title','Nueva Mascota')

@section('content')
<h3>Nueva Mascota</h3>
<form class="mt-3" method="POST" enctype="multipart/form-data" action="{{ route('portal.pets.store') }}">
  @csrf
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nombre *</label>
      <input name="name" class="form-control" required value="{{ old('name') }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">Raza</label>
      <input name="breed" class="form-control" value="{{ old('breed') }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">Zona</label>
      <input name="zone" class="form-control" value="{{ old('zone') }}" placeholder="Curridabat, San Pedro">
    </div>
    <div class="col-md-3">
      <label class="form-label">Edad</label>
      <input type="number" min="0" max="50" name="age" class="form-control" value="{{ old('age') }}">
    </div>
    <div class="col-md-12">
      <label class="form-label">Condiciones médicas</label>
      <textarea name="medical_conditions" class="form-control" rows="3">{{ old('medical_conditions') }}</textarea>
    </div>
    <div class="col-md-6">
      <label class="form-label">Foto</label>
      <input type="file" name="photo" class="form-control">
    </div>
  </div>
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">Guardar</button>
    <a class="btn btn-outline-secondary" href="{{ route('portal.pets.index') }}">Cancelar</a>
  </div>
</form>
@endsection