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

<style>
:root {
  --primary: #667eea;
  --primary-dark: #5568d3;
  --success: #10b981;
  --info: #3b82f6;
  --warning: #f59e0b;
  --orange: #f97316;
  --purple: #a855f7;
  --gray-50: #f9fafb;
  --gray-100: #f3f4f6;
  --gray-200: #e5e7eb;
  --gray-300: #d1d5db;
  --gray-400: #9ca3af;
  --gray-500: #6b7280;
  --gray-600: #4b5563;
  --gray-700: #374151;
  --gray-800: #1f2937;
  --gray-900: #111827;
}

/* ===== Header ===== */
.page-header {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 24px;
  background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
  border-radius: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.header-icon {
  width: 72px;
  height: 72px;
  border-radius: 20px;
  background: linear-gradient(135deg, var(--primary) 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 32px;
  box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
}

.page-title {
  font-size: 28px;
  font-weight: 700;
  color: var(--gray-900);
  margin: 0;
}

.page-subtitle {
  color: var(--gray-500);
  margin: 4px 0 0;
  font-size: 15px;
}

/* ===== Form Sections ===== */
.form-section {
  background: white;
  border-radius: 24px;
  padding: 32px;
  margin-bottom: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 40px rgba(0, 0, 0, 0.02);
  border: 1px solid var(--gray-100);
  transition: all 0.3s ease;
}

.form-section:hover {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 20px 50px rgba(0, 0, 0, 0.03);
  transform: translateY(-2px);
}

.section-header {
  margin-bottom: 28px;
  padding-bottom: 20px;
  border-bottom: 2px solid var(--gray-100);
}

.section-icon-wrapper {
  display: flex;
  align-items: center;
  gap: 16px;
}

.section-icon {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.section-icon.primary {
  background: linear-gradient(135deg, var(--primary) 0%, #764ba2 100%);
}

.section-icon.success {
  background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
}

.section-icon.info {
  background: linear-gradient(135deg, var(--info) 0%, #2563eb 100%);
}

.section-icon.warning {
  background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
}

.section-icon.purple {
  background: linear-gradient(135deg, var(--purple) 0%, #9333ea 100%);
}

.section-icon.orange {
  background: linear-gradient(135deg, var(--orange) 0%, #ea580c 100%);
}

.section-title {
  font-size: 20px;
  font-weight: 700;
  color: var(--gray-900);
  margin: 0;
}

.section-description {
  color: var(--gray-500);
  font-size: 14px;
  margin: 4px 0 0;
}

.section-content {
  padding-top: 4px;
}

/* ===== Form Inputs ===== */
.form-group {
  margin-bottom: 0;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  color: var(--gray-700);
  margin-bottom: 10px;
  font-size: 14px;
}

.label-icon {
  color: var(--gray-400);
  font-size: 14px;
}

.form-input,
.form-textarea {
  width: 100%;
  padding: 14px 16px;
  border: 2px solid var(--gray-200);
  border-radius: 12px;
  font-size: 15px;
  color: var(--gray-900);
  background: white;
  transition: all 0.2s ease;
}

.form-input:focus,
.form-textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-input::placeholder,
.form-textarea::placeholder {
  color: var(--gray-400);
}

.form-textarea {
  resize: vertical;
  min-height: 120px;
  font-family: inherit;
}

.input-with-icon {
  position: relative;
}

.input-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--gray-400);
  font-size: 16px;
  pointer-events: none;
}

.input-with-icon .form-input {
  padding-left: 48px;
}

/* ===== Gender Selector ===== */
.gender-selector {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 12px;
}

.gender-input {
  display: none;
}

.gender-option {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  padding: 20px 16px;
  border: 2px solid var(--gray-200);
  border-radius: 16px;
  background: white;
  cursor: pointer;
  transition: all 0.3s ease;
  text-align: center;
}

.gender-option i {
  font-size: 32px;
  color: var(--gray-400);
  transition: all 0.3s ease;
}

.gender-option span {
  font-weight: 600;
  color: var(--gray-600);
  font-size: 14px;
}

.gender-input:checked + .gender-option {
  border-color: var(--primary);
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.gender-input:checked + .gender-option i {
  color: var(--primary);
  transform: scale(1.1);
}

.gender-input:checked + .gender-option span {
  color: var(--primary);
}

/* ===== Toggle Cards ===== */
.toggle-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 20px;
  border: 2px solid var(--gray-200);
  border-radius: 16px;
  background: var(--gray-50);
  transition: all 0.3s ease;
}

.toggle-card:hover {
  border-color: var(--gray-300);
  background: white;
}

.toggle-info {
  display: flex;
  align-items: center;
  gap: 14px;
  flex: 1;
}

.toggle-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary);
  font-size: 20px;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.toggle-label {
  font-weight: 600;
  color: var(--gray-800);
  margin: 0;
  font-size: 15px;
  cursor: pointer;
}

.toggle-description {
  color: var(--gray-500);
  font-size: 13px;
  margin: 2px 0 0;
}

/* ===== Modern Switch ===== */
.modern-switch {
  position: relative;
  display: inline-block;
  width: 56px;
  height: 32px;
  flex-shrink: 0;
  cursor: pointer;
}

.modern-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.switch-slider {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: var(--gray-300);
  border-radius: 34px;
  transition: all 0.3s ease;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.switch-slider:before {
  content: "";
  position: absolute;
  height: 26px;
  width: 26px;
  left: 3px;
  bottom: 3px;
  background: white;
  border-radius: 50%;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.modern-switch input:checked + .switch-slider {
  background: linear-gradient(135deg, var(--primary) 0%, #764ba2 100%);
}

.modern-switch input:checked + .switch-slider:before {
  transform: translateX(24px);
}

/* ===== Zone Preview ===== */
.zone-preview {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px 20px;
  background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
  border: 2px dashed var(--gray-300);
  border-radius: 12px;
  font-size: 14px;
  color: var(--gray-600);
}

.zone-preview i {
  color: var(--info);
  font-size: 18px;
}

.zone-preview code {
  font-family: 'Courier New', monospace;
  background: white;
  padding: 6px 12px;
  border-radius: 8px;
  color: var(--primary);
  font-weight: 600;
  border: 1px solid var(--gray-200);
}

/* ===== Upload Area ===== */
.upload-area {
  border: 3px dashed var(--gray-300);
  border-radius: 20px;
  padding: 40px 24px;
  text-align: center;
  background: var(--gray-50);
  transition: all 0.3s ease;
  cursor: pointer;
  margin-bottom: 20px;
}

.upload-area:hover {
  border-color: var(--primary);
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.02), rgba(118, 75, 162, 0.02));
}

.upload-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
  cursor: pointer;
  margin: 0;
}

.upload-icon {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary) 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 32px;
  box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
}

.upload-text {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.upload-title {
  font-weight: 600;
  color: var(--gray-800);
  font-size: 16px;
}

.upload-subtitle {
  color: var(--gray-500);
  font-size: 13px;
}

/* ===== Photos Grid ===== */
.photos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 16px;
  margin-bottom: 16px;
}

.photos-grid .ph {
  position: relative;
  aspect-ratio: 1;
  border-radius: 16px;
  overflow: hidden;
  background: var(--gray-100);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.photos-grid .ph:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.photos-grid .ph img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.photos-grid .ph .ph-remove {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: none;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
  backdrop-filter: blur(4px);
}

.photos-grid .ph .ph-remove:hover {
  background: #ef4444;
  transform: scale(1.1);
}

.btn-clear-photos {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 20px;
  border: 2px solid var(--gray-200);
  border-radius: 12px;
  background: white;
  color: #ef4444;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-clear-photos:hover {
  background: #fef2f2;
  border-color: #ef4444;
  transform: translateY(-2px);
}

/* ===== Main Photo Uploader ===== */
.main-photo-uploader {
  display: grid;
  gap: 20px;
}

.photo-preview-area {
  position: relative;
  min-height: 280px;
  border: 3px dashed var(--gray-300);
  border-radius: 20px;
  background: var(--gray-50);
  overflow: hidden;
  transition: all 0.3s ease;
}

.photo-preview-area.is-dragover {
  border-color: var(--primary);
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.photo-preview-area img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.photo-placeholder {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  padding: 24px;
  text-align: center;
}

.photo-placeholder i {
  font-size: 48px;
  color: var(--gray-400);
}

.photo-placeholder p {
  color: var(--gray-600);
  font-weight: 500;
  margin: 0;
  font-size: 15px;
}

.photo-placeholder span {
  color: var(--gray-500);
  font-size: 13px;
}

.photo-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.btn-photo-action {
  flex: 1;
  min-width: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 14px 24px;
  border-radius: 12px;
  font-weight: 600;
  font-size: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
  border: none;
  text-decoration: none;
}

.btn-photo-action.primary {
  background: linear-gradient(135deg, var(--primary) 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-photo-action.primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.btn-photo-action.secondary {
  background: white;
  color: #ef4444;
  border: 2px solid var(--gray-200);
}

.btn-photo-action.secondary:hover {
  border-color: #ef4444;
  background: #fef2f2;
  transform: translateY(-2px);
}

/* ===== Form Actions ===== */
.form-actions {
  display: flex;
  gap: 16px;
  padding: 32px 0;
  flex-wrap: wrap;
}

.btn-submit,
.btn-cancel {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 16px 32px;
  border-radius: 14px;
  font-weight: 600;
  font-size: 16px;
  transition: all 0.3s ease;
  text-decoration: none;
  border: none;
  cursor: pointer;
}

.btn-submit {
  flex: 1;
  min-width: 200px;
  background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
  color: white;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-submit:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

.btn-submit:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  pointer-events: none;
}

.btn-cancel {
  background: white;
  color: var(--gray-700);
  border: 2px solid var(--gray-300);
}

.btn-cancel:hover {
  border-color: var(--gray-400);
  background: var(--gray-50);
  transform: translateY(-2px);
}

/* ===== Responsive ===== */
@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    text-align: center;
    padding: 20px;
  }

  .header-icon {
    width: 64px;
    height: 64px;
    font-size: 28px;
  }

  .page-title {
    font-size: 24px;
  }

  .form-section {
    padding: 24px 20px;
    border-radius: 20px;
  }

  .section-icon-wrapper {
    flex-direction: column;
    text-align: center;
  }

  .section-icon {
    width: 48px;
    height: 48px;
    font-size: 20px;
  }

  .section-title {
    font-size: 18px;
  }

  .gender-selector {
    grid-template-columns: 1fr;
  }

  .photo-actions {
    flex-direction: column;
  }

  .btn-photo-action {
    width: 100%;
  }

  .form-actions {
    flex-direction: column;
  }

  .btn-submit,
  .btn-cancel {
    width: 100%;
  }

  .photos-grid {
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 12px;
  }
}

@media (max-width: 576px) {
  .toggle-card {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .modern-switch {
    margin-left: auto;
  }

  .upload-icon {
    width: 56px;
    height: 56px;
    font-size: 24px;
  }

  .photo-preview-area {
    min-height: 220px;
  }
}
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  (() => {
    // ===== Observaciones toggle: deshabilita/habilita textarea
    const $noMedical = document.getElementById('no-medical');
    const $medical = document.getElementById('medical_conditions');

    function toggleMedical() {
      if ($noMedical.checked) {
        $medical.value = '';
        $medical.setAttribute('disabled', 'disabled');
        $medical.style.opacity = '0.5';
      } else {
        $medical.removeAttribute('disabled');
        $medical.style.opacity = '1';
      }
    }
    $noMedical.addEventListener('change', toggleMedical);
    toggleMedical();
  })();
</script>

<script>
  (() => {
    // ===== Cascada CR provincias/cantones/distritos
    const API = 'https://ubicaciones.paginasweb.cr';
    const $prov = document.getElementById('cr-province');
    const $cant = document.getElementById('cr-canton');
    const $dist = document.getElementById('cr-district');
    const $zone = document.getElementById('zone');
    const $zonePreview = document.getElementById('zone-preview');

    async function getJSON(path) {
      const r = await fetch(`${API}${path}`);
      if (!r.ok) throw 0;
      return await r.json();
    }

    function fillSelect($sel, map, placeholder) {
      $sel.innerHTML = `<option value="">${placeholder}</option>`;
      for (const [id, name] of Object.entries(map)) {
        const opt = document.createElement('option');
        opt.value = id;
        opt.textContent = name;
        $sel.appendChild(opt);
      }
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
        $zonePreview.textContent = 'No seleccionada';
      }
    }

    (async () => {
      try {
        const provincias = await getJSON('/provincias.json');
        fillSelect($prov, provincias, 'Selecciona provincia');
        $prov.disabled = false;
      } catch (e) {
        const wrap = $prov.closest('.row');
        wrap.outerHTML = `
        <div class="col-12">
          <div class="alert alert-warning" style="border-radius: 12px; border: 2px solid #fbbf24; background: #fef3c7; color: #92400e; padding: 16px;">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <strong>No se pudo cargar la lista de ubicaciones.</strong> Ingresa manualmente la zona.
          </div>
          <div class="form-group">
            <label class="form-label">
              <i class="fa-solid fa-map-location-dot label-icon"></i>
              Ubicación manual
            </label>
            <input class="form-input" placeholder="Ej: San Juan, Grecia, Alajuela"
                   oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
          </div>
        </div>`;
      }
    })();

    $prov.addEventListener('change', async () => {
      $cant.disabled = true;
      $dist.disabled = true;
      $dist.innerHTML = `<option value="">Selecciona distrito</option>`;
      setZone();
      if (!$prov.value) {
        $cant.innerHTML = `<option value="">Selecciona cantón</option>`;
        return;
      }
      const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
      fillSelect($cant, cantones, 'Selecciona cantón');
      $cant.disabled = false;
    });

    $cant.addEventListener('change', async () => {
      $dist.disabled = true;
      $dist.innerHTML = `<option value="">Selecciona distrito</option>`;
      setZone();
      if (!$prov.value || !$cant.value) return;
      const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
      fillSelect($dist, distritos, 'Selecciona distrito');
      $dist.disabled = false;
    });

    $dist.addEventListener('change', setZone);
  })();
</script>

<script>
  /* Uploader principal (legacy) */
  (function() {
    const input = document.getElementById('photo');
    const preview = document.getElementById('photoPreview');
    const drop = document.getElementById('photoDrop');
    const clear = document.getElementById('btnClearPhoto');
    const form = document.getElementById('pet-form');
    const submit = form.querySelector('button[type="submit"]') || form.querySelector('.btn-submit');

    function hasMain() {
      return !!preview.src && !preview.classList.contains('d-none');
    }

    function syncSubmit() {
      const ok = hasMain();
      submit.disabled = !ok;
      submit.classList.toggle('disabled', !ok);
      submit.style.pointerEvents = ok ? '' : 'none';
      submit.style.opacity = ok ? '' : '.65';
    }

    function show(file) {
      if (!file) return;
      const url = URL.createObjectURL(file);
      preview.src = url;
      preview.classList.remove('d-none');
      drop.querySelector('.photo-placeholder').style.display = 'none';
      drop.classList.remove('is-dragover');
      syncSubmit();
    }

    input.addEventListener('change', e => show(e.target.files[0]));
    
    ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => {
      e.preventDefault();
      drop.classList.add('is-dragover');
    }));
    
    ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => {
      e.preventDefault();
      drop.classList.remove('is-dragover');
    }));
    
    drop.addEventListener('drop', e => {
      const f = e.dataTransfer.files && e.dataTransfer.files[0];
      if (f) {
        input.files = e.dataTransfer.files;
        show(f);
      }
    });
    
    clear.addEventListener('click', () => {
      preview.src = '';
      preview.classList.add('d-none');
      drop.querySelector('.photo-placeholder').style.display = 'flex';
      input.value = '';
      syncSubmit();
    });

    form.addEventListener('submit', (e) => {
      if (!hasMain()) {
        e.preventDefault();
        Swal.fire({
          icon: 'warning',
          title: 'Foto principal requerida',
          text: 'Por favor selecciona una foto principal para tu mascota.',
          confirmButtonText: 'Entendido',
          confirmButtonColor: '#667eea'
        });
      }
    });
    
    syncSubmit();
  })();
</script>

<script>
  /* Previews de fotos múltiples + LÍMITE 3 */
  (function() {
    const MAX = 3;
    const input = document.getElementById('photos');
    const grid = document.getElementById('photosPreviewGrid');
    const btnClear = document.getElementById('btnClearPhotos');
    let filesBuffer = [];

    function refreshGrid() {
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
      refreshGrid();
    }

    input.addEventListener('change', (e) => {
      const incoming = Array.from(e.target.files || []);
      const totalIfAdded = filesBuffer.length + incoming.length;
      if (totalIfAdded > MAX) {
        const allowed = Math.max(0, MAX - filesBuffer.length);
        Swal.fire({
          icon: 'warning',
          title: 'Máximo 3 fotos adicionales',
          text: `Puedes añadir ${allowed} foto(s) más.`,
          confirmButtonText: 'Entendido',
          confirmButtonColor: '#667eea'
        });
        if (allowed > 0) filesBuffer = filesBuffer.concat(incoming.slice(0, allowed));
      } else {
        filesBuffer = filesBuffer.concat(incoming);
      }
      applyBufferToInput();
      refreshGrid();
      input.value = '';
    });

    btnClear.addEventListener('click', () => {
      Swal.fire({
        title: '¿Eliminar todas las fotos?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          filesBuffer = [];
          input.value = '';
          refreshGrid();
        }
      });
    });

    document.getElementById('pet-form').addEventListener('submit', (e) => {
      if (filesBuffer.length > MAX) e.preventDefault();
    });
  })();
  \n  // Toggle emergency contact fields\n  function toggleEmergencyFields() {\n    const toggle = document.getElementById("has_emergency_contact");\n    const fields = document.getElementById("emergency-fields");\n    if (toggle.checked) {\n      fields.style.display = "flex";\n    } else {\n      fields.style.display = "none";\n      // Clear fields when disabled\n      document.querySelector("input[name=\"emergency_contact_name\"]").value = "";\n      document.querySelector("input[name=\"emergency_contact_phone\"]").value = "";\n    }\n  }
</script>
@endpush
