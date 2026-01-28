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



            <div class="col-12 col-lg-8">
              <label class="form-label">Edad</label>
              <div class="age-inputs-dual">
                <div class="age-field">
                  <label class="age-sublabel">Años</label>
                  <div class="input-with-icon">
                    <i class="fa-solid fa-calendar-days input-icon"></i>
                    <input type="number" name="age_years" min="0" max="50"
                           class="form-input" placeholder="0" id="ageYearsInputEdit"
                           value="{{ old('age_years', $pet->age_years ?? 0) }}">
                  </div>
                </div>
                <div class="age-field">
                  <label class="age-sublabel">Meses</label>
                  <div class="input-with-icon">
                    <i class="fa-solid fa-calendar-alt input-icon"></i>
                    <input type="number" name="age_months" min="0" max="11"
                           class="form-input" placeholder="0" id="ageMonthsInputEdit"
                           value="{{ old('age_months', $pet->age_months ?? 0) }}">
                  </div>
                </div>
              </div>
              <small class="text-muted mt-1 d-block">
                <i class="fa-solid fa-info-circle me-1"></i>
                Puedes ingresar años, meses o ambos (Ej: 1 año y 6 meses)
              </small>
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

  {{-- Pantalla de carga --}}
  <div id="loading-overlay">
    <div class="loading-spinner"></div>
    <div class="loading-text">Procesando fotos...</div>
    <div class="loading-subtext">Estamos optimizando las imágenes para que carguen más rápido. Esto tomará unos segundos.</div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
  .fw-black {
    font-weight: 900
  }

  .head-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: #eef2ff;
    display: grid;
    place-items: center;
    color: #4338ca
  }

  .edit-wrap {
    max-width: 1200px
  }

  .sheet {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #eef1f5;
    box-shadow: 0 14px 42px rgba(31, 41, 55, .06);
    padding: 18px 16px
  }

  .section-title {
    font-weight: 800;
    margin-bottom: 10px
  }

  .form-control.modern,
  .form-select.modern {
    height: 46px;
    border-radius: 12px;
    border: 1px solid #e5e7eb
  }

  .form-control.modern:focus,
  .form-select.modern:focus {
    border-color: #c7d2fe;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, .16)
  }

  textarea.form-control.modern {
    min-height: 120px
  }

  /* label + toggle */
  .form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 8px 0
  }

  /* Switch responsive */
  .ft-switch {
    position: relative;
    display: inline-flex;
    width: 52px;
    height: 30px;
    flex: 0 0 auto;
    cursor: pointer
  }

  .ft-switch input {
    position: absolute;
    inset: 0;
    opacity: 0;
    margin: 0;
    cursor: pointer
  }

  .ft-switch .track {
    position: relative;
    width: 100%;
    height: 100%;
    background: #e5e7eb;
    border-radius: 999px;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, .12);
    transition: background .2s
  }

  .ft-switch .thumb {
    position: absolute;
    top: 50%;
    left: 3px;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
    transition: left .2s
  }

  .ft-switch input:checked+.track {
    background: #2563eb
  }

  .ft-switch input:checked+.track .thumb {
    left: calc(100% - 27px)
  }

  @media (max-width:480px) {
    .ft-switch {
      width: 46px;
      height: 26px
    }

    .ft-switch .thumb {
      width: 20px;
      height: 20px;
      left: 3px
    }

    .ft-switch input:checked+.track .thumb {
      left: calc(100% - 23px)
    }
  }

  /* Segmented control */
  .segmented {
    display: inline-grid;
    grid-auto-flow: column;
    gap: 6px;
    background: #f6f7fb;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid #eef1f5
  }

  .segmented .seg {
    display: none
  }

  .segmented label {
    padding: .45rem .8rem;
    border-radius: 10px;
    cursor: pointer;
    color: #374151;
    background: transparent
  }

  .segmented .seg:checked+label {
    background: #115DFC;
    color: #fff;
    font-weight: 700
  }

  /* Inputs con icono */
  .input-icon {
    position: relative
  }

  .input-icon>i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aa0aa
  }

  .input-icon>.form-control,
  .input-icon>.form-select {
    padding-left: 40px
  }

  /* Foto principal */
  .photo-main-preview {
    width: 300px;
    aspect-ratio: 4/3;
    border: 1px dashed #e5e7eb;
    border-radius: 14px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden
  }

  .photo-main-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover
  }

  .placeholder {
    text-align: center
  }

  /* existentes */
  .existing-card {
    width: 180px;
    height: 120px;
    border-radius: .6rem;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    background: #f8fafc
  }

  .existing-card img {
    width: 100%;
    height: 100%;
    object-fit: cover
  }

  .btn-remove-photo {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 26px;
    height: 26px;
    border: none;
    border-radius: 50%;
    background: rgba(220, 53, 69, .95);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer
  }

  /* dropzone */
  .dropzone-modern {
    position: relative;
    border: 1px dashed #d1d5db;
    border-radius: 14px;
    background: #f9fafb;
    min-height: 110px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .15s, border-color .15s
  }

  .dropzone-modern input[type=file] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer
  }

  .dropzone-modern .dz-cta {
    text-align: center;
    color: #6b7280
  }

  .dropzone-modern .dz-cta i {
    font-size: 26px;
    display: block;
    margin-bottom: 6px;
    color: #64748b
  }

  .dropzone-modern .dz-title {
    font-weight: 800
  }

  .dropzone-modern.hover {
    background: #eef2ff;
    border-color: #c7d2fe
  }

  /* previews nuevas */
  .photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: .75rem
  }

  .photos-grid .ph {
    position: relative;
    border: 1px solid #e5e7eb;
    border-radius: .6rem;
    overflow: hidden;
    background: #f8fafc;
    aspect-ratio: 1/1;
    display: flex;
    align-items: center;
    justify-content: center
  }

  .photos-grid .ph img {
    width: 100%;
    height: 100%;
    object-fit: cover
  }

  .photos-grid .ph .ph-remove {
    position: absolute;
    top: .35rem;
    right: .35rem;
    border: 0;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, .55);
    color: #fff;
    cursor: pointer
  }

  /* barra de acciones */
  .action-bar {
    position: sticky;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 12px 0;
    background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, #fff 40%);
    border-top: 1px solid #eef1f5;
    margin-top: 18px
  }

  /* ==== Pantalla de carga ==== */
  #loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 20px;
  }

  #loading-overlay.active {
    display: flex;
  }

  .loading-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #e5e7eb;
    border-top-color: #115DFC;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  .loading-text {
    font-size: 1.1rem;
    font-weight: 700;
    color: #115DFC;
    margin-top: 10px;
  }

  .loading-subtext {
    font-size: 0.9rem;
    color: #6b7280;
    max-width: 300px;
    text-align: center;
  }


  /* ==== Toggle switch UI (accesible) ==== */
  .toggle-field {
    display: flex;
    align-items: center;
    gap: .75rem;
  }

  .switch {
    display: inline-flex;
    align-items: center;
    gap: .65rem;
    cursor: pointer;
    user-select: none;
  }

  .switch input {
    position: absolute;
    opacity: 0;
    width: 1px;
    height: 1px;
  }

  .switch .slider {
    position: relative;
    width: 52px;
    height: 30px;
    background: #e5e7eb;
    border-radius: 999px;
    transition: background .2s ease, box-shadow .2s ease;
    box-shadow: inset 0 0 0 1px #d1d5db;
  }

  .switch .slider::after {
    content: "";
    position: absolute;
    top: 3px;
    left: 3px;
    width: 24px;
    height: 24px;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .2);
    transition: transform .2s ease;
  }

  .switch input:checked+.slider {
    background: #115DFC;
    box-shadow: inset 0 0 0 1px rgba(17, 93, 252, .55);
  }

  .switch input:checked+.slider::after {
    transform: translateX(22px);
  }

  /* Enfoque accesible */
  .switch input:focus-visible+.slider {
    outline: 3px solid rgba(17, 93, 252, .35);
    outline-offset: 2px;
  }

  /* Etiqueta de estado (No/Sí) */
  .switch .state {
    font-weight: 700;
    font-size: .95rem;
    color: #6b7280;
    min-width: 2ch;
  }

  .switch .state::before {
    content: attr(data-off);
  }

  .switch input:checked~.state {
    color: #115DFC;
  }

  .switch input:checked~.state::before {
    content: attr(data-on);
  }

  /* Age Dual Fields */
  .age-inputs-dual {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  .age-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .age-sublabel {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .input-with-icon {
    position: relative;
  }

  .input-with-icon .input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 16px;
    pointer-events: none;
  }

  .input-with-icon .form-input {
    width: 100%;
    height: 46px;
    padding-left: 40px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.2s;
  }

  .input-with-icon .form-input:focus {
    outline: none;
    border-color: #115DFC;
    box-shadow: 0 0 0 3px rgba(17, 93, 252, 0.1);
  }

  @media (max-width: 576px) {
    .age-inputs-dual {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  /** ======= Ubicación CR (con fallback) + Observaciones toggle ======= */
  (() => {
    const API = 'https://ubicaciones.paginasweb.cr';
    const $prov = document.getElementById('cr-province');
    const $cant = document.getElementById('cr-canton');
    const $dist = document.getElementById('cr-district');
    const $zone = document.getElementById('zone');
    const $zonePreview = document.getElementById('zone-preview');

    const host = document.getElementById('cr-geo');
    const pNameInit = host?.dataset?.currentProvince || '';
    const cNameInit = host?.dataset?.currentCanton || '';
    const dNameInit = host?.dataset?.currentDistrict || '';

    const $noMedical = document.getElementById('no-medical');
    const $medical = document.getElementById('medical_conditions');

    function toggleMedical() {
      if ($noMedical.checked) {
        $medical.value = '';
        $medical.setAttribute('disabled', 'disabled');
      } else {
        $medical.removeAttribute('disabled');
      }
    }
    $noMedical.addEventListener('change', toggleMedical);

    async function getJSON(path) {
      const r = await fetch(`${API}${path}`);
      if (!r.ok) throw 0;
      return await r.json();
    }

    function fillSelect($sel, map, placeholder, selectedByName = null) {
      $sel.innerHTML = `<option value="">${placeholder}</option>`;
      let selectedValue = '';
      for (const [id, name] of Object.entries(map)) {
        const opt = document.createElement('option');
        opt.value = id;
        opt.textContent = name;
        if (selectedByName && name.toLowerCase() === selectedByName.toLowerCase()) selectedValue = id;
        $sel.appendChild(opt);
      }
      if (selectedValue) $sel.value = selectedValue;
    }

    function setZone() {
      const pName = $prov.options[$prov.selectedIndex]?.text || '';
      const cName = $cant.options[$cant.selectedIndex]?.text || '';
      const dName = $dist.options[$dist.selectedIndex]?.text || '';
      if (pName && cName && dName) {
        const z = `${dName}, ${cName}, ${pName}`;
        $zone.value = z;
        $zonePreview.textContent = z;
      } else {
        $zone.value = '';
        $zonePreview.textContent = '—';
      }
    }

    (async () => {
      try {
        const provincias = await getJSON('/provincias.json');
        fillSelect($prov, provincias, 'Provincia', pNameInit);
        $prov.disabled = false;
        if ($prov.value) {
          const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
          fillSelect($cant, cantones, 'Cantón', cNameInit);
          $cant.disabled = false;
        }
        if ($prov.value && $cant.value) {
          const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
          fillSelect($dist, distritos, 'Distrito', dNameInit);
          $dist.disabled = false;
        }
        setZone();
        toggleMedical();
      } catch (e) {
        const wrap = $prov.closest('.row');
        wrap.outerHTML = `
        <div class="col-12">
          <div class="alert alert-warning small mb-2">No se pudo cargar la lista de ubicaciones. Ingresa manualmente la zona.</div>
          <input class="form-control modern" value="{{ $pet->zone }}" placeholder="Ej: San Juan, Grecia, Alajuela"
                 oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
        </div>`;
      }
    })();

    $prov.addEventListener('change', async () => {
      $cant.disabled = true;
      $dist.disabled = true;
      $cant.innerHTML = `<option value="">Cantón</option>`;
      $dist.innerHTML = `<option value="">Distrito</option>`;
      $zone.value = '';
      $zonePreview.textContent = '—';
      if (!$prov.value) return;
      const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
      fillSelect($cant, cantones, 'Cantón');
      $cant.disabled = false;
    });
    $cant.addEventListener('change', async () => {
      $dist.disabled = true;
      $dist.innerHTML = `<option value="">Distrito</option>`;
      $zone.value = '';
      $zonePreview.textContent = '—';
      if (!$prov.value || !$cant.value) return;
      const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
      fillSelect($dist, distritos, 'Distrito');
      $dist.disabled = false;
    });
    $dist.addEventListener('change', setZone);
  })();
</script>

<script>
  /** ================= Foto principal ================= */
  (function() {
    const input = document.getElementById('photo');
    const preview = document.getElementById('photoPreview');
    const ph = document.getElementById('mainPlaceholder');
    const clear = document.getElementById('btnClearPhoto');

    function show(file) {
      if (!file) return;
      const url = URL.createObjectURL(file);
      preview.src = url;
      preview.classList.remove('d-none');
      ph && (ph.style.display = 'none');
    }
    input.addEventListener('change', e => show(e.target.files[0]));
    clear.addEventListener('click', () => {
      preview.src = '';
      preview.classList.add('d-none');
      input.value = '';
      if (ph) {
        ph.style.display = 'block';
        ph.textContent = 'Sin foto principal';
      }
    });
  })();
</script>

<script>
  /** ================= Fotos adicionales (máx 3) ================= */
  (function() {
    const MAX = 3;

    const existingGrid = document.getElementById('existingGrid');
    const deleteInput = document.getElementById('deletePhotos');
    const input = document.getElementById('photos');
    const grid = document.getElementById('photosPreviewGrid');
    const btnClear = document.getElementById('btnClearPhotos');
    const counter = document.getElementById('photosCounter');
    const dropzone = document.getElementById('dz-photos');

    function existingAliveCount() {
      return existingGrid.querySelectorAll('.existing-card').length;
    }

    const removed = new Set();

    function syncDeleteInput() {
      deleteInput.value = Array.from(removed).join(',');
    }

    existingGrid.addEventListener('click', async (e) => {
      const btn = e.target.closest('.btn-remove-photo');
      if (!btn) return;
      const id = btn.dataset.photoId;
      if (!id) return;
      const res = await Swal.fire({
        icon: 'warning',
        title: 'Eliminar foto',
        text: '¿Quieres eliminar esta foto?',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      });
      if (!res.isConfirmed) return;
      removed.add(id);
      syncDeleteInput();
      btn.closest('.existing-card')?.remove();
      refreshCounter();
    });

    let filesBuffer = [];

    function refreshNewGrid() {
      grid.innerHTML = '';
      if (filesBuffer.length === 0) {
        grid.classList.add('d-none');
        btnClear.classList.add('d-none');
        return;
      }
      grid.classList.remove('d-none');
      btnClear.classList.remove('d-none');
      filesBuffer.forEach((file, idx) => {
        const url = URL.createObjectURL(file);
        const cell = document.createElement('div');
        cell.className = 'ph';
        const img = document.createElement('img');
        img.src = url;
        img.alt = `Foto ${idx+1}`;
        const rm = document.createElement('button');
        rm.type = 'button';
        rm.className = 'ph-remove';
        rm.innerHTML = '<i class="fa-solid fa-xmark"></i>';
        rm.addEventListener('click', () => removeAt(idx));
        cell.appendChild(img);
        cell.appendChild(rm);
        grid.appendChild(cell);
      });
    }

    function applyBufferToInput() {
      const dt = new DataTransfer();
      filesBuffer.forEach(f => dt.items.add(f));
      input.files = dt.files;
    }

    function removeAt(i) {
      filesBuffer.splice(i, 1);
      applyBufferToInput();
      refreshNewGrid();
      refreshCounter();
    }

    function refreshCounter() {
      counter.textContent = `${existingAliveCount() + filesBuffer.length} / ${MAX}`;
    }

    input.addEventListener('change', (e) => {
      const incoming = Array.from(e.target.files || []);
      const totalIfAdded = existingAliveCount() + filesBuffer.length + incoming.length;
      if (totalIfAdded > MAX) {
        const allowed = Math.max(0, MAX - existingAliveCount() - filesBuffer.length);
        Swal.fire({
          icon: 'warning',
          title: 'Máximo 3 fotos adicionales',
          text: `Puedes añadir ${allowed} foto(s) más.`,
          confirmButtonText: 'Entendido'
        });
        if (allowed > 0) filesBuffer = filesBuffer.concat(incoming.slice(0, allowed));
      } else {
        filesBuffer = filesBuffer.concat(incoming);
      }
      applyBufferToInput();
      refreshNewGrid();
      refreshCounter();
    });

    ;
    ['dragenter', 'dragover'].forEach(evt => dropzone.addEventListener(evt, e => {
      e.preventDefault();
      dropzone.classList.add('hover');
    }));;
    ['dragleave', 'drop'].forEach(evt => dropzone.addEventListener(evt, e => {
      e.preventDefault();
      dropzone.classList.remove('hover');
    }));
    dropzone.addEventListener('drop', (e) => {
      const incoming = Array.from(e.dataTransfer.files || []).filter(f => f.type.startsWith('image/'));
      if (incoming.length === 0) return;
      const totalIfAdded = existingAliveCount() + filesBuffer.length + incoming.length;
      if (totalIfAdded > MAX) {
        const allowed = Math.max(0, MAX - existingAliveCount() - filesBuffer.length);
        Swal.fire({
          icon: 'warning',
          title: 'Máximo 3 fotos adicionales',
          text: `Puedes añadir ${allowed} foto(s) más.`
        });
        if (allowed > 0) filesBuffer = filesBuffer.concat(incoming.slice(0, allowed));
      } else {
        filesBuffer = filesBuffer.concat(incoming);
      }
      applyBufferToInput();
      refreshNewGrid();
      refreshCounter();
    });

    btnClear.addEventListener('click', () => {
      filesBuffer = [];
      applyBufferToInput();
      refreshNewGrid();
      refreshCounter();
    });

    document.getElementById('pet-form').addEventListener('submit', (e) => {
      const hasMainNow = !!document.getElementById('photoPreview').src && !document.getElementById('photoPreview').classList.contains('d-none');
      if (!hasMainNow) {
        Swal.fire({
          icon: 'warning',
          title: 'Falta la foto principal',
          text: 'Debes seleccionar una foto principal para guardar.'
        });
        e.preventDefault();
        return;
      }
      const totalFinal = existingAliveCount() + filesBuffer.length;
      if (totalFinal > MAX) {
        Swal.fire({
          icon: 'error',
          title: 'Demasiadas fotos adicionales',
          text: 'El máximo permitido es 3.'
        });
        e.preventDefault();
        return;
      }

      // Mostrar pantalla de carga si hay fotos para procesar
      const hasNewPhotos = filesBuffer.length > 0 || input.files.length > 0 || document.getElementById('photo').files.length > 0;
      if (hasNewPhotos) {
        document.getElementById('loading-overlay').classList.add('active');
      }
    });

    refreshCounter();
  })();

  // Toggle emergency contact fields
  function toggleEmergencyFieldsEdit() {
    const toggle = document.getElementById("has_emergency_contact");
    const fields = document.getElementById("emergency-fields-edit");
    if (toggle.checked) {
      fields.style.display = "flex";
    } else {
      fields.style.display = "none";
      // Clear fields when disabled
      const nameInput = document.querySelector("input[name=\"emergency_contact_name\"]");
      const phoneInput = document.querySelector("input[name=\"emergency_contact_phone\"]");
      if (nameInput) nameInput.value = "";
      if (phoneInput) phoneInput.value = "";
    }
  }

  // Validación de edad: años y meses separados
  (function() {
    const yearsInput = document.getElementById('ageYearsInputEdit');
    const monthsInput = document.getElementById('ageMonthsInputEdit');

    if (yearsInput) {
      yearsInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (isNaN(value) || value < 0) this.value = 0;
        if (value > 50) this.value = 50;
      });
    }

    if (monthsInput) {
      monthsInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (isNaN(value) || value < 0) this.value = 0;
        if (value > 11) this.value = 11;
      });
    }
  })();
</script>
@endpush
