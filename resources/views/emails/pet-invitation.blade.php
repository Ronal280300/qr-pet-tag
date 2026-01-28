<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invitación para gestionar tu mascota</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      background-color: #f5f7fa;
      padding: 20px;
      line-height: 1.6;
    }

    .email-container {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 40px 30px;
      text-align: center;
      color: white;
    }

    .header h1 {
      font-size: 28px;
      font-weight: 800;
      margin-bottom: 8px;
    }

    .header p {
      font-size: 16px;
      opacity: 0.95;
    }

    .pet-icon {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 40px;
      margin: 0 auto 20px;
    }

    .content {
      padding: 40px 30px;
    }

    .pet-info {
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      border: 2px solid #bfdbfe;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 30px;
    }

    .pet-info h2 {
      font-size: 24px;
      color: #1e40af;
      margin-bottom: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .pet-detail {
      display: flex;
      padding: 12px 0;
      border-bottom: 1px solid #bfdbfe;
    }

    .pet-detail:last-child {
      border-bottom: none;
    }

    .pet-detail-label {
      font-weight: 700;
      color: #1e3a8a;
      min-width: 100px;
    }

    .pet-detail-value {
      color: #475569;
    }

    .plan-box {
      background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
      border: 2px solid #fbbf24;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 30px;
      text-align: center;
    }

    .plan-box h3 {
      font-size: 18px;
      color: #78350f;
      margin-bottom: 8px;
    }

    .plan-price {
      font-size: 32px;
      font-weight: 800;
      color: #92400e;
      margin: 10px 0;
    }

    .cta-button {
      display: inline-block;
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white !important;
      text-decoration: none;
      padding: 18px 40px;
      border-radius: 12px;
      font-size: 18px;
      font-weight: 700;
      text-align: center;
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
      transition: all 0.3s ease;
      margin: 20px 0;
    }

    .cta-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    .steps {
      background: #f9fafb;
      border-radius: 12px;
      padding: 24px;
      margin: 30px 0;
    }

    .steps h3 {
      font-size: 18px;
      color: #1f2937;
      margin-bottom: 16px;
    }

    .step {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      margin-bottom: 16px;
      padding-bottom: 16px;
      border-bottom: 1px solid #e5e7eb;
    }

    .step:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .step-number {
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      flex-shrink: 0;
    }

    .step-text {
      flex: 1;
      color: #4b5563;
      font-size: 15px;
      padding-top: 4px;
    }

    .footer {
      background: #f9fafb;
      padding: 30px;
      text-align: center;
      border-top: 1px solid #e5e7eb;
    }

    .footer p {
      color: #6b7280;
      font-size: 14px;
      margin-bottom: 8px;
    }

    .footer a {
      color: #667eea;
      text-decoration: none;
    }

    .disclaimer {
      font-size: 12px;
      color: #9ca3af;
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #e5e7eb;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <!-- Header -->
    <div class="header">
      <div class="pet-icon">🐾</div>
      <h1>¡Te invitamos a gestionar tu mascota!</h1>
      <p>Hemos registrado una mascota para ti</p>
    </div>

    <!-- Content -->
    <div class="content">
      <!-- Pet Info -->
      <div class="pet-info">
        <h2>🐶 {{ $pet->name }}</h2>

        @if($pet->breed)
        <div class="pet-detail">
          <span class="pet-detail-label">Raza:</span>
          <span class="pet-detail-value">{{ $pet->breed }}</span>
        </div>
        @endif

        @if($pet->age_display)
        <div class="pet-detail">
          <span class="pet-detail-label">Edad:</span>
          <span class="pet-detail-value">{{ $pet->age_display }}</span>
        </div>
        @endif

        @if($pet->zone)
        <div class="pet-detail">
          <span class="pet-detail-label">Ubicación:</span>
          <span class="pet-detail-value">{{ $pet->zone }}</span>
        </div>
        @endif
      </div>

      <!-- Plan Info -->
      <div class="plan-box">
        <h3>📦 Plan incluido</h3>
        <div class="plan-price">{{ $planPrice }}</div>
        <p style="color: #78350f; font-weight: 600;">{{ $planName }}</p>
      </div>

      <!-- CTA Button -->
      <div style="text-align: center;">
        <a href="{{ $activationUrl }}" class="cta-button">
          ✨ Reclamar mi mascota
        </a>
      </div>

      <!-- Steps -->
      <div class="steps">
        <h3>📋 ¿Cómo funciona?</h3>

        <div class="step">
          <div class="step-number">1</div>
          <div class="step-text">
            Haz clic en el botón "Reclamar mi mascota"
          </div>
        </div>

        <div class="step">
          <div class="step-number">2</div>
          <div class="step-text">
            Si no tienes cuenta, completa el registro (es rápido)
          </div>
        </div>

        <div class="step">
          <div class="step-number">3</div>
          <div class="step-text">
            Tu mascota se ligará automáticamente a tu cuenta
          </div>
        </div>

        <div class="step">
          <div class="step-number">4</div>
          <div class="step-text">
            ¡Listo! Podrás gestionar toda la información de {{ $pet->name }}
          </div>
        </div>
      </div>

      <!-- Alternative link -->
      <p style="font-size: 14px; color: #6b7280; text-align: center; margin-top: 20px;">
        Si el botón no funciona, copia y pega este enlace en tu navegador:<br>
        <a href="{{ $activationUrl }}" style="color: #667eea; word-break: break-all;">{{ $activationUrl }}</a>
      </p>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p><strong>QR Pet Tag</strong></p>
      <p>Sistema de identificación y localización de mascotas</p>

      <div class="disclaimer">
        <p>Este enlace es válido por 30 días. Si tienes algún problema, contacta con nosotros.</p>
        <p>Este correo fue enviado a {{ $pet->pending_email }}</p>
      </div>
    </div>
  </div>
</body>
</html>
