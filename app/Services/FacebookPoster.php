<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class FacebookPoster
{
    protected string $token;
    protected string $version;
    protected string $pageId;

    public function __construct()
    {
        $this->token   = (string) config('services.facebook.page_access_token');
        $this->version = (string) config('services.facebook.version', 'v23.0');
        $this->pageId  = (string) config('services.facebook.page_id');
    }

    /**
     * Publica una foto usando una URL pública.
     */
    public function postPhotoByUrl(string $imageUrl, ?string $message = null): array
    {
        $endpoint = "https://graph.facebook.com/{$this->version}/{$this->pageId}/photos";

        $payload = [
            'url'           => $imageUrl,   // Debe ser https público
            'published'     => 'true',
            'access_token'  => $this->token,
        ];

        if ($message !== null && $message !== '') {
            // En /photos el parámetro recomendado es "caption"
            $payload['caption'] = mb_substr($message, 0, 1000);
        }

        $res = Http::asForm()->post($endpoint, $payload);
        if ($res->failed()) {
            throw new RequestException($res);
        }

        return $res->json();
    }

    /**
     * Publica una foto subiendo el ARCHIVO (multipart).
     */
    public function postPhotoFile(string $absPath, ?string $message = null, string $mime = 'image/jpeg'): array
    {
        if (!is_file($absPath) || !is_readable($absPath) || filesize($absPath) <= 0) {
            throw new \RuntimeException("Foto inválida: {$absPath}");
        }

        $endpoint = "https://graph.facebook.com/{$this->version}/{$this->pageId}/photos";

        $stream = @fopen($absPath, 'r');
        if (!$stream) {
            throw new \RuntimeException("No se pudo abrir la foto: {$absPath}");
        }

        $res = Http::asMultipart()
            ->attach('source', $stream, basename($absPath), ['Content-Type' => $mime])
            ->post($endpoint, [
                'published'     => 'true',
                'access_token'  => $this->token,
                // "caption" en vez de "message" para /photos
                'caption'       => $message ? mb_substr($message, 0, 1000) : null,
            ]);

        if (is_resource($stream)) {
            @fclose($stream);
        }

        if ($res->failed()) {
            throw new RequestException($res);
        }

        return $res->json();
    }
}
