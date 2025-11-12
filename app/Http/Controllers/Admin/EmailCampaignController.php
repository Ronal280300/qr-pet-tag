<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\EmailTemplate;
use App\Models\CampaignRecipient;
use App\Models\EmailLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class EmailCampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminOnly::class);
    }

    /**
     * Listar todas las campañas
     */
    public function index()
    {
        $campaigns = EmailCampaign::with(['template', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('portal.admin.email-campaigns.index', compact('campaigns'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $templates = EmailTemplate::active()->get();

        return view('portal.admin.email-campaigns.create', compact('templates'));
    }

    /**
     * Previsualizar destinatarios según filtros
     */
    public function previewRecipients(Request $request)
    {
        $filterType = $request->input('filter_type');
        $noScansDays = $request->input('no_scans_days', 30);
        $paymentDueDays = $request->input('payment_due_days', 5);

        // Crear campaña temporal para usar el método getFilteredRecipients
        $tempCampaign = new EmailCampaign([
            'filter_type' => $filterType,
            'no_scans_days' => $noScansDays,
            'payment_due_days' => $paymentDueDays,
            'filter_config' => $request->input('filter_config', []),
        ]);

        $recipients = $tempCampaign->getFilteredRecipients();

        return response()->json([
            'count' => $recipients->count(),
            'recipients' => $recipients->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'pets_count' => $user->pets->count(),
                ];
            }),
        ]);
    }

    /**
     * Guardar nueva campaña
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email_template_id' => 'required|exists:email_templates,id',
            'filter_type' => 'required|in:all,no_scans,payment_due,custom',
            'no_scans_days' => 'nullable|integer|min:1',
            'payment_due_days' => 'nullable|integer|min:1',
            'filter_config' => 'nullable|array',
            'send_now' => 'nullable|boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'draft';

        $campaign = EmailCampaign::create($validated);

        // Si se marca "enviar ahora", redirigir a confirmación
        if ($request->boolean('send_now')) {
            return redirect()->route('portal.admin.email-campaigns.confirm', $campaign);
        }

        return redirect()
            ->route('portal.admin.email-campaigns.index')
            ->with('success', 'Campaña creada como borrador');
    }

    /**
     * Mostrar detalles de una campaña
     */
    public function show(EmailCampaign $emailCampaign)
    {
        $emailCampaign->load(['template', 'creator', 'recipients.user']);

        return view('portal.admin.email-campaigns.show', compact('emailCampaign'));
    }

    /**
     * Confirmar envío de campaña
     */
    public function confirm(EmailCampaign $emailCampaign)
    {
        if ($emailCampaign->status !== 'draft') {
            return redirect()
                ->route('portal.admin.email-campaigns.show', $emailCampaign)
                ->with('error', 'Solo se pueden enviar campañas en estado borrador');
        }

        $recipients = $emailCampaign->getFilteredRecipients();

        return view('portal.admin.email-campaigns.confirm', compact('emailCampaign', 'recipients'));
    }

    /**
     * Enviar campaña
     */
    public function send(Request $request, EmailCampaign $emailCampaign)
    {
        if ($emailCampaign->status !== 'draft') {
            return redirect()
                ->back()
                ->with('error', 'Esta campaña ya fue enviada o está en proceso');
        }

        DB::beginTransaction();

        try {
            // Obtener destinatarios filtrados
            $recipients = $emailCampaign->getFilteredRecipients();

            // Si vienen usuarios seleccionados manualmente, filtrar solo esos
            if ($request->has('selected_recipients')) {
                $selectedIds = $request->input('selected_recipients', []);

                if (empty($selectedIds)) {
                    return redirect()
                        ->back()
                        ->with('error', 'Debes seleccionar al menos un destinatario.');
                }

                // Filtrar solo los usuarios seleccionados
                $recipients = $recipients->whereIn('id', $selectedIds);
            }

            if ($recipients->count() === 0) {
                return redirect()
                    ->back()
                    ->with('error', 'No hay destinatarios válidos para enviar.');
            }

            // Actualizar campaña
            $emailCampaign->update([
                'status' => 'sending',
                'total_recipients' => $recipients->count(),
                'started_at' => now(),
            ]);

            // Crear registros de destinatarios
            foreach ($recipients as $user) {
                CampaignRecipient::create([
                    'email_campaign_id' => $emailCampaign->id,
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'status' => 'pending',
                ]);
            }

            DB::commit();

            // Enviar correos en segundo plano (o en el mismo request para demo)
            $this->processCampaign($emailCampaign);

            return redirect()
                ->route('portal.admin.email-campaigns.show', $emailCampaign)
                ->with('success', 'Campaña enviándose. Se procesarán ' . $recipients->count() . ' destinatarios.');

        } catch (\Exception $e) {
            DB::rollBack();

            $emailCampaign->update(['status' => 'failed']);

            return redirect()
                ->back()
                ->with('error', 'Error al enviar campaña: ' . $e->getMessage());
        }
    }

    /**
     * Procesar envío de campaña
     */
    protected function processCampaign(EmailCampaign $campaign)
    {
        $pendingRecipients = $campaign->recipients()->where('status', 'pending')->get();

        foreach ($pendingRecipients as $recipient) {
            try {
                // Renderizar HTML con datos del usuario
                $html = $campaign->template->renderForUser($recipient->user);

                // Enviar email
                Mail::send([], [], function ($message) use ($recipient, $campaign, $html) {
                    $message->to($recipient->email)
                        ->subject($campaign->template->subject)
                        ->html($html);
                    $message->getSwiftMessage()->setContentType('text/html; charset=UTF-8');
                });

                // Actualizar recipient
                $recipient->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);

                // Crear log de email
                EmailLog::logEmail(
                    recipient: $recipient->email,
                    subject: $campaign->template->subject,
                    type: 'campaign',
                    orderId: null,
                    userId: $recipient->user->id,
                    status: 'sent',
                    errorMessage: null
                );

                // Incrementar contador
                $campaign->increment('sent_count');

            } catch (\Exception $e) {
                // Marcar como fallido
                $recipient->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);

                // Log de error
                EmailLog::logEmail(
                    recipient: $recipient->email,
                    subject: $campaign->template->subject,
                    type: 'campaign',
                    orderId: null,
                    userId: $recipient->user->id,
                    status: 'failed',
                    errorMessage: $e->getMessage()
                );

                // Incrementar contador de fallidos
                $campaign->increment('failed_count');
            }
        }

        // Actualizar campaña como completada
        $campaign->update([
            'status' => 'sent',
            'completed_at' => now(),
        ]);
    }

    /**
     * Eliminar campaña
     */
    public function destroy(EmailCampaign $emailCampaign)
    {
        if ($emailCampaign->status === 'sending') {
            return redirect()
                ->back()
                ->with('error', 'No se puede eliminar una campaña que se está enviando');
        }

        $emailCampaign->delete();

        return redirect()
            ->route('portal.admin.email-campaigns.index')
            ->with('success', 'Campaña eliminada exitosamente');
    }
}
