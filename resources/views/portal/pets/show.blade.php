{{-- resources/views/portal/pets/show.blade.php --}}
@extends('layouts.app')

@section('title', $pet->name . ' | Mascota')

@section('content')
@php
$isAdmin = (bool) (auth()->user()->is_admin ?? false);
$qr = $pet->qrCode;
$slug = $qr->slug ?? null;
$publicUrl = $slug ? route('public.pet.show', $slug) : null;
$qrImageUrl = ($qr && $qr->image && Storage::disk('public')->exists($qr->image)) ? Storage::url($qr->image) : null;
$canDownloadQr = $qrImageUrl && (auth()->id() === $pet->user_id || $isAdmin);
$photos = $pet->all_photos; // FIX: Incluye foto principal + opcionales en orden
$mainPhotoUrl = $pet->main_photo_url ?? asset('images/default-pet.jpg');
$sexLabel = ['male' => 'Macho', 'female' => 'Hembra', 'unknown' => 'Desconocido'][$pet->sex ?? 'unknown'];
@endphp

<div class="pet-show-container">
  <div class="row g-4">
    {{-- Columna izquierda --}}
    <div class="col-12 col-xl-8">
      {{-- Hero Card con Carousel --}}
      <div class="hero-card">
        <div class="carousel-wrapper">
          <div id="petPhotosCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
            <div class="carousel-inner">
              @if($photos->isEmpty())
              <div class="carousel-item active">
                <div class="hero-image-container js-skel">
                  <img src="{{ $mainPhotoUrl }}" alt="Mascota" class="hero-image" loading="lazy">
                </div>
              </div>
              @else
              @foreach($photos as $i => $ph)
              <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                <div class="hero-image-container js-skel">
                  <img src="{{ asset('storage/'.$ph->path) }}" alt="Mascota {{ $i+1 }}" class="hero-image" loading="lazy">
                </div>
              </div>
              @endforeach
              @endif
            </div>

            @if($photos->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#petPhotosCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#petPhotosCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
            
            <div class="carousel-indicators-modern">
              @foreach($photos as $i => $ph)
              <button type="button" data-bs-target="#petPhotosCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
              @endforeach
            </div>
            @endif
          </div>

          {{-- Overlay con nombre y acciones --}}
          <div class="hero-overlay no-swipe">
            <div class="hero-content">
              <div class="hero-header">
                <h1 class="pet-name-hero">
                  @if(($pet->sex ?? 'unknown') === 'male')
                  <i class="fa-solid fa-mars gender-icon male"></i>
                  @elseif(($pet->sex ?? 'unknown') === 'female')
                  <i class="fa-solid fa-venus gender-icon female"></i>
                  @else
                  <i class="fa-solid fa-circle-question gender-icon"></i>
                  @endif
                  {{ $pet->name }}
                </h1>
                @if($pet->is_lost)
                <span class="status-badge lost"><i class="fa-solid fa-triangle-exclamation"></i> Perdida</span>
                @endif
              </div>
              
              <div class="hero-actions">
                <a href="{{ route('portal.pets.edit',$pet) }}" class="btn-modern btn-light">
                  <i class="fa-solid fa-pen-to-square"></i>
                  <span>Editar</span>
                </a>

                @if($isAdmin)
                <form action="{{ route('portal.pets.destroy',$pet) }}" method="POST" class="pet-delete-form">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-modern btn-danger">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Eliminar</span>
                  </button>
                </form>

                <button type="button" class="btn-modern btn-primary" data-url="{{ route('portal.pets.share.facebook', $pet) }}" data-name="{{ $pet->name }}" data-page="{{ config('services.facebook.page_id') }}" onclick="publishToFacebook(event)">
                  <i class="fa-brands fa-facebook"></i>
                  <span class="d-none d-lg-inline">Facebook</span>
                </button>
                @endif
              </div>
            </div>
          </div>
        </div>

        {{-- Info Cards Grid --}}
        <div class="info-grid">
          <div class="info-card">
            <i class="fa-solid fa-dog info-icon"></i>
            <div class="info-content">
              <div class="info-label">Raza</div>
              <div class="info-value">{{ $pet->breed ?: 'Sin raza' }}</div>
            </div>
          </div>

          <div class="info-card">
            <i class="fa-solid fa-{{ ($pet->sex ?? 'unknown') === 'male' ? 'mars' : (($pet->sex ?? 'unknown') === 'female' ? 'venus' : 'circle-question') }} info-icon"></i>
            <div class="info-content">
              <div class="info-label">Sexo</div>
              <div class="info-value">{{ $sexLabel }}</div>
            </div>
          </div>

          @if(!is_null($pet->age))
          <div class="info-card">
            <i class="fa-solid fa-cake-candles info-icon"></i>
            <div class="info-content">
              <div class="info-label">Edad</div>
              <div class="info-value">{{ $pet->age }} {{ Str::plural('año', $pet->age) }}</div>
            </div>
          </div>
          @endif

          @if($pet->zone)
          <div class="info-card">
            <i class="fa-solid fa-location-dot info-icon"></i>
            <div class="info-content">
              <div class="info-label">Ubicación</div>
              <div class="info-value">{{ $pet->zone }}</div>
            </div>
          </div>
          @endif

          <div class="info-card">
            <i class="fa-solid fa-syringe info-icon"></i>
            <div class="info-content">
              <div class="info-label">Antirrábica</div>
              <div class="info-value">{{ $pet->rabies_vaccine ? 'Sí' : 'No' }}</div>
            </div>
          </div>

          <div class="info-card">
            <i class="fa-solid fa-scissors info-icon"></i>
            <div class="info-content">
              <div class="info-label">Esterilizado</div>
              <div class="info-value">{{ $pet->is_neutered ? 'Sí' : 'No' }}</div>
            </div>
          </div>

          <div class="info-card">
            <i class="fa-solid fa-user info-icon"></i>
            <div class="info-content">
              <div class="info-label">Dueño</div>
              <div class="info-value">{{ optional($pet->user)->name ?: 'Sin dueño' }}</div>
            </div>
          </div>
        </div>

        {{-- Observaciones --}}
        <div class="observations-card">
          <div class="observations-header">
            <i class="fa-solid fa-notes-medical"></i>
            <h3>Observaciones Médicas</h3>
          </div>
          <p class="observations-text">{{ $pet->medical_conditions ?: 'No tiene observaciones registradas.' }}</p>
        </div>

        {{-- Acciones Principales --}}
        <div class="main-actions">
          <form action="{{ route('portal.pets.toggle-lost', $pet) }}" method="POST" id="toggleLostForm">
            @csrf
            <button type="submit" class="btn-action {{ $pet->is_lost ? 'btn-action-warning' : 'btn-action-danger' }}" id="toggleLostBtn">
              <i class="fa-solid fa-triangle-exclamation"></i>
              <span>{{ $pet->is_lost ? 'Quitar estado de pérdida' : 'Marcar como perdida/robada' }}</span>
            </button>
          </form>
          @if($pet->is_lost)
          <form action="{{ route('portal.pets.share-card', $pet) }}" method="POST">
            @csrf
            <button type="submit" class="btn-action btn-action-info">
              <i class="fa-solid fa-bullhorn"></i>
              <span>Generar publicación para redes</span>
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>

    {{-- Columna derecha --}}
    <div class="col-12 col-xl-4">
      {{-- Preview Compartir --}}
      @if (session('share_card_url'))
      <div class="side-card share-preview-card" id="shareCardPreview">
        <div class="card-header-modern">
          <div class="card-title-group">
            <i class="fa-solid fa-bullhorn card-icon"></i>
            <h3>Publicación Lista</h3>
          </div>
          <div class="card-actions-group">
            <a href="{{ session('share_card_url') }}" download="qr-pet-{{ $pet->id }}.png" class="btn-icon" title="Descargar">
              <i class="fa-solid fa-download"></i>
            </a>
            <button type="button" id="btnShareCard" data-url="{{ session('share_card_url') }}" data-title="Mascota perdida: {{ $pet->name }}" class="btn-icon" title="Compartir">
              <i class="fa-solid fa-share-nodes"></i>
            </button>
          </div>
        </div>
        <div class="share-image-wrapper js-skel">
          <img src="{{ session('share_card_url') }}" alt="Publicación" class="share-image" loading="lazy">
        </div>
        <p class="share-note">Formato optimizado 1080×1350 para redes sociales</p>
      </div>
      @endif

      {{-- QR Card --}}
      <div class="side-card qr-card">
        <div class="card-header-modern">
          <div class="card-title-group">
            <i class="fa-solid fa-qrcode card-icon"></i>
            <h3>Código QR</h3>
          </div>
          @if($isAdmin && $qr && $qr->activation_code)
          <div class="tag-badge-wrapper">
            <span class="tag-badge" id="tagCode" data-tag="{{ $qr->activation_code }}">
              <span class="tag-label">TAG:</span>
              <span class="tag-value">{{ $qr->activation_code }}</span>
            </span>
            <button type="button" class="btn-copy-tag" onclick="copyTag(event)" title="Copiar TAG">
              <i class="fa-solid fa-copy"></i>
            </button>
          </div>
          @endif
        </div>

        <div class="qr-display js-skel">
          @if($qrImageUrl)
          <img src="{{ $qrImageUrl }}" alt="QR" class="qr-image" loading="lazy">
          @else
          <div class="qr-empty">
            <i class="fa-solid fa-qrcode"></i>
            <p>QR no generado</p>
          </div>
          @endif
        </div>

        @if($publicUrl)
        <div class="qr-url-box">
          <input type="text" value="{{ $publicUrl }}" readonly class="qr-url-input" id="qrUrlInput">
          <button type="button" class="btn-copy-url" onclick="copyQrUrl(event)" title="Copiar URL">
            <i class="fa-solid fa-copy"></i>
          </button>
        </div>
        @else
        <p class="qr-note">Genera el QR para obtener la URL pública</p>
        @endif

        <div class="qr-actions">
          @if($isAdmin)
          <form action="{{ route('portal.pets.generate-qr',$pet) }}" method="POST" class="no-swipe" data-confirm="{{ $qr && !blank($qr->slug) ? '¿Regenerar el QR? Se creará un nuevo código único y el anterior dejará de funcionar.' : '¿Generar el QR de esta mascota?' }}">
            @csrf
            <button class="btn-qr btn-qr-primary">
              <i class="fa-solid fa-{{ $qr && !blank($qr->slug) ? 'rotate' : 'bolt' }}"></i>
              <span>{{ $qr && !blank($qr->slug) ? 'Regenerar QR' : 'Generar QR' }}</span>
            </button>
          </form>
          @endif

          <a href="{{ $canDownloadQr ? route('portal.pets.download-qr', $pet) : '#' }}" class="btn-qr btn-qr-secondary {{ $canDownloadQr ? '' : 'disabled' }}">
            <i class="fa-solid fa-download"></i>
            <span>Descargar</span>
          </a>

          <a href="{{ $publicUrl ?: '#' }}" target="_blank" class="btn-qr btn-qr-secondary {{ $publicUrl ? '' : 'disabled' }}">
            <i class="fa-solid fa-up-right-from-square"></i>
            <span>Ver Público</span>
          </a>

          @if($isAdmin && $qr)
          <form action="{{ route('portal.pets.regen-code', $pet) }}" method="POST" class="no-swipe" data-confirm="¿Quieres regenerar el TAG de esta mascota?">
            @csrf
            <button class="btn-qr btn-qr-warning">
              <i class="fa-solid fa-rotate"></i>
              <span>Regenerar TAG</span>
            </button>
          </form>
          @endif
        </div>
      </div>

      {{-- Recompensa Card --}}
      <div class="side-card reward-card">
        <div class="card-header-modern">
          <div class="card-title-group">
            <i class="fa-solid fa-medal card-icon"></i>
            <h3>Recompensa</h3>
          </div>
          <i class="fa-solid fa-circle-info help-icon" data-bs-toggle="tooltip" title="Activa la recompensa y define un monto mayor a 0"></i>
        </div>

        <form action="{{ route('portal.pets.reward.update', $pet) }}" method="POST" id="rewardForm" class="no-swipe">
          @csrf @method('PUT')
          @php($activeVal = 0)
          <input type="hidden" name="active" id="rwActive" value="0">

          <div class="reward-toggle {{ !$pet->is_lost ? 'disabled-toggle' : '' }}" id="rewardToggleContainer" {{ !$pet->is_lost ? 'title="La mascota debe estar marcada como perdida para activar la recompensa"' : '' }}>
            <span class="toggle-label">Mostrar en perfil público</span>
            <div id="rwSwitch" class="modern-switch" role="button" aria-pressed="false">
              <span class="switch-slider"></span>
            </div>
          </div>

          @if(!$pet->is_lost)
          <div class="reward-info-message">
            <i class="fa-solid fa-info-circle"></i>
            <span>Marca la mascota como perdida para activar la recompensa</span>
          </div>
          @endif

          <div class="reward-inputs">
            <div class="input-group-reward">
              <label class="input-label">Monto</label>
              <div class="input-with-prefix">
                <span class="input-prefix">₡</span>
                <input type="text" inputmode="decimal" pattern="[0-9.,]*" name="amount" id="rwAmount" class="reward-input" value="" placeholder="0.00">
              </div>
            </div>

            <div class="input-group-reward">
              <label class="input-label">Mensaje</label>
              <input type="text" name="message" id="rwMessage" class="reward-input" maxlength="200" value="{{ optional($pet->reward)->message ?? 'Gracias por tu ayuda 🙏' }}" placeholder="Mensaje opcional">
            </div>
          </div>

          <button type="submit" id="rwSave" class="btn-reward-save">
            <i class="fa-solid fa-floppy-disk"></i>
            <span>Guardar Recompensa</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
@keyframes fadeInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
@keyframes slideInRight{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:translateX(0)}}
@keyframes scaleIn{from{opacity:0;transform:scale(.9)}to{opacity:1;transform:scale(1)}}
@keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.05)}}
@keyframes shimmer{0%{background-position:-1000px 0}100%{background-position:1000px 0}}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes glow{0%,100%{box-shadow:0 0 20px rgba(102,126,234,.3)}50%{box-shadow:0 0 30px rgba(102,126,234,.6)}}

.pet-show-container{animation:fadeInUp .6s ease-out;padding:1rem 0}

/* Hero Card */
.hero-card{background:#fff;border-radius:24px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,.08);margin-bottom:1.5rem;animation:scaleIn .6s ease-out}

.carousel-wrapper{position:relative;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.05)}

.hero-image-container{aspect-ratio:4/3;overflow:hidden;display:flex;align-items:center;justify-content:center;background:#f8fafc}

.hero-image{width:100%;height:100%;object-fit:contain;transition:transform .6s ease;padding:1rem}

.carousel-item.active .hero-image{animation:scaleIn .6s ease-out}

.carousel-control-prev,.carousel-control-next{width:40px;height:40px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.98);border-radius:12px;opacity:0;transition:all .3s ease;backdrop-filter:blur(10px);box-shadow:0 3px 15px rgba(0,0,0,.2)}

.carousel:hover .carousel-control-prev,.carousel:hover .carousel-control-next{opacity:1}

.carousel-control-prev:hover,.carousel-control-next:hover{background:#fff;transform:translateY(-50%) scale(1.08);box-shadow:0 4px 20px rgba(0,0,0,.25)}

.carousel-control-prev{left:15px}
.carousel-control-next{right:15px}

.carousel-control-prev-icon,.carousel-control-next-icon{width:20px;height:20px;background-size:20px;filter:invert(.1)}

.carousel-indicators-modern{position:absolute;bottom:15px;left:50%;transform:translateX(-50%);display:flex;gap:8px;z-index:20}

.carousel-indicators-modern button{width:8px;height:8px;border-radius:50%;border:2px solid rgba(255,255,255,.95);background:rgba(255,255,255,.6);padding:0;transition:all .3s ease;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,.2)}

.carousel-indicators-modern button.active{background:#fff;width:24px;border-radius:4px;box-shadow:0 2px 10px rgba(0,0,0,.35)}

.carousel-indicators-modern button:hover{background:rgba(255,255,255,.9);transform:scale(1.2)}

/* Hero Overlay */
.hero-overlay{position:absolute;left:0;right:0;bottom:0;background:#fff;padding:1.25rem 1.5rem;z-index:25;border-top:1px solid #e5e7eb}

.hero-content{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}

.hero-header{flex:1;min-width:200px}

.pet-name-hero{font-size:1.75rem;font-weight:900;color:#111827;margin:0;display:flex;align-items:center;gap:.6rem;animation:fadeInUp .6s ease-out}

.gender-icon{font-size:1.5rem;animation:float 3s ease-in-out infinite}

.gender-icon.male{color:#60a5fa}
.gender-icon.female{color:#f472b6}

.status-badge{display:inline-flex;align-items:center;gap:.4rem;padding:.4rem .8rem;background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);border-radius:999px;font-size:.8rem;font-weight:700;color:#fff;margin-top:.5rem;animation:pulse 2s ease-in-out infinite;box-shadow:0 4px 15px rgba(239,68,68,.4)}

.hero-actions{display:flex;gap:.6rem;flex-wrap:wrap;animation:fadeInUp .6s ease-out .2s both}

.btn-modern{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem .9rem;font-size:.85rem;font-weight:700;border-radius:10px;border:none;cursor:pointer;transition:all .3s cubic-bezier(.4,0,.2,1);position:relative;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.1)}

.btn-modern::before{content:'';position:absolute;top:50%;left:50%;width:0;height:0;border-radius:50%;background:rgba(255,255,255,.3);transform:translate(-50%,-50%);transition:width .5s,height .5s}

.btn-modern:hover::before{width:300px;height:300px}

.btn-modern:hover{transform:translateY(-2px);box-shadow:0 4px 15px rgba(0,0,0,.15)}

.btn-light{background:#f3f4f6;color:#374151}
.btn-danger{background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:#fff}
.btn-primary{background:linear-gradient(135deg,#3b82f6 0%,#1d4ed8 100%);color:#fff}

/* Info Grid */
.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(155px,1fr));gap:.85rem;padding:1.25rem}

.info-card{background:linear-gradient(135deg,#f8fafc 0%,#fff 100%);border:2px solid #e8ecf4;border-radius:14px;padding:.95rem 1rem;display:flex;align-items:center;gap:.75rem;transition:all .3s ease;animation:scaleIn .5s ease-out}

.info-card:hover{border-color:#667eea;transform:translateY(-3px);box-shadow:0 6px 20px rgba(102,126,234,.12)}

.info-icon{font-size:1.35rem;color:#667eea;min-width:30px;text-align:center;transition:transform .3s ease}

@media (min-width:992px){
.info-icon{font-size:1.4rem;min-width:32px}
}

.info-card:hover .info-icon{transform:scale(1.1) rotate(5deg)}

.info-content{flex:1;min-width:0}

.info-label{font-size:.7rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.2rem}

.info-value{font-size:.88rem;font-weight:700;color:#111827;word-wrap:break-word;overflow-wrap:break-word;line-height:1.3}

/* Desktop - más espacio horizontal */
@media (min-width:992px){
.info-value{font-size:.9rem;line-height:1.35}
}

/* Observaciones */
.observations-card{margin:0 1.5rem 1.5rem;padding:1.5rem;background:linear-gradient(135deg,#fff 0%,#f8f9fa 100%);border:2px solid #e8ecf4;border-radius:16px;transition:all .3s ease;animation:fadeInUp .6s ease-out .3s both}

.observations-card:hover{border-color:#667eea;box-shadow:0 8px 25px rgba(102,126,234,.1)}

.observations-header{display:flex;align-items:center;gap:.8rem;margin-bottom:1rem}

.observations-header i{font-size:1.5rem;color:#667eea}

.observations-header h3{font-size:1.1rem;font-weight:700;color:#111827;margin:0}

.observations-text{color:#6b7280;line-height:1.6;margin:0}

/* Main Actions */
.main-actions{display:flex;flex-direction:column;gap:1rem;padding:0 1.5rem 1.5rem;animation:fadeInUp .6s ease-out .4s both}

.btn-action{width:100%;display:flex;align-items:center;justify-content:center;gap:.8rem;padding:1rem 1.5rem;font-size:1rem;font-weight:700;border-radius:14px;border:none;cursor:pointer;transition:all .3s ease;box-shadow:0 4px 15px rgba(0,0,0,.1)}

.btn-action:hover{transform:translateY(-3px);box-shadow:0 6px 25px rgba(0,0,0,.2)}

.btn-action-warning{background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);color:#fff}
.btn-action-danger{background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:#fff}
.btn-action-info{background:linear-gradient(135deg,#06b6d4 0%,#0891b2 100%);color:#fff}

/* Side Cards */
.side-card{background:#fff;border-radius:20px;padding:1.5rem;margin-bottom:1.5rem;box-shadow:0 8px 30px rgba(0,0,0,.08);transition:all .3s ease;animation:slideInRight .6s ease-out}

.side-card:hover{box-shadow:0 12px 40px rgba(0,0,0,.12);transform:translateY(-4px)}

@media (max-width:768px){
.side-card{border-radius:16px;padding:1.2rem;margin-bottom:1rem;box-shadow:0 4px 15px rgba(0,0,0,.06)}
.side-card:hover{transform:translateY(-2px)}
}

@media (max-width:576px){
.side-card{border-radius:0;padding:1rem;margin-bottom:.75rem;box-shadow:0 2px 10px rgba(0,0,0,.05)}
}

.card-header-modern{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem}

.card-title-group{display:flex;align-items:center;gap:.8rem}

.card-icon{font-size:1.5rem;color:#667eea}

.card-title-group h3{font-size:1.25rem;font-weight:800;color:#111827;margin:0}

.card-actions-group{display:flex;gap:.5rem}

.btn-icon{width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:linear-gradient(135deg,#f3f4f6 0%,#e5e7eb 100%);border:none;color:#374151;cursor:pointer;transition:all .3s ease}

.btn-icon:hover{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;transform:scale(1.1)}

.help-icon{color:#9ca3af;cursor:help;transition:all .3s ease}

.help-icon:hover{color:#667eea;transform:scale(1.15)}

/* TAG Badge con botón */
.tag-badge-wrapper{display:flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#f0f4ff 0%,#e8ecf4 100%);padding:.5rem .75rem;border-radius:12px;border:2px solid #c7d2fe}

.tag-badge{display:flex;align-items:center;gap:.5rem;font-weight:700;font-size:.9rem}

.tag-label{color:#6b7280}
.tag-value{color:#667eea}

.btn-copy-tag{width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border:none;border-radius:8px;color:#fff;cursor:pointer;transition:all .3s ease}

.btn-copy-tag:hover{transform:scale(1.1);box-shadow:0 4px 15px rgba(102,126,234,.4)}

/* Share Preview */
.share-image-wrapper{aspect-ratio:4/5;border-radius:14px;overflow:hidden;margin-bottom:1rem;background:linear-gradient(135deg,#f8fafc 0%,#e8ecf4 100%)}

.share-image{width:100%;height:100%;object-fit:contain}

.share-note{font-size:.85rem;color:#6b7280;margin:0;text-align:center}

/* QR Display */
.qr-display{display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#f6f8ff 0%,#e8ecf4 100%);border:3px dashed #c7d2fe;border-radius:16px;padding:2rem;min-height:280px;margin-bottom:1.5rem;transition:all .3s ease}

.qr-display:hover{border-color:#667eea;background:linear-gradient(135deg,#e8ecf4 0%,#f6f8ff 100%);box-shadow:0 8px 25px rgba(102,126,234,.15)}

.qr-image{width:240px;height:240px;object-fit:contain;image-rendering:crisp-edges;image-rendering:pixelated;transition:transform .4s ease}

.qr-display:hover .qr-image{transform:scale(1.08) rotate(2deg)}

.qr-empty{text-align:center;color:#9ca3af}

.qr-empty i{font-size:4rem;margin-bottom:1rem;opacity:.5;animation:float 3s ease-in-out infinite}

.qr-empty p{font-size:1rem;font-weight:600}

.qr-url-box{display:flex;gap:.5rem;background:#f8f9fa;border:2px solid #e5e7eb;border-radius:12px;padding:.5rem;margin-bottom:1rem;transition:all .3s ease}

.qr-url-box:focus-within{border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,.15)}

.qr-url-input{flex:1;border:none;background:transparent;padding:.5rem;font-size:.85rem;color:#374151;font-weight:600}

.qr-url-input:focus{outline:none}

.btn-copy-url{width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border:none;border-radius:8px;color:#fff;cursor:pointer;transition:all .3s ease}

.btn-copy-url:hover{transform:scale(1.1);box-shadow:0 4px 15px rgba(102,126,234,.4)}

.qr-note{font-size:.85rem;color:#6b7280;text-align:center;margin-bottom:1rem}

.qr-actions{display:flex;flex-direction:column;gap:.75rem}

.btn-qr{width:100%;display:flex;align-items:center;justify-content:center;gap:.6rem;padding:.85rem 1rem;font-size:.95rem;font-weight:700;border-radius:12px;border:none;cursor:pointer;transition:all .3s ease}

.btn-qr:hover:not(.disabled){transform:translateY(-2px)}

.btn-qr.disabled{opacity:.5;cursor:not-allowed}

.btn-qr-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;box-shadow:0 4px 15px rgba(102,126,234,.3)}

.btn-qr-primary:hover{box-shadow:0 6px 20px rgba(102,126,234,.4)}

.btn-qr-secondary{background:linear-gradient(135deg,#f3f4f6 0%,#e5e7eb 100%);color:#374151;box-shadow:0 2px 8px rgba(0,0,0,.08)}

.btn-qr-secondary:hover:not(.disabled){background:linear-gradient(135deg,#e5e7eb 0%,#d1d5db 100%);box-shadow:0 4px 12px rgba(0,0,0,.12)}

.btn-qr-warning{background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);color:#fff;box-shadow:0 4px 15px rgba(251,191,36,.3)}

.btn-qr-warning:hover{box-shadow:0 6px 20px rgba(251,191,36,.4)}

/* Reward Card */
.reward-toggle{display:flex;justify-content:space-between;align-items:center;padding:1rem;background:linear-gradient(135deg,#f8f9fa 0%,#f3f4f6 100%);border-radius:12px;margin-bottom:1.5rem;cursor:pointer;user-select:none;transition:all .3s ease}

.reward-toggle:hover{background:linear-gradient(135deg,#f3f4f6 0%,#e5e7eb 100%);transform:translateY(-1px)}

.reward-toggle:active{transform:translateY(0)}

.reward-toggle.disabled-toggle{opacity:.6;cursor:not-allowed;background:linear-gradient(135deg,#f3f4f6 0%,#e5e7eb 100%)}

.reward-toggle.disabled-toggle:hover{transform:translateY(0);background:linear-gradient(135deg,#f3f4f6 0%,#e5e7eb 100%)}

.reward-toggle.disabled-toggle .toggle-label{color:#9ca3af}

.reward-toggle.disabled-toggle .modern-switch{opacity:.5}

.reward-info-message{display:flex;align-items:center;gap:.6rem;padding:.75rem 1rem;background:linear-gradient(135deg,#fef3c7 0%,#fde68a 100%);border:2px solid #fbbf24;border-radius:10px;margin-bottom:1.5rem;font-size:.85rem;color:#92400e;font-weight:600}

.reward-info-message i{color:#f59e0b;font-size:1rem}

.toggle-label{font-size:.95rem;font-weight:600;color:#374151;cursor:pointer}

.modern-switch{width:60px;height:32px;background:linear-gradient(135deg,#e5e7eb 0%,#d1d5db 100%);border-radius:999px;position:relative;cursor:pointer;transition:all .3s ease;box-shadow:inset 0 2px 6px rgba(0,0,0,.1);pointer-events:none}

.modern-switch:hover{transform:scale(1.05)}

.switch-slider{position:absolute;width:24px;height:24px;background:linear-gradient(135deg,#fff 0%,#f3f4f6 100%);border-radius:50%;top:4px;left:4px;transition:all .3s cubic-bezier(.4,0,.2,1);box-shadow:0 3px 8px rgba(0,0,0,.2)}

.modern-switch.active{background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);box-shadow:0 4px 15px rgba(34,197,94,.3)}

.modern-switch.active .switch-slider{transform:translateX(28px);background:linear-gradient(135deg,#fff 0%,#f0fdf4 100%)}

.reward-inputs{display:flex;flex-direction:column;gap:1rem;margin-bottom:1.5rem}

.input-group-reward{display:flex;flex-direction:column;gap:.5rem}

.input-label{font-size:.85rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.5px}

.input-with-prefix{display:flex;align-items:center;background:#f8f9fa;border:2px solid #e5e7eb;border-radius:12px;overflow:hidden;transition:all .3s ease}

.input-with-prefix:focus-within{border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,.15);transform:translateY(-2px)}

.input-prefix{padding:0 1rem;font-size:1.1rem;font-weight:900;color:#667eea;background:linear-gradient(135deg,#f0f4ff 0%,#e8ecf4 100%);height:50px;display:flex;align-items:center}

.reward-input{flex:1;border:none;background:transparent;padding:0 1rem;height:50px;font-size:.95rem;font-weight:600;color:#111827}

.reward-input:focus{outline:none}

.reward-input:disabled{opacity:.5;cursor:not-allowed}

.btn-reward-save{width:100%;display:flex;align-items:center;justify-content:center;gap:.6rem;padding:1rem;font-size:1rem;font-weight:700;background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);color:#fff;border:none;border-radius:12px;cursor:pointer;transition:all .3s ease;box-shadow:0 4px 15px rgba(34,197,94,.3)}

.btn-reward-save:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(34,197,94,.4)}

/* Skeleton */
.js-skel{position:relative;overflow:hidden;background:linear-gradient(135deg,#f2f4f7 0%,#e8ebf1 100%)}

.js-skel::after{content:"";position:absolute;inset:0;background:linear-gradient(110deg,transparent 0%,rgba(255,255,255,.8) 50%,transparent 100%);background-size:200% 100%;animation:shimmer 1.5s ease-in-out infinite}

.js-skel>img{opacity:0;transition:opacity .4s ease}

.js-skel.is-loaded::after{opacity:0;pointer-events:none;animation:none}

.js-skel.is-loaded>img{opacity:1}

/* Responsive */
@media (min-width:1400px){
.info-grid{grid-template-columns:repeat(auto-fit,minmax(165px,1fr))}
}

@media (max-width:1200px){
.pet-name-hero{font-size:1.65rem}
.info-grid{grid-template-columns:repeat(auto-fit,minmax(150px,1fr))}
}

@media (max-width:992px){
.info-grid{grid-template-columns:repeat(3,1fr)}
}

@media (max-width:768px){
.hero-image-container{aspect-ratio:1/1;border-radius:0}
.hero-image{padding:.5rem}
.carousel-wrapper{border-radius:0;box-shadow:none}
.hero-overlay{position:relative;padding:1rem;border-top:1px solid #e5e7eb}
.hero-content{gap:.8rem}
.hero-actions{width:100%;justify-content:flex-start;gap:.5rem}
.btn-modern{padding:.5rem .8rem;font-size:.8rem;flex:1;justify-content:center;min-width:0}
.btn-modern span{font-size:.75rem}
.info-grid{grid-template-columns:1fr 1fr;gap:.6rem;padding:.75rem}
.pet-name-hero{font-size:1.4rem}
.gender-icon{font-size:1.2rem}
.info-card{padding:.75rem .65rem;gap:.5rem}
.info-icon{font-size:1.1rem;min-width:26px}
.info-label{font-size:.62rem}
.info-value{font-size:.8rem}
.observations-card{margin:0 .75rem .75rem;padding:1rem}
.observations-header h3{font-size:.95rem}
.observations-text{font-size:.85rem}
.main-actions{padding:0 .75rem .75rem;gap:.6rem}
.btn-action{padding:.85rem 1rem;font-size:.9rem}
}

@media (max-width:576px){
.hero-card{border-radius:0;margin-bottom:1rem;box-shadow:0 2px 10px rgba(0,0,0,.06)}
.carousel-control-prev,.carousel-control-next{width:34px;height:34px}
.carousel-control-prev-icon,.carousel-control-next-icon{width:16px;height:16px}
.pet-name-hero{font-size:1.25rem}
.gender-icon{font-size:1.1rem}
.status-badge{font-size:.75rem;padding:.35rem .7rem}
.btn-modern{padding:.45rem .6rem;font-size:.72rem;gap:.25rem}
.info-grid{padding:.6rem}
.info-card{padding:.65rem .55rem}
.info-icon{font-size:1rem;min-width:24px}
.info-label{font-size:.6rem}
.info-value{font-size:.75rem}
.hero-overlay{padding:.85rem}
.observations-card{margin:0 .6rem .6rem;padding:.85rem}
.observations-header{margin-bottom:.6rem}
.observations-header i{font-size:1.2rem}
.observations-header h3{font-size:.9rem}
.observations-text{font-size:.8rem;line-height:1.5}
.main-actions{padding:0 .6rem .6rem;gap:.5rem}
.btn-action{padding:.75rem .85rem;font-size:.85rem;gap:.6rem}
.carousel-indicators-modern{bottom:10px;gap:6px}
.carousel-indicators-modern button{width:6px;height:6px}
.carousel-indicators-modern button.active{width:18px}
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Tooltips
(function(){const list=[].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));list.forEach(el=>new bootstrap.Tooltip(el))})();

// Recompensa switch - MEJORADO con validación de mascota perdida
(function(){
  const container = document.getElementById('rewardToggleContainer');
  const sw = document.getElementById('rwSwitch');
  const act = document.getElementById('rwActive');
  const amt = document.getElementById('rwAmount');
  const msg = document.getElementById('rwMessage');
  const form = document.getElementById('rewardForm');
  const isPetLost = {{ $pet->is_lost ? 'true' : 'false' }};
  
  if (!sw || !act || !container) return;
  
  function isOn() { return act.value === '1'; }
  
  function setOn(on) {
    // Verificar si la mascota está perdida antes de activar
    if (on && !isPetLost) {
      Swal.fire({
        icon: 'warning',
        title: 'Mascota no está perdida',
        text: 'Primero debes marcar la mascota como perdida/robada para activar la recompensa',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#667eea'
      });
      return;
    }
    
    sw.classList.toggle('active', on);
    sw.setAttribute('aria-pressed', on ? 'true' : 'false');
    act.value = on ? '1' : '0';
    setEnabled(on);
    
    // Limpiar campo si está vacío, pero NO hacer focus automático
    if (on && !parseFloat((amt.value || '').replace(',', '.'))) {
      amt.value = '';
    }
  }
  
  function normalizeMoney(v) {
    v = (v || '').toString().replace(/[^\d.,]/g, '').replace(',', '.');
    const n = parseFloat(v);
    return isNaN(n) ? '' : n.toFixed(2);
  }
  
  function amountOk() {
    const n = parseFloat((amt.value || '').toString().replace(',', '.'));
    return !isNaN(n) && n > 0;
  }
  
  function setEnabled(enabled) {
    // Si la mascota no está perdida, siempre deshabilitar
    const shouldEnable = enabled && isPetLost;
    [amt, msg].forEach(el => {
      el.disabled = !shouldEnable;
      el.style.opacity = shouldEnable ? 1 : .5;
    });
  }
  
  // Inicializar estado
  setEnabled(isOn());
  
  // Click en todo el contenedor
  container.addEventListener('click', (e) => {
    // Evitar que el click en inputs active el toggle
    if (e.target.tagName === 'INPUT') return;
    setOn(!isOn());
  });
  
  amt.addEventListener('focus', e => {
    const raw = (e.target.value || '').replace(',', '.');
    const n = parseFloat(raw);
    if (!raw || isNaN(n) || n === 0) e.target.value = '';
    else e.target.select();
  });
  
  amt.addEventListener('blur', e => {
    const v = normalizeMoney(e.target.value);
    // Dejar vacío si no hay valor, no poner 0.00
    e.target.value = v || '';
  });
  
  form.addEventListener('submit', (e) => {
    if (!isPetLost) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Mascota no está perdida',
        text: 'No puedes configurar una recompensa si la mascota no está marcada como perdida',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#667eea'
      });
      return;
    }
    if (isOn() && !amountOk()) {
      e.preventDefault();
      amt.focus();
    }
  });
})();

// Evitar swipe en botones
(function(){const stopSwipeZone=document.querySelectorAll('.no-swipe, .no-swipe *');const stopEvts=['click','mousedown','mouseup','touchstart','touchmove','touchend','pointerdown','pointerup'];stopSwipeZone.forEach(el=>{stopEvts.forEach(evt=>{el.addEventListener(evt,e=>{e.stopPropagation()},{passive:false})})})})();

// Confirmar eliminación
(function(){const forms=document.querySelectorAll('.pet-delete-form');forms.forEach(form=>{form.addEventListener('submit',function(e){e.preventDefault();Swal.fire({title:'¿Eliminar mascota?',text:'Esta acción no se puede deshacer.',icon:'warning',showCancelButton:true,confirmButtonText:'Sí, eliminar',cancelButtonText:'Cancelar',confirmButtonColor:'#dc3545'}).then(res=>{if(res.isConfirmed)form.submit()})})})})();

// Compartir
(function(){const btn=document.getElementById('btnShareCard');if(!btn)return;const url=btn.dataset.url;const title=btn.dataset.title||document.title;btn.addEventListener('click',async()=>{if(navigator.share){try{await navigator.share({title,url})}catch(e){}}else{try{await navigator.clipboard.writeText(url);const prev=btn.innerHTML;btn.innerHTML='<i class="fa-solid fa-check"></i>';setTimeout(()=>btn.innerHTML=prev,1300)}catch{alert('Copia este enlace:\n'+url)}}})})();

// Publicar Facebook
async function publishToFacebook(event){const btn=event.currentTarget||event.target;const url=btn.dataset.url;const petName=btn.dataset.name||'la mascota';const pageId=btn.dataset.page||'';const csrf=document.querySelector('meta[name="csrf-token"]')?.content||'';if(!url)return;if(btn.dataset.loading==='1')return;const confirm=await Swal.fire({title:'¿Publicar en Facebook?',html:`Se publicará <b>${petName}</b> en tu Página.`,icon:'question',showCancelButton:true,confirmButtonText:'Sí, publicar',cancelButtonText:'Cancelar'});if(!confirm.isConfirmed)return;btn.dataset.loading='1';btn.disabled=true;const controller=new AbortController();const timeoutId=setTimeout(()=>controller.abort(),25000);try{Swal.fire({title:'Publicando…',html:'Enviando a Facebook',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});const res=await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},credentials:'same-origin',signal:controller.signal});const raw=await res.text();let data=null;try{data=raw?JSON.parse(raw):null}catch{}clearTimeout(timeoutId);Swal.close();if(!res.ok||!data||data.ok!==true){const msg=(data&&data.error)||`HTTP ${res.status}`;return Swal.fire({icon:'error',title:'Error al publicar',text:msg,confirmButtonText:'Aceptar'})}let fbUrl='';const postId=data?.result?.post_id||data?.result?.id||'';if(postId&&postId.includes('_')){const[pid,suffix]=postId.split('_');fbUrl=`https://www.facebook.com/${pid}/posts/${suffix}`}else if(postId&&pageId){const suffix=postId.split('_').pop();fbUrl=`https://www.facebook.com/${pageId}/posts/${suffix}`}return Swal.fire({icon:'success',title:`¡Publicado en Facebook!`,html:fbUrl?`<a href="${fbUrl}" target="_blank">${fbUrl}</a>`:'Publicación exitosa',confirmButtonText:'Aceptar'})}catch(err){clearTimeout(timeoutId);Swal.close();const msg=(err?.name==='AbortError')?'Tiempo agotado':(err?.message||'Error de red');return Swal.fire({icon:'error',title:'Error',text:msg,confirmButtonText:'Aceptar'})}finally{btn.dataset.loading='';btn.disabled=false}}
window.publishToFacebook=publishToFacebook;

// Skeleton
(function(){const containers=document.querySelectorAll('.js-skel');containers.forEach(c=>{const img=c.querySelector('img');if(!img){c.classList.add('is-loaded');return}const markLoaded=()=>c.classList.add('is-loaded');if(img.complete&&img.naturalWidth>0){markLoaded()}else{img.addEventListener('load',markLoaded,{once:true});img.addEventListener('error',markLoaded,{once:true});setTimeout(()=>c.classList.add('is-loaded'),10000)}})})();

// Confirmar formularios
(function(){const forms=document.querySelectorAll('form[data-confirm]');forms.forEach(form=>{form.addEventListener('submit',async(e)=>{e.stopPropagation();e.preventDefault();const msg=form.getAttribute('data-confirm')||'¿Confirmar?';const res=await Swal.fire({title:'Confirmar',text:msg,icon:'question',showCancelButton:true,confirmButtonText:'Sí',cancelButtonText:'Cancelar'});if(res.isConfirmed)form.submit()},{passive:false})})})();

// Copiar TAG
function copyTag(event){const tag=document.getElementById('tagCode')?.dataset?.tag;if(!tag){Swal.fire({icon:'error',title:'Error',text:'No se encontró el TAG',confirmButtonText:'Aceptar'});return}navigator.clipboard.writeText(tag).then(()=>{const btn=event.target.closest('.btn-copy-tag');const originalHTML=btn.innerHTML;btn.innerHTML='<i class="fa-solid fa-check"></i>';btn.style.background='linear-gradient(135deg,#22c55e 0%,#16a34a 100%)';setTimeout(()=>{btn.innerHTML=originalHTML;btn.style.background='linear-gradient(135deg,#667eea 0%,#764ba2 100%)'},1500)}).catch((err)=>{Swal.fire({icon:'error',title:'Error al copiar',text:'No se pudo copiar el TAG: '+tag,confirmButtonText:'Aceptar'})})}

// Copiar URL QR
function copyQrUrl(event){const input=document.getElementById('qrUrlInput');const url=input?.value;if(!url){Swal.fire({icon:'error',title:'Error',text:'No se encontró la URL',confirmButtonText:'Aceptar'});return}navigator.clipboard.writeText(url).then(()=>{const btn=event.target.closest('.btn-copy-url');const originalHTML=btn.innerHTML;btn.innerHTML='<i class="fa-solid fa-check"></i>';btn.style.background='linear-gradient(135deg,#22c55e 0%,#16a34a 100%)';setTimeout(()=>{btn.innerHTML=originalHTML;btn.style.background='linear-gradient(135deg,#667eea 0%,#764ba2 100%)'},1500)}).catch((err)=>{Swal.fire({icon:'error',title:'Error al copiar',text:'No se pudo copiar la URL',confirmButtonText:'Aceptar'})})}
</script>
@endpush
