@php
$maps = ($ping->lat && $ping->lng)
    ? 'https://maps.google.com/?q='.$ping->lat.','.$ping->lng
    : null;
$when = $ping->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i');
@endphp

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>QR-Pet Tag</title>
</head>
<body style="margin:0;background:#f6f7fb;padding:24px;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;margin:auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 6px 18px rgba(0,0,0,.06)">
    <tr>
      <td style="background:#4f46e5;color:#fff;padding:18px 24px;font-size:18px;font-weight:700;">
        QR-Pet Tag
      </td>
    </tr>
    <tr>
      <td style="padding:24px">
        <h2 style="margin:0 0 8px 0;font-size:20px;color:#111827;">
          {{ $kind === 'upgrade' ? 'Ubicación más precisa disponible' : 'Se escaneó el QR de tu mascota' }}
        </h2>
        <p style="margin:0 0 16px 0;color:#374151;font-size:14px;line-height:1.5">
          Mascota: <strong>{{ $pet->name }}</strong><br>
          Fecha: {{ $when }}
        </p>

        @if($maps)
          <div style="margin:16px 0;padding:16px;border:1px solid #e5e7eb;border-radius:10px;background:#fafafa;color:#111827">
            <p style="margin:0 0 8px 0;font-size:14px">Coordenadas aproximadas:</p>
            <p style="margin:0 0 12px 0;font-family:ui-monospace,Consolas,monospace;font-size:13px">
              Lat: {{ number_format($ping->lat, 6) }},
              Lng: {{ number_format($ping->lng, 6) }}
              @if(!is_null($ping->accuracy))
                <span style="color:#6b7280"> (±{{ $ping->accuracy }} m)</span>
              @endif
            </p>
            <a href="{{ $maps }}" target="_blank"
               style="display:inline-block;background:#10b981;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:600">
              Ver en Google Maps
            </a>
          </div>
        @else
          <div style="margin:16px 0;padding:16px;border:1px solid #e5e7eb;border-radius:10px;background:#fafafa;color:#111827">
            <p style="margin:0">No se obtuvo GPS; este aviso se generó con la ubicación por IP.</p>
          </div>
        @endif

        <p style="margin:16px 0 0 0;color:#6b7280;font-size:12px">
          Fuente: {{ strtoupper($ping->source) }} — IP: {{ $ping->ip }}
        </p>
      </td>
    </tr>
    <tr>
      <td style="padding:14px 24px;color:#9ca3af;font-size:12px;background:#f9fafb">
        Recibirás como máximo 2 avisos por hora. Si este mensaje no corresponde, puedes ignorarlo.
      </td>
    </tr>
  </table>
</body>
</html>
