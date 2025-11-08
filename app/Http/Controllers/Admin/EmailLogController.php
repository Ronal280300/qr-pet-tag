<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Mostrar listado de logs de correos
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all'); // all, sent, failed
        $search = $request->get('search');

        $query = EmailLog::with(['order', 'user'])
            ->orderBy('created_at', 'desc');

        // Filtrar por estado
        if ($status === 'sent') {
            $query->sent();
        } elseif ($status === 'failed') {
            $query->failed();
        }

        // Búsqueda
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('recipient', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('error_message', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(50);

        // Estadísticas
        $stats = [
            'total' => EmailLog::count(),
            'sent' => EmailLog::sent()->count(),
            'failed' => EmailLog::failed()->count(),
            'today' => EmailLog::getDailyCount(),
            'month' => EmailLog::getMonthlyCount(),
        ];

        return view('portal.admin.email-logs.index', compact('logs', 'stats', 'status', 'search'));
    }

    /**
     * Ver detalles de un log específico
     */
    public function show(EmailLog $log)
    {
        $log->load(['order', 'user']);
        return view('portal.admin.email-logs.show', compact('log'));
    }
}
