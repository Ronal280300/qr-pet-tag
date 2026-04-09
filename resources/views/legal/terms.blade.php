@extends('layouts.app')
@section('title', 'Términos de uso y Condiciones — PetScan')

@push('styles')
<style>
  :root {
    --ps-font: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    --ps-primary: #0F172A;
    --ps-accent: #2563EB;
    --ps-border: #E5E7EB;
    --ps-text-900: #111827;
    --ps-text-600: #4B5563;
    --ps-bg-soft: #F8FAFC;
  }

  body {
    background-color: var(--ps-bg-soft);
  }

  /* ====== ESTRUCTURA PRINCIPAL ====== */
  .legal-container {
    max-width: 1200px;
    margin: 60px auto 100px;
    padding: 0 24px;
  }

  .legal-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 48px;
    align-items: start;
  }

  @media (max-width: 992px) {
    .legal-grid {
      grid-template-columns: 1fr;
    }
  }

  /* ====== TOC STICKY (Indice) ====== */
  .toc-wrapper {
    position: sticky;
    top: 100px;
    background: transparent;
  }

  .toc-title {
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--ps-text-900);
    margin-bottom: 24px;
    display: block;
  }

  .toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: calc(100vh - 150px);
    overflow-y: auto;
  }

  /* Custom Scrollbar for TOC */
  .toc-list::-webkit-scrollbar {
    width: 4px;
  }
  .toc-list::-webkit-scrollbar-thumb {
    background: var(--ps-border);
    border-radius: 4px;
  }

  .toc-list li {
    margin-bottom: 8px;
  }

  .toc-list a {
    display: block;
    color: var(--ps-text-600);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
    border-left: 2px solid transparent;
  }

  .toc-list a:hover {
    color: var(--ps-accent);
    background: #EFF6FF;
    border-left-color: var(--ps-accent);
  }

  /* ====== CONTENIDO (Derecha) ====== */
  .legal-content-card {
    background: #FFFFFF;
    border-radius: 24px;
    padding: 64px 56px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
    border: 1px solid var(--ps-border);
  }

  @media (max-width: 768px) {
    .legal-content-card {
      padding: 40px 24px;
    }
  }

  .legal-header {
    margin-bottom: 48px;
    padding-bottom: 32px;
    border-bottom: 1px solid var(--ps-border);
  }

  .legal-title {
    font-size: 40px;
    font-weight: 800;
    letter-spacing: -1.5px;
    color: var(--ps-primary);
    margin-bottom: 12px;
    line-height: 1.1;
  }

  .legal-date {
    display: inline-block;
    padding: 6px 16px;
    background: #F1F5F9;
    color: var(--ps-text-600);
    border-radius: 9999px;
    font-size: 13px;
    font-weight: 600;
  }

  .legal-lead {
    font-size: 18px;
    line-height: 1.7;
    color: var(--ps-text-600);
    margin-bottom: 0;
  }

  /* Secciones textuales */
  .legal-section {
    margin-bottom: 40px;
    scroll-margin-top: 100px;
  }

  .legal-section h2 {
    font-size: 20px;
    font-weight: 700;
    color: var(--ps-primary);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .legal-section h2::before {
    content: "";
    display: block;
    width: 24px;
    height: 2px;
    background: var(--ps-accent);
    border-radius: 2px;
  }

  .legal-section p, 
  .legal-section li {
    font-size: 16px;
    line-height: 1.7;
    color: var(--ps-text-600);
    margin-bottom: 16px;
  }

  .legal-section ul {
    padding-left: 24px;
    margin-bottom: 16px;
  }

  .legal-section li {
    margin-bottom: 8px;
  }

  .legal-section strong {
    color: var(--ps-text-900);
    font-weight: 600;
  }

  .legal-link {
    color: var(--ps-accent);
    text-decoration: underline;
    text-underline-offset: 4px;
    font-weight: 500;
  }

  .legal-link:hover {
    color: #1D4ED8;
  }

  /* Return Btn */
  .return-btn-wrapper {
    margin-top: 56px;
    padding-top: 40px;
    border-top: 1px solid var(--ps-border);
  }

  .btn-elegant {
    display: inline-flex;
    align-items: center;
    background-color: var(--ps-primary);
    color: #FFFFFF;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  .btn-elegant:hover {
    background-color: #1E293B;
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    color: #FFF;
  }

  /* Smooth scroll nativo para toda la pagina cuando se hacen clics en el TOC */
  html {
    scroll-behavior: smooth;
  }
</style>
@endpush

@section('content')
<div class="legal-container">
  <div class="legal-grid">
    
    <!-- BARRA LATERAL (Índice) -->
    <aside class="d-none d-lg-block">
      <div class="toc-wrapper">
        <span class="toc-title">Navegación Rápida</span>
        <ul class="toc-list">
          <li><a href="#definiciones">1. Definiciones</a></li>
          <li><a href="#servicios">2. Servicios</a></li>
          <li><a href="#cuentas">3. Registro y Cuentas</a></li>
          <li><a href="#uso-placas">4. Uso de Placas QR</a></li>
          <li><a href="#responsabilidades">5. Responsabilidades</a></li>
          <li><a href="#contacto-rapido">6. Contacto Rápido</a></li>
          <li><a href="#recompensas">7. Recompensas</a></li>
          <li><a href="#contenido-usuario">8. Contenido de Usuario</a></li>
          <li><a href="#pagos">9. Pagos y Devoluciones</a></li>
          <li><a href="#prohibiciones">10. Usos Prohibidos</a></li>
          <li><a href="#propiedad">11. Propiedad Intelectual</a></li>
          <li><a href="#terceros">12. Integración de Terceros</a></li>
          <li><a href="#privacidad">13. Privacidad de Datos</a></li>
          <li><a href="#garantias">14. Descargos</a></li>
          <li><a href="#responsabilidad">15. Límite de Responsabilidad</a></li>
          <li><a href="#indemnizacion">16. Indemnización</a></li>
          <li><a href="#terminacion">17. Término del Servicio</a></li>
          <li><a href="#cambios">18. Modificaciones</a></li>
          <li><a href="#ley">19. Ley Aplicable</a></li>
          <li><a href="#contacto">20. Contacto General</a></li>
        </ul>
      </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="legal-content-card">
      @php($appName = config('app.name','PetScan'))

      <div class="legal-header">
        <div class="legal-date mb-3">Última actualización: {{ now()->format('d \d\e F, Y') }}</div>
        <h1 class="legal-title">Términos de uso y Condiciones</h1>
        <p class="legal-lead">Bienvenido a <strong>{{ $appName }}</strong>. Estos Términos regulan el acceso comercial y el uso de nuestras placas inteligentes, software en la nube y servicios asociados.</p>
      </div>

      <!-- Sección 1 -->
      <section class="legal-section" id="definiciones">
        <h2>1. Definiciones</h2>
        <p><strong>Placa/Tag Inteligente:</strong> Objeto físico o etiqueta con tecnología de escaneo (código QR, NFC) que conecta a un perfil digital unificado.</p>
        <p><strong>Perfil de Emergencia:</strong> Interfaz web segura que despliega datos clave de la mascota y establece el canal de contacto para recuperación.</p>
        <p><strong>Portal:</strong> Panel de control administrativo y de suscripción propiedad de {{ $appName }} exclusivo para el usuario autenticado.</p>
      </section>

      <!-- Sección 2 -->
      <section class="legal-section" id="servicios">
        <h2>2. Descripción de los Servicios</h2>
        <p>El núcleo operativo de {{ $appName }} radica en ofrecer a los ciudadanos infraestructura pasiva. Vinculamos una placa de escaneo rápido a un perfil administrable en la nube, posibilitando la identificación de mascotas extraviadas al instante y sin mediar la instalación de software adicional en el teléfono de un transeúnte.</p>
      </section>

      <!-- Sección 3 -->
      <section class="legal-section" id="cuentas">
        <h2>3. Registro y Cuentas</h2>
        <ul>
          <li>Para ostentar una cuenta como titular de una mascota, debes ser mayor de 18 años, o estar bajo explícita tutela de un representante legal.</li>
          <li>Bajo ningún concepto transferiremos la administración de correos o datos si pierdes acceso a la validación de cuenta por seguridad a robos de mascotas.</li>
          <li>Nos reservamos el derecho unilateral de purgar perfiles que incumplan leyes del bienestar animal aplicables al país constituyente.</li>
        </ul>
      </section>

      <!-- Sección 4 -->
      <section class="legal-section" id="uso-placas">
        <h2>4. Uso de Placas QR</h2>
        <p>La correcta asociación de una placa en físico adquirida con tu panel web es un factor crítico y recae en responsabilidad tuya como cliente. Clonar, comercializar masivamente sin licencia o manipular nuestra tecnología anulará automáticamente garantías de reposición.</p>
      </section>

      <!-- Sección 5 -->
      <section class="legal-section" id="responsabilidades">
        <h2>5. Responsabilidades del Titular</h2>
        <p>Actúas como editor directo de qué información expones. Si optas por mostrar números de teléfono residencial, direcciones u otra PII (Información Personal Identificable), lo haces bajo consigna de que nuestro sistema la imprimirá textualmente en la web al ser escaneado tu collar. Te comprometes a no cargar fotografías que contengan desnudez, violencia, o elementos despectivos en los avatares.</p>
      </section>

      <!-- Sección 6 -->
      <section class="legal-section" id="contacto-rapido">
        <h2>6. Comunicación por Escaneo (WhatsApp / Teléfono)</h2>
        <p>{{ $appName }} facilita el puente inicial mediante links encriptados de <a href="#" class="legal-link">API de WhatsApp</a> y esquemas <pre style="display:inline">tel:</pre>. No intervenimos, no leemos comunicaciones, y no participaremos de ninguna mediación en caso de controversia con quien halló a tu mascota.</p>
      </section>

      <!-- Sección 7 -->
      <section class="legal-section" id="recompensas">
        <h2>7. Recompensas Asignadas</h2>
        <p>El portal permite exhibir o esconder un banner visual de "Ofrezco Recompensa". Las cifras estipuladas son acuerdos entre ciudadanos. {{ $appName }} es puramente la vía de aviso visual y rechaza rotundamente cualquier demanda para cumplir transacciones comerciales relativas a estos canjes voluntarios.</p>
      </section>

      <!-- Sección 8 -->
      <section class="legal-section" id="contenido-usuario">
        <h2>8. Propiedad del Contenido de Usuario</h2>
        <p>El copyright de las fotos de tus perros, gatos o exóticos te pertenece perpetuamente a ti. Al crear perfiles nos das licencia temporal (revocable al borrar tu cuenta) para hostear esas imágenes en nuestros servidores tipo S3 y CDN logrando que carguen correctamente globalmente.</p>
      </section>

      <!-- Sección 9 -->
      <section class="legal-section" id="pagos">
        <h2>9. Pagos y Suscripciones</h2>
        <p>Los procesos de checkout aplican a compras por dispositivo (hardware físico) y/o planes (SaaS recurrente). Operamos bajo estrictas normativas financieras, por ende, las pasarelas retienen fondos y cobran impuestos de acuerdo a tu locación de acceso. Planes sujetos a términos de garantía de reposición mostrados en tu dashboard.</p>
      </section>

      <!-- Sección 10 -->
      <section class="legal-section" id="prohibiciones">
        <h2>10. Usos Prohibidos en Plataforma</h2>
        <p>Cualquier intento de penetración a la base de datos (SQLi), inyecciones de script en campos públicos de las mascotas, o el despliegue de robots/scrapers para bajar perfiles públicos resultará en ban permanente por IP.</p>
      </section>

      <!-- Sección 11 -->
      <section class="legal-section" id="propiedad">
        <h2>11. Derechos de Marca</h2>
        <p>Logo, branding, patentes UX, renders de diseño 3D y componentes de interfaz (Blade/Vue/React) son dominio registrado corporativo y propiedad de {{ $appName }}. Queda prohibida la extracción de nuestro CSS u otros activos para calcar portales ajenos.</p>
      </section>

      <!-- Sección 12 -->
      <section class="legal-section" id="terceros">
        <h2>12. Redes de Terceros</h2>
        <p>Utilizamos infraestructura de terceros, como Cloudflare para blindaje anti-DDoS o Amazon Web Services para alojamiento. Posibles latencias o interrupciones que escapen a nuestros data centers estarán sujetos a reportes de SLA externos.</p>
      </section>

      <!-- Sección 13 -->
      <section class="legal-section" id="privacidad">
        <h2>13. Privacidad de Datos</h2>
        <p>Garantizamos blindaje tecnológico como normado en la <a href="{{ route('legal.privacy') }}" class="legal-link">Política de Privacidad Integral</a>, a la que te sometes al aceptar el uso de este portal.</p>
      </section>

      <!-- Sección 14 -->
      <section class="legal-section" id="garantias">
        <h2>14. Descargos Funcionales</h2>
        <p>Si bien operamos la mayor cantidad de servidores distribuidos posible (CDN Edge), no garantizamos acceso infinito con "zero fallback". Redes intermitentes del usuario u operadoras defectuosas pudiesen retrasar la visualización del perfil en campo.</p>
      </section>

      <!-- Sección 15 -->
      <section class="legal-section" id="responsabilidad">
        <h2>15. Límites de Responsabilidad Legal</h2>
        <p>Comprando el dispositivo y servicio renuncias a la ejecución de demandas monetarias punitivas masivas en contra de directivos u operarios de {{ $appName }} en factores donde la plataforma operó humanamente bajo estándares correctos. La indemnización límite se topa contractualmente al sumatorio del importe facturado en el año en vigencia.</p>
      </section>

      <!-- Sección 16 -->
      <section class="legal-section" id="indemnizacion">
        <h2>16. Escudo a Nuestra Organización</h2>
        <p>Aceptas ser el único encargado de afrontar juicios en materia de difamación o de uso indebido en caso de que alguien interponga una denuncia por los datos públicos cargados a tus collares usando el CMS que prestamos.</p>
      </section>

      <!-- Sección 17 -->
      <section class="legal-section" id="terminacion">
        <h2>17. Cláusula de Rescisión</h2>
        <p>Los contratos suscritos (Planes) operan con libertad absoluta. El cliente cuenta en su área privada con botoneras rojas explícitamente diseñadas para la terminación unilateral y destrucción total (wipe-out) de activos.</p>
      </section>

      <!-- Sección 18 -->
      <section class="legal-section" id="cambios">
        <h2>18. Modificaciones a los Términos</h2>
        <p>Haremos esfuerzos tecnológicos comerciales (envíos de campaña de email) por alertar saltos a nuevas "major versions" de este texto legal, pero operar la aplicación y los portales después de re-publicaciones cuenta como refrendo unívoco.</p>
      </section>

      <!-- Sección 19 -->
      <section class="legal-section" id="ley">
        <h2>19. Jurisdicción Local</h2>
        <p>Nuestra locación base estipulada resuelve conflictos de jurisdicción y arbitraje basados en la legislación costarricense.</p>
      </section>

      <!-- Sección 20 -->
      <section class="legal-section" id="contacto">
        <h2>20. Dirección Física y Correspondencia</h2>
        <p>Contestamos velozmente todo tipo de solicitudes. Nuestra casilla base atiende reclamos en <a href="mailto:soporte@petscan.com" class="legal-link">soporte@petscan.com</a> y áreas asociadas. Nunca pediremos credenciales o contraseñas al operar en estas casillas.</p>
      </section>

      <div class="return-btn-wrapper d-flex justify-content-between align-items-center">
        <a href="{{ url('/') }}" class="btn-elegant"><i class="fa-solid fa-arrow-left me-2"></i> Volver al inicio</a>
        <a href="{{ route('register') }}" style="color: var(--ps-accent); font-weight: 600; text-decoration: none;">Crear una cuenta <i class="fa-solid fa-arrow-right ms-1"></i></a>
      </div>

    </main>
  </div>
</div>
@endsection
