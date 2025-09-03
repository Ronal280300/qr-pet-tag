@extends('layouts.app')

@section('title', 'Registrarse - QR-Pet Tag')

@push('styles')
<style>
  /* ===== Card estilo minimal ===== */
  .auth-stage {
    min-height: calc(100vh - 140px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
  }

  .auth-card {
    width: min(720px, 96vw);
    background: #fff;
    border-radius: 16px;
    box-shadow:
      0 80px 60px rgba(0, 0, 0, .03),
      0 30px 30px rgba(0, 0, 0, .05),
      0 12px 20px rgba(0, 0, 0, .06);
    padding: 48px 40px 28px;
  }

  .logo_box {
    width: 78px;
    height: 78px;
    border-radius: 14px;
    border: 1px solid #F0F0F2;
    background: linear-gradient(180deg, rgba(248, 248, 248, 0) 48%, #F8F8F8 100%);
    margin: 0 auto 10px;
    display: grid;
    place-items: center;
    color: #1e7cf2;
    font-size: 30px;
    filter: drop-shadow(0 .5px .5px #EFEFEF) drop-shadow(0 1px .5px rgba(239, 239, 239, .5));
  }

  .auth-title {
    font-weight: 800;
    font-size: 28px;
    text-align: center;
    margin: 10px 0 6px;
    color: #1b1c1f;
    letter-spacing: .2px
  }

  .auth-sub {
    color: #8B8E98;
    text-align: center;
    max-width: 520px;
    margin: 0 auto 22px;
    line-height: 1.35rem
  }

  .grid {
    display: grid;
    gap: 14px
  }

  .grid-2 {
    grid-template-columns: 1fr 2fr
  }

  @media (max-width:560px) {
    .grid-2 {
      grid-template-columns: 1fr
    }
  }

  .field {
    position: relative
  }

  .field label {
    display: block;
    font-size: 14px;
    color: #6c7280;
    margin-bottom: 6px
  }

  .in {
    width: 100%;
    height: 48px;
    border-radius: 12px;
    border: 1px solid #e6e7eb;
    background: #fff;
    padding: 0 14px 0 44px;
    font-size: 16px;
    outline: none;
    transition: border .2s, box-shadow .2s;
  }

  select.in {
    padding-left: 14px;
    appearance: auto
  }

  .in:focus {
    border-color: #3466ff;
    box-shadow: 0 0 0 3px rgba(52, 102, 255, .15)
  }

  .in.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, .14)
  }

  .icon-l {
    position: absolute;
    left: 14px;
    bottom: 12px;
    width: 20px;
    height: 20px;
    opacity: .9
  }

  .invalid-feedback {
    display: block;
    font-size: .9rem
  }

  .btn-primary-xl {
    width: 100%;
    height: 50px;
    border: 0;
    border-radius: 12px;
    background: #115DFC;
    color: #fff;
    font-weight: 800;
    letter-spacing: .2px
  }

  .btn-primary-xl:hover {
    filter: brightness(.98)
  }

  .sep {
    display: flex;
    align-items: center;
    gap: 14px;
    color: #9aa0aa;
    margin: 12px 0
  }

  .sep .line {
    flex: 1;
    height: 1px;
    background: #e9ebef
  }

  /* Botón social genérico */
  .btn-ghost {
    width: 100%;
    height: 50px;
    border-radius: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #e6e7eb;
    color: #1b1c1f;
    text-decoration: none;
  }

  .btn-ghost:hover {
    background: #f9fafb
  }

  /* Ajuste ícono Google */
  .btn-ghost img {
    width: 18px;
    height: 18px;
    display: block
  }

  .terms {
    margin: 10px 0 0;
    text-align: center;
    color: #8B8E98
  }

  .terms a {
    color: #8B8E98;
    text-decoration: underline
  }
</style>
@endpush

@section('content')
<div class="auth-stage">
  <div class="auth-card">

    <div class="logo_box"><i class="fa-solid fa-paw"></i></div>
    <h1 class="auth-title">Crea tu cuenta</h1>
    <p class="auth-sub">Comienza a usar la app: crea una cuenta y disfruta la experiencia.</p>

    <form method="POST" action="{{ route('register') }}" class="grid">
      @csrf

      {{-- Nombre --}}
      <div class="field">
        <label for="name">Nombre</label>
        <svg class="icon-l" viewBox="0 0 24 24" fill="none">
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M15.5 7.5A4.5 4.5 0 1 1 6.5 7.5 4.5 4.5 0 0 1 15.5 7.5ZM20 21c0-4.418-4.03-8-9-8s-9 3.582-9 8" />
        </svg>
        <input id="name" name="name" type="text" class="in @error('name') is-invalid @enderror"
          value="{{ old('name') }}" placeholder="Tu nombre completo" required autocomplete="name" autofocus>
        @error('name')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
      </div>

      {{-- Email --}}
      <div class="field">
        <label for="email">Correo electrónico</label>
        <svg class="icon-l" viewBox="0 0 24 24" fill="none">
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z" />
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6" />
        </svg>
        <input id="email" name="email" type="email" class="in @error('email') is-invalid @enderror"
          value="{{ old('email') }}" placeholder="name@mail.com" required autocomplete="email">
        @error('email')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
      </div>

      {{-- Teléfono (prefijo + local) --}}
      <div class="field">
        <label for="phone_local">Teléfono (WhatsApp)</label>
        <div class="grid grid-2">
          <select id="phone_code" name="phone_code" class="in @error('phone_code') is-invalid @enderror">
            <option value="506" @selected(old('phone_code','506')=='506' )>Costa Rica (+506)</option>
            <option value="507" @selected(old('phone_code')=='507' )>Panamá (+507)</option>
            <option value="505" @selected(old('phone_code')=='505' )>Nicaragua (+505)</option>
            <option value="502" @selected(old('phone_code')=='502' )>Guatemala (+502)</option>
            <option value="503" @selected(old('phone_code')=='503' )>El Salvador (+503)</option>
            <option value="504" @selected(old('phone_code')=='504' )>Honduras (+504)</option>
            <option value="52" @selected(old('phone_code')=='52' )>México (+52)</option>
            <option value="1" @selected(old('phone_code')=='1' )>EE.UU. / Canadá (+1)</option>
          </select>

          <div class="field" style="margin:0">
            <svg class="icon-l" viewBox="0 0 24 24" fill="none" style="bottom:14px">
              <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                d="M4 5c0-1.105.895-2 2-2h1.5c.74 0 1.386.405 1.732 1.036l.788 1.417a2 2 0 0 1-.223 2.224l-.82.984a12.05 12.05 0 0 0 5.078 5.078l.984-.82a2 2 0 0 1 2.224-.223l1.417.788A2 2 0 0 1 20 17.5V19a2 2 0 0 1-2 2h-1C9.373 21 3 14.627 3 7V6a1 1 0 0 1 1-1Z" />
            </svg>
            <input id="phone_local" name="phone_local" type="text"
              class="in @error('phone_local') is-invalid @enderror"
              value="{{ old('phone_local') }}" placeholder="8888-8888">
            {{-- bajo el input del teléfono, añade también: --}}
            @error('phone')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror

          </div>
        </div>
        <small class="text-muted">Se guardará en formato E.164 para compatibilidad con WhatsApp.</small>
        @error('phone_code')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
        @error('phone_local')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
      </div>

      {{-- Contraseña --}}
      <div class="field">
        <label for="password">Contraseña</label>
        <svg class="icon-l" viewBox="0 0 24 24" fill="none">
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
            d="M6 9V6.5A4.5 4.5 0 0 1 10.5 2 4.5 4.5 0 0 1 15 6.5V9" />
          <rect x="3" y="9" width="18" height="12" rx="3" stroke="#111827" stroke-width="1.6" />
        </svg>
        <input id="password" name="password" type="password"
          class="in @error('password') is-invalid @enderror" placeholder="••••••••" required autocomplete="new-password">
        @error('password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
      </div>

      {{-- Confirmación --}}
      <div class="field">
        <label for="password-confirm">Confirmar contraseña</label>
        <svg class="icon-l" viewBox="0 0 24 24" fill="none">
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
          <rect x="3" y="9" width="18" height="12" rx="3" stroke="#111827" stroke-width="1.6" />
        </svg>
        <input id="password-confirm" name="password_confirmation" type="password"
          class="in" placeholder="••••••••" required autocomplete="new-password">
      </div>

      <button class="btn-primary-xl mt-1" type="submit">Crear cuenta</button>

      <div class="sep"><span class="line"></span><span>O</span><span class="line"></span></div>

      {{-- Google --}}
      <a href="{{ url('/auth/google/redirect') }}" class="btn-ghost">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google">
        <span>Registrarte con Google</span>
      </a>

      <p class="terms">
        Al registrarte aceptas nuestros
        <a href="{{ route('legal.terms') }}">Términos de uso y Condiciones</a>.
      </p>
    </form>

  </div>
</div>
@endsection