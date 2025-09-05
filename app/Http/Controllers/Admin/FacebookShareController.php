<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Services\FacebookPoster;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class FacebookShareController extends Controller
{
    public function __invoke(Pet $pet, FacebookPoster $poster)
    {
        $pet->loadMissing('qrCode', 'photos', 'reward');

        // URL del perfil público (por QR)
        $publicProfileUrl = $pet->qrCode?->slug
            ? route('public.pet.show', $pet->qrCode->slug)
            : null;

        // ===== MENSAJE con emojis =====
        $sexTxt = match ($pet->sex) {
            'male'   => '♂️ Macho',
            'female' => '♀️ Hembra',
            default  => '❔ Desconocido',
        };

        $ageTxt = null;
        if (!is_null($pet->age)) {
            $ageTxt = 'Edad: ' . $pet->age . ' ' . ((int) $pet->age === 1 ? 'año' : 'años');
        }

        $rewardActive = (bool) ($pet->reward?->active ?? false);
        $rewardAmount = (float) ($pet->reward?->amount ?? 0);
        $hasReward    = $rewardActive && $rewardAmount > 0;

        // Línea combinada cuando está perdida y hay recompensa
        $lostRewardLine = null;
        if ($pet->is_lost && $hasReward) {
            $lostRewardLine = '⚠️ Perdida/robada — 💰 Recompensa: ₡' . number_format($rewardAmount, 2);
        }

        $messageLines = array_filter([
            "🐾 {$pet->name}",
            $pet->breed ? "Raza: {$pet->breed}" : null,
            "Sexo: {$sexTxt}",
            $ageTxt, //edad
            $pet->zone ? "Zona: {$pet->zone}" : null,

            // Si existe la línea combinada, úsala. Si no:
            $lostRewardLine ?: ($pet->is_lost ? '⚠️ Perdida/robada' : null),
            !$lostRewardLine && $hasReward ? ('💰 Recompensa: ₡' . number_format($rewardAmount, 2)) : null,

            $publicProfileUrl ? "Perfil: {$publicProfileUrl}" : null,
            'QR-Pet Tag',
        ]);

        $message = implode("\n", $messageLines);

        // ===== Imagen: normalizar a URL pública o fallback =====
        $imageUrl = $pet->main_photo_url ?: null;

        // Si viene ruta relativa, volverla absoluta
        if ($imageUrl && !preg_match('#^https?://#i', $imageUrl)) {
            $imageUrl = asset(ltrim($imageUrl, '/'));
        }

        // Si no hay main_photo_url, usa la primera foto guardada
        if (!$imageUrl && $pet->photos->first()) {
            $imageUrl = asset('storage/' . $pet->photos->first()->path);
        }

        // Validar que sea https pública y no local
        $host    = parse_url((string) $imageUrl, PHP_URL_HOST);
        $invalid = !$imageUrl || !filter_var($imageUrl, FILTER_VALIDATE_URL);
        $isLocal = in_array($host, ['127.0.0.1', 'localhost'], true);

        if ($invalid || $isLocal) {
            $imageUrl = 'https://picsum.photos/seed/qrpet/800/600';
        }

        Log::info('FB publish - image & message', [
            'pet_id'   => $pet->id,
            'imageUrl' => $imageUrl,
            'has_msg'  => mb_strlen($message) > 0,
        ]);

        try {
            $result = $poster->postPhotoByUrl($imageUrl, $message);

            return response()->json([
                'ok'     => true,
                'result' => $result,
            ]);
        } catch (RequestException $e) {
            $json = $e->response?->json();
            Log::error('FB publish failed (RequestException)', [
                'pet_id'   => $pet->id,
                'fb_error' => $json,
            ]);

            return response()->json([
                'ok'    => false,
                'error' => $json['error']['message'] ?? 'No se pudo publicar',
            ], 422);
        } catch (\Throwable $e) {
            Log::error('FB publish failed (Throwable)', [
                'pet_id' => $pet->id,
                'msg'    => $e->getMessage(),
            ]);

            return response()->json([
                'ok'    => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
