@extends('layouts.app')

@section('title', 'Iniciar sesión - QR-Pet Tag')

@push('styles')
<style>
  .auth-stage{
    min-height:calc(100vh - 140px);
    display:flex;align-items:center;justify-content:center;
    padding:24px;
  }
  .auth-card{
    width:min(720px,96vw);
    background:#fff;border-radius:16px;
    box-shadow:
      0 80px 60px rgba(0,0,0,.03),
      0 30px 30px rgba(0,0,0,.05),
      0 12px 20px rgba(0,0,0,.06);
    padding:48px 40px 28px;
  }
  .logo_box{
    width:78px;height:78px;border-radius:14px;
    border:1px solid #F0F0F2;
    background:linear-gradient(180deg,rgba(248,248,248,0) 48%, #F8F8F8 100%);
    margin:0 auto 10px;display:grid;place-items:center;
    color:#1e7cf2;font-size:30px;
    filter:drop-shadow(0 .5px .5px #EFEFEF) drop-shadow(0 1px .5px rgba(239,239,239,.5));
  }
  .auth-title{font-weight:800;font-size:28px;text-align:center;margin:10px 0 6px;color:#1b1c1f;letter-spacing:.2px}
  .auth-sub{color:#8B8E98;text-align:center;max-width:520px;margin:0 auto 22px;line-height:1.35rem}

  .grid{display:grid;gap:14px}
  .field{position:relative}
  .field label{display:block;font-size:14px;color:#6c7280;margin-bottom:6px}
  .in{
    width:100%;height:48px;border-radius:12px;
    border:1px solid #e6e7eb;background:#fff;
    padding:0 14px 0 44px;font-size:16px;outline:none;
    transition:border .2s, box-shadow .2s;
  }
  .in:focus{border-color:#3466ff; box-shadow:0 0 0 3px rgba(52,102,255,.15)}
  .in.is-invalid{border-color:#dc3545; box-shadow:0 0 0 3px rgba(220,53,69,.14)}
  .icon-l{
    position:absolute;left:14px;bottom:12px;width:20px;height:20px;opacity:.9
  }
  .invalid-feedback{display:block;font-size:.9rem}

  .meta-actions{
    display:flex;align-items:center;justify-content:space-between;
    gap:12px;margin-top:2px
  }
  .meta-actions .form-check-label{font-size:.95rem;color:#475069}

  .btn-primary-xl{
    width:100%;height:50px;border:0;border-radius:12px;
    background:#115DFC;color:#fff;font-weight:800;letter-spacing:.2px
  }
  .btn-primary-xl:hover{filter:brightness(.98)}

  .sep{display:flex;align-items:center;gap:14px;color:#9aa0aa;margin:12px 0}
  .sep .line{flex:1;height:1px;background:#e9ebef}

  .btn-ghost{
    width:100%;height:50px;border-radius:12px;font-weight:700;display:flex;
    align-items:center;justify-content:center;gap:10px;
    background:#fff;border:1px solid #e6e7eb;color:#1b1c1f;text-decoration:none;
  }
  .btn-ghost:hover{background:#fafafa}
  .btn-ghost .g-icon{display:inline-flex;align-items:center;justify-content:center}

  .small-link{font-size:.95rem}
</style>
@endpush

@section('content')
<div class="auth-stage">
  <div class="auth-card">

    <div class="logo_box"><i class="fa-solid fa-paw"></i></div>
    <h1 class="auth-title">Inicia sesión</h1>
    <p class="auth-sub">Accede a tu cuenta para gestionar tus mascotas, tags y datos de contacto.</p>

    @if (session('status'))
      <div class="alert alert-success mb-3" role="alert">{{ session('status') }}</div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger mb-3" role="alert">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="grid">
      @csrf

      {{-- Email --}}
      <div class="field">
        <label for="email">Correo electrónico</label>
        <svg class="icon-l" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z"/>
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" d="m3 7 9 6 9-6"/>
        </svg>
        <input id="email" type="email"
               class="in @error('email') is-invalid @enderror"
               name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
               placeholder="name@mail.com">
        @error('email')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
      </div>

      {{-- Password --}}
      <div class="field">
        <label for="password">Contraseña</label>
        <svg class="icon-l" viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
                d="M6 9V6.5A4.5 4.5 0 0 1 10.5 2 4.5 4.5 0 0 1 15 6.5V9"/>
          <rect x="3" y="9" width="18" height="12" rx="3" stroke="#111827" stroke-width="1.6"/>
        </svg>
        <input id="password" type="password"
               class="in @error('password') is-invalid @enderror"
               name="password" required autocomplete="current-password"
               placeholder="••••••••">
        @error('password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
      </div>

      {{-- Remember + Forgot --}}
      <div class="meta-actions">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
          <label class="form-check-label" for="remember">Recordarme</label>
        </div>
        @if (Route::has('password.request'))
          <a class="small-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        @endif
      </div>

      <button type="submit" class="btn-primary-xl mt-1">Ingresar</button>

      <div class="sep"><span class="line"></span><span>O</span><span class="line"></span></div>

      {{-- Google --}}
      <a href="{{ url('/auth/google/redirect') }}" class="btn-ghost" aria-label="Continuar con Google">
        <span class="g-icon" aria-hidden="true">
          <svg width="18" height="18" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" focusable="false" aria-hidden="true">
            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.61l6.9-6.9C35.9 2.1 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l8.01 6.22C12.33 13.3 17.68 9.5 24 9.5z"/>
            <path fill="#4285F4" d="M46.5 24.5c0-1.59-.14-3.12-.41-4.59H24v9.18h12.7c-.55 2.95-2.2 5.45-4.7 7.12l7.19 5.58C43.94 37.4 46.5 31.44 46.5 24.5z"/>
            <path fill="#FBBC05" d="M10.57 19.44l-8.01-6.22C1.1 16.37 0 20.05 0 24c0 3.95 1.1 7.63 2.56 10.78l8.01-6.22C9.88 26.49 9.5 25.28 9.5 24s.38-2.49 1.07-3.56z"/>
            <path fill="#34A853" d="M24 48c6.48 0 11.94-2.14 15.93-5.8l-7.19-5.58c-2.02 1.36-4.61 2.16-8.74 2.16-6.32 0-11.67-3.8-13.43-9.94l-8.01 6.22C6.51 42.62 14.62 48 24 48z"/>
          </svg>
        </span>
        <span>Continuar con Google</span>
      </a>

    </form>

  </div>
</div>
@endsection
