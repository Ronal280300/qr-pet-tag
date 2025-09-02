@php
  // 1) Tomamos el número desde donde esté disponible
  $rawPhone = $pet->owner_phone
           ?? ($pet->user->phone ?? null)
           ?? ($pet->contact_phone ?? null);

  // 2) Normalizamos a solo dígitos (sirve para tel: y wa.me)
  $digits = preg_replace('/\D+/', '', (string) $rawPhone);

  // 3) Mensaje para WhatsApp
  $waMessage = "Hola, encontré a {$pet->name}. Vi su perfil en QR-Pet Tag.";
@endphp

@if($digits)
  <div class="d-grid gap-3">
    <a class="btn btn-wa w-100"
       href="https://wa.me/{{ $digits }}?text={{ rawurlencode($waMessage) }}"
       target="_blank" rel="noopener">
       <i class="fa-brands fa-whatsapp"></i>
       Contactar por WhatsApp
    </a>

    <a class="btn btn-call w-100"
       href="tel:{{ $digits }}">
       <i class="fa-solid fa-phone"></i>
       Llamar
    </a>
  </div>
@else
  {{-- Si no hay teléfono, no mostramos los botones --}}
@endif
