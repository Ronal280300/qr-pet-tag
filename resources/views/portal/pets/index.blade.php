@extends('layouts.app')

@push('styles')
<style>
/* SAAS ESTÉTICA PREMIUM - REDISEÑO V2 */

:root {
  /* Paleta original respetada (Azul vibrante) */
  --saas-primary: #2563eb;
  --saas-primary-hover: #1d4ed8;
  --saas-primary-soft: #eff6ff;
  
  --saas-bg: #F8FAFC;
  --saas-surface: #FFFFFF;
  --saas-text: #0F172A;
  --saas-text-muted: #64748B;
  --saas-border: #E2E8F0;
  
  --saas-radius-lg: 24px;
  --saas-radius: 16px;
  
  --saas-shadow-sm: 0 8px 24px rgba(15, 23, 42, 0.08); /* Sombra base más pronunciada */
  --saas-shadow-md: 0 12px 24px -4px rgba(15, 23, 42, 0.1);
  --saas-shadow-hover: 0 25px 40px -10px rgba(37, 99, 235, 0.18); /* Sombra azulada al hover */
}

/* Layout Page */
.pets-dashboard {
  padding: 2.5rem 0 5rem;
  background: transparent;
  animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  max-width: 1200px;
  margin: 0 auto;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Header Premium */
.dash-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 2rem;
  padding-bottom: 1.5rem;
}

.dash-title-group h1 {
  font-size: 2.75rem;
  font-weight: 900;
  letter-spacing: -0.04em;
  color: var(--saas-text);
  margin: 0;
  line-height: 1.1;
}

.dash-title-group p {
  color: var(--saas-text-muted);
  font-size: 1.1rem;
  margin: 0.5rem 0 0;
  font-weight: 500;
}

.dash-actions {
  display: flex;
  gap: 1rem;
}

.saas-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.6rem;
  padding: 0.9rem 1.6rem;
  font-size: 0.95rem;
  font-weight: 700;
  border-radius: 14px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  text-decoration: none;
  cursor: pointer;
  border: none;
}

.saas-btn-primary {
  background: var(--saas-primary);
  color: #FFF;
  box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
}
.saas-btn-primary:hover {
  background: var(--saas-primary-hover);
  color: #FFF;
  transform: translateY(-2px) scale(1.02);
  box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35);
}

.saas-btn-outline {
  background: var(--saas-surface);
  color: var(--saas-primary);
  border: 2px solid transparent;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.saas-btn-outline:hover {
  background: var(--saas-primary-soft);
  color: var(--saas-primary-hover);
}

/* Top Stats */
.dash-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1.25rem;
  margin-bottom: 2.5rem;
}

.stat-pill {
  background: var(--saas-surface);
  border: none;
  padding: 1.25rem 1.75rem;
  border-radius: var(--saas-radius);
  display: flex;
  align-items: center;
  gap: 1.25rem;
  box-shadow: var(--saas-shadow-sm);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
  overflow: hidden;
}

.stat-pill::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; height: 3px;
  background: var(--saas-primary);
  opacity: 0;
  transition: opacity 0.3s;
}

.stat-pill:hover {
  transform: translateY(-4px);
  box-shadow: var(--saas-shadow-md);
}
.stat-pill:hover::before { opacity: 1; }

.stat-pill-icon {
  width: 50px;
  height: 50px;
  background: var(--saas-primary-soft);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--saas-primary);
  font-size: 1.25rem;
}

.stat-pill-data {
  display: flex;
  flex-direction: column;
}

.stat-pill-data strong {
  font-size: 1.75rem;
  font-weight: 800;
  color: var(--saas-text);
  line-height: 1;
}

.stat-pill-data span {
  font-size: 0.85rem;
  color: var(--saas-text-muted);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-top: 4px;
}

/* Barra de Herramientas */
.dash-tools {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 3rem;
  background: var(--saas-surface);
  padding: 0.75rem;
  border-radius: var(--saas-radius-lg);
  box-shadow: var(--saas-shadow-md);
}

.saas-filters {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.saas-filter-btn {
  padding: 0.75rem 1.25rem;
  border-radius: 12px;
  background: transparent;
  border: none;
  font-weight: 700;
  font-size: 0.9rem;
  color: var(--saas-text-muted);
  cursor: pointer;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.saas-filter-btn:hover {
  background: var(--saas-bg);
  color: var(--saas-text);
}
.saas-filter-btn.active {
  background: var(--saas-primary-soft);
  color: var(--saas-primary);
}

.saas-search {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0 1.25rem;
  width: 340px;
  position: relative;
  border-left: 1px solid var(--saas-border);
}
.saas-search i { color: var(--saas-primary); font-size: 1.1rem; }
.saas-search input {
  border: none;
  background: transparent;
  outline: none;
  width: 100%;
  font-weight: 600;
  font-size: 0.95rem;
  color: var(--saas-text);
}
.saas-search input::placeholder { color: #94A3B8; font-weight: 500; }
.search-clear { color: #94A3B8; cursor: pointer; transition: color 0.2s; }
.search-clear:hover { color: #EF4444; }

/* Grid Moderno V2 */
.saas-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2.5rem;
  margin-bottom: 4rem;
}

/* Card Super Premium */
.saas-card {
  background: var(--saas-surface);
  border-radius: var(--saas-radius-lg);
  border: none;
  overflow: hidden;
  box-shadow: var(--saas-shadow-sm);
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  display: flex;
  flex-direction: column;
  position: relative;
}

.saas-card:hover {
  transform: translateY(-10px);
  box-shadow: var(--saas-shadow-hover);
}

.card-hero {
  position: relative;
  aspect-ratio: 4/3; 
  background: #F1F5F9;
  overflow: hidden;
}

.card-hero img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
}

.saas-card:hover .card-hero img {
  transform: scale(1.08);
}

.card-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(15, 23, 42, 0.8) 0%, transparent 60%);
  opacity: 0;
  transition: opacity 0.4s ease;
}

.saas-card:hover .card-hero::after {
  opacity: 1;
}

.card-badges {
  position: absolute;
  top: 1.25rem;
  left: 1.25rem;
  right: 1.25rem;
  display: flex;
  justify-content: space-between;
  z-index: 2;
}

.saas-badge {
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  padding: 0.5rem 1rem;
  border-radius: 100px;
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  display: flex;
  align-items: center;
  gap: 0.4rem;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.badge-alert { background: rgba(239, 68, 68, 0.95); color: white; border: 1px solid rgba(255,255,255,0.2); }
.badge-reward { background: rgba(16, 185, 129, 0.95); color: white; border: 1px solid rgba(255,255,255,0.2); }

/* Botones de acción inferiores fijos en móvil y PC para mejor UX */
.card-bottom-actions {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
  margin-top: 1.5rem;
}

.btn-action-solid {
  background: var(--saas-primary);
  color: white;
  border-radius: 12px;
  padding: 0.8rem;
  text-align: center;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  border: 1px solid transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
}
.btn-action-solid:hover {
  background: var(--saas-primary-hover);
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.btn-action-light {
  background: var(--saas-primary-soft);
  color: var(--saas-primary);
  border-radius: 12px;
  padding: 0.8rem;
  text-align: center;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  border: 1px solid transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
}
.btn-action-light:hover {
  background: white;
  border-color: var(--saas-primary);
  color: var(--saas-primary-hover);
  transform: translateY(-2px);
}

.card-body {
  padding: 1.75rem;
  display: flex;
  flex-direction: column;
  flex: 1;
  background: var(--saas-surface);
}

.card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.pet-title {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--saas-text);
  margin: 0;
  letter-spacing: -0.02em;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.pet-gender {
  color: var(--saas-text-muted);
  font-size: 1.2rem;
}
.pet-gender.male { color: var(--saas-primary); }
.pet-gender.female { color: #EC4899; }

.pet-details {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.detail-row {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--saas-text-muted);
}
.detail-row i {
  color: var(--saas-text-muted);
  width: 16px;
  text-align: center;
  opacity: 0.7;
}

/* Paginación minimalista (Full Números) */
.saas-pagination-container {
  display: flex;
  justify-content: center;
  margin-top: 3rem;
  width: 100%;
}

.saas-numbers-wrapper {
  background: var(--saas-surface);
  padding: 0.5rem;
  border-radius: 100px;
  box-shadow: var(--saas-shadow-md);
  display: inline-flex;
  gap: 0.25rem;
  border: 1px solid var(--saas-border);
  overflow-x: auto;
  max-width: 100%;
  scrollbar-width: none; /* Firefox */
}

.saas-numbers-wrapper::-webkit-scrollbar {
  display: none; /* Chrome, Webkit */
}

.page-num {
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--saas-text-muted);
  text-decoration: none;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

/* Nav arrows style */
.page-nav-arrow {
  width: auto;
  padding: 0 1rem;
  border-radius: 100px;
}

.page-num:hover:not(.disabled) {
  background: var(--saas-primary-soft);
  color: var(--saas-primary);
}

.page-num.active {
  background: var(--saas-primary);
  color: white;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

.page-num.disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.page-dots {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  color: var(--saas-text-muted);
  font-weight: 800;
}

/* Empty State */
.saas-empty {
  grid-column: span 3;
  text-align: center;
  padding: 8rem 2rem;
  background: transparent;
  border: 2px dashed #CBD5E1;
  border-radius: var(--saas-radius-lg);
}
.saas-empty-icon {
  width: 96px;
  height: 96px;
  background: var(--saas-surface);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  font-size: 3rem;
  color: var(--saas-primary);
  box-shadow: var(--saas-shadow-sm);
}
.saas-empty h3 { font-size: 1.75rem; font-weight: 800; color: var(--saas-text); margin-bottom: 0.5rem; }
.saas-empty p { color: var(--saas-text-muted); margin-bottom: 2.5rem; font-size: 1.1rem; }

/* Responsive */
@media (max-width: 1024px) {
  .saas-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
  .saas-empty { grid-column: span 2; }
}

@media (max-width: 768px) {
  .dash-header { flex-direction: column; align-items: flex-start; gap: 1.5rem; }
  .dash-actions { width: 100%; display: grid; grid-template-columns: 1fr 1fr; }
  .saas-btn { justify-content: center; padding: 1rem; }
  
  .dash-stats { grid-template-columns: 1fr; gap: 1rem; }
  
  .dash-tools { flex-direction: column; padding: 1rem; gap: 1.25rem; border-radius: 20px; }
  .saas-filters { width: 100%; overflow-x: auto; padding-bottom: 0.5rem; flex-wrap: nowrap; scrollbar-width: none; }
  .saas-filters::-webkit-scrollbar { display: none; }
  .saas-filter-btn { flex-shrink: 0; }
  .saas-search { width: 100%; border-left: none; border-top: 1px solid var(--saas-border); padding: 1rem 0 0 0; }
  
  .saas-grid { grid-template-columns: 1fr; margin-bottom: 2rem; }
  .saas-empty { grid-column: span 1; }
  
  .saas-numbers-wrapper { padding: 0.5rem 0.25rem; justify-content: flex-start; }
}

</style>
@endpush

@section('content')
<div class="pets-dashboard">
  
  {{-- Header --}}
  <div class="dash-header">
    <div class="dash-title-group">
      <h1>{{ auth()->user()->is_admin ? 'Directorio Mascotas' : 'Mis Mascotas' }}</h1>
      <p>Vista general y administración total de registros</p>
    </div>
    <div class="dash-actions">
      @if(auth()->user()->is_admin)
        <a href="{{ route('portal.pets.create') }}" class="saas-btn saas-btn-outline">
          <i class="fa-solid fa-plus"></i>
          <span>Nuevo Registro</span>
        </a>
      @endif
      <a href="{{ route('portal.activate-tag') }}" class="saas-btn saas-btn-primary">
        <i class="fa-solid fa-qrcode"></i>
        <span>Activar TAG</span>
      </a>
    </div>
  </div>

  {{-- Stats --}}
  <div class="dash-stats">
    <div class="stat-pill">
      <div class="stat-pill-icon"><i class="fa-solid fa-folder-open"></i></div>
      <div class="stat-pill-data">
        <strong>{{ $pets->total() }}</strong>
        <span>Registros</span>
      </div>
    </div>
    <div class="stat-pill">
      <div class="stat-pill-icon" style="background:#FEF2F2; color:#EF4444;"><i class="fa-solid fa-bell"></i></div>
      <div class="stat-pill-data">
        <strong>{{ $pets->getCollection()->where('is_lost', true)->count() }}</strong>
        <span>Extraviadas</span>
      </div>
    </div>
    <div class="stat-pill">
      <div class="stat-pill-icon" style="background:#ECFDF5; color:#10B981;"><i class="fa-solid fa-award"></i></div>
      <div class="stat-pill-data">
        <strong>{{ $pets->getCollection()->filter(fn($p)=>optional($p->reward)->active)->count() }}</strong>
        <span>Recompensadas</span>
      </div>
    </div>
  </div>

  {{-- Herramientas Modernas --}}
  <div class="dash-tools">
    <div class="saas-filters" id="filterBar">
      <button class="saas-filter-btn active" data-filter="all"><i class="fa-solid fa-layer-group"></i> Todos</button>
      <button class="saas-filter-btn" data-filter="lost"><i class="fa-solid fa-location-crosshairs"></i> Perdidas</button>
      <button class="saas-filter-btn" data-filter="reward"><i class="fa-solid fa-sack-dollar"></i> Con Premio</button>
      <button class="saas-filter-btn" data-filter="sex:male"><i class="fa-solid fa-mars"></i> Machos</button>
      <button class="saas-filter-btn" data-filter="sex:female"><i class="fa-solid fa-venus"></i> Hembras</button>
    </div>

    @if(auth()->user()->is_admin)
      <form method="GET" action="{{ route('portal.pets.index') }}" id="searchForm">
        <div class="saas-search">
          <i class="fa-solid fa-search"></i>
          <input type="text" name="search" id="petSearch" placeholder="Buscar por nombre o raza..." value="{{ request('search') }}">
          @if(request('search'))
            <a href="{{ route('portal.pets.index') }}" class="search-clear" title="Limpiar"><i class="fa-solid fa-xmark"></i></a>
          @endif
        </div>
      </form>
    @endif
  </div>

  {{-- Grid UI --}}
  @if($pets->isEmpty())
    <div class="saas-grid">
      <div class="saas-empty">
        <div class="saas-empty-icon"><i class="fa-brands fa-space-awesome"></i></div>
        <h3>El directorio está vacío</h3>
        <p>No se encontraron registros de mascotas en esta base de datos en este momento.</p>
        <a href="{{ route('portal.activate-tag') }}" class="saas-btn saas-btn-primary">Vincular TAG ahora</a>
      </div>
    </div>
  @else
    <div class="saas-grid" id="petGrid">
      @foreach($pets as $pet)
        @php
          $hasReward = optional($pet->reward)->active ? '1' : '0';
          $sex = $pet->sex ?? 'unknown';
        @endphp
        <div class="saas-card pet-card"
             data-lost="{{ $pet->is_lost ? '1' : '0' }}"
             data-reward="{{ $hasReward }}"
             data-sex="{{ $sex }}">
          
          {{-- Hero Image & Flotantes --}}
          <div class="card-hero">
            <img src="{{ $pet->main_photo_url }}" alt="{{ $pet->name }}" loading="lazy">
            <div class="card-badges">
              @if($pet->is_lost)
                <span class="saas-badge badge-alert"><i class="fa-solid fa-bolt"></i> Buscada</span>
              @else
                <span></span>
              @endif
              
              @if(optional($pet->reward)->active)
                <span class="saas-badge badge-reward">
                  <i class="fa-solid fa-star"></i> 
                  @if(optional($pet->reward)->amount) ₡{{ number_format((float)$pet->reward->amount, 0) }} @else Premio @endif
                </span>
              @endif
            </div>

            {{-- Removido quick-actions flotantes para mejor UX en móviles --}}
          </div>

          {{-- Content --}}
          <div class="card-body">
            <div class="card-meta">
              <h3 class="pet-title">{{ $pet->name }}</h3>
              @if($sex === 'male')
                <i class="fa-solid fa-mars pet-gender male"></i>
              @elseif($sex === 'female')
                <i class="fa-solid fa-venus pet-gender female"></i>
              @else
                <i class="fa-solid fa-minus pet-gender"></i>
              @endif
            </div>

            <div class="pet-details">
              @if($pet->breed)
                <div class="detail-row"><i class="fa-solid fa-paw"></i> {{ Str::limit($pet->breed, 20) }}</div>
              @endif
              @if($pet->zone)
                <div class="detail-row"><i class="fa-solid fa-map-pin"></i> {{ Str::limit($pet->zone, 20) }}</div>
              @endif
            </div>

            {{-- Fijos al fondo para un tacto seguro en móviles y PC --}}
            <div class="card-bottom-actions">
              <a href="{{ route('portal.pets.show', $pet) }}" class="btn-action-light"><i class="fa-regular fa-eye"></i> Ver</a>
              <a href="{{ route('portal.pets.edit', $pet) }}" class="btn-action-solid"><i class="fa-solid fa-pen-nib"></i> Editar</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    {{-- Empty JS State --}}
    <div class="saas-grid" id="emptyFiltered" style="display: none;">
      <div class="saas-empty" style="grid-column: span 3; padding: 4rem 2rem;">
        <div class="saas-empty-icon" style="width:72px; height:72px; font-size:2rem;"><i class="fa-solid fa-ghost"></i></div>
        <h3 style="font-size:1.25rem;">Sin coincidencias</h3>
        <p style="margin-bottom:0;">Intenta limpiar los filtros visuales seleccionados arriba.</p>
      </div>
    </div>

    {{-- Paginación Minimalista (Pestañas en números responsive) --}}
    @if($pets->hasPages())
      <div class="saas-pagination-container">
        <div class="saas-numbers-wrapper">
          
          {{-- Prev --}}
          @if ($pets->onFirstPage())
             <span class="page-num page-nav-arrow disabled"><i class="fa-solid fa-chevron-left"></i></span>
          @else
             <a href="{{ $pets->previousPageUrl() }}" class="page-num page-nav-arrow"><i class="fa-solid fa-chevron-left"></i></a>
          @endif

          @php
             $currentPage = $pets->currentPage();
             $lastPage = $pets->lastPage();
             $start = max(1, $currentPage - 2);
             $end = min($lastPage, $currentPage + 2);
          @endphp

          @if($start > 1)
             <a href="{{ $pets->url(1) }}" class="page-num">1</a>
             @if($start > 2)
               <span class="page-dots"><i class="fa-solid fa-ellipsis"></i></span>
             @endif
          @endif

          @for ($i = $start; $i <= $end; $i++)
             <a href="{{ $pets->url($i) }}" class="page-num {{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
          @endfor

          @if($end < $lastPage)
             @if($end < $lastPage - 1)
               <span class="page-dots"><i class="fa-solid fa-ellipsis"></i></span>
             @endif
             <a href="{{ $pets->url($lastPage) }}" class="page-num">{{ $lastPage }}</a>
          @endif

          {{-- Next --}}
          @if ($pets->hasMorePages())
             <a href="{{ $pets->nextPageUrl() }}" class="page-num page-nav-arrow"><i class="fa-solid fa-chevron-right"></i></a>
          @else
             <span class="page-num page-nav-arrow disabled"><i class="fa-solid fa-chevron-right"></i></span>
          @endif

        </div>
      </div>
    @endif
  @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const grid = document.getElementById('petGrid');
  if (!grid) return;

  const items = Array.from(grid.querySelectorAll('.pet-card'));
  const emptyFiltered = document.getElementById('emptyFiltered');
  const filterBar = document.getElementById('filterBar');

  const state = { lost: false, reward: false, sex: null };

  // JS Search Submit
  const searchInput = document.getElementById('petSearch');
  const searchForm = document.getElementById('searchForm');
  if (searchInput && searchForm) {
    let searchTimeout;
    searchInput.addEventListener('input', () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => searchForm.submit(), 600);
    });
  }

  // Filter Logic
  filterBar.addEventListener('click', (e) => {
    let btn = e.target.closest('.saas-filter-btn');
    if (!btn) return;

    const filter = btn.dataset.filter;
    const isActive = btn.classList.contains('active');

    if (filter === 'all') {
      state.lost = false; state.reward = false; state.sex = null;
      filterBar.querySelectorAll('button').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    } else {
      filterBar.querySelector('[data-filter="all"]').classList.remove('active');
      
      if (filter === 'lost') {
        state.lost = !isActive;
        btn.classList.toggle('active');
      } else if (filter === 'reward') {
        state.reward = !isActive;
        btn.classList.toggle('active');
      } else if (filter.startsWith('sex:')) {
        const val = filter.split(':')[1];
        filterBar.querySelectorAll('[data-filter^="sex:"]').forEach(b => b.classList.remove('active'));
        if (state.sex === val) {
          state.sex = null;
        } else {
          state.sex = val;
          btn.classList.add('active');
        }
      }
    }
    applyFilters();
  });

  function applyFilters() {
    let visibleCount = 0;
    items.forEach(card => {
      const matchLost = !state.lost || card.dataset.lost === '1';
      const matchReward = !state.reward || card.dataset.reward === '1';
      const matchSex = !state.sex || card.dataset.sex === state.sex;
      
      const show = matchLost && matchReward && matchSex;
      
      if(show) {
        card.style.display = '';
        visibleCount++;
        // Trigger reflow to restart animation on filter
        card.style.animation = 'none';
        card.offsetHeight; 
        card.style.animation = 'fadeIn 0.4s ease forwards';
      } else {
        card.style.display = 'none';
      }
    });

    if (emptyFiltered) {
      emptyFiltered.style.display = visibleCount === 0 ? '' : 'none';
      grid.style.display = visibleCount === 0 ? 'none' : 'grid';
    }
  }
});
</script>
@endpush
