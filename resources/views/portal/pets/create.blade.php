{{-- resources/views/portal/pets/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Nueva Mascota')

@section('content')
<div class="container my-4">
  <h1 class="h3 mb-3 fw-bold">Nueva Mascota</h1>

  <div class="card card-elevated">
    <div class="card-body">
      <form action="{{ route('portal.pets.store') }}" method="POST" enctype="multipart/form-data" id="pet-form">
        @csrf

        {{-- ======================= DATOS BÁSICOS ======================= --}}
        <div class="section-card">
          <div class="section-header">
            <div class="section-icon bg-primary-subtle text-primary"><i class="fa-solid fa-paw"></i></div>
            <div>
              <h2 class="section-title">Datos básicos</h2>
              <div class="section-sub">Nombre, raza y sexo de tu mascota.</div>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-12 col-lg-6">
              <label class="form-label">Nombre *</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-12 col-lg-6">
              <label class="form-label">Raza</label>
              <input type="text" name="breed" class="form-control" placeholder="Labrador, Poodle, etc.">
            </div>

            {{-- Sexo (segmented) --}}
            <div class="col-12">
              <label class="form-label d-block mb-2">Sexo</label>
              <div class="segmented">
                <input type="radio" id="sex_m" name="sex" value="male" class="seg" checked>
                <label for="sex_m"><i class="fa-solid fa-mars me-1"></i> Macho</label>

                <input type="radio" id="sex_f" name="sex" value="female" class="seg">
                <label for="sex_f"><i class="fa-solid fa-venus me-1"></i> Hembra</label>

                <input type="radio" id="sex_u" name="sex" value="unknown" class="seg">
                <label for="sex_u"><i class="fa-solid fa-circle-question me-1"></i> Desconocido</label>
              </div>
            </div>
          </div>
        </div>

        {{-- ======================= SALUD ======================= --}}
        <div class="section-card">
          <div class="section-header">
            <div class="section-icon bg-success-subtle text-success"><i class="fa-solid fa-stethoscope"></i></div>
            <div>
              <h2 class="section-title">Salud</h2>
              <div class="section-sub">Esterilización, vacunas y edad.</div>
            </div>
          </div>

          <div class="row g-3">
            {{-- Esterilizado --}}
            <div class="col-12 col-sm-6">
              <div class="form-row">
                <label class="mb-0" for="is_neutered">Esterilizado</label>

                {{-- Fallback para enviar "0" cuando el toggle está apagado --}}
                <input type="hidden" name="is_neutered" value="0">

                <label class="ft-switch" aria-label="Esterilizado">
                  <input id="is_neutered" type="checkbox" name="is_neutered" value="1"
                    {{ old('is_neutered') ? 'checked' : '' }}>
                  <span class="track"><span class="thumb"></span></span>
                </label>
              </div>
            </div>

            {{-- Vacuna antirrábica --}}
            <div class="col-12 col-sm-6">
              <div class="form-row">
                <label class="mb-0" for="rabies_vaccine">Vacuna antirrábica</label>

                {{-- Fallback para enviar "0" cuando el toggle está apagado --}}
                <input type="hidden" name="rabies_vaccine" value="0">

                <label class="ft-switch" aria-label="Vacuna antirrábica">
                  <input id="rabies_vaccine" type="checkbox" name="rabies_vaccine" value="1"
                    {{ old('rabies_vaccine') ? 'checked' : '' }}>
                  <span class="track"><span class="thumb"></span></span>
                </label>
              </div>
            </div>
          </div>
        </div>


        {{-- Edad --}}
        <div class="col-12 col-sm-6">
          <label class="form-label">Edad</label>
          <div class="input-icon">
            <i class="fa-solid fa-cake-candles"></i>
            <input type="number" name="age" min="0" max="50" class="form-control" placeholder="0">
          </div>
        </div>
    </div>
  </div>

  {{-- ======================= UBICACIÓN ======================= --}}
  <div class="section-card">
    <div class="section-header">
      <div class="section-icon bg-info-subtle text-info"><i class="fa-solid fa-location-dot"></i></div>
      <div>
        <h2 class="section-title">Ubicación</h2>
        <div class="section-sub">Se utiliza para mostrar la zona en el perfil público.</div>
      </div>
    </div>

    <div class="row g-3" id="cr-geo"
      data-current-province=""
      data-current-canton=""
      data-current-district="">
      <div class="col-12">
        <label class="form-label">Provincia</label>
        <div class="input-icon">
          <i class="fa-solid fa-map"></i>
          <select id="cr-province" class="form-select" aria-label="Provincia" disabled>
            <option value="">Provincia</option>
          </select>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">Cantón</label>
        <div class="input-icon">
          <i class="fa-solid fa-map"></i>
          <select id="cr-canton" class="form-select" aria-label="Cantón" disabled>
            <option value="">Cantón</option>
          </select>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label">Distrito</label>
        <div class="input-icon">
          <i class="fa-solid fa-map"></i>
          <select id="cr-district" class="form-select" aria-label="Distrito" disabled>
            <option value="">Distrito</option>
          </select>
        </div>
      </div>

      <input type="hidden" name="zone" id="zone" value="">
      <div class="col-12">
        <div class="d-flex align-items-center justify-content-between gap-3">
          <small class="text-muted">Se guardará como:</small>
          <code id="zone-preview">—</code>
        </div>
      </div>
    </div>
  </div>

  {{-- ======================= OBSERVACIONES ======================= --}}
  <div class="section-card">
    <div class="section-header">
      <div class="section-icon bg-secondary-subtle text-secondary"><i class="fa-solid fa-notes-medical"></i></div>
      <div>
        <h2 class="section-title">Observaciones</h2>
        <div class="section-sub">Alergias, medicación, comportamiento, etc.</div>
      </div>
    </div>

    <div class="form-row mb-2">
      <label class="mb-0" for="no-medical">Sin observaciones</label>
      <label class="ft-switch" aria-label="Sin observaciones">
        <input id="no-medical" type="checkbox">
        <span class="track"><span class="thumb"></span></span>
      </label>
    </div>

    <textarea name="medical_conditions" id="medical_conditions" rows="4" class="form-control"
      placeholder="Ej: Alérgica a pollo. Toma medicamento 2 veces al día."></textarea>
  </div>

  {{-- ======================= FOTOS (múltiples) ======================= --}}
  <div class="section-card">
    <div class="section-header">
      <div class="section-icon bg-warning-subtle text-warning"><i class="fa-solid fa-images"></i></div>
      <div>
        <h2 class="section-title">Fotos</h2>
        <div class="section-sub">Puedes seleccionar varias (máx. 3 adicionales).</div>
      </div>
    </div>

    <input type="file" id="photos" name="photos[]" class="form-control" multiple accept="image/*">
    <div class="form-text">Formatos: JPG/PNG. Tamaño máx. 6 MB por imagen.</div>

    {{-- Previews en cuadrícula --}}
    <div id="photosPreviewGrid" class="mt-3 photos-grid d-none"></div>
    <button type="button" id="btnClearPhotos" class="btn btn-outline-danger btn-sm mt-2 d-none">
      <i class="fa-solid fa-xmark me-1"></i> Quitar todas
    </button>
  </div>

  {{-- ======================= FOTO PRINCIPAL (legacy) ======================= --}}
  <div class="section-card">
    <div class="section-header">
      <div class="section-icon bg-light text-body-tertiary"><i class="fa-regular fa-image"></i></div>
      <div>
        <h2 class="section-title">Foto principal (sistema antiguo)</h2>
        <div class="section-sub">Opcional. El recorte es solo de vista previa.</div>
      </div>
    </div>

    <div class="photo-uploader">
      <div class="photo-uploader__preview" id="photoDrop">
        <img id="photoPreview" src="" alt="Vista previa" class="d-none">
        <div class="photo-uploader__overlay">Arrastra una imagen o haz clic en “Seleccionar imagen”.</div>
      </div>
      <div class="photo-uploader__actions">
        <label for="photo" class="btn btn-outline-primary">
          <i class="fa-solid fa-image me-1"></i> Seleccionar imagen
        </label>
        <input id="photo" name="photo" type="file" accept="image/*" class="d-none" required>
        <button type="button" id="btnClearPhoto" class="btn btn-outline-danger">
          <i class="fa-solid fa-xmark me-1"></i> Quitar
        </button>
      </div>
    </div>
    <small class="text-muted d-block mt-2">
      Formatos: JPG/PNG. Tamaño máx. 4 MB.
    </small>
  </div>

  <div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Guardar</button>
    <a href="{{ route('portal.pets.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </div>
  </form>
</div>
</div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
  /* ===== Cards/secciones ===== */
  .card-elevated {
    border: 0;
    box-shadow: 0 10px 30px rgba(31, 41, 55, .06);
  }

  .section-card {
    border: 1px solid #eef1f5;
    border-radius: 16px;
    padding: 18px;
    margin-bottom: 18px;
    background: #fff;
  }

  .section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
  }

  .section-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    font-size: 18px;
  }

  .section-title {
    font-weight: 800;
    margin: 0
  }

  .section-sub {
    color: #6b7280;
    font-size: .95rem
  }

  /* ===== Alineación label + toggle (móvil-friendly) ===== */
  .form-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 8px 0;
  }

  /* ===== Switch accesible y responsive ===== */
  .ft-switch {
    position: relative;
    display: inline-flex;
    width: 52px;
    height: 30px;
    flex: 0 0 auto;
    cursor: pointer;
  }

  .ft-switch input {
    position: absolute;
    inline-size: 100%;
    block-size: 100%;
    opacity: 0;
    margin: 0;
    cursor: pointer;
  }

  .ft-switch .track {
    position: relative;
    inline-size: 100%;
    block-size: 100%;
    background: #e5e7eb;
    border-radius: 999px;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, .12);
    transition: background .2s ease;
  }

  .ft-switch .thumb {
    position: absolute;
    inset-block-start: 50%;
    inset-inline-start: 3px;
    transform: translateY(-50%);
    inline-size: 24px;
    block-size: 24px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
    transition: left .2s ease, inset-inline-start .2s ease;
  }

  .ft-switch input:checked+.track {
    background: #2563eb;
  }

  .ft-switch input:checked+.track .thumb {
    inset-inline-start: calc(100% - 27px);
  }

  /* Compactar un poco en pantallas muy pequeñas */
  @media (max-width: 480px) {
    .ft-switch {
      width: 46px;
      height: 26px;
    }

    .ft-switch .thumb {
      inline-size: 20px;
      block-size: 20px;
      inset-inline-start: 3px;
    }

    .ft-switch input:checked+.track .thumb {
      inset-inline-start: calc(100% - 23px);
    }
  }

  /* ===== Segmented control (sexo) ===== */
  .segmented {
    display: inline-grid;
    grid-auto-flow: column;
    gap: 6px;
    background: #f6f7fb;
    padding: 6px;
    border-radius: 12px;
    border: 1px solid #eef1f5;
  }

  .segmented .seg {
    display: none;
  }

  .segmented label {
    padding: .45rem .8rem;
    border-radius: 10px;
    cursor: pointer;
    user-select: none;
    color: #374151;
    background: transparent;
  }

  .segmented .seg:checked+label {
    background: #115DFC;
    color: #fff;
    font-weight: 700;
  }

  /* ===== Inputs con icono ===== */
  .input-icon {
    position: relative
  }

  .input-icon>i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aa0aa;
  }

  .input-icon>.form-control,
  .input-icon>.form-select {
    padding-left: 40px
  }

  /* ===== Previews grid de fotos múltiples ===== */
  .photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: .75rem;
  }

  .photos-grid .ph {
    position: relative;
    border: 1px solid #e5e7eb;
    border-radius: .5rem;
    overflow: hidden;
    background: #f8fafc;
    aspect-ratio: 1 / 1;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .photos-grid .ph img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .photos-grid .ph .ph-remove {
    position: absolute;
    top: .35rem;
    right: .35rem;
    border: 0;
    border-radius: 999px;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, .55);
    color: #fff;
  }

  /* ===== Uploader principal (legacy) ===== */
  .photo-uploader {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 12px;
    align-items: center;
  }

  .photo-uploader__preview {
    border: 1px dashed #e5e7eb;
    border-radius: 12px;
    background: #f8fafc;
    min-height: 160px;
    display: grid;
    place-items: center;
    position: relative;
    overflow: hidden;
  }

  .photo-uploader__preview img {
    width: 100%;
    height: 100%;
    object-fit: cover
  }

  .photo-uploader__preview.is-dragover {
    outline: 2px dashed #2563eb;
    outline-offset: -8px
  }

  .photo-uploader__overlay {
    color: #9aa0aa;
    font-size: .95rem;
    padding: 6px 10px;
    text-align: center
  }

  .photo-uploader__actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap
  }
</style>
@endpush

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
      } else {
        $medical.removeAttribute('disabled');
      }
    }
    $noMedical.addEventListener('change', toggleMedical);
    toggleMedical(); // estado inicial
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
        $zonePreview.textContent = '—';
      }
    }

    (async () => {
      try {
        const provincias = await getJSON('/provincias.json');
        fillSelect($prov, provincias, 'Provincia');
        $prov.disabled = false;
      } catch (e) {
        // Fallback manual si no hay internet
        const wrap = $prov.closest('.row');
        wrap.outerHTML = `
        <div class="col-12">
          <div class="alert alert-warning small mb-2">No se pudo cargar la lista de ubicaciones. Ingresa manualmente la zona.</div>
          <input class="form-control" placeholder="Ej: San Juan, Grecia, Alajuela"
                 oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
        </div>`;
      }
    })();

    $prov.addEventListener('change', async () => {
      $cant.disabled = true;
      $dist.disabled = true;
      $dist.innerHTML = `<option value="">Distrito</option>`;
      setZone();
      if (!$prov.value) {
        $cant.innerHTML = `<option value="">Cantón</option>`;
        return;
      }
      const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
      fillSelect($cant, cantones, 'Cantón');
      $cant.disabled = false;
    });

    $cant.addEventListener('change', async () => {
      $dist.disabled = true;
      $dist.innerHTML = `<option value="">Distrito</option>`;
      setZone();
      if (!$prov.value || !$cant.value) return;
      const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
      fillSelect($dist, distritos, 'Distrito');
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
    const submit = form.querySelector('button[type="submit"]') || form.querySelector('.btn-primary');

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
      input.value = '';
      syncSubmit();
    });

    form.addEventListener('submit', (e) => {
      if (!hasMain()) {
        e.preventDefault();
      }
    });
    syncSubmit();
  })();
</script>

<script>
  /* Previews de fotos múltiples + LÍMITE 3 (principal NO cuenta) */
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
          confirmButtonText: 'Entendido'
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
      filesBuffer = [];
      input.value = '';
      refreshGrid();
    });

    document.getElementById('pet-form').addEventListener('submit', (e) => {
      if (filesBuffer.length > MAX) e.preventDefault();
    });
  })();
</script>
@endpush