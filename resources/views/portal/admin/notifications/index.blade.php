@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<div class="container-fluid py-4">
  <div class="row mb-4">
    <div class="col">
      <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-bell me-2"></i>Notificaciones
      </h1>
    </div>
    <div class="col-auto">
      <form method="POST" action="{{ route('portal.admin.notifications.readAll') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-sm btn-primary">
          <i class="fas fa-check-double me-1"></i>Marcar todas como leídas
        </button>
      </form>
    </div>
  </div>

  <div class="card shadow">
    <div class="card-body">
      @if($notifications->isEmpty())
        <div class="text-center py-5">
          <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
          <h5 class="text-muted">No tienes notificaciones</h5>
        </div>
      @else
        <div class="list-group list-group-flush">
          @foreach($notifications as $notification)
            <div class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'bg-light border-start border-primary border-4' }}">
              <div class="d-flex w-100 justify-content-between align-items-start">
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center mb-2">
                    <i class="fas {{ $notification->icon ?? 'fa-bell' }} me-2 text-primary"></i>
                    <h6 class="mb-0">{{ $notification->title }}</h6>
                    @if(!$notification->is_read)
                      <span class="badge bg-primary ms-2">Nuevo</span>
                    @endif
                  </div>
                  <p class="mb-2 text-muted">{{ $notification->message }}</p>
                  <small class="text-muted">
                    <i class="far fa-clock me-1"></i>
                    {{ $notification->created_at->diffForHumans() }}
                  </small>
                </div>
                <div class="ms-3 d-flex flex-column gap-2">
                  @if($notification->url)
                    <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-eye"></i> Ver
                    </a>
                  @endif
                  @if(!$notification->is_read)
                    <form method="POST" action="{{ route('portal.admin.notifications.read', $notification) }}">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-check"></i> Marcar leída
                      </button>
                    </form>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-3">
          {{ $notifications->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
