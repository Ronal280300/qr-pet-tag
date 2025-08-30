<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TagController extends Controller
{
    /**
     * Inventario de TAGs (QRs)
     * La vista espera: $tags, $stats, $q, $status
     */
    public function index(Request $request)
    {
        // Valores que usa la vista en los inputs
        $q      = trim((string) $request->input('q', ''));
        $status = trim((string) $request->input('status', ''));

        $query = QrCode::with(['pet:id,name']);

        // Filtro de búsqueda
        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('slug', 'like', "%{$q}%")
                   ->orWhere('activation_code', 'like', "%{$q}%")
                   ->orWhereHas('pet', function ($p) use ($q) {
                       $p->where('name', 'like', "%{$q}%");
                   });
            });
        }

        // Filtro por estado (coincide con los options de la vista)
        switch ($status) {
            case 'assigned':
                $query->whereNotNull('pet_id');
                break;
            case 'unassigned':
                $query->whereNull('pet_id');
                break;
            case 'with_image':
                $query->whereNotNull('image');
                break;
            case 'without_image':
                $query->whereNull('image');
                break;
            default:
                // "Todos" => sin condición extra
                break;
        }

        // ⚠️ La vista usa $tags, no $qrs
        $tags = $query->latest('id')->paginate(25)->withQueryString();

        // KPIs (nombres que la vista espera)
        $stats = [
            'total'       => QrCode::count(),
            'assigned'    => QrCode::whereNotNull('pet_id')->count(),
            'unassigned'  => QrCode::whereNull('pet_id')->count(),
            'with_image'  => QrCode::whereNotNull('image')->count(),
        ];

        return view('admin.tags.index', compact('tags', 'stats', 'q', 'status'));
    }

    /**
     * Exportar CSV
     */
    public function exportCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="tags.csv"',
        ];

        $callback = function () {
            $out = fopen('php://output', 'w');

            // BOM UTF-8
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            // Encabezados
            fputcsv($out, ['id', 'slug', 'activation_code', 'pet_id', 'pet_name', 'image']);

            QrCode::with('pet:id,name')
                ->orderBy('id')
                ->chunk(500, function ($rows) use ($out) {
                    foreach ($rows as $qr) {
                        fputcsv($out, [
                            $qr->id,
                            $qr->slug,
                            $qr->activation_code,
                            $qr->pet_id,
                            optional($qr->pet)->name,
                            $qr->image,
                        ]);
                    }
                });

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Regenerar el código de activación
     */
    public function regenCode(QrCode $qr)
    {
        if (method_exists(QrCode::class, 'generateActivationCode')) {
            $qr->activation_code = QrCode::generateActivationCode();
        } else {
            $qr->activation_code = strtoupper(Str::random(6));
        }

        $qr->save();

        return back()->with('success', 'Código de activación regenerado correctamente.');
    }

    /**
     * Reconstruir imagen del QR (placeholder; ajusta a tu generador real)
     */
    public function rebuild(QrCode $qr)
    {
        // Aquí iría la regeneración real de la imagen
        if (!$qr->image) {
            $qr->image = 'qrs/'.$qr->id.'.png';
        }
        $qr->save();

        return back()->with('success', 'Imagen del QR reconstruida.');
    }

    /**
     * Descargar imagen del QR, si existe
     */
    public function download(QrCode $qr)
    {
        if (!$qr->image || !Storage::disk('public')->exists($qr->image)) {
            return back()->with('danger', 'No hay imagen del QR para descargar.');
        }

        $path = Storage::disk('public')->path($qr->image);
        return response()->download($path);
    }

    /**
     * Crear TAGs faltantes para mascotas sin QR (backfill)
     */
    public function backfill()
    {
        $petsMissing = Pet::whereDoesntHave('qrCode')->get(['id', 'name']);

        $created = 0;

        foreach ($petsMissing as $pet) {
            $base = Str::slug($pet->name ?: 'pet', '-');
            if ($base === '') {
                $base = 'pet';
            }

            // Unicidad de slug en qr_codes
            $slug = $base;
            $i = 1;
            while (QrCode::where('slug', $slug)->exists()) {
                $slug = "{$base}-{$i}";
                $i++;
            }

            $qr = new QrCode();
            $qr->pet_id = $pet->id;
            $qr->slug   = $slug;

            if (method_exists(QrCode::class, 'generateActivationCode')) {
                $qr->activation_code = QrCode::generateActivationCode();
            } else {
                $qr->activation_code = strtoupper(Str::random(6));
            }

            // La imagen la generará tu servicio cuando corresponda
            $qr->save();
            $created++;
        }

        if ($created === 0) {
            return back()->with('info', 'No había mascotas pendientes. Todo está sincronizado.');
        }

        return back()->with('success', "Se crearon {$created} TAG(s) faltantes.");
    }
}
