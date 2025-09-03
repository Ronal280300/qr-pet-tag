@extends('layouts.app')

@section('title', 'Restablecer contraseña - QR-Pet Tag')

@push('styles')
<style>
  .auth-stage{min-height:calc(100vh - 140px);display:flex;align-items:center;justify-content:center;padding:24px}
  .auth-card{width:min(720px,96vw);background:#fff;border-radius:16px;box-shadow:0 80px 60px rgba(0,0,0,.03),0 30px 30px rgba(0,0,0,.05),0 12px 20px rgba(0,0,0,.06);padding:48px 40px 28px}
  .logo_box{width:78px;height:78px;border-radius:14px;border:1px solid #F0F0F2;background:linear-gradient(180deg,rgba(248,248,248,0) 48%, #F8F8F8 100%);margin:0 auto 10px;display:grid;place-items:center;color:#1e7cf2;font-size:30px}
  .auth-title{font-weight:800;font-size:28px;text-align:center;margin:10px 0 6px;color:#1b1c1f}
  .auth-sub{color:#8B8E98;text-align:center;max-width:520px;margin:0 auto 22px;line-height:1.35rem}
  .grid{display:grid;gap:14px}
  .field{position:relative}
  .field label{display:block;font-size:14px;color:#6c7280;margin-bottom:6px}
  .in{width:100%;height:48px;border-radius:12px;border:1px solid #e6e7eb;background:#fff;padding:0 14px 0 44px;font-size:16px;outline:none}
  .icon-l{position:absolute;left:14px;bottom:12px;width:20px;height:20px;opacity:.9}
  .btn-primary-xl{width:100%;height:50px;border:0;border-radius:12px;background:#115DFC;color:#fff;font-weight:800;letter-spacing:.2px}
</style>
@endpush

@section('content')
<div class="auth-stage">
  <div class="auth-card">
    <div class="logo_box"><i class="fa-solid fa-lock"></i></div>
    <h1 class="auth-title">Restablecer contraseña</h1>
    <p class="auth-sub">Ingresa tu nueva contraseña para completar el proceso.</p>

    <form method="POST" action="{{ route('password.update') }}" class="grid">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">
      <input type="hidden" name="email" value="{{ request('email') }}">

      {{-- Nueva contraseña --}}
      <div class="field">
        <label for="password">Nueva contraseña</label>
        <svg class="icon-l" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                d="M6 9V6.5A4.5 4.5 0 0 1 10.5 2 4.5 4.5 0 0 1 15 6.5V9"/>
          <rect x="3" y="9" width="18" height="12" rx="3" stroke="#111827" stroke-width="1.6"/>
        </svg>
        <input id="password" type="password" class="in @error('password') is-invalid @enderror"
               name="password" required autocomplete="new-password" placeholder="••••••••">
        @error('password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
      </div>

      {{-- Confirmación --}}
      <div class="field">
        <label for="password_confirmation">Confirmar contraseña</label>
        <svg class="icon-l" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
          <rect x="3" y="9" width="18" height="12" rx="3" stroke="#111827" stroke-width="1.6"/>
        </svg>
        <input id="password_confirmation" type="password" class="in"
               name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
      </div>

      <button type="submit" class="btn-primary-xl">Guardar nueva contraseña</button>
    </form>
  </div>
</div>
@endsection
