@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
<style>
  .page-wrap{background:linear-gradient(180deg,#f8fafc 0,#fff 160px)}
  .head-actions .btn{border-radius:12px}

  /* ===== Stats ===== */
  .stat{background:#fff;border:1px solid #eef1f5;border-radius:14px;padding:10px 14px;display:flex;align-items:center;gap:10px;box-shadow:0 8px 28px rgba(31,41,55,.06)}
  .stat i{width:34px;height:34px;border-radius:10px;display:grid;place-items:center;background:#f3f4f6;color:#111827}
  .stat .n{font-weight:900;font-size:18px;line-height:1}
  .stat .l{color:#6b7280;margin-top:-2px}

  /* ===== Filter bar ===== */
  .filterbar{display:flex;gap:.5rem;flex-wrap:wrap}
  .chip-toggle{border:1px solid #e5e7eb;background:#fff;color:#111827;padding:.45rem .75rem;border-radius:999px;cursor:pointer;font-weight:600}
  .chip-toggle[aria-pressed="true"]{background:#115dfc;color:#fff;border-color:#115dfc}
  .chip-toggle .dot{display:inline-block;width:8px;height:8px;border-radius:999px;margin-right:.4rem;background:#9ca3af}
  .chip-toggle[aria-pressed="true"] .dot{background:#fff}

  /* ===== Grid & card ===== */
  .pet-item.reveal{opacity:0;transform:translateY(14px);transition:.35s ease}
  .pet-item.reveal.in{opacity:1;transform:none}

  .pet-card{background:#fff;border:1px solid #eef1f5;border-radius:16px;overflow:hidden;box-shadow:0 14px 38px rgba(31,41,55,.06);transition:transform .18s ease, box-shadow .18s ease}
  .pet-card:hover{transform:translateY(-2px);box-shadow:0 18px 48px rgba(31,41,55,.09)}
  .pet-thumb{position:relative;background:#f8fafc}
  .pet-thumb .ratio{--bs-aspect-ratio:75%} /* 4:3 */
  .pet-thumb img{width:100%;height:100%;object-fit:contain;background:#f8fafc}

  /* ribbons */
  .ribbon{position:absolute;left:12px;top:12px;border-radius:999px;padding:.28rem .6rem;font-weight:800;font-size:.78rem}
  .r-lost{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}
  .r-rew{right:12px;left:auto;background:#dcfce7;color:#065f46;border:1px solid #bbf7d0}

  .pet-body{padding:12px 14px}
  .pet-name{font-weight:900;font-size:1.05rem;margin:2px 0 6px}
  .pet-meta{display:flex;flex-wrap:wrap;gap:.35rem}
  .pet-chip{display:inline-flex;align-items:center;gap:.35rem;background:#f5f7fb;border:1px solid #eef1f5;border-radius:999px;padding:.28rem .55rem;font-weight:600;color:#263143}
  .card-actions{display:flex;gap:.5rem;margin-top:auto}
  .btn-soft{background:#f3f4f6;border:1px solid #e5e7eb}
  .btn-soft:hover{background:#e5e7eb}

  /* search box (admin) */
  .searchbox{background:#fff;border:1px solid #eef1f5;border-radius:14px;box-shadow:0 10px 30px rgba(31,41,55,.06);padding:6px 10px}
  .searchbox .form-control{border:0;height:46px}

  /* empty filtered */
  .empty-filter{border:1px dashed #d1d5db;border-radius:14px;background:#fff;padding:24px;text-align:center;color:#6b7280}
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
