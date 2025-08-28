
@php
    // Mapear tipos a estilos Bootstrap
    $map = [
        'success' => 'success',
        'status'  => 'success',   // por compatibilidad
        'error'   => 'danger',
        'danger'  => 'danger',
        'warning' => 'warning',
        'info'    => 'info',
    ];
@endphp

@foreach (['success','status','error','danger','warning','info'] as $key)
    @if (session()->has($key))
        <div class="alert alert-{{ $map[$key] ?? 'info' }} alert-dismissible fade show shadow-sm" role="alert">
            {!! session($key) !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <strong>Corrige los siguientes errores:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
@endif
