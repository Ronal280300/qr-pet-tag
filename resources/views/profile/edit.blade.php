{{-- resources/views/portal/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title','Mi perfil')

@section('content')
<div class="row g-4">

  {{-- === Datos de contacto === --}}
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3">Datos de contacto</h4>

        <form id="profileForm" action="{{ route('portal.profile.update') }}" method="POST" novalidate>
          @csrf
          @method('PUT')

          {{-- Nombre --}}
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
          </div>

          {{-- Correo Electrónico --}}
          <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
          </div>

          {{-- Teléfono (código + local) --}}
          @php
            // Valores del controlador: $code y $local
            $phoneCode  = $code  ?? '506';
            $phoneLocal = $local ?? '';
          @endphp

          <div class="mb-1">
            <label class="form-label">Teléfono (para WhatsApp)</label>
            <div class="input-group">
              {{-- Código de país --}}
              <select class="form-select" name="phone_code" id="phone_code" style="max-width:220px">
                <option value="506"  {{ $phoneCode=='506'  ? 'selected':'' }}>Costa Rica (+506)</option>
                <option value="502"  {{ $phoneCode=='502'  ? 'selected':'' }}>Guatemala (+502)</option>
                <option value="503"  {{ $phoneCode=='503'  ? 'selected':'' }}>El Salvador (+503)</option>
                <option value="504"  {{ $phoneCode=='504'  ? 'selected':'' }}>Honduras (+504)</option>
                <option value="505"  {{ $phoneCode=='505'  ? 'selected':'' }}>Nicaragua (+505)</option>
                <option value="507"  {{ $phoneCode=='507'  ? 'selected':'' }}>Panamá (+507)</option>
                <option value="52"   {{ $phoneCode=='52'   ? 'selected':'' }}>México (+52)</option>
                <option value="1"    {{ $phoneCode=='1'    ? 'selected':'' }}>Estados Unidos (+1)</option>
              </select>

              {{-- Número local (sin +, solo dígitos) - Muestra el valor actual --}}
              <input type="text" class="form-control" id="phone_local" name="phone_local"
                     value="{{ old('phone_local', $phoneLocal) }}"
                     placeholder="{{ $phoneLocal ? $phoneLocal : '85307943' }}">
            </div>
          </div>
          <div class="form-text mb-3">
            <i class="fa-solid fa-info-circle me-1"></i>
            Se guardará como <span id="e164Preview" class="fw-semibold">+{{ $phoneCode }}{{ preg_replace('/\D+/','',$phoneLocal) }}</span>
            (formato E.164). WhatsApp requiere el prefijo del país.
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-floppy-disk me-1"></i> Guardar cambios
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- === Cambiar contraseña === --}}
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title mb-3">Cambiar contraseña</h4>

        <form id="passwordForm" action="{{ route('portal.profile.password.update') }}" method="POST" novalidate>
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label">Contraseña actual</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" name="password" class="form-control" required minlength="8">
          </div>

          <div class="mb-3">
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-control" required minlength="8">
          </div>

          <button type="submit" class="btn btn-outline-primary">
            <i class="fa-solid fa-key me-1"></i> Actualizar contraseña
          </button>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // ==== Helpers para preview E.164 y normalización ====
  const $code  = document.getElementById('phone_code');
  const $local = document.getElementById('phone_local');
  const $prev  = document.getElementById('e164Preview');

  function digits(v){ return (v || '').toString().replace(/\D+/g, ''); }
  function e164(code, local){ return `+${digits(code)}${digits(local)}`; }

  function syncPreview(){
    $prev.textContent = e164($code.value, $local.value);
  }
  $code?.addEventListener('change', syncPreview);
  $local?.addEventListener('input', syncPreview);
  syncPreview();

  // ==== Confirmaciones con SweetAlert2 antes de enviar ====
  const confirmAndSubmit = async (ev, {title, text, form}) => {
    ev.preventDefault();
    const res = await Swal.fire({
      icon: 'question',
      title,
      text,
      showCancelButton: true,
      confirmButtonText: 'Sí, continuar',
      cancelButtonText: 'Cancelar'
    });
    if (res.isConfirmed){
      // evitamos doble click
      const btn = form.querySelector('[type="submit"]');
      if (btn) btn.disabled = true;
      form.submit();
    }
  };

  const profileForm  = document.getElementById('profileForm');
  const passwordForm = document.getElementById('passwordForm');

  profileForm?.addEventListener('submit', (e) => {
    const phoneText = e164($code?.value, $local?.value);
    confirmAndSubmit(e, {
      title: '¿Guardar cambios?',
      text : `Se guardará el teléfono como ${phoneText}.`,
      form : profileForm
    });
  });

  passwordForm?.addEventListener('submit', (e) => {
    confirmAndSubmit(e, {
      title: '¿Actualizar contraseña?',
      text : 'Deberás usar la nueva contraseña en tu próximo inicio de sesión.',
      form : passwordForm
    });
  });
});
</script>
@endpush
