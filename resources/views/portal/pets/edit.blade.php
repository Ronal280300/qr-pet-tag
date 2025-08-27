@extends('layouts.app')
@section('title','Editar Mascota')

@section('content')
<h3>Editar Mascota</h3>
<form class="mt-3" method="POST" enctype="multipart/form-data" action="{{ route('portal.pets.update', $pet) }}">
  @csrf @method('PUT')
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nombre *</label>
      <input name="name" class="form-control" required value="{{ old('name', $pet->name) }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">Raza</label>
      <input name="breed" class="form-control" value="{{ old('breed', $pet->breed) }}">
    </div>
    <div class="col-md-6">
      <label class="form-label">Zona</label>
      <input name="zone" class="form-control" value="{{ old('zone', $pet->zone) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Edad</label>
      <input type="number" min="0" max="50" name="age" class="form-control" value="{{ old('age', $pet->age) }}">
    </div>
    <div class="col-md-12">
      <label class="form-label">Condiciones médicas</label>
      <textarea name="medical_conditions" class="form-control">{{ old('medical_conditions', $pet->medical_conditions) }}</textarea>
    </div>
    <div class="col-md-6">
      <label class="form-label">Foto</label>
      <input type="file" name="photo" class="form-control">
      @if($pet->photo)
        <img src="{{ asset('storage/'.$pet->photo) }}" class="img-fluid mt-2" style="max-height:160px">
      @endif
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">Guardar cambios</button>
    <a class="btn btn-outline-secondary" href="{{ route('portal.pets.show', $pet) }}">Volver</a>
  </div>
</form>
@endsection