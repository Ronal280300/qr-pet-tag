<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\PostPetToFacebookJob;
use App\Models\Pet;
use App\Models\PetFbPost;
use App\Services\FacebookPoster; // sólo para tipos; no lo usamos aquí
use Illuminate\Support\Str;

class FacebookShareController extends Controller
{
    private int $dedupeWindowMinutes = 60;

    public function __invoke(Pet $pet)
    {
        $pet->loadMissing('qrCode','photos','reward');

        // ===== Construir mensaje =====
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

        $ageTxt = !is_null($pet->age) ? "Edad: {$pet->age} " . Str::plural('año', $pet->age) : null;
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

        // ===== Determinar imagen (archivo o URL) =====
        $imageKind = null;
        $imageRef  = null;

        if ($pet->photos->first()) {
            $rel = $pet->photos->first()->path;
            $abs = storage_path('app/public/'.$rel);
            if (is_file($abs) && is_readable($abs) && filesize($abs) > 0) {
                $imageKind = 'file';
                $imageRef  = $abs;
            }
        }

        if (!$imageKind) {
            $url = $pet->main_photo_url ?: null;
            if (!$url && $pet->photos->first()) {
                $url = asset('storage/'.$pet->photos->first()->path);
            }
            $isLocal = !$url || str_contains($url,'127.0.0.1') || str_contains($url,'localhost');
            if ($isLocal || !filter_var($url, FILTER_VALIDATE_URL)) {
                $url = 'https://picsum.photos/seed/qrpet/1080/1080';
            }
            $imageKind = 'url';
            $imageRef  = $url;
        }

        // ===== Idempotencia por fingerprint =====
        $imageKey    = $imageKind === 'file' ? ('file:' . sha1_file($imageRef)) : ('url:' . $imageRef);
        $fingerprint = hash('sha256', $message.'|'.$imageKey);

        $force = request()->boolean('force');
        if (!$force
            && $pet->last_fb_content_hash === $fingerprint
            && $pet->last_fb_post_at
            && now()->diffInMinutes($pet->last_fb_post_at) < $this->dedupeWindowMinutes
        ) {
            // Responder duplicado, el front decidirá si forzar
            return response()->json([
                'ok'               => false,
                'duplicate'        => true,
                'cooldown_minutes' => $this->dedupeWindowMinutes,
            ], 200);
        }

        // ===== Crear registro y encolar job =====
        $reg = PetFbPost::create([
            'pet_id'     => $pet->id,
            'status'     => 'queued',
            'message'    => $message,
            'fingerprint'=> $fingerprint,
            'image_kind' => $imageKind,
            'image_ref'  => $imageRef,
        ]);

        PostPetToFacebookJob::dispatch($reg->id);

        return response()->json([
            'ok'         => true,
            'queued'     => true,
            'post_id'    => $reg->id,
            'status_url' => route('portal.pets.share.facebook.status', [$pet, $reg]),
        ]);
    }

    // Endpoint de estado (se añade en routes más abajo)
    public function status(Pet $pet, PetFbPost $reg)
    {
        abort_if($reg->pet_id !== $pet->id, 404);

        $pageId = config('services.facebook.page_id');
        $fbUrl = null;
        if ($reg->status === 'success' && $reg->post_id) {
            $suffix = str_contains($reg->post_id,'_') ? explode('_', $reg->post_id)[1] : $reg->post_id;
            $fbUrl = "https://www.facebook.com/{$pageId}/posts/{$suffix}";
        }

        return response()->json([
            'ok'            => true,
            'status'        => $reg->status,
            'attempts'      => $reg->attempts,
            'error'         => $reg->error_message,
            'facebook_url'  => $fbUrl,
        ]);
    }
}
