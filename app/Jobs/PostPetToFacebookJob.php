<?php

namespace App\Jobs;

use App\Models\Pet;
use App\Models\PetFbPost;
use App\Services\FacebookPoster;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostPetToFacebookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    // backoff exponencial
    public function backoff(): array
    {
        return [10, 30, 60, 120, 240];
    }

    public function __construct(public int $postId) {}

    public function handle(FacebookPoster $poster): void
    {
        /** @var PetFbPost $reg */
        $reg = PetFbPost::lockForUpdate()->findOrFail($this->postId);

        if ($reg->status === 'success') {
            return; // idempotente
        }

        $reg->update([
            'status'          => 'processing',
            'attempts'        => $reg->attempts + 1,
            'last_attempt_at' => now(),
            'error_message'   => null,
        ]);

        try {
            /** @var Pet $pet */
            $pet = Pet::with(['photos','qrCode','reward'])->findOrFail($reg->pet_id);

            // Publicar según image_kind
            $result = null;
            if ($reg->image_kind === 'file') {
                $result = $poster->postPhotoFile($reg->message ?? '', $reg->image_ref);
            } else {
                $result = $poster->postPhotoByUrl($reg->image_ref, $reg->message ?? '');
            }

            $postId = $result['post_id'] ?? ($result['id'] ?? null);

            $reg->update([
                'status'  => 'success',
                'post_id' => $postId,
            ]);

            $pet->forceFill([
                'last_fb_post_id'      => $postId,
                'last_fb_post_at'      => now(),
                'last_fb_content_hash' => $reg->fingerprint,
            ])->save();

        } catch (\Throwable $e) {
            Log::error('PostPetToFacebookJob error', [
                'post_fb_id' => $this->postId,
                'msg'        => $e->getMessage(),
            ]);
            // re-lanzamos para que se marque como failed y ejecute failed()
            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        if ($reg = PetFbPost::find($this->postId)) {
            $reg->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
