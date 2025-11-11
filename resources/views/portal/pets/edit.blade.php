{{-- resources/views/portal/pets/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Mascota')

@section('content')
<div class="container my-4 edit-wrap">
  <div class="page-head d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
    <div class="d-flex align-items-center gap-2">
      <div class="head-icon"><i class="fa-solid fa-paw"></i></div>
      <div>
        <h1 class="h4 fw-black mb-0">Editar Mascota</h1>
        <div class="text-muted small">Actualiza la información y las fotos de tu mascota.</div>
      </div>
    </div>
  </div>

  @php
  // zone viene como "Distrito, Cantón, Provincia"
  $zDistrict = $zCanton = $zProvince = null;
  if ($pet->zone){
  $parts = array_map('trim', explode(',', $pet->zone));
  if (count($parts) === 3){ [$zDistrict, $zCanton, $zProvince] = $parts; }
  }
  $existingPhotos = $pet->photos ?? collect();
  $hasMain = !empty($pet->photo);
  @endphp

  <form action="{{ route('portal.pets.update', $pet) }}" method="POST" enctype="multipart/form-data" id="pet-form">
    @csrf @method('PUT')

    <div class="row g-4">
      {{-- =================== Información básica =================== --}}
      <div class="col-12 col-xl-6">
        <div class="sheet">
          <div class="section-title"><i class="fa-regular fa-rectangle-list me-2"></i> Información básica</div>

          <div class="row g-3">
            <div class="col-12 col-lg-6">
              <label class="form-label">Nombre <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control modern" value="{{ $pet->name }}" required>
            </div>
            <div class="col-12 col-lg-6">
              <label class="form-label">Raza</label>
              <input type="text" name="breed" class="form-control modern" value="{{ $pet->breed }}">
            </div>

            {{-- Sexo (segmented) --}}
            <div class="col-12">
              <label class="form-label d-block mb-2">Sexo</label>
              @php $sex = $pet->sex ?? 'unknown'; @endphp
              <div class="segmented">
                <input type="radio" id="sex_m" name="sex" value="male" class="seg" {{ $sex==='male' ? 'checked' : '' }}>
                <label for="sex_m"><i class="fa-solid fa-mars me-1"></i> Macho</label>

                <input type="radio" id="sex_f" name="sex" value="female" class="seg" {{ $sex==='female' ? 'checked' : '' }}>
                <label for="sex_f"><i class="fa-solid fa-venus me-1"></i> Hembra</label>

                <input type="radio" id="sex_u" name="sex" value="unknown" class="seg" {{ $sex==='unknown' ? 'checked' : '' }}>
                <label for="sex_u"><i class="fa-solid fa-circle-question me-1"></i> Desconocido</label>
              </div>
            </div>

            {{-- Salud: switches + edad --}}
            {{-- Esterilizado (toggle) --}}
            <div class="col-12 col-lg-6">
              <label class="form-label d-block">Esterilizado</label>

              {{-- fallback para unchecked --}}
              <input type="hidden" name="is_neutered" value="0">

              <label class="switch" aria-label="Esterilizado">
                <input
                  type="checkbox"
                  name="is_neutered"
                  value="1"
                  {{ old('is_neutered', (int)$pet->is_neutered) ? 'checked' : '' }}>
                <span class="slider" aria-hidden="true"></span>
                <span class="state" data-off="No" data-on="Sí"></span>
              </label>

              <div class="form-text">Indica si tu mascota está esterilizada/castrada.</div>
            </div>

            {{-- Vacuna antirrábica (toggle) --}}
            <div class="col-12 col-lg-6">
              <label class="form-label d-block">Vacuna antirrábica</label>

              {{-- fallback para unchecked --}}
              <input type="hidden" name="rabies_vaccine" value="0">

              <label class="switch" aria-label="Vacuna antirrábica al día">
                <input
                  type="checkbox"
                  name="rabies_vaccine"
                  value="1"
                  {{ old('rabies_vaccine', (int)$pet->rabies_vaccine) ? 'checked' : '' }}>
                <span class="slider" aria-hidden="true"></span>
                <span class="state" data-off="No" data-on="Sí"></span>
              </label>

              <div class="form-text">Marca “Sí” si la vacuna está vigente.</div>
            </div>



            <div class="col-12 col-lg-4">
              <label class="form-label">Edad</label>
              <div class="input-icon">
                <i class="fa-solid fa-cake-candles"></i>
                <input type="number" name="age" min="0" max="50" class="form-control modern" value="{{ $pet->age }}">
              </div>
            </div>

            {{-- Ubicación CR --}}
            <div class="col-12">
              <label class="form-label">Ubicación</label>
              <div class="row g-2" id="cr-geo"
                data-current-province="{{ $zProvince }}"
                data-current-canton="{{ $zCanton }}"
                data-current-district="{{ $zDistrict }}">
                <div class="col-md-4">
                  <div class="input-icon">
                    <i class="fa-solid fa-map"></i>
                    <select id="cr-province" class="form-select modern" aria-label="Provincia" disabled>
                      <option value="">Provincia</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-icon">
                    <i class="fa-solid fa-map"></i>
                    <select id="cr-canton" class="form-select modern" aria-label="Cantón" disabled>
                      <option value="">Cantón</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="input-icon">
                    <i class="fa-solid fa-map"></i>
                    <select id="cr-district" class="form-select modern" aria-label="Distrito" disabled>
                      <option value="">Distrito</option>
                    </select>
                  </div>
                </div>
              </div>
              <input type="hidden" name="zone" id="zone" value="{{ $pet->zone }}">
              <div class="form-text">Se guardará como: <code id="zone-preview">{{ $pet->zone ?: '—' }}</code></div>
            </div>

            {{-- Observaciones --}}
            <div class="col-12">
              <div class="form-row mb-1">
                <label class="mb-0" for="no-medical">Sin observaciones</label>
                @php $noMed = empty($pet->medical_conditions); @endphp
                <label class="ft-switch" aria-label="Sin observaciones">
                  <input id="no-medical" type="checkbox" {{ $noMed ? 'checked' : '' }}>
                  <span class="track"><span class="thumb"></span></span>
                </label>
              </div>
              <textarea name="medical_conditions" id="medical_conditions" rows="4" class="form-control modern"
                placeholder="Alergias, medicación, etc." {{ $noMed ? 'disabled' : '' }}>{{ $pet->medical_conditions }}</textarea>
            </div>

            {{-- Contacto de Emergencia --}}
            <div class="col-12 mt-4">
              <label class="form-label fw-bold">
                <i class="fa-solid fa-phone-volume me-2"></i>
                Contacto de Emergencia (Opcional)
              </label>
              <div class="form-text mb-3">Si el dueño no responde, este contacto aparecerá como alternativa en el perfil público.</div>
              
              <div class="form-row mb-3">
                <label class="mb-0" for="has_emergency_contact">Habilitar contacto de emergencia</label>
                @php $hasEmergency = (bool)($pet->has_emergency_contact ?? false); @endphp
                <input type="hidden" name="has_emergency_contact" value="0">
                <label class="ft-switch" aria-label="Habilitar contacto de emergencia">
                  <input id="has_emergency_contact" name="has_emergency_contact" type="checkbox" value="1" 
                         {{ $hasEmergency ? 'checked' : '' }} onchange="toggleEmergencyFieldsEdit()">
                  <span class="track"><span class="thumb"></span></span>
                </label>
              </div>

              <div id="emergency-fields-edit" class="row g-3" style="display: {{ $hasEmergency ? 'flex' : 'none' }};">
                <div class="col-12 col-md-6">
                  <label class="form-label">Nombre del contacto</label>
                  <div class="input-icon">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="emergency_contact_name" class="form-control modern" 
                           value="{{ old('emergency_contact_name', $pet->emergency_contact_name ?? '') }}"
                           placeholder="Ej: María González">
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <label class="form-label">Teléfono del contacto</label>
                  <div class="input-icon">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <input type="text" name="emergency_contact_phone" class="form-control modern" 
                           value="{{ old('emergency_contact_phone', $pet->emergency_contact_phone ?? '') }}"
                           placeholder="Ej: +506 8765-4321">
                  </div>
                </div>

                <div class="col-12">
                  <div class="alert alert-info small mb-0">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    <strong>Nota:</strong> Este contacto se mostrará como "Contacto de Emergencia" en el perfil público.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- =================== Fotos =================== --}}
      <div class="col-12 col-xl-6">
        <div class="sheet">
          <div class="section-title d-flex align-items-center justify-content-between">
            <div><i class="fa-regular fa-images me-2"></i> Fotos</div>
            <span class="badge bg-light text-dark fw-semibold">máx. 3 adicionales</span>
          </div>

          {{-- Foto principal (obligatoria) --}}
          <div class="mb-3">
            <label class="form-label">Foto principal <span class="text-danger">*</span></label>
            <div class="d-flex align-items-start gap-3 flex-wrap">
              <div class="photo-main-preview" id="mainPreview">
                @if($hasMain)
                <img id="photoPreview" src="{{ asset('storage/'.$pet->photo) }}" alt="Foto principal">
                @else
                <img id="photoPreview" src="" alt="Foto principal" class="d-none">
                <div id="mainPlaceholder" class="placeholder small text-muted">Sin foto principal</div>
                @endif
              </div>

              <div class="d-flex flex-column gap-2">
                <label for="photo" class="btn btn-outline-primary btn-sm w-auto">
                  <i class="fa-solid fa-image me-1"></i> Seleccionar imagen
                </label>
                <input id="photo" name="photo" type="file" accept="image/*" class="d-none">
                <button type="button" id="btnClearPhoto" class="btn btn-outline-danger btn-sm w-auto">
                  <i class="fa-solid fa-xmark me-1"></i> Quitar
                </button>
                <div class="form-text">JPG/PNG. Máx 4 MB. Relación 4:3 recomendada.</div>
              </div>
            </div>
          </div>

          {{-- Fotos adicionales (máx 3) --}}
          <div class="mb-2">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <label class="form-label mb-0">Fotos adicionales</label>
              <div class="small text-muted">Puedes eliminar o agregar; el total no puede exceder 3.</div>
            </div>

            {{-- existentes --}}
            <div id="existingGrid" class="d-flex flex-wrap gap-3 mb-3">
              @foreach(($pet->photos ?? collect()) as $ph)
              <div class="existing-card position-relative" data-id="{{ $ph->id }}">
                <img src="{{ Storage::url($ph->path) }}" alt="Foto adicional">
                <button type="button" class="btn-remove-photo" title="Eliminar" data-photo-id="{{ $ph->id }}">
                  <i class="fa-solid fa-xmark"></i>
                </button>
              </div>
              @endforeach
            </div>
            <input type="hidden" name="delete_photos" id="deletePhotos" value="">

            {{-- input + droparea --}}
            <div class="dropzone-modern mb-2" id="dz-photos">
              <div class="dz-cta">
                <i class="fa-regular fa-images"></i>
                <div class="dz-title">Arrastra aquí tus fotos</div>
                <div class="dz-sub">o haz clic para seleccionarlas</div>
              </div>
              <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
            </div>
            <div class="form-text">JPG/PNG. Máx 6 MB c/u.</div>

            <div id="photosPreviewGrid" class="mt-3 photos-grid d-none"></div>
            <div class="d-flex justify-content-between mt-2">
              <button type="button" id="btnClearPhotos" class="btn btn-outline-danger btn-sm d-none">
                <i class="fa-solid fa-xmark me-1"></i> Quitar todas (nuevas)
              </button>
              <div class="small text-muted ms-auto" id="photosCounter">0 / 3</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Barra de acciones fija --}}
    <div class="action-bar">
      <div class="container px-0 d-flex gap-2 justify-content-end">
        <a href="{{ route('portal.pets.show', $pet) }}" class="btn btn-outline-secondary">Cancelar</a>
        <button class="btn btn-primary" id="btnSubmit">
          <i class="fa-solid fa-floppy-disk me-1"></i> Guardar
        </button>
      </div>
    </div>
  </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pet-form-edit.css') }}">
@endpush


@push('scripts')
<script src="{{ asset('js/pet-form-edit.js') }}"></script>
@endpush
