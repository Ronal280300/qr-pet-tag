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

<div class="saas-show-container">
  <div class="saas-grid-layout">
    
    {{-- Columna Izquierda: Detalles de Mascota --}}
    <div class="saas-col-main">
      <div class="saas-panel p-0 hero-panel">
        {{-- Carrusel Moderno --}}
        <div class="saas-carousel-wrapper no-swipe">
          <div id="petPhotosCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner">
              @if($photos->isEmpty())
                <div class="carousel-item active">
                  <div class="hero-image-box js-skel">
                    <img src="{{ $mainPhotoUrl }}" alt="Mascota" loading="lazy">
                  </div>
                </div>
              @else
                @foreach($photos as $i => $ph)
                  <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                    <div class="hero-image-box js-skel">
                      <img src="{{ asset('storage/'.$ph->path) }}" alt="Mascota {{ $i+1 }}" loading="lazy">
                    </div>
                  </div>
                @endforeach
              @endif
            </div>

            @if($photos->count() > 1)
              <button class="carousel-control-prev saas-carousel-btn" type="button" data-bs-target="#petPhotosCarousel" data-bs-slide="prev">
                <i class="fa-solid fa-chevron-left"></i>
              </button>
              <button class="carousel-control-next saas-carousel-btn" type="button" data-bs-target="#petPhotosCarousel" data-bs-slide="next">
                <i class="fa-solid fa-chevron-right"></i>
              </button>
              
              <div class="carousel-indicators-modern">
                @foreach($photos as $i => $ph)
                  <button type="button" data-bs-target="#petPhotosCarousel" data-bs-slide-to="{{ $i }}" class="{{ $i === 0 ? 'active' : '' }}"></button>
                @endforeach
              </div>
            @endif
          </div>
        </div>

        {{-- Cabecera e Info Principal --}}
        <div class="hero-body">
          <div class="hero-header">
            <div class="pet-title-block">
              <h1 class="saas-pet-name">
                {{ $pet->name }}
                @if(($pet->sex ?? 'unknown') === 'male')
                  <i class="fa-solid fa-mars gender-icon male"></i>
                @elseif(($pet->sex ?? 'unknown') === 'female')
                  <i class="fa-solid fa-venus gender-icon female"></i>
                @endif
              </h1>
              @if($pet->is_lost)
                <span class="saas-badge alert-badge"><i class="fa-solid fa-triangle-exclamation"></i> Buscada / Perdida</span>
              @endif
            </div>
            
            <div class="hero-actions">
              <a href="{{ route('portal.pets.edit', $pet) }}" class="saas-btn-light">
                <i class="fa-solid fa-pen"></i> Editar
              </a>

              @if($isAdmin)
                <form action="{{ route('portal.pets.destroy', $pet) }}" method="POST" class="pet-delete-form d-inline-block">
                  @csrf @method('DELETE')
                  <button type="submit" class="saas-btn-danger">
                    <i class="fa-solid fa-trash-can"></i> Eliminar
                  </button>
                </form>

                <button type="button" class="saas-btn-blue" data-url="{{ route('portal.pets.share.facebook', $pet) }}" data-name="{{ $pet->name }}" data-page="{{ config('services.facebook.page_id') }}" onclick="publishToFacebook(event)">
                  <i class="fa-brands fa-facebook"></i>
                  <span class="d-none d-sm-inline">Publicar</span>
                </button>
              @endif
            </div>
          </div>

          {{-- Data Grid (Pills) --}}
          <div class="saas-stats-grid">
            <div class="stat-pill">
              <i class="fa-solid fa-paw icon-accent"></i>
              <div>
                <label>Raza</label>
                <strong>{{ $pet->breed ?: 'Mestizo' }}</strong>
              </div>
            </div>
            <div class="stat-pill">
              <i class="fa-solid fa-cake-candles icon-accent"></i>
              <div>
                <label>Edad</label>
                <strong>{{ !is_null($pet->age) ? $pet->age . ' ' . Str::plural('año', $pet->age) : 'Desconocida' }}</strong>
              </div>
            </div>
            <div class="stat-pill">
              <i class="fa-solid fa-map-location-dot icon-accent"></i>
              <div>
                <label>Ubicación</label>
                <strong>{{ $pet->zone ?: 'Sin zona' }}</strong>
              </div>
            </div>
            <div class="stat-pill">
              <i class="fa-solid fa-syringe icon-accent"></i>
              <div>
                <label>Antirrábica</label>
                <strong>{{ $pet->rabies_vaccine ? 'Al día' : 'No reportado' }}</strong>
              </div>
            </div>
            <div class="stat-pill">
              <i class="fa-solid fa-scissors icon-accent"></i>
              <div>
                <label>Castración</label>
                <strong>{{ $pet->is_neutered ? 'Sí' : 'No' }}</strong>
              </div>
            </div>
            <div class="stat-pill">
              <i class="fa-solid fa-user icon-accent"></i>
              <div>
                <label>Propietario</label>
                <strong>{{ optional($pet->user)->name ?: 'Desconocido' }}</strong>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Panel de Notas / Observaciones --}}
      <div class="saas-panel highlight-panel">
        <div class="panel-header">
          <i class="fa-solid fa-stethoscope panel-icon"></i>
          <h3>Notas y Cuidados</h3>
        </div>
        <div class="panel-body">
          <p class="notes-text">{{ $pet->medical_conditions ?: 'Sin notas u observaciones médicas especiales.' }}</p>
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="saas-main-actions">
        <form action="{{ route('portal.pets.toggle-lost', $pet) }}" method="POST" id="toggleLostForm">
          @csrf
          <button type="submit" class="saas-btn-xl {{ $pet->is_lost ? 'btn-warn' : 'btn-danger-gradient' }}" id="toggleLostBtn">
            <i class="fa-solid fa-bullhorn"></i>
            <span>{{ $pet->is_lost ? 'Reportar mascota encontrada' : 'Reportar Extraviada/Robada' }}</span>
          </button>
        </form>
        @if($pet->is_lost)
          <form action="{{ route('portal.pets.share-card', $pet) }}" method="POST">
            @csrf
            <button type="submit" class="saas-btn-xl btn-info-gradient">
              <i class="fa-solid fa-images"></i>
              <span>Generar Póster para Redes</span>
            </button>
          </form>
        @endif
      </div>
    </div>

    {{-- Columna Derecha: Interacciones Rápidas --}}
    <div class="saas-col-sidebar">
      
      {{-- Posterior a Generar Póster --}}
      @if (session('share_card_url'))
        <div class="saas-panel animated-panel" id="shareCardPreview">
          <div class="panel-header justify-between">
            <div class="flex items-center gap-2">
              <i class="fa-solid fa-check-circle text-green"></i>
              <h3 class="m-0">Póster Generado</h3>
            </div>
            <div class="flex gap-2">
              <a href="{{ session('share_card_url') }}" download="mascota.png" class="mini-icon-btn"><i class="fa-solid fa-download"></i></a>
              <button type="button" id="btnShareCard" data-url="{{ session('share_card_url') }}" data-title="Mascota pérdida: {{ $pet->name }}" class="mini-icon-btn"><i class="fa-solid fa-share-nodes"></i></button>
            </div>
          </div>
          <div class="poster-preview-box js-skel">
            <img src="{{ session('share_card_url') }}" alt="Póster" loading="lazy">
          </div>
        </div>
      @endif

      {{-- Panel QR Code --}}
      <div class="saas-panel">
        <div class="panel-header justify-between">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-qrcode panel-icon"></i>
            <h3 class="m-0">Código QR</h3>
          </div>
        </div>

        <div class="qr-container js-skel">
          @if($qrImageUrl)
            <img src="{{ $qrImageUrl }}" alt="QR" class="qr-img" loading="lazy">
          @else
            <div class="qr-empty-state">
              <i class="fa-solid fa-qrcode"></i>
              <span>No generado</span>
            </div>
          @endif
        </div>

        @if($isAdmin && $qr && $qr->activation_code)
          <div class="tag-box no-swipe">
            <div class="tag-badge" id="tagCode" data-tag="{{ $qr->activation_code }}">
              <span class="tag-lbl">TAG</span>
              <strong class="tag-val">{{ $qr->activation_code }}</strong>
            </div>
            <button type="button" class="mini-icon-btn btn-copy-tag" onclick="copyTag(event)"><i class="fa-regular fa-copy"></i></button>
          </div>
        @endif

        @if($publicUrl)
          <div class="url-box no-swipe">
            <input type="text" value="{{ $publicUrl }}" readonly id="qrUrlInput">
            <button type="button" class="mini-icon-btn btn-copy-url" onclick="copyQrUrl(event)"><i class="fa-regular fa-copy"></i></button>
          </div>
        @endif

        <div class="panel-footer-actions">
          @if($isAdmin)
            <form action="{{ route('portal.pets.generate-qr', $pet) }}" method="POST" class="no-swipe" data-confirm="{{ $qr && !blank($qr->slug) ? '¿Regenerar QR y reemplazar el actual?' : '¿Generar código QR de la mascota?' }}">
              @csrf
              <button class="saas-btn-block btn-primary-gradient">
                <i class="fa-solid fa-rotate"></i> {{ $qr && !blank($qr->slug) ? 'Regenerar QR' : 'Crear Código QR' }}
              </button>
            </form>
          @endif
          
          <div class="grid-2-col">
            <a href="{{ $canDownloadQr ? route('portal.pets.download-qr', $pet) : '#' }}" class="saas-btn-block saas-btn-light {{ !$canDownloadQr ? 'disabled' : '' }}">
              <i class="fa-solid fa-download"></i> Descargar QR
            </a>
            <a href="{{ $publicUrl ?: '#' }}" target="_blank" class="saas-btn-block saas-btn-light {{ !$publicUrl ? 'disabled' : '' }}">
              <i class="fa-solid fa-eye"></i> Perfil
            </a>
          </div>

          @if($isAdmin && $qr)
            <form action="{{ route('portal.pets.regen-code', $pet) }}" method="POST" class="no-swipe" data-confirm="¿Reemplazar TAG de activación de esta mascota?">
              @csrf
              <button class="saas-btn-block saas-btn-light text-warning">
                <i class="fa-solid fa-triangle-exclamation"></i> Nuevo TAG
              </button>
            </form>
          @endif
        </div>
      </div>

      {{-- Panel de Recompensa --}}
      <div class="saas-panel">
        <div class="panel-header justify-between">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-sack-dollar panel-icon text-green"></i>
            <h3 class="m-0">Recompensa</h3>
          </div>
          <i class="fa-solid fa-circle-question info-tooltip" data-bs-toggle="tooltip" title="Aplica dinero a la mascota. Activalo solo en emergencias."></i>
        </div>

        <form action="{{ route('portal.pets.reward.update', $pet) }}" method="POST" id="rewardForm" class="no-swipe">
          @csrf @method('PUT')
          <input type="hidden" name="active" id="rwActive" value="0">

          <div class="ios-toggle-box {{ !$pet->is_lost ? 'locked' : '' }}" id="rewardToggleContainer">
            <span>Visibilidad en modo público</span>
            <div id="rwSwitch" class="ios-switch">
              <span class="ios-slider"></span>
            </div>
          </div>

          @if(!$pet->is_lost)
            <div class="alert-box-soft-warning">
              <i class="fa-solid fa-lock"></i> Debes marcar a la mascota extraviada para activar esta caja de recompensa.
            </div>
          @endif

          <div class="reward-fields">
            <div class="saas-input-group">
              <label>Monto Ofrecido</label>
              <div class="input-with-icon">
                <span class="icon">₡</span>
                <input type="text" name="amount" id="rwAmount" inputmode="decimal" class="saas-input" placeholder="0.00" value="{{ optional($pet->reward)->amount }}">
              </div>
            </div>
            <div class="saas-input-group">
              <label>Mensaje Opcional</label>
              <input type="text" name="message" id="rwMessage" class="saas-input" maxlength="200" placeholder="Ejem: ¡Se lo suplico!" value="{{ optional($pet->reward)->message ?? 'Gracias por tu ayuda' }}">
            </div>
          </div>

          <button type="submit" id="rwSave" class="saas-btn-block btn-green-gradient mt-1">
            <i class="fa-solid fa-floppy-disk"></i> Guardar Recompensa
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
/* SAAS ESTÉTICA PREMIUM - REDISEÑO SHOW V2 */
:root {
  --saas-primary: #2563eb;
  --saas-primary-hover: #1d4ed8;
  --saas-primary-soft: #eff6ff;
  
  --saas-surface: #FFFFFF;
  --saas-bg: #F8FAFC;
  --saas-text: #0F172A;
  --saas-text-muted: #64748B;
  --saas-border: #E2E8F0;
  
  --saas-green: #10B981;
  --saas-warning: #F59E0B;
  --saas-danger: #EF4444;

  --saas-radius: 20px;
  --saas-radius-sm: 12px;
  
  --saas-shadow-sm: 0 4px 12px rgba(15, 23, 42, 0.05);
  --saas-shadow-md: 0 10px 24px -4px rgba(15, 23, 42, 0.08);
}

.saas-show-container {
  padding: 1.5rem 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
  animation: fadeIn 0.5s ease-out;
  overflow-x: hidden;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Utils Flex */
.flex { display: flex; }
.items-center { align-items: center; }
.justify-between { justify-content: space-between; }
.gap-2 { gap: 0.5rem; }
.text-green { color: var(--saas-green) !important; }
.text-warning { color: var(--saas-warning) !important; }
.m-0 { margin: 0 !important; }
.p-0 { padding: 0 !important; }
.mt-1 { margin-top: 1rem !important; }

/* Grid Layout */
.saas-grid-layout {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 2rem;
  align-items: start;
}

/* Panels */
.saas-panel {
  background: var(--saas-surface);
  border-radius: var(--saas-radius);
  box-shadow: var(--saas-shadow-sm);
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  transition: box-shadow 0.3s ease;
  border: 1px solid var(--saas-border);
}
.saas-panel:hover {
  box-shadow: var(--saas-shadow-md);
}
.panel-header {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  margin-bottom: 1.5rem;
}
.panel-header h3 {
  font-size: 1.25rem;
  font-weight: 800;
  color: var(--saas-text);
  letter-spacing: -0.01em;
}
.panel-icon {
  font-size: 1.4rem;
  color: var(--saas-primary);
}

/* Hero Carousel */
.hero-panel { overflow: hidden; }
.saas-carousel-wrapper {
  position: relative;
  background: var(--saas-bg);
}
.hero-image-box {
  aspect-ratio: 16/9;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: var(--saas-surface);
}
.hero-image-box img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 1rem;
  background: #F8FAFC;
}
.saas-carousel-btn {
  background: rgba(255,255,255,0.8);
  backdrop-filter: blur(8px);
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: none;
  font-size: 1.1rem;
  color: var(--saas-text);
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  opacity: 0;
  transition: all 0.3s ease;
  cursor: pointer;
}
.saas-carousel-btn.carousel-control-prev { left: 1rem; }
.saas-carousel-btn.carousel-control-next { right: 1rem; }
.saas-carousel-wrapper:hover .saas-carousel-btn { opacity: 1; }
.saas-carousel-btn:hover { background: white; color: var(--saas-primary); transform: translateY(-50%) scale(1.1); }

/* Hero Body */
.hero-body { padding: 2rem; }
.hero-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1.5rem;
}
.pet-title-block h1 {
  font-size: 1.8rem;
  font-weight: 900;
  color: var(--saas-text);
  margin: 0 0 0.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.gender-icon.male { color: var(--saas-primary); }
.gender-icon.female { color: #EC4899; }
.alert-badge {
  background: var(--saas-danger);
  color: white;
  padding: 0.4rem 0.8rem;
  border-radius: 100px;
  font-size: 0.8rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.hero-actions {
  display: flex;
  gap: 0.5rem;
}

/* Pills Grid */
.saas-stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 1rem;
}
.stat-pill {
  background: var(--saas-bg);
  padding: 0.8rem 1rem;
  border-radius: var(--saas-radius-sm);
  display: flex;
  align-items: center;
  gap: 0.8rem;
  border: 1px solid var(--saas-border);
  transition: all 0.3s ease;
}
.stat-pill:hover {
  background: white;
  border-color: var(--saas-primary-soft);
  transform: translateY(-2px);
  box-shadow: var(--saas-shadow-sm);
}
.icon-accent {
  width: 38px;
  height: 38px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--saas-primary-soft);
  color: var(--saas-primary);
  border-radius: 10px;
  font-size: 1rem;
}
.stat-pill label {
  display: block;
  font-size: 0.65rem;
  color: var(--saas-text-muted);
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 0.2rem;
}
.stat-pill strong {
  display: block;
  font-size: 0.9rem;
  font-weight: 800;
  color: var(--saas-text);
  line-height: 1.2;
}

/* Notes Panel */
.highlight-panel {
  background: linear-gradient(145deg, #ffffff, var(--saas-bg));
  border-top-width: 4px;
  border-top-color: var(--saas-primary);
}
.notes-text {
  font-size: 1rem;
  color: var(--saas-text-muted);
  line-height: 1.6;
  font-weight: 500;
}

/* Main Big Actions */
.saas-main-actions {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.saas-btn-xl {
  width: 100%;
  padding: 0.8rem 1.2rem;
  font-size: 0.95rem;
  font-weight: 700;
  border-radius: 12px;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  color: white;
}
.btn-danger-gradient {
  background: var(--saas-danger);
  box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}
.btn-warn {
  background: var(--saas-warning);
  box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
}
.btn-info-gradient {
  background: #0284c7;
  box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);
}
.saas-btn-xl:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); filter: brightness(1.05); }

/* standard buttons */
.saas-btn-light {
  background: var(--saas-bg);
  border: 1px solid var(--saas-border);
  color: var(--saas-text);
  padding: 0.55rem 1.1rem;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}
.saas-btn-light:hover { background: var(--saas-border); color: var(--saas-primary); }
.saas-btn-danger {
  background: #FEF2F2;
  color: var(--saas-danger);
  border: none;
  padding: 0.55rem 1.1rem;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}
.saas-btn-danger:hover { background: var(--saas-danger); color: white; }
.saas-btn-blue {
  background: var(--saas-primary);
  color: white;
  border: none;
  padding: 0.55rem 1.1rem;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
}
.saas-btn-blue:hover { filter: brightness(1.1); transform: translateY(-1px);}

/* Sidebar Widgets */
.qr-container {
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--saas-bg);
  border: 2px dashed #CBD5E1;
  border-radius: var(--saas-radius-sm);
  padding: 1.25rem;
  min-height: 180px;
  margin-bottom: 1rem;
}
.qr-img { width: 150px; height: 150px; mix-blend-mode: multiply; }
.qr-empty-state { text-align: center; color: #94A3B8; font-weight: 600; font-size: 1.1rem;}
.qr-empty-state i { font-size: 3rem; margin-bottom: 0.5rem; display: block; opacity: 0.5; }

.tag-box, .url-box {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: var(--saas-bg);
  border: 1px solid var(--saas-border);
  padding: 0.5rem;
  border-radius: 10px;
  margin-bottom: 0.75rem;
}
.tag-badge { flex: 1; display: flex; align-items: center; gap: 0.5rem; padding-left: 0.5rem; }
.tag-lbl { font-size: 0.8rem; font-weight: 800; color: var(--saas-text-muted); }
.tag-val { font-size: 1rem; font-weight: 800; color: var(--saas-primary); letter-spacing: 1px;}
.url-box input {
  flex: 1;
  background: transparent;
  border: none;
  outline: none;
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--saas-text);
  padding-left: 0.5rem;
}
.mini-icon-btn {
  width: 32px;
  height: 32px;
  font-size: 0.85rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: white;
  border: 1px solid var(--saas-border);
  border-radius: 8px;
  color: var(--saas-primary);
  cursor: pointer;
  transition: all 0.2s;
}
.mini-icon-btn:hover { background: var(--saas-primary); color: white; border-color: var(--saas-primary); }

.panel-footer-actions {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  margin-top: 1.25rem;
}
.grid-2-col { display: grid; grid-template-columns: 1fr 1fr; gap: 0.6rem; }
.saas-btn-block {
  width: 100%;
  padding: 0.6rem;
  text-align: center;
  font-weight: 700;
  font-size: 0.85rem;
  border-radius: 12px;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  text-decoration: none;
}
.btn-primary-gradient {
  background: var(--saas-primary);
  color: white;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
}
.btn-primary-gradient:hover { background: var(--saas-primary-hover); transform: translateY(-2px); }

/* Reward Module */
.ios-toggle-box {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background: var(--saas-bg);
  border-radius: 12px;
  border: 1px solid var(--saas-border);
  cursor: pointer;
  user-select: none;
  margin-bottom: 1rem;
}
.ios-toggle-box span { font-weight: 700; font-size: 0.95rem; color: var(--saas-text); }
.ios-switch {
  width: 52px;
  height: 30px;
  background: #CBD5E1;
  border-radius: 100px;
  position: relative;
  transition: background 0.3s;
}
.ios-slider {
  width: 24px;
  height: 24px;
  background: white;
  border-radius: 50%;
  position: absolute;
  top: 3px;
  left: 3px;
  transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.ios-switch.active { background: var(--saas-green); }
.ios-switch.active .ios-slider { transform: translateX(22px); }
.ios-toggle-box.locked { opacity: 0.6; cursor: not-allowed; }

.alert-box-soft-warning {
  background: #FEF3C7;
  color: #B45309;
  padding: 0.8rem;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 1rem;
}

.reward-fields { display: flex; flex-direction: column; gap: 1rem; }
.saas-input-group label { display: block; font-size: 0.8rem; font-weight: 800; color: var(--saas-text-muted); margin-bottom: 0.4rem; text-transform: uppercase;}
.input-with-icon {
  display: flex;
  align-items: center;
  background: var(--saas-bg);
  border: 1px solid var(--saas-border);
  border-radius: 10px;
  overflow: hidden;
}
.input-with-icon:focus-within { border-color: var(--saas-primary); box-shadow: 0 0 0 3px var(--saas-primary-soft); }
.input-with-icon .icon { padding: 0 1rem; color: var(--saas-green); font-weight: 900; background: #ECFDF5; height: 44px; display: flex; align-items: center; border-right: 1px solid var(--saas-border); }
.saas-input {
  width: 100%;
  height: 44px;
  border: none;
  background: transparent;
  padding: 0 1rem;
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--saas-text);
  outline: none;
}
.saas-input-group > .saas-input {
  background: var(--saas-bg);
  border: 1px solid var(--saas-border);
  border-radius: 10px;
  width: 100%;
}
.saas-input-group > .saas-input:focus { border-color: var(--saas-primary); box-shadow: 0 0 0 3px var(--saas-primary-soft); }

.btn-green-gradient {
  background: var(--saas-green);
  color: white;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
  font-size: 0.9rem;
  padding: 0.75rem;
  border-radius: 12px;
  border: 1px solid transparent;
}
.btn-green-gradient:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(16, 185, 129, 0.25); }

/* Poster Preview */
.poster-preview-box {
  width: 100%;
  aspect-ratio: 4/5;
  background: var(--saas-bg);
  border-radius: 12px;
  overflow: hidden;
  margin-top: 1rem;
  border: 1px solid var(--saas-border);
}
.poster-preview-box img { width: 100%; height: 100%; object-fit: contain; }

/* Skel */
.js-skel { position: relative; }
.js-skel::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(110deg, transparent 0%, rgba(255,255,255,0.6) 50%, transparent 100%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
  pointer-events: none;
}
@keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
.js-skel.is-loaded::after { display: none; }

/* Responsive adjustments */
@media (max-width: 1024px) {
  .saas-grid-layout { grid-template-columns: 1fr; gap: 1.5rem; }
  .saas-panel { margin-bottom: 0; }
  .saas-col-sidebar { display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 2rem;}
}
@media (max-width: 768px) {
  .saas-show-container { padding: 1rem; width: 100%; box-sizing: border-box; overflow-x: hidden; }
  .saas-grid-layout, .saas-col-main, .saas-col-sidebar { width: 100%; box-sizing: border-box; }
  .saas-panel { padding: 1.25rem 1rem; margin-bottom: 1.5rem; width: 100%; box-sizing: border-box; }
  .pet-title-block h1 { font-size: 1.7rem; }
  .hero-header { flex-direction: column; gap: 1rem; align-items: stretch; }
  .hero-actions { width: 100%; display: flex; flex-direction: column; gap: 0.6rem; }
  .hero-actions > * { width: 100%; margin: 0; }
  .hero-actions > a, .hero-actions > button, .hero-actions > form > button { justify-content: center; width: 100%; padding: 0.85rem; }
  .hero-body { padding: 1.25rem 1rem; }
  .saas-stats-grid { grid-template-columns: 1fr; gap: 0.75rem; }
  .hero-image-box { aspect-ratio: 1/1; }
  .saas-btn-xl { font-size: 0.95rem; padding: 1rem; }
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
        confirmButtonColor: '#2563eb'
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
    const shouldEnable = enabled && isPetLost;
    [amt, msg].forEach(el => {
      if(!el) return;
      el.disabled = !shouldEnable;
      el.style.opacity = shouldEnable ? 1 : .5;
    });
  }
  
  // Inicializar estado
  setEnabled(isOn());
  
  // Click en todo el contenedor
  container.addEventListener('click', (e) => {
    if (e.target.tagName === 'INPUT') return;
    setOn(!isOn());
  });
  
  amt?.addEventListener('focus', e => {
    const raw = (e.target.value || '').replace(',', '.');
    const n = parseFloat(raw);
    if (!raw || isNaN(n) || n === 0) e.target.value = '';
    else e.target.select();
  });
  
  amt?.addEventListener('blur', e => {
    const v = normalizeMoney(e.target.value);
    e.target.value = v || '';
  });
  
  form?.addEventListener('submit', (e) => {
    if (!isPetLost) {
      e.preventDefault();
      Swal.fire({
        icon: 'warning',
        title: 'Mascota no está perdida',
        text: 'No puedes configurar una recompensa si la mascota no está marcada como perdida',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#2563eb'
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
(function(){const forms=document.querySelectorAll('.pet-delete-form');forms.forEach(form=>{form.addEventListener('submit',function(e){e.preventDefault();Swal.fire({title:'¿Eliminar mascota?',text:'Esta acción no se puede deshacer.',icon:'warning',showCancelButton:true,confirmButtonText:'Sí, eliminar',cancelButtonText:'Cancelar',confirmButtonColor:'#dc2626'}).then(res=>{if(res.isConfirmed)form.submit()})})})})();

// Compartir
(function(){const btn=document.getElementById('btnShareCard');if(!btn)return;const url=btn.dataset.url;const title=btn.dataset.title||document.title;btn.addEventListener('click',async()=>{if(navigator.share){try{await navigator.share({title,url})}catch(e){}}else{try{await navigator.clipboard.writeText(url);const prev=btn.innerHTML;btn.innerHTML='<i class="fa-solid fa-check"></i>';setTimeout(()=>btn.innerHTML=prev,1300)}catch{alert('Copia este enlace:\n'+url)}}})})();

// Publicar Facebook
async function publishToFacebook(event){const btn=event.currentTarget||event.target;const url=btn.dataset.url;const petName=btn.dataset.name||'la mascota';const pageId=btn.dataset.page||'';const csrf=document.querySelector('meta[name="csrf-token"]')?.content||'';if(!url)return;if(btn.dataset.loading==='1')return;const confirm=await Swal.fire({title:'¿Publicar en Facebook?',html:`Se publicará <b>${petName}</b> en tu Página.`,icon:'question',showCancelButton:true,confirmButtonText:'Sí, publicar',cancelButtonText:'Cancelar', confirmButtonColor:'#2563eb'});if(!confirm.isConfirmed)return;btn.dataset.loading='1';btn.disabled=true;const controller=new AbortController();const timeoutId=setTimeout(()=>controller.abort(),25000);try{Swal.fire({title:'Publicando…',html:'Enviando a Facebook',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});const res=await fetch(url,{method:'POST',headers:{'X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest','Accept':'application/json'},credentials:'same-origin',signal:controller.signal});const raw=await res.text();let data=null;try{data=raw?JSON.parse(raw):null}catch{}clearTimeout(timeoutId);Swal.close();if(!res.ok||!data||data.ok!==true){const msg=(data&&data.error)||`HTTP ${res.status}`;return Swal.fire({icon:'error',title:'Error al publicar',text:msg,confirmButtonText:'Aceptar'})}let fbUrl='';const postId=data?.result?.post_id||data?.result?.id||'';if(postId&&postId.includes('_')){const[pid,suffix]=postId.split('_');fbUrl=`https://www.facebook.com/${pid}/posts/${suffix}`}else if(postId&&pageId){const suffix=postId.split('_').pop();fbUrl=`https://www.facebook.com/${pageId}/posts/${suffix}`}return Swal.fire({icon:'success',title:`¡Publicado en Facebook!`,html:fbUrl?`<a href="${fbUrl}" target="_blank">${fbUrl}</a>`:'Publicación exitosa',confirmButtonText:'Aceptar', confirmButtonColor:'#10B981'})}catch(err){clearTimeout(timeoutId);Swal.close();const msg=(err?.name==='AbortError')?'Tiempo agotado':(err?.message||'Error de red');return Swal.fire({icon:'error',title:'Error',text:msg,confirmButtonText:'Aceptar'})}finally{btn.dataset.loading='';btn.disabled=false}}
window.publishToFacebook=publishToFacebook;

// Skeleton
(function(){const containers=document.querySelectorAll('.js-skel');containers.forEach(c=>{const img=c.querySelector('img');if(!img){c.classList.add('is-loaded');return}const markLoaded=()=>c.classList.add('is-loaded');if(img.complete&&img.naturalWidth>0){markLoaded()}else{img.addEventListener('load',markLoaded,{once:true});img.addEventListener('error',markLoaded,{once:true});setTimeout(()=>c.classList.add('is-loaded'),10000)}})})();

// Confirmar formularios
(function(){const forms=document.querySelectorAll('form[data-confirm]');forms.forEach(form=>{form.addEventListener('submit',async(e)=>{e.stopPropagation();e.preventDefault();const msg=form.getAttribute('data-confirm')||'¿Confirmar?';const res=await Swal.fire({title:'Confirmar',text:msg,icon:'question',showCancelButton:true,confirmButtonText:'Sí',cancelButtonText:'Cancelar', confirmButtonColor:'#2563eb'});if(res.isConfirmed)form.submit()},{passive:false})})})();

// Copiar TAG
function copyTag(event){const tag=document.getElementById('tagCode')?.dataset?.tag;if(!tag){Swal.fire({icon:'error',title:'Error',text:'No se encontró el TAG',confirmButtonText:'Aceptar'});return}navigator.clipboard.writeText(tag).then(()=>{const btn=event.target.closest('.btn-copy-tag');const originalHTML=btn.innerHTML;btn.innerHTML='<i class="fa-solid fa-check"></i>';btn.style.color='#10B981';setTimeout(()=>{btn.innerHTML=originalHTML;btn.style.color=''},1500)}).catch((err)=>{Swal.fire({icon:'error',title:'Error al copiar',text:'No se pudo copiar el TAG: '+tag,confirmButtonText:'Aceptar'})})}

// Copiar URL QR
function copyQrUrl(event){const input=document.getElementById('qrUrlInput');const url=input?.value;if(!url){Swal.fire({icon:'error',title:'Error',text:'No se encontró la URL',confirmButtonText:'Aceptar'});return}navigator.clipboard.writeText(url).then(()=>{const btn=event.target.closest('.btn-copy-url');const originalHTML=btn.innerHTML;btn.innerHTML='<i class="fa-solid fa-check"></i>';btn.style.color='#10B981';setTimeout(()=>{btn.innerHTML=originalHTML;btn.style.color=''},1500)}).catch((err)=>{Swal.fire({icon:'error',title:'Error al copiar',text:'No se pudo copiar la URL',confirmButtonText:'Aceptar'})})}
</script>
@endpush
