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
                    Raza <span class="text-danger">*</span>
                  </label>
                  <input type="text" name="breed" class="form-input" placeholder="Ej: Labrador, Poodle..." required>
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
                    Edad <span class="text-danger">*</span>
                  </label>
                  <div class="age-inputs-dual">
                    <div class="age-field">
                      <label class="age-sublabel">Años</label>
                      <div class="input-with-icon">
                        <i class="fa-solid fa-calendar-days input-icon"></i>
                        <input type="number" name="age_years" min="0" max="50" class="form-input" placeholder="0" id="ageYearsInput">
                      </div>
                    </div>
                    <div class="age-field">
                      <label class="age-sublabel">Meses</label>
                      <div class="input-with-icon">
                        <i class="fa-solid fa-calendar-alt input-icon"></i>
                        <input type="number" name="age_months" min="0" max="11" class="form-input" placeholder="0" id="ageMonthsInput">
                      </div>
                    </div>
                  </div>
                  <small class="text-muted mt-2 d-block">
                    <i class="fa-solid fa-info-circle me-1"></i>
                    Ejemplo: 1 año y 6 meses, o solo años, o solo meses
                  </small>
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
                <h2 class="section-title">Ubicación <span class="text-danger">*</span></h2>
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

        {{-- Sección de invitación (solo admin) --}}
        @if(Auth::user()->is_admin)
        <div class="admin-invitation-section">
          <div class="section-header-invitation">
            <div class="section-icon-invitation">
              <i class="fa-solid fa-envelope"></i>
            </div>
            <div>
              <h3 class="section-title-invitation">Invitación al Cliente</h3>
              <p class="section-subtitle-invitation">Envía una invitación al cliente para que se registre y gestione su mascota</p>
            </div>
          </div>

          <div class="invitation-toggle">
            <label class="invitation-switch">
              <input type="checkbox" id="sendInvitation" name="send_invitation" value="1">
              <span class="slider-invitation"></span>
            </label>
            <div class="invitation-toggle-label">
              <strong>Enviar invitación al correo del cliente</strong>
              <small>Al activar esta opción, se enviará un email al cliente con un link para registrarse y reclamar su mascota</small>
            </div>
          </div>

          <div id="invitationFields" class="invitation-fields" style="display: none;">
            <div class="row g-3">
              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-envelope label-icon"></i>
                    Email del cliente <span class="text-danger">*</span>
                  </label>
                  <div class="input-with-icon">
                    <i class="fa-solid fa-at input-icon"></i>
                    <input
                      type="email"
                      name="pending_email"
                      id="pendingEmail"
                      class="form-input"
                      placeholder="cliente@ejemplo.com"
                    >
                  </div>
                  <small class="form-text">Se enviará la invitación a este correo</small>
                </div>
              </div>

              <div class="col-12 col-md-6">
                <div class="form-group">
                  <label class="form-label">
                    <i class="fa-solid fa-box label-icon"></i>
                    Plan a asignar <span class="text-danger">*</span>
                  </label>
                  <div class="input-with-icon">
                    <i class="fa-solid fa-layer-group input-icon"></i>
                    <select name="pending_plan_id" id="pendingPlanId" class="form-input">
                      <option value="">Selecciona un plan...</option>
                      @foreach(\App\Models\Plan::where('is_active', true)->orderBy('price')->get() as $plan)
                        <option
                          value="{{ $plan->id }}"
                          data-price="{{ $plan->price }}"
                          data-pets-included="{{ $plan->pets_included }}">
                          {{ $plan->name }} - ₡{{ number_format($plan->price, 0, ',', '.') }} ({{ $plan->pets_included }} mascota{{ $plan->pets_included > 1 ? 's' : '' }})
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <small class="form-text">Este plan se activará cuando el cliente complete su registro</small>
                </div>
              </div>

              <!-- Contenedor dinámico para múltiples mascotas -->
              <div class="col-12" id="multiplePetsContainer" style="display: none;">
                <div class="multiple-pets-header">
                  <i class="fa-solid fa-paw"></i>
                  <h4>Mascotas Adicionales</h4>
                  <span class="pets-count-badge" id="petsCountBadge"></span>
                </div>
                <p style="padding: 12px 20px; margin: 0; background: #f0fdf4; color: #15803d; font-size: 14px; border-bottom: 2px solid #86efac;">
                  <i class="fa-solid fa-info-circle"></i> El formulario principal arriba es la mascota #1. Aquí completa los datos de las mascotas adicionales.
                </p>
                <div id="petsFormsContainer"></div>
              </div>

              <div class="col-12">
                <div class="invitation-info-box">
                  <div class="info-icon">
                    <i class="fa-solid fa-lightbulb"></i>
                  </div>
                  <div class="info-content">
                    <strong>¿Cómo funciona?</strong>
                    <ul>
                      <li>Se enviará un email al cliente con un link único</li>
                      <li>El cliente hace clic y se registra en la plataforma</li>
                      <li>Al completar el registro, la mascota se liga automáticamente a su cuenta</li>
                      <li>Se crea una orden automáticamente con el plan seleccionado</li>
                      <li>El cliente podrá gestionar su mascota inmediatamente</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif

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

/* ===== Age Inputs Dual (Years + Months) ===== */
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
  color: var(--gray-600);
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.age-field .form-input {
  text-align: center;
  font-weight: 600;
  font-size: 18px;
}

@media (max-width: 576px) {
  .age-inputs-dual {
    grid-template-columns: 1fr;
    gap: 12px;
  }
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
  aspect-ratio: 4 / 3;
  max-height: 400px;
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

/* ===== Sección de Invitación (Admin) ===== */
.admin-invitation-section {
  background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
  border: 2px solid #bfdbfe;
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 24px;
}

.section-header-invitation {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 20px;
}

.section-icon-invitation {
  width: 56px;
  height: 56px;
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.section-title-invitation {
  font-size: 20px;
  font-weight: 700;
  color: var(--gray-900);
  margin: 0;
}

.section-subtitle-invitation {
  font-size: 14px;
  color: var(--gray-600);
  margin: 4px 0 0 0;
}

.invitation-toggle {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  background: white;
  border-radius: 12px;
  margin-bottom: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.invitation-switch {
  position: relative;
  display: inline-block;
  width: 56px;
  height: 32px;
  flex-shrink: 0;
}

.invitation-switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider-invitation {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: var(--gray-300);
  transition: 0.3s;
  border-radius: 34px;
}

.slider-invitation:before {
  position: absolute;
  content: "";
  height: 24px;
  width: 24px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.invitation-switch input:checked + .slider-invitation {
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
}

.invitation-switch input:checked + .slider-invitation:before {
  transform: translateX(24px);
}

.invitation-toggle-label {
  flex: 1;
}

.invitation-toggle-label strong {
  display: block;
  font-size: 15px;
  color: var(--gray-900);
  margin-bottom: 4px;
}

.invitation-toggle-label small {
  display: block;
  font-size: 13px;
  color: var(--gray-600);
}

.invitation-fields {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.invitation-info-box {
  display: flex;
  gap: 16px;
  padding: 16px;
  background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
  border: 2px solid #fbbf24;
  border-radius: 12px;
}

.invitation-info-box .info-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 18px;
  flex-shrink: 0;
}

.invitation-info-box .info-content {
  flex: 1;
}

.invitation-info-box strong {
  display: block;
  font-size: 15px;
  color: var(--gray-900);
  margin-bottom: 8px;
}

.invitation-info-box ul {
  margin: 0;
  padding-left: 20px;
  font-size: 14px;
  color: var(--gray-700);
}

.invitation-info-box ul li {
  margin-bottom: 4px;
}

@media (max-width: 768px) {
  .admin-invitation-section {
    padding: 16px;
  }

  .section-header-invitation {
    flex-direction: column;
    align-items: flex-start;
  }

  .invitation-toggle {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }

  .invitation-info-box {
    flex-direction: column;
  }
}

/* ===== Estilos para formularios dinámicos de múltiples mascotas ===== */
.multiple-pets-header {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
  border: 2px solid #86efac;
  border-radius: 12px 12px 0 0;
  margin-bottom: 0;
}

.multiple-pets-header i {
  font-size: 24px;
  color: #16a34a;
}

.multiple-pets-header h4 {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: var(--gray-900);
  flex: 1;
}

.pets-count-badge {
  background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
  color: white;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
}

#petsFormsContainer {
  background: white;
  border: 2px solid #86efac;
  border-top: none;
  border-radius: 0 0 12px 12px;
  padding: 0;
}

.pet-form-card {
  padding: 20px;
  border-bottom: 2px dashed #d1d5db;
  transition: background-color 0.2s;
}

.pet-form-card:last-child {
  border-bottom: none;
  border-radius: 0 0 10px 10px;
}

.pet-form-card:hover {
  background: #f9fafb;
}

.pet-form-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
  padding-bottom: 12px;
  border-bottom: 2px solid #e5e7eb;
}

.pet-number-badge {
  width: 36px;
  height: 36px;
  background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 16px;
  box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.pet-form-header h5 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: var(--gray-800);
}

.dynamic-pet-field {
  margin-bottom: 16px;
}

.dynamic-pet-field label {
  display: block;
  margin-bottom: 6px;
  font-weight: 500;
  color: var(--gray-700);
  font-size: 14px;
}

.dynamic-pet-field .text-danger {
  color: #ef4444;
}

.dynamic-pet-field input,
.dynamic-pet-field select,
.dynamic-pet-field textarea {
  width: 100%;
  padding: 10px 14px;
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  font-size: 14px;
  transition: all 0.2s;
}

.dynamic-pet-field input:focus,
.dynamic-pet-field select:focus,
.dynamic-pet-field textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.dynamic-pet-field small {
  display: block;
  margin-top: 4px;
  font-size: 12px;
  color: var(--gray-500);
}

@media (max-width: 768px) {
  .multiple-pets-header {
    padding: 12px;
  }

  .multiple-pets-header h4 {
    font-size: 16px;
  }

  .pet-form-card {
    padding: 16px;
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
      // FIX: NO limpiar input.value aquí porque también limpia input.files
      // Los archivos deben persistir en el input para enviarse con el formulario
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
          applyBufferToInput(); // Aplica la lista vacía al input
          refreshGrid();
        }
      });
    });

    document.getElementById('pet-form').addEventListener('submit', (e) => {
      if (filesBuffer.length > MAX) e.preventDefault();
    });
  })();

  // Toggle emergency contact fields
  function toggleEmergencyFields() {
    const toggle = document.getElementById("has_emergency_contact");
    const fields = document.getElementById("emergency-fields");
    if (toggle.checked) {
      fields.style.display = "flex";
    } else {
      fields.style.display = "none";
      // Clear fields when disabled
      document.querySelector("input[name=\"emergency_contact_name\"]").value = "";
      document.querySelector("input[name=\"emergency_contact_phone\"]").value = "";
    }
  }
</script>

<script>
  // Validación de edad dual (años y meses)
  (function() {
    const yearsInput = document.getElementById('ageYearsInput');
    const monthsInput = document.getElementById('ageMonthsInput');

    if (yearsInput) {
      yearsInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 0) this.value = 0;
        if (value > 50) this.value = 50;
      });
    }

    if (monthsInput) {
      monthsInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value < 0) this.value = 0;
        if (value > 11) this.value = 11;
      });
    }
  })();
</script>

{{-- Script para manejar invitación al cliente (solo admin) --}}
@if(Auth::user()->is_admin)
<script>
  (function() {
    const sendInvitation = document.getElementById('sendInvitation');
    const invitationFields = document.getElementById('invitationFields');
    const pendingEmail = document.getElementById('pendingEmail');
    const pendingPlanId = document.getElementById('pendingPlanId');
    const multiplePetsContainer = document.getElementById('multiplePetsContainer');
    const petsFormsContainer = document.getElementById('petsFormsContainer');
    const petsCountBadge = document.getElementById('petsCountBadge');

    // Variable para almacenar el número de mascotas del plan seleccionado
    let petsIncluded = 1;

    if (sendInvitation && invitationFields) {
      sendInvitation.addEventListener('change', function() {
        if (this.checked) {
          invitationFields.style.display = 'block';
          pendingEmail.required = true;
          pendingPlanId.required = true;
        } else {
          invitationFields.style.display = 'none';
          pendingEmail.required = false;
          pendingPlanId.required = false;
          pendingEmail.value = '';
          pendingPlanId.value = '';
          multiplePetsContainer.style.display = 'none';
          petsFormsContainer.innerHTML = '';
        }
      });
    }

    // Cuando se selecciona un plan, generar formularios dinámicos
    if (pendingPlanId) {
      pendingPlanId.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        petsIncluded = parseInt(selectedOption.getAttribute('data-pets-included')) || 1;

        if (this.value && sendInvitation.checked) {
          const additionalPets = petsIncluded - 1;
          generatePetForms(petsIncluded);

          if (additionalPets > 0) {
            multiplePetsContainer.style.display = 'block';
            petsCountBadge.textContent = `${additionalPets} adicional${additionalPets > 1 ? 'es' : ''}`;
          } else {
            multiplePetsContainer.style.display = 'none';
          }
        } else {
          multiplePetsContainer.style.display = 'none';
          petsFormsContainer.innerHTML = '';
        }
      });
    }

    // Función para generar formularios dinámicos IDÉNTICOS al original
    // El formulario original ya es la mascota #1, aquí generamos #2, #3, etc.
    function generatePetForms(totalPets) {
      petsFormsContainer.innerHTML = '';

      // Generar totalPets - 1 formularios (porque el original ya es uno)
      const additionalPets = totalPets - 1;

      for (let i = 1; i <= additionalPets; i++) {
        const petCard = document.createElement('div');
        petCard.className = 'pet-form-card';
        petCard.innerHTML = `
          <div class="pet-form-header">
            <div class="pet-number-badge">${i + 1}</div>
            <h5>Mascota ${i + 1}</h5>
          </div>

          <!-- Replicación EXACTA del formulario original -->

          {{-- ======================= DATOS BÁSICOS ======================= --}}
          <div class="form-section" style="margin-bottom: 24px;">
            <div class="section-header">
              <div class="section-icon-wrapper">
                <div class="section-icon primary">
                  <i class="fa-solid fa-id-card"></i>
                </div>
                <div class="section-info">
                  <h2 class="section-title">Datos básicos</h2>
                  <p class="section-description">Nombre, raza y sexo</p>
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
                    <input type="text" name="pets[${i}][name]" class="form-input" placeholder="Ej: Max, Luna..." required>
                  </div>
                </div>

                <div class="col-12 col-lg-6">
                  <div class="form-group">
                    <label class="form-label">
                      <i class="fa-solid fa-dna label-icon"></i>
                      Raza <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="pets[${i}][breed]" class="form-input" placeholder="Ej: Labrador, Poodle..." required>
                  </div>
                </div>

                <div class="col-12">
                  <label class="form-label mb-3">
                    <i class="fa-solid fa-venus-mars label-icon"></i>
                    Sexo
                  </label>
                  <div class="gender-selector">
                    <input type="radio" id="sex_m_${i}" name="pets[${i}][sex]" value="male" class="gender-input" checked>
                    <label for="sex_m_${i}" class="gender-option">
                      <i class="fa-solid fa-mars"></i>
                      <span>Macho</span>
                    </label>

                    <input type="radio" id="sex_f_${i}" name="pets[${i}][sex]" value="female" class="gender-input">
                    <label for="sex_f_${i}" class="gender-option">
                      <i class="fa-solid fa-venus"></i>
                      <span>Hembra</span>
                    </label>

                    <input type="radio" id="sex_u_${i}" name="pets[${i}][sex]" value="unknown" class="gender-input">
                    <label for="sex_u_${i}" class="gender-option">
                      <i class="fa-solid fa-circle-question"></i>
                      <span>Desconocido</span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- ======================= SALUD ======================= --}}
          <div class="form-section" style="margin-bottom: 24px;">
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
                        <label class="toggle-label" for="is_neutered_${i}">Esterilizado</label>
                        <p class="toggle-description">¿Está esterilizada tu mascota?</p>
                      </div>
                    </div>
                    <input type="hidden" name="pets[${i}][is_neutered]" value="0">
                    <label class="modern-switch">
                      <input id="is_neutered_${i}" type="checkbox" name="pets[${i}][is_neutered]" value="1">
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
                        <label class="toggle-label" for="rabies_vaccine_${i}">Vacuna antirrábica</label>
                        <p class="toggle-description">¿Tiene la vacuna al día?</p>
                      </div>
                    </div>
                    <input type="hidden" name="pets[${i}][rabies_vaccine]" value="0">
                    <label class="modern-switch">
                      <input id="rabies_vaccine_${i}" type="checkbox" name="pets[${i}][rabies_vaccine]" value="1">
                      <span class="switch-slider"></span>
                    </label>
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label class="form-label">
                      <i class="fa-solid fa-cake-candles label-icon"></i>
                      Edad <span class="text-danger">*</span>
                    </label>
                    <div class="age-inputs-dual">
                      <div class="age-field">
                        <label class="age-sublabel">Años</label>
                        <div class="input-with-icon">
                          <i class="fa-solid fa-calendar-days input-icon"></i>
                          <input type="number" name="pets[${i}][age_years]" min="0" max="50" class="form-input" placeholder="0">
                        </div>
                      </div>
                      <div class="age-field">
                        <label class="age-sublabel">Meses</label>
                        <div class="input-with-icon">
                          <i class="fa-solid fa-calendar-alt input-icon"></i>
                          <input type="number" name="pets[${i}][age_months]" min="0" max="11" class="form-input" placeholder="0">
                        </div>
                      </div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                      <i class="fa-solid fa-info-circle me-1"></i>
                      Ejemplo: 1 año y 6 meses, o solo años, o solo meses
                    </small>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- ======================= UBICACIÓN ======================= --}}
          <div class="form-section" style="margin-bottom: 24px;">
            <div class="section-header">
              <div class="section-icon-wrapper">
                <div class="section-icon info">
                  <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div class="section-info">
                  <h2 class="section-title">Ubicación <span class="text-danger">*</span></h2>
                  <p class="section-description">Se utiliza para mostrar la zona en el perfil público</p>
                </div>
              </div>
            </div>

            <div class="section-content">
              <div class="row g-4" id="cr-geo-${i}">
                <div class="col-12 col-md-4">
                  <div class="form-group">
                    <label class="form-label">
                      <i class="fa-solid fa-map label-icon"></i>
                      Provincia
                    </label>
                    <div class="input-with-icon">
                      <i class="fa-solid fa-location-dot input-icon"></i>
                      <select id="cr-province-${i}" class="form-input" disabled>
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
                      <select id="cr-canton-${i}" class="form-input" disabled>
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
                      <select id="cr-district-${i}" class="form-input" disabled>
                        <option value="">Selecciona distrito</option>
                      </select>
                    </div>
                  </div>
                </div>

                <input type="hidden" name="pets[${i}][zone]" id="zone-${i}" value="">

                <div class="col-12">
                  <div class="zone-preview">
                    <i class="fa-solid fa-location-crosshairs"></i>
                    <span>Ubicación seleccionada:</span>
                    <code id="zone-preview-${i}">No seleccionada</code>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {{-- ======================= OBSERVACIONES MÉDICAS ======================= --}}
          <div class="form-section" style="margin-bottom: 24px;">
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
                    <label class="toggle-label" for="no-medical-${i}">Sin observaciones</label>
                    <p class="toggle-description">Mi mascota no tiene condiciones especiales</p>
                  </div>
                </div>
                <label class="modern-switch">
                  <input id="no-medical-${i}" type="checkbox" class="no-medical-toggle">
                  <span class="switch-slider"></span>
                </label>
              </div>

              <div class="form-group">
                <textarea name="pets[${i}][medical_conditions]" id="medical_conditions_${i}" rows="5" class="form-textarea"
                  placeholder="Ej: Alérgica a pollo. Toma medicamento para el corazón 2 veces al día. Puede ser nerviosa con extraños."></textarea>
              </div>
            </div>
          </div>

          {{-- ======================= CONTACTO DE EMERGENCIA ======================= --}}
          <div class="form-section" style="margin-bottom: 24px;">
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
                    <label class="toggle-label" for="has_emergency_contact_${i}">Habilitar Contacto de Emergencia</label>
                    <p class="toggle-description">Se mostrará como contacto secundario en caso de emergencia</p>
                  </div>
                </div>
                <input type="hidden" name="pets[${i}][has_emergency_contact]" value="0">
                <label class="modern-switch">
                  <input id="has_emergency_contact_${i}" type="checkbox" name="pets[${i}][has_emergency_contact]" value="1"
                         class="emergency-contact-toggle">
                  <span class="switch-slider"></span>
                </label>
              </div>

              <div id="emergency-fields-${i}" class="row g-4" style="display: none;">
                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label class="form-label">
                      <i class="fa-solid fa-user label-icon"></i>
                      Nombre del contacto
                    </label>
                    <input type="text" name="pets[${i}][emergency_contact_name]" class="form-input"
                           placeholder="Ej: María González">
                  </div>
                </div>

                <div class="col-12 col-md-6">
                  <div class="form-group">
                    <label class="form-label">
                      <i class="fa-solid fa-mobile-screen label-icon"></i>
                      Teléfono del contacto
                    </label>
                    <input type="text" name="pets[${i}][emergency_contact_phone]" class="form-input"
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

          {{-- ======================= FOTO PRINCIPAL ======================= --}}
          <div class="form-section" style="margin-bottom: 24px;">
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
                <div class="photo-preview-area" id="photoDrop-${i}">
                  <img id="photoPreview-${i}" src="" alt="Vista previa" class="d-none">
                  <div class="photo-placeholder">
                    <i class="fa-solid fa-image"></i>
                    <p>Arrastra una imagen aquí o haz clic en "Seleccionar imagen"</p>
                    <span>JPG, PNG · Máx. 4 MB</span>
                  </div>
                </div>

                <div class="photo-actions">
                  <input id="photo-${i}" name="pets[${i}][photo]" type="file" accept="image/*" class="d-none" required>
                  <label for="photo-${i}" class="btn-photo-action primary">
                    <i class="fa-solid fa-image"></i>
                    Seleccionar imagen
                  </label>
                  <button type="button" id="btnClearPhoto-${i}" class="btn-photo-action secondary">
                    <i class="fa-solid fa-xmark"></i>
                    Quitar
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;
        petsFormsContainer.appendChild(petCard);

        // Agregar funcionalidad JavaScript para esta mascota
        initializePetForm(i);
      }
    }

    // Inicializar funcionalidad de cada formulario
    function initializePetForm(petIndex) {
      // Toggle de contacto de emergencia
      const emergencyToggle = document.getElementById(`has_emergency_contact_${petIndex}`);
      const emergencyFields = document.getElementById(`emergency-fields-${petIndex}`);
      if (emergencyToggle) {
        emergencyToggle.addEventListener('change', function() {
          emergencyFields.style.display = this.checked ? 'block' : 'none';
        });
      }

      // Toggle de sin observaciones médicas
      const noMedicalToggle = document.getElementById(`no-medical-${petIndex}`);
      const medicalTextarea = document.getElementById(`medical_conditions_${petIndex}`);
      if (noMedicalToggle && medicalTextarea) {
        noMedicalToggle.addEventListener('change', function() {
          if (this.checked) {
            medicalTextarea.value = '';
            medicalTextarea.setAttribute('disabled', 'disabled');
            medicalTextarea.style.opacity = '0.5';
          } else {
            medicalTextarea.removeAttribute('disabled');
            medicalTextarea.style.opacity = '1';
          }
        });
      }

      // Preview de foto principal
      const photoInput = document.getElementById(`photo-${petIndex}`);
      const photoPreview = document.getElementById(`photoPreview-${petIndex}`);
      const photoDrop = document.getElementById(`photoDrop-${petIndex}`);
      const btnClearPhoto = document.getElementById(`btnClearPhoto-${petIndex}`);

      if (photoInput && photoPreview) {
        photoInput.addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
              photoPreview.src = e.target.result;
              photoPreview.classList.remove('d-none');
              photoDrop.querySelector('.photo-placeholder').style.display = 'none';
            };
            reader.readAsDataURL(file);
          }
        });

        btnClearPhoto.addEventListener('click', function() {
          photoInput.value = '';
          photoPreview.src = '';
          photoPreview.classList.add('d-none');
          photoDrop.querySelector('.photo-placeholder').style.display = 'block';
        });
      }

      // Inicializar selectores de ubicación CR
      initializeLocationSelectors(petIndex);
    }

    // Inicializar selectores de ubicación de Costa Rica
    function initializeLocationSelectors(petIndex) {
      const API = 'https://ubicaciones.paginasweb.cr';
      const $prov = document.getElementById(`cr-province-${petIndex}`);
      const $cant = document.getElementById(`cr-canton-${petIndex}`);
      const $dist = document.getElementById(`cr-district-${petIndex}`);
      const $zone = document.getElementById(`zone-${petIndex}`);
      const $preview = document.getElementById(`zone-preview-${petIndex}`);

      if (!$prov || !$cant || !$dist) return;

      // Cargar provincias
      fetch(`${API}/provincias.json`)
        .then(r => r.json())
        .then(data => {
          $prov.innerHTML = '<option value="">Selecciona provincia</option>';
          data.forEach(p => {
            $prov.innerHTML += `<option value="${p.id}">${p.nombre}</option>`;
          });
          $prov.disabled = false;
        });

      // Eventos de cascada
      $prov.addEventListener('change', function() {
        if (!this.value) {
          $cant.innerHTML = '<option value="">Selecciona cantón</option>';
          $dist.innerHTML = '<option value="">Selecciona distrito</option>';
          $cant.disabled = true;
          $dist.disabled = true;
          updateZone();
          return;
        }

        fetch(`${API}/provincia/${this.value}/cantones.json`)
          .then(r => r.json())
          .then(data => {
            $cant.innerHTML = '<option value="">Selecciona cantón</option>';
            data.forEach(c => {
              $cant.innerHTML += `<option value="${c.id}">${c.nombre}</option>`;
            });
            $cant.disabled = false;
            $dist.innerHTML = '<option value="">Selecciona distrito</option>';
            $dist.disabled = true;
            updateZone();
          });
      });

      $cant.addEventListener('change', function() {
        if (!this.value) {
          $dist.innerHTML = '<option value="">Selecciona distrito</option>';
          $dist.disabled = true;
          updateZone();
          return;
        }

        fetch(`${API}/canton/${this.value}/distritos.json`)
          .then(r => r.json())
          .then(data => {
            $dist.innerHTML = '<option value="">Selecciona distrito</option>';
            data.forEach(d => {
              $dist.innerHTML += `<option value="${d.id}">${d.nombre}</option>`;
            });
            $dist.disabled = false;
            updateZone();
          });
      });

      $dist.addEventListener('change', updateZone);

      function updateZone() {
        const provText = $prov.selectedOptions[0]?.text || '';
        const cantText = $cant.selectedOptions[0]?.text || '';
        const distText = $dist.selectedOptions[0]?.text || '';

        const parts = [provText, cantText, distText].filter(p => p && !p.startsWith('Selecciona'));
        const fullZone = parts.join(', ') || 'No seleccionada';

        $zone.value = fullZone;
        $preview.textContent = fullZone;
      }
    }

    // Validación antes de enviar el formulario
    const form = document.querySelector('form');
    if (form && sendInvitation) {
      form.addEventListener('submit', function(e) {
        if (sendInvitation.checked) {
          if (!pendingEmail.value || !pendingPlanId.value) {
            e.preventDefault();
            alert('Por favor completa el email del cliente y selecciona un plan antes de enviar la invitación.');
            return false;
          }

          // Validar que se hayan llenado los datos mínimos de todas las mascotas ADICIONALES
          const petNameInputs = document.querySelectorAll('input[name^="pets"][name$="[name]"]');
          const petBreedInputs = document.querySelectorAll('input[name^="pets"][name$="[breed]"]');
          const petZoneInputs = document.querySelectorAll('input[name^="pets"][name$="[zone]"]');

          let allPetsValid = true;

          petNameInputs.forEach((input) => {
            if (!input.value.trim()) {
              allPetsValid = false;
              input.style.borderColor = '#ef4444';
            } else {
              input.style.borderColor = '#d1d5db';
            }
          });

          petBreedInputs.forEach((input) => {
            if (!input.value.trim()) {
              allPetsValid = false;
              input.style.borderColor = '#ef4444';
            } else {
              input.style.borderColor = '#d1d5db';
            }
          });

          petZoneInputs.forEach((input) => {
            if (!input.value.trim()) {
              allPetsValid = false;
              input.style.borderColor = '#ef4444';
            } else {
              input.style.borderColor = '#d1d5db';
            }
          });

          if (!allPetsValid) {
            e.preventDefault();
            alert('Por favor completa el nombre, raza y zona de todas las mascotas adicionales antes de continuar.');
            return false;
          }
        }
      });
    }
  })();
</script>
@endif
@endpush
