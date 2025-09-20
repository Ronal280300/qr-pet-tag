Hola {{ $ownerName }},

Alguien interactuó con el TAG de "{{ $pet->name }}" ({{ $whenLocal }}).

@isset($mapsUrl)
Mapa: {{ $mapsUrl }}
@if(!is_null($ping->accuracy))
Precisión aprox: ±{{ $ping->accuracy }} m
@endif
@endisset

@isset($locationHuman)
Ubicación aprox: {{ $locationHuman }}
@endisset

Fuente: {{ strtoupper($ping->source) }}
IP: {{ $ping->ip }}

— QR-Pet Tag
