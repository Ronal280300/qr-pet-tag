@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
@endpush

@section('content')
<div class="page-wrap py-4">
  <div class="container">
    <div class="hero reveal">
      <h1 class="h4 fw-bold mb-3">Editar mascota</h1>

      <form action="{{ route('portal.pets.update',$pet) }}" method="POST" enctype="multipart/form-data" id="formEditPet">
        @csrf @method('PUT')

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nombre *</label>
            <input type="text" class="form-control" name="name" value="{{ old('name',$pet->name) }}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Raza</label>
            <input type="text" class="form-control" name="breed" value="{{ old('breed',$pet->breed) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Zona</label>
            <input type="text" class="form-control" name="zone" value="{{ old('zone',$pet->zone) }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Edad (años)</label>
            <input type="number" class="form-control" name="age" value="{{ old('age',$pet->age) }}" min="0" max="50">
          </div>
          <div class="col-12">
            <div class="form-check mb-1">
              <input class="form-check-input" type="checkbox" id="chkNoMed" {{ empty($pet->medical_conditions) ? 'checked':'' }}>
              <label class="form-check-label" for="chkNoMed">No tiene condiciones médicas</label>
            </div>
            <label class="form-label">Condiciones médicas</label>
            <textarea class="form-control" rows="3" name="medical_conditions" id="txtMed">{{ old('medical_conditions',$pet->medical_conditions) }}</textarea>
            <input type="hidden" name="medical_conditions" id="txtMedHidden" value="{{ old('medical_conditions',$pet->medical_conditions) }}">
          </div>
          <div class="col-12">
            <label class="form-label">Foto</label>
            <input type="file" class="form-control" name="photo" accept="image/*">
          </div>
        </div>

        <div class="mt-3 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Guardar</button>
          <a href="{{ route('portal.pets.show',$pet) }}" class="btn btn-soft">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  (function(){ const el = document.querySelector('.reveal'); el && el.classList.add('in'); })();

  (function(){
    const chk = document.getElementById('chkNoMed');
    const txt = document.getElementById('txtMed');
    const hid = document.getElementById('txtMedHidden');
    function sync(){
      const off = chk.checked;
      txt.disabled = off;
      if(off){ hid.value = ''; } else { hid.value = txt.value; }
    }
    chk.addEventListener('change', sync);
    txt.addEventListener('input', ()=>{ if(!chk.checked) hid.value = txt.value; });
    sync();
  })();
</script>
@endpush
@endsection
