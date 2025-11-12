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
            border-bottom: 2px solid #3b82f6;
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
        .timeline-section {
            background: #fafafa;
            padding: 24px;
            border-radius: 6px;
            margin: 32px 0;
            border: 1px solid #e5e7eb;
        }
        .timeline-section h3 {
            color: #111827;
            margin-bottom: 16px;
            font-size: 15px;
            font-weight: 600;
        }
        .timeline {
            position: relative;
            padding: 0;
        }
        .timeline-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            position: relative;
        }
        .timeline-step:last-child {
            margin-bottom: 0;
        }
        .timeline-icon {
            width: 40px;
            height: 40px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
            flex-shrink: 0;
            font-weight: 600;
            font-size: 14px;
            z-index: 2;
            position: relative;
        }
        .timeline-icon.pending {
            background: #9ca3af;
        }
        .timeline-content {
            flex: 1;
            padding-top: 6px;
        }
        .timeline-content strong {
            display: block;
            color: #111827;
            font-size: 14px;
            margin-bottom: 4px;
            font-weight: 600;
        }
        .timeline-content p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
            font-weight: 400;
        }
        .tip-box {
            background: #fafafa;
            border-left: 3px solid #10b981;
            padding: 20px;
            border-radius: 4px;
            margin-top: 32px;
        }
        .tip-box strong {
            color: #111827;
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }
        .tip-box p {
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
            .timeline-icon { width: 36px; height: 36px; font-size: 13px; }
            .order-details, .timeline-section { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="header-icon">✅</span>
            <h1>¡Comprobante Recibido!</h1>
            <p>Estamos verificando tu pago</p>
        </div>

        <div class="content">
            <p class="greeting">¡Hola {{ $order->user->name }}!</p>

            <p class="message">
                ¡Gracias por tu compra! Hemos recibido tu comprobante exitosamente y estamos
                verificándolo. Te mantendremos informado del progreso.
            </p>

            <div class="order-details">
                <h3>📦 Detalles de tu Pedido</h3>
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
                <div class="detail-row">
                    <strong>Total:</strong>
                    <span>₡{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="timeline-section">
                <h3>🚀 ¿Qué Sigue Ahora?</h3>
                <div class="timeline">
                    <div class="timeline-step">
                        <div class="timeline-icon">✓</div>
                        <div class="timeline-content">
                            <strong>1. Comprobante Recibido</strong>
                            <p>Tu pago está en verificación</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-icon pending">2</div>
                        <div class="timeline-content">
                            <strong>2. Verificación</strong>
                            <p>Confirmaremos tu pago (máx. 24h)</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-icon pending">3</div>
                        <div class="timeline-content">
                            <strong>3. Personalización</strong>
                            <p>Diseñaremos tus placas QR</p>
                        </div>
                    </div>
                    <div class="timeline-step">
                        <div class="timeline-icon pending">4</div>
                        <div class="timeline-content">
                            <strong>4. Envío</strong>
                            <p>Las recibirás en 3-5 días</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tip-box">
                <strong>💡 Consejo Útil</strong>
                <p>
                    Mientras verificamos tu pago, registra la información de tus mascotas en tu panel.
                    Esto agilizará la personalización de tus placas QR.
                </p>
            </div>
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
