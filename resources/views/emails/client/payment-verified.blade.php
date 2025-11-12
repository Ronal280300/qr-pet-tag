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
            background: #fafafa;
            padding: 20px 10px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: #ffffff;
            color: #111827;
            padding: 40px 30px 30px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }
        .header-icon {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
        }
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0 0 8px 0;
            letter-spacing: -0.5px;
            color: #111827;
        }
        .header p {
            font-size: 15px;
            margin: 0;
            font-weight: 400;
            color: #6b7280;
        }
        .content {
            padding: 40px 30px;
            background: #ffffff;
        }
        .greeting {
            font-size: 18px;
            color: #111827;
            margin-bottom: 16px;
            font-weight: 600;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 32px;
            line-height: 1.7;
            font-weight: 400;
        }
        .order-details {
            background: #ffffff;
            padding: 24px;
            border-radius: 6px;
            margin: 32px 0;
            border: 1px solid #e5e7eb;
        }
        .order-details h3 {
            color: #111827;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 12px;
            border-bottom: 2px solid #10b981;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            align-items: center;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-row strong {
            color: #6b7280;
            font-weight: 500;
            font-size: 14px;
        }
        .detail-row span {
            color: #111827;
            font-weight: 600;
            font-size: 14px;
        }
        .next-steps {
            background: #fafafa;
            padding: 24px;
            border-radius: 6px;
            margin: 32px 0;
            border: 1px solid #e5e7eb;
        }
        .next-steps h3 {
            color: #111827;
            margin-bottom: 16px;
            font-size: 15px;
            font-weight: 600;
        }
        .next-steps ul {
            list-style: none;
            padding: 0;
            margin-top: 12px;
        }
        .next-steps li {
            padding: 10px 0 10px 24px;
            position: relative;
            color: #4b5563;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6;
        }
        .next-steps li:before {
            content: "•";
            position: absolute;
            left: 8px;
            color: #10b981;
            font-weight: bold;
            font-size: 16px;
        }
        .btn-container {
            text-align: center;
            margin: 40px 0 32px;
            padding: 0;
        }
        .btn {
            display: inline-block;
            background: #10b981;
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin: 8px 4px;
            border: none;
            transition: background 0.2s;
        }
        .btn-whatsapp {
            background: #25D366;
        }
        .note-box {
            background: #fafafa;
            border-left: 3px solid #10b981;
            padding: 20px;
            border-radius: 4px;
            margin-top: 32px;
        }
        .note-box strong {
            color: #111827;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }
        .note-box p {
            color: #4b5563;
            margin: 0;
            line-height: 1.6;
            font-weight: 400;
            font-size: 14px;
        }
        .footer {
            background: #fafafa;
            color: #6b7280;
            padding: 32px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-logo {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .footer-text {
            font-size: 13px;
            margin: 6px 0;
            color: #9ca3af;
        }
        .footer-contact {
            margin: 24px 0 16px;
            padding: 0;
        }
        .footer-contact p {
            margin: 8px 0;
            font-size: 13px;
            font-weight: 400;
            color: #6b7280;
        }
        .footer-contact a {
            color: #10b981;
            text-decoration: none;
            font-weight: 500;
        }
        .footer-btn {
            display: inline-block;
            background: #111827;
            color: #ffffff !important;
            padding: 10px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 12px;
            font-size: 13px;
        }
        @media only screen and (max-width: 600px) {
            body { padding: 10px 5px; }
            .header { padding: 32px 20px 24px; }
            .header h1 { font-size: 24px; }
            .header-icon { font-size: 42px; }
            .content { padding: 32px 20px; }
            .btn { padding: 12px 24px; font-size: 14px; display: block; margin: 8px 0; }
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
