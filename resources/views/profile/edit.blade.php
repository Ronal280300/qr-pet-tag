{{-- resources/views/portal/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title','Mi perfil')

@section('content')
<div class="profile-edit-container">
  {{-- Header del perfil --}}
  <div class="profile-header">
    <div class="profile-avatar">
      <i class="fa-solid fa-user"></i>
    </div>
    <div class="profile-header-info">
      <h1 class="profile-name">{{ auth()->user()->name }}</h1>
      <p class="profile-email">{{ auth()->user()->email }}</p>
    </div>
  </div>

  {{-- Grid de secciones --}}
  <div class="profile-sections">
    
    {{-- === Datos de contacto === --}}
    <div class="profile-section">
      <div class="section-header">
        <div class="section-icon">
          <i class="fa-solid fa-address-card"></i>
        </div>
        <h2 class="section-title">Datos de contacto</h2>
      </div>

      <form id="profileForm" action="{{ route('portal.profile.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-user label-icon"></i>
            Nombre completo
          </label>
          <input 
            type="text" 
            name="name" 
            class="form-input" 
            value="{{ old('name', auth()->user()->name) }}" 
            required
            placeholder="Tu nombre completo"
          >
        </div>

        {{-- Email --}}
        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-envelope label-icon"></i>
            Correo electrónico
          </label>
          <input 
            type="email" 
            name="email" 
            class="form-input" 
            value="{{ old('email', auth()->user()->email) }}" 
            required
            placeholder="tu@email.com"
          >
        </div>

        {{-- Teléfono (código + local) --}}
        @php
          $phoneCode  = $code  ?? '506';
          $phoneLocal = $local ?? '';
        @endphp

        <div class="form-group">
          <label class="form-label">
            <i class="fa-brands fa-whatsapp label-icon"></i>
            Teléfono WhatsApp
          </label>
          
          <div class="phone-input-group">
            {{-- Código de país --}}
            <select class="phone-code-select" name="phone_code" id="phone_code">
              <option value="506" {{ $phoneCode=='506' ? 'selected':'' }}>🇨🇷 +506</option>
              <option value="502" {{ $phoneCode=='502' ? 'selected':'' }}>🇬🇹 +502</option>
              <option value="503" {{ $phoneCode=='503' ? 'selected':'' }}>🇸🇻 +503</option>
              <option value="504" {{ $phoneCode=='504' ? 'selected':'' }}>🇭🇳 +504</option>
              <option value="505" {{ $phoneCode=='505' ? 'selected':'' }}>🇳🇮 +505</option>
              <option value="507" {{ $phoneCode=='507' ? 'selected':'' }}>🇵🇦 +507</option>
              <option value="52"  {{ $phoneCode=='52'  ? 'selected':'' }}>🇲🇽 +52</option>
              <option value="1"   {{ $phoneCode=='1'   ? 'selected':'' }}>🇺🇸 +1</option>
            </select>

            {{-- Número local --}}
            <input 
              type="tel" 
              class="phone-local-input" 
              id="phone_local" 
              name="phone_local"
              value="{{ old('phone_local', $phoneLocal) }}"
              placeholder="88888888"
            >
          </div>

          <div class="phone-preview">
            <i class="fa-solid fa-info-circle"></i>
            Se guardará como: <strong id="e164Preview">+{{ $phoneCode }}{{ preg_replace('/\D+/','',$phoneLocal) }}</strong>
          </div>
        </div>

        <button type="submit" class="btn-primary">
          <i class="fa-solid fa-check"></i>
          <span>Guardar cambios</span>
        </button>
      </form>
    </div>

    {{-- === Cambiar contraseña === --}}
    <div class="profile-section">
      <div class="section-header">
        <div class="section-icon security">
          <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2 class="section-title">Seguridad</h2>
      </div>

      <form id="passwordForm" action="{{ route('portal.profile.password.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-lock label-icon"></i>
            Contraseña actual
          </label>
          <div class="password-wrapper">
            <input 
              type="password" 
              name="current_password" 
              class="form-input password-input" 
              required
              placeholder="••••••••"
            >
            <button type="button" class="password-toggle" data-target="current_password">
              <i class="fa-solid fa-eye"></i>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-key label-icon"></i>
            Nueva contraseña
          </label>
          <div class="password-wrapper">
            <input 
              type="password" 
              name="password" 
              class="form-input password-input" 
              required 
              minlength="8"
              placeholder="Mínimo 8 caracteres"
            >
            <button type="button" class="password-toggle" data-target="password">
              <i class="fa-solid fa-eye"></i>
            </button>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">
            <i class="fa-solid fa-check-double label-icon"></i>
            Confirmar contraseña
          </label>
          <div class="password-wrapper">
            <input 
              type="password" 
              name="password_confirmation" 
              class="form-input password-input" 
              required 
              minlength="8"
              placeholder="Repite la nueva contraseña"
            >
            <button type="button" class="password-toggle" data-target="password_confirmation">
              <i class="fa-solid fa-eye"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-secondary">
          <i class="fa-solid fa-shield-halved"></i>
          <span>Actualizar contraseña</span>
        </button>
      </form>
    </div>

  </div>
</div>

<style>
/* === Reset y variables === */
:root {
  --primary: #2563eb;
  --primary-dark: #1e40af;
  --primary-light: #3b82f6;
  --text-primary: #1f2937;
  --text-secondary: #6b7280;
  --text-muted: #9ca3af;
  --border: #e5e7eb;
  --bg-light: #f8fafc;
  --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  --radius: 8px;
}

.profile-edit-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1.5rem;
}

/* === Header del perfil === */
.profile-header {
  background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
  border-radius: var(--radius);
  padding: 2rem;
  margin-bottom: 2rem;
  display: flex;
  align-items: center;
  gap: 1.5rem;
  box-shadow: var(--shadow-md);
}

.profile-avatar {
  width: 70px;
  height: 70px;
  background: rgba(255, 255, 255, 0.15);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid rgba(255, 255, 255, 0.3);
  flex-shrink: 0;
}

.profile-avatar i {
  font-size: 1.75rem;
  color: white;
}

.profile-header-info {
  flex: 1;
  min-width: 0;
}

.profile-name {
  font-size: 1.75rem;
  font-weight: 700;
  color: white;
  margin: 0 0 0.25rem 0;
  line-height: 1.2;
}

.profile-email {
  font-size: 0.95rem;
  color: rgba(255, 255, 255, 0.9);
  margin: 0;
  opacity: 0.95;
}

/* === Grid de secciones === */
.profile-sections {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 2rem;
}

/* === Secciones === */
.profile-section {
  background: white;
  border-radius: var(--radius);
  padding: 1.75rem;
  box-shadow: var(--shadow);
  border: 1px solid var(--border);
  transition: box-shadow 0.2s ease;
}

.profile-section:hover {
  box-shadow: var(--shadow-md);
}

.section-header {
  display: flex;
  align-items: center;
  gap: 0.875rem;
  margin-bottom: 1.75rem;
  padding-bottom: 0.875rem;
  border-bottom: 1px solid var(--border);
}

.section-icon {
  width: 40px;
  height: 40px;
  background: #eff6ff;
  border-radius: var(--radius);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.section-icon.security {
  background: #eff6ff;
}

.section-icon i {
  font-size: 1.125rem;
  color: var(--primary);
}

.section-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text-primary);
  margin: 0;
}

/* === Formularios === */
.form-group {
  margin-bottom: 1.5rem;
}

.form-group:last-of-type {
  margin-bottom: 1.75rem;
}

.form-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.label-icon {
  font-size: 0.875rem;
  color: var(--primary);
}

.form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  font-size: 0.95rem;
  color: var(--text-primary);
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius);
  transition: all 0.2s ease;
  outline: none;
}

.form-input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-input::placeholder {
  color: var(--text-muted);
}

/* === Teléfono === */
.phone-input-group {
  display: flex;
  gap: 0.75rem;
}

.phone-code-select {
  flex: 0 0 130px;
  padding: 0.75rem 1rem;
  font-size: 0.95rem;
  font-weight: 500;
  color: var(--text-primary);
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius);
  transition: all 0.2s ease;
  cursor: pointer;
  outline: none;
}

.phone-code-select:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.phone-local-input {
  flex: 1;
  padding: 0.75rem 1rem;
  font-size: 0.95rem;
  color: var(--text-primary);
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius);
  transition: all 0.2s ease;
  outline: none;
}

.phone-local-input:focus {
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.phone-preview {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.625rem;
  padding: 0.625rem 0.875rem;
  background: #eff6ff;
  border-radius: var(--radius);
  font-size: 0.8125rem;
  color: var(--primary-dark);
  border: 1px solid #dbeafe;
}

.phone-preview i {
  font-size: 0.8125rem;
}

.phone-preview strong {
  font-weight: 700;
}

/* === Password wrapper === */
.password-wrapper {
  position: relative;
}

.password-input {
  padding-right: 3rem;
}

.password-toggle {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  padding: 0.5rem;
  cursor: pointer;
  color: var(--text-muted);
  transition: color 0.2s ease;
  outline: none;
}

.password-toggle:hover {
  color: var(--primary);
}

.password-toggle i {
  font-size: 1rem;
}

/* === Botones === */
.btn-primary,
.btn-secondary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.625rem;
  width: 100%;
  padding: 0.875rem 1.5rem;
  font-size: 0.95rem;
  font-weight: 600;
  border: none;
  border-radius: var(--radius);
  cursor: pointer;
  transition: all 0.2s ease;
  outline: none;
}

.btn-primary {
  background: var(--primary);
  color: white;
}

.btn-primary:hover {
  background: var(--primary-dark);
}

.btn-primary:active {
  transform: scale(0.98);
}

.btn-secondary {
  background: white;
  color: var(--primary);
  border: 1px solid var(--border);
}

.btn-secondary:hover {
  background: var(--bg-light);
  border-color: var(--primary);
}

.btn-secondary:active {
  transform: scale(0.98);
}

.btn-primary i,
.btn-secondary i {
  font-size: 1rem;
}

/* === Responsive === */
@media (max-width: 768px) {
  .profile-edit-container {
    padding: 1rem;
  }

  .profile-header {
    padding: 1.5rem;
    flex-direction: column;
    text-align: center;
  }

  .profile-avatar {
    width: 60px;
    height: 60px;
  }

  .profile-avatar i {
    font-size: 1.5rem;
  }

  .profile-name {
    font-size: 1.35rem;
  }

  .profile-email {
    font-size: 0.875rem;
  }

  .profile-sections {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .profile-section {
    padding: 1.5rem;
  }

  .section-header {
    margin-bottom: 1.5rem;
  }

  .section-icon {
    width: 36px;
    height: 36px;
  }

  .section-icon i {
    font-size: 1rem;
  }

  .section-title {
    font-size: 1.125rem;
  }

  .phone-input-group {
    flex-direction: column;
    gap: 0.75rem;
  }

  .phone-code-select {
    flex: 1;
    width: 100%;
  }

  .form-group {
    margin-bottom: 1.25rem;
  }
}

@media (max-width: 480px) {
  .profile-header {
    padding: 1.25rem;
  }

  .profile-avatar {
    width: 56px;
    height: 56px;
  }

  .profile-avatar i {
    font-size: 1.375rem;
  }

  .profile-name {
    font-size: 1.25rem;
  }

  .profile-section {
    padding: 1.25rem;
  }

  .section-title {
    font-size: 1.0625rem;
  }

  .form-input,
  .phone-code-select,
  .phone-local-input {
    padding: 0.6875rem 0.875rem;
    font-size: 0.9375rem;
  }

  .btn-primary,
  .btn-secondary {
    padding: 0.8125rem 1.25rem;
    font-size: 0.9375rem;
  }
}

/* === Animaciones === */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.profile-section {
  animation: fadeIn 0.4s ease;
}

.profile-section:nth-child(2) {
  animation-delay: 0.1s;
}

/* === SweetAlert2 Custom Styles === */
.swal2-popup {
  border-radius: var(--radius) !important;
  padding: 2rem !important;
}

.swal2-title {
  font-size: 1.5rem !important;
  font-weight: 700 !important;
  color: var(--text-primary) !important;
}

.swal2-html-container {
  font-size: 0.95rem !important;
  color: var(--text-secondary) !important;
}

.swal2-actions {
  gap: 0.75rem !important;
  margin-top: 1.5rem !important;
}

.swal2-confirm,
.swal2-cancel {
  margin: 0 !important;
  padding: 0.875rem 1.75rem !important;
  font-size: 0.95rem !important;
  font-weight: 600 !important;
  border-radius: var(--radius) !important;
  border: none !important;
  transition: all 0.2s ease !important;
  box-shadow: none !important;
}

.swal2-confirm {
  background: var(--primary) !important;
  color: white !important;
}

.swal2-confirm:hover {
  background: var(--primary-dark) !important;
}

.swal2-cancel {
  background: white !important;
  color: var(--text-secondary) !important;
  border: 1px solid var(--border) !important;
}

.swal2-cancel:hover {
  background: var(--bg-light) !important;
  color: var(--text-primary) !important;
}
</style>
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
    if ($prev && $code && $local) {
      $prev.textContent = e164($code.value, $local.value);
    }
  }

  if ($code && $local) {
    $code.addEventListener('change', syncPreview);
    $local.addEventListener('input', syncPreview);
    syncPreview();
  }

  // ==== Toggle de contraseñas ====
  document.querySelectorAll('.password-toggle').forEach(toggle => {
    toggle.addEventListener('click', function() {
      const targetName = this.dataset.target;
      const input = document.querySelector(`input[name="${targetName}"]`);
      const icon = this.querySelector('i');
      
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

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
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span>Guardando...</span>';
      }
      form.submit();
    }
  };

  const profileForm  = document.getElementById('profileForm');
  const passwordForm = document.getElementById('passwordForm');

  if (profileForm) {
    profileForm.addEventListener('submit', (e) => {
      const phoneText = e164($code?.value, $local?.value);
      confirmAndSubmit(e, {
        title: '¿Guardar cambios?',
        text : `Se guardará el teléfono como ${phoneText}.`,
        form : profileForm
      });
    });
  }

  if (passwordForm) {
    passwordForm.addEventListener('submit', (e) => {
      confirmAndSubmit(e, {
        title: '¿Actualizar contraseña?',
        text : 'Deberás usar la nueva contraseña en tu próximo inicio de sesión.',
        form : passwordForm
      });
    });
  }

  // ==== Validación en tiempo real ====
  document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('blur', function() {
      if (this.hasAttribute('required') && !this.value.trim()) {
        this.style.borderColor = '#ef4444';
      } else if (this.type === 'email' && this.value && !this.value.includes('@')) {
        this.style.borderColor = '#ef4444';
      } else {
        this.style.borderColor = 'transparent';
      }
    });

    input.addEventListener('input', function() {
      if (this.style.borderColor === 'rgb(239, 68, 68)') {
        this.style.borderColor = 'transparent';
      }
    });
  });
});
</script>
@endpush
