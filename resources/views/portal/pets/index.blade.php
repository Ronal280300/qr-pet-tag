@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>

  /* ===== Animaciones globales ===== */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes slideInLeft {
    from {
      opacity: 0;
      transform: translateX(-30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes scaleIn {
    from {
      opacity: 0;
      transform: scale(0.9);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  /* ===== Variables y reset ===== */
  :root {
    --primary: #2563eb;
    --primary-dark: #1e40af;
    --primary-light: #3b82f6;
    --primary-ultra-light: #eff6ff;
    --text-primary: #111827;
    --text-secondary: #6b7280;
    --text-muted: #9ca3af;
    --border: #e5e7eb;
    --bg-page: #f8fafc;
    --bg-card: #ffffff;
    --radius: 12px;
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  /* ===== Page background ===== */
  .pets-page {
    background: var(--bg-page);
    min-height: 100vh;
    padding: 2rem 0;
  }

  .pets-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
  }

  /* ===== Header ===== */
  .pets-header {
    margin-bottom: 2.5rem;
  }

  .header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    margin-bottom: 2rem;
  }


  .header-title h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 0.375rem 0;
    line-height: 1.1;
    letter-spacing: -0.025em;
  }

  .header-subtitle {
    font-size: 1rem;
    color: var(--text-secondary);
    margin: 0;
    font-weight: 500;
  }

  .header-actions {
    display: flex;
    gap: 0.75rem;
    flex-shrink: 0;
  }

  .btn-header {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    font-size: 0.9375rem;
    font-weight: 600;
    border-radius: var(--radius);
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    text-decoration: none;
  }

  .btn-header:hover {
    text-decoration: none;
  }

  .btn-primary-header {
    background: var(--primary);
    color: white;
  }

  .btn-primary-header:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    color: white;
  }

  .btn-secondary-header {
    background: white;
    color: var(--primary);
    border: 1px solid var(--border);
  }

  .btn-secondary-header:hover {
    background: var(--primary-ultra-light);
    border-color: var(--primary);
    color: var(--primary);
  }

  /* ===== Stats minimalistas ===== */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2.5rem;
  }

  .stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background: var(--primary);
    opacity: 0;
    transition: opacity 0.2s ease;
  }

  .stat-card:hover {
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
    transform: translateY(-2px);
  }

  .stat-card:hover::before {
    opacity: 1;
  }

  .stat-icon {
    width: 52px;
    height: 52px;
    background: var(--primary-ultra-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1.375rem;
    flex-shrink: 0;
  }

  .stat-content {
    flex: 1;
    min-width: 0;
  }

  .stat-value {
    font-size: 2rem;
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 0.375rem;
    letter-spacing: -0.025em;
  }

  .stat-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  /* ===== Filters bar ===== */
  .filters-section {
    margin-bottom: 2.5rem;
  }

  .filters-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 0.875rem;
    margin-bottom: 1.25rem;
  }

  .filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.125rem;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text-secondary);
    background: white;
    border: 1px solid var(--border);
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    user-select: none;
  }

  .filter-chip:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-ultra-light);
    transform: translateY(-1px);
  }

  .filter-chip[aria-pressed="true"] {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
  }

  .filter-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.4;
    transition: opacity 0.2s ease;
  }

  .filter-chip:hover .filter-dot,
  .filter-chip[aria-pressed="true"] .filter-dot {
    opacity: 1;
  }

  /* ===== Search box ===== */
  .search-box {
    background: white;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
  }

  .search-box:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
  }

  .search-icon {
    color: var(--text-muted);
    font-size: 1.125rem;
  }

  .search-box:focus-within .search-icon {
    color: var(--primary);
  }

  .search-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 0.9375rem;
    color: var(--text-primary);
    font-weight: 500;
  }

  .search-input::placeholder {
    color: var(--text-muted);
  }

  /* ===== Grid de mascotas ===== */
  .pets-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 1.75rem;
    margin-bottom: 2.5rem;
  }

  .pet-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    position: relative;
  }

  .pet-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.12);
    opacity: 0;
    transition: opacity 0.25s ease;
    pointer-events: none;
  }

  .pet-card:hover {
    border-color: var(--primary);
    transform: translateY(-4px);
  }

  .pet-card:hover::after {
    opacity: 1;
  }

  /* ===== Pet thumbnail ===== */
  .pet-thumbnail {
    position: relative;
    background: linear-gradient(135deg, #f8fafc 0%, var(--primary-ultra-light) 100%);
    aspect-ratio: 4/3;
    overflow: hidden;
  }

  .pet-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .pet-card:hover .pet-image {
    transform: scale(1.06);
  }

  /* ===== Badges ===== */
  .pet-badges {
    position: absolute;
    top: 1rem;
    left: 1rem;
    right: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    pointer-events: none;
    z-index: 10;
  }

  .badge-item {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.875rem;
    font-size: 0.75rem;
    font-weight: 700;
    border-radius: 999px;
    backdrop-filter: blur(12px);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
  }

  .badge-lost {
    background: rgba(239, 68, 68, 0.96);
    color: white;
  }

  .badge-reward {
    background: rgba(34, 197, 94, 0.96);
    color: white;
  }

  /* ===== Pet content ===== */
  .pet-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .pet-name-row {
    display: flex;
    align-items: center;
    gap: 0.625rem;
  }

  .pet-gender-icon {
    font-size: 1.125rem;
    color: var(--primary);
  }

  .pet-name {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    letter-spacing: -0.015em;
  }

  .pet-info {
    display: flex;
    flex-wrap: wrap;
    gap: 0.625rem;
  }

  .info-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.8125rem;
    font-weight: 600;
    color: var(--text-secondary);
    background: var(--bg-page);
    border-radius: 8px;
    border: 1px solid transparent;
    transition: all 0.2s ease;
  }

  .info-tag:hover {
    background: var(--primary-ultra-light);
    border-color: var(--primary);
    color: var(--primary);
  }

  .info-tag i {
    font-size: 0.75rem;
    color: var(--primary);
  }

  /* ===== Pet actions ===== */
  .pet-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-top: auto;
  }

  .btn-pet {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 10px;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
  }

  .btn-pet:hover {
    text-decoration: none;
  }

  .btn-pet-view {
    background: white;
    color: var(--text-secondary);
  }

  .btn-pet-view:hover {
    background: var(--bg-page);
    color: var(--text-primary);
    border-color: var(--text-secondary);
  }

  .btn-pet-edit {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
  }

  .btn-pet-edit:hover {
    background: var(--primary-dark);
    border-color: var(--primary-dark);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
  }

  /* ===== Empty states ===== */
  .empty-state {
    background: white;
    border: 2px dashed var(--border);
    border-radius: 16px;
    padding: 4rem 2rem;
    text-align: center;
  }

  .empty-icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 1.5rem;
    background: var(--primary-ultra-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 2rem;
  }

  .empty-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.625rem;
  }

  .empty-text {
    font-size: 1rem;
    color: var(--text-secondary);
    margin-bottom: 2rem;
  }

  .empty-filtered {
    display: none;
  }

  /* ===== Animaciones sutiles ===== */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(24px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .pet-card {
    animation: fadeInUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation-fill-mode: both;
  }

  .pet-card:nth-child(1) { animation-delay: 0s; }
  .pet-card:nth-child(2) { animation-delay: 0.05s; }
  .pet-card:nth-child(3) { animation-delay: 0.1s; }
  .pet-card:nth-child(4) { animation-delay: 0.15s; }
  .pet-card:nth-child(5) { animation-delay: 0.2s; }
  .pet-card:nth-child(6) { animation-delay: 0.25s; }

  /* ===== Responsive ===== */
  @media (max-width: 768px) {
    .pets-page {
      padding: 1.5rem 0;
    }

    .pets-container {
      padding: 0 1rem;
    }

    .header-top {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }

    .header-title h1 {
      font-size: 1.5rem;
    }

    .header-actions {
      width: 100%;
    }

    .btn-header {
      flex: 1;
      justify-content: center;
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
    }

    .stats-grid {
      grid-template-columns: 1fr;
      gap: 0.75rem;
    }

    .stat-card {
      padding: 1rem;
    }

    .stat-icon {
      width: 42px;
      height: 42px;
      font-size: 1.125rem;
    }

    .stat-value {
      font-size: 1.5rem;
    }

    .stat-label {
      font-size: 0.8125rem;
    }

    .filters-bar {
      gap: 0.5rem;
    }

    .filter-chip {
      padding: 0.5rem 0.875rem;
      font-size: 0.875rem;
    }

    .pets-grid {
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    .pet-content {
      padding: 1rem;
    }
  }

  @media (max-width: 480px) {
    .pets-page {
      padding: 1rem 0;
    }

    .pets-container {
      padding: 0 0.75rem;
    }

    .pets-header {
      margin-bottom: 1.5rem;
    }

    .header-title h1 {
      font-size: 1.375rem;
    }

    .header-subtitle {
      font-size: 0.875rem;
    }

    .btn-header {
      padding: 0.625rem 0.875rem;
      font-size: 0.8125rem;
    }

    .stats-grid {
      gap: 0.625rem;
    }

    .stat-card {
      padding: 0.875rem;
    }

    .filters-section {
      margin-bottom: 1.5rem;
    }

    .search-box {
      padding: 0.625rem 0.875rem;
    }

    .search-input {
      font-size: 0.875rem;
    }

    .pet-name {
      font-size: 1rem;
    }

    .info-tag {
      font-size: 0.75rem;
    }

    .btn-pet {
      padding: 0.5rem 0.75rem;
      font-size: 0.8125rem;
    }

    .empty-state {
      padding: 2rem 1rem;
    }

    .empty-icon {
      width: 56px;
      height: 56px;
      font-size: 1.5rem;
    }
  }

  /* ===== Loading state ===== */
  .pet-thumbnail.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 1.5s infinite;
  }

  @keyframes shimmer {
    to {
      left: 100%;
    }
  }

  /* ===== Pagination mejorada ===== */
  .pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
  }

  .pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .page-item .page-link {
    padding: 0.625rem 1rem;
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--text-secondary);
    background: white;
    border: 1px solid var(--border);
    border-radius: 8px;
    transition: all 0.2s ease;
  }

  .page-item.active .page-link {
    background: var(--primary);
    color: white;
    border-color: var(--primary);
  }

  .page-item:not(.disabled) .page-link:hover {
    background: var(--primary-ultra-light);
    border-color: var(--primary);
    color: var(--primary);
  }

  .page-item.disabled .page-link {
    opacity: 0.5;
    cursor: not-allowed;
  }
</style>
@endpush

@section('content')
<div class="pets-page">
  <div class="pets-container">

    {{-- Header --}}
    <div class="pets-header">
      <div class="header-top">
        <div class="header-title">
          <h1>{{ auth()->user()->is_admin ? 'Gestión de Mascotas' : 'Mis Mascotas' }}</h1>
          <p class="header-subtitle">Administra la información de tus mascotas registradas</p>
        </div>
        <div class="header-actions">
          @if(auth()->user()->is_admin)
            <a href="{{ route('portal.pets.create') }}" class="btn-header btn-secondary-header">
              <i class="fa-solid fa-plus"></i>
              <span>Registrar</span>
            </a>
          @endif
          <a href="{{ route('portal.activate-tag') }}" class="btn-header btn-primary-header">
            <i class="fa-solid fa-tag"></i>
            <span>Activar TAG</span>
          </a>
        </div>
      </div>

      {{-- Stats --}}
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fa-solid fa-paw"></i>
          </div>
          <div class="stat-content">
            <div class="stat-value">{{ $pets->total() }}</div>
            <div class="stat-label">Total</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
          </div>
          <div class="stat-content">
            <div class="stat-value">{{ $pets->getCollection()->where('is_lost', true)->count() }}</div>
            <div class="stat-label">Perdidas</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon">
            <i class="fa-solid fa-medal"></i>
          </div>
          <div class="stat-content">
            <div class="stat-value">{{ $pets->getCollection()->filter(fn($p)=>optional($p->reward)->active)->count() }}</div>
            <div class="stat-label">Con recompensa</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Filters --}}
    <div class="filters-section">
      <div class="filters-bar" id="filterBar">
        <button class="filter-chip" data-filter="all" aria-pressed="true">
          <span class="filter-dot"></span>
          Todos
        </button>
        <button class="filter-chip" data-filter="lost">
          <span class="filter-dot"></span>
          Perdidas
        </button>
        <button class="filter-chip" data-filter="reward">
          <span class="filter-dot"></span>
          Con recompensa
        </button>
        <button class="filter-chip" data-filter="sex:male">
          <span class="filter-dot"></span>
          Macho
        </button>
        <button class="filter-chip" data-filter="sex:female">
          <span class="filter-dot"></span>
          Hembra
        </button>
      </div>

      {{-- Search (solo admin) --}}
      @if(auth()->user()->is_admin)
        <div class="search-box">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input 
            type="text" 
            id="petSearch" 
            class="search-input" 
            placeholder="Buscar por nombre, raza, zona o dueño..."
          >
        </div>
      @endif
    </div>

    {{-- Grid de mascotas --}}
    @if($pets->isEmpty())
      <div class="empty-state">
        <div class="empty-icon">
          <i class="fa-solid fa-paw"></i>
        </div>
        <div class="empty-title">No hay mascotas registradas</div>
        <div class="empty-text">Comienza registrando tu primera mascota</div>
        <a href="{{ route('portal.activate-tag') }}" class="btn-header btn-primary-header">
          <i class="fa-solid fa-tag"></i>
          <span>Activar mi primer TAG</span>
        </a>
      </div>
    @else
      <div id="petGrid" class="pets-grid">
        @foreach($pets as $pet)
          @php
            $hasReward = optional($pet->reward)->active ? '1' : '0';
            $sex = $pet->sex ?? 'unknown';
          @endphp
          <div
            class="pet-card"
            data-name="{{ Str::lower($pet->name) }}"
            data-breed="{{ Str::lower($pet->breed ?? '') }}"
            data-zone="{{ Str::lower($pet->zone ?? '') }}"
            data-owner="{{ Str::lower(optional($pet->user)->name.' '.optional($pet->user)->email) }}"
            data-lost="{{ $pet->is_lost ? '1' : '0' }}"
            data-reward="{{ $hasReward }}"
            data-sex="{{ $sex }}"
          >
            {{-- Thumbnail --}}
            <div class="pet-thumbnail">
              <img src="{{ $pet->main_photo_url }}" alt="{{ $pet->name }}" class="pet-image">
              
              <div class="pet-badges">
                @if($pet->is_lost)
                  <span class="badge-item badge-lost">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Perdida
                  </span>
                @else
                  <span></span>
                @endif
                
                @if(optional($pet->reward)->active)
                  <span class="badge-item badge-reward">
                    <i class="fa-solid fa-medal"></i>
                    @if(optional($pet->reward)->amount)
                      ₡{{ number_format((float)$pet->reward->amount, 0) }}
                    @else
                      Recompensa
                    @endif
                  </span>
                @endif
              </div>
            </div>

            {{-- Content --}}
            <div class="pet-content">
              <div class="pet-name-row">
                @if($sex === 'male')
                  <i class="fa-solid fa-mars pet-gender-icon"></i>
                @elseif($sex === 'female')
                  <i class="fa-solid fa-venus pet-gender-icon"></i>
                @else
                  <i class="fa-solid fa-circle-question pet-gender-icon" style="color: var(--text-muted);"></i>
                @endif
                <h3 class="pet-name">{{ $pet->name }}</h3>
              </div>

              <div class="pet-info">
                @if($pet->breed)
                  <span class="info-tag">
                    <i class="fa-solid fa-dog"></i>
                    {{ $pet->breed }}
                  </span>
                @endif
                
                @if($pet->zone)
                  <span class="info-tag">
                    <i class="fa-solid fa-location-dot"></i>
                    {{ $pet->zone }}
                  </span>
                @endif
                
                @if(auth()->user()->is_admin && $pet->user)
                  <span class="info-tag">
                    <i class="fa-solid fa-user"></i>
                    {{ $pet->user->name }}
                  </span>
                @endif
              </div>

              <div class="pet-actions">
                <a href="{{ route('portal.pets.show', $pet) }}" class="btn-pet btn-pet-view">
                  <i class="fa-solid fa-eye"></i>
                  Ver
                </a>
                <a href="{{ route('portal.pets.edit', $pet) }}" class="btn-pet btn-pet-edit">
                  <i class="fa-solid fa-pen"></i>
                  Editar
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Empty filtered state --}}
      <div id="emptyFiltered" class="empty-state empty-filtered">
        <div class="empty-icon">
          <i class="fa-solid fa-search"></i>
        </div>
        <div class="empty-title">No se encontraron resultados</div>
        <div class="empty-text">Intenta ajustar los filtros o términos de búsqueda</div>
      </div>

      {{-- Pagination --}}
      <div class="pagination-wrapper">
        {{ $pets->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
      </div>
    @endif

  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const grid = document.getElementById('petGrid');
  if (!grid) return;

  const items = Array.from(grid.querySelectorAll('.pet-card'));
  const emptyFiltered = document.getElementById('emptyFiltered');

  // Estado de filtros
  const state = {
    text: '',
    lost: false,
    reward: false,
    sex: null,
  };

  // Normalizar texto
  const normalize = (str) => {
    return (str || '').toString().toLowerCase()
      .normalize('NFD')
      .replace(/[\u0300-\u036f]/g, '');
  };

  // Search input (solo admin)
  const searchInput = document.getElementById('petSearch');
  if (searchInput) {
    let searchTimeout;
    searchInput.addEventListener('input', (e) => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        state.text = normalize(e.target.value);
        applyFilters();
      }, 150);
    });
  }

  // Filter chips
  const filterBar = document.getElementById('filterBar');
  filterBar.addEventListener('click', (e) => {
    const chip = e.target.closest('.filter-chip');
    if (!chip) return;

    const filter = chip.dataset.filter;
    const isPressed = chip.getAttribute('aria-pressed') === 'true';

    // Reset con "Todos"
    if (filter === 'all') {
      state.lost = false;
      state.reward = false;
      state.sex = null;
      
      filterBar.querySelectorAll('.filter-chip').forEach(c => {
        c.setAttribute('aria-pressed', 'false');
      });
      chip.setAttribute('aria-pressed', 'true');
      applyFilters();
      return;
    }

    // Desactivar "Todos"
    filterBar.querySelector('[data-filter="all"]').setAttribute('aria-pressed', 'false');

    // Toggle filtros
    if (filter === 'lost') {
      chip.setAttribute('aria-pressed', (!isPressed).toString());
      state.lost = !isPressed;
    } else if (filter === 'reward') {
      chip.setAttribute('aria-pressed', (!isPressed).toString());
      state.reward = !isPressed;
    } else if (filter.startsWith('sex:')) {
      const sexValue = filter.split(':')[1];
      const sexChips = filterBar.querySelectorAll('[data-filter^="sex:"]');
      
      if (state.sex === sexValue) {
        state.sex = null;
        chip.setAttribute('aria-pressed', 'false');
      } else {
        state.sex = sexValue;
        sexChips.forEach(c => c.setAttribute('aria-pressed', 'false'));
        chip.setAttribute('aria-pressed', 'true');
      }
    }

    applyFilters();
  });

  // Aplicar filtros
  function applyFilters() {
    let visibleCount = 0;

    items.forEach(card => {
      const name = card.dataset.name || '';
      const breed = card.dataset.breed || '';
      const zone = card.dataset.zone || '';
      const owner = card.dataset.owner || '';
      
      const matchesText = !state.text || 
        [name, breed, zone, owner].some(v => normalize(v).includes(state.text));

      const matchesLost = !state.lost || card.dataset.lost === '1';
      const matchesReward = !state.reward || card.dataset.reward === '1';
      const matchesSex = !state.sex || card.dataset.sex === state.sex;

      const show = matchesText && matchesLost && matchesReward && matchesSex;
      
      card.style.display = show ? '' : 'none';
      if (show) visibleCount++;
    });

    // Mostrar/ocultar mensaje de vacío
    if (emptyFiltered) {
      emptyFiltered.style.display = visibleCount === 0 ? 'block' : 'none';
    }
  }

  // Aplicar filtros iniciales
  applyFilters();

  // Lazy loading de imágenes
  const imageObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        const thumbnail = img.closest('.pet-thumbnail');
        
        if (thumbnail) {
          thumbnail.classList.add('loading');
        }

        img.addEventListener('load', () => {
          if (thumbnail) {
            thumbnail.classList.remove('loading');
          }
        });

        imageObserver.unobserve(img);
      }
    });
  }, { rootMargin: '50px' });

  document.querySelectorAll('.pet-image').forEach(img => {
    imageObserver.observe(img);
  });
});
</script>
@endpush
