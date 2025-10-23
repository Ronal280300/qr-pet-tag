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
        .btn { display: inline-block; background: #4e89e8; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">🎉 Nuevo Comprobante de Pago</h1>
        </div>

        <div class="content">
            <p>Hola Administrador,</p>

            <p>Un cliente ha subido su comprobante de pago. Por favor, verifica la transferencia y procede con la activación del plan.</p>

            <div class="order-details">
                <h3>Detalles del Pedido</h3>
                <div class="detail-row">
                    <strong>Número de pedido:</strong>
                    <span>{{ $order->order_number }}</span>
                </div>
                <div class="detail-row">
                    <strong>Cliente:</strong>
                    <span>{{ $order->user->name }} ({{ $order->user->email }})</span>
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
                    <span style="color: #4e89e8; font-size: 18px; font-weight: bold;">
                        ₡{{ number_format($order->total, 0, ',', '.') }}
                    </span>
                </div>
                <div class="detail-row" style="border:none;">
                    <strong>Fecha de envío:</strong>
                    <span>{{ $order->payment_uploaded_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('portal.admin.orders.show', $order->id) }}" class="btn">
                    Ver Pedido y Comprobante
                </a>
            </div>

            <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
                <strong>Nota:</strong> Recuerda verificar que el monto de la transferencia coincida con el total del pedido.
            </p>
        </div>

        <div class="footer">
            <p>QR Pet Tag - Sistema de Gestión</p>
            <p>Este es un correo automático, por favor no respondas.</p>
        </div>
    </div>
</body>
</html>
