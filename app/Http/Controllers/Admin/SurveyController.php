<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function index()
    {
        // KPIs principales
        $totalResponses = SurveyResponse::count();
        $responsesLast7d = SurveyResponse::recent(7)->count();
        $responsesLast30d = SurveyResponse::recent(30)->count();

        // Intención de compra
        $wouldBuyCount = SurveyResponse::wouldBuy()->count();
        $wouldBuyPercentage = $totalResponses > 0
            ? round(($wouldBuyCount / $totalResponses) * 100, 1)
            : 0;

        // Likelihood score promedio
        $avgLikelihood = SurveyResponse::whereNotNull('likelihood_score')
            ->avg('likelihood_score');
        $avgLikelihood = $avgLikelihood ? round($avgLikelihood, 1) : 0;

        // Emails capturados
        $emailsCaptured = SurveyResponse::whereNotNull('email')
            ->where('email', '!=', '')
            ->count();

        // Distribuciones para gráficos
        $hasPetsDist = SurveyResponse::distribution('has_pets');
        $petTypeDist = SurveyResponse::distribution('pet_type');
        $mainConcernDist = SurveyResponse::distribution('main_concern');
        $lostPetDist = SurveyResponse::distribution('lost_pet_before');
        $wouldBuyDist = SurveyResponse::distribution('would_buy');
        $priceRangeDist = SurveyResponse::distribution('price_range');

        // Likelihood score distribution
        $likelihoodDist = SurveyResponse::selectRaw('likelihood_score as label, COUNT(*) as total')
            ->whereNotNull('likelihood_score')
            ->groupBy('likelihood_score')
            ->orderBy('likelihood_score')
            ->get()
            ->map(function ($row) use ($totalResponses) {
                return [
                    'label' => $row->label,
                    'count' => $row->total,
                    'percentage' => $totalResponses > 0
                        ? round(($row->total / $totalResponses) * 100, 1)
                        : 0,
                ];
            })
            ->toArray();

        // Serie temporal: respuestas por día últimos 30 días
        $from = Carbon::now()->subDays(29)->startOfDay();
        $dailyLabels = [];
        for ($i = 0; $i < 30; $i++) {
            $dailyLabels[] = (clone $from)->addDays($i)->format('d/m');
        }

        $dailyRaw = SurveyResponse::select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('COUNT(*) as c')
            )
            ->where('created_at', '>=', $from)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $dailySeries = [];
        for ($i = 0; $i < 30; $i++) {
            $day = (clone $from)->addDays($i)->format('Y-m-d');
            $dailySeries[] = (int)($dailyRaw[$day]->c ?? 0);
        }

        // Últimas respuestas
        $recentResponses = SurveyResponse::latest()->take(10)->get();

        // URL del formulario público
        $surveyUrl = route('survey.public');

        return view('admin.survey.index', compact(
            'totalResponses',
            'responsesLast7d',
            'responsesLast30d',
            'wouldBuyCount',
            'wouldBuyPercentage',
            'avgLikelihood',
            'emailsCaptured',
            'hasPetsDist',
            'petTypeDist',
            'mainConcernDist',
            'lostPetDist',
            'wouldBuyDist',
            'priceRangeDist',
            'likelihoodDist',
            'dailyLabels',
            'dailySeries',
            'recentResponses',
            'surveyUrl'
        ));
    }
}
