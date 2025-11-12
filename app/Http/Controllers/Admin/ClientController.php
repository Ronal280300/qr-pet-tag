<?php
// app/Http/Controllers/Admin/ClientController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pet;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Validation\Rule;


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
            ->with('currentPlan') // Incluir plan actual
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

        // Órdenes del cliente (latest first)
        $orders = $user->orders()
            ->with('plan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Estadísticas básicas
        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->whereIn('status', ['verified', 'completed'])->sum('total'),
            'pending_orders' => $user->orders()->whereNotIn('status', ['verified', 'completed'])->count(),
            'total_pets' => $pets->count(),
            'active_pets' => $pets->where('is_activated', true)->count(),
        ];

        return view('admin.clients.show', compact('user', 'pets', 'orders', 'stats'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(!$user->is_admin, 403);

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:255'],
            'address'            => ['nullable', 'string', 'max:255'],
            'emergency_contact'  => ['nullable', 'string', 'max:255'],
            'status'             => ['required', 'in:active,pending,inactive'],
            // NUEVO:
            'notes'              => ['nullable', 'string'],
            'tags'               => ['nullable', 'string'], // coma separada en el form
        ]);

        // Normaliza tags (coma separada -> array)
        $tags = array_values(array_filter(array_map(
            fn($t) => trim($t),
            explode(',', (string)($data['tags'] ?? ''))
        )));
        $data['tags'] = $tags ?: null;

        if ($user->status !== $data['status']) {
            if (($data['status'] ?? null) === 'pending' && !$user->pending_since) {
                $data['pending_since'] = now();
            } else {
                $data['pending_since'] = $user->pending_since;
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

    /**
     * Acciones masivas sobre clientes seleccionados
     * action in: status_active|status_pending|status_inactive|delete|export
     */
    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids'    => ['required', 'array', 'min:1'],
            'ids.*'  => ['integer', 'exists:users,id'],
            'action' => ['required', 'string', 'in:status_active,status_pending,status_inactive,delete,export'],
        ]);

        $ids = collect($validated['ids'])->unique()->values();
        // Nunca tocar admins
        $ids = User::whereIn('id', $ids)->where('is_admin', false)->pluck('id');

        if ($ids->isEmpty()) {
            return back()->with('error', 'No hay clientes válidos para procesar.');
        }

        switch ($validated['action']) {
            case 'status_active':
            case 'status_pending':
            case 'status_inactive':
                $map = [
                    'status_active'   => 'active',
                    'status_pending'  => 'pending',
                    'status_inactive' => 'inactive',
                ];
                $new = $map[$validated['action']];
                DB::table('users')
                    ->whereIn('id', $ids)
                    ->update([
                        'status' => $new,
                        'status_changed_at' => now(),
                        'pending_since' => DB::raw($new === 'pending' ? 'COALESCE(pending_since, NOW())' : 'pending_since'),
                        'updated_at' => now(),
                    ]);
                return back()->with('success', 'Estados actualizados.');

            case 'delete':
                // Solo sin mascotas
                $noPets = User::whereIn('id', $ids)->doesntHave('pets')->pluck('id');
                $blocked = $ids->diff($noPets);

                if ($noPets->isNotEmpty()) {
                    User::whereIn('id', $noPets)->delete();
                }

                $msg = 'Clientes eliminados: ' . $noPets->count();
                if ($blocked->isNotEmpty()) {
                    $msg .= '. Bloqueados (tienen mascotas): ' . $blocked->count();
                }
                return back()->with('success', $msg);

            case 'export':
                $clients = User::withCount('pets')
                    ->whereIn('id', $ids)
                    ->orderBy('name')
                    ->get();

                $headers = [
                    'Content-Type'        => 'text/csv; charset=UTF-8',
                    'Content-Disposition' => 'attachment; filename="clientes_seleccionados.csv"',
                    'Pragma'              => 'no-cache',
                    'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                    'Expires'             => '0',
                ];

                $callback = function () use ($clients) {
                    $out = fopen('php://output', 'w');
                    // BOM UTF-8
                    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
                    // cabecera
                    fputcsv($out, ['ID', 'Nombre', 'Email', 'Teléfono', 'Estado', 'Mascotas', 'Registrado'], ';');

                    foreach ($clients as $c) {
                        $tel = preg_replace('/\D+/', '', (string)$c->phone);
                        if ($tel === '') {
                            $tel = '';
                        } elseif (!str_starts_with($tel, '506')) {
                            $tel = '+' . $tel;
                        } else {
                            $tel = '+' . $tel;
                        }
                        fputcsv($out, [
                            $c->id,
                            $c->name,
                            $c->email,
                            $tel,
                            $c->status ?? 'active',
                            $c->pets_count,
                            optional($c->created_at)->format('d/m/Y H:i'),
                        ], ';');
                    }
                    fclose($out);
                };
                return new StreamedResponse($callback, 200, $headers);
        }

        return back();
    }

    /**
     * Transferir una mascota a otro cliente
     */
    public function transferPet(Request $request, User $user, Pet $pet)
    {
        abort_unless(!$user->is_admin, 403);
        abort_unless($pet->user_id === $user->id, 404);

        $data = $request->validate([
            'to_user_id' => ['required', 'integer', 'exists:users,id'],
            'keep_qr'    => ['nullable', 'boolean'],
        ]);

        $to = User::where('is_admin', false)->findOrFail($data['to_user_id']);
        abort_if($to->id === $user->id, 422, 'El destino no puede ser el mismo cliente.');

        DB::transaction(function () use ($pet, $to, $data) {
            $pet->user_id = $to->id;
            $pet->save();

            // (Opcional) Resetear QR si NO se mantiene
            if (!(bool)($data['keep_qr'] ?? true)) {
                DB::table('qr_codes')->where('pet_id', $pet->id)->update([
                    'is_activated' => 0,
                    'activated_at' => null,
                    'activated_by' => null,
                    'updated_at'   => now(),
                ]);
            }
        });

        return back()->with('success', 'Mascota transferida correctamente.');
    }


    public function bulkTags(Request $request)
    {
        // 1) Normalizar 'mode': si viene vacío o con un valor raro, lo tratamos como null.
        $rawMode = $request->input('mode');
        if (!in_array($rawMode, ['append', 'replace'], true)) {
            $request->merge(['mode' => null]); // evita que 'in' falle con ""
        }

        // 2) Validar
        $data = $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:users,id'],
            'note'  => ['nullable', 'string', 'max:2000'],
            'tags'  => ['nullable', 'string', 'max:1000'],
            // ahora sí, si viene (no vacío) debe ser append|replace
            'mode'  => ['nullable', Rule::in(['append', 'replace'])],
        ], [
            'ids.required' => 'Selecciona al menos un cliente.',
        ]);

        $ids  = $data['ids'];
        $note = trim((string)($data['note'] ?? ''));
        $tagsInput = trim((string)($data['tags'] ?? ''));
        // 3) Valor por defecto cuando no viene modo: append
        $mode = $data['mode'] ?? 'append';

        // 4) Normalizar tags
        $tags = collect(preg_split('/[,;]+/', $tagsInput, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn($t) => trim(mb_strtolower($t)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        // 5) Aplicar cambios
        $query = User::whereIn('id', $ids)->where('is_admin', false);

        DB::transaction(function () use ($query, $note, $tags, $mode) {
            $query->lockForUpdate()->get()->each(function (User $u) use ($note, $tags, $mode) {
                if ($note !== '') {
                    $prefix = '[' . now()->format('Y-m-d H:i') . '] ';
                    $u->notes = trim(($u->notes ?? '') . ($u->notes ? "\n" : '') . $prefix . $note);
                }

                if (!empty($tags)) {
                    $current = is_array($u->labels)
                        ? $u->labels
                        : (json_decode((string)$u->labels, true) ?: []);

                    if ($mode === 'replace') {
                        $u->labels = array_values(array_unique($tags));
                    } else { // append
                        $u->labels = array_values(array_unique(array_merge($current, $tags)));
                    }
                }

                $u->save();
            });
        });

        return back()->with('success', 'Notas/etiquetas aplicadas a ' . count($ids) . ' cliente(s).');
    }

    /**
     * Enviar recordatorio manual de pago a un cliente
     */
    public function sendPaymentReminder(User $user)
    {
        abort_unless(!$user->is_admin, 403);

        if (!$user->currentPlan || !$user->plan_is_active) {
            return back()->with('error', 'El cliente no tiene un plan activo.');
        }

        try {
            \Illuminate\Support\Facades\Mail::send('emails.client.payment-reminder', [
                'user' => $user,
                'plan' => $user->currentPlan,
                'expiresAt' => $user->plan_expires_at,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Recordatorio de Pago - ' . config('app.name'));
            });

            \App\Models\EmailLog::logEmail(
                recipient: $user->email,
                subject: 'Recordatorio de Pago',
                type: 'payment_reminder',
                userId: $user->id,
                status: 'sent'
            );

            // Enviar WhatsApp al cliente
            $whatsapp = app(WhatsAppService::class);
            $whatsapp->sendPaymentReminder($user, false);

            return back()->with('success', 'Recordatorio enviado a ' . $user->name);

        } catch (\Exception $e) {
            \App\Models\EmailLog::logEmail(
                recipient: $user->email,
                subject: 'Recordatorio de Pago',
                type: 'payment_reminder',
                userId: $user->id,
                status: 'failed',
                errorMessage: $e->getMessage()
            );

            return back()->with('error', 'Error al enviar recordatorio: ' . $e->getMessage());
        }
    }
}
