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
