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

    $existingPhotos = $pet->photos ?? collect();
    $existingCount  = $existingPhotos->count(); // fotos adicionales existentes
    $hasMain        = !empty($pet->photo);
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
          <label class="form-label mb-0">Observaciones</label>
          <div class="form-check">
            @php $noMed = empty($pet->medical_conditions); @endphp
            <input class="form-check-input" type="checkbox" id="no-medical" {{ $noMed ? 'checked' : '' }}>
            <label class="form-check-label small" for="no-medical">Sin observaciones</label>
          </div>
        </div>
        <textarea name="medical_conditions" id="medical_conditions" rows="4" class="form-control" placeholder="Alergias, medicación, etc." {{ $noMed ? 'disabled' : '' }}>{{ $pet->medical_conditions }}</textarea>
      </div>

      {{-- ===================== FOTO PRINCIPAL (OBLIGATORIA) ===================== --}}
      <div class="col-12">
        <h5 class="mb-2">Foto principal <span class="text-danger">*</span></h5>
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
            <div class="form-text">JPG/PNG. Máx 4 MB.</div>
          </div>
        </div>
      </div>

      {{-- ===================== FOTOS ADICIONALES (MÁX 3) ===================== --}}
      <div class="col-12">
        <div class="d-flex align-items-center justify-content-between">
          <h5 class="mb-2">Fotos adicionales <small class="text-muted">(máx. 3)</small></h5>
          <div class="small text-muted">Puedes eliminar o agregar; el total no puede exceder 3.</div>
        </div>

        {{-- existentes --}}
        <div id="existingGrid" class="d-flex flex-wrap gap-3 mb-3">
          @foreach($existingPhotos as $ph)
            <div class="existing-card position-relative" data-id="{{ $ph->id }}">
              <img src="{{ Storage::url($ph->path) }}" alt="Foto adicional">
              <button type="button" class="btn-remove-photo" title="Eliminar" data-photo-id="{{ $ph->id }}">
                <i class="fa-solid fa-xmark"></i>
              </button>
            </div>
          @endforeach
        </div>

        {{-- oculto con IDs a borrar --}}
        <input type="hidden" name="delete_photos" id="deletePhotos" value="">

        {{-- nuevas --}}
        <input type="file" id="photos" name="photos[]" class="form-control" accept="image/*" multiple>
        <div class="form-text">JPG/PNG. Máx 6 MB c/u.</div>

        <div id="photosPreviewGrid" class="mt-3 photos-grid d-none"></div>
        <button type="button" id="btnClearPhotos" class="btn btn-outline-danger btn-sm mt-2 d-none">
          <i class="fa-solid fa-xmark me-1"></i> Quitar todas (nuevas)
        </button>
      </div>
    </div>

    <div class="mt-4 d-flex gap-2">
      <button class="btn btn-primary" id="btnSubmit"><i class="fa-solid fa-floppy-disk me-1"></i> Guardar</button>
      <a href="{{ route('portal.pets.show', $pet) }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
  .select-loading { position: relative; }
  .select-loading::after{ content:""; position:absolute; inset:0; background:rgba(255,255,255,.5); border-radius:.375rem; display:none; }
  .select-loading.loading::after{ display:block; }

  /* Foto principal */
  .photo-main-preview{
    width: 260px; height: 180px;
    border:1px dashed #e5e7eb; border-radius:.75rem;
    background:#f8fafc; display:flex; align-items:center; justify-content:center;
  }
  .photo-main-preview img{ width:100%; height:100%; object-fit:cover; border-radius:.75rem; }
  .placeholder{ text-align:center; }

  /* Cards de fotos existentes */
  .existing-card{
    width: 180px; height:120px; border-radius:.5rem; overflow:hidden;
    border:1px solid #e5e7eb; background:#f8fafc;
  }
  .existing-card img{ width:100%; height:100%; object-fit:cover; }
  .btn-remove-photo{
    position:absolute; top:6px; right:6px; width:26px; height:26px;
    border:none; border-radius:50%; background:rgba(220,53,69,.92); color:#fff;
    display:flex; align-items:center; justify-content:center; font-size:14px; cursor:pointer;
  }

  /* Grid previews nuevas */
  .photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: .75rem;
  }
  .photos-grid .ph {
    position: relative; border: 1px solid #e5e7eb; border-radius: .5rem;
    overflow: hidden; background: #f8fafc; aspect-ratio: 1 / 1;
    display: flex; align-items: center; justify-content: center;
  }
  .photos-grid .ph img { width: 100%; height: 100%; object-fit: cover; }
  .photos-grid .ph .ph-remove {
    position: absolute; top: .35rem; right: .35rem; border:0; border-radius:50%;
    width: 26px; height: 26px; display:inline-flex; align-items:center; justify-content:center;
    background: rgba(0,0,0,.55); color:#fff; cursor:pointer;
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

  async function getJSON(path){ const r=await fetch(`${API}${path}`); if(!r.ok) throw 0; return await r.json(); }
  function fillSelect($sel, map, placeholder, selectedByName=null){
    $sel.innerHTML = `<option value="">${placeholder}</option>`;
    let selectedValue = '';
    for (const [id, name] of Object.entries(map)) {
      const opt = document.createElement('option'); opt.value = id; opt.textContent = name;
      if(selectedByName && name.toLowerCase() === selectedByName.toLowerCase()) selectedValue = id;
      $sel.appendChild(opt);
    }
    if(selectedValue) $sel.value = selectedValue;
  }
  function setZone(){
    const pName = $prov.options[$prov.selectedIndex]?.text || '';
    const cName = $cant.options[$cant.selectedIndex]?.text || '';
    const dName = $dist.options[$dist.selectedIndex]?.text || '';
    if(pName && cName && dName){ const z = `${dName}, ${cName}, ${pName}`; $zone.value = z; $zonePreview.textContent = z; }
    else { $zone.value = ''; $zonePreview.textContent = '—'; }
  }

  (async () => {
    try{
      const provincias = await getJSON('/provincias.json');
      fillSelect($prov, provincias, 'Provincia', pNameInit); $prov.disabled = false;
      if($prov.value){
        const cantones = await getJSON(`/provincia/${$prov.value}/cantones.json`);
        fillSelect($cant, cantones, 'Cantón', cNameInit); $cant.disabled = false;
      }
      if($prov.value && $cant.value){
        const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
        fillSelect($dist, distritos, 'Distrito', dNameInit); $dist.disabled = false;
      }
      setZone();
    }catch(e){
      const wrap = $prov.closest('.row');
      wrap.outerHTML = `
        <div class="col-12">
          <div class="alert alert-warning small mb-2">No se pudo cargar la lista de ubicaciones. Ingresa manualmente la zona.</div>
          <input class="form-control" value="{{ $pet->zone }}" placeholder="Ej: San Juan, Grecia, Alajuela"
                 oninput="document.getElementById('zone').value=this.value;document.getElementById('zone-preview').textContent=this.value;">
        </div>`;
    }
  })();

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
    $dist.disabled = true; $dist.innerHTML = `<option value="">Distrito</option>`;
    $zone.value=''; $zonePreview.textContent='—';
    if(!$prov.value || !$cant.value){ $dist.classList.remove('loading'); return; }
    const distritos = await getJSON(`/provincia/${$prov.value}/canton/${$cant.value}/distritos.json`);
    fillSelect($dist, distritos, 'Distrito'); $dist.disabled = false; $dist.classList.remove('loading');
  });

  $dist.addEventListener('change', setZone);
})();
</script>

<script>
/* FOTO PRINCIPAL (preview y quitar) */
(function(){
  const input   = document.getElementById('photo');
  const preview = document.getElementById('photoPreview');
  const ph      = document.getElementById('mainPlaceholder');
  const clear   = document.getElementById('btnClearPhoto');

  function show(file){
    if(!file) return;
    const url = URL.createObjectURL(file);
    preview.src = url; preview.classList.remove('d-none');
    ph && (ph.style.display='none');
  }

  input.addEventListener('change', e => show(e.target.files[0]));
  clear.addEventListener('click', () => {
    preview.src = ''; preview.classList.add('d-none');
    input.value = '';
    if(ph){ ph.style.display='block'; ph.textContent='Sin foto principal'; }
  });
})();
</script>

<script>
/* FOTOS ADICIONALES (máx 3, contando existentes no eliminadas + nuevas) */
(function(){
  const MAX = 3;

  const existingGrid = document.getElementById('existingGrid');
  const deleteInput  = document.getElementById('deletePhotos');
  const input        = document.getElementById('photos');
  const grid         = document.getElementById('photosPreviewGrid');
  const btnClear     = document.getElementById('btnClearPhotos');

  function existingAliveCount(){
    return existingGrid.querySelectorAll('.existing-card').length;
  }

  const removed = new Set(); // ids eliminados
  function syncDeleteInput(){ deleteInput.value = Array.from(removed).join(','); }

  // eliminar existentes
  existingGrid.addEventListener('click', (e)=>{
    const btn = e.target.closest('.btn-remove-photo'); if(!btn) return;
    const id  = btn.dataset.photoId; if(!id) return;
    removed.add(id); syncDeleteInput();
    btn.closest('.existing-card')?.remove();
  });

  let filesBuffer = []; // nuevas seleccionadas (File[])
  function refreshNewGrid(){
    grid.innerHTML = '';
    if(filesBuffer.length===0){ grid.classList.add('d-none'); btnClear.classList.add('d-none'); return; }
    grid.classList.remove('d-none'); btnClear.classList.remove('d-none');
    filesBuffer.forEach((file, idx)=>{
      const url = URL.createObjectURL(file);
      const cell = document.createElement('div'); cell.className='ph';
      const img  = document.createElement('img'); img.src=url; img.alt=`Foto ${idx+1}`; cell.appendChild(img);
      const rm   = document.createElement('button'); rm.type='button'; rm.className='ph-remove'; rm.innerHTML='<i class="fa-solid fa-xmark"></i>';
      rm.addEventListener('click', ()=>removeAt(idx));
      cell.appendChild(rm);
      grid.appendChild(cell);
    });
  }
  function applyBufferToInput(){
    const dt = new DataTransfer();
    filesBuffer.forEach(f=>dt.items.add(f));
    input.files = dt.files;
  }
  function removeAt(i){
    filesBuffer.splice(i,1);
    applyBufferToInput(); refreshNewGrid();
  }

  input.addEventListener('change', (e)=>{
    const incoming = Array.from(e.target.files || []);
    const totalIfAdded = existingAliveCount() + filesBuffer.length + incoming.length;
    if(totalIfAdded > MAX){
      const allowed = Math.max(0, MAX - existingAliveCount() - filesBuffer.length);
      Swal.fire({
        icon:'warning',
        title:'Máximo 3 fotos adicionales',
        text:`Puedes añadir ${allowed} foto(s) más.`,
        confirmButtonText:'Entendido'
      });
      if(allowed > 0){
        filesBuffer = filesBuffer.concat(incoming.slice(0, allowed));
      }
    }else{
      filesBuffer = filesBuffer.concat(incoming);
    }
    applyBufferToInput(); 
    refreshNewGrid();
    // IMPORTANTE: no limpiar input.value aquí (provocaba que PHP recibiera 0 archivos)
  });

  btnClear.addEventListener('click', ()=>{
    filesBuffer = []; applyBufferToInput(); refreshNewGrid();
  });

  // Validación final antes de enviar (también valida foto principal)
  document.getElementById('pet-form').addEventListener('submit', (e)=>{
    const hasMainNow = !!document.getElementById('photoPreview').src && !document.getElementById('photoPreview').classList.contains('d-none');
    if(!hasMainNow){
      Swal.fire({icon:'warning', title:'Falta la foto principal', text:'Debes seleccionar una foto principal para guardar.'});
      e.preventDefault(); return false;
    }
    const totalFinal = existingAliveCount() + filesBuffer.length;
    if(totalFinal > MAX){
      Swal.fire({icon:'error', title:'Demasiadas fotos adicionales', text:'El máximo permitido es 3.'});
      e.preventDefault(); return false;
    }
  });
})();
</script>

@endpush
