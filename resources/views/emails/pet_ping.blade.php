<!doctype html>
<html lang="es">
  <body>
    <h2>¡Escanearon el QR de {{ $pet->name }}!</h2>

    <p>Alguien leyó el código QR del perfil público.</p>

    @if($ping->lat && $ping->lng)
      <p><strong>Ubicación aproximada:</strong>
         {{ $ping->city ?? '—' }}, {{ $ping->region ?? '—' }}, {{ $ping->country ?? '—' }}</p>
      <p><a href="{{ $maps }}" target="_blank" rel="noopener">Ver en Google Maps</a></p>
    @else
      <p>No pudimos obtener GPS. Se registró un acceso desde IP <code>{{ $ping->ip }}</code>.</p>
    @endif

    <hr>
    <p>Fecha: {{ $ping->created_at->timezone(config('app.timezone'))->format('Y-m-d H:i:s') }}</p>
  </body>
</html>
