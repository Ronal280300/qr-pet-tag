<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Servicio no disponible | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .error-container {
            text-align: center;
            color: white;
            padding: 40px 20px;
            position: relative;
            z-index: 10;
        }

        .error-code {
            font-size: 180px;
            font-weight: 900;
            line-height: 1;
            text-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: float 3s ease-in-out infinite;
            background: linear-gradient(135deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 30px;
            animation: spin 3s linear infinite;
        }

        .error-title {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 16px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .error-description {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .maintenance-info {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .maintenance-info h3 {
            font-size: 20px;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .maintenance-info p {
            font-size: 16px;
            margin: 0;
            opacity: 0.9;
        }

        .btn-retry {
            display: inline-block;
            padding: 16px 40px;
            background: white;
            color: #4facfe;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-retry:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: #00f2fe;
        }

        .btn-retry i {
            margin-right: 8px;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 120px;
            }

            .error-title {
                font-size: 28px;
            }

            .error-description {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fa-solid fa-screwdriver-wrench"></i>
        </div>
        <div class="error-code">503</div>
        <h1 class="error-title">Estamos en mantenimiento</h1>
        <p class="error-description">
            Estamos realizando mejoras en nuestro sistema para ofrecerte un mejor servicio.
        </p>
        <div class="maintenance-info">
            <h3>
                <i class="fa-solid fa-clock me-2"></i>
                Tiempo estimado
            </h3>
            <p>Estaremos de vuelta en breve. Generalmente nuestros mantenimientos duran menos de 30 minutos.</p>
        </div>
        <button onclick="location.reload()" class="btn-retry">
            <i class="fa-solid fa-rotate-right"></i>
            Verificar si ya estamos de vuelta
        </button>
    </div>
</body>
</html>
