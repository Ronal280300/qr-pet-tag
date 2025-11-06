<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\User;
use App\Models\Reward;
use App\Models\QrCode;
use App\Models\Scan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $u = Auth::user();

        // =============== Métricas personales ===============
        $myPetIds = Pet::where('user_id', $u->id)->pluck('id');
        $myQrIds = QrCode::whereIn('pet_id', $myPetIds)->pluck('id');

        $my = [
            'pets'         => $myPetIds->count(),
            'lost'         => Pet::where('user_id', $u->id)->where('is_lost', 1)->count(),
            'rewards'      => Reward::whereIn('pet_id', $myPetIds)->where('is_active', 1)->count(),
            'scans_today'  => Scan::whereIn('qr_code_id', $myQrIds)
                                  ->whereDate('created_at', now()->toDateString())
                                  ->count(),
            'scans_total'  => Scan::whereIn('qr_code_id', $myQrIds)->count(),
            'scans_week'   => Scan::whereIn('qr_code_id', $myQrIds)
                                  ->where('created_at', '>=', now()->subDays(7))
                                  ->count(),
        ];

        // Últimos 10 escaneos de mis TAGs (ordenados por fecha)
        $myRecentScans = Scan::with(['qrCode.pet:id,name'])
            ->whereIn('qr_code_id', $myQrIds)
            ->latest('created_at')
            ->take(10)
            ->get();

        // Mascota más escaneada
        $topScannedPet = null;
        if ($myQrIds->isNotEmpty()) {
            $topScanned = Scan::whereIn('qr_code_id', $myQrIds)
                ->select('qr_code_id', \DB::raw('COUNT(*) as scan_count'))
                ->groupBy('qr_code_id')
                ->orderByDesc('scan_count')
                ->first();

            if ($topScanned) {
                $qr = QrCode::with('pet:id,name')->find($topScanned->qr_code_id);
                if ($qr && $qr->pet) {
                    $topScannedPet = [
                        'name' => $qr->pet->name,
                        'scans' => $topScanned->scan_count,
                    ];
                }
            }
        }

        // =============== Si es admin: métricas globales + charts ===============
        $global = null;
        $charts = null;

        if ($u->is_admin) {
            $global = [
                'users'       => User::count(),
                'pets'        => Pet::count(),
                'lost'        => Pet::where('is_lost', 1)->count(),
                'rewards'     => Reward::where('is_active', 1)->count(),
                'qrs_total'   => QrCode::count(),
                'qrs_with_img'=> QrCode::whereNotNull('image')->count(),
                'scans_today' => Scan::whereDate('created_at', now()->toDateString())->count(),
            ];

            // --- Mascotas registradas por mes (últimos 12 meses) ---
            $labelsMonths = [];
            $dataPets     = [];
            for ($i = 11; $i >= 0; $i--) {
                $m = now()->copy()->subMonths($i);
                $labelsMonths[] = $m->format('Y-m');
                $dataPets[]     = Pet::whereYear('created_at', $m->year)
                                      ->whereMonth('created_at', $m->month)
                                      ->count();
            }

            // --- Escaneos últimos 14 días ---
            $labelsDays = [];
            $dataScans  = [];
            for ($i = 13; $i >= 0; $i--) {
                $d = now()->copy()->subDays($i)->startOfDay();
                $labelsDays[] = $d->format('Y-m-d');
                $dataScans[]  = Scan::whereDate('created_at', $d->toDateString())->count();
            }

            $charts = [
                'labelsMonths' => $labelsMonths,
                'dataPets'     => $dataPets,
                'labelsDays'   => $labelsDays,
                'dataScans'    => $dataScans,
            ];
        }

        return view('portal.dashboard', [
            'u'             => $u,
            'my'            => $my,
            'myRecentScans' => $myRecentScans,
            'topScannedPet' => $topScannedPet,
            'global'        => $global,
            'charts'        => $charts,
        ]);
    }
}
