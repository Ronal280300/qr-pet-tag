<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\QrCode;
use App\Models\PetPing;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PublicPetPingController extends Controller
{
    /**
     * Distancia Haversine en metros. Devuelve INF si faltan coordenadas.
     */
    private function haversineMeters($lat1, $lon1, $lat2, $lon2): float
    {
        if (!is_numeric($lat1) || !is_numeric($lon1) || !is_numeric($lat2) || !is_numeric($lon2)) {
            return INF;
        }
        $R = 6371000; // metros
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return 2 * $R * asin(sqrt($a));
    }

    /**
     * Config de throttling (con fallbacks).
     * NOTA: Valores aumentados significativamente para asegurar que SIEMPRE se notifique.
     */
    private function cfg(): array
    {
        return [
            'perHour'   => (int) env('NOTIFY_MAX_PER_HOUR', (int) env('PING_MAX_MAILS_PER_HOUR', 999)),  // Aumentado a 999
            'minGap'    => (int) env('NOTIFY_MIN_GAP_MINUTES', 1),    // Reducido a 1 minuto
            'moveBreak' => (int) env('NOTIFY_MOVE_BREAK_METERS', 50),  // Reducido a 50m
            'dailyCap'  => (int) env('NOTIFY_DAILY_CAP', 999),          // Aumentado a 999
            'dedupR'    => (int) env('NOTIFY_DEDUP_RADIUS_METERS', 20), // Reducido a 20m
        ];
    }

    /**
     * Decide si podemos notificar ahora según límites por hora/día, gap,
     * deduplicación por ubicación y "romper por movimiento".
     * Devuelve [bool allowed, string reason].
     */
    private function canNotifyNow(int $petId, int $ownerId, ?float $lat, ?float $lng): array
    {
        $cfg  = $this->cfg();
        $pref = "notify:pet:$petId:owner:$ownerId";

        // Contadores de hora y día
        $hourKey = "$pref:hour";
        $dayKey  = "$pref:day";
        if (!Cache::has($hourKey)) Cache::put($hourKey, 0, now()->addHour());
        if (!Cache::has($dayKey))  Cache::put($dayKey, 0, now()->endOfDay());

        $hourCount = (int) Cache::get($hourKey);
        $dayCount  = (int) Cache::get($dayKey);

        if ($hourCount >= $cfg['perHour'])  return [false, 'throttled_hour'];
        if ($dayCount  >= $cfg['dailyCap']) return [false, 'throttled_day'];

        // Último aviso
        $lastKey = "$pref:last"; // ['t'=>Carbon, 'lat'=>, 'lng'=>]
        $last = Cache::get($lastKey);

        if (!$last) return [true, 'first'];

        $movedMeters = $this->haversineMeters($last['lat'] ?? null, $last['lng'] ?? null, $lat, $lng);
        $movedEnough = is_finite($movedMeters) && $movedMeters >= $cfg['moveBreak'];
        $samePlace   = is_finite($movedMeters) && $movedMeters < $cfg['dedupR'];

        $gapOk = Carbon::parse($last['t'])->diffInMinutes(now()) >= $cfg['minGap'];

        if ($samePlace && !$gapOk) return [false, 'dedup_same_place_gap'];
        if (!$gapOk && !$movedEnough) return [false, 'min_gap_not_reached'];

        return [true, $movedEnough ? 'break_by_movement' : 'gap_ok'];
    }

    /**
     * Marca que se notificó: incrementa contadores y guarda última posición/tiempo.
     */
    private function markNotified(int $petId, int $ownerId, ?float $lat, ?float $lng): void
    {
        $pref = "notify:pet:$petId:owner:$ownerId";
        Cache::increment("$pref:hour");
        Cache::increment("$pref:day");
        Cache::put("$pref:last", ['t' => now(), 'lat' => $lat, 'lng' => $lng], now()->addDay());
    }

    public function store(Request $request, string $slug)
    {
        $qr  = QrCode::where('slug', $slug)->firstOrFail();
        $pet = Pet::with('user')->findOrFail($qr->pet_id);

        $method   = $request->input('method', 'ip'); // 'gps' | 'ip'
        $lat      = $request->input('lat');
        $lng      = $request->input('lng');
        $accuracy = $request->input('accuracy');

        $city = $region = $country = null;

        // IP real del lector (detrás de Cloudflare u otro proxy)
        $clientIp = $request->header('CF-Connecting-IP', $request->ip());

        // Si no hay GPS válido, completar por IP
        if ($method !== 'gps' || !is_numeric($lat) || !is_numeric($lng)) {
            $method = 'ip';
            try {
                // Servicio público (limitado) suficiente para el fallback
                $res = Http::timeout(4)->get("https://ipapi.co/{$clientIp}/json/");
                if ($res->ok()) {
                    $j = $res->json();
                    $lat     = $lat ?? ($j['latitude']      ?? null);
                    $lng     = $lng ?? ($j['longitude']     ?? null);
                    $city    = $j['city']                   ?? null;
                    $region  = $j['region']                 ?? null;
                    $country = $j['country_name'] ?? ($j['country'] ?? null);
                }
            } catch (\Throwable $e) {
                Log::warning('IP geolocate failed', ['ip' => $clientIp, 'err' => $e->getMessage()]);
            }
        }

        // Guardar ping
        $ping = PetPing::create([
            'pet_id'     => $pet->id,
            'qr_code_id' => $qr->id,
            'source'     => $method, // gps | ip
            'lat'        => is_numeric($lat) ? (float) $lat : null,
            'lng'        => is_numeric($lng) ? (float) $lng : null,
            'accuracy'   => is_numeric($accuracy) ? (int) $accuracy : null,
            'city'       => $city,
            'region'     => $region,
            'country'    => $country,
            'ip'         => $clientIp,
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        // Validaciones previas a notificar
        $mailed = false;
        $why    = null;

        if (!$pet->user || !filter_var($pet->user->email ?? '', FILTER_VALIDATE_EMAIL)) {
            $why = 'no_owner_email';
        } else {
            // Throttling inteligente (hora/día/gap/movimiento/dedup)
            $ownerId = (int) ($pet->user->id ?? 0);
            [$allowed, $reason] = $this->canNotifyNow($pet->id, $ownerId, $ping->lat, $ping->lng);

            if (!$allowed) {
                $why = $reason;
            } else {
                // Armar correo
                $mapsUrl = (is_numeric($ping->lat) && is_numeric($ping->lng))
                    ? "https://maps.google.com/?q={$ping->lat},{$ping->lng}"
                    : null;

                $ownerName     = $pet->user->name ?? 'dueño/a';
                $whenLocal     = $ping->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i');
                $locationHuman = trim(implode(', ', array_filter([$ping->city, $ping->region, $ping->country])), ', ');
                $isGps         = $ping->source === 'gps';

                $subject = $isGps
                    ? "📍 Ubicación precisa de {$pet->name}"
                    : "🔔 Escanearon el QR de {$pet->name}";

                $viewData = [
                    'pet'           => $pet,
                    'ping'          => $ping,
                    'mapsUrl'       => $mapsUrl,
                    'ownerName'     => $ownerName,
                    'whenLocal'     => $whenLocal,
                    'locationHuman' => $locationHuman,
                    'isGps'         => $isGps,
                ];

                try {
                    // HTML + texto como alternativa
                    Mail::send(
                        ['html' => 'emails.ping-html', 'text' => 'emails.ping-text'],
                        $viewData,
                        function ($m) use ($pet, $subject) {
                            $m->to($pet->user->email)->subject($subject);
                        }
                    );

                    $this->markNotified($pet->id, $ownerId, $ping->lat, $ping->lng);
                    $mailed = true;
                    Log::info('Ping mailed', ['pet_id' => $pet->id, 'email' => $pet->user->email]);

                    // Registrar envío exitoso en EmailLog
                    \App\Models\EmailLog::create([
                        'recipient'        => $pet->user->email,
                        'subject'          => $subject,
                        'type'             => 'scan_notification',
                        'related_user_id'  => $pet->user->id,
                        'status'           => 'sent',
                        'error_message'    => null,
                        'sent_at'          => now(),
                    ]);

                    // Enviar WhatsApp al dueño
                    $whatsapp = app(WhatsAppService::class);
                    $whatsapp->sendQrScanned($pet->user, $pet->name, $locationHuman, $mapsUrl);

                } catch (\Throwable $e) {
                    $why = 'mail_error';
                    $errorMessage = $e->getMessage();
                    Log::error('Ping mail failed', [
                        'pet_id'       => $pet->id,
                        'owner_email'  => $pet->user->email ?? 'N/A',
                        'error'        => $errorMessage,
                        'trace'        => $e->getTraceAsString(),
                    ]);

                    // Registrar fallo en EmailLog
                    \App\Models\EmailLog::create([
                        'recipient'        => $pet->user->email ?? 'unknown',
                        'subject'          => $subject ?? 'QR Scan Notification',
                        'type'             => 'scan_notification',
                        'related_user_id'  => optional($pet->user)->id,
                        'status'           => 'failed',
                        'error_message'    => substr($errorMessage, 0, 500), // Limitar a 500 caracteres
                        'sent_at'          => null,
                    ]);
                }
            }
        }

        return response()->json([
            'ok'      => true,
            'mailed'  => $mailed,
            'why'     => $why,
            'ping_id' => $ping->id,
        ]);
    }
}
