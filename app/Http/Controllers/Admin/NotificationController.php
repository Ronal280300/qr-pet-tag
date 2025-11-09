<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminOnly::class);
    }

    /**
     * Obtener notificaciones no leídas (para el dropdown)
     */
    public function getUnread()
    {
        $notifications = AdminNotification::unread()
            ->with(['order', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $unreadCount = AdminNotification::unread()->count();

        return response()->json([
            'count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function markAsRead(AdminNotification $notification)
    {
        $notification->markAsRead();

        return redirect()
            ->back()
            ->with('success', 'Notificación marcada como leída');
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        $count = AdminNotification::unread()->count();

        AdminNotification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', "Se marcaron {$count} notificaciones como leídas");
    }

    /**
     * Ver todas las notificaciones (página completa)
     */
    public function index()
    {
        $notifications = AdminNotification::with(['order', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('portal.admin.notifications.index', compact('notifications'));
    }
}
