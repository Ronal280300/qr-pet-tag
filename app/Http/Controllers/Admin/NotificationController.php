<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
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
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Marcar una notificación como leída
     */
    public function markAsRead(AdminNotification $notification)
    {
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída',
        ]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        AdminNotification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todas las notificaciones marcadas como leídas',
        ]);
    }

    /**
     * Ver todas las notificaciones (página completa)
     */
    public function index()
    {
        $notifications = AdminNotification::with(['order', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }
}
