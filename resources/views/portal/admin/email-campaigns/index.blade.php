@extends('layouts.app')

@section('title', 'Campañas de Email')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-paper-plane me-2"></i>Campañas de Email
      </h1>
    </div>
    <div class="col-auto">
      <a href="{{ route('portal.admin.email-campaigns.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Nueva Campaña
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card shadow">
    <div class="card-body">
      @if($campaigns->isEmpty())
        <div class="text-center py-5">
          <i class="fas fa-paper-plane fa-4x text-muted mb-3"></i>
          <h5 class="text-muted">No hay campañas creadas</h5>
          <p class="text-muted">Crea tu primera campaña de email marketing</p>
          <a href="{{ route('portal.admin.email-campaigns.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Crear Campaña
          </a>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Plantilla</th>
                <th>Estado</th>
                <th>Destinatarios</th>
                <th>Enviados</th>
                <th>Fallidos</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($campaigns as $campaign)
                <tr>
                  <td>
                    <strong>{{ $campaign->name }}</strong>
                    <br><small class="text-muted">Por {{ $campaign->creator->name }}</small>
                  </td>
                  <td>{{ $campaign->template->name }}</td>
                  <td>
                    @php
                      $badges = [
                        'draft' => 'secondary',
                        'sending' => 'warning',
                        'sent' => 'success',
                        'failed' => 'danger',
                      ];
                    @endphp
                    <span class="badge bg-{{ $badges[$campaign->status] ?? 'secondary' }}">
                      {{ ucfirst($campaign->status) }}
                    </span>
                  </td>
                  <td>{{ $campaign->total_recipients }}</td>
                  <td>
                    <span class="text-success">
                      <i class="fas fa-check"></i> {{ $campaign->sent_count }}
                    </span>
                  </td>
                  <td>
                    @if($campaign->failed_count > 0)
                      <span class="text-danger">
                        <i class="fas fa-times"></i> {{ $campaign->failed_count }}
                      </span>
                    @else
                      <span class="text-muted">0</span>
                    @endif
                  </td>
                  <td>
                    <small>{{ $campaign->created_at->format('d/m/Y H:i') }}</small>
                  </td>
                  <td>
                    <a href="{{ route('portal.admin.email-campaigns.show', $campaign) }}" class="btn btn-sm btn-info">
                      <i class="fas fa-eye"></i>
                    </a>
                    @if($campaign->status === 'draft')
                      <a href="{{ route('portal.admin.email-campaigns.confirm', $campaign) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-paper-plane"></i>
                      </a>
                    @endif
                    @if($campaign->status !== 'sending')
                      <form method="POST" action="{{ route('portal.admin.email-campaigns.destroy', $campaign) }}" style="display:inline;" onsubmit="return confirm('¿Eliminar esta campaña?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $campaigns->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
