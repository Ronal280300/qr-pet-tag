@component('mail::message')
# Aviso de lectura del QR

Se leyó el QR de **{{ $pet->name }}** {{ $ping->created_at->diffForHumans() }}.

- Método: **{{ strtoupper($ping->method ?? 'N/A') }}**
- Aproximación: {{ trim("{$ping->city} {$ping->region} {$ping->country}") ?: 'Sin datos' }}
@if($ping->address)
- Dirección estimada: {{ $ping->address }}
@endif
@if($maps)
@component('mail::button', ['url' => $maps])
Ver en Google Maps
@endcomponent
@endif

> Este aviso se limita a **2 notificaciones por hora** para evitar spam.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
