{{-- resources/views/share/card.blade.php --}}
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=1080, initial-scale=1">
<title>Tarjeta – {{ $pet->name }}</title>
<style>
  :root{
    --bg:#0b1220; --panel:#0f1a33; --panel-2:#0c162b;
    --ink:#e6ecff; --muted:#9aa8c7;
    --chip:#0f1a33; --chip-stroke:#21335a;
    --brand:#93c5fd; --brand-2:#60a5fa; --accent:#3b82f6;
    --ok:#22c55e;
  }

  *{ box-sizing:border-box; }
  html,body{ margin:0; padding:0; width:1080px; height:1350px; background:var(--bg);
             font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; color:var(--ink); }

  .wrap{ padding:40px; width:100%; height:100%; display:flex; }
  .card{ background:linear-gradient(180deg, #0b1326 0%, #0c152b 100%);
         border:1px solid rgba(255,255,255,.06); border-radius:28px;
         box-shadow:0 30px 80px rgba(2,6,23,.25); padding:36px; width:100%;
         display:flex; flex-direction:column; gap:24px; }

  .photo{
    background:var(--panel-2);
    border:1px solid rgba(255,255,255,.06);
    border-radius:18px; padding:12px;
  }
  .photo img{ width:100%; height:720px; object-fit:cover; border-radius:12px; display:block; }

  .title{ display:flex; align-items:baseline; gap:16px; }
  .name{ font-size:88px; font-weight:800; letter-spacing:.4px; line-height:1.05; margin:0; }
  .meta{ font-size:36px; color:var(--brand); font-weight:700; }

  .chips{ display:flex; gap:14px; flex-wrap:wrap; }
  .chip{
    background:var(--chip); border:1px solid var(--chip-stroke); border-radius:12px;
    padding:.7rem 1.1rem; color:var(--brand); font-weight:800; font-size:28px;
  }

  .row{ display:grid; grid-template-columns:1fr; gap:14px; }

  .call{
    background:linear-gradient(180deg, #0d172b 0%, #0c1a33 100%);
    border:1px solid rgba(255,255,255,.08); border-radius:16px; padding:22px 24px;
    font-size:42px; font-weight:900; display:flex; align-items:center; gap:18px;
  }
  .call .phone{ color:#fff; }
  .mute{ color:var(--muted); }

  .brand{
    display:flex; align-items:center; justify-content:flex-end; gap:10px; color:#a5b4fc; font-weight:800;
    margin-top:auto; font-size:28px;
  }
  .brand img{ width:42px; height:42px; display:block; }
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">

      {{-- Foto principal --}}
      <div class="photo">
        <img src="{{ $photoSrc }}" alt="Foto de {{ $pet->name }}">
      </div>

      {{-- Nombre + meta (zona • edad) --}}
      <div class="title">
        <h1 class="name">{{ $pet->name }}</h1>
      </div>
      <div class="meta">
        {{ $pet->full_location ?: ($pet->zone ?: 'Zona no disponible') }}
        @if(!is_null($pet->age)) • {{ $pet->age }} {{ Str::plural('año', $pet->age) }} @endif
      </div>

      {{-- Chips --}}
      <div class="chips">
        <div class="chip">
          @php($sex = $pet->sex ?? 'unknown')
          {{ $sex === 'female' ? 'Hembra' : ($sex === 'male' ? 'Macho' : 'Sexo N/D') }}
        </div>
        <div class="chip">{{ ($pet->is_neutered ?? false) ? 'Esterilizada' : 'Sin esterilizar' }}</div>
        <div class="chip">{{ ($pet->rabies_vaccine ?? false) ? 'Antirrábica al día' : 'Antirrábica N/D' }}</div>
      </div>

      {{-- CTA: teléfono (si hay) --}}
      <div class="row">
        <div class="call">
          <span class="mute">Llamar:</span>
          <span class="phone">
            {{ $ownerPhone ? ('+' . $ownerPhone) : '—' }}
          </span>
        </div>
      </div>

      {{-- Branding --}}
      <div class="brand">
        @if($logoSrc)
          <img src="{{ $logoSrc }}" alt="QR PET TAG">
        @endif
        <span>QR PET TAG</span>
      </div>

    </div>
  </div>
</body>
</html>
