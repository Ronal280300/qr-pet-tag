@extends('layouts.app')

@section('title', 'Plantillas de Email')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-file-alt me-2"></i>Plantillas de Email
      </h1>
    </div>
    <div class="col-auto">
      <a href="{{ route('portal.admin.email-templates.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nueva Plantilla
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card shadow">
    <div class="card-body">
      @if($templates->isEmpty())
        <div class="text-center py-5">
          <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
          <h5 class="text-muted">No hay plantillas creadas</h5>
          <p class="text-muted">Crea tu primera plantilla de email para usar en campañas</p>
          <a href="{{ route('portal.admin.email-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Crear Plantilla
          </a>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Asunto</th>
                <th>Estado</th>
                <th>Campañas</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($templates as $template)
                <tr>
                  <td>
                    <strong>{{ $template->name }}</strong>
                    @if($template->description)
                      <br><small class="text-muted">{{ Str::limit($template->description, 50) }}</small>
                    @endif
                  </td>
                  <td>
                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $template->category)) }}</span>
                  </td>
                  <td>{{ Str::limit($template->subject, 40) }}</td>
                  <td>
                    @if($template->is_active)
                      <span class="badge bg-success">Activa</span>
                    @else
                      <span class="badge bg-secondary">Inactiva</span>
                    @endif
                  </td>
                  <td>
                    <span class="text-muted">{{ $template->campaigns()->count() }}</span>
                  </td>
                  <td>
                    <small>{{ $template->created_at->format('d/m/Y') }}</small>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="{{ route('portal.admin.email-templates.preview', $template) }}"
                         class="btn btn-sm btn-info"
                         target="_blank"
                         title="Previsualizar">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('portal.admin.email-templates.edit', $template) }}"
                         class="btn btn-sm btn-primary"
                         title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form method="POST" action="{{ route('portal.admin.email-templates.duplicate', $template) }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary" title="Duplicar">
                          <i class="fas fa-copy"></i>
                        </button>
                      </form>
                      <form method="POST" action="{{ route('portal.admin.email-templates.destroy', $template) }}"
                            style="display:inline;"
                            onsubmit="return confirm('¿Estás seguro de eliminar esta plantilla?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $templates->links() }}
        </div>
      @endif
    </div>
  </div>

  {{-- Información sobre variables disponibles --}}
  <div class="card shadow mt-4">
    <div class="card-header">
      <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Variables Disponibles</h6>
    </div>
    <div class="card-body">
      <p class="mb-2">Puedes usar las siguientes variables en tus plantillas:</p>
      <div class="row">
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li><code>{{'{{'}}name{{'}}'}}</code> - Nombre del usuario</li>
            <li><code>{{'{{'}}email{{'}}'}}</code> - Email del usuario</li>
            <li><code>{{'{{'}}phone{{'}}'}}</code> - Teléfono del usuario</li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li><code>{{'{{'}}year{{'}}'}}</code> - Año actual</li>
            <li><code>{{'{{'}}site_name{{'}}'}}</code> - Nombre del sitio</li>
            <li><code>{{'{{'}}site_url{{'}}'}}</code> - URL del sitio</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
