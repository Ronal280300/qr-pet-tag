@extends('layouts.public')
@section('title', $pet->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pet-public.css') }}">
@endpush

@section('content')
<div class="page-wrap py-4">
  <div class="container">

    {{-- Avisos --}}
    @if($pet->is_lost)
      <div class="lost reveal mb-3 d-flex align-items-center">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        <strong>¡Mascota reportada como perdida!</strong>&nbsp;Si tienes información, por favor contacta a su dueño.
      </div>
    @endif

    @if(optional($pet->reward)->active)
      <div class="reward reveal mb-3 d-flex align-items-center">
        <span class="pulse"></span>
        <div>
          <strong>Recompensa activa.</strong>
          @if(optional($pet->reward)->amount)
            Monto: ₡{{ number_format((float)optional($pet->reward)->amount, 2) }}.
          @endif
          @if(optional($pet->reward)->message)
            <span class="d-block small mt-1">{{ optional($pet->reward)->message }}</span>
          @endif
        </div>
      </div>
    @endif

    <div class="hero reveal">
      <div class="row g-4 align-items-start">
        {{-- Foto + datos --}}
        <div class="col-lg-6">
          <div class="photo mb-3">
            @if($pet->photo)
              <img src="{{ asset('storage/'.$pet->photo) }}" alt="Foto de {{ $pet->name }}">
            @else
              <img src="https://images.unsplash.com/photo-1558944351-cbbdcc8c4fba?q=80&w=1400&auto=format&fit=crop" alt="Mascota">
            @endif
          </div>

          <div class="name mb-2">{{ $pet->name }}</div>

          <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="chip"><i class="fa-solid fa-dog"></i> {{ $pet->breed ?: 'Raza N/D' }}</span>
            <span class="chip"><i class="fa-solid fa-location-dot"></i> {{ $pet->zone ?: 'Zona N/D' }}</span>
            <span class="chip"><i class="fa-solid fa-cake-candles"></i> {{ $pet->age !== null ? $pet->age.' años' : 'Edad N/D' }}</span>
          </div>

          @if($pet->medical_conditions)
            <div class="p-3 rounded-3 bg-white border">
              <div class="fw-semibold mb-1"><i class="fa-solid fa-notes-medical me-1"></i> Condiciones médicas</div>
              <div class="text-muted">{{ $pet->medical_conditions }}</div>
            </div>
          @endif
        </div>

        {{-- Contacto (centrado) --}}
        <div class="col-lg-6">
          <div class="p-3 p-lg-4 rounded-3 bg-white border h-100 d-flex flex-column justify-content-between contact-card">
            <div>
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h4 class="mb-0">Contacto</h4>
                <span class="badge text-bg-light">TAG: <code>{{ $qr->slug }}</code></span>
              </div>

              @php
                $ownerName  = optional($owner)->name;
                $ownerPhone = optional($owner)->phone; // puede ser null
              @endphp

              @if($ownerName)
                <p class="mb-3"><strong>Dueño:</strong> {{ $ownerName }}</p>
              @endif

              @if($ownerPhone)
                <div class="d-flex flex-wrap gap-2">
                  <a id="btn-wa" class="btn btn-wa" target="_blank" href="#" data-phone="{{ $ownerPhone }}">
                    <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                  </a>
                  <a class="btn btn-soft" href="tel:{{ $ownerPhone }}">
                    <i class="fa-solid fa-phone me-1"></i> Llamar
                  </a>
                </div>
              @else
                <div class="alert alert-info mb-0">
                  El dueño aún no ha configurado un teléfono de contacto.
                </div>
              @endif
            </div>

            {{-- Acciones rápidas en móvil --}}
            <div class="cta-floating mt-3 d-lg-none">
              @if($ownerPhone)
                <div class="d-grid gap-2">
                  <a id="btn-wa-m" class="btn btn-wa" target="_blank" href="#" data-phone="{{ $ownerPhone }}">
                    <i class="fa-brands fa-whatsapp me-1"></i> Enviar WhatsApp
                  </a>
                  <a class="btn btn-soft" href="tel:{{ $ownerPhone }}">
                    <i class="fa-solid fa-phone me-1"></i> Llamar ahora
                  </a>
                </div>
              @endif
            </div>

          </div>
        </div>
      </div>
    </div>

    <div class="text-center text-muted small mt-3 reveal">
      Si encontraste a <strong>{{ $pet->name }}</strong>, ¡gracias por ayudar!
    </div>

  </div>
</div>

{{-- Scripts de la vista --}}
<script>
  // Aparición suave al hacer scroll
  (function(){
    const els = document.querySelectorAll('.reveal');
    const io = new IntersectionObserver(entries=>{
      entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); io.unobserve(e.target); } });
    }, {threshold:.12});
    els.forEach(el=>io.observe(el));
  })();

  // Construir enlace de WhatsApp en el cliente (sanitiza a dígitos)
  (function(){
    function setWaHref(btnId){
      const btn = document.getElementById(btnId);
      if(!btn) return;
      const raw = (btn.getAttribute('data-phone') || '').toString();
      const digits = raw.replace(/\D+/g, '');
      if(digits){
        btn.setAttribute('href', 'https://wa.me/' + digits);
      }else{
        btn.remove();
      }
    }
    setWaHref('btn-wa');
    setWaHref('btn-wa-m');
  })();
</script>
@endsection
