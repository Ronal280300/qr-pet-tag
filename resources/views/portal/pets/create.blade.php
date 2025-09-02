@extends('layouts.app')

@section('title', 'Nueva Mascota')

@section('content')
<div class="container my-4">
  <h1 class="h3 mb-3 fw-bold">Nueva Mascota</h1>

  <div class="card card-elevated">
    <div class="card-body">
      <form action="{{ route('portal.pets.store') }}" method="POST" enctype="multipart/form-data" id="pet-form">
        @csrf

        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <label class="form-label">Nombre *</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="col-12 col-lg-6">
            <label class="form-label">Raza</label>
            <input type="text" name="breed" class="form-control" placeholder="Labrador, Poodle, etc.">
          </div>

          {{-- UBICACIÓN (Provincia > Cantón > Distrito) --}}
          <div class="col-12">
            <label class="form-label">Ubicación</label>
            <div class="row g-2" id="cr-geo"
                 data-current-province=""
                 data-current-canton=""
                 data-current-district="">
              <div class="col-md-4">
                <select id="cr-province" class="form-select" aria-label="Provincia" disabled>
                  <option value="">Provincia</option>
                </select>
              </div>
              <div class="col-md-4">
                <select id="cr-canton" class="form-select" aria-label="Cantón" disabled>
                  <option value="">Cantón</option>
                </select>
              </div>
              <div class="col-md-4">
                <select id="cr-district" class="form-select" aria-label="Distrito" disabled>
                  <option value="">Distrito</option>
                </select>
              </div>
            </div>
            <input type="hidden" name="zone" id="zone" value="">
            <div class="form-text">Se guardará como: <code id="zone-preview">—</code></div>
          </div>

          <div class="col-12 col-lg-6">
            <label class="form-label">Edad</label>
            <input type="number" name="age" min="0" max="50" class="form-control" placeholder="0">
          </div>

          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
              <label class="form-label mb-0">Condiciones médicas</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="no-medical">
                <label class="form-check-label small" for="no-medical">No tiene condiciones</label>
              </div>
            </div>
            <textarea name="medical_conditions" id="medical_conditions" rows="4" class="form-control" placeholder="Alergias, medicación, etc."></textarea>
          </div>

          {{-- ==========================================
               NUEVO: FOTOS MÚLTIPLES (con vista previa)
               ========================================== --}}
          <div class="col-12">
            <label class="form-label fw-semibold">Fotos (puedes seleccionar varias)</label>
            <input type="file" id="photos" name="photos[]" class="form-control" multiple accept="image/*">
            <div class="form-text">Formatos: JPG/PNG. Tamaño máx. 6 MB por imagen.</div>

            {{-- Previews en cuadrícula --}}
            <div id="photosPreviewGrid" class="mt-3 photos-grid d-none"></div>

            <button type="button" id="btnClearPhotos" class="btn btn-outline-danger btn-sm mt-2 d-none">
              <i class="fa-solid fa-xmark me-1"></i> Quitar todas
            </button>
          </div>

          {{-- FOTO (uploader pequeño con preview) - COMPATIBILIDAD LEGACY --}}
          <div class="col-12">
            <label class="form-label">Foto principal (opcional, sistema antiguo)</label>
            <div class="photo-uploader">
              <div class="photo-uploader__preview" id="photoDrop">
                <img id="photoPreview" src="" alt="Vista previa" class="d-none">
                <div class="photo-uploader__overlay">Arrastra una imagen o haz clic en “Seleccionar imagen”.</div>
              </div>
              <div class="photo-uploader__actions">
                <label for="photo" class="btn btn-outline-primary">
                  <i class="fa-solid fa-image me-1"></i> Seleccionar imagen
                </label>
                <input id="photo" name="photo" type="file" accept="image/*" class="d-none">
                <button type="button" id="btnClearPhoto" class="btn btn-outline-danger">
                  <i class="fa-solid fa-xmark me-1"></i> Quitar
                </button>
              </div>
            </div>
            <small class="text-muted d-block mt-2">
              Formatos: JPG/PNG. Tamaño máx. 4 MB. El recorte es solo de vista previa.
            </small>
          </div>
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
  /* loader visual para selects */
  .select-loading { position: relative; }
  .select-loading::after{
    content:""; position:absolute; inset:0; background:rgba(255,255,255,.5);
    border-radius:.375rem; display:none;
  }
  .select-loading.loading::after{ display:block; }

  /* Previews grid de fotos múltiples */
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
    display: flex; align-items: center; justify-content: center;
  }
  .photos-grid .ph img {
    width: 100%; height: 100%; object-fit: cover;
  }
  .photos-grid .ph .ph-remove {
    position: absolute; top: .35rem; right: .35rem;
    border: 0; border-radius: 999px; width: 28px; height: 28px;
    display: inline-flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,.55); color: #fff;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(() => {
  const API = 'https://ubicaciones.paginasweb.cr';
  const $prov = document.getElementById('cr-province');
  const $cant = document.getElementById('cr-canton');
  const $dist = document.getElementById('cr-district');
  const $zone = document.getElementById('zone');
  const $zonePreview = document.getElementById('zone-preview');

  const $noMedical = document.getElementById('no-medical');
  const $medical   = document.getElementById('medical_conditions');
  function toggleMedical(){
    if($noMedical.checked){ $medical.value = ''; $medical.setAttribute('disabled','disabled'); }
    else{ $medical.removeAttribute('disabled'); }
  }
  $noMedical.addEventListener('change', toggleMedical);

  async function getJSON(path){
    const resp = await fetch(`${API}${path}`);
    if(!resp.ok) throw new Error('Network');
    return await resp.json();
  }
  function fillSelect($sel, map, placeholder){
    $sel.innerHTML = `<option value="">${placeholder}</option>`;
    for (const [id, name] of Object.entries(map)) {
      const opt = document.createElement('option');
      opt.value = id; opt.textContent = name;
      $sel.appendChild(opt);
    }
  }
  function setZone(){
    const pName = $prov.options[$prov.selectedIndex]?.text || '';
    const cName = $cant.options[$cant.selectedIndex]?.text || '';
    const dName = $dist.options[$dist.selectedIndex]?.text || '';
    if(pName && cName && dName){
      const z = `${dName}, ${cName}, ${pName}`;
      $zone.value = z; $zonePreview.textContent = z;
    }else{
      $zone.value = ''; $zonePreview.textContent = '—';
    }
  }

  // Carga inicial de provincias
  (async () => {
    try{
      const provincias = await getJSON('/provincias.json');
      fillSelect($prov, provincias, 'Provincia');
      $prov.disabled = false;
    } catch(e){
      // Fallback manual (si no hay internet)
      const wrap = $prov.closest('.row');
      wrap.outerHTML = `
        <div class="col-12">
          <div class="alert alert-warning small mb-2">
            No se pudo cargar la lista de ubicaciones. Ingresa manualmente la zona.
          </div>
          <input class="form-control" placeholder="Ej: San Juan, Grecia, Alajuela"
                 oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
        </div>`;
    }
  })();

  // Eventos de cascada
  $prov.addEventListener('change', async () => {
    $cant.classList.add('select-loading','loading');
    $cant.disabled = true; $dist.disabled = true;
    $dist.innerHTML = `<option value="">Distrito</option>`;
    setZone();

    if(!$prov.value){
      $cant.innerHTML = `<option value="">Cantón</option>`;
      $cant.classList.remove('loading'); return;
    }
    const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
    fillSelect($cant, cantones, 'Cantón');
    $cant.disabled = false; $cant.classList.remove('loading');
  });

  $cant.addEventListener('change', async () => {
    $dist.classList.add('select-loading','loading');
    $dist.disabled = true;
    $dist.innerHTML = `<option value="">Distrito</option>`;
    setZone();

    if(!$prov.value || !$cant.value){ $dist.classList.remove('loading'); return; }
    const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
    fillSelect($dist, distritos, 'Distrito');
    $dist.disabled = false; $dist.classList.remove('loading');
  });

  $dist.addEventListener('change', setZone);
})();
</script>

<script>
/* Uploader pequeño con preview (mismo que en Editar) + obligación de principal */
(function(){
  const input   = document.getElementById('photo');
  const preview = document.getElementById('photoPreview');
  const drop    = document.getElementById('photoDrop');
  const clear   = document.getElementById('btnClearPhoto');
  const form    = document.getElementById('pet-form');
  const submit  = form.querySelector('button[type="submit"]') || form.querySelector('.btn-primary');

  // hacemos obligatorio el campo (sin tocar HTML original)
  input.setAttribute('required', 'required');

  function hasMain(){
    return !!preview.src && !preview.classList.contains('d-none');
  }
  function syncSubmit(){
    const ok = hasMain();
    submit.disabled = !ok;
    submit.classList.toggle('disabled', !ok);
    submit.style.pointerEvents = ok ? '' : 'none';
    submit.style.opacity = ok ? '' : '.65';
  }

  function show(file){
    if(!file) return;
    const url = URL.createObjectURL(file);
    preview.src = url;
    preview.classList.remove('d-none');
    drop.classList.remove('is-dragover');
    syncSubmit();
  }

  input.addEventListener('change', e => show(e.target.files[0]));

  ['dragenter','dragover'].forEach(ev =>
    drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('is-dragover'); })
  );
  ['dragleave','drop'].forEach(ev =>
    drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('is-dragover'); })
  );
  drop.addEventListener('drop', e => {
    const file = e.dataTransfer.files && e.dataTransfer.files[0];
    if(file){ input.files = e.dataTransfer.files; show(file); }
  });

  clear.addEventListener('click', () => {
    preview.src = '';
    preview.classList.add('d-none');
    input.value = '';
    syncSubmit();
  });

  // bloquear envío si no hay principal
  form.addEventListener('submit', (e) => {
    if(!hasMain()){
      e.preventDefault();
    }
  });

  // estado inicial
  syncSubmit();
})();
</script>

<script>
/* NUEVO: Previews de fotos múltiples + LÍMITE 3 (principal NO cuenta) */
(function(){
  const MAX = 3;
  const input = document.getElementById('photos');
  const grid  = document.getElementById('photosPreviewGrid');
  const btnClear = document.getElementById('btnClearPhotos');

  // Mantenemos una lista virtual para poder "remover" antes de enviar
  let filesBuffer = [];

  function refreshGrid(){
    grid.innerHTML = '';
    if(filesBuffer.length === 0){
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
      img.src = url; img.alt = `Foto ${idx+1}`;
      cell.appendChild(img);

      const rm = document.createElement('button');
      rm.type = 'button';
      rm.className = 'ph-remove';
      rm.innerHTML = '<i class="fa-solid fa-xmark"></i>';
      rm.addEventListener('click', () => { removeAt(idx); });
      cell.appendChild(rm);

      grid.appendChild(cell);
    });
  }

  function applyBufferToInput(){
    const dt = new DataTransfer();
    filesBuffer.forEach(f => dt.items.add(f));
    input.files = dt.files;
  }

  function removeAt(i){
    filesBuffer.splice(i, 1);
    applyBufferToInput(); refreshGrid();
  }

  input.addEventListener('change', (e) => {
    const incoming = Array.from(e.target.files || []);
    const totalIfAdded = filesBuffer.length + incoming.length;

    if (totalIfAdded > MAX) {
      const allowed = Math.max(0, MAX - filesBuffer.length);
      Swal.fire({
        icon:'warning',
        title:'Máximo 3 fotos adicionales',
        text:`Puedes añadir ${allowed} foto(s) más.`,
        confirmButtonText:'Entendido'
      });
      if (allowed > 0) {
        filesBuffer = filesBuffer.concat(incoming.slice(0, allowed));
      }
    } else {
      filesBuffer = filesBuffer.concat(incoming);
    }

    applyBufferToInput(); refreshGrid();
    input.value = ''; // permitir volver a abrir el diálogo
  });

  btnClear.addEventListener('click', () => {
    filesBuffer = [];
    input.value = '';
    refreshGrid();
  });

  // validación final (no deja enviar con >3 adicionales)
  const form = document.getElementById('pet-form');
  form.addEventListener('submit', (e) => {
    if (filesBuffer.length > MAX) {
      e.preventDefault();
    }
  });
})();
</script>
@endpush
