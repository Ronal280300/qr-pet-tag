@php
  $brand    = $brand ?? config('app.name','QR-Pet Tag');
  $minutes  = $minutes ?? 60;
@endphp
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restablecer contraseña</title>
  <style>
    /* Fallbacks básicos y dark-mode suave */
    @media (prefers-color-scheme: dark) {
      body { background:#0f172a !important; }
      .card { background:#111827 !important; color:#e5e7eb !important; }
      .muted { color:#94a3b8 !important; }
      .btn   { background:#3b82f6 !important; }
      .brand { color:#60a5fa !important; }
      a { color:#93c5fd !important; }
    }
  </style>
</head>
<body style="margin:0; padding:0; background:#f3f6fb;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f3f6fb;">
    <tr>
      <td align="center" style="padding:28px 12px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:640px;">
          <tr>
            <td align="center" style="padding:8px 0 20px;">
              <!-- Marca: solo texto limpio; si quieres imagen, cambia por <img> -->
              <div class="brand" style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; font-weight:800; font-size:22px; color:#1e7cf2; letter-spacing:.2px;">
                {{ $brand }}
              </div>
            </td>
          </tr>

          <tr>
            <td class="card" style="background:#ffffff; border-radius:14px; padding:28px 28px; box-shadow:0 20px 60px rgba(2,6,23,.06); font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; color:#0f172a;">
              
              <h1 style="margin:0 0 14px; font-size:20px; line-height:1.25; font-weight:800;">Restablecer tu contraseña</h1>

              <p style="margin:0 0 10px; font-size:15px; line-height:1.55;">
                Hola {{ $user->name ?? 'usuario' }}, hemos recibido una solicitud para
                restablecer la contraseña de tu cuenta.
              </p>

              <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:18px 0 10px;">
                <tr>
                  <td>
                    <a href="{{ $url }}" class="btn"
                       style="display:inline-block; background:#115dfc; color:#ffffff; text-decoration:none; font-weight:700; padding:12px 18px; border-radius:10px;">
                      Restablecer contraseña
                    </a>
                  </td>
                </tr>
              </table>

              <p class="muted" style="margin:6px 0 16px; color:#6b7280; font-size:14px; line-height:1.55;">
                Este enlace de restablecimiento <strong style="color:#111827;">expira en {{ $minutes }} minutos</strong>.
                Si tú no solicitaste este cambio, puedes ignorar este correo.
              </p>

              <hr style="border:0; border-top:1px solid #eef2f7; margin:16px 0 12px;">

              <p class="muted" style="margin:0 0 8px; color:#6b7280; font-size:13px; line-height:1.55;">
                Si tienes problemas para hacer clic en el botón, copia y pega esta URL en tu navegador:
              </p>
              <p style="margin:0; font-size:13px; word-break:break-all;">
                <a href="{{ $url }}" style="color:#1e7cf2; text-decoration:underline;">{{ $url }}</a>
              </p>

            </td>
          </tr>

          <tr>
            <td align="center" style="padding:16px 6px; color:#94a3b8; font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif; font-size:12px;">
              © {{ date('Y') }} {{ $brand }}. Todos los derechos reservados.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
