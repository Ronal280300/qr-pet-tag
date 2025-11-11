@extends('layouts.app')
@section('title', 'Completa tu perfil')

@push('styles')
<style>
  .onboarding-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
  }

  .onboarding-card {
    max-width: 500px;
    width: 100%;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(15, 23, 42, 0.1);
    padding: 48px 40px;
    text-align: center;
  }

  .onboarding-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: linear-gradient(135deg, #4e89e8, #3a6bb8);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    color: white;
    box-shadow: 0 8px 24px rgba(78, 137, 232, 0.3);
  }

  .onboarding-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 12px;
  }

  .onboarding-subtitle {
    font-size: 16px;
    color: #64748b;
    margin-bottom: 32px;
    line-height: 1.6;
  }

  .form-group {
    text-align: left;
    margin-bottom: 24px;
  }

  .form-label {
    display: block;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 8px;
    font-size: 14px;
  }

  .form-control {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    outline: none;
    border-color: #4e89e8;
    box-shadow: 0 0 0 4px rgba(78, 137, 232, 0.1);
  }

  .btn-primary {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #4e89e8, #3a6bb8);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 24px rgba(78, 137, 232, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(78, 137, 232, 0.4);
  }

  .help-text {
    font-size: 13px;
    color: #94a3b8;
    margin-top: 8px;
  }

  .invalid-feedback {
    color: #ef4444;
    font-size: 13px;
    margin-top: 8px;
    text-align: left;
  }
</style>
@endpush

@section('content')
<div class="onboarding-container">
  <div class="onboarding-card">
    <div class="onboarding-icon">
      <i class="fa-solid fa-phone"></i>
    </div>

    <h1 class="onboarding-title">¡Bienvenido, {{ Auth::user()->name }}!</h1>
    <p class="onboarding-subtitle">
      Para completar tu registro, necesitamos tu número de teléfono.
      Esto nos permitirá enviarte notificaciones importantes sobre tus mascotas.
    </p>

    <form method="POST" action="{{ route('onboarding.store') }}">
      @csrf

      <div class="form-group">
        <label for="phone" class="form-label">
          <i class="fa-solid fa-phone me-1"></i> Número de teléfono
        </label>
        <input
          type="text"
          id="phone"
          name="phone"
          class="form-control @error('phone') is-invalid @enderror"
          value="{{ old('phone') }}"
          placeholder="Ej: +506 6290-1184"
          required
          autofocus
        >
        <div class="help-text">
          <i class="fa-solid fa-info-circle me-1"></i>
          Incluye el código de país (ej: +506 para Costa Rica)
        </div>
        @error('phone')
          <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn-primary">
        <i class="fa-solid fa-check me-2"></i>
        Continuar
      </button>
    </form>
  </div>
</div>
@endsection
