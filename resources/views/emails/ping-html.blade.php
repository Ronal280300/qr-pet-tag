@php
/** @var \App\Models\Pet $pet */
/** @var \App\Models\PetPing $ping */
@endphp
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>QR-Pet Tag</title>
</head>
<body style="margin:0;background:#f4f6fb;padding:24px;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Arial,sans-serif;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;margin:auto;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.08)">
    <tr>
      <td style="background:#4f46e5;color:#fff;padding:18px 24px;font-size:18px;font-weight:700;">
        🐾 QR-Pet Tag
      </td>
    </tr>

    <tr>
      <td style="padding:24px">
        <h2 style="margin:0 0 6px 0;font-size:20px;color:#111827;">
          {{ $isGps ? 'Ubicación precisa disponible' : 'Se escaneó el QR de tu mascota' }}
        </h2>
        <p style="margin:0 0 16px 0;color:#374151;font-size:14px;line-height:1.55">
          Hola <strong>{{ $ownerName }}</strong>,<br>
          alguien interactuó con el TAG de <strong>{{ $pet->name }}</strong> ({{ $whenLocal }}).
        </p>

        @if($mapsUrl)
          <div style="margin:16px 0;padding:16px;border:1px solid #e5e7eb;border-radius:12px;background:#fafafa;color:#111827">
            <p style="margin:0 0 10px 0;font-size:14px">
              {{ $isGps ? 'Coordenadas precisas:' : 'Coordenadas aproximadas:' }}
            </p>
            <p style="margin:0 0 12px 0;font-family:ui-monospace,Consolas,monospace;font-size:13px">
              Lat: {{ number_format($ping->lat, 6) }},
              Lng: {{ number_format($ping->lng, 6) }}
              @if(!is_null($ping->accuracy))
                <span style="color:#6b7280"> (±{{ $ping->accuracy }} m)</span>
              @endif
            </p>

            <a href="{{ $mapsUrl }}" target="_blank"
               style="display:inline-block;background:#10b981;color:#fff;text-decoration:none;padding:10px 14px;border-radius:10px;font-weight:700">
              Ver en Google Maps
            </a>
          </div>
        @else
          <div style="margin:16px 0;padding:16px;border:1px solid #e5e7eb;border-radius:12px;background:#fafafa;color:#111827">
            <p style="margin:0">No se obtuvo GPS; este aviso se generó con ubicación por IP.</p>
          </div>
        @endif

        @if($locationHuman)
          <p style="margin:12px 0 0 0;color:#374151;font-size:14px">
            📍 <strong>{{ $locationHuman }}</strong>
          </p>
        @endif

        <p style="margin:8px 0 0 0;color:#9ca3af;font-size:12px">
          Fuente: {{ strtoupper($ping->source) }} — IP: {{ $ping->ip }}
        </p>
      </td>
    </tr>

    <tr>
      <td style="padding:14px 24px;color:#9ca3af;font-size:12px;background:#f9fafb">
        Recibirás como máximo {{ (int)env('PING_MAX_MAILS_PER_HOUR', 2) }} avisos por hora.
        Si este mensaje no corresponde, puedes ignorarlo.
      </td>
    </tr>
  </table>
</body>
</html>
