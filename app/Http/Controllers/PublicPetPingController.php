<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\QrCode;
use App\Models\PetPing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PublicPetPingController extends Controller
{
    // Máximo correos por hora por mascota (se puede sobrescribir por .env)
    private function perHourLimit(): int
    {
        return (int) env('PING_MAX_MAILS_PER_HOUR', 2);
    }

    public function store(Request $request, string $slug)
    {
        $qr = QrCode::where('slug', $slug)->firstOrFail();
        $pet = Pet::with('user')->findOrFail($qr->pet_id);

        $method   = $request->input('method', 'ip'); // 'gps' | 'ip'
        $lat      = $request->input('lat');
        $lng      = $request->input('lng');
        $accuracy = $request->input('accuracy');

        $city = $region = $country = null;

        // Si no hay GPS o no vino lat/lng, completamos por IP
        if ($method !== 'gps' || !is_numeric($lat) || !is_numeric($lng)) {
            $method = 'ip';
            $clientIp = $request->ip(); // IP del lector
            try {
                // Servicio público gratuito con rate limitado (suficiente para pruebas)
                $res = Http::timeout(4)->get("https://ipapi.co/{$clientIp}/json/");
                if ($res->ok()) {
                    $j = $res->json();
                    $lat     = $lat ?? ($j['latitude']  ?? null);
                    $lng     = $lng ?? ($j['longitude'] ?? null);
                    $city    = $j['city']    ?? null;
                    $region  = $j['region']  ?? null;
                    $country = $j['country_name'] ?? ($j['country'] ?? null);
                }
            } catch (\Throwable $e) {
                // si falla, guardamos sin ciudad/país, pero no interrumpimos
                Log::warning('IP geolocate failed', ['ip' => $clientIp, 'err' => $e->getMessage()]);
            }
        }

        // Guardamos el ping
        $ping = PetPing::create([
            'pet_id'     => $pet->id,
            'qr_code_id' => $qr->id,
            'source'     => $method,
            'lat'        => is_numeric($lat) ? $lat : null,
            'lng'        => is_numeric($lng) ? $lng : null,
            'accuracy'   => is_numeric($accuracy) ? (int)$accuracy : null,
            'city'       => $city,
            'region'     => $region,
            'country'    => $country,
            'ip'         => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
        ]);

        // Anti-spam: máx N correos por hora por mascota
        $limit = $this->perHourLimit();
        $mailKey = "pet:{$pet->id}:pings_mailed_hour";
        $count   = (int) Cache::get($mailKey, 0);

        $mailed  = false;
        $why     = null;

        if ($count >= $limit) {
            $why = 'throttled'; // no enviamos más en esta hora
        } elseif (!$pet->user || !filter_var($pet->user->email ?? '', FILTER_VALIDATE_EMAIL)) {
            $why = 'no_owner_email';
        } else {
            // Construimos el cuerpo simple con link a Google Maps si tenemos lat/lng
            $gmaps = (is_numeric($ping->lat) && is_numeric($ping->lng))
                ? "https://maps.google.com/?q={$ping->lat},{$ping->lng}"
                : null;

            $subject = "QR-Pet Tag: alguien escaneó el QR de {$pet->name}";
            $lines   = [];
            $lines[] = "Hola " . ($pet->user->name ?? 'dueño/a') . ",";
            $lines[] = "Alguien acaba de leer el QR de \"{$pet->name}\".";
            if ($gmaps) {
                $lines[] = "Mapa: {$gmaps}";
                if ($ping->accuracy) $lines[] = "Precisión aprox: {$ping->accuracy} m";
            }
            if ($ping->city || $ping->region || $ping->country) {
                $lines[] = "Ubicación aprox: " . trim(($ping->city ? "{$ping->city}, " : '') . ($ping->region ? "{$ping->region}, " : '') . ($ping->country ?: ''), ', ');
            }
            $lines[] = "IP: {$ping->ip}";
            $lines[] = "";
            $lines[] = "— QR-Pet Tag";

            $text = implode("\n", $lines);

            $mapsUrl = (is_numeric($ping->lat) && is_numeric($ping->lng))
                ? "https://maps.google.com/?q={$ping->lat},{$ping->lng}"
                : null;

            $ownerName      = $pet->user->name ?? 'dueño/a';
            $whenLocal      = $ping->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i');
            $locationHuman  = trim(implode(', ', array_filter([$ping->city, $ping->region, $ping->country])), ', ');
            $isGps          = $ping->source === 'gps';

            $subject = $isGps
                ? "📍 Ubicación precisa de {$pet->name}"
                : "🔔 Escanearon el QR de {$pet->name}";

            $viewData = [
                'pet'            => $pet,
                'ping'           => $ping,
                'mapsUrl'        => $mapsUrl,
                'ownerName'      => $ownerName,
                'whenLocal'      => $whenLocal,
                'locationHuman'  => $locationHuman,
                'isGps'          => $isGps,
            ];

            try {
                // HTML + texto como alternativa
                Mail::send(['html' => 'emails.ping-html', 'text' => 'emails.ping-text'], $viewData, function ($m) use ($pet, $subject) {
                    $m->to($pet->user->email)->subject($subject);
                });

                // sube el contador 1 hora
                Cache::put($mailKey, $count + 1, now()->addHour());
                $mailed = true;
                Log::info('Ping mailed', ['pet_id' => $pet->id, 'email' => $pet->user->email]);
            } catch (\Throwable $e) {
                $why = 'mail_error';
                Log::error('Ping mail failed', ['pet_id' => $pet->id, 'err' => $e->getMessage()]);
            }
        }

        return response()->json([
            'ok'     => true,
            'mailed' => $mailed,
            'why'    => $why,
            'ping_id' => $ping->id,
        ]);
    }
}
