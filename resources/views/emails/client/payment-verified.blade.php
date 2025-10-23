<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; }
        .order-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .btn { display: inline-block; background: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">🎉 ¡Pago Verificado!</h1>
        </div>

        <div class="content">
            <p>Hola {{ $order->user->name }},</p>

            <p>¡Excelentes noticias! Hemos verificado tu pago y tu plan ha sido activado exitosamente.</p>

            <div class="order-details">
                <h3>Tu Plan Activo</h3>
                <div class="detail-row">
                    <strong>Pedido:</strong>
                    <span>{{ $order->order_number }}</span>
                </div>
                <div class="detail-row">
                    <strong>Plan:</strong>
                    <span>{{ $order->plan->name }}</span>
                </div>
                <div class="detail-row">
                    <strong>Mascotas incluidas:</strong>
                    <span>{{ $order->pets_quantity }}</span>
                </div>
                @if($order->plan->type === 'subscription' && $order->expires_at)
                <div class="detail-row" style="border:none;">
                    <strong>Válido hasta:</strong>
                    <span>{{ $order->expires_at->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>

            <h3>Próximos Pasos</h3>
            <p>Ahora que tu plan está activo, te contactaremos pronto para:</p>
            <ul>
                <li>📱 Coordinar el diseño de tus placas QR personalizadas</li>
                <li>🐾 Verificar la información de tus mascotas</li>
                <li>📦 Organizar el envío a tu dirección</li>
            </ul>

            <div style="text-align: center;">
                <a href="{{ route('portal.pets.create') }}" class="btn">
                    Registrar Mis Mascotas
                </a>
            </div>

            @if($order->admin_notes)
            <p style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px; margin-top: 20px;">
                <strong>Nota del equipo:</strong><br>
                {{ $order->admin_notes }}
            </p>
            @endif
        </div>

        <div class="footer">
            <p><strong>QR Pet Tag</strong></p>
            <p>Gracias por confiar en nosotros para proteger a tus mascotas 🐾</p>
        </div>
    </div>
</body>
</html>
