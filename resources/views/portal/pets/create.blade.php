@extends('layouts.app')

@section('title', 'Nueva Mascota')

@section('content')
<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-12 col-lg-10">

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0">Nueva Mascota</h1>
            <a href="{{ route('portal.pets.index') }}" class="btn btn-light">
              <i class="fa-solid fa-arrow-left me-2"></i>Volver
            </a>
          </div>

          {{-- Errores --}}
          @if ($errors->any())
            <div class="alert alert-danger">
              <div class="fw-semibold mb-1">Corrige los siguientes campos:</div>
              <ul class="mb-0">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('portal.pets.store') }}" enctype="multipart/form-data" id="pet-create-form">
            @csrf

            <div class="row g-3">
              <div class="col-12 col-md-6">
                <label class="form-label">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="120" autocomplete="off">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Raza</label>
                <input type="text" name="breed" class="form-control" value="{{ old('breed') }}" maxlength="120" autocomplete="off">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Zona</label>
                <input type="text" name="zone" class="form-control" value="{{ old('zone') }}" maxlength="120" placeholder="Ej. San Juan, Grecia, Alajuela">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label">Edad</label>
                <input type="number" name="age" class="form-control" value="{{ old('age') }}" min="0" max="50" placeholder="Años">
              </div>

              <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                  <label for="medical_conditions" class="form-label mb-1">Condiciones médicas</label>

                  {{-- FIX: check funcional y accesible --}}
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="no-med-check">
                    <label class="form-check-label" for="no-med-check">
                      No tiene condiciones médicas
                    </label>
                  </div>
                </div>

                {{-- Usamos readonly (no disabled) para que el campo se envíe siempre --}}
                <textarea
                  id="medical_conditions"
                  name="medical_conditions"
                  class="form-control"
                  rows="4"
                  placeholder="Alergias, medicación, veterinario, etc."
                >{{ old('medical_conditions') }}</textarea>

                {{-- Ayuda visual cuando está readonly --}}
                <div id="no-med-hint" class="form-text d-none">
                  El campo está bloqueado porque marcaste “No tiene condiciones médicas”.
                </div>
              </div>

              <div class="col-12">
                <label class="form-label">Foto</label>
                <input type="file" name="photo" class="form-control" accept="image/*">
                <div class="form-text">JPG, PNG o WEBP hasta 4MB.</div>
              </div>
            </div>

            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-2"></i>Guardar
              </button>
              <a href="{{ route('portal.pets.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
  /* detalles suaves para inputs */
  .form-control:focus { box-shadow: 0 0 0 .2rem rgba(30,124,242,.15); border-color:#1e7cf2; }
  .card .form-label { font-weight:600; color:#334155; }
  .form-text { color:#6b7280; }
  .is-readonly { background:#f8fafc; }
</style>
@endpush

@push('scripts')
<script>
  // Toggle del check "No tiene condiciones médicas"
  document.addEventListener('DOMContentLoaded', function(){
    const cb = document.getElementById('no-med-check');
    const ta = document.getElementById('medical_conditions');
    const hint = document.getElementById('no-med-hint');

    function sync(){
      if(cb.checked){
        ta.value = '';
        ta.readOnly = true;      // IMPORTANTe: readonly (no disabled) para que se envíe en el form
        ta.classList.add('is-readonly');
        hint.classList.remove('d-none');
      }else{
        ta.readOnly = false;
        ta.classList.remove('is-readonly');
        hint.classList.add('d-none');
      }
    }

    // Si viene vacío por old() puedes decidir arrancar marcado o no:
    // cb.checked = (ta.value.trim() === '');
    sync();

    cb.addEventListener('change', sync);
  });
</script>
@endpush
