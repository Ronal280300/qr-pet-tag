<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Encuesta - Protege a tu Mascota</title>
  <meta name="description" content="Ayudanos a crear una mejor solucion para proteger y encontrar mascotas perdidas.">

  <link rel="icon" href="{{ asset('brand/qr-pet-tag-logo.png') }}" type="image/png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  <style>
    /* ── TOKENS ─────────────────────────────────────── */
    :root {
      --primary:      #2563eb;
      --primary-dk:   #1d4ed8;
      --primary-bg:   #eff6ff;
      --success:      #16a34a;
      --success-bg:   #f0fdf4;
      --ink:          #0f172a;
      --ink2:         #334155;
      --muted:        #64748b;
      --light:        #94a3b8;
      --border:       #e2e8f0;
      --border-dk:    #cbd5e1;
      --bg:           #f8fafc;
      --surface:      #ffffff;
      --r:            10px;
      --shadow:       0 1px 3px rgba(15,23,42,.06), 0 8px 24px rgba(15,23,42,.04);
      --font:         'Inter', system-ui, -apple-system, sans-serif;
    }

    /* ── RESET ──────────────────────────────────────── */
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: var(--font);
      background: var(--bg);
      color: var(--ink);
      min-height: 100vh;
      -webkit-font-smoothing: antialiased;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 32px 16px 48px;
    }

    /* ── HEADER ─────────────────────────────────────── */
    .sv-header {
      width: 100%; max-width: 680px;
      display: flex; align-items: center; gap: 12px;
      padding-bottom: 20px;
      margin-bottom: 24px;
      border-bottom: 1px solid var(--border);
    }
    .sv-logo {
      width: 44px; height: 44px;
      border-radius: 12px;
      object-fit: contain;
    }
    .sv-header-info h1 {
      font-size: 17px; font-weight: 700; color: var(--ink);
    }
    .sv-header-info p {
      font-size: 12px; color: var(--muted); font-weight: 500;
    }

    /* ── CARD PRINCIPAL ────────────────────────────── */
    .sv-card {
      width: 100%; max-width: 680px;
      background: var(--surface);
      border-radius: var(--r);
      border: 1px solid var(--border);
      box-shadow: var(--shadow);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      min-height: 500px;
    }

    /* ── PROGRESO ───────────────────────────────────── */
    .sv-progress {
      padding: 20px 28px 0;
      display: none;
    }
    .sv-progress.show { display: block; }
    .sv-progress-top {
      display: flex; justify-content: space-between;
      font-size: 12px; font-weight: 600; color: var(--muted);
      margin-bottom: 8px;
    }
    .sv-progress-track {
      height: 5px; background: var(--border); border-radius: 99px; overflow: hidden;
    }
    .sv-progress-bar {
      height: 100%; background: var(--primary); border-radius: 99px;
      transition: width .4s ease; width: 0%;
    }

    /* ── VISTAS ─────────────────────────────────────── */
    .sv-view {
      padding: 28px;
      flex: 1;
      display: none;
      flex-direction: column;
      animation: viewIn .35s ease both;
    }
    .sv-view.active { display: flex; }
    @keyframes viewIn {
      from { opacity: 0; transform: translateY(10px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── INTRO ─────────────────────────────────────── */
    .sv-intro-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--primary-bg); color: var(--primary);
      font-size: 12px; font-weight: 700;
      padding: 5px 12px; border-radius: 99px;
      margin-bottom: 16px; width: fit-content;
    }
    .sv-intro-title {
      font-size: 24px; font-weight: 800; color: var(--ink);
      line-height: 1.25; margin-bottom: 12px;
    }
    .sv-intro-desc {
      font-size: 15px; color: var(--ink2); line-height: 1.65;
      margin-bottom: 28px;
    }

    /* ── HOW IT WORKS (Animación) ──────────────────── */
    .sv-how {
      background: var(--bg);
      border: 1px solid var(--border);
      border-radius: var(--r);
      padding: 24px;
      margin-bottom: 28px;
    }
    .sv-how-title {
      font-size: 13px; font-weight: 700; color: var(--muted);
      text-transform: uppercase; letter-spacing: .06em;
      margin-bottom: 20px;
    }
    .sv-how-steps {
      display: flex; flex-direction: column; gap: 0;
      position: relative;
    }
    .sv-how-step {
      display: flex; align-items: flex-start; gap: 16px;
      position: relative;
      padding-bottom: 24px;
      opacity: 0;
      transform: translateX(-12px);
      animation: stepReveal .5s ease forwards;
    }
    .sv-how-step:nth-child(1) { animation-delay: .3s; }
    .sv-how-step:nth-child(2) { animation-delay: .7s; }
    .sv-how-step:nth-child(3) { animation-delay: 1.1s; }
    .sv-how-step:last-child { padding-bottom: 0; }
    @keyframes stepReveal {
      to { opacity: 1; transform: translateX(0); }
    }

    /* Línea vertical conectora */
    .sv-how-step:not(:last-child)::after {
      content: '';
      position: absolute;
      left: 19px; top: 42px;
      width: 2px;
      height: calc(100% - 42px);
      background: var(--border-dk);
    }

    .sv-how-num {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: var(--primary);
      color: #fff;
      display: grid; place-items: center;
      font-size: 16px; font-weight: 700;
      flex-shrink: 0;
      position: relative;
      z-index: 1;
    }
    .sv-how-content {
      flex: 1; padding-top: 2px;
    }
    .sv-how-content strong {
      display: block;
      font-size: 14px; font-weight: 700; color: var(--ink);
      margin-bottom: 3px;
    }
    .sv-how-content span {
      font-size: 13px; color: var(--muted); line-height: 1.5;
    }

    /* Ícono animado dentro del círculo */
    .sv-how-num i {
      animation: iconPulse 2s ease-in-out infinite;
    }
    @keyframes iconPulse {
      0%, 100% { transform: scale(1); }
      50%      { transform: scale(1.15); }
    }

    /* ── DATOS DEL INTRO ───────────────────────────── */
    .sv-intro-meta {
      display: flex; gap: 20px; flex-wrap: wrap;
      margin-bottom: 28px;
    }
    .sv-meta-item {
      display: flex; align-items: center; gap: 6px;
      font-size: 12px; font-weight: 600; color: var(--muted);
    }
    .sv-meta-item i { color: var(--primary); font-size: 12px; }

    /* ── PREGUNTA ───────────────────────────────────── */
    .sv-q {
      font-size: 18px; font-weight: 700; color: var(--ink);
      line-height: 1.35; margin-bottom: 6px;
    }
    .sv-q-hint {
      font-size: 13px; color: var(--muted); margin-bottom: 24px;
    }

    /* ── OPCIONES ───────────────────────────────────── */
    .sv-opts { display: flex; flex-direction: column; gap: 8px; }
    .sv-opts.cols-2 {
      display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
    }

    .sv-opt {
      position: relative; cursor: pointer;
      border: 1.5px solid var(--border-dk);
      border-radius: var(--r);
      padding: 14px 16px;
      display: flex; align-items: center; gap: 14px;
      background: #fff;
      transition: all .18s ease;
    }
    .sv-opt:hover {
      border-color: var(--primary);
      background: var(--primary-bg);
    }
    .sv-opt.on {
      border-color: var(--primary);
      background: var(--primary-bg);
      box-shadow: inset 0 0 0 1px var(--primary);
    }
    .sv-opt input[type="radio"] {
      position: absolute; opacity: 0; pointer-events: none;
    }

    .sv-opt-ico {
      width: 34px; height: 34px;
      border-radius: 8px;
      background: var(--bg);
      color: var(--muted);
      display: grid; place-items: center;
      font-size: 14px; flex-shrink: 0;
      transition: all .18s;
    }
    .sv-opt.on .sv-opt-ico {
      background: var(--primary); color: #fff;
    }
    .sv-opt-label {
      flex: 1; font-size: 14px; font-weight: 600; color: var(--ink2);
    }
    .sv-opt-sub {
      font-size: 12px; font-weight: 400; color: var(--muted); margin-top: 2px;
    }
    .sv-opt-dot {
      width: 20px; height: 20px;
      border-radius: 50%;
      border: 2px solid var(--border-dk);
      flex-shrink: 0;
      position: relative;
      transition: all .18s;
    }
    .sv-opt.on .sv-opt-dot {
      border-color: var(--primary);
      background: var(--primary);
    }
    .sv-opt.on .sv-opt-dot::after {
      content: '';
      position: absolute;
      top: 4px; left: 4px;
      width: 8px; height: 8px;
      border-radius: 50%;
      background: #fff;
    }

    /* ── SLIDER ─────────────────────────────────────── */
    .sv-slider { text-align: center; margin: 8px 0 28px; }
    .sv-slider-val {
      font-size: 52px; font-weight: 800;
      color: var(--primary); line-height: 1;
      font-variant-numeric: tabular-nums;
    }
    .sv-slider-lbl {
      font-size: 13px; font-weight: 600;
      color: var(--muted); margin: 4px 0 18px;
    }
    .sv-range {
      -webkit-appearance: none; width: 100%;
      height: 6px; background: var(--border);
      border-radius: 99px; outline: none;
    }
    .sv-range::-webkit-slider-thumb {
      -webkit-appearance: none;
      width: 24px; height: 24px;
      border-radius: 50%;
      background: #fff;
      border: 2.5px solid var(--primary);
      cursor: pointer;
      box-shadow: 0 2px 6px rgba(0,0,0,.12);
    }
    .sv-range::-moz-range-thumb {
      width: 24px; height: 24px;
      border-radius: 50%;
      background: #fff;
      border: 2.5px solid var(--primary);
      cursor: pointer;
    }
    .sv-range-ends {
      display: flex; justify-content: space-between;
      font-size: 11px; font-weight: 600; color: var(--light);
      margin-top: 8px;
    }

    /* ── INPUT ──────────────────────────────────────── */
    .sv-input-grp {
      margin-top: 24px; border-top: 1px solid var(--border); padding-top: 20px;
    }
    .sv-input {
      width: 100%; padding: 12px 16px;
      border: 1.5px solid var(--border-dk);
      border-radius: var(--r);
      font-family: var(--font);
      font-size: 14px; color: var(--ink);
      outline: none; transition: all .18s;
    }
    .sv-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-bg);
    }
    .sv-input-note {
      font-size: 11px; color: var(--light); margin-top: 6px;
      display: flex; align-items: center; gap: 5px;
    }

    /* ── NAV ────────────────────────────────────────── */
    .sv-nav {
      margin-top: auto; padding-top: 24px;
      display: flex; justify-content: space-between;
      align-items: center; gap: 12px;
    }
    .sv-btn {
      padding: 12px 24px; border-radius: var(--r);
      font-size: 14px; font-weight: 700;
      font-family: var(--font);
      cursor: pointer; border: none;
      display: inline-flex; align-items: center; gap: 8px;
      transition: all .2s; text-decoration: none;
    }
    .sv-btn-p { background: var(--primary); color: #fff; }
    .sv-btn-p:hover:not(:disabled) { background: var(--primary-dk); }
    .sv-btn-p:disabled { opacity: .45; cursor: not-allowed; }
    .sv-btn-o {
      background: transparent; color: var(--muted);
      border: 1.5px solid var(--border-dk);
    }
    .sv-btn-o:hover { border-color: var(--muted); color: var(--ink2); }
    .sv-btn-s { background: var(--success); color: #fff; }
    .sv-btn-s:hover:not(:disabled) { background: #15803d; }

    /* ── SUCCESS ────────────────────────────────────── */
    .sv-done {
      text-align: center; padding: 48px 24px;
    }
    .sv-done-ico {
      width: 72px; height: 72px;
      background: var(--success-bg);
      color: var(--success);
      border-radius: 50%;
      display: inline-grid; place-items: center;
      font-size: 30px; margin-bottom: 20px;
      animation: pop .5s .1s cubic-bezier(.34,1.56,.64,1) both;
    }
    @keyframes pop {
      from { transform: scale(0); } to { transform: scale(1); }
    }
    .sv-done h2 {
      font-size: 22px; font-weight: 800; margin-bottom: 10px;
    }
    .sv-done p {
      font-size: 15px; color: var(--muted); line-height: 1.6;
      max-width: 400px; margin: 0 auto 24px;
    }

    /* ── ERROR ──────────────────────────────────────── */
    .sv-err {
      background: #fef2f2; border: 1px solid #fecaca;
      color: #b91c1c; padding: 10px 16px;
      border-radius: var(--r); font-size: 13px; font-weight: 500;
      display: none; align-items: center; gap: 8px;
      margin: 0 28px 0;
    }

    /* ── RESPONSIVE ────────────────────────────────── */
    @media (max-width: 600px) {
      body { padding: 16px 10px 40px; }
      .sv-view { padding: 22px 18px; }
      .sv-opts.cols-2 { grid-template-columns: 1fr; }
      .sv-nav { flex-direction: column-reverse; }
      .sv-btn { width: 100%; justify-content: center; }
      .sv-intro-title { font-size: 20px; }
      .sv-slider-val { font-size: 40px; }
      .sv-how { padding: 18px; }
    }
  </style>
</head>
<body>

{{-- HEADER CON LOGO --}}
<header class="sv-header">
  <img src="{{ asset('brand/qr-pet-tag-logo.png') }}" alt="Logo" class="sv-logo">
  <div class="sv-header-info">
    <h1>Protege a tu Mascota</h1>
    <p>Encuesta de investigacion</p>
  </div>
</header>

{{-- CARD PRINCIPAL --}}
<main class="sv-card">

  {{-- Progreso --}}
  <div class="sv-progress" id="progressWrap">
    <div class="sv-progress-top">
      <span>Progreso</span>
      <span id="pText">1 / 7</span>
    </div>
    <div class="sv-progress-track">
      <div class="sv-progress-bar" id="pBar"></div>
    </div>
  </div>

  {{-- Error --}}
  <div class="sv-err" id="errBox"></div>

  <form id="surveyForm" novalidate>
    <input type="hidden" name="source" value="{{ $source ?? 'direct' }}">

    {{-- ═══ PASO 0: INTRO + ANIMACION ═══════════════════════════ --}}
    <div class="sv-view active" data-step="0">
      <div class="sv-intro-badge">
        <i class="fa-solid fa-clipboard-list"></i> Encuesta rapida
      </div>

      <h2 class="sv-intro-title">Ayudanos a proteger mejor a las mascotas</h2>
      <p class="sv-intro-desc">
        Estamos creando un sistema que <strong>ayuda a encontrar mascotas perdidas</strong>
        y <strong>notifica al dueño de inmediato</strong> cuando alguien la encuentra.
        <br><br>
        Antes de lanzarlo, queremos saber tu opinion para hacer un producto que realmente sirva.
      </p>

      {{-- COMO FUNCIONA - Animacion de pasos --}}
<div class="sv-how">
  <div class="sv-how-title">Así funciona PetScan</div>
  <div class="sv-how-steps">

    <div class="sv-how-step">
      <div class="sv-how-num"><i class="fa-solid fa-tag"></i></div>
      <div class="sv-how-content">
        <strong>Tu mascota lleva un tag inteligente PetScan</strong>
        <span>Es un tag resistente que se coloca en el collar de tu mascota y contiene tecnología NFC para identificarla de forma rápida y segura.</span>
      </div>
    </div>

    <div class="sv-how-step">
      <div class="sv-how-num"><i class="fa-solid fa-mobile-screen-button"></i></div>
      <div class="sv-how-content">
        <strong>Quien la encuentre solo acerca su teléfono</strong>
        <span>La persona únicamente necesita acercar su celular al tag NFC para abrir la información de la mascota. En algunos casos, también puede incluir QR como respaldo.</span>
      </div>
    </div>

    <div class="sv-how-step">
      <div class="sv-how-num"><i class="fa-solid fa-shield-dog"></i></div>
      <div class="sv-how-content">
        <strong>Se muestra su perfil y la forma de contactarte</strong>
        <span>Al instante se visualiza el perfil de la mascota con la información importante y los datos de contacto que definiste, facilitando que pueda regresar a casa más rápido.</span>
      </div>
    </div>

  </div>
</div>

      <div class="sv-intro-meta">
        <div class="sv-meta-item"><i class="fa-regular fa-clock"></i> Menos de 2 minutos</div>
        <div class="sv-meta-item"><i class="fa-solid fa-lock"></i> Respuestas anonimas</div>
        <div class="sv-meta-item"><i class="fa-solid fa-list-check"></i> 7 preguntas</div>
      </div>

      <div class="sv-nav" style="justify-content:flex-end;">
        <button type="button" class="sv-btn sv-btn-p btn-start">
          Empezar <i class="fa-solid fa-arrow-right"></i>
        </button>
      </div>
    </div>

    {{-- ═══ PASO 1 ═══════════════════════════════════════════════ --}}
    <div class="sv-view" data-step="1">
      <h3 class="sv-q">¿Tienes mascota actualmente?</h3>
      <p class="sv-q-hint">Selecciona la opcion que mejor te describa.</p>
      <div class="sv-opts cols-2">
        <label class="sv-opt">
          <input type="radio" name="has_pets" value="si">
          <div class="sv-opt-ico"><i class="fa-solid fa-check"></i></div>
          <div class="sv-opt-label">Si, tengo mascota</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="has_pets" value="no">
          <div class="sv-opt-ico"><i class="fa-solid fa-heart"></i></div>
          <div class="sv-opt-label">No, pero me gustaria</div>
          <div class="sv-opt-dot"></div>
        </label>
      </div>
      <div class="sv-nav">
        <button type="button" class="sv-btn sv-btn-o btn-prev"><i class="fa-solid fa-arrow-left"></i> Atras</button>
        <button type="button" class="sv-btn sv-btn-p btn-next" disabled>Siguiente <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- ═══ PASO 2 ═══════════════════════════════════════════════ --}}
    <div class="sv-view" data-step="2">
      <h3 class="sv-q">¿Que tipo de mascota tienes o te gustaria tener?</h3>
      <p class="sv-q-hint">Elige una opcion.</p>
      <div class="sv-opts cols-2">
        <label class="sv-opt">
          <input type="radio" name="pet_type" value="perro">
          <div class="sv-opt-ico"><i class="fa-solid fa-dog"></i></div>
          <div class="sv-opt-label">Perro</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="pet_type" value="gato">
          <div class="sv-opt-ico"><i class="fa-solid fa-cat"></i></div>
          <div class="sv-opt-label">Gato</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="pet_type" value="ambos">
          <div class="sv-opt-ico"><i class="fa-solid fa-paw"></i></div>
          <div class="sv-opt-label">Tengo perro y gato</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="pet_type" value="otro">
          <div class="sv-opt-ico"><i class="fa-solid fa-dove"></i></div>
          <div class="sv-opt-label">Otro animal</div>
          <div class="sv-opt-dot"></div>
        </label>
      </div>
      <div class="sv-nav">
        <button type="button" class="sv-btn sv-btn-o btn-prev"><i class="fa-solid fa-arrow-left"></i> Atras</button>
        <button type="button" class="sv-btn sv-btn-p btn-next" disabled>Siguiente <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- ═══ PASO 3 ═══════════════════════════════════════════════ --}}
    <div class="sv-view" data-step="3">
      <h3 class="sv-q">¿Que es lo que mas te preocupa de tu mascota?</h3>
      <p class="sv-q-hint">Escoge lo que mas te quite el sueno.</p>
      <div class="sv-opts">
        <label class="sv-opt">
          <input type="radio" name="main_concern" value="se_pierda">
          <div class="sv-opt-ico"><i class="fa-solid fa-location-crosshairs"></i></div>
          <div class="sv-opt-label">
            Que se pierda o se escape
            <div class="sv-opt-sub">No saber donde esta ni como encontrarla</div>
          </div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="main_concern" value="salud">
          <div class="sv-opt-ico"><i class="fa-solid fa-heart-pulse"></i></div>
          <div class="sv-opt-label">
            Que tenga una emergencia de salud
            <div class="sv-opt-sub">Que nadie sepa sus datos medicos si le pasa algo</div>
          </div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="main_concern" value="identificacion">
          <div class="sv-opt-ico"><i class="fa-solid fa-id-card"></i></div>
          <div class="sv-opt-label">
            Que no tenga identificacion
            <div class="sv-opt-sub">Si alguien la encuentra, no sabe de quien es</div>
          </div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="main_concern" value="robo">
          <div class="sv-opt-ico"><i class="fa-solid fa-shield-halved"></i></div>
          <div class="sv-opt-label">
            Que me la roben
            <div class="sv-opt-sub">No poder demostrar que es mia</div>
          </div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="main_concern" value="otro">
          <div class="sv-opt-ico"><i class="fa-solid fa-ellipsis"></i></div>
          <div class="sv-opt-label">Otra cosa</div>
          <div class="sv-opt-dot"></div>
        </label>
      </div>
      <div class="sv-nav">
        <button type="button" class="sv-btn sv-btn-o btn-prev"><i class="fa-solid fa-arrow-left"></i> Atras</button>
        <button type="button" class="sv-btn sv-btn-p btn-next" disabled>Siguiente <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- ═══ PASO 4 ═══════════════════════════════════════════════ --}}
    <div class="sv-view" data-step="4">
      <h3 class="sv-q">¿Alguna vez se te ha perdido una mascota?</h3>
      <p class="sv-q-hint">Tambien cuenta si le paso a alguien cercano.</p>
      <div class="sv-opts">
        <label class="sv-opt">
          <input type="radio" name="lost_pet_before" value="si">
          <div class="sv-opt-ico"><i class="fa-solid fa-circle-exclamation"></i></div>
          <div class="sv-opt-label">Si, a mi me paso</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="lost_pet_before" value="no">
          <div class="sv-opt-ico"><i class="fa-solid fa-shield"></i></div>
          <div class="sv-opt-label">No, nunca me ha pasado</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="lost_pet_before" value="conozco_alguien">
          <div class="sv-opt-ico"><i class="fa-solid fa-users"></i></div>
          <div class="sv-opt-label">A mi no, pero a alguien que conozco si</div>
          <div class="sv-opt-dot"></div>
        </label>
      </div>
      <div class="sv-nav">
        <button type="button" class="sv-btn sv-btn-o btn-prev"><i class="fa-solid fa-arrow-left"></i> Atras</button>
        <button type="button" class="sv-btn sv-btn-p btn-next" disabled>Siguiente <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- ═══ PASO 5 ═══════════════════════════════════════════════ --}}
    <div class="sv-view" data-step="5">
      <h3 class="sv-q">Si existiera este sistema para encontrar mascotas perdidas, ¿lo comprarias?</h3>
      <p class="sv-q-hint">Una plaquita con codigo QR que avisa al dueno cuando alguien la escanea.</p>
      <div class="sv-opts">
        <label class="sv-opt">
          <input type="radio" name="would_buy" value="definitivamente_si">
          <div class="sv-opt-ico"><i class="fa-solid fa-check-double"></i></div>
          <div class="sv-opt-label">Si, seguro lo compraria</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="would_buy" value="probablemente_si">
          <div class="sv-opt-ico"><i class="fa-solid fa-thumbs-up"></i></div>
          <div class="sv-opt-label">Probablemente si</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="would_buy" value="no_estoy_seguro">
          <div class="sv-opt-ico"><i class="fa-solid fa-minus"></i></div>
          <div class="sv-opt-label">No estoy seguro todavia</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="would_buy" value="probablemente_no">
          <div class="sv-opt-ico"><i class="fa-solid fa-thumbs-down"></i></div>
          <div class="sv-opt-label">Creo que no</div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="would_buy" value="definitivamente_no">
          <div class="sv-opt-ico"><i class="fa-solid fa-xmark"></i></div>
          <div class="sv-opt-label">No, no me interesa</div>
          <div class="sv-opt-dot"></div>
        </label>
      </div>
      <div class="sv-nav">
        <button type="button" class="sv-btn sv-btn-o btn-prev"><i class="fa-solid fa-arrow-left"></i> Atras</button>
        <button type="button" class="sv-btn sv-btn-p btn-next" disabled>Siguiente <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- ═══ PASO 6 ═══════════════════════════════════════════════ --}}
    <div class="sv-view" data-step="6">
      <h3 class="sv-q">Si existiera este sistema, ¿cómo preferirías pagarlo?</h3>
      <p class="sv-q-hint">Elige la forma de pago que más te conviene.</p>
      <div class="sv-opts">
        <label class="sv-opt">
          <input type="radio" name="price_range" value="pago_unico">
          <div class="sv-opt-ico"><i class="fa-solid fa-hand-holding-dollar"></i></div>
          <div class="sv-opt-label">
            Un solo pago
            <div class="sv-opt-sub">Lo pago una vez y lo uso para siempre.</div>
          </div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="price_range" value="pago_anual">
          <div class="sv-opt-ico"><i class="fa-solid fa-calendar-check"></i></div>
          <div class="sv-opt-label">
            Pago anual
            <div class="sv-opt-sub">Pago una vez al año para mantenerlo activo.</div>
          </div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="price_range" value="suscripcion_mensual">
          <div class="sv-opt-ico"><i class="fa-solid fa-arrows-rotate"></i></div>
          <div class="sv-opt-label">
            Suscripción mensual
            <div class="sv-opt-sub">Pago una cuota baja cada mes.</div>
          </div>
          <div class="sv-opt-dot"></div>
        </label>
        <label class="sv-opt">
          <input type="radio" name="price_range" value="solo_placa">
          <div class="sv-opt-ico"><i class="fa-solid fa-tag"></i></div>
          <div class="sv-opt-label">
            Solo el producto físico, sin plataforma
            <div class="sv-opt-sub">No me interesa el sistema digital, solo la placa.</div>
          </div>
          <div class="sv-opt-dot"></div>
        </label>
      </div>
      <div class="sv-nav">
        <button type="button" class="sv-btn sv-btn-o btn-prev"><i class="fa-solid fa-arrow-left"></i> Atras</button>
        <button type="button" class="sv-btn sv-btn-p btn-next" disabled>Siguiente <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>

    {{-- ═══ PASO 7 ═══════════════════════════════════════════════ --}}
    <div class="sv-view" data-step="7">
      <h3 class="sv-q">Del 1 al 10, ¿que tanto te interesaria usar un sistema como este?</h3>
      <p class="sv-q-hint">1 = No me interesa nada. 10 = Lo quiero ya.</p>

      <div class="sv-slider">
        <div class="sv-slider-val" id="sVal">5</div>
        <div class="sv-slider-lbl" id="sLbl">Me interesa un poco</div>
        <input type="range" class="sv-range" name="likelihood_score" id="sRange" min="1" max="10" value="5">
        <div class="sv-range-ends">
          <span>1 — Nada</span>
          <span>10 — Mucho</span>
        </div>
      </div>

      <div class="sv-input-grp">
        <label class="sv-q" style="font-size:14px;">¿Quieres que te avisemos cuando este listo? (opcional)</label>
        <p class="sv-q-hint" style="margin-bottom:10px; font-size:12px;">Dejanos tu correo y te notificamos cuando lancemos.</p>
        <input type="email" name="email" class="sv-input" placeholder="tucorreo@ejemplo.com" id="emailField">
        <div class="sv-input-note">
          <i class="fa-solid fa-lock" style="font-size:9px;"></i> No compartimos tu informacion con nadie.
        </div>
      </div>

      <div class="sv-nav">
        <button type="button" class="sv-btn sv-btn-o btn-prev"><i class="fa-solid fa-arrow-left"></i> Atras</button>
        <button type="button" class="sv-btn sv-btn-s" id="btnSend">
          <i class="fa-solid fa-paper-plane"></i> Enviar mis respuestas
        </button>
      </div>
    </div>

  </form>

  {{-- SUCCESS --}}
  <div class="sv-view" id="doneScreen" style="justify-content:center;">
    <div class="sv-done">
      <div class="sv-done-ico"><i class="fa-solid fa-check"></i></div>
      <h2>Gracias por participar</h2>
      <p>Tu opinion nos ayuda a crear un mejor producto. Si dejaste tu correo, te avisaremos cuando estemos listos.</p>
    </div>
  </div>

</main>

{{-- ═══ JS ═══════════════════════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

  const STORAGE_KEY = 'survey_progress';

  /* ── State ────────────────────────────────────────── */
  const S = {
    step: 0,
    max: 7,
    data: {
      has_pets: null, pet_type: null, main_concern: null,
      lost_pet_before: null, would_buy: null, price_range: null,
      likelihood_score: 5, email: '',
      source: document.querySelector('[name="source"]')?.value || 'direct'
    }
  };

  /* ── Persistence helpers ──────────────────────────── */
  function saveProgress() {
    try {
      sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
        step: S.step,
        data: S.data
      }));
    } catch(e) { /* quota exceeded or private mode - fail silently */ }
  }

  function clearProgress() {
    try { sessionStorage.removeItem(STORAGE_KEY); } catch(e) {}
  }

  function loadProgress() {
    try {
      const raw = sessionStorage.getItem(STORAGE_KEY);
      if (!raw) return false;
      const saved = JSON.parse(raw);
      if (!saved || !saved.data) return false;

      // Restore data
      Object.keys(S.data).forEach(key => {
        if (saved.data[key] !== undefined && saved.data[key] !== null) {
          S.data[key] = saved.data[key];
        }
      });

      // Restore step (at least 1 if they had started)
      if (saved.step > 0) {
        S.step = saved.step;
      }
      return saved.step > 0;
    } catch(e) { return false; }
  }

  /* ── DOM refs ─────────────────────────────────────── */
  const views    = document.querySelectorAll('.sv-view[data-step]');
  const pWrap    = document.getElementById('progressWrap');
  const pBar     = document.getElementById('pBar');
  const pText    = document.getElementById('pText');
  const form     = document.getElementById('surveyForm');
  const errBox   = document.getElementById('errBox');
  const btnSend  = document.getElementById('btnSend');
  const doneScr  = document.getElementById('doneScreen');

  /* ── View controller ──────────────────────────────── */
  function go(n, skipSave) {
    S.step = n;
    views.forEach(v => v.classList.remove('active'));
    const target = document.querySelector(`[data-step="${n}"]`);
    if (target) target.classList.add('active');

    if (n === 0) {
      pWrap.classList.remove('show');
    } else {
      pWrap.classList.add('show');
      const pct = Math.round((n / S.max) * 100);
      pBar.style.width = pct + '%';
      pText.textContent = n + ' / ' + S.max;
    }

    if (!skipSave) {
      saveProgress();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  }

  /* ── Restore UI from saved state ──────────────────── */
  function restoreUI() {
    // Re-check radios and style their cards
    const radioFields = ['has_pets','pet_type','main_concern','lost_pet_before','would_buy','price_range'];
    radioFields.forEach(name => {
      const val = S.data[name];
      if (!val) return;
      const input = document.querySelector(`input[name="${name}"][value="${val}"]`);
      if (!input) return;
      input.checked = true;
      const card = input.closest('.sv-opt');
      if (card) card.classList.add('on');

      // Enable the "next" button on that step
      const view = input.closest('.sv-view');
      if (view) {
        const btn = view.querySelector('.btn-next');
        if (btn) btn.disabled = false;
      }
    });

    // Restore slider
    const slider = document.getElementById('sRange');
    if (slider && S.data.likelihood_score) {
      slider.value = S.data.likelihood_score;
      slider.dispatchEvent(new Event('input'));
    }

    // Restore email
    const emailField = document.getElementById('emailField');
    if (emailField && S.data.email) {
      emailField.value = S.data.email;
    }

    // Navigate to saved step (skip scroll animation on restore)
    go(S.step, true);
  }

  /* ── Radio handling (FIX: uses 'change' on input, not click on label) ── */
  let advanceTimer = null;

  document.querySelectorAll('input[type="radio"]').forEach(input => {
    input.addEventListener('change', function() {
      // Style
      const grid = this.closest('.sv-opts');
      grid.querySelectorAll('.sv-opt').forEach(o => o.classList.remove('on'));
      this.closest('.sv-opt').classList.add('on');

      // Save
      S.data[this.name] = this.value;
      saveProgress();

      // Enable next
      const view = this.closest('.sv-view');
      const btn = view.querySelector('.btn-next');
      if (btn) btn.disabled = false;

      // Auto-advance (single timer, prevents double-fire)
      if (advanceTimer) clearTimeout(advanceTimer);
      advanceTimer = setTimeout(() => {
        if (S.step < S.max) go(S.step + 1);
        advanceTimer = null;
      }, 400);
    });
  });

  /* ── Nav buttons ──────────────────────────────────── */
  document.querySelector('.btn-start')?.addEventListener('click', () => go(1));

  document.querySelectorAll('.btn-next').forEach(b =>
    b.addEventListener('click', () => { if (S.step < S.max) go(S.step + 1); })
  );
  document.querySelectorAll('.btn-prev').forEach(b =>
    b.addEventListener('click', () => { if (S.step > 0) go(S.step - 1); })
  );

  /* ── Slider ───────────────────────────────────────── */
  const slider = document.getElementById('sRange');
  const sV = document.getElementById('sVal');
  const sL = document.getElementById('sLbl');
  const labels = {
    1:'No me interesa nada', 2:'Muy poco', 3:'Poco',
    4:'Algo de interes', 5:'Me interesa un poco', 6:'Bastante',
    7:'Me interesa mucho', 8:'Mucho interes', 9:'Casi seguro lo usaria',
    10:'Lo quiero ya'
  };

  if (slider) {
    slider.addEventListener('input', function() {
      const v = parseInt(this.value);
      sV.textContent = v;
      sL.textContent = labels[v] || '';
      S.data.likelihood_score = v;
      saveProgress();
      // Color cue
      sV.style.color = v >= 7 ? 'var(--success)' : v >= 4 ? 'var(--primary)' : '#dc2626';
      // Track fill
      const pct = ((v - 1) / 9) * 100;
      this.style.background = `linear-gradient(to right, var(--primary) ${pct}%, var(--border) ${pct}%)`;
    });
  }

  /* ── Email field persistence ─────────────────────── */
  const emailField = document.getElementById('emailField');
  if (emailField) {
    emailField.addEventListener('input', function() {
      S.data.email = this.value.trim();
      saveProgress();
    });
  }

  /* ── Submit ───────────────────────────────────────── */
  btnSend.addEventListener('click', function() {
    S.data.email = emailField?.value.trim() || '';
    S.data.likelihood_score = parseInt(slider.value);

    // Validate required
    const req = ['has_pets','main_concern','lost_pet_before','would_buy','price_range'];
    const miss = req.filter(k => !S.data[k]);
    if (miss.length) {
      showErr('Por favor completa todas las preguntas antes de enviar.');
      return;
    }

    // Loading
    const origHTML = this.innerHTML;
    this.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Enviando...';
    this.disabled = true;
    errBox.style.display = 'none';

    fetch('{{ route("survey.store") }}', {
      method: 'POST',
      headers: {
        'Content-Type':  'application/json',
        'Accept':        'application/json',
        'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(S.data)
    })
    .then(r => { if(!r.ok) return r.json().then(d => { throw d; }); return r.json(); })
    .then(d => {
      if (d.success) {
        clearProgress();
        form.style.display    = 'none';
        pWrap.style.display   = 'none';
        errBox.style.display  = 'none';
        doneScr.classList.add('active');
      }
    })
    .catch(e => {
      this.innerHTML = origHTML;
      this.disabled  = false;
      let msg = 'Hubo un error. Intenta de nuevo.';
      if (e?.errors) {
        const first = Object.values(e.errors)[0];
        msg = Array.isArray(first) ? first[0] : first;
      } else if (e?.message) { msg = e.message; }
      showErr(msg);
    });
  });

  function showErr(msg) {
    errBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> <span>${msg}</span>`;
    errBox.style.display = 'flex';
    errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    setTimeout(() => { errBox.style.display = 'none'; }, 6000);
  }

  /* ── INIT: Attempt restore ───────────────────────── */
  const hadProgress = loadProgress();
  if (hadProgress) {
    restoreUI();
  }

});
</script>
</body>
</html>
