<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pet;
use App\Models\Reward;
use App\Models\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Solo admins
        if (! (bool)(Auth::user()->is_admin ?? false)) {
            abort(403, 'Solo administradores.');
        }

        // Métricas puntuales
        $totalUsers      = User::count();
        $newUsers30d     = User::where('created_at', '>=', now()->subDays(30))->count();

        $totalPets       = Pet::count();
        $lostPets        = Pet::where('is_lost', true)->count();

        // Recompensas
        $activeRewards        = Reward::where('active', true)->count();
        $activeRewardsAmount  = (float) Reward::where('active', true)->sum('amount');

        // QRs
        $qrsGenerated     = QrCode::whereNotNull('image')->count();

        // Mascotas sin QR (no tienen registro o su QR no tiene imagen)
        $withQrPetIds = QrCode::whereNotNull('image')->pluck('pet_id')->filter()->unique()->all();
        $petsWithoutQr = Pet::when(count($withQrPetIds) > 0, fn($q) => $q->whereNotIn('id', $withQrPetIds))->count();

        // Ultimas mascotas registradas
        $recentPets = Pet::latest('id')->take(5)->get();

        // Series por mes (últimos 12 meses)
        $from = Carbon::now()->startOfMonth()->subMonths(11);
        $labels = [];
        for ($i = 0; $i < 12; $i++) {
            $m = (clone $from)->addMonths($i);
            $labels[] = $m->format('Y-m'); // eje X (YYYY-MM)
        }

        // Pets por mes
        $petsByMonthRaw = Pet::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
                DB::raw('COUNT(*) as c')
            )
            ->where('created_at', '>=', $from)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $petsSeries = array_map(fn($ym) => (int)($petsByMonthRaw[$ym]->c ?? 0), $labels);

        // QRs generados por mes (tomamos created_at del registro QR con image no null)
        $qrsByMonthRaw = QrCode::whereNotNull('image')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
                DB::raw('COUNT(*) as c')
            )
            ->where('created_at', '>=', $from)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $qrsSeries = array_map(fn($ym) => (int)($qrsByMonthRaw[$ym]->c ?? 0), $labels);

        // Perdidos por mes (opcional)
        $lostByMonthRaw = Pet::where('is_lost', true)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym'),
                DB::raw('COUNT(*) as c')
            )
            ->where('created_at', '>=', $from)
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->keyBy('ym');

        $lostSeries = array_map(fn($ym) => (int)($lostByMonthRaw[$ym]->c ?? 0), $labels);

        return view('admin.dashboard', [
            // KPIs
            'totalUsers'          => $totalUsers,
            'newUsers30d'         => $newUsers30d,
            'totalPets'           => $totalPets,
            'lostPets'            => $lostPets,
            'activeRewards'       => $activeRewards,
            'activeRewardsAmount' => $activeRewardsAmount,
            'qrsGenerated'        => $qrsGenerated,
            'petsWithoutQr'       => $petsWithoutQr,
            'recentPets'          => $recentPets,

            // Series
            'labels'              => $labels,
            'petsSeries'          => $petsSeries,
            'qrsSeries'           => $qrsSeries,
            'lostSeries'          => $lostSeries,
        ]);
    }
}
