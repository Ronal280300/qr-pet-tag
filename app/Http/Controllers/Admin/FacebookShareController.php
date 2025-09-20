<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Services\FacebookPoster;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FacebookShareController extends Controller
{
   public function __invoke(Pet $pet, FacebookPoster $poster)
    {
        // Carga mínima para armar mensaje/imagen
        $pet->loadMissing('qrCode', 'photos', 'reward');

        // ===== Construir mensaje (igual al ejemplo) =====
        $publicProfileUrl = $pet->qrCode?->slug
            ? route('public.pet.show', $pet->qrCode->slug)
            : null;

        $sexTxt = match ($pet->sex) {
            'male'   => 'Macho ♂️',
            'female' => 'Hembra ♀️',
            default  => 'Desconocido ❔',
        };

        $rewardTxt = ($pet->is_lost && $pet->reward?->active && ($pet->reward?->amount ?? 0) > 0)
            ? "💰 Recompensa: ₡" . number_format($pet->reward->amount, 2)
            : null;

        $ageTxt     = !is_null($pet->age) ? "Edad: {$pet->age} " . Str::plural('año', (int)$pet->age) : null;
        $statusLost = $pet->is_lost ? '⚠️ Reportada como perdida/robada' : null;

        $messageLines = array_filter([
            "🐾 {$pet->name}",
            $pet->breed ? "Raza: {$pet->breed}" : null,
            "Sexo: {$sexTxt}",
            $ageTxt,
            $pet->zone ? "Zona: {$pet->zone}" : null,
            $statusLost,
            $rewardTxt,
            $publicProfileUrl ? "Perfil: {$publicProfileUrl}" : null,
            "QR-Pet Tag",
        ]);
        $message = implode("\n", $messageLines);

        // ===== Determinar imagen (igual al ejemplo) =====
        $imageKind = null;   // 'file' | 'url'
        $imageRef  = null;   // ruta abs si file, o URL si url

        if ($pet->photos->first()) {
            $rel = $pet->photos->first()->path;
            $abs = storage_path('app/public/' . ltrim($rel, '/'));
            if (is_file($abs) && is_readable($abs) && filesize($abs) > 0) {
                $imageKind = 'file';
                $imageRef  = $abs;
            }
        }

        if (!$imageKind) {
            $url = $pet->main_photo_url ?: null;
            if (!$url && $pet->photos->first()) {
                $url = asset('storage/' . $pet->photos->first()->path);
            }
            $isLocal = !$url || str_contains($url, '127.0.0.1') || str_contains($url, 'localhost');
            if ($isLocal || !filter_var($url, FILTER_VALIDATE_URL)) {
                $url = 'https://picsum.photos/seed/qrpet/1080/1080';
            }
            $imageKind = 'url';
            $imageRef  = $url;
        }

        Log::info('FB publish - image & message', [
            'pet_id'    => $pet->id,
            'imageKind' => $imageKind,
            'imageRef'  => $imageRef,
            'has_msg'   => filled($message),
        ]);

        // ===== Publicación directa (sin jobs), lo demás igual =====
        try {
            $result = $imageKind === 'file'
                ? $poster->postPhotoFile($imageRef, $message)   // sube archivo (multipart)
                : $poster->postPhotoByUrl($imageRef, $message); // usa URL pública

            if (!empty($result['post_id'])) {
                try {
                    $pet->last_fb_post_id = $result['post_id'];
                    $pet->save();
                } catch (\Throwable $e) {
                    // no crítico
                }
            }

            return response()->json([
                'ok'     => true,
                'result' => $result,
            ]);

        } catch (RequestException $e) {
            $json = $e->response?->json();
            $msg  = $json['error']['message'] ?? 'No se pudo publicar';
            Log::error('FB publish failed', ['pet_id' => $pet->id, 'err' => $msg]);

            return response()->json([
                'ok'    => false,
                'error' => $msg,
            ], 422);

        } catch (\Throwable $e) {
            Log::error('FB publish failed', ['pet_id' => $pet->id, 'err' => $e->getMessage()]);

            return response()->json([
                'ok'    => false,
                'error' => 'No se pudo publicar en Facebook. Revisa el token o vuelve a intentar.',
            ], 422);
        }
    }
}
