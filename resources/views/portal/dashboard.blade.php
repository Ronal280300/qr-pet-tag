@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/portal.css') }}">
@endpush

@section('content')
<div class="page-wrap py-4">
  <div class="container">

    @php
      $u = auth()->user();
      $uid = $u->id;
      $petCount   = \App\Models\Pet::where('user_id',$uid)->count();
      $lostCount  = \App\Models\Pet::where('user_id',$uid)->where('is_lost',1)->count();
      $petIds     = \App\Models\Pet::where('user_id',$uid)->pluck('id');
      $rewardsOn  = \App\Models\Reward::whereIn('pet_id',$petIds)->where('active',1)->count();
    @endphp

    <div class="mb-3">
      <h1 class="h3 fw-bold">Bienvenido, {{ $u->name }}</h1>
      <div class="text-muted">Gestiona tus mascotas, genera/descarga su QR y activa tags.</div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-12 col-md-4">
        <div class="kpi reveal">
          <div class="value">{{ $petCount }}</div>
          <div class="hint">Mascotas registradas</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="kpi reveal">
          <div class="value">{{ $lostCount }}</div>
          <div class="hint">Marcadas como perdidas/robadas</div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="kpi reveal">
          <div class="value">{{ $rewardsOn }}</div>
          <div class="hint">Recompensas activas</div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-12 col-lg-6">
        <div class="card-glass p-3 reveal">
          <h5 class="fw-bold mb-2">Acciones rápidas</h5>
          <div class="d-flex flex-wrap gap-2">
            @if($u->is_admin)
              <a href="{{ route('portal.pets.create') }}" class="btn btn-grad">
                <i class="fa-solid fa-plus me-1"></i> Registrar mascota (admin)
              </a>
            @endif

            <a href="{{ route('portal.activate-tag') }}" class="btn btn-primary">
              <i class="fa-solid fa-tag me-1"></i> Activar TAG
            </a>

            <a href="{{ route('portal.pets.index') }}" class="btn btn-soft">
              <i class="fa-solid fa-paw me-1"></i> Ver mis mascotas
            </a>
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-6">
        <div class="card-glass p-3 reveal">
          <h5 class="fw-bold mb-2">Consejos</h5>
          <ul class="mb-0 text-muted">
            <li>Genera el QR de cada mascota y <strong>descárgalo</strong> para grabarlo en el TAG.</li>
            <li>Si tu mascota se pierde, actívala como <strong>perdida/robada</strong> para que el mensaje sea más visible.</li>
            <li>Puedes habilitar una <strong>recompensa</strong> temporal para incentivar reportes.</li>
          </ul>
        </div>
      </div>
    </div>

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
</script>
@endpush
@endsection
