{{-- resources/views/portal/pets/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nueva Mascota')

@section('content')
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
      
      <!-- Header -->
      <div class="page-header mb-4">
        <div class="header-icon">
          <i class="fa-solid fa-paw"></i>
        </div>
        <div>
          <h1 class="page-title">Nueva Mascota</h1>
          <p class="page-subtitle">Completa la información de tu mejor amigo</p>
        </div>
      </div>

      <form action="{{ route('portal.pets.store') }}" method="POST" enctype="multipart/form-data" id="pet-form">
        @csrf

        {{-- ======================= DATOS BÁSICOS ======================= --}}
        <div class="form-section">
          <div class="section-header">
            <div class="section-icon-wrapper">
              <div class="section-icon primary">
                <i class="fa-solid fa-id-card"></i>
              </div>
              <div class="section-info">
                <h2 class="section-title">Datos básicos</h2>
                <p class="section-description">Nombre, raza y sexo de tu mascota</p>
              </div>
            </div>
          </div>

          <div class="section-content">
            <div class="row g-4">
              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-tag label-icon"></i>
                    Nombre *
                  </label>
                  <input type="text" name="name" class="form-input" placeholder="Ej: Max, Luna..." required>
                </div>
              </div>

              <div class="col-12 col-lg-6">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-dna label-icon"></i>
                    Raza
                  </label>
                  <input type="text" name="breed" class="form-input" placeholder="Ej: Labrador, Poodle...">
                </div>
              </div>

              <div class="col-12">
                <label class="form-label mb-3">
                  <i class="fa-solid fa-venus-mars label-icon"></i>
                  Sexo
                </label>
                <div class="gender-selector">
                  <input type="radio" id="sex_m" name="sex" value="male" class="gender-input" checked>
                  <label for="sex_m" class="gender-option">
                    <i class="fa-solid fa-mars"></i>
                    <span>Macho</span>
                  </label>

                  <input type="radio" id="sex_f" name="sex" value="female" class="gender-input">
                  <label for="sex_f" class="gender-option">
                    <i class="fa-solid fa-venus"></i>
                    <span>Hembra</span>
                  </label>

                  <input type="radio" id="sex_u" name="sex" value="unknown" class="gender-input">
                  <label for="sex_u" class="gender-option">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>Desconocido</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- ======================= SALUD ======================= --}}
        <div class="form-section">
          <div class="section-header">
            <div class="section-icon-wrapper">
              <div class="section-icon success">
                <i class="fa-solid fa-heart-pulse"></i>
              </div>
              <div class="section-info">
                <h2 class="section-title">Salud</h2>
                <p class="section-description">Esterilización, vacunas y edad</p>
              </div>
            </div>
          </div>

          <div class="section-content">
            <div class="row g-4">
              <div class="col-12 col-md-6">
                <div class="toggle-card">
                  <div class="toggle-info">
                    <div class="toggle-icon">
                      <i class="fa-solid fa-scissors"></i>
                    </div>
                    <div>
                      <label class="toggle-label" for="is_neutered">Esterilizado</label>
                      <p class="toggle-description">¿Está esterilizada tu mascota?</p>
                    </div>
                  </div>
                  <input type="hidden" name="is_neutered" value="0">
                  <label class="modern-switch">
                    <input id="is_neutered" type="checkbox" name="is_neutered" value="1" {{ old('is_neutered') ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                  </label>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="toggle-card">
                  <div class="toggle-info">
                    <div class="toggle-icon">
                      <i class="fa-solid fa-syringe"></i>
                    </div>
                    <div>
                      <label class="toggle-label" for="rabies_vaccine">Vacuna antirrábica</label>
                      <p class="toggle-description">¿Tiene la vacuna al día?</p>
                    </div>
                  </div>
                  <input type="hidden" name="rabies_vaccine" value="0">
                  <label class="modern-switch">
                    <input id="rabies_vaccine" type="checkbox" name="rabies_vaccine" value="1" {{ old('rabies_vaccine') ? 'checked' : '' }}>
                    <span class="switch-slider"></span>
                  </label>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-cake-candles label-icon"></i>
                    Edad (años)
                  </label>
                  <div class="input-with-icon">
                    <i class="fa-solid fa-calendar-days input-icon"></i>
                    <input type="number" name="age" min="0" max="50" class="form-input" placeholder="0">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- ======================= UBICACIÓN ======================= --}}
        <div class="form-section">
          <div class="section-header">
            <div class="section-icon-wrapper">
              <div class="section-icon info">
                <i class="fa-solid fa-map-location-dot"></i>
              </div>
              <div class="section-info">
                <h2 class="section-title">Ubicación</h2>
                <p class="section-description">Se utiliza para mostrar la zona en el perfil público</p>
              </div>
            </div>
          </div>

          <div class="section-content">
            <div class="row g-4" id="cr-geo" data-current-province="" data-current-canton="" data-current-district="">
              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-map label-icon"></i>
                    Provincia
                  </label>
                  <div class="input-with-icon">
                    <i class="fa-solid fa-location-dot input-icon"></i>
                    <select id="cr-province" class="form-input" disabled>
                      <option value="">Selecciona provincia</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-city label-icon"></i>
                    Cantón
                  </label>
                  <div class="input-with-icon">
                    <i class="fa-solid fa-building input-icon"></i>
                    <select id="cr-canton" class="form-input" disabled>
                      <option value="">Selecciona cantón</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-4">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-house label-icon"></i>
                    Distrito
                  </label>
                  <div class="input-with-icon">
                    <i class="fa-solid fa-map-pin input-icon"></i>
                    <select id="cr-district" class="form-input" disabled>
                      <option value="">Selecciona distrito</option>
                    </select>
                  </div>
                </div>
              </div>

              <input type="hidden" name="zone" id="zone" value="">
              
              <div class="col-12">
                <div class="zone-preview">
                  <i class="fa-solid fa-location-crosshairs"></i>
                  <span>Ubicación seleccionada:</span>
                  <code id="zone-preview">No seleccionada</code>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- ======================= OBSERVACIONES ======================= --}}
        <div class="form-section">
          <div class="section-header">
            <div class="section-icon-wrapper">
              <div class="section-icon warning">
                <i class="fa-solid fa-notes-medical"></i>
              </div>
              <div class="section-info">
                <h2 class="section-title">Observaciones médicas</h2>
                <p class="section-description">Alergias, medicación, comportamiento especial</p>
              </div>
            </div>
          </div>

          <div class="section-content">
            <div class="toggle-card mb-3">
              <div class="toggle-info">
                <div class="toggle-icon">
                  <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                  <label class="toggle-label" for="no-medical">Sin observaciones</label>
                  <p class="toggle-description">Mi mascota no tiene condiciones especiales</p>
                </div>
              </div>
              <label class="modern-switch">
                <input id="no-medical" type="checkbox">
                <span class="switch-slider"></span>
              </label>
            </div>

            <div class="form-group">
              <textarea name="medical_conditions" id="medical_conditions" rows="5" class="form-textarea"
                placeholder="Ej: Alérgica a pollo. Toma medicamento para el corazón 2 veces al día. Puede ser nerviosa con extraños."></textarea>
            </div>
          </div>
        </div>


        {{-- ======================= CONTACTO DE EMERGENCIA ======================= --}}
        <div class="form-section">
          <div class="section-header">
            <div class="section-icon-wrapper">
              <div class="section-icon danger">
                <i class="fa-solid fa-phone-volume"></i>
              </div>
              <div class="section-info">
                <h2 class="section-title">Contacto de Emergencia (Opcional)</h2>
                <p class="section-description">Si el dueño no responde, mostrar otro contacto en el perfil público</p>
              </div>
            </div>
          </div>

          <div class="section-content">
            <div class="toggle-card mb-4">
              <div class="toggle-info">
                <div class="toggle-icon">
                  <i class="fa-solid fa-user-nurse"></i>
                </div>
                <div>
                  <label class="toggle-label" for="has_emergency_contact">Habilitar Contacto de Emergencia</label>
                  <p class="toggle-description">Se mostrará como contacto secundario en caso de emergencia</p>
                </div>
              </div>
              <input type="hidden" name="has_emergency_contact" value="0">
              <label class="modern-switch">
                <input id="has_emergency_contact" type="checkbox" name="has_emergency_contact" value="1" 
                       onchange="toggleEmergencyFields()">
                <span class="switch-slider"></span>
              </label>
            </div>

            <div id="emergency-fields" class="row g-4" style="display: none;">
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-user label-icon"></i>
                    Nombre del contacto
                  </label>
                  <input type="text" name="emergency_contact_name" class="form-input" 
                         placeholder="Ej: María González">
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-mobile-screen label-icon"></i>
                    Teléfono del contacto
                  </label>
                  <input type="text" name="emergency_contact_phone" class="form-input" 
                         placeholder="Ej: +506 8765-4321">
                </div>
              </div>

              <div class="col-12">
                <div class="alert alert-info">
                  <i class="fa-solid fa-circle-info me-2"></i>
                  <strong>Nota:</strong> Este contacto aparecerá como alternativa en el perfil público 
                  de la mascota con la etiqueta "Contacto de Emergencia".
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- ======================= FOTOS MÚLTIPLES ======================= --}}
        <div class="form-section">
          <div class="section-header">
            <div class="section-icon-wrapper">
              <div class="section-icon purple">
                <i class="fa-solid fa-images"></i>
              </div>
              <div class="section-info">
                <h2 class="section-title">Galería de fotos</h2>
                <p class="section-description">Puedes seleccionar hasta 3 fotos adicionales</p>
              </div>
            </div>
          </div>

          <div class="section-content">
            <div class="upload-area">
              <input type="file" id="photos" name="photos[]" class="d-none" multiple accept="image/*">
              <label for="photos" class="upload-label">
                <div class="upload-icon">
                  <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div class="upload-text">
                  <span class="upload-title">Haz clic para seleccionar fotos</span>
                  <span class="upload-subtitle">JPG, PNG · Máx. 6 MB por imagen · Hasta 3 fotos</span>
                </div>
              </label>
            </div>

            <div id="photosPreviewGrid" class="photos-grid d-none"></div>
            
            <button type="button" id="btnClearPhotos" class="btn-clear-photos d-none">
              <i class="fa-solid fa-trash"></i>
              Eliminar todas las fotos
            </button>
          </div>
        </div>

        {{-- ======================= FOTO PRINCIPAL ======================= --}}
        <div class="form-section">
          <div class="section-header">
            <div class="section-icon-wrapper">
              <div class="section-icon orange">
                <i class="fa-solid fa-image"></i>
              </div>
              <div class="section-info">
                <h2 class="section-title">Foto principal *</h2>
                <p class="section-description">Esta será la foto de perfil de tu mascota</p>
              </div>
            </div>
          </div>

          <div class="section-content">
            <div class="main-photo-uploader">
              <div class="photo-preview-area" id="photoDrop">
                <img id="photoPreview" src="" alt="Vista previa" class="d-none">
                <div class="photo-placeholder">
                  <i class="fa-solid fa-image"></i>
                  <p>Arrastra una imagen aquí o haz clic en "Seleccionar imagen"</p>
                  <span>JPG, PNG · Máx. 4 MB</span>
                </div>
              </div>
              
              <div class="photo-actions">
                <input id="photo" name="photo" type="file" accept="image/*" class="d-none" required>
                <label for="photo" class="btn-photo-action primary">
                  <i class="fa-solid fa-image"></i>
                  Seleccionar imagen
                </label>
                <button type="button" id="btnClearPhoto" class="btn-photo-action secondary">
                  <i class="fa-solid fa-xmark"></i>
                  Quitar
                </button>
              </div>
            </div>
          </div>
        </div>

        {{-- Botones de acción --}}
        <div class="form-actions">
          <button type="submit" class="btn-submit">
            <i class="fa-solid fa-check"></i>
            <span>Guardar mascota</span>
          </button>
          <a href="{{ route('portal.pets.index') }}" class="btn-cancel">
            <i class="fa-solid fa-xmark"></i>
            Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>


@push('styles')
<link rel="stylesheet" href="{{ asset('css/pet-form.css') }}">
@endpush


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@push('scripts')
<script src="{{ asset('js/pet-form-create.js') }}"></script>
@endpush
