@extends('layouts.app')

@push('styles')
<style>
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
  }

  @keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
  }

  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }

  .page-wrap { 
    padding-top: 2rem; 
    padding-bottom: 3rem;
    background: #ffffff;
  }

  /* Header mejorado */
  .dashboard-header {
    margin-bottom: 2.5rem;
    animation: fadeInUp 0.6s ease-out;
  }

  .dashboard-header h1 {
    font-size: 2rem;
    font-weight: 900;
    color: #0f1419;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
  }

  .dashboard-header .greeting-text {
    font-size: 1.1rem;
    color: #5f6c7b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .dashboard-header .greeting-icon {
    width: 28px;
    height: 28px;
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    animation: pulse 2s ease-in-out infinite;
  }

  /* KPI Cards mejoradas */
  .kpi {
    border: 0;
    background: white;
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
  }

  .kpi::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #115DFC, #3466ff, #1e7cf2);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .kpi:hover::before {
    opacity: 1;
  }

  .kpi:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(17, 93, 252, 0.2);
  }

  .kpi .kpi-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
  }

  .kpi .kpi-info {
    flex: 1;
  }

  .kpi .lbl {
    color: #6c7a89;
    font-weight: 600;
    font-size: 0.875rem;
    letter-spacing: 0.3px;
    margin-bottom: 0.75rem;
    text-transform: uppercase;
  }

  .kpi .big {
    font-size: 2.5rem;
    font-weight: 900;
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
  }

  .kpi .kpi-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, rgba(17,93,252,0.1) 0%, rgba(52,102,255,0.05) 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #3466ff;
    transition: all 0.3s ease;
  }

  .kpi:hover .kpi-icon {
    transform: rotate(10deg) scale(1.1);
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
    color: white;
    box-shadow: 0 8px 20px rgba(17, 93, 252, 0.3);
  }

  /* Animaciones escalonadas para KPIs */
  .kpi:nth-child(1) { animation-delay: 0.1s; }
  .kpi:nth-child(2) { animation-delay: 0.2s; }
  .kpi:nth-child(3) { animation-delay: 0.3s; }
  .kpi:nth-child(4) { animation-delay: 0.4s; }
  .kpi:nth-child(5) { animation-delay: 0.5s; }
  .kpi:nth-child(6) { animation-delay: 0.6s; }
  .kpi:nth-child(7) { animation-delay: 0.7s; }

  /* Section Title mejorado */
  .section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    animation: slideInLeft 0.6s ease-out;
  }

  .section-title {
    font-weight: 800;
    font-size: 1.5rem;
    letter-spacing: -0.3px;
    color: #0f1419;
    margin: 0;
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
  }

  .section-title::before {
    content: '';
    width: 4px;
    height: 28px;
    background: linear-gradient(180deg, #115DFC 0%, #3466ff 100%);
    border-radius: 2px;
  }

  .section-title .badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.75rem;
    background: linear-gradient(135deg, rgba(17,93,252,0.1) 0%, rgba(52,102,255,0.05) 100%);
    color: #3466ff;
    border-radius: 8px;
    font-weight: 700;
  }

  /* Cards elevadas mejoradas */
  .card-elev {
    border: 0;
    background: white;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    border-radius: 20px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
    position: relative;
    overflow: hidden;
  }

  .card-elev::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
    background: linear-gradient(135deg, rgba(17,93,252,0.02) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
  }

  .card-elev:hover::after {
    opacity: 1;
  }

  .card-elev:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
  }

  .card-elev h5 {
    font-weight: 800;
    color: #0f1419;
    font-size: 1.25rem;
    letter-spacing: -0.3px;
  }

  .badge-soft {
    background: linear-gradient(135deg, rgba(17,93,252,0.1) 0%, rgba(52,102,255,0.05) 100%);
    color: #3466ff;
    font-weight: 700;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-size: 0.8rem;
    letter-spacing: 0.3px;
  }

  /* Charts con mejor altura */
  .chart-box {
    height: 300px;
    position: relative;
  }

  /* Tabla mejorada */
  .table {
    margin-bottom: 0;
  }

  .table thead th {
    background: linear-gradient(135deg, rgba(17,93,252,0.05) 0%, rgba(52,102,255,0.02) 100%);
    color: #3d4451;
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    padding: 1rem;
  }

  .table tbody tr {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
  }

  .table tbody tr:hover {
    background: linear-gradient(135deg, rgba(17,93,252,0.03) 0%, rgba(52,102,255,0.01) 100%);
    transform: translateX(4px);
  }

  .table tbody td {
    padding: 1rem;
    color: #3d4451;
    font-size: 0.95rem;
  }

  .table code {
    background: linear-gradient(135deg, rgba(17,93,252,0.1) 0%, rgba(52,102,255,0.05) 100%);
    color: #3466ff;
    padding: 0.35rem 0.75rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.85rem;
  }

  /* Botones mejorados */
  .btn {
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    position: relative;
    overflow: hidden;
  }

  .btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .btn:hover::before {
    width: 300px;
    height: 300px;
  }

  .btn-primary {
    background: linear-gradient(135deg, #115DFC 0%, #3466ff 100%);
    color: white;
    box-shadow: 0 4px 16px rgba(17, 93, 252, 0.3);
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(17, 93, 252, 0.4);
    background: linear-gradient(135deg, #0d4dd9 0%, #2955e6 100%);
  }

  .btn-outline-primary {
    border: 2px solid #3466ff;
    color: #3466ff;
    background: transparent;
  }

  .btn-outline-primary:hover {
    background: #3466ff;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(17, 93, 252, 0.3);
  }

  .btn-outline-secondary {
    border: 2px solid #e5e7eb;
    color: #3d4451;
    background: white;
  }

  .btn-outline-secondary:hover {
    background: linear-gradient(135deg, rgba(17,93,252,0.05) 0%, rgba(52,102,255,0.02) 100%);
    border-color: #3466ff;
    color: #3466ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
  }

  .btn i {
    transition: transform 0.3s ease;
  }

  .btn:hover i {
    transform: scale(1.2);
  }

  /* Quick actions mejorado */
  .quick .d-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
  }

  .quick form {
    display: inline-block;
  }

  /* Empty state mejorado */
  .empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
    color: #6c7a89;
  }

  .empty-state i {
    font-size: 3rem;
    color: #d1d5db;
    margin-bottom: 1rem;
    display: block;
  }

  /* Admin badge */
  .admin-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.875rem;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    animation: float 3s ease-in-out infinite;
  }

  /* Loading skeleton */
  .skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
    border-radius: 8px;
  }

  /* Responsive */
  @media (max-width: 992px) {
    .dashboard-header h1 {
      font-size: 1.75rem;
    }

    .section-title {
      font-size: 1.25rem;
    }

    .kpi .big {
      font-size: 2rem;
    }

    .kpi .kpi-icon {
      width: 56px;
      height: 56px;
      font-size: 24px;
    }
  }

  @media (max-width: 768px) {
    .page-wrap {
      padding-top: 1.5rem;
    }

    .dashboard-header {
      margin-bottom: 1.5rem;
    }

    .dashboard-header h1 {
      font-size: 1.5rem;
    }

    .dashboard-header .greeting-text {
      font-size: 0.95rem;
    }

    .section-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }

    .kpi {
      padding: 1.25rem;
    }

    .kpi .big {
      font-size: 1.75rem;
    }

    .kpi .kpi-icon {
      width: 48px;
      height: 48px;
      font-size: 20px;
      border-radius: 12px;
    }

    .chart-box {
      height: 240px;
    }

    .card-elev {
      padding: 1.25rem !important;
    }

    .btn {
      padding: 0.65rem 1.25rem;
      font-size: 0.9rem;
    }

    .quick .d-flex {
      flex-direction: column;
    }

    .quick .d-flex > * {
      width: 100%;
    }
  }

  @media (max-width: 576px) {
    .kpi .kpi-content {
      flex-direction: column;
      text-align: center;
    }

    .table-responsive {
      font-size: 0.875rem;
    }

    .table thead th,
    .table tbody td {
      padding: 0.75rem 0.5rem;
    }
  }

  /* Reveal animation */
  .reveal {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease;
  }

  .reveal.show {
    opacity: 1;
    transform: none;
  }
</style>
@endpush

@section('content')
<div class="page-wrap">
  <div class="container">
    <!-- Header mejorado -->
    <div class="dashboard-header">
      <h1>
        <span class="greeting-icon">
          <i class="fa-solid fa-paw"></i>
        </span>
        Bienvenido, {{ $u->name }}
        @if($u->is_admin)
          <span class="admin-badge">
            <i class="fa-solid fa-shield-halved"></i>
            Admin
          </span>
        @endif
      </h1>
      <div class="greeting-text">
        <i class="fa-solid fa-sparkles"></i>
        Gestiona tus mascotas, genera/descarga su QR y activa tags.
      </div>
    </div>

    {{-- ================== KPIs PERSONALES (usuarios normales) ================== --}}
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card kpi">
          <div class="kpi-content">
            <div class="kpi-info">
              <div class="lbl">Mis mascotas</div>
              <div class="big">{{ $my['pets'] ?? 0 }}</div>
            </div>
            <div class="kpi-icon">
              <i class="fa-solid fa-paw"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card kpi">
          <div class="kpi-content">
            <div class="kpi-info">
              <div class="lbl">Marcadas perdidas</div>
              <div class="big">{{ $my['lost'] ?? 0 }}</div>
            </div>
            <div class="kpi-icon">
              <i class="fa-solid fa-magnifying-glass"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="card kpi">
          <div class="kpi-content">
            <div class="kpi-info">
              <div class="lbl">Recompensas activas</div>
              <div class="big">{{ $my['rewards'] ?? 0 }}</div>
            </div>
            <div class="kpi-icon">
              <i class="fa-solid fa-gift"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ================== SECCIÓN ADMIN (SOLO SI ES ADMIN) ================== --}}
    @if($u->is_admin)
      <div class="section-header mb-3">
        <h2 class="section-title">
          Panel de administración
          <span class="badge">Estadísticas globales</span>
        </h2>
        <a href="{{ route('portal.admin.dashboard') }}" class="btn btn-outline-primary btn-sm">
          <i class="fa-solid fa-arrow-right me-1"></i> Panel completo
        </a>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="card kpi">
            <div class="kpi-content">
              <div class="kpi-info">
                <div class="lbl">Usuarios</div>
                <div class="big">{{ $global['users'] ?? 0 }}</div>
              </div>
              <div class="kpi-icon">
                <i class="fa-solid fa-users"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi">
            <div class="kpi-content">
              <div class="kpi-info">
                <div class="lbl">Mascotas</div>
                <div class="big">{{ $global['pets'] ?? 0 }}</div>
              </div>
              <div class="kpi-icon">
                <i class="fa-solid fa-paw"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi">
            <div class="kpi-content">
              <div class="kpi-info">
                <div class="lbl">Perdidas</div>
                <div class="big">{{ $global['lost'] ?? 0 }}</div>
              </div>
              <div class="kpi-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card kpi">
            <div class="kpi-content">
              <div class="kpi-info">
                <div class="lbl">Recompensas</div>
                <div class="big">{{ $global['rewards'] ?? 0 }}</div>
              </div>
              <div class="kpi-icon">
                <i class="fa-solid fa-coins"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4">
          <div class="card kpi">
            <div class="kpi-content">
              <div class="kpi-info">
                <div class="lbl">TAGs (total)</div>
                <div class="big">{{ $global['qrs_total'] ?? 0 }}</div>
              </div>
              <div class="kpi-icon">
                <i class="fa-solid fa-qrcode"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4">
          <div class="card kpi">
            <div class="kpi-content">
              <div class="kpi-info">
                <div class="lbl">TAGs con imagen</div>
                <div class="big">{{ $global['qrs_with_img'] ?? 0 }}</div>
              </div>
              <div class="kpi-icon">
                <i class="fa-solid fa-image"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-4">
          <div class="card kpi">
            <div class="kpi-content">
              <div class="kpi-info">
                <div class="lbl">Escaneos (hoy)</div>
                <div class="big">{{ $global['scans_today'] ?? 0 }}</div>
              </div>
              <div class="kpi-icon">
                <i class="fa-solid fa-radar"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Gráficos admin --}}
      <div class="row g-3 mb-4">
        <div class="col-12 col-xl-7">
          <div class="card card-elev p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0">
                <i class="fa-solid fa-chart-column me-2" style="color: #3466ff;"></i>
                Mascotas registradas por mes
              </h5>
              <span class="badge badge-soft">
                <i class="fa-solid fa-calendar-days me-1"></i>
                Últimos 12 meses
              </span>
            </div>
            <div class="chart-box">
              <canvas id="chartPetsByMonth"></canvas>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-5">
          <div class="card card-elev p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0">
                <i class="fa-solid fa-chart-line me-2" style="color: #3466ff;"></i>
                Escaneos por día
              </h5>
              <span class="badge badge-soft">
                <i class="fa-solid fa-clock me-1"></i>
                Últimos 14 días
              </span>
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
        <div class="card card-elev p-4">
          <h5 class="mb-3">
            <i class="fa-solid fa-clock-rotate-left me-2" style="color: #3466ff;"></i>
            Últimos escaneos de mis TAGs
          </h5>
          @if(($myRecentScans ?? collect())->isEmpty())
            <div class="empty-state">
              <i class="fa-solid fa-inbox"></i>
              <div>Aún no hay escaneos recientes.</div>
            </div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th><i class="fa-solid fa-calendar me-1"></i> Fecha</th>
                    <th><i class="fa-solid fa-paw me-1"></i> Mascota</th>
                    <th><i class="fa-solid fa-tag me-1"></i> TAG</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($myRecentScans as $s)
                    <tr>
                      <td>{{ $s->created_at->format('Y-m-d H:i') }}</td>
                      <td><strong>{{ optional($s->qrCode->pet)->name ?: '—' }}</strong></td>
                      <td><code>#{{ $s->qr_code_id }}</code></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- Acciones rápidas --}}
      <div class="col-12 col-lg-4">
        <div class="card card-elev p-4 quick">
          <h5 class="mb-3">
            <i class="fa-solid fa-bolt me-2" style="color: #3466ff;"></i>
            Acciones rápidas
          </h5>
          <div class="d-flex flex-column gap-2">
            @if($u->is_admin)
              <a href="{{ route('portal.pets.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-2"></i> Registrar mascota
              </a>
              <a href="{{ route('portal.admin.tags.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-tags me-2"></i> Inventario TAGs
              </a>
              <form method="POST" action="{{ route('portal.admin.tags.backfill') }}"
                    onsubmit="return confirm('¿Crear TAGs faltantes para mascotas sin QR?');">
                @csrf
                <button class="btn btn-outline-secondary w-100">
                  <i class="fa-solid fa-rotate me-2"></i> Sincronizar TAGs
                </button>
              </form>
              <a href="{{ route('portal.admin.tags.export') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-file-csv me-2"></i> Exportar CSV
              </a>
            @endif

            <a href="{{ route('portal.pets.index') }}" class="btn btn-outline-secondary">
              <i class="fa-solid fa-paw me-2"></i> Ver mis mascotas
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
    $chartsSafe = $charts ?? [
      'labelsMonths' => [],
      'dataPets'     => [],
      'labelsDays'   => [],
      'dataScans'    => [],
    ];
  @endphp

  <script>
    (function () {
      const charts = @json($chartsSafe);

      const months    = charts.labelsMonths || [];
      const dataPets  = charts.dataPets     || [];
      const days      = charts.labelsDays   || [];
      const dataScans = charts.dataScans    || [];

      function destroyIfExists(key){
        if (window[key] && typeof window[key].destroy === 'function') {
          try { window[key].destroy(); } catch(e) {}
        }
      }

      // Chart 1: Mascotas por mes
      const el1 = document.getElementById('chartPetsByMonth');
      if (el1 && months.length) {
        destroyIfExists('_chartPetsByMonth');
        window._chartPetsByMonth = new Chart(el1, {
          type: 'bar',
          data: { 
            labels: months, 
            datasets: [{ 
              label: 'Mascotas registradas',
              data: dataPets,
              backgroundColor: 'rgba(52, 102, 255, 0.8)',
              borderColor: 'rgba(52, 102, 255, 1)',
              borderWidth: 2,
              borderRadius: 8,
              hoverBackgroundColor: 'rgba(17, 93, 252, 0.9)',
            }] 
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { 
              duration: 800,
              easing: 'easeInOutQuart'
            },
            plugins: {
              legend: {
                display: true,
                position: 'top',
                labels: {
                  font: { size: 13, weight: '600' },
                  padding: 15,
                  usePointStyle: true
                }
              },
              tooltip: {
                backgroundColor: 'rgba(15, 20, 25, 0.95)',
                padding: 12,
                titleFont: { size: 14, weight: '700' },
                bodyFont: { size: 13 },
                borderColor: 'rgba(52, 102, 255, 0.3)',
                borderWidth: 1,
                cornerRadius: 8
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: {
                  color: 'rgba(0, 0, 0, 0.05)',
                  drawBorder: false
                },
                ticks: {
                  font: { size: 12, weight: '600' },
                  color: '#6c7a89'
                }
              },
              x: {
                grid: {
                  display: false,
                  drawBorder: false
                },
                ticks: {
                  font: { size: 12, weight: '600' },
                  color: '#6c7a89'
                }
              }
            }
          }
        });
      }

      // Chart 2: Escaneos por día
      const el2 = document.getElementById('chartScans14d');
      if (el2 && days.length) {
        destroyIfExists('_chartScans14d');
        window._chartScans14d = new Chart(el2, {
          type: 'line',
          data: { 
            labels: days, 
            datasets: [{ 
              label: 'Escaneos',
              data: dataScans,
              tension: 0.4,
              fill: true,
              backgroundColor: 'rgba(52, 102, 255, 0.1)',
              borderColor: 'rgba(52, 102, 255, 1)',
              borderWidth: 3,
              pointBackgroundColor: '#ffffff',
              pointBorderColor: 'rgba(52, 102, 255, 1)',
              pointBorderWidth: 2,
              pointRadius: 5,
              pointHoverRadius: 7,
              pointHoverBackgroundColor: 'rgba(52, 102, 255, 1)',
              pointHoverBorderColor: '#ffffff',
              pointHoverBorderWidth: 3
            }] 
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { 
              duration: 800,
              easing: 'easeInOutQuart'
            },
            plugins: {
              legend: {
                display: true,
                position: 'top',
                labels: {
                  font: { size: 13, weight: '600' },
                  padding: 15,
                  usePointStyle: true
                }
              },
              tooltip: {
                backgroundColor: 'rgba(15, 20, 25, 0.95)',
                padding: 12,
                titleFont: { size: 14, weight: '700' },
                bodyFont: { size: 13 },
                borderColor: 'rgba(52, 102, 255, 0.3)',
                borderWidth: 1,
                cornerRadius: 8
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                grid: {
                  color: 'rgba(0, 0, 0, 0.05)',
                  drawBorder: false
                },
                ticks: {
                  font: { size: 12, weight: '600' },
                  color: '#6c7a89'
                }
              },
              x: {
                grid: {
                  display: false,
                  drawBorder: false
                },
                ticks: {
                  font: { size: 12, weight: '600' },
                  color: '#6c7a89',
                  maxRotation: 45,
                  minRotation: 0
                }
              }
            }
          }
        });
      }

      // Animación de entrada para elementos con clase 'reveal'
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('show');
          }
        });
      }, observerOptions);

      document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    })();
  </script>
@endif
@endpush