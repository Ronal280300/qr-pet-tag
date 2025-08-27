@extends('layouts.app')

@section('title', 'Mi Portal')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-0">Bienvenido, {{ Auth::user()->name }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('portal.pets.index') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-dog me-1"></i> Mis Mascotas
        </a>
        <a href="{{ route('portal.pets.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Nueva Mascota
        </a>
    </div>
</div>

<div class="alert alert-info">
    <i class="fa-solid fa-circle-info me-2"></i>
    ¡Listo! Tu cuenta está activa. En el <strong>Punto 3</strong> ya puedes crear, editar,
    generar QR y activar recompensa para tus mascotas desde el menú.
</div>
@endsection