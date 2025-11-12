<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            background: #f0fdf4;
            padding: 20px 10px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border: 4px solid #10b981;
        }
        .header {
            background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
            color: #ffffff;
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #fbbf24, #f59e0b, #fbbf24);
        }
        .header-icon {
            font-size: 64px;
            margin-bottom: 15px;
            display: block;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .header h1 {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 10px 0;
            text-shadow: 0 3px 6px rgba(0,0,0,0.2);
            letter-spacing: -0.5px;
        }
        .header p {
            font-size: 18px;
            opacity: 0.95;
            margin: 0;
            font-weight: 500;
        }
        .content {
            padding: 40px 30px;
            background: #ffffff;
        }
        .greeting {
            font-size: 22px;
            color: #111827;
            margin-bottom: 20px;
            font-weight: 800;
        }
        .message {
            font-size: 17px;
            color: #1f2937;
            margin-bottom: 30px;
            line-height: 1.8;
            font-weight: 500;
        }
        .order-details {
            background: #f0fdf4;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            border: 4px solid #10b981;
            box-shadow: 0 0 0 1px rgba(16, 185, 129, 0.1);
        }
        .order-details h3 {
            color: #10b981;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 2px solid #d1fae5;
            align-items: center;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-row strong {
            color: #111827;
            font-weight: 700;
            font-size: 16px;
        }
        .detail-row span {
            color: #1f2937;
            font-weight: 700;
            font-size: 16px;
        }
        .next-steps {
            background: #fffbeb;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            border: 4px solid #fbbf24;
        }
        .next-steps h3 {
            color: #d97706;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
        }
        .next-steps ul {
            list-style: none;
            padding: 0;
            margin-top: 15px;
        }
        .next-steps li {
            padding: 12px 0 12px 35px;
            position: relative;
            color: #1f2937;
            font-size: 16px;
            font-weight: 600;
        }
        .next-steps li:before {
            content: "✓";
            position: absolute;
            left: 5px;
            color: #10b981;
            font-weight: bold;
            font-size: 20px;
        }
        .btn-container {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: #f9fafb;
            border-radius: 12px;
            border: 3px solid #e5e7eb;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            padding: 18px 36px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            margin: 10px;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            border: 3px solid #065f46;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-whatsapp {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            border: 3px solid #075e54;
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        }
        .note-box {
            background: #eff6ff;
            border-left: 5px solid #3b82f6;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
            border: 3px solid #3b82f6;
            border-left-width: 5px;
        }
        .note-box strong {
            color: #1e40af;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 800;
        }
        .note-box p {
            color: #1f2937;
            margin: 0;
            line-height: 1.7;
            font-weight: 600;
        }
        .footer {
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            color: #e5e7eb;
            padding: 40px 30px;
            text-align: center;
        }
        .footer-logo {
            font-size: 28px;
            font-weight: 800;
            color: #10b981;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .footer-text {
            font-size: 14px;
            margin: 10px 0;
            color: #d1d5db;
        }
        .footer-contact {
            margin: 25px 0;
            padding: 25px;
            background: #374151;
            border-radius: 12px;
        }
        .footer-contact p {
            margin: 10px 0;
            font-size: 15px;
            font-weight: 600;
        }
        .footer-contact a {
            color: #34d399;
            text-decoration: none;
            font-weight: 700;
        }
        .footer-btn {
            display: inline-block;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            margin-top: 15px;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
            border: 2px solid #075e54;
        }
        @media only screen and (max-width: 600px) {
            body { padding: 10px 0; }
            .header h1 { font-size: 26px; }
            .header-icon { font-size: 48px; }
            .content { padding: 30px 20px; }
            .btn { padding: 16px 28px; font-size: 14px; display: block; margin: 10px 0; }
            .order-details, .next-steps { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="header-icon">🎉</span>
            <h1>¡Pago Verificado!</h1>
            <p>Tu plan está activo y listo</p>
        </div>

        <div class="content">
            <p class="greeting">¡Hola {{ $order->user->name }}!</p>

            <p class="message">
                ¡Excelentes noticias! Tu pago ha sido verificado exitosamente y tu plan está ahora ACTIVO.
                Ahora puedes disfrutar de todas las ventajas de proteger a tus mascotas con QR Pet Tag.
            </p>

            <div class="order-details">
                <h3>✨ Tu Plan Activo</h3>
                <div class="detail-row">
                    <strong>Pedido:</strong>
                    <span>#{{ $order->order_number }}</span>
                </div>
                <div class="detail-row">
                    <strong>Plan:</strong>
                    <span>{{ $order->plan->name }}</span>
                </div>
                <div class="detail-row">
                    <strong>Mascotas:</strong>
                    <span>{{ $order->pets_quantity }}</span>
                </div>
                @if($order->plan->type === 'subscription' && $order->expires_at)
                <div class="detail-row">
                    <strong>Válido Hasta:</strong>
                    <span>{{ $order->expires_at->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>

            <div class="next-steps">
                <h3>📋 Próximos Pasos</h3>
                <ul>
                    <li>Diseñaremos tus placas QR personalizadas</li>
                    <li>Verificaremos la información de tus mascotas</li>
                    <li>Organizaremos el envío a tu dirección</li>
                </ul>
            </div>

            <div class="btn-container">
                @php
                    $hasPets = $order->pets && $order->pets->count() > 0;
                    $whatsappNumber = config('app.whatsapp_number', '50685307943');
                    $whatsappMessage = urlencode("Hola, mi pago fue verificado (Pedido: {$order->order_number}) y necesito ayuda para registrar mis mascotas.");
                @endphp

                @if($hasPets)
                    <a href="{{ route('portal.pets.index') }}" class="btn">
                        🐾 Ver Mis Mascotas ({{ $order->pets->count() }})
                    </a>
                @else
                    <a href="{{ route('portal.pets.create') }}" class="btn">
                        ✨ Registrar Mascotas
                    </a>
                    <br>
                    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}" class="btn btn-whatsapp">
                        💬 Ayuda para Registrar
                    </a>
                @endif
            </div>

            @if($order->admin_notes)
            <div class="note-box">
                <strong>💡 Nota del Equipo:</strong>
                <p>{{ $order->admin_notes }}</p>
            </div>
            @endif
        </div>

        <div class="footer">
            <div class="footer-logo">🐾 QR PET TAG</div>
            <p class="footer-text">Protección inteligente para tus mejores amigos</p>

            <div class="footer-contact">
                <p><strong>¿Necesitas Ayuda?</strong></p>
                <p>📧 <a href="mailto:info.qrpet@gmail.com">info.qrpet@gmail.com</a></p>
                <a href="https://wa.me/{{ config('app.whatsapp_number', '50685307943') }}?text={{ urlencode('Hola, necesito ayuda con QR Pet Tag') }}" class="footer-btn">
                    💬 WhatsApp
                </a>
            </div>

            <p class="footer-text" style="margin-top: 25px; font-size: 12px; opacity: 0.7;">
                © {{ date('Y') }} QR Pet Tag • Todos los derechos reservados
            </p>
        </div>
    </div>
</body>
</html>
