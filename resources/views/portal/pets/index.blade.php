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


  @keyframes float {
    0%, 100% {
      transform: translateY(0);
    }
    50% {
      transform: translateY(-10px);
    }
  }

  @keyframes pulse-soft {
    0%, 100% {
      opacity: 1;
    }
    50% {
      opacity: 0.7;
    }
  }

  /* ===== Background mejorado ===== */
  .page-wrap {
    background: linear-gradient(180deg, #f0f4ff 0%, #ffffff 200px);
    position: relative;
    overflow: hidden;
  }

  .page-wrap::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 8s ease-in-out infinite;
  }

  .page-wrap::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(118, 75, 162, 0.06) 0%, transparent 70%);
    border-radius: 50%;
    animation: float 10s ease-in-out infinite;
    animation-delay: 1s;
  }

  .container {
    position: relative;
    z-index: 1;
  }

  /* ===== Header con animación ===== */
  .head-actions .btn {
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
  }

  .head-actions .btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .head-actions .btn:hover::before {
    width: 300px;
    height: 300px;
  }

  .head-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
  }

  /* ===== Stats mejoradas ===== */
  .stat {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid #e8ecf4;
    border-radius: 16px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 4px 20px rgba(31, 41, 55, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: scaleIn 0.5s ease-out;
    position: relative;
    overflow: hidden;
  }

  .stat:nth-child(2) {
    animation-delay: 0.1s;
  }

  .stat:nth-child(3) {
    animation-delay: 0.2s;
  }

  .stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
    transition: left 0.5s;
  }

  .stat:hover::before {
    left: 100%;
  }

  .stat:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 35px rgba(31, 41, 55, 0.15);
    border-color: #667eea;
  }

  .stat i {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    font-size: 1.1rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
  }

  .stat:hover i {
    transform: scale(1.1) rotate(5deg);
  }

  .stat .n {
    font-weight: 900;
    font-size: 20px;
    line-height: 1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .stat .l {
    color: #6b7280;
    margin-top: -2px;
    font-weight: 600;
  }

  /* ===== Filter bar mejorada ===== */
  .filterbar {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
    animation: slideInLeft 0.6s ease-out;
  }

  .chip-toggle {
    border: 2px solid #e5e7eb;
    background: #ffffff;
    color: #111827;
    padding: 0.5rem 1rem;
    border-radius: 999px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .chip-toggle::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
    transform: translate(-50%, -50%);
    transition: width 0.4s, height 0.4s;
  }

  .chip-toggle:hover::before {
    width: 200px;
    height: 200px;
  }

  .chip-toggle:hover {
    transform: translateY(-2px);
    border-color: #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
  }

  .chip-toggle[aria-pressed="true"] {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
    transform: scale(1.05);
  }

  .chip-toggle .dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 999px;
    margin-right: 0.5rem;
    background: #9ca3af;
    transition: all 0.3s ease;
  }

  .chip-toggle[aria-pressed="true"] .dot {
    background: #fff;
    animation: pulse-soft 2s infinite;
  }

  /* ===== Grid & card mejoradas ===== */
  .pet-item.reveal {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
  }

  .pet-item.reveal.in {
    opacity: 1;
    transform: none;
  }

  .pet-card {
    background: #ffffff;
    border: 1px solid #e8ecf4;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(31, 41, 55, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
  }

  .pet-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
  }

  .pet-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 50px rgba(31, 41, 55, 0.15);
    border-color: #667eea;
  }

  .pet-card:hover::after {
    opacity: 1;
  }

  .pet-thumb {
    position: relative;
    background: linear-gradient(135deg, #f8fafc 0%, #f0f4ff 100%);
    overflow: hidden;
  }

  .pet-thumb .ratio {
    --bs-aspect-ratio: 75%;
  }

  .pet-thumb img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .pet-card:hover .pet-thumb img {
    transform: scale(1.08);
  }

  /* ribbons mejoradas */
  .ribbon {
    position: absolute;
    left: 12px;
    top: 12px;
    border-radius: 999px;
    padding: 0.4rem 0.8rem;
    font-weight: 800;
    font-size: 0.75rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    animation: scaleIn 0.5s ease-out;
    transition: all 0.3s ease;
  }

  .ribbon:hover {
    transform: scale(1.1);
  }

  .r-lost {
    background: rgba(254, 226, 226, 0.95);
    color: #991b1b;
    border: 1px solid #fecaca;
  }

  .r-rew {
    right: 12px;
    left: auto;
    background: rgba(220, 252, 231, 0.95);
    color: #065f46;
    border: 1px solid #bbf7d0;
  }

  .pet-body {
    padding: 16px 18px;
  }

  .pet-name {
    font-weight: 900;
    font-size: 1.1rem;
    margin: 2px 0 8px;
    transition: color 0.3s ease;
  }

  .pet-card:hover .pet-name {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .pet-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
  }

  .pet-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #f5f7fb 0%, #e8ecf4 100%);
    border: 1px solid #e0e5ed;
    border-radius: 999px;
    padding: 0.35rem 0.65rem;
    font-weight: 600;
    color: #263143;
    font-size: 0.85rem;
    transition: all 0.3s ease;
  }

  .pet-chip:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  }

  .card-actions {
    display: flex;
    gap: 0.6rem;
    margin-top: auto;
  }

  .btn-soft {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    border: 1px solid #d1d5db;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .btn-soft:hover {
    background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  }

  .btn-outline-primary {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  /* search box mejorada */
  .searchbox {
    background: #ffffff;
    border: 2px solid #e8ecf4;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(31, 41, 55, 0.08);
    padding: 6px 12px;
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease-out;
  }

  .searchbox:focus-within {
    border-color: #667eea;
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.15);
    transform: translateY(-2px);
  }

  .searchbox .form-control {
    border: 0;
    height: 48px;
    font-size: 0.95rem;
  }

  .searchbox .input-group-text {
    background: transparent;
    color: #667eea;
    font-size: 1.1rem;
  }

  /* empty filtered mejorado */
  .empty-filter {
    border: 2px dashed #d1d5db;
    border-radius: 16px;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 32px;
    text-align: center;
    color: #6b7280;
    animation: fadeInUp 0.5s ease-out;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  }

  /* Card glass mejorado */
  .card-glass {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(31, 41, 55, 0.1);
    animation: scaleIn 0.6s ease-out;
  }

  /* Mejoras en iconos de género */
  .pet-name i {
    transition: all 0.3s ease;
  }

  .pet-card:hover .pet-name i {
    transform: scale(1.2) rotate(10deg);
  }

</style>
@endpush

@section('content')
<div class="page-wrap py-4">
  <div class="container">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
      <div>
        <h1 class="h4 fw-bold mb-0">
          {{ auth()->user()->is_admin ? 'Mascotas (admin)' : 'Mis Mascotas' }}
        </h1>
        <div class="text-muted">Gestiona y edita la información de tus mascotas.</div>
      </div>
      <div class="head-actions d-flex gap-2">
        @if(auth()->user()->is_admin)
          <a href="{{ route('portal.pets.create') }}" class="btn btn-grad">
            <i class="fa-solid fa-plus me-1"></i> Registrar (admin)
          </a>
        @endif
        <a href="{{ route('portal.activate-tag') }}" class="btn btn-primary">
          <i class="fa-solid fa-tag me-1"></i> Activar TAG
        </a>
      </div>
    </div>

    {{-- Stats --}}
    <div class="d-flex gap-2 flex-wrap mb-3">
      <div class="stat">
        <i class="fa-solid fa-paw"></i>
        <div>
          <div class="n">{{ $pets->total() }}</div>
          <div class="l">Total</div>
        </div>
      </div>
      <div class="stat">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
          <div class="n">{{ $pets->getCollection()->where('is_lost', true)->count() }}</div>
          <div class="l">Perdidas</div>
        </div>
      </div>
      <div class="stat">
        <i class="fa-solid fa-medal"></i>
        <div>
          <div class="n">{{ $pets->getCollection()->filter(fn($p)=>optional($p->reward)->active)->count() }}</div>
          <div class="l">Con recompensa</div>
        </div>
      </div>
    </div>

    {{-- Filtros (visibles para todos) --}}
    <div class="filterbar mb-3" id="filterBar">
      <button class="chip-toggle" data-filter="all" aria-pressed="true"><span class="dot"></span>Todos</button>
      <button class="chip-toggle" data-filter="lost"><span class="dot"></span>Perdidas</button>
      <button class="chip-toggle" data-filter="reward"><span class="dot"></span>Con recompensa</button>
      <button class="chip-toggle" data-filter="sex:male"><span class="dot"></span>Macho</button>
      <button class="chip-toggle" data-filter="sex:female"><span class="dot"></span>Hembra</button>
    </div>

    {{-- Buscador (solo admin, como antes) --}}
    @if(auth()->user()->is_admin)
      <div class="admin-search mb-3">
        <div class="searchbox">
          <div class="input-group">
            <span class="input-group-text bg-white border-0">
              <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input id="petSearch" type="text" class="form-control border-0" placeholder="Buscar por nombre, raza, zona o dueño…">
          </div>
        </div>
      </div>
    @endif

    {{-- Grid --}}
    @if($pets->isEmpty())
      <div class="card-glass p-4 text-center text-muted">
        Aún no tienes mascotas registradas.
        <div class="mt-2">
          <a href="{{ route('portal.activate-tag') }}" class="btn btn-primary">Registrar mi primera mascota</a>
        </div>
      </div>
    @else
      <div id="petGrid" class="row g-3">
        @foreach($pets as $pet)
          @php
            $hasReward = optional($pet->reward)->active ? '1' : '0';
            $sex       = $pet->sex ?? 'unknown';
          @endphp
          <div
            class="col-12 col-sm-6 col-lg-4 col-xl-3 pet-item reveal"
            data-name="{{ Str::lower($pet->name) }}"
            data-breed="{{ Str::lower($pet->breed) }}"
            data-zone="{{ Str::lower($pet->zone) }}"
            data-owner="{{ Str::lower(optional($pet->user)->name.' '.optional($pet->user)->email) }}"
            data-lost="{{ $pet->is_lost ? '1' : '0' }}"
            data-reward="{{ $hasReward }}"
            data-sex="{{ $sex }}"
          >
            <div class="pet-card h-100 d-flex flex-column">
              <div class="pet-thumb">
                <div class="ratio">
                  <img src="{{ $pet->main_photo_url }}" alt="{{ $pet->name }}">
                </div>
                @if($pet->is_lost)
                  <span class="ribbon r-lost"><i class="fa-solid fa-triangle-exclamation me-1"></i>Perdida</span>
                @endif
                @if(optional($pet->reward)->active)
                  <span class="ribbon r-rew"><i class="fa-solid fa-medal me-1"></i>Recompensa</span>
                @endif
              </div>

              <div class="pet-body flex-grow-1 d-flex flex-column">
                <h3 class="pet-name">
                  @if($sex === 'male')
                    <i class="fa-solid fa-mars me-1 text-primary"></i>
                  @elseif($sex === 'female')
                    <i class="fa-solid fa-venus me-1 text-primary"></i>
                  @else
                    <i class="fa-solid fa-circle-question me-1 text-secondary"></i>
                  @endif
                  {{ $pet->name }}
                </h3>

                <div class="pet-meta">
                  @if($pet->breed)
                    <span class="pet-chip"><i class="fa-solid fa-dog"></i>{{ $pet->breed }}</span>
                  @endif
                  @if($pet->zone)
                    <span class="pet-chip"><i class="fa-solid fa-location-dot"></i>{{ $pet->zone }}</span>
                  @endif
                  @if(auth()->user()->is_admin)
                    <span class="pet-chip"><i class="fa-solid fa-user"></i>{{ optional($pet->user)->name ?: '—' }}</span>
                  @endif
                </div>

                <div class="card-actions mt-3">
                  <a href="{{ route('portal.pets.show',$pet) }}" class="btn btn-soft w-100">
                    <i class="fa-solid fa-eye me-1"></i> Ver
                  </a>
                  <a href="{{ route('portal.pets.edit',$pet) }}" class="btn btn-outline-primary w-100">
                    <i class="fa-solid fa-pen me-1"></i> Editar
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div id="emptyFiltered" class="empty-filter mt-3 d-none">
        No hay resultados para los filtros/búsqueda aplicados.
      </div>

      <div class="mt-3">
        {{ $pets->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
      </div>
    @endif

  </div>
</div>
@endSection

@push('scripts')
<script>
  // Revelado al hacer scroll
  (function() {
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('in');
          io.unobserve(e.target);
        }
      });
    }, { threshold: .12 });
    els.forEach(el => io.observe(el));
  })();

  // Búsqueda (admin) + filtros (todos)
  (function() {
    const grid  = document.getElementById('petGrid');
    if (!grid) return;

    const items = Array.from(grid.querySelectorAll('.pet-item'));
    const emptyFiltered = document.getElementById('emptyFiltered');

    const state = { // filtros activos
      text: '',
      lost: false,
      reward: false,
      sex: null, // 'male' | 'female' | null
    };

    const norm = s => (s || '').toString().toLowerCase()
      .normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // Search (solo admin)
    const input = document.getElementById('petSearch');
    if (input) {
      let t;
      input.addEventListener('input', e => {
        clearTimeout(t);
        t = setTimeout(() => {
          state.text = norm(e.target.value);
          applyFilters();
        }, 120);
      });
    }

    // Filtros
    const bar = document.getElementById('filterBar');
    bar.addEventListener('click', e => {
      const btn = e.target.closest('.chip-toggle');
      if (!btn) return;

      const filter = btn.dataset.filter;
      // toggle pressed
      const pressed = btn.getAttribute('aria-pressed') === 'true';
      // "Todos" resetea
      if (filter === 'all') {
        state.lost = false; state.reward = false; state.sex = null;
        Array.from(bar.querySelectorAll('.chip-toggle')).forEach(b => b.setAttribute('aria-pressed', 'false'));
        btn.setAttribute('aria-pressed', 'true');
        applyFilters();
        return;
      }
      // desactivar "Todos"
      bar.querySelector('[data-filter="all"]').setAttribute('aria-pressed','false');

      if (filter === 'lost') {
        btn.setAttribute('aria-pressed', (!pressed).toString());
        state.lost = !pressed;
      }
      else if (filter === 'reward') {
        btn.setAttribute('aria-pressed', (!pressed).toString());
        state.reward = !pressed;
      }
      else if (filter && filter.startsWith('sex:')) {
        const val = filter.split(':')[1];
        // alterna sexo exclusivo
        const sexBtns = bar.querySelectorAll('[data-filter^="sex:"]');
        if (state.sex === val) {
          state.sex = null;
          btn.setAttribute('aria-pressed','false');
        } else {
          state.sex = val;
          sexBtns.forEach(b => b.setAttribute('aria-pressed','false'));
          btn.setAttribute('aria-pressed','true');
        }
      }
      applyFilters();
    });

    function applyFilters() {
      let visibleCount = 0;
      items.forEach(it => {
        const matchesText = !state.text || [it.dataset.name, it.dataset.breed, it.dataset.zone, it.dataset.owner]
          .some(v => norm(v).includes(state.text));

        const matchesLost   = !state.lost   || it.dataset.lost === '1';
        const matchesReward = !state.reward || it.dataset.reward === '1';
        const matchesSex    = !state.sex    || it.dataset.sex === state.sex;

        const show = matchesText && matchesLost && matchesReward && matchesSex;
        it.style.display = show ? '' : 'none';
        if (show) visibleCount++;
      });

      emptyFiltered.classList.toggle('d-none', visibleCount !== 0);
    }

    // primera pasada
    applyFilters();
  })();
</script>
@endpush