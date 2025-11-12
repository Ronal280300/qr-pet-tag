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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            border: 1px solid #fecaca;
        }
        .order-details h3 {
            color: #991b1b;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 700;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #fecaca;
            align-items: center;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-row strong {
            color: #7c2d12;
            font-weight: 600;
        }
        .detail-row span {
            color: #2d3748;
            font-weight: 500;
        }
        .alert-box {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .alert-box strong {
            color: #991b1b;
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .alert-box p {
            color: #7f1d1d;
            margin: 0;
            line-height: 1.6;
        }
        .info-section {
            margin: 30px 0;
        }
        .info-section h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
        }
        .info-section ul {
            list-style: none;
            padding: 0;
        }
        .info-section li {
            padding: 10px 0 10px 30px;
            position: relative;
            color: #4a5568;
            font-size: 15px;
        }
        .info-section li:before {
            content: "•";
            position: absolute;
            left: 10px;
            color: #ef4444;
            font-weight: bold;
            font-size: 20px;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            color: white !important;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 8px;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.4);
        }
        .btn-email {
            background: linear-gradient(135deg, #4e89e8 0%, #3b82f6 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .btn-email:hover {
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        .reassurance {
            background: #f0fdf4;
            border-left: 4px solid #10b981;
            padding: 20px;
            border-radius: 6px;
            margin-top: 25px;
        }
        .reassurance p {
            color: #065f46;
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
            .btn { padding: 14px 24px; font-size: 14px; display: block; margin: 10px 0; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="container">
            <div class="header">
                <h1>⚠️ Problema con el Pago</h1>
                <p>Necesitamos revisar tu comprobante</p>
            </div>

            <div class="content">
                <p class="greeting">Hola {{ $order->user->name }},</p>

                <p class="message">
                    Hemos revisado tu comprobante de pago, pero lamentablemente no pudimos verificar la transferencia.
                    No te preocupes, estamos aquí para ayudarte a resolver esto rápidamente.
                </p>

                <div class="order-details">
                    <h3>📋 Detalles del Pedido</h3>
                    <div class="detail-row">
                        <strong>Número de Pedido:</strong>
                        <span>{{ $order->order_number }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Plan Seleccionado:</strong>
                        <span>{{ $order->plan->name }}</span>
                    </div>
                    <div class="detail-row">
                        <strong>Monto Esperado:</strong>
                        <span style="color: #dc2626; font-size: 20px; font-weight: 700;">
                            ₡{{ number_format($order->total, 0, ',', '.') }}
                        </span>
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
                        <li>Verifica que el monto transferido sea exactamente <strong>₡{{ number_format($order->total, 0, ',', '.') }}</strong></li>
                        <li>Confirma que la cuenta destino coincida con la información proporcionada</li>
                        <li>Revisa que el comprobante de pago sea legible y completo</li>
                        <li>Si el pago es correcto, contáctanos de inmediato para resolverlo</li>
                    </ul>
                </div>

                <div class="btn-container">
                    @php
                        $whatsappNumber = config('app.whatsapp_number', '50685307943');
                        $whatsappMessage = urlencode("Hola, tengo un problema con mi pedido {$order->order_number}. El pago fue rechazado y necesito ayuda para resolverlo.");
                    @endphp
                    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}" class="btn">
                        💬 Contactar por WhatsApp
                    </a>
                    <br>
                    <a href="mailto:info.qrpet@gmail.com?subject=Problema con pedido {{ $order->order_number }}" class="btn btn-email">
                        📧 Enviar Email
                    </a>
                </div>

                <div class="reassurance">
                    <p>
                        <strong>💚 Estamos aquí para ayudarte</strong><br>
                        Nuestro equipo está disponible para resolver cualquier inconveniente. No dudes en contactarnos,
                        responderemos lo más pronto posible para que puedas disfrutar de la protección QR Pet Tag.
                    </p>
                </div>
            </div>

            <div class="footer">
                <div class="footer-logo">🐾 QR Pet Tag</div>
                <p class="footer-text">Protección inteligente para tus mejores amigos</p>

                <div class="footer-contact">
                    <p><strong>¿Necesitas ayuda?</strong></p>
                    <p>📧 Email: <a href="mailto:info.qrpet@gmail.com">info.qrpet@gmail.com</a></p>
                    <p>⏰ Horario: Lunes a Viernes 8:00 AM - 6:00 PM</p>
                    <a href="https://wa.me/{{ config('app.whatsapp_number', '50685307943') }}?text={{ urlencode('Hola, necesito ayuda con mi cuenta de QR Pet Tag') }}" class="footer-btn">
                        💬 Contáctanos por WhatsApp
                    </a>
                </div>

                <p class="footer-text" style="margin-top: 25px; font-size: 12px; color: #718096;">
                    © {{ date('Y') }} QR Pet Tag. Todos los derechos reservados.<br>
                    Gracias por tu comprensión y paciencia 🐾
                </p>
            </div>
        </div>
    </div>
</body>
</html>
