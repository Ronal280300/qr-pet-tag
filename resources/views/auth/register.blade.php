@extends('layouts.app')

@section('title', 'Registrarse - QR-Pet Tag')

@push('styles')
<style>
  /* Reset para prevenir overflow */
  * {
    box-sizing: border-box;
  }

  html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    overflow-x: hidden;
  }

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

  @keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  .auth-stage {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    position: relative;
    width: 100%;
    max-width: 100vw;
    margin: 0 auto;
  }

  /* Decoración de fondo sutil */
  .auth-stage::before {
    content: '';
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(17,93,252,0.05) 0%, transparent 70%);
    top: -200px;
    left: -200px;
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
    right: -100px;
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
    box-sizing: border-box;
    margin: 0 auto;
  }

  /* Panel izquierdo - Branding */
  .brand-panel {
    display: flex;
    flex-direction: column;
    gap: 32px;
    animation: slideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
  }

  .brand-hero {
    position: relative;
    width: 100%;
    max-width: 100%;
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
    word-wrap: break-word;
    overflow-wrap: break-word;
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
    width: 100%;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .brand-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 24px;
    animation: fadeIn 0.8s ease-out 0.5s both;
  }

  .stat-card {
    padding: 20px;
    background: linear-gradient(135deg, rgba(17,93,252,0.04) 0%, rgba(30,124,242,0.02) 100%);
    border-radius: 16px;
    border: 1px solid rgba(17,93,252,0.08);
    transition: all 0.3s ease;
    min-width: 0;
  }

  .stat-card:hover {
    transform: translateY(-4px);
    border-color: rgba(17,93,252,0.15);
    box-shadow: 0 8px 24px rgba(17,93,252,0.1);
  }

  .stat-number {
    font-size: 28px;
    font-weight: 900;
    color: #115DFC;
    display: block;
    margin-bottom: 4px;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .stat-label {
    font-size: 13px;
    color: #5f6c7b;
    font-weight: 600;
    word-wrap: break-word;
    overflow-wrap: break-word;
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
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    margin: 0 auto;
  }
  
  /* Scroll solo en desktop cuando sea necesario */
  @media (min-width: 1025px) {
    .form-panel {
      max-height: 90vh;
      overflow-y: auto;
    }
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

  /* Scrollbar personalizado */
  .form-panel::-webkit-scrollbar {
    width: 6px;
  }

  .form-panel::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
  }

  .form-panel::-webkit-scrollbar-thumb {
    background: #3466ff;
    border-radius: 10px;
  }

  .form-panel::-webkit-scrollbar-thumb:hover {
    background: #115DFC;
  }

  .form-header {
    text-align: center;
    margin-bottom: 32px;
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

  .form-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
  }

  .input-group {
    position: relative;
    display: grid;
    grid-template-columns: 140px 1fr;
    gap: 16px;
    align-items: start;
    width: 100%;
    min-width: 0;
  }

  .input-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #3d4451;
    transition: color 0.3s ease;
    padding-top: 18px;
    text-align: left;
    word-wrap: break-word;
  }

  .input-wrapper {
    position: relative;
    width: 100%;
    min-width: 0;
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
    height: 52px;
    padding: 0 18px 0 52px;
    border: 2px solid #e5e7eb;
    border-radius: 14px;
    font-size: 15px;
    color: #0f1419;
    background: #fafbfc;
    outline: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-sizing: border-box;
    max-width: 100%;
    min-width: 0;
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
    margin-top: 6px;
    font-size: 12px;
    color: #ef4444;
    animation: fadeIn 0.3s ease-out;
    grid-column: 2;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  /* Select sin icono */
  select.form-input {
    padding-left: 18px;
    appearance: auto;
    cursor: pointer;
  }

  /* Grid telefono */
  .phone-grid {
    display: grid;
    grid-template-columns: 110px 1fr;
    gap: 12px;
    width: 100%;
    min-width: 0;
  }

  .phone-help {
    grid-column: 2;
    font-size: 12px;
    color: #5f6c7b;
    margin-top: 6px;
    word-wrap: break-word;
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

  .password-toggle i {
    font-size: 16px;
  }

  .btn-submit {
    width: 100%;
    height: 52px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
    color: white;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 24px rgba(17,93,252,0.3);
    margin-top: 8px;
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
    margin: 24px 0 20px;
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
    height: 52px;
    border: 2px solid #e5e7eb;
    border-radius: 14px;
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

  .terms {
    margin-top: 20px;
    text-align: center;
    font-size: 13px;
    color: #5f6c7b;
    line-height: 1.5;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .terms a {
    color: #3466ff;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
  }

  .terms a:hover {
    color: #115DFC;
    text-decoration: underline;
  }

  /* Responsive */
  @media (max-width: 1024px) {
    .auth-container {
      grid-template-columns: 1fr;
      gap: 40px;
      max-width: 600px;
      padding: 0 20px;
    }

    .brand-panel {
      text-align: center;
    }

    .brand-logo {
      margin: 0 auto 32px;
      width: 100px;
      height: 100px;
      font-size: 48px;
    }

    .brand-title {
      font-size: 36px;
    }

    .brand-subtitle {
      margin: 0 auto;
      font-size: 16px;
    }

    .brand-stats {
      max-width: 400px;
      margin: 24px auto 0;
    }

    .form-panel {
      max-height: none;
      overflow-y: visible;
      overflow-x: hidden;
    }
  }

  @media (max-width: 640px) {
    .auth-stage {
      padding: 30px 16px;
      min-height: auto;
      align-items: flex-start;
    }

    .auth-container {
      gap: 32px;
      padding: 0;
      margin: 0 auto;
      width: 100%;
      max-width: 100%;
    }

    .brand-panel {
      padding: 0;
      width: 100%;
    }

    .brand-logo {
      width: 80px;
      height: 80px;
      font-size: 40px;
      border-radius: 20px;
      margin-bottom: 24px;
    }

    .brand-title {
      font-size: 28px;
      word-wrap: break-word;
      overflow-wrap: break-word;
      max-width: 100%;
    }

    .brand-subtitle {
      font-size: 15px;
      padding: 0;
      max-width: 100%;
      word-wrap: break-word;
      overflow-wrap: break-word;
    }

    .brand-stats {
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      width: 100%;
    }

    .stat-card {
      padding: 16px;
      min-width: 0;
    }

    .stat-number {
      font-size: 24px;
      word-wrap: break-word;
    }

    .stat-label {
      font-size: 12px;
      word-wrap: break-word;
    }

    .form-panel {
      padding: 28px 20px;
      border-radius: 24px;
      margin: 0;
      width: 100%;
      max-width: 100%;
    }

    .form-header {
      margin-bottom: 28px;
    }

    .form-title {
      font-size: 24px;
    }

    .form-subtitle {
      font-size: 14px;
    }

    .form-grid {
      gap: 18px;
      width: 100%;
    }

    .input-group {
      grid-template-columns: 1fr;
      gap: 8px;
      width: 100%;
    }

    .input-label {
      padding-top: 0;
      font-size: 13px;
    }

    .input-wrapper {
      width: 100%;
    }

    .form-input {
      height: 50px;
      font-size: 16px;
      width: 100%;
    }

    .invalid-feedback {
      grid-column: 1;
      font-size: 12px;
    }

    .phone-grid {
      grid-template-columns: 105px 1fr;
      gap: 8px;
      width: 100%;
    }

    .phone-help {
      grid-column: 1 / -1;
      font-size: 11px;
      margin-top: 4px;
    }

    .btn-submit {
      height: 50px;
      font-size: 15px;
      width: 100%;
    }

    .btn-google {
      height: 50px;
      font-size: 14px;
      width: 100%;
    }

    .divider {
      margin: 20px 0 16px;
      font-size: 13px;
    }

    .terms {
      font-size: 12px;
      margin-top: 16px;
      padding: 0;
      max-width: 100%;
    }
  }

  @media (max-width: 400px) {
    .auth-stage {
      padding: 16px 0;
    }

    .brand-panel {
      padding: 0 12px;
    }

    .brand-logo {
      width: 70px;
      height: 70px;
      font-size: 36px;
    }

    .brand-title {
      font-size: 24px;
    }

    .brand-subtitle {
      font-size: 14px;
    }

    .form-panel {
      padding: 20px 12px;
      margin: 0 12px;
    }

    .form-title {
      font-size: 22px;
    }

    .input-group {
      gap: 6px;
    }

    .form-input {
      height: 48px;
      padding-left: 46px;
      font-size: 15px;
    }

    .input-icon {
      width: 18px;
      height: 18px;
      left: 14px;
    }

    select.form-input {
      padding-left: 12px;
      font-size: 14px;
    }

    .phone-grid {
      grid-template-columns: 95px 1fr;
    }

    .password-toggle {
      width: 32px;
      height: 32px;
      right: 14px;
    }

    .password-toggle i {
      font-size: 14px;
    }

    .btn-submit,
    .btn-google {
      height: 48px;
      font-size: 14px;
    }

    .stat-card {
      padding: 12px;
    }

    .stat-number {
      font-size: 20px;
    }

    .stat-label {
      font-size: 11px;
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
          Únete a<br>
          <span class="highlight">QR-Pet Tag</span>
        </h1>
        <p class="brand-subtitle">
          Crea tu cuenta gratis y comienza a proteger a tus mascotas con tecnología QR de última generación.
        </p>
      </div>

      <div class="brand-stats">
        <div class="stat-card">
          <span class="stat-number">5min</span>
          <span class="stat-label">Registro rápido</span>
        </div>
        <div class="stat-card">
          <span class="stat-number">Gratis</span>
          <span class="stat-label">Sin costo inicial</span>
        </div>
        <div class="stat-card">
          <span class="stat-number">24/7</span>
          <span class="stat-label">Soporte disponible</span>
        </div>
        <div class="stat-card">
          <span class="stat-number">Seguro</span>
          <span class="stat-label">Datos protegidos</span>
        </div>
      </div>
    </div>

    <!-- Panel Derecho - Formulario -->
    <div class="form-panel">
      <div class="form-header">
        <h2 class="form-title">Crea tu cuenta</h2>
        <p class="form-subtitle">Completa el formulario para comenzar</p>
      </div>

      <form method="POST" action="{{ route('register') }}" class="form-grid">
        @csrf

        <!-- Nombre -->
        <div class="input-group">
          <label for="name" class="input-label">Nombre</label>
          <div class="input-wrapper">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none">
              <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M15.5 7.5A4.5 4.5 0 1 1 6.5 7.5 4.5 4.5 0 0 1 15.5 7.5ZM20 21c0-4.418-4.03-8-9-8s-9 3.582-9 8" />
            </svg>
            <input 
              id="name" 
              name="name" 
              type="text" 
              class="form-input @error('name') is-invalid @enderror"
              value="{{ old('name') }}" 
              placeholder="Tu nombre completo" 
              required 
              autocomplete="name" 
              autofocus>
          </div>
          @error('name')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <!-- Email -->
        <div class="input-group">
          <label for="email" class="input-label">Email</label>
          <div class="input-wrapper">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none">
              <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z" />
              <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6" />
            </svg>
            <input 
              id="email" 
              name="email" 
              type="email" 
              class="form-input @error('email') is-invalid @enderror"
              value="{{ old('email') }}" 
              placeholder="tu@email.com" 
              required 
              autocomplete="email">
          </div>
          @error('email')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <!-- Teléfono -->
        <div class="input-group">
          <label for="phone_local" class="input-label">Teléfono</label>
          <div style="width: 100%;">
            <div class="phone-grid">
              <select id="phone_code" name="phone_code" class="form-input @error('phone_code') is-invalid @enderror">
                <option value="506" @selected(old('phone_code','506')=='506')>🇨🇷 +506</option>
                <option value="507" @selected(old('phone_code')=='507')>🇵🇦 +507</option>
                <option value="505" @selected(old('phone_code')=='505')>🇳🇮 +505</option>
                <option value="502" @selected(old('phone_code')=='502')>🇬🇹 +502</option>
                <option value="503" @selected(old('phone_code')=='503')>🇸🇻 +503</option>
                <option value="504" @selected(old('phone_code')=='504')>🇭🇳 +504</option>
                <option value="52" @selected(old('phone_code')=='52')>🇲🇽 +52</option>
                <option value="1" @selected(old('phone_code')=='1')>🇺🇸 +1</option>
              </select>

              <div class="input-wrapper">
                <svg class="input-icon" viewBox="0 0 24 24" fill="none">
                  <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                    d="M4 5c0-1.105.895-2 2-2h1.5c.74 0 1.386.405 1.732 1.036l.788 1.417a2 2 0 0 1-.223 2.224l-.82.984a12.05 12.05 0 0 0 5.078 5.078l.984-.82a2 2 0 0 1 2.224-.223l1.417.788A2 2 0 0 1 20 17.5V19a2 2 0 0 1-2 2h-1C9.373 21 3 14.627 3 7V6a1 1 0 0 1 1-1Z" />
                </svg>
                <input 
                  id="phone_local" 
                  name="phone_local" 
                  type="text"
                  class="form-input @error('phone_local') is-invalid @enderror"
                  value="{{ old('phone_local') }}" 
                  placeholder="8888-8888">
              </div>
            </div>
            <small class="phone-help">Compatible con WhatsApp</small>
            @error('phone_code')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
            @error('phone_local')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
            @error('phone')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
          </div>
        </div>

        <!-- Contraseña -->
        <div class="input-group">
          <label for="password" class="input-label">Contraseña</label>
          <div class="input-wrapper">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none">
              <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                d="M6 9V6.5A4.5 4.5 0 0 1 10.5 2 4.5 4.5 0 0 1 15 6.5V9" />
              <rect x="3" y="9" width="18" height="12" rx="3" stroke="currentColor" stroke-width="1.8" />
            </svg>
            <input 
              id="password" 
              name="password" 
              type="password"
              class="form-input @error('password') is-invalid @enderror" 
              placeholder="Mínimo 8 caracteres" 
              required 
              autocomplete="new-password"
              style="padding-right: 52px;">
            <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')" aria-label="Mostrar contraseña">
              <i class="fa-solid fa-eye" id="toggleIcon1"></i>
            </button>
          </div>
          @error('password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
        </div>

        <!-- Confirmar Contraseña -->
        <div class="input-group">
          <label for="password-confirm" class="input-label">Confirmar</label>
          <div class="input-wrapper">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none">
              <path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
              <rect x="3" y="9" width="18" height="12" rx="3" stroke="currentColor" stroke-width="1.8" />
            </svg>
            <input 
              id="password-confirm" 
              name="password_confirmation" 
              type="password"
              class="form-input" 
              placeholder="Repite tu contraseña" 
              required 
              autocomplete="new-password"
              style="padding-right: 52px;">
            <button type="button" class="password-toggle" onclick="togglePassword('password-confirm', 'toggleIcon2')" aria-label="Mostrar contraseña">
              <i class="fa-solid fa-eye" id="toggleIcon2"></i>
            </button>
          </div>
        </div>

        <button class="btn-submit" type="submit">Crear cuenta</button>

        <div class="divider">O regístrate con</div>

        <a href="{{ url('/auth/google/redirect') }}" class="btn-google">
          <img class="google-icon" src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
          <span>Google</span>
        </a>

        <p class="terms">
          Al registrarte aceptas nuestros
          <a href="{{ route('legal.terms') }}">Términos de uso y Condiciones</a>.
        </p>
      </form>
    </div>

  </div>
</div>

<script>
function togglePassword(inputId, iconId) {
  const passwordInput = document.getElementById(inputId);
  const toggleIcon = document.getElementById(iconId);
  
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