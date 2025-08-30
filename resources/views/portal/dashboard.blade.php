@extends('layouts.app')

@push('styles')
<style>
  .page-wrap{ padding-top: 1rem; }
  .kpi{ border:0; box-shadow:0 4px 20px rgba(0,0,0,.06); border-radius:16px; }
  .kpi .big{ font-size:32px; font-weight:800; }
  .kpi .lbl{ color:#6c7a89; font-weight:600; letter-spacing:.2px; }
  .section-title{ font-weight:800; letter-spacing:.3px; }
  .card-elev{ border:0; box-shadow:0 4px 20px rgba(0,0,0,.06); border-radius:16px; }
  .list-unstyled li+li{ margin-top:.5rem; }
  .badge-soft{ background:#eef4ff; color:#1e7cf2; font-weight:600; }
  .quick a{ min-width: 220px; }
  .reveal{ opacity:0; transform: translateY(8px); transition: all .5s ease; }
  .reveal.show{ opacity:1; transform:none; }

  /* Altura fija de los gráficos para evitar loops de resize */
  .chart-box { height: 280px; }
  @media (max-width: 576px){ .chart-box { height: 220px; } }
</style>
@endpush

@section('content')
<div class="page-wrap">
  <div class="container">
    <div class="mb-4">
      <h1 class="h3 fw-bold">Bienvenido, {{ $u->name }}</h1>
      <div class="text-muted">Gestiona tus mascotas, genera/descarga su QR y activa tags.</div>
    </div>

    {{-- ================== KPIs PERSONALES (usuarios normales) ================== --}}
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card kpi p-3">
          <div class="lbl">Mis mascotas</div>
          <div class="big">{{ $my['pets'] ?? 0 }}</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card kpi p-3">
          <div class="lbl">Marcadas perdidas</div>
          <div class="big">{{ $my['lost'] ?? 0 }}</div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card kpi p-3">
          <div class="lbl">Recompensas activas</div>
          <div class="big">{{ $my['rewards'] ?? 0 }}</div>
        </div>
      </div>
      {{-- IMPORTANTE: Escaneos (hoy) SOLO para admin → lo movemos al panel admin --}}
    </div>

    {{-- ================== SECCIÓN ADMIN (SOLO SI ES ADMIN) ================== --}}
    @if($u->is_admin)
      <div class="mb-3 d-flex align-items-center justify-content-between">
        <h2 class="h5 section-title mb-0">Panel rápido de administración</h2>
        <a href="{{ route('portal.admin.dashboard') }}" class="btn btn-outline-primary btn-sm">
          Ir al panel completo
        </a>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="card kpi p-3">
            <div class="lbl">Usuarios</div>
            <div class="big">{{ $global['users'] ?? 0 }}</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi p-3">
            <div class="lbl">Mascotas</div>
            <div class="big">{{ $global['pets'] ?? 0 }}</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi p-3">
            <div class="lbl">Perdidas</div>
            <div class="big">{{ $global['lost'] ?? 0 }}</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi p-3">
            <div class="lbl">Recompensas activas</div>
            <div class="big">{{ $global['rewards'] ?? 0 }}</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi p-3">
            <div class="lbl">TAGs (total)</div>
            <div class="big">{{ $global['qrs_total'] ?? 0 }}</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi p-3">
            <div class="lbl">TAGs con imagen</div>
            <div class="big">{{ $global['qrs_with_img'] ?? 0 }}</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi p-3">
            <div class="lbl">Escaneos (hoy)</div>
            <div class="big">{{ $global['scans_today'] ?? 0 }}</div>
          </div>
        </div>
      </div>

      {{-- Gráficos admin --}}
      <div class="row g-3 mb-4">
        <div class="col-12 col-xl-7">
          <div class="card card-elev p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h5 class="mb-0">Mascotas registradas por mes</h5>
              <span class="badge badge-soft">Últimos 12 meses</span>
            </div>
            <div class="chart-box">
              <canvas id="chartPetsByMonth"></canvas>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-5">
          <div class="card card-elev p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h5 class="mb-0">Escaneos por día</h5>
              <span class="badge badge-soft">Últimos 14 días</span>
            </div>
            <div class="chart-box">
              <canvas id="chartScans14d"></canvas>
            </div>
          </div>
        </div>
      </div>
    @endif

    {{-- ================== ÚLTIMOS ESCANEOS DEL USUARIO ================== --}}
    <div class="row g-3">
      <div class="col-12 col-lg-8">
        <div class="card card-elev p-3">
          <h5 class="mb-2">Últimos escaneos de mis TAGs</h5>
          @if(($myRecentScans ?? collect())->isEmpty())
            <div class="text-muted">Aún no hay escaneos recientes.</div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Mascota</th>
                    <th>TAG</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($myRecentScans as $s)
                    <tr>
                      <td>{{ $s->created_at->format('Y-m-d H:i') }}</td>
                      <td>{{ optional($s->qrCode->pet)->name ?: '—' }}</td>
                      <td><code>#{{ $s->qr_code_id }}</code></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- Acciones rápidas: usuarios ven solo “Ver mis mascotas”; admin ve todo --}}
      <div class="col-12 col-lg-4">
        <div class="card card-elev p-3 mb-3 quick">
          <h5 class="mb-2">Acciones rápidas</h5>
          <div class="d-flex flex-wrap gap-2">
            @if($u->is_admin)
              <a href="{{ route('portal.pets.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Registrar mascota
              </a>
              <a href="{{ route('portal.admin.tags.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-tags me-1"></i> Inventario TAGs
              </a>
              <form method="POST" action="{{ route('portal.admin.tags.backfill') }}"
                    onsubmit="return confirm('¿Crear TAGs faltantes para mascotas sin QR?');">
                @csrf
                <button class="btn btn-outline-secondary">
                  <i class="fa-solid fa-rotate me-1"></i> Sincronizar TAGs
                </button>
              </form>
              <a href="{{ route('portal.admin.tags.export') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-file-csv me-1"></i> Exportar CSV
              </a>
            @endif

            <a href="{{ route('portal.pets.index') }}" class="btn btn-outline-secondary">
              <i class="fa-solid fa-paw me-1"></i> Ver mis mascotas
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@if($u->is_admin)
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  @php
    // Normalizamos $charts en PHP para evitar cualquier problema de sintaxis al inyectar JSON
    $chartsSafe = $charts ?? [
      'labelsMonths' => [],
      'dataPets'     => [],
      'labelsDays'   => [],
      'dataScans'    => [],
    ];
  @endphp

  <script>
    (function () {
      // Datos para los charts (siempre objeto JSON válido)
      const charts = @json($chartsSafe);

      const months    = charts.labelsMonths || [];
      const dataPets  = charts.dataPets     || [];
      const days      = charts.labelsDays   || [];
      const dataScans = charts.dataScans    || [];

      // Evitar instancias duplicadas si el script corre más de una vez
      function destroyIfExists(key){
        if (window[key] && typeof window[key].destroy === 'function') {
          try { window[key].destroy(); } catch(e) {}
        }
      }

      const el1 = document.getElementById('chartPetsByMonth');
      if (el1 && months.length) {
        destroyIfExists('_chartPetsByMonth');
        window._chartPetsByMonth = new Chart(el1, {
          type: 'bar',
          data: { labels: months, datasets: [{ label: 'Mascotas', data: dataPets }] },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 300 }
          }
        });
      }

      const el2 = document.getElementById('chartScans14d');
      if (el2 && days.length) {
        destroyIfExists('_chartScans14d');
        window._chartScans14d = new Chart(el2, {
          type: 'line',
          data: { labels: days, datasets: [{ label: 'Escaneos', data: dataScans, tension: .3, fill: false }] },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 300 }
          }
        });
      }
    })();
  </script>
@endif
@endpush
