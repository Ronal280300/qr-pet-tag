<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Services\PetQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TagController extends Controller
{
    private function ensureAdmin(): void
    {
        if (! (Auth::user()->is_admin ?? false)) {
            abort(403, 'Solo administradores.');
        }
    }

    public function index(Request $request)
    {
        $this->ensureAdmin();

        $q        = trim((string) $request->get('q', ''));
        $activated= $request->get('activated', 'all'); // all|1|0
        $assigned = $request->get('assigned', 'all');  // all|1|0

        $query = QrCode::with(['pet.user'])->latest('id');

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('slug', 'like', "%{$q}%")
                   ->orWhere('activation_code', 'like', "%{$q}%")
                   ->orWhereHas('pet', function ($pp) use ($q) {
                       $pp->where('name', 'like', "%{$q}%");
                   });
            });
        }

        if ($activated === '1')     $query->where('is_activated', true);
        elseif ($activated === '0') $query->where('is_activated', false);

        if     ($assigned === '1')  $query->whereNotNull('pet_id');
        elseif ($assigned === '0')  $query->whereNull('pet_id');

        $tags = $query->paginate(20)->withQueryString();

        return view('admin.tags.index', compact('tags', 'q', 'activated', 'assigned'));
    }

    public function regenCode(QrCode $qr, PetQrService $svc)
    {
        $this->ensureAdmin();

        $qr->activation_code = $svc->generateUniqueActivationCode();
        $qr->save();

        return back()->with('status', 'Código de activación reemitido.');
    }

    public function rebuild(QrCode $qr, PetQrService $svc)
    {
        $this->ensureAdmin();

        if (!$qr->pet) {
            return back()->with('error', 'Este TAG no está asociado a una mascota.');
        }

        // Reconstruye URL e imagen (mantiene slug / code)
        $svc->ensureSlugAndImage($qr, $qr->pet);

        return back()->with('status', 'Imagen del QR regenerada.');
    }

    public function download(QrCode $qr): StreamedResponse
    {
        $this->ensureAdmin();

        if (!$qr->image || !Storage::disk('public')->exists($qr->image)) {
            abort(404, 'QR no disponible.');
        }

        $filename     = 'qr-' . ($qr->pet->name ?? $qr->slug) . '.' . pathinfo($qr->image, PATHINFO_EXTENSION);
        $absolutePath = Storage::disk('public')->path($qr->image);
        $mime         = File::exists($absolutePath) ? (File::mimeType($absolutePath) ?: 'application/octet-stream') : 'application/octet-stream';

        return response()->streamDownload(function () use ($absolutePath) {
            readfile($absolutePath);
        }, $filename, [
            'Content-Type'  => $mime,
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $this->ensureAdmin();

        $filename = 'tags_export_'.now()->format('Ymd_His').'.csv';

        $query = QrCode::with(['pet.user'])->orderBy('id');

        if ($q = trim((string) $request->get('q', ''))) {
            $query->where(function ($qq) use ($q) {
                $qq->where('slug', 'like', "%{$q}%")
                   ->orWhere('activation_code', 'like', "%{$q}%")
                   ->orWhereHas('pet', function ($pp) use ($q) {
                       $pp->where('name', 'like', "%{$q}%");
                   });
            });
        }

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID','SLUG','ACTIVATION_CODE','ACTIVATED','ACTIVATED_AT','PET','OWNER','PUBLIC_URL','IMAGE']);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->slug,
                        $r->activation_code,
                        $r->is_activated ? '1' : '0',
                        optional($r->activated_at)->format('Y-m-d H:i:s'),
                        optional($r->pet)->name,
                        optional(optional($r->pet)->user)->name,
                        $r->qr_code,
                        $r->image,
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}