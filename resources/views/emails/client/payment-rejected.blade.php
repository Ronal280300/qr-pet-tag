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
            background: #fef2f2;
            padding: 20px 10px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border: 4px solid #dc2626;
        }
        .header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 50%, #f87171 100%);
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
            background: #fef2f2;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            border: 4px solid #dc2626;
            box-shadow: 0 0 0 1px rgba(220, 38, 38, 0.1);
        }
        .order-details h3 {
            color: #dc2626;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 2px solid #fee2e2;
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
        .alert-box {
            background: #fef2f2;
            border-left: 5px solid #dc2626;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            border: 3px solid #dc2626;
            border-left-width: 5px;
        }
        .alert-box strong {
            color: #dc2626;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 800;
        }
        .alert-box p {
            color: #1f2937;
            margin: 0;
            line-height: 1.7;
            font-weight: 600;
        }
        .info-section {
            background: #fffbeb;
            padding: 25px;
            border-radius: 12px;
            margin: 30px 0;
            border: 4px solid #fbbf24;
        }
        .info-section h3 {
            color: #d97706;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
        }
        .info-section ul {
            list-style: none;
            padding: 0;
        }
        .info-section li {
            padding: 12px 0 12px 35px;
            position: relative;
            color: #1f2937;
            font-size: 16px;
            font-weight: 600;
        }
        .info-section li:before {
            content: "•";
            position: absolute;
            left: 10px;
            color: #dc2626;
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
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: #ffffff !important;
            padding: 18px 36px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            margin: 10px;
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
            border: 3px solid #075e54;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-email {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: 3px solid #1e40af;
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        .reassurance {
            background: #f0fdf4;
            border-left: 5px solid #10b981;
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
            border: 3px solid #10b981;
            border-left-width: 5px;
        }
        .reassurance p {
            color: #1f2937;
            margin: 0;
            line-height: 1.7;
            font-weight: 600;
        }
        .reassurance strong {
            font-size: 18px;
            font-weight: 800;
            color: #10b981;
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
            .order-details, .info-section { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="header-icon">⚠️</span>
            <h1>Problema con el Pago</h1>
            <p>Revisemos juntos tu comprobante</p>
        </div>

        <div class="content">
            <p class="greeting">Hola {{ $order->user->name }},</p>

            <p class="message">
                Hemos revisado tu comprobante, pero no pudimos verificar la transferencia.
                No te preocupes, estamos aquí para ayudarte a resolver esto rápidamente.
            </p>

            <div class="order-details">
                <h3>📋 Detalles del Pedido</h3>
                <div class="detail-row">
                    <strong>Pedido:</strong>
                    <span>#{{ $order->order_number }}</span>
                </div>
                <div class="detail-row">
                    <strong>Plan:</strong>
                    <span>{{ $order->plan->name }}</span>
                </div>
                <div class="detail-row">
                    <strong>Monto Esperado:</strong>
                    <span>₡{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            @if($order->admin_notes)
            <div class="alert-box">
                <strong>⚠️ Motivo del Rechazo:</strong>
                <p>{{ $order->admin_notes }}</p>
            </div>
            @endif

            <div class="info-section">
                <h3>✅ ¿Qué puedes hacer?</h3>
                <ul>
                    <li>Verifica el monto: <strong>₡{{ number_format($order->total, 0, ',', '.') }}</strong></li>
                    <li>Confirma la cuenta destino</li>
                    <li>Revisa que el comprobante sea legible</li>
                    <li>Contáctanos para resolverlo</li>
                </ul>
            </div>

            <div class="btn-container">
                @php
                    $whatsappNumber = config('app.whatsapp_number', '50685307943');
                    $whatsappMessage = urlencode("Hola, tengo un problema con pedido {$order->order_number}. El pago fue rechazado.");
                @endphp
                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}" class="btn">
                    💬 WhatsApp
                </a>
                <br>
                <a href="mailto:info.qrpet@gmail.com?subject=Problema pedido {{ $order->order_number }}" class="btn btn-email">
                    📧 Email
                </a>
            </div>

            <div class="reassurance">
                <p>
                    <strong>💚 Estamos Aquí para Ti</strong><br>
                    Nuestro equipo está disponible para resolver esto. Contáctanos y lo solucionaremos juntos.
                </p>
            </div>
        </div>

        <div class="footer">
            <div class="footer-logo">🐾 QR PET TAG</div>
            <p class="footer-text">Protección inteligente para tus mejores amigos</p>

            <div class="footer-contact">
                <p><strong>¿Necesitas Ayuda?</strong></p>
                <p>📧 <a href="mailto:info.qrpet@gmail.com">info.qrpet@gmail.com</a></p>
                <p>⏰ Lunes a Viernes 8:00 AM - 6:00 PM</p>
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
