@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Welcome banner --}}
<div class="row mb-4">
  <div class="col-12">
    <div class="alert alert-light border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
      <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <h4 class="alert-heading fw-bold mb-2">¡Bienvenido, {{ Auth::user()->name }}! 👋</h4>
          <p class="mb-0">Aquí tienes un resumen general del sistema. Última actualización: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
        <i class="fa-solid fa-chart-line fa-3x opacity-50 d-none d-md-block"></i>
      </div>
    </div>
  </div>
</div>

  {{-- KPIs --}}
  <div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon bg-primary-subtle text-primary">
          <i class="fa-solid fa-users"></i>
        </div>
        <div class="kpi-body">
          <div class="kpi-title">Usuarios</div>
          <div class="kpi-value">{{ number_format($totalUsers) }}</div>
          <div class="kpi-sub">+{{ number_format($newUsers30d) }} en 30 días</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon bg-info-subtle text-info">
          <i class="fa-solid fa-dog"></i>
        </div>
        <div class="kpi-body">
          <div class="kpi-title">Mascotas</div>
          <div class="kpi-value">{{ number_format($totalPets) }}</div>
          <div class="kpi-sub">{{ number_format($lostPets) }} perdidas</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon bg-success-subtle text-success">
          <i class="fa-solid fa-gift"></i>
        </div>
        <div class="kpi-body">
          <div class="kpi-title">Recompensas activas</div>
          <div class="kpi-value">{{ number_format($activeRewards) }}</div>
          <div class="kpi-sub">₡ {{ number_format($activeRewardsAmount, 2, '.', ',') }}</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="kpi-card">
        <div class="kpi-icon bg-purple-subtle text-purple">
          <i class="fa-solid fa-qrcode"></i>
        </div>
        <div class="kpi-body">
          <div class="kpi-title">QRs generados</div>
          <div class="kpi-value">{{ number_format($qrsGenerated) }}</div>
          <div class="kpi-sub">{{ number_format($petsWithoutQr) }} pendientes</div>
        </div>
      </div>
    </div>
  </div>

  {{-- Gráficos --}}
  <div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
      <div class="card card-elevated h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="card-title mb-0">Registros por mes</h5>
            <small class="text-muted">Últimos 12 meses</small>
          </div>
          <canvas id="chartPetsQrs" height="130"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="card card-elevated h-100">
        <div class="card-body">
          <h5 class="card-title mb-2">Mascotas perdidas por mes</h5>
          <canvas id="chartLost" height="130"></canvas>
        </div>
      </div>
    </div>
  </div>

  {{-- Últimas mascotas --}}
  <div class="card card-elevated">
    <div class="card-body">
      <h5 class="card-title mb-3">Últimas mascotas registradas</h5>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Raza</th>
              <th>Zona</th>
              <th>Edad</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($recentPets as $pet)
              <tr>
                <td class="fw-semibold">{{ $pet->name }}</td>
                <td>{{ $pet->breed ?: '—' }}</td>
                <td class="text-muted">{{ $pet->zone ?: '—' }}</td>
                <td>{{ is_null($pet->age) ? '—' : $pet->age }}</td>
                <td>
                  @if($pet->is_lost)
                    <span class="badge text-bg-warning">Perdida</span>
                  @else
                    <span class="badge text-bg-success">Normal</span>
                  @endif
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('portal.pets.show', $pet) }}">
                    <i class="fa-regular fa-eye me-1"></i> Ver
                  </a>
                  <a class="btn btn-sm btn-outline-secondary" href="{{ route('portal.pets.edit', $pet) }}">
                    <i class="fa-regular fa-pen-to-square me-1"></i> Editar
                  </a>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center text-muted">Sin registros.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush

@push('scripts')
@php
    // Preparamos el JSON en PHP (sin comas colgando)
    $dashPayload = [
        'labels' => $labels ?? [],
        'pets'   => $petsSeries ?? [],
        'qrs'    => $qrsSeries ?? [],
        'lost'   => $lostSeries ?? [],
    ];
    $dashJson = json_encode($dashPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp

<script id="dash-data" type="application/json">{!! $dashJson !!}</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  (function () {
    // Recuperar datos del <script type="application/json">
    let DATA = {};
    try {
      DATA = JSON.parse(document.getElementById('dash-data').textContent || '{}');
    } catch (e) { DATA = {}; }

    const LABELS = DATA.labels || [];
    const PETS   = DATA.pets   || [];
    const QRS    = DATA.qrs    || [];
    const LOST   = DATA.lost   || [];

    // Line - Pets vs QRs
    new Chart(document.getElementById('chartPetsQrs'), {
      type: 'line',
      data: {
        labels: LABELS,
        datasets: [
          { label: 'Mascotas',      data: PETS, tension: .3, borderWidth: 2, pointRadius: 3 },
          { label: 'QRs generados', data: QRS,  tension: .3, borderWidth: 2, pointRadius: 3 }
        ]
      },
      options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'bottom' } },
        scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { precision: 0 } } }
      }
    });

    // Bars - Lost pets
    new Chart(document.getElementById('chartLost'), {
      type: 'bar',
      data: {
        labels: LABELS,
        datasets: [{ label: 'Perdidas', data: LOST, borderWidth: 1 }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { precision: 0 } } }
      }
    });
  })();
</script>
@endpush

