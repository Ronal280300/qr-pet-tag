@extends('layouts.app')

@section('title', 'Iniciar sesión - QR-Pet Tag')

@push('styles')
<style>
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes slideIn {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
  }

  @keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-15px) rotate(5deg); }
  }

  @keyframes ripple {
    0% { transform: scale(0); opacity: 1; }
    100% { transform: scale(4); opacity: 0; }
  }

  @keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .auth-stage {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    position: relative;
    width: 100%;
  }

  /* Decoración de fondo sutil */
  .auth-stage::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(17,93,252,0.05) 0%, transparent 70%);
    top: -200px;
    right: -200px;
    border-radius: 50%;
    animation: float 20s ease-in-out infinite;
    pointer-events: none;
  }

  .auth-stage::after {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(30,124,242,0.04) 0%, transparent 70%);
    bottom: -100px;
    left: -100px;
    border-radius: 50%;
    animation: float 15s ease-in-out infinite 2s;
    pointer-events: none;
  }

  .auth-container {
    max-width: 1100px;
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    position: relative;
    z-index: 1;
  }

  /* Panel izquierdo - Branding */
  .brand-panel {
    display: flex;
    flex-direction: column;
    gap: 32px;
    animation: slideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .brand-hero {
    position: relative;
  }

  .brand-logo {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 50%, #1e7cf2 100%);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 56px;
    color: white;
    margin-bottom: 32px;
    box-shadow: 
      0 20px 60px rgba(17,93,252,0.3),
      0 0 0 1px rgba(255,255,255,0.1) inset;
    position: relative;
    overflow: hidden;
    animation: fadeIn 0.8s ease-out 0.2s both;
  }

  .brand-logo::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
    animation: gradient 3s ease infinite;
    background-size: 200% 200%;
  }

  .brand-logo i {
    position: relative;
    z-index: 1;
    animation: float 4s ease-in-out infinite;
  }

  .brand-title {
    font-size: 42px;
    font-weight: 900;
    line-height: 1.2;
    color: #0f1419;
    margin-bottom: 16px;
    letter-spacing: -0.5px;
    animation: fadeIn 0.8s ease-out 0.3s both;
  }

  .brand-title .highlight {
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .brand-subtitle {
    font-size: 18px;
    line-height: 1.6;
    color: #5f6c7b;
    max-width: 440px;
    animation: fadeIn 0.8s ease-out 0.4s both;
  }

  .brand-features {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-top: 16px;
    animation: fadeIn 0.8s ease-out 0.5s both;
  }

  .feature-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: linear-gradient(135deg, rgba(17,93,252,0.03) 0%, rgba(30,124,242,0.02) 100%);
    border-radius: 16px;
    border: 1px solid rgba(17,93,252,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .feature-item:hover {
    transform: translateX(8px);
    border-color: rgba(17,93,252,0.15);
    background: linear-gradient(135deg, rgba(17,93,252,0.05) 0%, rgba(30,124,242,0.03) 100%);
  }

  .feature-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(17,93,252,0.2);
  }

  .feature-text {
    flex: 1;
  }

  .feature-text strong {
    display: block;
    color: #0f1419;
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 4px;
  }

  .feature-text span {
    color: #5f6c7b;
    font-size: 14px;
  }

  /* Panel derecho - Formulario */
  .form-panel {
    background: white;
    border-radius: 32px;
    padding: 48px;
    box-shadow: 
      0 50px 100px rgba(0,0,0,0.06),
      0 20px 40px rgba(0,0,0,0.04),
      0 0 0 1px rgba(0,0,0,0.03);
    animation: fadeIn 0.8s ease-out 0.3s both;
    position: relative;
    overflow: hidden;
  }

  .form-panel::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #115DFC 0%, #3466ff 50%, #1e7cf2 100%);
    background-size: 200% 100%;
    animation: gradient 3s ease infinite;
  }

  .form-header {
    text-align: center;
    margin-bottom: 36px;
  }

  .form-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f1419;
    margin-bottom: 8px;
    letter-spacing: -0.3px;
  }

  .form-subtitle {
    color: #5f6c7b;
    font-size: 15px;
  }

  .alert {
    border-radius: 14px;
    border: none;
    padding: 14px 18px;
    margin-bottom: 24px;
    font-size: 14px;
    animation: fadeIn 0.4s ease-out;
  }

  .form-grid {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }

  .input-group {
    position: relative;
    display: grid;
    grid-template-columns: 150px 1fr;
    gap: 16px;
    align-items: start;
  }

  .input-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #3d4451;
    transition: color 0.3s ease;
    padding-top: 18px;
    text-align: left;
  }

  .input-wrapper {
    position: relative;
    width: 100%;
  }

  .input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    opacity: 0.5;
    transition: all 0.3s ease;
    pointer-events: none;
    z-index: 1;
  }

  .form-input {
    width: 100%;
    height: 56px;
    padding: 0 18px 0 52px;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    font-size: 15px;
    color: #0f1419;
    background: #fafbfc;
    outline: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .form-input::placeholder {
    color: #9ca3af;
  }

  .form-input:hover {
    background: white;
    border-color: #d1d5db;
  }

  .form-input:focus {
    background: white;
    border-color: #3466ff;
    box-shadow: 0 0 0 4px rgba(52,102,255,0.1);
  }

  .input-group:focus-within .input-icon {
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
  }

  .input-group:focus-within .input-icon path {
    stroke: #3466ff;
  }

  .input-group:focus-within .input-label {
    color: #3466ff;
  }

  .form-input.is-invalid {
    border-color: #ef4444;
    background: #fef2f2;
  }

  .invalid-feedback {
    display: block;
    margin-top: 8px;
    font-size: 13px;
    color: #ef4444;
    animation: fadeIn 0.3s ease-out;
    grid-column: 2;
  }

  .form-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
  }

  .checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
  }

  .checkbox-input {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #3466ff;
  }

  .checkbox-label {
    font-size: 14px;
    color: #3d4451;
    cursor: pointer;
    user-select: none;
  }

  .forgot-link {
    font-size: 14px;
    color: #3466ff;
    text-decoration: none;
    font-weight: 600;
    position: relative;
    transition: color 0.2s ease;
    white-space: nowrap;
  }

  .forgot-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: #3466ff;
    transition: width 0.3s ease;
  }

  .forgot-link:hover {
    color: #115DFC;
  }

  .forgot-link:hover::after {
    width: 100%;
  }

  .btn-submit {
    width: 100%;
    height: 56px;
    border: none;
    border-radius: 16px;
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
    color: white;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 24px rgba(17,93,252,0.3);
  }

  .btn-submit::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .btn-submit:hover::before {
    width: 400px;
    height: 400px;
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(17,93,252,0.4);
  }

  .btn-submit:active {
    transform: translateY(0);
  }

  .divider {
    display: flex;
    align-items: center;
    gap: 16px;
    color: #9ca3af;
    font-size: 14px;
    font-weight: 500;
    margin: 28px 0;
  }

  .divider::before,
  .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
  }

  .btn-google {
    width: 100%;
    height: 56px;
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    background: white;
    color: #0f1419;
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .btn-google::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(52,102,255,0.04), transparent);
    transition: left 0.5s ease;
  }

  .btn-google:hover::before {
    left: 100%;
  }

  .btn-google:hover {
    background: #fafbfc;
    border-color: #d1d5db;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
  }

  .google-icon {
    width: 20px;
    height: 20px;
    transition: transform 0.3s ease;
  }

  .btn-google:hover .google-icon {
    transform: scale(1.1) rotate(5deg);
  }

  /* Toggle password visibility */
  .password-toggle {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 2;
    width: 36px;
    height: 36px;
    border-radius: 8px;
  }

  .password-toggle:hover {
    color: #3466ff;
    background: rgba(52,102,255,0.08);
  }

  .password-toggle:active {
    transform: translateY(-50%) scale(0.95);
  }

  .password-toggle i {
    font-size: 16px;
  }

  /* Responsive */
  @media (max-width: 1024px) {
    .auth-stage {
      padding: 30px 20px;
    }

    .auth-container {
      grid-template-columns: 1fr;
      gap: 40px;
      max-width: 500px;
    }

    .brand-panel {
      text-align: center;
    }

    .brand-logo {
      margin: 0 auto 32px;
    }

    .brand-features {
      display: none;
    }

    .brand-title {
      font-size: 36px;
    }

    .brand-subtitle {
      margin: 0 auto;
    }
  }

  @media (max-width: 640px) {
    .auth-stage {
      padding: 20px 16px;
    }

    .auth-stage::before,
    .auth-stage::after {
      display: none;
    }

    .auth-container {
      gap: 24px;
    }

    .brand-logo {
      width: 80px;
      height: 80px;
      font-size: 40px;
      margin-bottom: 20px;
      border-radius: 20px;
    }

    .brand-title {
      font-size: 26px;
    }

    .brand-subtitle {
      font-size: 15px;
    }

    .form-panel {
      padding: 24px 20px;
      border-radius: 24px;
    }

    .form-header {
      margin-bottom: 24px;
    }

    .form-title {
      font-size: 22px;
    }

    .form-subtitle {
      font-size: 14px;
    }

    .form-grid {
      gap: 20px;
    }

    .input-group {
      grid-template-columns: 1fr;
      gap: 8px;
    }

    .input-label {
      padding-top: 0;
      font-size: 13px;
    }

    .form-input {
      height: 50px;
      font-size: 14px;
      padding: 0 16px 0 48px;
    }

    .input-icon {
      left: 16px;
      width: 18px;
      height: 18px;
    }

    .password-toggle {
      right: 12px;
      width: 32px;
      height: 32px;
    }

    .invalid-feedback {
      grid-column: 1;
      margin-top: 6px;
      font-size: 12px;
    }

    .form-options {
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }

    .checkbox-label {
      font-size: 13px;
    }

    .forgot-link {
      font-size: 13px;
    }

    .btn-submit {
      height: 50px;
      font-size: 15px;
    }

    .divider {
      margin: 20px 0;
      font-size: 13px;
      gap: 12px;
    }

    .btn-google {
      height: 50px;
      font-size: 14px;
      gap: 10px;
    }

    .google-icon {
      width: 18px;
      height: 18px;
    }

    .alert {
      padding: 12px 14px;
      font-size: 13px;
      margin-bottom: 20px;
    }
  }

  @media (max-width: 375px) {
    .form-panel {
      padding: 20px 16px;
    }

    .brand-title {
      font-size: 24px;
    }

    .form-title {
      font-size: 20px;
    }

    .checkbox-wrapper {
      gap: 8px;
    }

    .checkbox-input {
      width: 18px;
      height: 18px;
    }
  }
</style>
@endpush

@section('content')
<div class="auth-stage">
  <div class="auth-container">
    
    <!-- Panel Izquierdo - Branding -->
    <div class="brand-panel">
      <div class="brand-hero">
        <div class="brand-logo">
          <i class="fa-solid fa-paw"></i>
        </div>
        <h1 class="brand-title">
          Bienvenido a<br>
          <span class="highlight">QR-Pet Tag</span>
        </h1>
        <p class="brand-subtitle">
          La forma más moderna y segura de proteger a tus mascotas. Gestiona toda su información en un solo lugar.
        </p>
      </div>

      <div class="brand-features">
        <div class="feature-item">
          <div class="feature-icon">
            <i class="fa-solid fa-qrcode"></i>
          </div>
          <div class="feature-text">
            <strong>Tags Inteligentes</strong>
            <span>Códigos QR únicos para cada mascota</span>
          </div>
        </div>

        <div class="feature-item">
          <div class="feature-icon">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <div class="feature-text">
            <strong>Seguridad Total</strong>
            <span>Datos protegidos y acceso controlado</span>
          </div>
        </div>

        <div class="feature-item">
          <div class="feature-icon">
            <i class="fa-solid fa-bell"></i>
          </div>
          <div class="feature-text">
            <strong>Notificaciones Instantáneas</strong>
            <span>Alertas cuando escaneen el tag de tu mascota</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Panel Derecho - Formulario -->
    <div class="form-panel">
      <div class="form-header">
        <h2 class="form-title">Inicia sesión</h2>
        <p class="form-subtitle">Accede a tu cuenta para continuar</p>
      </div>

      @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="form-grid">
        @csrf

        <!-- Email -->
        <div class="input-group">
          <label for="email" class="input-label">Correo electrónico</label>
          <div class="input-wrapper">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z"/>
              <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6"/>
            </svg>
            <input 
              id="email" 
              type="email"
              class="form-input @error('email') is-invalid @enderror"
              name="email" 
              value="{{ old('email') }}" 
              required 
              autofocus 
              autocomplete="email"
              placeholder="tu@email.com">
          </div>
          @error('email')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Password -->
        <div class="input-group">
          <label for="password" class="input-label">Contraseña</label>
          <div class="input-wrapper">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M6 9V6.5A4.5 4.5 0 0 1 10.5 2 4.5 4.5 0 0 1 15 6.5V9"/>
              <rect x="3" y="9" width="18" height="12" rx="3" stroke="currentColor" stroke-width="1.8"/>
            </svg>
            <input 
              id="password" 
              type="password"
              class="form-input @error('password') is-invalid @enderror"
              name="password" 
              required 
              autocomplete="current-password"
              placeholder="Tu contraseña"
              style="padding-right: 52px;">
            <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Mostrar contraseña">
              <i class="fa-solid fa-eye" id="toggleIcon"></i>
            </button>
          </div>
          @error('password')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
          @enderror
        </div>

        <!-- Options -->
        <div class="form-options">
          <div class="checkbox-wrapper">
            <input 
              class="checkbox-input" 
              type="checkbox" 
              name="remember" 
              id="remember" 
              {{ old('remember') ? 'checked' : '' }}>
            <label class="checkbox-label" for="remember">Mantener sesión iniciada</label>
          </div>
          @if (Route::has('password.request'))
            <a class="forgot-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
          @endif
        </div>

        <button type="submit" class="btn-submit">Iniciar sesión</button>

        <div class="divider">O continúa con</div>

        <a href="{{ url('/auth/google/redirect') }}" class="btn-google" aria-label="Continuar con Google">
          <svg class="google-icon" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" focusable="false" aria-hidden="true">
            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.61l6.9-6.9C35.9 2.1 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l8.01 6.22C12.33 13.3 17.68 9.5 24 9.5z"/>
            <path fill="#4285F4" d="M46.5 24.5c0-1.59-.14-3.12-.41-4.59H24v9.18h12.7c-.55 2.95-2.2 5.45-4.7 7.12l7.19 5.58C43.94 37.4 46.5 31.44 46.5 24.5z"/>
            <path fill="#FBBC05" d="M10.57 19.44l-8.01-6.22C1.1 16.37 0 20.05 0 24c0 3.95 1.1 7.63 2.56 10.78l8.01-6.22C9.88 26.49 9.5 25.28 9.5 24s.38-2.49 1.07-3.56z"/>
            <path fill="#34A853" d="M24 48c6.48 0 11.94-2.14 15.93-5.8l-7.19-5.58c-2.02 1.36-4.61 2.16-8.74 2.16-6.32 0-11.67-3.8-13.43-9.94l-8.01 6.22C6.51 42.62 14.62 48 24 48z"/>
          </svg>
          <span>Google</span>
        </a>

      </form>
    </div>

  </div>
</div>

<script>
function togglePassword() {
  const passwordInput = document.getElementById('password');
  const toggleIcon = document.getElementById('toggleIcon');
  
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    toggleIcon.classList.remove('fa-eye');
    toggleIcon.classList.add('fa-eye-slash');
  } else {
    passwordInput.type = 'password';
    toggleIcon.classList.remove('fa-eye-slash');
    toggleIcon.classList.add('fa-eye');
  }
}
</script>
@endsection