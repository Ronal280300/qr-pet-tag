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
            background: #eff6ff;
            padding: 20px 10px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border: 4px solid #3b82f6;
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #3b82f6 50%, #60a5fa 100%);
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
            background: linear-gradient(90deg, #10b981, #34d399, #10b981);
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
            background: #eff6ff;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            border: 4px solid #3b82f6;
            box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.1);
        }
        .order-details h3 {
            color: #3b82f6;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 2px solid #dbeafe;
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
        .timeline-section {
            background: #fffbeb;
            padding: 30px;
            border-radius: 12px;
            margin: 30px 0;
            border: 4px solid #fbbf24;
        }
        .timeline-section h3 {
            color: #d97706;
            margin-bottom: 25px;
            font-size: 20px;
            font-weight: 800;
            text-align: center;
        }
        .timeline {
            position: relative;
            padding: 0;
        }
        .timeline-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
            position: relative;
        }
        .timeline-step:last-child {
            margin-bottom: 0;
        }
        .timeline-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
            font-weight: 800;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            z-index: 2;
            position: relative;
            border: 3px solid #065f46;
        }
        .timeline-icon.pending {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            border: 3px solid #4b5563;
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }
        .timeline-content {
            flex: 1;
            padding-top: 8px;
        }
        .timeline-content strong {
            display: block;
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: 800;
        }
        .timeline-content p {
            margin: 0;
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
            font-weight: 600;
        }
        .tip-box {
            background: #f0fdf4;
            border-left: 5px solid #10b981;
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            border: 3px solid #10b981;
            border-left-width: 5px;
        }
        .tip-box strong {
            color: #10b981;
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 800;
        }
        .tip-box p {
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
            .timeline-icon { width: 45px; height: 45px; font-size: 16px; }
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
