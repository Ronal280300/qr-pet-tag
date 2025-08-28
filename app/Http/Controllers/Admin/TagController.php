<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrCode;
use App\Services\PetQrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TagController extends Controller
{
    /**
     * Listado con filtros/búsqueda.
     */
    public function index(Request $request)
    {
        $q      = trim($request->input('q', ''));
        $status = $request->input('status', ''); // assigned|unassigned|with_image|without_image

        $query = QrCode::query()
            ->with(['pet:id,name,slug'])
            ->latest('id');

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('activation_code', 'like', "%{$q}%")
                   ->orWhere('slug', 'like', "%{$q}%")
                   ->orWhereHas('pet', function ($qp) use ($q) {
                       $qp->where('name', 'like', "%{$q}%");
                   });
            });
        }

        if ($status === 'assigned') {
            $query->whereNotNull('pet_id');
        } elseif ($status === 'unassigned') {
            $query->whereNull('pet_id');
        } elseif ($status === 'with_image') {
            $query->whereNotNull('image');
        } elseif ($status === 'without_image') {
            $query->whereNull('image');
        }

        $tags = $query->paginate(20)->withQueryString();

        $stats = [
            'total'      => QrCode::count(),
            'assigned'   => QrCode::whereNotNull('pet_id')->count(),
            'unassigned' => QrCode::whereNull('pet_id')->count(),
            'with_image' => QrCode::whereNotNull('image')->count(),
        ];

        return view('admin.tags.index', compact('tags', 'q', 'status', 'stats'));
    }

    /**
     * Exportar CSV con los mismos filtros de index.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $q      = trim($request->input('q', ''));
        $status = $request->input('status', '');

        $query = QrCode::query()->with('pet:id,name');

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('activation_code', 'like', "%{$q}%")
                   ->orWhere('slug', 'like', "%{$q}%")
                   ->orWhereHas('pet', fn ($qp) => $qp->where('name', 'like', "%{$q}%"));
            });
        }
        if ($status === 'assigned')       $query->whereNotNull('pet_id');
        elseif ($status === 'unassigned') $query->whereNull('pet_id');
        elseif ($status === 'with_image') $query->whereNotNull('image');
        elseif ($status === 'without_image') $query->whereNull('image');

        $filename = 'tags_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($query) {
            $out = fopen('php://output', 'w');
            // BOM para Excel
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['ID', 'Código (TAG)', 'Slug', 'Mascota', 'Asignado', 'Imagen', 'Actualizado']);

            $query->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->activation_code,
                        $r->slug,
                        optional($r->pet)->name ?: '',
                        $r->pet_id ? 'Sí' : 'No',
                        $r->image ? 'Sí' : 'No',
                        optional($r->updated_at)->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, 200, $headers);
    }

    /**
     * Regenerar el CÓDIGO (TAG).
     */
    public function regenCode(QrCode $qr)
    {
        // Ojo: si está asignado, puedes requerir confirmación o evitarlo. Aquí lo permitimos.
        $qr->activation_code = QrCode::generateActivationCode();
        $qr->save();

        return back()->with('status', 'Código (TAG) regenerado.');
    }

    /**
     * Reconstruir imagen de QR (requiere que el TAG tenga slug y, si está asignado, enlaza al perfil público).
     */
    public function rebuild(QrCode $qr, PetQrService $qrService)
    {
        // Si no hay mascota asignada, podemos aún generar el QR con la URL pública basada en slug.
        if (!$qr->slug) {
            // Garantizamos slug si falta
            $qr->slug = $qr->slug ?: $qr->id.'-'.str()->slug('tag');
            $qr->save();
        }

        // Si la mascota existe, usamos el servicio estándar que ya usas en el flujo de mascotas.
        if ($qr->pet) {
            $qrService->ensureSlugAndImage($qr, $qr->pet);
        } else {
            // Sin mascota: construimos QR a la URL pública del slug
            $publicUrl = route('public.pet.show', $qr->slug);
            // Reutiliza el servicio si tiene método que acepte solo qr+url;
            // si no, puedes crear aquí el PNG/SVG con la librería que ya usas.
            // Para no duplicar lógica, intentamos:
            $qrService->buildFromUrl($qr, $publicUrl); // Si no existe este método, usa tu implementación actual para generar PNG/SVG y guarda $qr->image
        }

        return back()->with('status', 'Imagen del QR reconstruida.');
    }

    /**
     * Descargar imagen asociada al TAG.
     */
    public function download(QrCode $qr)
    {
        if (!$qr->image || !Storage::disk('public')->exists($qr->image)) {
            abort(404, 'Imagen no disponible.');
        }
        $absolute = Storage::disk('public')->path($qr->image);
        $filename = 'tag-'.$qr->id.'.'.pathinfo($absolute, PATHINFO_EXTENSION);

        return response()->download($absolute, $filename, [
            'Content-Type'  => mime_content_type($absolute) ?: 'application/octet-stream',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }
}
