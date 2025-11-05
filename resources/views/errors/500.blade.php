<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Error del servidor | {{ config('app.name') }}</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            animation: glitch 2s infinite;
            background: linear-gradient(135deg, #fff, #f0f0f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 30px;
            animation: pulse 2s ease-in-out infinite;
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

        .btn-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-home, .btn-retry {
            display: inline-block;
            padding: 16px 40px;
            background: white;
            color: #f5576c;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-home:hover, .btn-retry:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            color: #f093fb;
        }

        .btn-home i, .btn-retry i {
            margin-right: 8px;
        }

        @keyframes glitch {
            0%, 100% {
                transform: translate(0);
            }
            20% {
                transform: translate(-2px, 2px);
            }
            40% {
                transform: translate(-2px, -2px);
            }
            60% {
                transform: translate(2px, 2px);
            }
            80% {
                transform: translate(2px, -2px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
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

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fa-solid fa-circle-exclamation"></i>
        </div>
        <div class="error-code">500</div>
        <h1 class="error-title">¡Error del servidor!</h1>
        <p class="error-description">
            Algo salió mal en nuestros servidores. Nuestro equipo ya fue notificado
            y está trabajando para solucionar el problema. Por favor, intenta nuevamente en unos momentos.
        </p>
        <div class="btn-group">
            <button onclick="location.reload()" class="btn-retry">
                <i class="fa-solid fa-rotate-right"></i>
                Reintentar
            </button>
            <a href="{{ route('home') }}" class="btn-home">
                <i class="fa-solid fa-home"></i>
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
