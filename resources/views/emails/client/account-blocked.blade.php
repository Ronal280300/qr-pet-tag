<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #dc2626, #991b1b); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; }
        .alert-box { background: #fee2e2; border-left: 4px solid #dc2626; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .plan-details { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .btn { display: inline-block; background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin-top: 20px; font-weight: 600; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">⚠️ Cuenta Suspendida</h1>
        </div>

        <div class="content">
            <p>Hola {{ $user->name }},</p>

            <div class="alert-box">
                <strong>⛔ Tu cuenta ha sido suspendida</strong>
                <p style="margin: 10px 0 0 0;">
                    Lamentamos informarte que tu cuenta ha sido suspendida temporalmente debido a que
                    tu plan venció y no hemos recibido el pago de renovación.
                </p>
            </div>

            <div class="plan-details">
                <h3>Detalles del Plan Vencido</h3>
                <div class="detail-row">
                    <strong>Plan:</strong>
                    <span>{{ $plan->name }}</span>
                </div>
                <div class="detail-row">
                    <strong>Fecha de Vencimiento:</strong>
                    <span style="color: #dc2626; font-weight: 600;">{{ $expiredAt->format('d/m/Y') }}</span>
                </div>
                <div class="detail-row" style="border: none;">
                    <strong>Días vencidos:</strong>
                    <span style="color: #dc2626; font-weight: 600;">{{ $expiredAt->diffInDays(now()) }} días</span>
                </div>
            </div>

            <h3>¿Qué significa esto?</h3>
            <ul>
                <li>❌ No podrás acceder a ciertas funcionalidades de tu cuenta</li>
                <li>❌ Los servicios de tus mascotas están temporalmente desactivados</li>
                <li>✅ Tu información y datos están seguros</li>
            </ul>

            <h3>¿Cómo reactivar tu cuenta?</h3>
            <ol>
                <li>Realiza el pago de renovación de tu plan</li>
                <li>Sube el comprobante de pago en nuestro portal</li>
                <li>Tu cuenta será reactivada automáticamente al verificar el pago</li>
            </ol>

            <div style="text-align: center;">
                <a href="{{ route('plans.index') }}" class="btn">
                    Renovar Ahora y Reactivar
                </a>
            </div>

            <p style="margin-top: 30px; font-size: 14px; color: #6b7280;">
                <strong>Importante:</strong> Si ya realizaste el pago, por favor contáctanos de inmediato
                para reactivar tu cuenta manualmente mientras verificamos tu pago.
            </p>
        </div>

        <div class="footer">
            <p><strong>QR Pet Tag</strong></p>
            <p>Protegiendo a tus mascotas 🐾</p>
            <p style="font-size: 11px; color: #9ca3af;">
                Para reactivar tu cuenta urgentemente, contáctanos por WhatsApp o email
            </p>
        </div>
    </div>
</body>
</html>
