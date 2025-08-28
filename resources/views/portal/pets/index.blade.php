@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
@endpush

@section('content')
<div class="page-wrap py-4">
  <div class="container">

    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <h1 class="h4 fw-bold mb-0">Mis Mascotas</h1>
        <div class="text-muted">Gestiona y edita la información de tus mascotas.</div>
      </div>
      <div class="d-flex gap-2">
        @if(auth()->user()->is_admin)
          <a href="{{ route('portal.pets.create') }}" class="btn btn-grad"><i class="fa-solid fa-plus me-1"></i> Registrar (admin)</a>
        @endif
        <a href="{{ route('portal.activate-tag') }}" class="btn btn-primary"><i class="fa-solid fa-tag me-1"></i> Activar TAG</a>
      </div>
    </div>

    @if(auth()->user()->is_admin)
      <div class="admin-search mb-3">
        <div class="searchbox">
          <div class="input-group">
            <span class="input-group-text bg-white border-0"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input id="petSearch" type="text" class="form-control border-0" placeholder="Buscar por nombre, raza, zona o dueño…">
          </div>
        </div>
      </div>
    @endif

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
          <div class="col-12 col-sm-6 col-lg-4 col-xl-3 pet-item reveal"
               data-name="{{ Str::lower($pet->name) }}"
               data-breed="{{ Str::lower($pet->breed) }}"
               data-zone="{{ Str::lower($pet->zone) }}"
               data-owner="{{ Str::lower(optional($pet->user)->name.' '.optional($pet->user)->email) }}">
            <div class="pet-card h-100 d-flex flex-column">
              <div class="pet-thumb">
                @if($pet->photo)
                  <img src="{{ asset('storage/'.$pet->photo) }}" alt="{{ $pet->name }}">
                @else
                  <img src="https://images.unsplash.com/photo-1558944351-cbbdcc8c4fba?q=80&w=1200&auto=format&fit=crop" alt="{{ $pet->name }}">
                @endif
              </div>
              <div class="pet-body flex-grow-1 d-flex flex-column">
                <h3 class="pet-name">{{ $pet->name }}</h3>
                <div class="pet-meta">
                  @if($pet->breed)<span class="pet-chip"><i class="fa-solid fa-dog me-1"></i>{{ $pet->breed }}</span>@endif
                  @if($pet->zone)<span class="pet-chip"><i class="fa-solid fa-location-dot me-1"></i>{{ $pet->zone }}</span>@endif
                  @if($pet->is_lost)<span class="badge bg-danger">Perdida</span>@endif
                  @if(optional($pet->reward)->active)<span class="badge bg-success">Recompensa</span>@endif
                </div>

                <div class="mt-3 d-flex gap-2">
                  <a href="{{ route('portal.pets.show',$pet) }}" class="btn btn-soft w-100"><i class="fa-solid fa-eye me-1"></i> Ver</a>
                  <a href="{{ route('portal.pets.edit',$pet) }}" class="btn btn-outline-primary w-100"><i class="fa-solid fa-pen me-1"></i> Editar</a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="mt-3">
        {{ $pets->links() }}
      </div>
    @endif

  </div>
</div>

@push('scripts')
<script>
  (function(){
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver(entries=>{
      entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target);} });
    },{threshold:.12});
    els.forEach(el=>io.observe(el));
  })();

  (function(){
    const input = document.getElementById('petSearch'); if(!input) return;
    const items = document.querySelectorAll('#petGrid .pet-item');
    const norm = s => (s||'').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
    input.addEventListener('input', e=>{
      const t = norm(e.target.value);
      items.forEach(it=>{
        const match = [it.dataset.name, it.dataset.breed, it.dataset.zone, it.dataset.owner]
          .some(v => norm(v).includes(t));
        it.style.display = match ? '' : 'none';
      });
    });
  })();
</script>
@endpush
@endsection
