<?php
// app/Http/Controllers/Admin/ClientController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $status = $request->get('status');

        $clients = User::query()
            ->where('is_admin', false)
            ->when($q, fn($qq) => $qq->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            }))
            ->when($status, fn($qq) => $qq->where('status', $status))
            ->withCount('pets')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $statusCounts = User::select('status', DB::raw('count(*) as c'))
            ->where('is_admin', false)->groupBy('status')->pluck('c', 'status');

        return view('admin.clients.index', compact('clients', 'statusCounts', 'q', 'status'));
    }

    public function show(User $user)
    {
        abort_unless(!$user->is_admin, 403);

        // Mascotas y fecha de enlace: usamos qr_codes.activated_at si existe
        $pets = Pet::query()
            ->where('user_id', $user->id)
            ->leftJoin('qr_codes', 'qr_codes.pet_id', '=', 'pets.id')
            ->select('pets.*', 'qr_codes.activated_at', 'qr_codes.activated_by', 'qr_codes.is_activated')
            ->orderBy('pets.name')
            ->get();

        return view('admin.clients.show', compact('user', 'pets'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(!$user->is_admin, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,pending,inactive'],
        ]);

        // Control de transición a Pending / Active
        if ($user->status !== $data['status']) {
            if ($data['status'] === User::STATUS_PENDING && !$user->pending_since) {
                $data['pending_since'] = now();
            } else {
                $data['pending_since'] = $user->pending_since; // conserva si no aplica
            }
            $data['status_changed_at'] = now();
        }

        $user->fill($data)->save();

        return back()->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(\Illuminate\Http\Request $request, \App\Models\User $user)
    {
        // Solo clientes (no admins)
        abort_unless(!$user->is_admin, 403);

        // Bloquea si aún tiene mascotas
        if ($user->pets()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un cliente con mascotas vinculadas. Desenlaza sus mascotas primero.');
        }

        $user->delete();

        return redirect()
            ->route('portal.admin.clients.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }


    public function detachPet(Request $request, User $user, Pet $pet)
    {
        abort_unless(!$user->is_admin, 403);
        abort_unless($pet->user_id === $user->id, 404);

        $resetQr = (bool)$request->boolean('reset_qr');

        DB::transaction(function () use ($pet, $resetQr) {
            $pet->user_id = null;
            $pet->save();

            if ($resetQr) {
                DB::table('qr_codes')
                    ->where('pet_id', $pet->id)
                    ->update([
                        'is_activated' => 0,
                        'activated_at' => null,
                        'activated_by' => null,
                        'updated_at' => now(),
                    ]);
            }
        });

        return back()->with('success', 'Mascota desenlazada del cliente.');
    }
    public function exportCsv(Request $request)
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $q      = trim((string) $request->get('q', ''));
        $status = $request->get('status');

        $clients = \App\Models\User::query()
            ->where('is_admin', false)
            ->when($q, fn($qq) => $qq->where(function ($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            }))
            ->when($status, fn($qq) => $qq->where('status', $status))
            ->withCount('pets')
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone', 'status', 'created_at']);

        $filename = 'clientes_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($clients) {
            $out = fopen('php://output', 'w');

            fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fwrite($out, "sep=;\r\n");                    // separador para Excel


            // Encabezados (con ; como delimitador)
            fputcsv($out, ['ID', 'Nombre', 'Email', 'Teléfono', 'Estado', 'Mascotas', 'Registrado'], ';');

            foreach ($clients as $c) {
                $estado = match ($c->status) {
                    'active'   => 'Activo',
                    'pending'  => 'Pendiente',
                    'inactive' => 'Inactivo',
                    default    => $c->status,
                };

                // 👇 Fuerza el teléfono como TEXTO en Excel (no lo mostrará el apóstrofo)
                $telefono = $c->phone ? "'" . $c->phone : '';

                fputcsv($out, [
                    $c->id,
                    $c->name,
                    $c->email,
                    $telefono,                 // <- usa la variable con apóstrofo
                    $estado,
                    $c->pets_count,
                    optional($c->created_at)->format('d/m/Y H:i'),
                ], ';');
            }


            fclose($out);
        }, $filename, [
            // Estos headers funcionan bien con Excel
            'Content-Type'  => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }
}
