@extends('layouts.app')

@section('title', 'Pedido #' . $order->order_number)

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <a href="{{ route('portal.admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver a pedidos
        </a>
    </div>

    <div class="row">
        <!-- Columna izquierda: Información del pedido -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Pedido {{ $order->order_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Estado:</strong>
                            <span class="badge {{ $order->status_badge_class }} ms-2">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>

                    <hr>

                    <h5>Información del Cliente</h5>
                    <p>
                        <strong>Nombre:</strong> {{ $order->user->name }}<br>
                        <strong>Email:</strong> {{ $order->user->email }}<br>
                        <strong>Teléfono:</strong> {{ $order->user->phone ?? 'N/A' }}
                    </p>

                    <hr>

                    <h5>Detalles del Plan</h5>
                    <table class="table">
                        <tr>
                            <td><strong>Plan:</strong></td>
                            <td>{{ $order->plan->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cantidad de mascotas:</strong></td>
                            <td>{{ $order->pets_quantity }}</td>
                        </tr>
                        <tr>
                            <td><strong>Subtotal:</strong></td>
                            <td>₡{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($order->additional_pets_cost > 0)
                        <tr>
                            <td><strong>Mascotas adicionales:</strong></td>
                            <td>₡{{ number_format($order->additional_pets_cost, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="table-primary">
                            <td><strong>TOTAL:</strong></td>
                            <td><strong class="fs-4">₡{{ number_format($order->total, 0, ',', '.') }}</strong></td>
                        </tr>
                    </table>

                    @if($order->admin_notes)
                    <div class="alert alert-info">
                        <strong>Notas del administrador:</strong><br>
                        {{ $order->admin_notes }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Comprobante de Pago -->
            @if($order->payment_proof)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Comprobante de Pago</h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted">
                        Subido el: {{ $order->payment_uploaded_at->format('d/m/Y H:i') }}
                    </p>

                    @if(Str::endsWith($order->payment_proof, '.pdf'))
                        <div class="mb-3">
                            <i class="fa-solid fa-file-pdf text-danger" style="font-size: 80px;"></i>
                            <p class="mt-3"><strong>Archivo PDF</strong></p>
                        </div>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ Storage::url($order->payment_proof) }}" target="_blank" class="btn btn-lg btn-primary">
                                <i class="fa-solid fa-eye"></i> Ver PDF
                            </a>
                            <a href="{{ Storage::url($order->payment_proof) }}" download="comprobante-{{ $order->order_number }}.pdf" class="btn btn-lg btn-success">
                                <i class="fa-solid fa-download"></i> Descargar
                            </a>
                        </div>
                    @else
                        <img src="{{ Storage::url($order->payment_proof) }}"
                             alt="Comprobante"
                             class="img-fluid border rounded shadow-sm"
                             style="max-height: 600px; cursor: pointer;"
                             onclick="window.open(this.src, '_blank')">
                        <p class="mt-3">
                            <small class="text-muted d-block mb-2">Click en la imagen para ampliar</small>
                            <a href="{{ Storage::url($order->payment_proof) }}" download="comprobante-{{ $order->order_number }}.{{ pathinfo($order->payment_proof, PATHINFO_EXTENSION) }}" class="btn btn-success">
                                <i class="fa-solid fa-download"></i> Descargar Imagen
                            </a>
                        </p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Columna derecha: Acciones -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    @if($order->status === 'payment_uploaded')
                        <!-- Verificar pago -->
                        <form method="POST" action="{{ route('portal.admin.orders.verify', $order) }}" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Notas (opcional)</label>
                                <textarea name="admin_notes" class="form-control" rows="3"
                                          placeholder="Ej: Pago verificado correctamente"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fa-solid fa-check"></i> Verificar Pago
                            </button>
                        </form>

                        <!-- Rechazar pago -->
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fa-solid fa-times"></i> Rechazar Pago
                        </button>
                    @elseif($order->status === 'verified')
                        <form method="POST" action="{{ route('portal.admin.orders.complete', $order) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-check-double"></i> Marcar como Completado
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info">
                            Estado actual: <strong>{{ $order->status_label }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Información Adicional</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        @if($order->verified_at)
                        <p><strong>Verificado:</strong><br>{{ $order->verified_at->format('d/m/Y H:i') }}</p>
                        @endif

                        @if($order->verifiedBy)
                        <p><strong>Verificado por:</strong><br>{{ $order->verifiedBy->name }}</p>
                        @endif

                        @if($order->plan->type === 'subscription' && $order->expires_at)
                        <p><strong>Expira:</strong><br>{{ $order->expires_at->format('d/m/Y') }}</p>
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para rechazar -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('portal.admin.orders.reject', $order) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                        El cliente recibirá un email con el motivo del rechazo.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo del rechazo *</label>
                        <textarea name="admin_notes" class="form-control" rows="4"
                                  placeholder="Ej: El monto no coincide con el total del pedido"
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rechazar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
