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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 25px;
            border-radius: 10px;
            margin: 30px 0;
            border: 1px solid #e2e8f0;
        }
        .order-details h3 {
            color: #2d3748;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 700;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #cbd5e0;
            align-items: center;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-row strong {
            color: #4a5568;
            font-weight: 600;
        }
        .detail-row span {
            color: #2d3748;
            font-weight: 500;
        }
        .next-steps {
            margin: 30px 0;
        }
        .next-steps h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 700;
        }
        .next-steps ul {
            list-style: none;
            padding: 0;
        }
        .next-steps li {
            padding: 10px 0 10px 30px;
            position: relative;
            color: #4a5568;
            font-size: 15px;
        }
        .next-steps li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
            font-size: 18px;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white !important;
            padding: 16px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 8px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }
        .btn-whatsapp {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
        }
        .btn-whatsapp:hover {
            box-shadow: 0 6px 16px rgba(37, 211, 102, 0.4);
        }
        .note-box {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 6px;
            margin-top: 25px;
        }
        .note-box strong {
            color: #1e40af;
            display: block;
            margin-bottom: 8px;
        }
        .note-box p {
            color: #1e3a8a;
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
                <h1>🎉 ¡Pago Verificado!</h1>
                <p>Tu plan ha sido activado exitosamente</p>
            </div>

            <div class="content">
                <p class="greeting">¡Hola {{ $order->user->name }}!</p>

                <p class="message">
                    ¡Excelentes noticias! Hemos verificado tu pago y tu plan ha sido activado exitosamente.
                    Ahora puedes disfrutar de todas las ventajas de proteger a tus mascotas con QR Pet Tag.
                </p>

                <div class="order-details">
                    <h3>✨ Tu Plan Activo</h3>
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
                    @if($order->plan->type === 'subscription' && $order->expires_at)
                    <div class="detail-row">
                        <strong>Válido Hasta:</strong>
                        <span style="color: #10b981; font-weight: 700;">{{ $order->expires_at->format('d/m/Y') }}</span>
                    </div>
                    @endif
                </div>

                <div class="next-steps">
                    <h3>📋 Próximos Pasos</h3>
                    <p class="message">Ahora que tu plan está activo, te contactaremos pronto para:</p>
                    <ul>
                        <li>Coordinar el diseño de tus placas QR personalizadas</li>
                        <li>Verificar la información de tus mascotas</li>
                        <li>Organizar el envío a tu dirección</li>
                    </ul>
                </div>

                <div class="btn-container">
                    @php
                        $hasPets = $order->pets && $order->pets->count() > 0;
                        $whatsappNumber = config('app.whatsapp_number', '50685307943');
                        $whatsappMessage = urlencode("Hola, ya tengo mi pago verificado (Pedido: {$order->order_number}) y necesito ayuda para registrar mis mascotas. ¿Pueden asistirme?");
                    @endphp

                    @if($hasPets)
                        <a href="{{ route('portal.pets.index') }}" class="btn">
                            🐾 Ver Mis Mascotas ({{ $order->pets->count() }})
                        </a>
                    @else
                        <a href="{{ route('portal.pets.create') }}" class="btn">
                            ✨ Registrar Mis Mascotas
                        </a>
                        <br>
                        <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}" class="btn btn-whatsapp">
                            💬 Necesito Ayuda para Registrar
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
