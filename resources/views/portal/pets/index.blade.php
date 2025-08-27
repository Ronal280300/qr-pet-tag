@extends('layouts.app')
@section('title','Mis Mascotas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Mis Mascotas</h3>
  <a class="btn btn-primary" href="{{ route('portal.pets.create') }}"><i class="fa fa-plus me-2"></i>Nueva</a>
</div>

@if($pets->count() === 0)
  <div class="alert alert-info">Aún no tienes mascotas registradas.</div>
@else
  <div class="list-group">
    @foreach($pets as $pet)
      <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
         href="{{ route('portal.pets.show', $pet) }}">
        <span>{{ $pet->name }} @if($pet->is_lost)<span class="badge text-bg-danger ms-2">Perdida</span>@endif</span>
        <i class="fa fa-chevron-right"></i>
      </a>
    @endforeach
  </div>

  <div class="mt-3">
    {{ $pets->links() }}
  </div>
@endif
@endsection