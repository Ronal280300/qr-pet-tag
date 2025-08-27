<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $qrCode->pet->name }} - QR-Pet Tag</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-paw"></i> QR-Pet Tag
            </a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Información de la Mascota</h2>
                    </div>
                    <div class="card-body">
                        @if($qrCode->pet->photo)
                            <div class="text-center mb-3">
                                <img src="{{ asset('storage/' . $qrCode->pet->photo) }}" alt="{{ $qrCode->pet->name }}" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        @else
                            <div class="text-center mb-3">
                                <i class="fas fa-paw fa-5x text-muted"></i>
                            </div>
                        @endif
                        
                        <h3 class="text-center">{{ $qrCode->pet->name }}</h3>
                        
                        <div class="mt-3">
                            <p><strong>Dueño:</strong> {{ $qrCode->pet->user->name }}</p>
                            <p><strong>Teléfono:</strong> {{ $qrCode->pet->user->phone ?? 'No proporcionado' }}</p>
                            <p><strong>Raza:</strong> {{ $qrCode->pet->breed ?? 'No especificada' }}</p>
                            <p><strong>Edad:</strong> {{ $qrCode->pet->age ?? 'No especificada' }}</p>
                            
                            @if($qrCode->pet->medical_conditions)
                                <p><strong>Condiciones médicas:</strong> {{ $qrCode->pet->medical_conditions }}</p>
                            @endif
                        </div>

                        <div class="text-center mt-4">
                            @if($qrCode->pet->user->phone)
                                <a href="https://wa.me/506{{ $qrCode->pet->user->phone }}?text=Hola,%20encontré%20a%20tu%20mascota%20{{ $qrCode->pet->name }}" 
                                   class="btn btn-success" target="_blank">
                                    <i class="fab fa-whatsapp"></i> Contactar por WhatsApp
                                </a>
                            @endif
                        </div>

                        @if($qrCode->pet->reward->is_active && $qrCode->pet->reward->amount > 0)
                            <div class="alert alert-info mt-3">
                                <h5>Recompensa</h5>
                                <p>Se ofrece una recompensa de ₡{{ number_format($qrCode->pet->reward->amount, 2) }}</p>
                                @if($qrCode->pet->reward->message)
                                    <p>{{ $qrCode->pet->reward->message }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>