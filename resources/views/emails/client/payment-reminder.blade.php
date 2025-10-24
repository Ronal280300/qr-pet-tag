<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #ffc107, #ff9800); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; }
        .alert-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .plan-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .btn { display: inline-block; background: #ffc107; color: #000; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: 600; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">⏰ Recordatorio de Pago</h1>
        </div>

        <div class="content">
            <p>Hola {{ $user->name }},</p>

            <div class="alert-box">
                <strong>⚠️ Tu plan está por vencer</strong>
                <p style="margin: 10px 0 0 0;">
                    Este es un recordatorio amigable de que tu plan actual vence pronto.
                    Para continuar disfrutando de nuestros servicios sin interrupciones, por favor realiza tu renovación.
                </p>
            </div>

            <div class="plan-details">
                <h3>Detalles de tu Plan</h3>
                <div class="detail-row">
                    <strong>Plan Actual:</strong>
                    <span>{{ $plan->name }}</span>
                </div>
                @if($expiresAt)
                <div class="detail-row">
                    <strong>Fecha de Vencimiento:</strong>
                    <span style="color: #dc2626; font-weight: 600;">{{ $expiresAt->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row" style="border: none;">
                    <strong>Días Restantes:</strong>
                    <span style="color: #dc2626; font-weight: 600;">{{ max(0, $expiresAt->diffInDays(now())) }} días</span>
                </div>
                @endif
            </div>

            <h3>¿Cómo renovar?</h3>
            <ol>
                <li>Realiza la transferencia bancaria por el monto correspondiente</li>
                <li>Sube tu comprobante de pago en nuestro portal</li>
                <li>¡Listo! Verificaremos tu pago y activaremos tu renovación</li>
            </ol>

            <div style="text-align: center;">
                <a href="{{ route('plans.index') }}" class="btn">
                    Renovar Ahora
                </a>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                <strong>Nota:</strong> Si ya realizaste el pago, por favor ignora este mensaje.
                Tu renovación será procesada pronto.
            </p>
        </div>

        <div class="footer">
            <p><strong>QR Pet Tag</strong></p>
            <p>Protegiendo a tus mascotas 🐾</p>
            <p style="font-size: 11px; color: #9ca3af;">
                Si tienes dudas, contáctanos por WhatsApp o email
            </p>
        </div>
    </div>
</body>
</html>
