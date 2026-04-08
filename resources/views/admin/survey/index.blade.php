@extends('layouts.admin')

@section('title', 'Estadísticas de Mercado')
@section('page-title', 'Estadísticas')

@section('content')
{{-- Header con acciones --}}
<div class="row mb-4">
  <div class="col-12">
    <div class="survey-hero">
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
          <h4 class="fw-bold mb-1 text-white">Validacion de Mercado</h4>
          <p class="text-white-50 mb-0">Resultados en tiempo real de la encuesta pública · {{ $totalResponses }} respuestas totales</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-light btn-sm fw-bold" id="btnCopyLink" onclick="copySurveyLink()">
            <i class="fa-solid fa-link me-1"></i> Copiar enlace
          </button>
          <a href="{{ $surveyUrl }}" target="_blank" class="btn btn-outline-light btn-sm fw-bold">
            <i class="fa-solid fa-external-link me-1"></i> Ver formulario
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- KPIs principales --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="survey-kpi">
      <div class="survey-kpi-icon" style="background: rgba(99,102,241,.1); color: #6366f1;">
        <i class="fa-solid fa-clipboard-list"></i>
      </div>
      <div class="survey-kpi-data">
        <div class="survey-kpi-value">{{ number_format($totalResponses) }}</div>
        <div class="survey-kpi-label">Respuestas totales</div>
        <div class="survey-kpi-sub text-success">
          <i class="fa-solid fa-arrow-up"></i> {{ $responsesLast7d }} esta semana
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="survey-kpi">
      <div class="survey-kpi-icon" style="background: rgba(16,185,129,.1); color: #10b981;">
        <i class="fa-solid fa-cart-shopping"></i>
      </div>
      <div class="survey-kpi-data">
        <div class="survey-kpi-value">{{ $wouldBuyPercentage }}%</div>
        <div class="survey-kpi-label">Intención de compra</div>
        <div class="survey-kpi-sub">{{ $wouldBuyCount }} dispuestos a comprar</div>
      </div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="survey-kpi">
      <div class="survey-kpi-icon" style="background: rgba(245,158,11,.1); color: #f59e0b;">
        <i class="fa-solid fa-star"></i>
      </div>
      <div class="survey-kpi-data">
        <div class="survey-kpi-value">{{ $avgLikelihood }}/10</div>
        <div class="survey-kpi-label">Probabilidad promedio</div>
        <div class="survey-kpi-sub">
          @if($avgLikelihood >= 7)
            <span class="text-success"><i class="fa-solid fa-check"></i> Señal fuerte</span>
          @elseif($avgLikelihood >= 5)
            <span class="text-warning"><i class="fa-solid fa-minus"></i> Moderada</span>
          @else
            <span class="text-danger"><i class="fa-solid fa-xmark"></i> Baja</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-6 col-xl-3">
    <div class="survey-kpi">
      <div class="survey-kpi-icon" style="background: rgba(236,72,153,.1); color: #ec4899;">
        <i class="fa-solid fa-envelope"></i>
      </div>
      <div class="survey-kpi-data">
        <div class="survey-kpi-value">{{ number_format($emailsCaptured) }}</div>
        <div class="survey-kpi-label">Emails capturados</div>
        <div class="survey-kpi-sub">Leads potenciales</div>
      </div>
    </div>
  </div>
</div>

{{-- Gráfico de tendencia + Intención de compra --}}
<div class="row g-3 mb-4">
  <div class="col-12 col-xl-8">
    <div class="card card-elevated h-100">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h6 class="fw-bold mb-0"><i class="fa-solid fa-chart-area me-2 text-primary"></i>Respuestas por día</h6>
          <span class="badge bg-primary-subtle text-primary">Últimos 30 días</span>
        </div>
        <canvas id="chartDaily" height="120"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-xl-4">
    <div class="card card-elevated h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-bag-shopping me-2 text-success"></i>¿Compraría un tag QR?</h6>
        <canvas id="chartWouldBuy" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- Distribuciones --}}
<div class="row g-3 mb-4">
  {{-- Tipo de mascota --}}
  <div class="col-12 col-md-6 col-xl-4">
    <div class="card card-elevated h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-paw me-2 text-info"></i>Tipo de mascota</h6>
        @if(count($petTypeDist) > 0)
          @foreach($petTypeDist as $item)
            <div class="dist-row">
              <div class="d-flex justify-content-between mb-1">
                <span class="fw-semibold text-capitalize">{{ str_replace('_', ' ', $item['label']) }}</span>
                <span class="text-muted">{{ $item['count'] }} ({{ $item['percentage'] }}%)</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-info" style="width: {{ $item['percentage'] }}%"></div>
              </div>
            </div>
          @endforeach
        @else
          <p class="text-muted text-center py-3">Sin datos aún</p>
        @endif
      </div>
    </div>
  </div>

  {{-- Mayor preocupación --}}
  <div class="col-12 col-md-6 col-xl-4">
    <div class="card card-elevated h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-triangle-exclamation me-2 text-warning"></i>Mayor preocupación</h6>
        @if(count($mainConcernDist) > 0)
          @foreach($mainConcernDist as $item)
            @php
              $concernLabels = [
                'se_pierda'      => 'Extravio o escape',
                'salud'          => 'Emergencias medicas',
                'identificacion' => 'Falta de identificacion',
                'robo'           => 'Robo o apropiacion',
                'otro'           => 'Otra situacion',
              ];
            @endphp
            <div class="dist-row">
              <div class="d-flex justify-content-between mb-1">
                <span class="fw-semibold">{{ $concernLabels[$item['label']] ?? $item['label'] }}</span>
                <span class="text-muted">{{ $item['percentage'] }}%</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-warning" style="width: {{ $item['percentage'] }}%"></div>
              </div>
            </div>
          @endforeach
        @else
          <p class="text-muted text-center py-3">Sin datos aún</p>
        @endif
      </div>
    </div>
  </div>

  {{-- Rango de precio --}}
  <div class="col-12 col-md-6 col-xl-4">
    <div class="card card-elevated h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-money-bill-wave me-2 text-success"></i>Rango de precio</h6>
        @if(count($priceRangeDist) > 0)
          @foreach($priceRangeDist as $item)
            @php
              $priceLabels = [
                'pago_unico'          => 'Pago único (₡12K)',
                'pago_anual'          => 'Pago inicial + Anualidad',
                'suscripcion_mensual' => 'Suscripción (₡1K/mes)',
                'solo_placa'          => 'Solo placa física',
              ];
            @endphp
            <div class="dist-row">
              <div class="d-flex justify-content-between mb-1">
                <span class="fw-semibold">{{ $priceLabels[$item['label']] ?? $item['label'] }}</span>
                <span class="text-muted">{{ $item['percentage'] }}%</span>
              </div>
              <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-success" style="width: {{ $item['percentage'] }}%"></div>
              </div>
            </div>
          @endforeach
        @else
          <p class="text-muted text-center py-3">Sin datos aún</p>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- Más distribuciones --}}
<div class="row g-3 mb-4">
  {{-- ¿Ha perdido mascota? --}}
  <div class="col-12 col-md-6">
    <div class="card card-elevated h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-heart-crack me-2 text-danger"></i>¿Ha perdido una mascota?</h6>
        @if(count($lostPetDist) > 0)
          <div class="row text-center">
            @foreach($lostPetDist as $item)
              @php
                $lostLabels = [
                  'si'              => ['label' => 'Si, personalmente', 'icon' => 'fa-circle-check', 'color' => 'danger'],
                  'no'              => ['label' => 'No, nunca', 'icon' => 'fa-circle-xmark', 'color' => 'success'],
                  'conozco_alguien' => ['label' => 'Conozco a alguien', 'icon' => 'fa-users', 'color' => 'warning'],
                ];
                $cfg = $lostLabels[$item['label']] ?? ['label' => $item['label'], 'icon' => 'fa-question', 'color' => 'secondary'];
              @endphp
              <div class="col-4">
                <div class="text-{{ $cfg['color'] }} mb-2">
                  <i class="fa-solid {{ $cfg['icon'] }} fa-2x"></i>
                </div>
                <div class="fw-bold fs-4">{{ $item['percentage'] }}%</div>
                <div class="text-muted small">{{ $cfg['label'] }}</div>
                <div class="text-muted" style="font-size: 11px;">{{ $item['count'] }} resp.</div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-muted text-center py-3">Sin datos aún</p>
        @endif
      </div>
    </div>
  </div>

  {{-- Likelihood score --}}
  <div class="col-12 col-md-6">
    <div class="card card-elevated h-100">
      <div class="card-body">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-gauge-high me-2 text-primary"></i>Probabilidad de uso (1-10)</h6>
        <canvas id="chartLikelihood" height="150"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- Insight card --}}
@if($totalResponses >= 5)
<div class="row mb-4">
  <div class="col-12">
    <div class="card card-elevated border-0" style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);">
      <div class="card-body p-4">
        <h6 class="fw-bold text-success mb-3">
          <i class="fa-solid fa-lightbulb me-2"></i>Insights del Mercado
        </h6>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="p-3 bg-white rounded-3 h-100">
              <div class="text-muted small mb-1">Señal de demanda</div>
              <div class="fw-bold fs-5 {{ $wouldBuyPercentage >= 60 ? 'text-success' : ($wouldBuyPercentage >= 40 ? 'text-warning' : 'text-danger') }}">
                @if($wouldBuyPercentage >= 60)
                  ✅ FUERTE — {{ $wouldBuyPercentage }}% compraría
                @elseif($wouldBuyPercentage >= 40)
                  ⚠️ MODERADA — {{ $wouldBuyPercentage }}% compraría
                @else
                  ❌ DÉBIL — {{ $wouldBuyPercentage }}% compraría
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 bg-white rounded-3 h-100">
              <div class="text-muted small mb-1">NPS simplificado</div>
              <div class="fw-bold fs-5 {{ $avgLikelihood >= 7 ? 'text-success' : ($avgLikelihood >= 5 ? 'text-warning' : 'text-danger') }}">
                @if($avgLikelihood >= 7)
                  🟢 {{ $avgLikelihood }}/10 — Excelente acogida
                @elseif($avgLikelihood >= 5)
                  🟡 {{ $avgLikelihood }}/10 — Interés medio
                @else
                  🔴 {{ $avgLikelihood }}/10 — Bajo interés
                @endif
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="p-3 bg-white rounded-3 h-100">
              <div class="text-muted small mb-1">Tasa de conversión email</div>
              @php $emailRate = $totalResponses > 0 ? round(($emailsCaptured / $totalResponses) * 100, 1) : 0; @endphp
              <div class="fw-bold fs-5 {{ $emailRate >= 30 ? 'text-success' : 'text-warning' }}">
                📧 {{ $emailRate }}% dejaron email
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif

{{-- Últimas respuestas --}}
<div class="card card-elevated">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="fw-bold mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>Últimas respuestas</h6>
      <span class="text-muted small">Mostrando las últimas 10</span>
    </div>
    <div class="table-responsive">
      <table class="table align-middle table-hover" id="tableRecentResponses">
        <thead>
          <tr>
            <th>#</th>
            <th>Mascota</th>
            <th>Preocupación</th>
            <th>¿Compraría?</th>
            <th>Precio</th>
            <th>Prob.</th>
            <th>Email</th>
            <th>Fecha</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentResponses as $r)
            <tr>
              <td class="text-muted">{{ $r->id }}</td>
              <td>
                <span class="badge bg-info-subtle text-info text-capitalize">{{ str_replace('_', ' ', $r->pet_type ?? '—') }}</span>
              </td>
              <td class="text-capitalize">{{ str_replace('_', ' ', $r->main_concern ?? '—') }}</td>
              <td>
                @php
                  $buyBadge = match($r->would_buy) {
                    'definitivamente_si' => 'bg-success',
                    'probablemente_si' => 'bg-success bg-opacity-75',
                    'no_estoy_seguro' => 'bg-warning',
                    'probablemente_no' => 'bg-danger bg-opacity-75',
                    'definitivamente_no' => 'bg-danger',
                    default => 'bg-secondary',
                  };
                  $buyText = match($r->would_buy) {
                    'definitivamente_si' => 'Definitivamente sí',
                    'probablemente_si' => 'Probablemente sí',
                    'no_estoy_seguro' => 'No estoy seguro',
                    'probablemente_no' => 'Probablemente no',
                    'definitivamente_no' => 'Definitivamente no',
                    default => '—',
                  };
                @endphp
                <span class="badge {{ $buyBadge }}">{{ $buyText }}</span>
              </td>
              <td class="small">
                @php
                  $priceLabels = [
                    'pago_unico'          => 'Pago único',
                    'pago_anual'          => 'Anualidad',
                    'suscripcion_mensual' => 'Suscripción',
                    'solo_placa'          => 'Solo placa',
                  ];
                @endphp
                {{ $priceLabels[$r->price_range] ?? '—' }}
              </td>
              <td>
                <span class="badge {{ $r->likelihood_score >= 7 ? 'bg-success' : ($r->likelihood_score >= 5 ? 'bg-warning' : 'bg-danger') }}">
                  {{ $r->likelihood_score ?? '—' }}/10
                </span>
              </td>
              <td class="small text-muted">{{ $r->email ? Str::limit($r->email, 20) : '—' }}</td>
              <td class="small text-muted">{{ $r->created_at->diffForHumans() }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">
                <i class="fa-solid fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                Aún no hay respuestas. ¡Comparte el formulario!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- Enlace oculto para copiar --}}
<input type="hidden" id="surveyUrl" value="{{ $surveyUrl }}">
@endsection

@push('styles')
<style>
  .survey-hero {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
    border-radius: 16px;
    padding: 28px 32px;
    box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3);
  }

  .survey-kpi {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
    height: 100%;
  }

  .survey-kpi:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  }

  .survey-kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }

  .survey-kpi-value {
    font-size: 28px;
    font-weight: 800;
    line-height: 1.1;
    color: #1a1d2e;
  }

  .survey-kpi-label {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    margin-top: 2px;
  }

  .survey-kpi-sub {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 4px;
  }

  .card-elevated {
    border: 0;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
  }

  .dist-row {
    margin-bottom: 14px;
  }

  .dist-row:last-child {
    margin-bottom: 0;
  }

  .progress {
    border-radius: 99px;
    background: #f3f4f6;
  }

  .progress-bar {
    border-radius: 99px;
    transition: width 0.6s ease;
  }

  @media (max-width: 576px) {
    .survey-hero {
      padding: 20px;
    }
    .survey-kpi-value {
      font-size: 22px;
    }
    .survey-kpi-icon {
      width: 40px;
      height: 40px;
      font-size: 16px;
    }
  }
</style>
@endpush

@push('scripts')
@php
  $chartPayload = [
    'dailyLabels' => $dailyLabels ?? [],
    'dailySeries' => $dailySeries ?? [],
    'wouldBuyDist' => $wouldBuyDist ?? [],
    'likelihoodDist' => $likelihoodDist ?? [],
  ];
  $chartJson = json_encode($chartPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
@endphp

<script id="survey-chart-data" type="application/json">{!! $chartJson !!}</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
  let DATA = {};
  try { DATA = JSON.parse(document.getElementById('survey-chart-data').textContent || '{}'); } catch(e) {}

  // Respuestas por día (area chart)
  const dailyCtx = document.getElementById('chartDaily');
  if (dailyCtx) {
    new Chart(dailyCtx, {
      type: 'line',
      data: {
        labels: DATA.dailyLabels || [],
        datasets: [{
          label: 'Respuestas',
          data: DATA.dailySeries || [],
          fill: true,
          backgroundColor: 'rgba(99, 102, 241, 0.1)',
          borderColor: '#6366f1',
          borderWidth: 2,
          tension: 0.4,
          pointRadius: 2,
          pointHoverRadius: 6,
          pointBackgroundColor: '#6366f1',
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });
  }

  // ¿Compraría? (doughnut)
  const buyCtx = document.getElementById('chartWouldBuy');
  if (buyCtx && DATA.wouldBuyDist && DATA.wouldBuyDist.length > 0) {
    const buyLabels = {
      'definitivamente_si': 'Definitivamente sí',
      'probablemente_si': 'Probablemente sí',
      'no_estoy_seguro': 'No estoy seguro',
      'probablemente_no': 'Probablemente no',
      'definitivamente_no': 'Definitivamente no',
    };
    const buyColors = {
      'definitivamente_si': '#10b981',
      'probablemente_si': '#6ee7b7',
      'no_estoy_seguro': '#fbbf24',
      'probablemente_no': '#f87171',
      'definitivamente_no': '#ef4444',
    };

    new Chart(buyCtx, {
      type: 'doughnut',
      data: {
        labels: DATA.wouldBuyDist.map(d => buyLabels[d.label] || d.label),
        datasets: [{
          data: DATA.wouldBuyDist.map(d => d.count),
          backgroundColor: DATA.wouldBuyDist.map(d => buyColors[d.label] || '#94a3b8'),
          borderWidth: 2,
          borderColor: '#fff',
        }]
      },
      options: {
        responsive: true,
        cutout: '55%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: { font: { size: 11, weight: '600' }, padding: 12, usePointStyle: true }
          }
        }
      }
    });
  }

  // Likelihood score (bar chart)
  const likeCtx = document.getElementById('chartLikelihood');
  if (likeCtx && DATA.likelihoodDist && DATA.likelihoodDist.length > 0) {
    const likeColors = DATA.likelihoodDist.map(d => {
      if (d.label >= 8) return '#10b981';
      if (d.label >= 6) return '#6ee7b7';
      if (d.label >= 4) return '#fbbf24';
      return '#f87171';
    });

    new Chart(likeCtx, {
      type: 'bar',
      data: {
        labels: DATA.likelihoodDist.map(d => d.label),
        datasets: [{
          label: 'Respuestas',
          data: DATA.likelihoodDist.map(d => d.count),
          backgroundColor: likeColors,
          borderRadius: 6,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: {
            grid: { display: false },
            title: { display: true, text: 'Puntuación', font: { weight: '600' } }
          },
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });
  }
})();

// Copiar enlace de encuesta
function copySurveyLink() {
  const url = document.getElementById('surveyUrl').value;
  const btn = document.getElementById('btnCopyLink');

  navigator.clipboard.writeText(url).then(() => {
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-check me-1"></i> ¡Copiado!';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-light');

    Swal.fire({
      icon: 'success',
      title: '¡Enlace copiado!',
      text: url,
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      position: 'top-end',
    });

    setTimeout(() => {
      btn.innerHTML = originalHtml;
      btn.classList.remove('btn-success');
      btn.classList.add('btn-light');
    }, 2000);
  }).catch(() => {
    // Fallback
    const input = document.createElement('input');
    input.value = url;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);

    Swal.fire({
      icon: 'success',
      title: '¡Enlace copiado!',
      text: url,
      timer: 2000,
      showConfirmButton: false,
    });
  });
}
</script>
@endpush
