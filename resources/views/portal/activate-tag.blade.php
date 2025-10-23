@extends('layouts.app')
@section('title','Activar un TAG')

@section('content')
<style>
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes pulse {
    0%, 100% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.05);
    }
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  .fade-in-up {
    animation: fadeInUp 0.6s ease-out;
  }

  .slide-in {
    animation: slideIn 0.5s ease-out;
  }

  .tag-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .tag-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
  }

  .tag-icon {
    font-size: 3rem;
    animation: pulse 2s infinite;
  }

  .form-container {
    background: #ffffff;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #e9ecef;
    transition: box-shadow 0.3s ease;
  }

  .form-container:hover {
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
  }

  .input-icon {
    position: relative;
  }

  .input-icon input {
    padding-left: 2.75rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    border-radius: 10px;
    font-size: 1rem;
    height: 3rem;
  }

  .input-icon input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .input-icon i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-size: 1.1rem;
  }

  .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  }

  .btn-outline-secondary {
    border: 2px solid #e9ecef;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-outline-secondary:hover {
    background: #f8f9fa;
    border-color: #dee2e6;
    transform: translateY(-2px);
  }

  .info-alert {
    background: linear-gradient(135deg, #e0f7ff 0%, #cceeff 100%);
    border: none;
    border-left: 4px solid #17a2b8;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    animation: slideIn 0.6s ease-out 0.3s both;
  }

  .info-alert i {
    color: #17a2b8;
    font-size: 1.3rem;
  }

  .steps-container {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    margin-top: 2rem;
  }

  .step-card {
    flex: 1;
    min-width: 200px;
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease-out;
  }

  .step-card:nth-child(2) {
    animation-delay: 0.1s;
  }

  .step-card:nth-child(3) {
    animation-delay: 0.2s;
  }

  .step-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.12);
  }

  .step-number {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
    margin-bottom: 1rem;
  }

  .step-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
  }
</style>

<div class="container py-4">
  <!-- Header Card -->
  <div class="tag-card mb-4 fade-in-up">
    <div class="d-flex align-items-center">
      <div class="tag-icon me-4">
        <i class="fa-solid fa-tag"></i>
      </div>
      <div>
        <h2 class="mb-2 fw-bold">Activar un TAG</h2>
        <p class="mb-0 opacity-90">Vincula tu placa de identificación y protege a tu mascota</p>
      </div>
    </div>
  </div>

  <!-- Steps -->
  <div class="steps-container">
    <div class="step-card">
      <div class="step-number">1</div>
      <div class="step-icon">📦</div>
      <h6 class="fw-bold mb-2">Recibe tu TAG</h6>
      <p class="text-muted small mb-0">Recibe el código enviado para vincular a tu mascota</p>
    </div>
    <div class="step-card">
      <div class="step-number">2</div>
      <div class="step-icon">⌨️</div>
      <h6 class="fw-bold mb-2">Ingresa el código</h6>
      <p class="text-muted small mb-0">Escribe el código de activación en el formulario</p>
    </div>
    <div class="step-card">
      <div class="step-number">3</div>
      <div class="step-icon">🐾</div>
      <h6 class="fw-bold mb-2">¡Listo!</h6>
      <p class="text-muted small mb-0">Edita y comparte el perfil de tu mascota</p>
    </div>
  </div>

  <!-- Form -->
  <div class="form-container mt-4 fade-in-up" style="max-width: 600px;">
    <form method="POST" action="{{ route('portal.activate-tag.store') }}">
      @csrf

      <div class="mb-4">
        <label class="form-label fw-semibold mb-3">
          <i class="fa-solid fa-key me-2 text-primary"></i>
          Código de activación *
        </label>
        <div class="input-icon">
          <i class="fa-solid fa-barcode"></i>
          <input 
            name="activation_code" 
            class="form-control" 
            required 
            value="{{ old('activation_code') }}" 
            placeholder="Ej: ABC123-XYZ"
            autocomplete="off">
        </div>
        @error('activation_code') 
          <div class="text-danger small mt-2">
            <i class="fa-solid fa-circle-exclamation me-1"></i>
            {{ $message }}
          </div> 
        @enderror
      </div>

      <div class="d-flex gap-3 flex-wrap">
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-check-circle me-2"></i>
          Activar TAG
        </button>
        <a class="btn btn-outline-secondary" href="{{ route('portal.pets.index') }}">
          <i class="fa-solid fa-arrow-left me-2"></i>
          Cancelar
        </a>
      </div>
    </form>
  </div>

  <!-- Info Alert -->
  <div class="info-alert mt-4" style="max-width: 600px;">
    <div class="d-flex align-items-start">
      <i class="fa-solid fa-circle-info me-3 mt-1"></i>
      <div>
        <strong>¿No encuentras tu código?</strong>
        <p class="mb-0 mt-1">Contáctanos y con gusto te ayudaremos a reemitir tu código de activación.</p>
      </div>
    </div>
  </div>
</div>
@endsection