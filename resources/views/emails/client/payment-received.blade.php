<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            background-color: #f7fafc;
        }
        .email-wrapper {
            background-color: #f7fafc;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #4e89e8 0%, #3b82f6 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 10px 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header p {
            font-size: 16px;
            opacity: 0.95;
            margin: 0;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1a202c;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 25px;
            line-height: 1.7;
        }
        .order-details {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            border: 1px solid #bfdbfe;
        }
        .order-details h3 {
            color: #1e40af;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 700;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #93c5fd;
            align-items: center;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-row strong {
            color: #1e3a8a;
            font-weight: 600;
        }
        .detail-row span {
            color: #2d3748;
            font-weight: 500;
        }
        .timeline-section {
            margin: 35px 0;
        }
        .timeline-section h3 {
            color: #2d3748;
            margin-bottom: 25px;
            font-size: 20px;
            font-weight: 700;
            text-align: center;
        }
        .timeline {
            position: relative;
            padding: 0;
        }
        .timeline-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
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
            font-weight: 700;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
            z-index: 2;
            position: relative;
        }
        .timeline-icon.pending {
            background: linear-gradient(135deg, #cbd5e0 0%, #a0aec0 100%);
            box-shadow: 0 4px 10px rgba(160, 174, 192, 0.2);
        }
        .timeline-content {
            flex: 1;
            padding-top: 5px;
        }
        .timeline-content strong {
            display: block;
            color: #2d3748;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .timeline-content p {
            margin: 0;
            color: #718096;
            font-size: 14px;
            line-height: 1.5;
        }
        .tip-box {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        .tip-box strong {
            color: #92400e;
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .tip-box p {
            color: #78350f;
            margin: 0;
            line-height: 1.6;
        }
        .footer {
            background: #1a202c;
            color: #cbd5e0;
            padding: 35px 30px;
            text-align: center;
        }
        .footer-logo {
            font-size: 22px;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 15px;
        }
        .footer-text {
            font-size: 14px;
            margin: 10px 0;
            color: #a0aec0;
        }
        .footer-contact {
            margin: 20px 0;
            padding-top: 20px;
            border-top: 1px solid #2d3748;
        }
        .footer-contact p {
            margin: 8px 0;
            font-size: 14px;
        }
        .footer-contact a {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
        }
        .footer-btn {
            display: inline-block;
            background: #25D366;
            color: white !important;
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 15px;
            font-size: 14px;
        }
        @media only screen and (max-width: 600px) {
            .header h1 { font-size: 26px; }
            .content { padding: 30px 20px; }
            .timeline-icon { width: 45px; height: 45px; font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <div class="header">
                <h1>✅ ¡Comprobante Recibido!</h1>
                <p>Estamos procesando tu pago</p>
            </div>

            <div class="content">
                <p class="greeting">¡Hola {{ $order->user->name }}!</p>

                <p class="message">
                    ¡Gracias por tu compra! Hemos recibido tu comprobante de pago exitosamente y estamos
                    trabajando para verificarlo. Te mantendremos informado del progreso.
                </p>

                <div class="order-details">
                    <h3>📦 Detalles de tu Pedido</h3>
                    <div class="detail-row">
                        <strong>Número de Pedido:</strong>
                        <span>{{ $order->order_number }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Plan Seleccionado:</strong>
                        <span>{{ $order->plan->name }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Mascotas Incluidas:</strong>
                        <span>{{ $order->pets_quantity }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Total Pagado:</strong>
                        <span style="color: #1e40af; font-size: 20px; font-weight: 700;">
                            ₡{{ number_format($order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="timeline-section">
                    <h3>🚀 ¿Qué sigue ahora?</h3>
                    <div class="timeline">
                        <div class="timeline-step">
                            <div class="timeline-icon">✓</div>
                            <div class="timeline-content">
                                <strong>1. Comprobante Recibido</strong>
                                <p>Tu pago está en proceso de verificación por nuestro equipo</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon pending">2</div>
                            <div class="timeline-content">
                                <strong>2. Verificación del Pago</strong>
                                <p>Confirmaremos tu transferencia (máximo 24 horas hábiles)</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon pending">3</div>
                            <div class="timeline-content">
                                <strong>3. Personalización de Placas</strong>
                                <p>Te contactaremos para diseñar tus placas QR personalizadas</p>
                            </div>
                        </div>
                        <div class="timeline-step">
                            <div class="timeline-icon pending">4</div>
                            <div class="timeline-content">
                                <strong>4. Envío a Domicilio</strong>
                                <p>Recibirás tus placas QR en 3-5 días hábiles</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tip-box">
                    <strong>💡 Consejo Útil</strong>
                    <p>
                        Mientras verificamos tu pago, puedes registrar la información de tus mascotas desde tu panel
                        de usuario. Esto nos ayudará a agilizar el proceso de personalización de tus placas QR.
                    </p>
                </div>
            </div>

            <div class="footer">
                <div class="footer-logo">🐾 QR Pet Tag</div>
                <p class="footer-text">Protección inteligente para tus mejores amigos</p>

                <div class="footer-contact">
                    <p><strong>¿Necesitas ayuda?</strong></p>
                    <p>📧 Email: <a href="mailto:info.qrpet@gmail.com">info.qrpet@gmail.com</a></p>
                    <a href="https://wa.me/{{ config('app.whatsapp_number', '50685307943') }}?text={{ urlencode('Hola, necesito ayuda con mi cuenta de QR Pet Tag') }}" class="footer-btn">
                        💬 Contáctanos por WhatsApp
                    </a>
                </div>

                <p class="footer-text" style="margin-top: 25px; font-size: 12px; color: #718096;">
                    © {{ date('Y') }} QR Pet Tag. Todos los derechos reservados.<br>
                    Gracias por confiar en nosotros para proteger a tus mascotas 🐾
                </p>
            </div>
        </div>
    </div>
</body>
</html>
