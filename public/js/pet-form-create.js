/**
 * PET FORM - CREATE
 * Extracted and optimized from create.blade.php
 */

// Medical conditions toggle
(() => {
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

// Costa Rica provinces/cantons/districts cascade
(() => {
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

// Main photo uploader (legacy)
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

// Multiple photos preview + LIMIT 3
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
