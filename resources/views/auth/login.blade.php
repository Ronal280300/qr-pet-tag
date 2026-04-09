@extends('layouts.app')

@section('title', 'Ingresar a mi Portal — PetScan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
  :root {
    --ps-font: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    --ps-bg: #F8FAFC;
    --ps-primary: #0F172A;
    --ps-accent: #2563EB;
    --ps-border: #E2E8F0;
    --ps-text-900: #0F172A;
    --ps-text-600: #475569;
    --ps-text-400: #94A3B8;
  }

  body {
    background-color: var(--ps-bg);
    font-family: var(--ps-font);
    -webkit-font-smoothing: antialiased;
  }

  .ps-auth-layout {
    min-height: calc(100vh - 80px); /* Restando un navbar teórico */
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 24px;
    position: relative;
    overflow: hidden;
  }

  /* Decorative background */
  .ps-auth-layout::before {
    content: '';
    position: absolute;
    top: -150px; left: 50%;
    transform: translateX(-50%);
    width: 800px; height: 400px;
    background: radial-gradient(ellipse at bottom, rgba(37,99,235,0.08) 0%, rgba(248,250,252,0) 70%);
    pointer-events: none;
    z-index: 0;
  }

  .ps-auth-card {
    background: #FFFFFF;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 20px 40px -10px rgba(15,23,42,0.08);
    border-radius: 24px;
    width: 100%;
    max-width: 440px;
    padding: 48px 40px;
    position: relative;
    z-index: 1;
  }

  .ps-auth-header {
    text-align: center;
    margin-bottom: 32px;
  }

  .ps-auth-logo {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 56px; height: 56px;
    background: var(--ps-accent);
    color: #FFFFFF;
    border-radius: 16px;
    font-size: 24px;
    margin-bottom: 24px;
    box-shadow: 0 10px 15px -3px rgba(37,99,235,0.25);
  }

  .ps-auth-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--ps-text-900);
    letter-spacing: -0.5px;
    margin-bottom: 8px;
  }

  .ps-auth-subtitle {
    font-size: 15px;
    color: var(--ps-text-600);
    margin: 0;
  }

  /* Form Elements */
  .ps-form-group {
    margin-bottom: 20px;
  }

  .ps-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--ps-text-900);
    margin-bottom: 8px;
  }

  .ps-input-wrapper {
    position: relative;
  }

  .ps-input {
    width: 100%;
    height: 48px;
    background: #FFFFFF;
    border: 1px solid var(--ps-border);
    border-radius: 12px;
    padding: 0 16px;
    font-size: 15px;
    color: var(--ps-text-900);
    font-family: var(--ps-font);
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
  }

  .ps-input::placeholder {
    color: var(--ps-text-400);
  }

  .ps-input:focus {
    outline: none;
    border-color: var(--ps-accent);
    box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
  }

  .ps-input.is-invalid {
    border-color: #EF4444;
    background: #FEF2F2;
  }

  .ps-pw-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--ps-text-400);
    cursor: pointer;
    padding: 4px;
    font-size: 14px;
    transition: color 0.2s ease;
  }

  .ps-pw-toggle:hover {
    color: var(--ps-text-900);
  }

  .ps-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    margin-top: -4px;
  }

  .ps-checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
  }

  .ps-checkbox {
    width: 16px;
    height: 16px;
    border-radius: 4px;
    border: 1px solid var(--ps-border);
    accent-color: var(--ps-accent);
    cursor: pointer;
  }

  .ps-checkbox-label {
    font-size: 13px;
    color: var(--ps-text-600);
    font-weight: 500;
    cursor: pointer;
    user-select: none;
  }

  .ps-link {
    font-size: 13px;
    font-weight: 600;
    color: var(--ps-accent);
    text-decoration: none;
    transition: color 0.2s;
  }

  .ps-link:hover {
    color: #1D4ED8;
    text-decoration: underline;
    text-underline-offset: 2px;
  }

  /* Buttons */
  .ps-btn {
    width: 100%;
    height: 48px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    text-decoration: none;
  }

  .ps-btn-primary {
    background-color: var(--ps-primary);
    color: #FFFFFF;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
  }

  .ps-btn-primary:hover {
    background-color: var(--ps-primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 12px -2px rgba(0,0,0,0.15);
  }

  .ps-divider {
    display: flex;
    align-items: center;
    text-align: center;
    color: var(--ps-text-400);
    font-size: 13px;
    margin: 24px 0;
  }

  .ps-divider::before,
  .ps-divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid var(--ps-border);
  }

  .ps-divider::before { margin-right: 12px; }
  .ps-divider::after { margin-left: 12px; }

  .ps-btn-google {
    background-color: #FFFFFF;
    border: 1px solid var(--ps-border);
    color: var(--ps-text-900);
  }

  .ps-btn-google:hover {
    background-color: #F8FAFC;
    border-color: #CBD5E1;
  }

  .ps-btn-google img {
    width: 18px;
    height: 18px;
    margin-right: 10px;
  }

  .ps-register-prompt {
    text-align: center;
    margin-top: 32px;
    font-size: 14px;
    color: var(--ps-text-600);
  }

  .ps-error-msg {
    color: #EF4444;
    font-size: 12px;
    font-weight: 500;
    margin-top: 6px;
    display: block;
  }

  /* Global alerts */
  .ps-alert {
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 24px;
    text-align: center;
  }
  .ps-alert-success { background: #DCFCE7; color: #166534; }
  .ps-alert-danger { background: #FEF2F2; color: #991B1B; }

  @media (max-width: 480px) {
    .ps-auth-layout { padding: 24px 16px; }
    .ps-auth-card { padding: 40px 24px; border-radius: 20px; border:none; box-shadow: 0 10px 40px rgba(0,0,0,0.05); }
    .ps-auth-logo { width: 48px; height: 48px; font-size: 20px; }
    .ps-auth-title { font-size: 22px; }
  }
</style>
@endpush

@section('content')
<div class="ps-auth-layout">
  <div class="ps-auth-card">
    
    <div class="ps-auth-header">
      <div class="ps-auth-logo">
        <i class="fa-solid fa-paw"></i>
      </div>
      <h1 class="ps-auth-title">Bienvenido de vuelta</h1>
      <p class="ps-auth-subtitle">Ingresa para administrar el panel de tus mascotas.</p>
    </div>

    @if (session('status'))
      <div class="ps-alert ps-alert-success">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="ps-alert ps-alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email Field -->
      <div class="ps-form-group">
        <label for="email" class="ps-label">Correo electrónico</label>
        <div class="ps-input-wrapper">
          <input 
            id="email" 
            type="email" 
            name="email" 
            class="ps-input @error('email') is-invalid @enderror" 
            value="{{ old('email') }}" 
            placeholder="tumail@ejemplo.com"
            required autofocus autocomplete="email">
        </div>
        @error('email')
          <span class="ps-error-msg">{{ $message }}</span>
        @enderror
      </div>

      <!-- Password Field -->
      <div class="ps-form-group">
        <label for="password" class="ps-label">Contraseña</label>
        <div class="ps-input-wrapper">
          <input 
            id="password" 
            type="password" 
            name="password" 
            class="ps-input @error('password') is-invalid @enderror" 
            placeholder="••••••••"
            required autocomplete="current-password"
            style="padding-right: 40px;">
          <button type="button" class="ps-pw-toggle" onclick="togglePassword()" aria-label="Mostrar/Ocultar">
            <i class="fa-regular fa-eye" id="toggleIcon"></i>
          </button>
        </div>
        @error('password')
          <span class="ps-error-msg">{{ $message }}</span>
        @enderror
      </div>

      <!-- Remember & Forgot -->
      <div class="ps-options">
        <label class="ps-checkbox-wrapper">
          <input type="checkbox" name="remember" id="remember" class="ps-checkbox" {{ old('remember') ? 'checked' : '' }}>
          <span class="ps-checkbox-label">Mantener sesión</span>
        </label>
        
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="ps-link">¿Olvidaste tu contraseña?</a>
        @endif
      </div>

      <!-- Submit Button -->
      <button type="submit" class="ps-btn ps-btn-primary">
        Ingresar al portal
      </button>

      <div class="ps-divider">O continuar con</div>

      <!-- Google Auth -->
      <a href="{{ url('/auth/google/redirect') }}" class="ps-btn ps-btn-google">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="18px" height="18px" style="margin-right:8px;">
          <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.61l6.9-6.9C35.9 2.1 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l8.01 6.22C12.33 13.3 17.68 9.5 24 9.5z"/>
          <path fill="#4285F4" d="M46.5 24.5c0-1.59-.14-3.12-.41-4.59H24v9.18h12.7c-.55 2.95-2.2 5.45-4.7 7.12l7.19 5.58C43.94 37.4 46.5 31.44 46.5 24.5z"/>
          <path fill="#FBBC05" d="M10.57 19.44l-8.01-6.22C1.1 16.37 0 20.05 0 24c0 3.95 1.1 7.63 2.56 10.78l8.01-6.22C9.88 26.49 9.5 25.28 9.5 24s.38-2.49 1.07-3.56z"/>
          <path fill="#34A853" d="M24 48c6.48 0 11.94-2.14 15.93-5.8l-7.19-5.58c-2.02 1.36-4.61 2.16-8.74 2.16-6.32 0-11.67-3.8-13.43-9.94l-8.01 6.22C6.51 42.62 14.62 48 24 48z"/>
        </svg>
        Google
      </a>
      
    </form>

    <div class="ps-register-prompt">
      ¿No tienes una cuenta aún? <a href="{{ route('register') }}" class="ps-link">Regístrate aquí</a>
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
