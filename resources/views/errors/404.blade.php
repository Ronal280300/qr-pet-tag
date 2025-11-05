<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada | {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            animation: shake 2s ease-in-out infinite;
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

        .btn-home {
            display: inline-block;
            padding: 16px 40px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: #764ba2;
        }

        .btn-home i {
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

        @keyframes shake {
            0%, 100% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(-10deg);
            }
            75% {
                transform: rotate(10deg);
            }
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            top: 0;
            left: 0;
        }

        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: white;
            border-radius: 50%;
            opacity: 0.3;
            animation: rise 15s infinite ease-in;
        }

        @keyframes rise {
            0% {
                bottom: -10%;
                opacity: 0.3;
            }
            50% {
                opacity: 0.6;
            }
            100% {
                bottom: 110%;
                opacity: 0;
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
    <div class="particles">
        @for ($i = 0; $i < 20; $i++)
        <div class="particle" style="left: {{ rand(0, 100) }}%; animation-delay: {{ rand(0, 15) }}s;"></div>
        @endfor
    </div>

    <div class="error-container">
        <div class="error-icon">
            <i class="fa-solid fa-dog"></i>
        </div>
        <div class="error-code">404</div>
        <h1 class="error-title">¡Ups! Página no encontrada</h1>
        <p class="error-description">
            Parece que esta página se perdió como una mascota sin collar QR.
            No te preocupes, te ayudaremos a encontrar el camino de regreso.
        </p>
        <a href="{{ route('home') }}" class="btn-home">
            <i class="fa-solid fa-home"></i>
            Volver al inicio
        </a>
    </div>
</body>
</html>
