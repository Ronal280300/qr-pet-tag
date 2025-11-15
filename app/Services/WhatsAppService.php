<?php

namespace App\Services;

use App\Models\WhatsAppLog;
use App\Models\Order;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class WhatsAppService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        // Primero intentar obtener de settings, luego fallback a config
        $sid = $this->setting('twilio_sid') ?: config('services.twilio.sid');
        $token = $this->setting('twilio_token') ?: config('services.twilio.token');
        $this->from = $this->setting('twilio_whatsapp_from') ?: config('services.twilio.whatsapp_from');

        if ($sid && $token) {
            $this->client = new Client($sid, $token);
        }
    }

    /**
     * Helper para obtener settings (fallback si el helper global no está cargado)
     */
    private function setting(string $key, $default = null)
    {
        if (function_exists('setting')) {
            return setting($key, $default);
        }
        return Setting::get($key, $default);
    }

    /**
     * Enviar mensaje de WhatsApp genérico
     */
    public function send(string $to, string $message, string $type = 'general', ?int $orderId = null, ?int $userId = null): bool
    {
        // Verificar si WhatsApp está habilitado en configuración
        if (!$this->setting('twilio_enabled', true) || !$this->setting('notifications_whatsapp_enabled', true)) {
            Log::info('WhatsApp notifications disabled in settings');
            return false;
        }

        if (!$this->client) {
            Log::warning('WhatsApp service not configured');
            return false;
        }

        try {
            // Formatear número para Costa Rica
            $to = $this->formatPhoneNumber($to);

            // Enviar mensaje
            $response = $this->client->messages->create(
                "whatsapp:{$to}",
                [
                    'from' => "whatsapp:{$this->from}",
                    'body' => $message
                ]
            );

            // Registrar en logs
            WhatsAppLog::create([
                'recipient' => $to,
                'message' => $message,
                'type' => $type,
                'order_id' => $orderId,
                'user_id' => $userId,
                'status' => 'sent',
                'twilio_sid' => $response->sid,
                'sent_at' => now(),
            ]);

            Log::info('WhatsApp sent', [
                'to' => $to,
                'type' => $type,
                'sid' => $response->sid
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('WhatsApp send failed', [
                'to' => $to,
                'type' => $type,
                'error' => $e->getMessage()
            ]);

            // Registrar fallo
            WhatsAppLog::create([
                'recipient' => $to,
                'message' => $message,
                'type' => $type,
                'order_id' => $orderId,
                'user_id' => $userId,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Formatear número de teléfono para Costa Rica
     * Acepta formatos: 88888888, +50688888888, 50688888888
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Remover caracteres no numéricos excepto +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        // Si ya tiene +506, retornar
        if (str_starts_with($phone, '+506')) {
            return $phone;
        }

        // Si tiene 506 al inicio, agregar +
        if (str_starts_with($phone, '506')) {
            return '+' . $phone;
        }

        // Si es número de 8 dígitos, agregar +506
        if (strlen($phone) === 8) {
            return '+506' . $phone;
        }

        // Si ya tiene +, retornar como está
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        // Por defecto, agregar + si no lo tiene
        return '+' . $phone;
    }

    /**
     * Mensaje cuando cliente sube comprobante de pago
     */
    public function sendPaymentUploadedToAdmin(Order $order): bool
    {
        $adminPhone = $this->setting('twilio_admin_phone') ?: config('services.twilio.admin_phone');
        if (!$adminPhone) {
            return false;
        }

        $message = "🔔 *Nuevo Comprobante*\n\n"
            . "Orden: #{$order->order_number}\n"
            . "Cliente: {$order->user->name}\n"
            . "Plan: {$order->plan->name}\n"
            . "Monto: ₡" . number_format($order->total, 0, ',', '.') . "\n\n"
            . "Revisar en el panel de administración.";

        return $this->send($adminPhone, $message, 'admin_payment_notification', $order->id, $order->user_id);
    }

    /**
     * Mensaje de confirmación al cliente (comprobante recibido)
     */
    public function sendPaymentReceived(Order $order): bool
    {
        $phone = $order->user->phone;
        if (!$phone) {
            return false;
        }

        $message = "✅ *Comprobante Recibido*\n\n"
            . "Hola {$order->user->name},\n\n"
            . "Recibimos tu comprobante de pago para el pedido #{$order->order_number}.\n\n"
            . "Lo revisaremos pronto y te notificaremos.\n\n"
            . "Monto: ₡" . number_format($order->total, 0, ',', '.') . "\n"
            . "Plan: {$order->plan->name}";

        return $this->send($phone, $message, 'client_payment_confirmation', $order->id, $order->user_id);
    }

    /**
     * Mensaje cuando se verifica el pago
     */
    public function sendPaymentVerified(Order $order): bool
    {
        $phone = $order->user->phone;
        if (!$phone) {
            return false;
        }

        $message = "🎉 *Pago Verificado*\n\n"
            . "Hola {$order->user->name},\n\n"
            . "Tu pago ha sido verificado exitosamente.\n\n"
            . "Plan: {$order->plan->name}\n"
            . "Pedido: #{$order->order_number}\n\n"
            . "Tu plan ya está activo. ¡Gracias por tu compra!";

        return $this->send($phone, $message, 'payment_verified', $order->id, $order->user_id);
    }

    /**
     * Mensaje cuando se rechaza el pago
     */
    public function sendPaymentRejected(Order $order): bool
    {
        $phone = $order->user->phone;
        if (!$phone) {
            return false;
        }

        $reason = $order->admin_notes ? "\n\nMotivo: {$order->admin_notes}" : '';

        $message = "❌ *Pago Rechazado*\n\n"
            . "Hola {$order->user->name},\n\n"
            . "No pudimos verificar tu pago para el pedido #{$order->order_number}."
            . $reason . "\n\n"
            . "Por favor, contacta con soporte para más información.";

        return $this->send($phone, $message, 'payment_rejected', $order->id, $order->user_id);
    }

    /**
     * Recordatorio de pago (manual o automático)
     */
    public function sendPaymentReminder(User $user, bool $isAutomatic = false): bool
    {
        $phone = $user->phone;
        if (!$phone) {
            return false;
        }

        $daysLeft = $user->plan_expires_at ? now()->diffInDays($user->plan_expires_at, false) : 0;
        $expiresText = $user->plan_expires_at
            ? $user->plan_expires_at->format('d/m/Y')
            : 'pronto';

        $message = "⏰ *Recordatorio de Pago*\n\n"
            . "Hola {$user->name},\n\n"
            . "Tu plan vence el {$expiresText}";

        if ($daysLeft === 1) {
            $message .= " (mañana)";
        } elseif ($daysLeft > 0) {
            $message .= " (en {$daysLeft} días)";
        }

        $message .= ".\n\nRenueva tu plan para seguir disfrutando del servicio.";

        $type = $isAutomatic ? 'auto_payment_reminder' : 'payment_reminder';
        return $this->send($phone, $message, $type, null, $user->id);
    }

    /**
     * Mensaje de cuenta bloqueada
     */
    public function sendAccountBlocked(User $user): bool
    {
        $phone = $user->phone;
        if (!$phone) {
            return false;
        }

        $message = "⚠️ *Cuenta Suspendida*\n\n"
            . "Hola {$user->name},\n\n"
            . "Tu cuenta ha sido suspendida por falta de pago.\n\n"
            . "Para reactivarla, renueva tu plan desde el portal.\n\n"
            . "Si tienes dudas, contáctanos.";

        return $this->send($phone, $message, 'account_blocked', null, $user->id);
    }

    /**
     * Mensaje de QR escaneado
     */
    public function sendQrScanned(User $owner, string $petName, ?string $location, ?string $mapsUrl): bool
    {
        $phone = $owner->phone;
        if (!$phone) {
            return false;
        }

        $message = "🔔 *QR Escaneado*\n\n"
            . "Hola {$owner->name},\n\n"
            . "El QR de {$petName} fue escaneado";

        if ($location) {
            $message .= " en:\n{$location}";
        }

        if ($mapsUrl) {
            $message .= "\n\nVer ubicación: {$mapsUrl}";
        }

        return $this->send($phone, $message, 'qr_scanned', null, $owner->id);
    }
}
