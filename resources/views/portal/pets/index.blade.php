@extends('layouts.app')
@section('title','Mis Mascotas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Mis Mascotas</h3>
  <a class="btn btn-primary" href="{{ route('portal.pets.create') }}"><i class="fa fa-plus me-2"></i>Nueva</a>
</div>

@if($pets->count() === 0)
  <div class="card">
    <div class="card-body text-center">
      <p class="mb-2">Aún no tienes mascotas registradas.</p>
      <a class="btn btn-primary" href="{{ route('portal.pets.create') }}">Registrar mi primera mascota</a>
    </div>
  </div>
@else
  <div class="row g-3">
    @foreach($pets as $pet)
      <div class="col-md-6">
        <a class="text-decoration-none text-reset" href="{{ route('portal.pets.show', $pet) }}">
          <div class="card h-100">
            <div class="card-body d-flex gap-3">
              @if($pet->photo)
                <img src="{{ asset('storage/'.$pet->photo) }}" class="rounded" style="width:88px;height:88px;object-fit:cover" alt="">
              @else
                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:88px;height:88px">🐾</div>
              @endif
              <div class="flex-grow-1">
                <div class="d-flex align-items-center justify-content-between">
                  <h5 class="card-title mb-1">{{ $pet->name }}</h5>
                  @if($pet->is_lost)
                    <span class="badge text-bg-danger">Perdida</span>
                  @else
                    <span class="badge text-bg-success">Normal</span>
                  @endif
                </div>
                <div class="text-muted">
                  {{ $pet->breed ?: 'Sin raza' }} · {{ $pet->zone ?: 'Sin zona' }}
                </div>
              </div>
              <i class="fa fa-chevron-right text-muted"></i>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  <div class="mt-3">
    {{ $pets->links() }}
  </div>
@endif
@endsection