<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #4e89e8, #0e61c6); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; }
        .order-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .timeline { margin: 20px 0; }
        .timeline-step { display: flex; align-items: flex-start; margin-bottom: 20px; }
        .timeline-icon { width: 40px; height: 40px; background: #4e89e8; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; flex-shrink: 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">✅ Comprobante Recibido</h1>
        </div>

        <div class="content">
            <p>Hola {{ $order->user->name }},</p>

            <p>¡Gracias por tu compra! Hemos recibido tu comprobante de pago exitosamente.</p>

            <div class="order-details">
                <h3>Detalles de tu Pedido</h3>
                <div class="detail-row">
                    <strong>Número de pedido:</strong>
                    <span>{{ $order->order_number }}</span>
                </div>
                <div class="detail-row">
                    <strong>Plan:</strong>
                    <span>{{ $order->plan->name }}</span>
                </div>
                <div class="detail-row">
                    <strong>Mascotas:</strong>
                    <span>{{ $order->pets_quantity }}</span>
                </div>
                <div class="detail-row" style="border:none;">
                    <strong>Total:</strong>
                    <span style="color: #4e89e8; font-size: 18px; font-weight: bold;">
                        ₡{{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <h3>¿Qué sigue ahora?</h3>
            <div class="timeline">
                <div class="timeline-step">
                    <div class="timeline-icon">✓</div>
                    <div>
                        <strong>Comprobante recibido</strong>
                        <p style="margin: 5px 0 0 0; color: #6b7280;">Tu pago está en proceso de verificación</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon" style="background: #9ca3af;">2</div>
                    <div>
                        <strong>Verificación (máx. 24h)</strong>
                        <p style="margin: 5px 0 0 0; color: #6b7280;">Confirmaremos que el pago se haya realizado</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon" style="background: #9ca3af;">3</div>
                    <div>
                        <strong>Personalización</strong>
                        <p style="margin: 5px 0 0 0; color: #6b7280;">Te contactaremos para diseñar tus placas QR</p>
                    </div>
                </div>
                <div class="timeline-step">
                    <div class="timeline-icon" style="background: #9ca3af;">4</div>
                    <div>
                        <strong>Envío</strong>
                        <p style="margin: 5px 0 0 0; color: #6b7280;">Recibirás tus placas en 3-5 días</p>
                    </div>
                </div>
            </div>

            <p style="background: #fff7ed; border-left: 4px solid #f59e0b; padding: 15px; border-radius: 4px;">
                <strong>💡 Tip:</strong> Mientras esperamos, puedes registrar la información de tus mascotas desde tu panel para agilizar el proceso.
            </p>
        </div>

        <div class="footer">
            <p><strong>QR Pet Tag</strong></p>
            <p>¿Necesitas ayuda? Escríbenos a soporte@qrpettag.com o por WhatsApp</p>
        </div>
    </div>
</body>
</html>
