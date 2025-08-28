@extends('layouts.app')

@section('title', 'Admin | Inventario de TAGs')

@section('content')
<div class="container my-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h4 fw-bold mb-0">Inventario de TAGs (QR)</h1>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary"
         href="{{ route('portal.admin.tags.export', request()->query()) }}">
        <i class="fa-solid fa-file-csv me-1"></i> Exportar CSV
      </a>
    </div>
  </div>

  {{-- KPIs --}}
  <div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon bg-primary-subtle text-primary"><i class="fa-solid fa-qrcode"></i></div>
        <div class="kpi-body">
          <div class="kpi-title">Total</div>
          <div class="kpi-value">{{ number_format($stats['total']) }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon bg-success-subtle text-success"><i class="fa-solid fa-link"></i></div>
        <div class="kpi-body">
          <div class="kpi-title">Asignados</div>
          <div class="kpi-value">{{ number_format($stats['assigned']) }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon bg-warning-subtle text-warning"><i class="fa-solid fa-unlink"></i></div>
        <div class="kpi-body">
          <div class="kpi-title">Sin asignar</div>
          <div class="kpi-value">{{ number_format($stats['unassigned']) }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card">
        <div class="kpi-icon bg-info-subtle text-info"><i class="fa-solid fa-image"></i></div>
        <div class="kpi-body">
          <div class="kpi-title">Con imagen</div>
          <div class="kpi-value">{{ number_format($stats['with_image']) }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Filtros --}}
  <form class="card card-elevated mb-3 p-3">
    <div class="row g-2 align-items-end">
      <div class="col-12 col-md-6">
        <label class="form-label">Buscar</label>
        <input type="text" name="q" value="{{ $q }}" class="form-control"
               placeholder="Código, slug o nombre de mascota">
      </div>
      <div class="col-12 col-md-3">
        <label class="form-label">Estado</label>
        <select name="status" class="form-select">
          <option value="">Todos</option>
          <option value="assigned"   @selected($status==='assigned')>Asignados</option>
          <option value="unassigned" @selected($status==='unassigned')>Sin asignar</option>
          <option value="with_image" @selected($status==='with_image')>Con imagen</option>
          <option value="without_image" @selected($status==='without_image')>Sin imagen</option>
        </select>
      </div>
      <div class="col-12 col-md-3 d-flex gap-2">
        <button class="btn btn-primary flex-fill"><i class="fa-solid fa-magnifying-glass me-1"></i> Filtrar</button>
        <a class="btn btn-outline-secondary" href="{{ route('portal.admin.tags.index') }}">Limpiar</a>
      </div>
    </div>
  </form>

  {{-- Tabla --}}
  <div class="card card-elevated">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Código (TAG)</th>
            <th>Slug</th>
            <th>Mascota</th>
            <th>Asignado</th>
            <th>Imagen</th>
            <th class="text-end">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tags as $t)
            <tr>
              <td class="text-muted">{{ $t->id }}</td>
              <td class="fw-semibold">{{ $t->activation_code }}</td>
              <td class="text-muted">{{ $t->slug ?: '—' }}</td>
              <td>
                @if($t->pet)
                  <a href="{{ route('portal.pets.show', $t->pet) }}">{{ $t->pet->name }}</a>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td>
                @if($t->pet_id)
                  <span class="badge text-bg-success">Sí</span>
                @else
                  <span class="badge text-bg-secondary">No</span>
                @endif
              </td>
              <td>
                @if($t->image)
                  <a class="btn btn-sm btn-outline-secondary"
                     href="{{ route('portal.admin.tags.download', $t) }}">
                     <i class="fa-solid fa-download me-1"></i> Descargar
                  </a>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td class="text-end">
                <form action="{{ route('portal.admin.tags.regen-code', $t) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Regenerar el código (TAG)?');">
                  @csrf
                  <button class="btn btn-sm btn-outline-warning">
                    <i class="fa-solid fa-rotate me-1"></i> Regenerar código
                  </button>
                </form>

                <form action="{{ route('portal.admin.tags.rebuild', $t) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Reconstruir la imagen del QR?');">
                  @csrf
                  <button class="btn btn-sm btn-outline-info">
                    <i class="fa-solid fa-hammer me-1"></i> Reconstruir imagen
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-4">Sin resultados.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-body">
      {{ $tags->links() }}
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush
