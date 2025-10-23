<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; }
        .order-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .btn { display: inline-block; background: #4e89e8; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 10px 5px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">⚠️ Problema con el Pago</h1>
        </div>

        <div class="content">
            <p>Hola {{ $order->user->name }},</p>

            <p>Hemos revisado tu comprobante de pago, pero no pudimos verificar la transferencia.</p>

            <div class="order-details">
                <h3>Detalles del Pedido</h3>
                <div class="detail-row">
                    <strong>Pedido:</strong>
                    <span>{{ $order->order_number }}</span>
                </div>
                <div class="detail-row">
                    <strong>Plan:</strong>
                    <span>{{ $order->plan->name }}</span>
                </div>
                <div class="detail-row" style="border:none;">
                    <strong>Monto esperado:</strong>
                    <span style="color: #ef4444; font-size: 18px; font-weight: bold;">
                        ₡{{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            @if($order->admin_notes)
            <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <strong>Motivo:</strong><br>
                {{ $order->admin_notes }}
            </div>
            @endif

            <h3>¿Qué puedes hacer?</h3>
            <ul>
                <li>Verifica que el monto transferido sea exactamente <strong>₡{{ number_format($order->total, 0, ',', '.') }}</strong></li>
                <li>Confirma que la cuenta destino sea la correcta</li>
                <li>Si el pago es correcto, contáctanos de inmediato</li>
            </ul>

            <div style="text-align: center;">
                <a href="https://wa.me/50670000000?text=Hola,%20tengo%20un%20problema%20con%20mi%20pedido%20{{ $order->order_number }}" class="btn">
                    Contactar por WhatsApp
                </a>
                <a href="mailto:soporte@qrpettag.com?subject=Problema con pedido {{ $order->order_number }}" class="btn">
                    Enviar Email
                </a>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 30px;">
                Lamentamos las molestias. Nuestro equipo está disponible para ayudarte a resolver esto lo más pronto posible.
            </p>
        </div>

        <div class="footer">
            <p><strong>QR Pet Tag</strong></p>
            <p>Horario de atención: Lunes a Viernes 8:00 AM - 6:00 PM</p>
        </div>
    </div>
</body>
</html>
