@extends('layouts.app')

@section('title', 'Términos de uso y Condiciones - QR-Pet Tag')

@push('styles')
<style>
  :root{
    --brand: #1e7cf2;
    --ink: #0f172a;
    --muted: #6b7280;
    --bg-soft:#f7f9fc;
  }

  /* ===== Shell ===== */
  .legal-wrap{max-width:1120px; margin:0 auto; padding:24px}
  .legal-grid{display:grid; grid-template-columns: 280px 1fr; gap:28px}
  @media (max-width: 992px){ .legal-grid{grid-template-columns:1fr} }

  html:where(.legal){ scroll-behavior:smooth; } /* scope suave */

  /* ===== Tarjetas ===== */
  .card-soft{
    background:#fff;border-radius:16px;
    box-shadow:0 18px 55px rgba(31,41,55,.08);
  }

  /* ===== Sidebar (Índice) ===== */
  .toc-card{position:sticky; top:88px; align-self:start; padding:18px}
  .toc-title{
    font-size:.95rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase;
    color:#6b7280; margin:2px 0 10px;
  }
  .toc-list{ list-style:none; margin:0; padding:0 }
  .toc-list li a{
    display:block; padding:.5rem .75rem; border-radius:10px;
    color:#374151; text-decoration:none; font-weight:600;
  }
  .toc-list li a:hover{ background:#f0f6ff; color:var(--brand) }
  .toc-list li + li{ margin-top:2px }

  /* ===== Contenido ===== */
  .legal-card{ padding:28px 26px }
  @media (min-width:768px){ .legal-card{ padding:36px 34px } }

  .legal-h1{
    font-weight:900; letter-spacing:.2px; color:#111827;
    font-size: clamp(1.6rem, 1.1rem + 1.4vw, 2.2rem);
    margin:0 0 .25rem;
  }
  .updated-pill{
    display:inline-flex; align-items:center; gap:.5rem;
    padding:.35rem .6rem; border-radius:999px; font-weight:700;
    background:#edf5ff; color:#1e4396; font-size:.85rem;
  }

  .lead{
    color:#4b5563; line-height:1.75; font-size:1.06rem;
    margin-top:1rem; margin-bottom:.75rem;
  }

  .section{ scroll-margin-top: 96px; }
  .section h2{
    font-weight:900; margin:2rem 0 .4rem;
    font-size: clamp(1.15rem, .9rem + .8vw, 1.45rem);
    color:#0f172a; position:relative; padding-left:.85rem;
  }
  .section h2::before{
    content:""; position:absolute; left:0; top:.35rem;
    width:5px; height:1.15rem; border-radius:2px; background:var(--brand);
  }
  .section h3{ font-weight:800; margin:1rem 0 .35rem; color:#111827 }

  .section p{ color:#334155; line-height:1.75; margin:.4rem 0 }
  .section ul{ margin:.4rem 0 1rem 1.2rem }
  .section ul li{ margin:.25rem 0 }
  .section ul li::marker{ color:var(--brand); }

  /* Enlaces */
  .legal a{ color:var(--brand); text-decoration:underline; text-underline-offset:2px }
  .legal a:hover{ opacity:.9 }

  /* Bloque de “Contenido” dentro del main en mobile */
  .mini-toc{ display:none; padding:14px; background:var(--bg-soft); border-radius:12px; }
  .mini-toc h3{ margin:0 0 .5rem; font-size:.95rem; color:#6b7280; text-transform:uppercase; letter-spacing:.08em }
  @media (max-width:992px){
    .toc-card{ display:none }
    .mini-toc{ display:block }
  }
</style>
@endpush

@section('content')
<div class="legal legal-wrap">
  <div class="legal-grid">

    {{-- ===== Sidebar (Índice) ===== --}}
    <aside class="card-soft toc-card">
      <div class="toc-title">Contenido</div>
      <ol class="toc-list">
        <li><a href="#definiciones">Definiciones</a></li>
        <li><a href="#servicios">Descripción de los Servicios</a></li>
        <li><a href="#cuentas">Registro y cuentas</a></li>
        <li><a href="#uso-placas">Uso de placas QR</a></li>
        <li><a href="#responsabilidades">Responsabilidades del usuario</a></li>
        <li><a href="#contacto-rapido">Contacto rápido</a></li>
        <li><a href="#recompensas">Recompensas</a></li>
        <li><a href="#contenido-usuario">Contenido del usuario</a></li>
        <li><a href="#pagos">Pagos y devoluciones</a></li>
        <li><a href="#prohibiciones">Usos prohibidos</a></li>
        <li><a href="#propiedad">Propiedad intelectual</a></li>
        <li><a href="#terceros">Servicios de terceros</a></li>
        <li><a href="#privacidad">Privacidad</a></li>
        <li><a href="#garantias">Descargos</a></li>
        <li><a href="#responsabilidad">Limitación de responsabilidad</a></li>
        <li><a href="#indemnizacion">Indemnización</a></li>
        <li><a href="#terminacion">Suspensión y terminación</a></li>
        <li><a href="#cambios">Cambios</a></li>
        <li><a href="#ley">Ley aplicable</a></li>
        <li><a href="#contacto">Contacto</a></li>
      </ol>
    </aside>

    {{-- ===== Contenido ===== --}}
    <main class="card-soft legal-card">
      @php($appName = config('app.name','QR-Pet Tag'))

      <h1 class="legal-h1">Términos de uso y Condiciones</h1>
      <div class="updated-pill">
        <i class="fa-regular fa-calendar"></i> Última actualización: {{ now()->toDateString() }}
      </div>

      <p class="lead">
        Bienvenido a <strong>{{ $appName }}</strong>. Estos Términos regulan el acceso y uso del sitio web, el portal de usuarios y las
        funcionalidades asociadas a las placas/etiquetas QR para mascotas (en conjunto, los “Servicios”). Al crear una cuenta o usar los
        Servicios aceptas estos Términos. Si no estás de acuerdo, por favor no utilices los Servicios.
      </p>

      {{-- Mini índice para móvil --}}
      <div class="mini-toc">
        <h3>Contenido</h3>
        <ol class="toc-list">
          <li><a href="#definiciones">Definiciones</a></li>
          <li><a href="#servicios">Descripción de los Servicios</a></li>
          <li><a href="#cuentas">Registro y cuentas</a></li>
          <li><a href="#uso-placas">Uso de placas QR</a></li>
          <li><a href="#responsabilidades">Responsabilidades del usuario</a></li>
          <li><a href="#contacto-rapido">Contacto rápido</a></li>
          <li><a href="#recompensas">Recompensas</a></li>
          <li><a href="#contenido-usuario">Contenido del usuario</a></li>
          <li><a href="#pagos">Pagos y devoluciones</a></li>
          <li><a href="#prohibiciones">Usos prohibidos</a></li>
          <li><a href="#propiedad">Propiedad intelectual</a></li>
          <li><a href="#terceros">Servicios de terceros</a></li>
          <li><a href="#privacidad">Privacidad</a></li>
          <li><a href="#garantias">Descargos</a></li>
          <li><a href="#responsabilidad">Limitación de responsabilidad</a></li>
          <li><a href="#indemnizacion">Indemnización</a></li>
          <li><a href="#terminacion">Suspensión y terminación</a></li>
          <li><a href="#cambios">Cambios</a></li>
          <li><a href="#ley">Ley aplicable</a></li>
          <li><a href="#contacto">Contacto</a></li>
        </ol>
      </div>

      <div class="section" id="definiciones">
        <h2>1. Definiciones</h2>
        <p><strong>Placa/Tag QR:</strong> etiqueta física con un código QR único que redirige al perfil público de una mascota.</p>
        <p><strong>Perfil público:</strong> página que muestra información básica de la mascota y botones de contacto rápido con su propietario.</p>
        <p><strong>Portal:</strong> área autenticada para que el usuario gestione sus mascotas, tags y datos de contacto.</p>
      </div>

      <div class="section" id="servicios">
        <h2>2. Descripción de los Servicios</h2>
        <p>{{ $appName }} permite crear perfiles de mascotas y vincularlos a una placa QR para facilitar su identificación y la
          comunicación entre quien la encuentre y su dueño, sin necesidad de instalar apps adicionales.</p>
        <p>Los Servicios pueden incluir integraciones de contacto (por ejemplo, WhatsApp o llamadas telefónicas), notificaciones y
          funcionalidades administrativas (inventario/activación de tags, métricas, etc.).</p>
      </div>

      <div class="section" id="cuentas">
        <h2>3. Registro y cuentas</h2>
        <ul>
          <li>Debes ser mayor de 18 años y proporcionar información veraz y actualizada.</li>
          <li>Eres responsable de mantener la confidencialidad de tus credenciales y de toda actividad realizada en tu cuenta.</li>
          <li>Podemos cerrar o suspender cuentas que incumplan estos Términos o la ley.</li>
        </ul>
      </div>

      <div class="section" id="uso-placas">
        <h2>4. Uso de placas QR</h2>
        <ul>
          <li>Vincular un tag a la mascota correcta y mantener la información del perfil actualizada es responsabilidad del usuario.</li>
          <li>No manipules, dupliques o revendas tags sin autorización.</li>
          <li>El correcto funcionamiento del escaneo depende de la calidad de impresión, estado físico del tag y conectividad de quien escanea.</li>
        </ul>
      </div>

      <div class="section" id="responsabilidades">
        <h2>5. Responsabilidades del usuario</h2>
        <ul>
          <li>No publiques datos que no deseas hacer públicos en el perfil.</li>
          <li>Usa lenguaje respetuoso; no subas contenidos ilegales, ofensivos, falsos o que infrinjan derechos de terceros.</li>
          <li>Si compartes un número telefónico, autorizas su uso para contacto en caso de hallazgo de tu mascota.</li>
        </ul>
      </div>

      <div class="section" id="contacto-rapido">
        <h2>6. Contacto rápido (WhatsApp/llamada)</h2>
        <p>Los botones de WhatsApp y llamada redirigen al número configurado por el usuario. {{ $appName }} no participa ni garantiza la
          comunicación entre las partes, ni monitorea el contenido de dichas comunicaciones.</p>
      </div>

      <div class="section" id="recompensas">
        <h2>7. Recompensas por mascotas perdidas</h2>
        <ul>
          <li>Las recompensas son establecidas y financiadas por el usuario propietario. {{ $appName }} no administra, garantiza ni intermedia pagos.</li>
          <li>Las condiciones, verificación y entrega de una recompensa son responsabilidad exclusiva del propietario.</li>
        </ul>
      </div>

      <div class="section" id="contenido-usuario">
        <h2>8. Contenido del usuario</h2>
        <p>Conservas la titularidad de tu contenido. Nos concedes una licencia no exclusiva, mundial y libre de regalías para alojar,
          reproducir y mostrarlo con el fin de prestar los Servicios.</p>
      </div>

      <div class="section" id="pagos">
        <h2>9. Pagos, envíos y devoluciones</h2>
        <p>En caso de venta de placas u otros productos, aplicarán las condiciones mostradas en el proceso de compra (precios, impuestos,
          tiempos de envío y políticas de devolución). Si compras a distribuidores externos, sus políticas prevalecen.</p>
      </div>

      <div class="section" id="prohibiciones">
        <h2>10. Usos prohibidos</h2>
        <ul>
          <li>Usar los Servicios para fines ilícitos, difamatorios o fraudulentos.</li>
          <li>Intentar acceder sin autorización a sistemas o datos.</li>
          <li>Interferir con el funcionamiento del sitio o realizar scraping masivo.</li>
        </ul>
      </div>

      <div class="section" id="propiedad">
        <h2>11. Propiedad intelectual</h2>
        <p>El software, marcas, logotipos, textos e interfaces pertenecen a {{ $appName }} o a sus licenciantes. No se concede licencia
          alguna salvo lo estrictamente necesario para usar los Servicios conforme a estos Términos.</p>
      </div>

      <div class="section" id="terceros">
        <h2>12. Servicios de terceros</h2>
        <p>Podemos integrar servicios de terceros (por ejemplo, Google/Facebook para inicio de sesión o WhatsApp para contacto). Su uso se rige
          por los términos y políticas de esos terceros, sobre los cuales {{ $appName }} no tiene control ni responsabilidad.</p>
      </div>

      <div class="section" id="privacidad">
        <h2>13. Privacidad de datos</h2>
        <p>El tratamiento de datos personales se describe en nuestra <a href="#">Política de Privacidad</a>. Al usar los Servicios, consientes dicho tratamiento.</p>
      </div>

      <div class="section" id="garantias">
        <h2>14. Descargos de responsabilidad</h2>
        <p>Los Servicios se ofrecen “tal cual” y “según disponibilidad”. No garantizamos que el hallazgo o la devolución de una mascota vaya a
          ocurrir, ni la disponibilidad ininterrumpida del sitio o integraciones externas.</p>
      </div>

      <div class="section" id="responsabilidad">
        <h2>15. Limitación de responsabilidad</h2>
        <p>En la medida permitida por la ley, {{ $appName }} y sus afiliados no serán responsables por daños indirectos, incidentales, especiales,
          consecuentes o punitivos, ni por pérdida de datos, reputación o ganancias derivadas del uso o imposibilidad de uso de los Servicios.</p>
      </div>

      <div class="section" id="indemnizacion">
        <h2>16. Indemnización</h2>
        <p>Te comprometes a mantener indemne a {{ $appName }} frente a reclamaciones de terceros derivadas del contenido que publiques, del uso
          indebido de los Servicios o del incumplimiento de estos Términos.</p>
      </div>

      <div class="section" id="terminacion">
        <h2>17. Suspensión y terminación</h2>
        <p>Podemos suspender o terminar el acceso si incumples estos Términos o por razones operativas o legales. Puedes cerrar tu cuenta en
          cualquier momento desde el Portal.</p>
      </div>

      <div class="section" id="cambios">
        <h2>18. Cambios a estos Términos</h2>
        <p>Podemos modificar estos Términos. Publicaremos la versión vigente y, si el cambio es material, intentaremos notificarlo por los
          medios disponibles.</p>
      </div>

      <div class="section" id="ley">
        <h2>19. Ley aplicable y jurisdicción</h2>
        <p>Salvo que la ley disponga lo contrario, estos Términos se rigen por las leyes de Costa Rica. Cualquier disputa se someterá a los
          tribunales competentes de San José, Costa Rica.</p>
      </div>

      <div class="section" id="contacto">
        <h2>20. Contacto</h2>
        <p>Si tienes preguntas sobre estos Términos, escribe a
          <a href="mailto:contacto@qrpettag.com">info.qrpettag@gmail.com</a>.
        </p>
      </div>
    </main>
  </div>
</div>
@endsection
