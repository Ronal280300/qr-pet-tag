@extends('layouts.app')

@section('title', 'Editar Mascota')

@section('content')
<div class="container my-4">
  <h1 class="h3 mb-3 fw-bold">Editar Mascota</h1>

  @php
    // zone viene como "Distrito, Cantón, Provincia"
    $zDistrict = $zCanton = $zProvince = null;
    if ($pet->zone){
      $parts = array_map('trim', explode(',', $pet->zone));
      if (count($parts) === 3){
        [$zDistrict, $zCanton, $zProvince] = $parts;
      }
    }
  @endphp

  <form action="{{ route('portal.pets.update', $pet) }}" method="POST" enctype="multipart/form-data" id="pet-form">
    @csrf @method('PUT')

    <div class="row g-3">
      <div class="col-12 col-lg-6">
        <label class="form-label">Nombre *</label>
        <input type="text" name="name" class="form-control" value="{{ $pet->name }}" required>
      </div>
      <div class="col-12 col-lg-6">
        <label class="form-label">Raza</label>
        <input type="text" name="breed" class="form-control" value="{{ $pet->breed }}">
      </div>

      {{-- UBICACIÓN --}}
      <div class="col-12">
        <label class="form-label">Ubicación</label>
        <div class="row g-2" id="cr-geo"
             data-current-province="{{ $zProvince }}"
             data-current-canton="{{ $zCanton }}"
             data-current-district="{{ $zDistrict }}">
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
        <input type="hidden" name="zone" id="zone" value="{{ $pet->zone }}">
        <div class="form-text">Se guardará como: <code id="zone-preview">{{ $pet->zone ?: '—' }}</code></div>
      </div>

      <div class="col-12 col-lg-6">
        <label class="form-label">Edad</label>
        <input type="number" name="age" min="0" max="50" class="form-control" value="{{ $pet->age }}">
      </div>

      <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
          <label class="form-label mb-0">Condiciones médicas</label>
          <div class="form-check">
            @php $noMed = empty($pet->medical_conditions); @endphp
            <input class="form-check-input" type="checkbox" id="no-medical" {{ $noMed ? 'checked' : '' }}>
            <label class="form-check-label small" for="no-medical">No tiene condiciones</label>
          </div>
        </div>
        <textarea name="medical_conditions" id="medical_conditions" rows="4" class="form-control" placeholder="Alergias, medicación, etc." {{ $noMed ? 'disabled' : '' }}>{{ $pet->medical_conditions }}</textarea>
      </div>

<div class="mb-4">
  <label class="form-label">Foto</label>

  <div class="photo-uploader">
    <!-- Marco pequeño del preview -->
    <div class="photo-uploader__preview" id="photoDrop">
      <img
        id="photoPreview"
        src="{{ $pet->photo ? asset('storage/'.$pet->photo) : '' }}"
        alt="Vista previa"
        class="{{ $pet->photo ? '' : 'd-none' }}"
      >
      @unless($pet->photo)
        <div class="photo-uploader__overlay">Arrastra una imagen o haz clic en “Seleccionar imagen”.</div>
      @endunless
    </div>

    <!-- Botones / acciones -->
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



    <div class="mt-4 d-flex gap-2">
      <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-1"></i> Guardar</button>
      <a href="{{ route('portal.pets.show', $pet) }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
  /* (esto es solo para los selects mientras cargan) */
  .select-loading { position: relative; }
  .select-loading::after{
    content:""; position:absolute; inset:0; background:rgba(255,255,255,.5);
    border-radius:.375rem; display:none;
  }
  .select-loading.loading::after{ display:block; }
</style>
@endpush


@push('scripts')
<script>
(() => {
  const API = 'https://ubicaciones.paginasweb.cr';
  const $prov = document.getElementById('cr-province');
  const $cant = document.getElementById('cr-canton');
  const $dist = document.getElementById('cr-district');
  const $zone = document.getElementById('zone');
  const $zonePreview = document.getElementById('zone-preview');

  const host = document.getElementById('cr-geo');
  const pNameInit = host?.dataset?.currentProvince || '';
  const cNameInit = host?.dataset?.currentCanton   || '';
  const dNameInit = host?.dataset?.currentDistrict || '';

  const $noMedical = document.getElementById('no-medical');
  const $medical = document.getElementById('medical_conditions');
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
  function fillSelect($sel, map, placeholder, selectedByName=null){
    $sel.innerHTML = `<option value="">${placeholder}</option>`;
    let selectedValue = '';
    for (const [id, name] of Object.entries(map)) {
      const opt = document.createElement('option');
      opt.value = id; opt.textContent = name;
      if(selectedByName && name.toLowerCase() === selectedByName.toLowerCase()){
        selectedValue = id;
      }
      $sel.appendChild(opt);
    }
    if(selectedValue){
      $sel.value = selectedValue;
    }
  }
  function setZone(){
    const pName = $prov.options[$prov.selectedIndex]?.text || '';
    const cName = $cant.options[$cant.selectedIndex]?.text || '';
    const dName = $dist.options[$dist.selectedIndex]?.text || '';
    if(pName && cName && dName){
      const z = `${dName}, ${cName}, ${pName}`;
      $zone.value = z; $zonePreview.textContent = z;
    }
  }

  // Carga inicial + preselección si había zone
  (async () => {
    try{
      const provincias = await getJSON('/provincias.json');
      fillSelect($prov, provincias, 'Provincia', pNameInit);
      $prov.disabled = false;

      // Si había provincia => cargar cantones
      if($prov.value){
        const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
        fillSelect($cant, cantones, 'Cantón', cNameInit);
        $cant.disabled = false;
      }

      // Si había cantón => cargar distritos
      if($prov.value && $cant.value){
        const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
        fillSelect($dist, distritos, 'Distrito', dNameInit);
        $dist.disabled = false;
      }

      setZone();
    } catch(e){
      // Fallback manual
      const wrap = $prov.closest('.row');
      wrap.outerHTML = `
        <div class="col-12">
          <div class="alert alert-warning small mb-2">No se pudo cargar la lista de ubicaciones. Ingresa manualmente la zona.</div>
          <input class="form-control" value="{{ $pet->zone }}" placeholder="Ej: San Juan, Grecia, Alajuela"
                 oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
        </div>`;
    }
  })();

  // Manejadores comunes (cambios en selects)
  $prov.addEventListener('change', async () => {
    $cant.classList.add('select-loading','loading');
    $cant.disabled = true; $dist.disabled = true;
    $dist.innerHTML = `<option value="">Distrito</option>`;
    $zone.value=''; $zonePreview.textContent='—';

    if(!$prov.value){ $cant.innerHTML = `<option value="">Cantón</option>`; $cant.classList.remove('loading'); return; }

    const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
    fillSelect($cant, cantones, 'Cantón');
    $cant.disabled = false; $cant.classList.remove('loading');
  });

  $cant.addEventListener('change', async () => {
    $dist.classList.add('select-loading','loading');
    $dist.disabled = true;
    $dist.innerHTML = `<option value="">Distrito</option>`;
    $zone.value=''; $zonePreview.textContent='—';

    if(!$prov.value || !$cant.value){ $dist.classList.remove('loading'); return; }

    const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
    fillSelect($dist, distritos, 'Distrito');
    $dist.disabled = false; $dist.classList.remove('loading');
  });

  $dist.addEventListener('change', setZone);
})();
</script>

<script>
(function(){
  const input   = document.getElementById('photo');
  const preview = document.getElementById('photoPreview');
  const drop    = document.getElementById('photoDrop');
  const clear   = document.getElementById('btnClearPhoto');

  function show(file){
    if(!file) return;
    const url = URL.createObjectURL(file);
    preview.src = url;
    preview.classList.remove('d-none');
    drop.classList.remove('is-dragover');
  }

  input.addEventListener('change', e => show(e.target.files[0]));

  // Opcional: drag & drop
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

  // Quitar preview (solo visual; si quieres limpiar el archivo enviado, resetea el input)
  clear.addEventListener('click', () => {
    preview.src = '';
    preview.classList.add('d-none');
    input.value = ''; // limpia el input para que no se envíe el archivo
  });
})();
</script>

@endpush

