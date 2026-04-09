{{-- resources/views/portal/pets/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Mascota')

@section('content')
<div class="saas-wrapper my-4">
  <div class="saas-settings-header mb-4">
    <a href="{{ route('portal.pets.show', $pet) }}" class="saas-back-btn"><i class="fa-solid fa-arrow-left"></i> Volver a la mascota</a>
    <h1 class="saas-settings-title">Editar Perfil</h1>
    <p class="saas-settings-subtitle">Gestiona toda la información pública y médica de tu mascota.</p>
  </div>

  @php
  $zDistrict = $zCanton = $zProvince = null;
  if ($pet->zone){
    $parts = array_map('trim', explode(',', $pet->zone));
    if (count($parts) === 3){ [$zDistrict, $zCanton, $zProvince] = $parts; }
  }
  $existingPhotos = $pet->photos ?? collect();
  $hasMain = !empty($pet->photo);
  @endphp

  <form action="{{ route('portal.pets.update', $pet) }}" method="POST" enctype="multipart/form-data" id="pet-form" class="saas-form-stack">
    @csrf @method('PUT')

    {{-- Card 1: Identidad --}}
    <div class="saas-settings-card">
      <div class="card-header-flex">
        <div class="icon-box"><i class="fa-solid fa-paw"></i></div>
        <div>
          <h2>Identidad</h2>
          <p>Información básica y reconocimiento.</p>
        </div>
      </div>
      <div class="card-body-stack">
        <div class="saas-form-group">
          <label class="saas-label">Nombre <span class="req">*</span></label>
          <input type="text" name="name" class="saas-input" value="{{ $pet->name }}" required>
        </div>

        <div class="grid-2-form">
          <div class="saas-form-group">
            <label class="saas-label">Raza <span class="req">*</span></label>
            <input type="text" name="breed" class="saas-input" value="{{ $pet->breed }}" required>
          </div>
          
          <div class="saas-form-group">
            <label class="saas-label">Edad <span class="req">*</span></label>
            <div class="age-inputs-mini">
              <div class="mini-input-wrap">
                <input type="number" name="age_years" min="0" max="50" class="saas-input" placeholder="Años" id="ageYearsInputEdit" value="{{ old('age_years', $pet->age_years) }}">
                <span>Años</span>
              </div>
              <div class="mini-input-wrap">
                <input type="number" name="age_months" min="0" max="11" class="saas-input" placeholder="Meses" id="ageMonthsInputEdit" value="{{ old('age_months', $pet->age_months) }}">
                <span>Meses</span>
              </div>
            </div>
          </div>
        </div>

        <div class="saas-form-group mb-0">
          <label class="saas-label">Sexo</label>
          @php $sex = $pet->sex ?? 'unknown'; @endphp
          <div class="saas-segmented-control">
            <input type="radio" id="sex_m" name="sex" value="male" class="seg" {{ $sex==='male' ? 'checked' : '' }}>
            <label for="sex_m"><i class="fa-solid fa-mars"></i> Macho</label>

            <input type="radio" id="sex_f" name="sex" value="female" class="seg" {{ $sex==='female' ? 'checked' : '' }}>
            <label for="sex_f"><i class="fa-solid fa-venus"></i> Hembra</label>

            <input type="radio" id="sex_u" name="sex" value="unknown" class="seg" {{ $sex==='unknown' ? 'checked' : '' }}>
            <label for="sex_u"><i class="fa-solid fa-circle-question"></i> Desconocido</label>
          </div>
        </div>
      </div>
    </div>

    {{-- Card 2: Salud y Condiciones --}}
    <div class="saas-settings-card">
      <div class="card-header-flex">
        <div class="icon-box"><i class="fa-solid fa-notes-medical"></i></div>
        <div>
          <h2>Salud y Estado Médico</h2>
          <p>Condiciones y estatus veterinario.</p>
        </div>
      </div>
      <div class="card-body-stack">
        <div class="saas-toggle-row">
          <div>
            <label class="saas-label-title">Mascota Esterilizada</label>
            <p class="saas-helper">Indica si tu mascota ha sido castrada/esterilizada.</p>
          </div>
          <input type="hidden" name="is_neutered" value="0">
          <label class="ios-switch">
             <input type="checkbox" name="is_neutered" value="1" {{ old('is_neutered', (int)$pet->is_neutered) ? 'checked' : '' }}>
             <span class="slider"></span>
          </label>
        </div>
        <hr class="saas-divider">
        <div class="saas-toggle-row">
          <div>
            <label class="saas-label-title">Vacuna Antirrábica</label>
            <p class="saas-helper">Certifica que cuenta con la vacuna de la rabia al día.</p>
          </div>
          <input type="hidden" name="rabies_vaccine" value="0">
          <label class="ios-switch">
             <input type="checkbox" name="rabies_vaccine" value="1" {{ old('rabies_vaccine', (int)$pet->rabies_vaccine) ? 'checked' : '' }}>
             <span class="slider"></span>
          </label>
        </div>
        <hr class="saas-divider">
        
        <div class="saas-form-group mb-0">
          <div class="d-flex justify-content-between align-items-center mb-2">
             <label class="saas-label mb-0">Observaciones y Condiciones Especiales</label>
             @php $noMed = empty($pet->medical_conditions); @endphp
             <div class="d-flex align-items-center gap-2">
               <span class="saas-helper m-0">Sin notas</span>
               <label class="ios-switch mini" style="transform: scale(0.8); transform-origin: right center;">
                 <input id="no-medical" type="checkbox" {{ $noMed ? 'checked' : '' }}>
                 <span class="slider"></span>
               </label>
             </div>
          </div>
          <p class="saas-helper mb-2">Alergias, medicación, discapacidades o notas por si se extravía.</p>
          <textarea name="medical_conditions" id="medical_conditions" class="saas-input textarea" rows="4" placeholder="Ej: Es alérgico al pollo..." {{ $noMed ? 'disabled' : '' }}>{{ $pet->medical_conditions }}</textarea>
        </div>
      </div>
    </div>

    {{-- Card 3: Ubicación y Emergencia --}}
    <div class="saas-settings-card">
      <div class="card-header-flex">
        <div class="icon-box"><i class="fa-solid fa-map-location-dot"></i></div>
        <div>
          <h2>Ubicación y SOS</h2>
          <p>Datos en caso de extravío y contacto alternativo.</p>
        </div>
      </div>
      <div class="card-body-stack">
        <div class="saas-form-group">
          <label class="saas-label">Provincia, Cantón y Distrito <span class="req">*</span></label>
          <div class="grid-3-form" id="cr-geo" data-current-province="{{ $zProvince }}" data-current-canton="{{ $zCanton }}" data-current-district="{{ $zDistrict }}">
             <div class="select-wrapper">
               <i class="fa-solid fa-map select-icon"></i>
               <select id="cr-province" class="saas-select" disabled><option value="">Provincia</option></select>
             </div>
             <div class="select-wrapper">
               <i class="fa-solid fa-map select-icon"></i>
               <select id="cr-canton" class="saas-select" disabled><option value="">Cantón</option></select>
             </div>
             <div class="select-wrapper">
               <i class="fa-solid fa-map select-icon"></i>
               <select id="cr-district" class="saas-select" disabled><option value="">Distrito</option></select>
             </div>
          </div>
          <input type="hidden" name="zone" id="zone" value="{{ $pet->zone }}">
          <div class="saas-helper mt-2">Visibilidad final: <strong id="zone-preview">{{ $pet->zone ?: '—' }}</strong></div>
        </div>

        <hr class="saas-divider">

        <div class="saas-toggle-row align-items-start">
          <div>
            <label class="saas-label-title">Contacto de Emergencia <span class="badge saas-badge-soft-blue ms-1">Opcional</span></label>
            <p class="saas-helper mt-1 mb-0">Habilita un contacto secundario que aparecerá en el perfil público.</p>
          </div>
          @php $hasEmergency = (bool)($pet->has_emergency_contact ?? false); @endphp
          <input type="hidden" name="has_emergency_contact" value="0">
          <label class="ios-switch mt-1">
             <input id="has_emergency_contact" name="has_emergency_contact" type="checkbox" value="1" {{ $hasEmergency ? 'checked' : '' }} onchange="toggleEmergencyFieldsEdit()">
             <span class="slider"></span>
          </label>
        </div>

        <div id="emergency-fields-edit" class="saas-sub-panel mt-3" style="display: {{ $hasEmergency ? 'block' : 'none' }}; flex-direction:column; gap:1rem;">
          <div class="grid-2-form">
            <div class="saas-form-group mb-0">
               <label class="saas-label">Nombre del contacto</label>
               <input type="text" name="emergency_contact_name" class="saas-input" value="{{ old('emergency_contact_name', $pet->emergency_contact_name ?? '') }}" placeholder="Ej: María González">
            </div>
            <div class="saas-form-group mb-0">
               <label class="saas-label">Teléfono</label>
               <input type="text" name="emergency_contact_phone" class="saas-input" value="{{ old('emergency_contact_phone', $pet->emergency_contact_phone ?? '') }}" placeholder="+506 8888-8888">
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Card 4: Galería --}}
    <div class="saas-settings-card">
      <div class="card-header-flex">
        <div class="icon-box"><i class="fa-solid fa-camera"></i></div>
        <div>
          <h2>Galería de Fotos</h2>
          <p>Foto principal y registro visual.</p>
        </div>
      </div>
      <div class="card-body-stack">
        <div class="saas-form-group">
          <label class="saas-label">Foto de Perfil Principal <span class="req">*</span></label>
          <div class="d-flex align-items-center gap-3 flex-wrap mt-2">
            <div class="saas-main-avatar-wrapper" id="mainPreview">
              @if($hasMain)
                <img id="photoPreview" src="{{ asset('storage/'.$pet->photo) }}" alt="Foto principal">
              @else
                <img id="photoPreview" src="" alt="Foto principal" class="d-none">
                <div id="mainPlaceholder" class="saas-avatar-placeholder"><i class="fa-solid fa-image"></i></div>
              @endif
            </div>
            <div class="saas-avatar-actions">
               <label for="photo" class="saas-btn-light saas-btn-sm m-0"><i class="fa-solid fa-upload"></i> Cambiar imagen</label>
               <input id="photo" name="photo" type="file" accept="image/*" class="d-none">
               <button type="button" id="btnClearPhoto" class="saas-btn-danger saas-btn-sm"><i class="fa-solid fa-trash"></i></button>
               <p class="saas-helper mt-2 mb-0">Formatos: JPG/PNG. Máx 4MB.</p>
            </div>
          </div>
        </div>

        <hr class="saas-divider">

        <div class="saas-form-group mb-0">
          <div class="d-flex justify-content-between align-items-center mb-2">
             <label class="saas-label mb-0">Fotos Adicionales</label>
             <span class="badge bg-light text-dark fw-bold border" id="photosCounter">0 / 3</span>
          </div>
          <p class="saas-helper mb-3">Sube hasta 3 fotos extras para mostrar diferentes ángulos, manchas únicas o tamaño.</p>
          
          {{-- existentes --}}
          <div id="existingGrid" class="saas-existing-grid mb-3">
             @foreach(($pet->photos ?? collect()) as $ph)
             <div class="existing-card" data-id="{{ $ph->id }}">
               <img src="{{ Storage::url($ph->path) }}" alt="Foto adicional">
               <button type="button" class="btn-remove-photo" title="Eliminar" data-photo-id="{{ $ph->id }}"><i class="fa-solid fa-xmark"></i></button>
             </div>
             @endforeach
          </div>
          <input type="hidden" name="delete_photos" id="deletePhotos" value="">

          {{-- Dropzone nueva --}}
          <div class="saas-dropzone" id="dz-photos">
            <input type="file" id="photos" name="photos[]" accept="image/*" multiple>
            <div class="dz-content">
               <i class="fa-solid fa-cloud-arrow-up"></i>
               <span>Arrastra y suelta fotos adicionales aquí</span>
               <small>O haz clic para seleccionar (Máx 6MB c/u)</small>
            </div>
          </div>

          <div id="photosPreviewGrid" class="saas-preview-grid mt-3 d-none"></div>
          
          <div class="mt-3">
             <button type="button" id="btnClearPhotos" class="saas-btn-danger saas-btn-sm d-none"><i class="fa-solid fa-trash-can"></i> Limpiar nuevas subidas</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Barra Sticky Guardar -->
<div class="saas-sticky-action-bar">
   <div class="saas-wrapper-action d-flex justify-content-between align-items-center h-100 px-3 mx-auto" style="max-width: 800px;">
      <div class="d-none d-sm-block text-muted fw-bold text-truncate me-3"><i class="fa-solid fa-pen"></i> Editando: {{ $pet->name }}</div>
      <div class="d-flex gap-2 w-100 justify-content-end align-items-center">
         <a href="{{ route('portal.pets.show', $pet) }}" class="saas-btn-light fw-bold text-decoration-none">Cancelar</a>
         <button onclick="document.getElementById('pet-form').requestSubmit();" class="saas-btn-primary">
            <i class="fa-solid fa-check"></i> Guardar Cambios
         </button>
      </div>
   </div>
</div>

<!-- Pantalla de carga genérica conservada -->
<div id="loading-overlay">
  <div class="loading-spinner"></div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
/* SAAS SETTINGS CORE UI */
:root {
  --saas-border: #E2E8F0;
  --saas-surface: #ffffff;
  --saas-bg: #F8FAFC;
  --saas-text: #0F172A;
  --saas-text-muted: #64748B;
  --saas-primary: #2563EB;
  --saas-danger: #EF4444;
  --saas-green: #10B981;
}

body { background: var(--saas-bg); }

.saas-wrapper {
  max-width: 800px;
  margin: 0 auto;
}

.saas-settings-header { margin-top: 1rem; }
.saas-settings-title { font-size: 1.8rem; font-weight: 800; color: var(--saas-text); margin-bottom: 0.25rem; letter-spacing: -0.02em; }
.saas-settings-subtitle { font-size: 0.95rem; color: var(--saas-text-muted); font-weight: 500; }
.saas-back-btn { font-size: 0.85rem; font-weight: 700; color: var(--saas-text-muted); text-decoration: none; margin-bottom: 1rem; display: inline-flex; align-items: center; gap: 0.4rem; transition: color 0.2s;}
.saas-back-btn:hover { color: var(--saas-primary); }

.saas-form-stack { display: flex; flex-direction: column; gap: 1.5rem; padding-bottom: 6rem; }

/* Cards */
.saas-settings-card {
  background: var(--saas-surface);
  border: 1px solid var(--saas-border);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 10px 15px -3px rgba(0,0,0,0.01);
  animation: slideUp 0.4s ease forwards;
  opacity: 0;
  transform: translateY(10px);
}
@keyframes slideUp { to { opacity: 1; transform: translateY(0); } }

.card-header-flex {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--saas-border);
  display: flex;
  align-items: center;
  gap: 1rem;
  background: #fcfcfd;
}
.card-header-flex .icon-box {
  width: 38px; height: 38px;
  background: #eff6ff; color: var(--saas-primary);
  border-radius: 10px; display: flex; align-items: center; justify-content: center;
  font-size: 1rem;
}
.card-header-flex h2 { font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--saas-text); }
.card-header-flex p { font-size: 0.85rem; color: var(--saas-text-muted); margin: 0; font-weight: 500; }

.card-body-stack {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

/* Forms */
.saas-form-group { display: flex; flex-direction: column; gap: 0.4rem; }
.saas-label { font-size: 0.85rem; font-weight: 700; color: var(--saas-text); margin-bottom: 0px;}
.saas-label .req { color: var(--saas-danger); }

.saas-input, .saas-select {
  width: 100%;
  padding: 0.65rem 1rem;
  background: #fff;
  border: 1px solid #CBD5E1;
  border-radius: 10px;
  font-size: 0.95rem;
  color: var(--saas-text);
  font-weight: 500;
  transition: all 0.2s;
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.01);
}
.saas-input:focus, .saas-select:focus {
  outline: none;
  border-color: var(--saas-primary);
  box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
}
.saas-input:disabled, .saas-select:disabled { background: #F1F5F9; color: #94A3B8; cursor: not-allowed; }
.textarea { resize: vertical; min-height: 100px; }

/* Grid Utilities */
.grid-2-form { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
.grid-3-form { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
@media (max-width: 640px) {
  .grid-2-form, .grid-3-form { grid-template-columns: 1fr; }
}

/* Extras */
.saas-helper { font-size: 0.8rem; color: var(--saas-text-muted); font-weight: 500; margin-top: 0.2rem; }
.saas-divider { margin: 0.5rem 0; border: 0; border-top: 1px solid var(--saas-border); }

/* Switches */
.saas-toggle-row { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
.saas-label-title { font-size: 0.95rem; font-weight: 700; color: var(--saas-text); margin-bottom: 0px;}

.ios-switch { position: relative; display: inline-flex; width: 44px; height: 26px; cursor: pointer; flex-shrink:0; }
.ios-switch input { opacity: 0; width: 0; height: 0; }
.ios-switch .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #CBD5E1; transition: .3s cubic-bezier(0.4, 0.0, 0.2, 1); border-radius: 34px; }
.ios-switch .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; transition: .3s cubic-bezier(0.4, 0.0, 0.2, 1); border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
.ios-switch input:checked + .slider { background-color: var(--saas-primary); }
.ios-switch input:checked + .slider:before { transform: translateX(18px); }

.saas-badge-soft-blue { background: #eff6ff; color: var(--saas-primary); border: 1px solid #bfdbfe; font-size: 0.65rem; padding: 0.2rem 0.5rem; text-transform: uppercase; font-weight: 800;}

/* Segmented */
.saas-segmented-control { display: inline-flex; background: var(--saas-bg); padding: 4px; border-radius: 12px; border: 1px solid var(--saas-border); width: 100%;}
.saas-segmented-control .seg { display: none; }
.saas-segmented-control label { flex: 1; text-align: center; padding: 0.5rem; font-size: 0.85rem; font-weight: 600; color: var(--saas-text-muted); cursor: pointer; border-radius: 8px; transition: all 0.2s; margin-bottom: 0px;}
.saas-segmented-control .seg:checked + label { background: white; color: var(--saas-primary); box-shadow: 0 1px 3px rgba(0,0,0,0.05); font-weight: 700;}

/* Age Inputs */
.age-inputs-mini { display: flex; gap: 0.5rem; }
.mini-input-wrap { display: flex; align-items: center; background: #fff; border: 1px solid #CBD5E1; border-radius: 10px; overflow:hidden; flex:1; transition: border-color 0.2s; }
.mini-input-wrap:focus-within { border-color: var(--saas-primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.15);}
.mini-input-wrap input { width: 100%; border: none; padding: 0.65rem; background:transparent; outline:none; font-weight:600; min-width: 40px;}
.mini-input-wrap span { padding-right: 0.8rem; font-size: 0.8rem; font-weight: 600; color:#94A3B8; }

/* Sub Panel */
.saas-sub-panel { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 1rem; }

/* Select wrapper */
.select-wrapper { position: relative; }
.select-wrapper .select-icon { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 0.9rem;}
.select-wrapper .saas-select { padding-left: 2.2rem; }

/* Fotos Main Avatar */
.saas-main-avatar-wrapper { width: 100px; height: 100px; border-radius: 16px; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; position: relative; display:flex; align-items:center; justify-content:center; flex-shrink:0;}
.saas-main-avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }
.saas-avatar-placeholder { font-size: 2rem; color: #cbd5e1; }
.saas-avatar-actions { display: flex; flex-direction: column; gap: 0.5rem; align-items: flex-start;}

/* Botones genéricos */
.saas-btn-light { background: #fff; border: 1px solid #e2e8f0; color: #334155; padding: 0.65rem 1.25rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s; display: inline-flex; align-items:center; gap:0.4rem; font-size:0.9rem;}
.saas-btn-light:hover { background: #f8fafc; border-color: #cbd5e1; }
.saas-btn-primary { background: var(--saas-primary); border: none; color: white; padding: 0.65rem 1.25rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s; display: inline-flex; align-items:center; gap:0.4rem; font-size:0.9rem; box-shadow: 0 4px 10px rgba(37,99,235,0.2);}
.saas-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(37,99,235,0.3); filter:brightness(1.05);}
.saas-btn-danger { background: #fef2f2; border: 1px solid #fee2e2; color: var(--saas-danger); padding: 0.65rem 1.25rem; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s; display: inline-flex; align-items:center; justify-content:center; gap:0.4rem; font-size:0.9rem;}
.saas-btn-danger:hover { background: var(--saas-danger); color: white; }
.saas-btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; }

/* Existing Extra Grid */
.saas-existing-grid { display: flex; flex-wrap: wrap; gap: 0.75rem; }
.existing-card { width: 80px; height: 80px; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; position: relative; }
.existing-card img { width: 100%; height: 100%; object-fit: cover; }
.btn-remove-photo { position: absolute; top: 2px; right: 2px; width: 22px; height: 22px; background: rgba(0,0,0,0.6); color: white; border: none; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; cursor: pointer; }
.btn-remove-photo:hover { background: #ef4444; }

/* Dropzone */
.saas-dropzone { border: 2px dashed #cbd5e1; border-radius: 16px; background: #f8fafc; padding: 1.5rem; text-align: center; position: relative; transition: 0.2s; cursor: pointer; }
.saas-dropzone.hover { border-color: var(--saas-primary); background: #eff6ff; }
.saas-dropzone input[type=file] { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;}
.dz-content { pointer-events: none; }
.dz-content i { font-size: 2rem; color: #94a3b8; margin-bottom: 0.5rem; display:block;}
.dz-content span { display: block; font-weight: 700; color: var(--saas-text); font-size: 0.95rem; }
.dz-content small { color: #94a3b8; font-size: 0.8rem; }

.saas-preview-grid { display: flex; flex-wrap: wrap; gap: 0.75rem; }
.saas-preview-grid .ph { position: relative; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0; width: 80px; height: 80px; }
.saas-preview-grid .ph img { width: 100%; height: 100%; object-fit: cover; }
.saas-preview-grid .ph-remove { position: absolute; top: 2px; right: 2px; border: none; border-radius: 50%; width: 20px; height: 20px; background: rgba(0,0,0,0.6); color: white; display:flex; align-items:center; justify-content:center; font-size:0.7rem; cursor:pointer;}

/* Sticky Bottom Bar */
.saas-sticky-action-bar {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 64px;
  background: rgba(255, 255, 255, 0.85);
  backdrop-filter: blur(12px);
  border-top: 1px solid #E2E8F0;
  z-index: 100;
  box-shadow: 0 -4px 20px rgba(0,0,0,0.02);
}

/* Loading */
#loading-overlay { display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.8); backdrop-filter: blur(4px); z-index: 9999; justify-content: center; align-items: center; }
#loading-overlay.active { display: flex; }
.loading-spinner { border: 4px solid #f3f3f3; border-top: 4px solid var(--saas-primary); border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
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
    if($noMedical) $noMedical.addEventListener('change', toggleMedical);

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
        const wrap = $prov.closest('.grid-3-form');
        if(wrap){
            wrap.outerHTML = `
            <div class="saas-form-group">
            <div class="saas-helper mb-2 text-warning fw-bold">No se pudo automatizar. Ingresa la zona.</div>
            <input class="saas-input" value="{{ $pet->zone }}" placeholder="Ej: San Juan, Grecia, Alajuela"
                    oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
            </div>`;
        }
      }
    })();

    if($prov){
        $prov.addEventListener('change', async () => {
        $cant.disabled = true;
        $dist.disabled = true;
        $cant.innerHTML = `<option value="">Cantón</option>`;
        $dist.innerHTML = `<option value="">Distrito</option>`;
        $zone.value = '';
        if($zonePreview) $zonePreview.textContent = '—';
        if (!$prov.value) return;
        const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
        fillSelect($cant, cantones, 'Cantón');
        $cant.disabled = false;
        });
    }
    if($cant){
        $cant.addEventListener('change', async () => {
        $dist.disabled = true;
        $dist.innerHTML = `<option value="">Distrito</option>`;
        $zone.value = '';
        if($zonePreview) $zonePreview.textContent = '—';
        if (!$prov.value || !$cant.value) return;
        const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
        fillSelect($dist, distritos, 'Distrito');
        $dist.disabled = false;
        });
    }
    if($dist) $dist.addEventListener('change', setZone);
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
    if(input) input.addEventListener('change', e => show(e.target.files[0]));
    if(clear) clear.addEventListener('click', () => {
      preview.src = '';
      preview.classList.add('d-none');
      input.value = '';
      if (ph) {
        ph.style.display = 'flex';
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
        if(!existingGrid) return 0;
        return existingGrid.querySelectorAll('.existing-card').length;
    }

    const removed = new Set();

    function syncDeleteInput() {
      if(deleteInput) deleteInput.value = Array.from(removed).join(',');
    }

    if(existingGrid){
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
    }

    let filesBuffer = [];

    function refreshNewGrid() {
      if(!grid) return;
      grid.innerHTML = '';
      if (filesBuffer.length === 0) {
        grid.classList.add('d-none');
        if(btnClear) btnClear.classList.add('d-none');
        return;
      }
      grid.classList.remove('d-none');
      if(btnClear) btnClear.classList.remove('d-none');
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
      if(!input) return;
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
      if(counter) counter.textContent = `${existingAliveCount() + filesBuffer.length} / ${MAX}`;
    }

    if(input){
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
    }

    if(dropzone){
        ['dragenter', 'dragover'].forEach(evt => dropzone.addEventListener(evt, e => {
        e.preventDefault();
        dropzone.classList.add('hover');
        }));
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
    }

    if(btnClear){
        btnClear.addEventListener('click', () => {
        filesBuffer = [];
        applyBufferToInput();
        refreshNewGrid();
        refreshCounter();
        });
    }

    const form = document.getElementById('pet-form');
    if(form){
        form.addEventListener('submit', (e) => {
        const previewEl = document.getElementById('photoPreview');
        const hasMainNow = !!previewEl && !!previewEl.src && !previewEl.classList.contains('d-none');
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

        // Mostrar pantalla de carga
        const inputUploads = input ? input.files.length : 0;
        const mainUpload = document.getElementById('photo') ? document.getElementById('photo').files.length : 0;
        const hasNewPhotos = filesBuffer.length > 0 || inputUploads > 0 || mainUpload > 0;
        if (hasNewPhotos) {
            document.getElementById('loading-overlay').classList.add('active');
        }
        });
    }

    refreshCounter();
  })();

  // Toggle emergency contact fields
  function toggleEmergencyFieldsEdit() {
    const toggle = document.getElementById("has_emergency_contact");
    const fields = document.getElementById("emergency-fields-edit");
    if (!toggle || !fields) return;
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
  // Initialize
  toggleEmergencyFieldsEdit();

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
