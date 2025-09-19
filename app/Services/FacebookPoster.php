<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

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
     * Publica una foto usando una URL pública directa (https) + mensaje opcional.
     */
    public function postPhotoByUrl(string $imageUrl, ?string $message = null): array
    {
        // Validación dura para evitar el (#100)
        if (!$this->isPublicHttpsUrl($imageUrl)) {
            throw new \InvalidArgumentException('imageUrl must be a public https URL');
        }

        $endpoint = "https://graph.facebook.com/{$this->version}/{$this->pageId}/photos";

        $payload = [
            'url'          => $imageUrl,      // solo 'url' (NO mezclar con 'source')
            'access_token' => $this->token,
        ];
        if ($message !== null && $message !== '') {
            $payload['message'] = mb_substr($message, 0, 1000);
        }

        Log::info('FB publish by URL', ['url' => $imageUrl]);

        $res = Http::asForm()->post($endpoint, $payload);
        if ($res->failed()) {
            Log::error('FB publish by URL failed', ['status' => $res->status(), 'body' => $res->body()]);
            throw new RequestException($res);
        }
        return $res->json(); // { id, post_id }
    }

    /**
     * Publica una foto subiendo el archivo local (multipart).
     * Úsalo en desarrollo/local para evitar URLs no públicas.
     */
    public function postPhotoFile(string $message, string $absPath, string $mime = null): array
    {
        if (!is_file($absPath) || !is_readable($absPath) || filesize($absPath) <= 0) {
            throw new \RuntimeException("Invalid photo file: {$absPath}");
        }

        $endpoint = "https://graph.facebook.com/{$this->version}/{$this->pageId}/photos";

        if ($mime === null) {
            $probe = @mime_content_type($absPath);
            $mime = $probe ?: 'image/jpeg';
        }

        Log::info('FB publish by FILE', ['path' => $absPath, 'mime' => $mime]);

        $res = Http::asMultipart()
            ->attach('source', fopen($absPath, 'r'), basename($absPath), ['Content-Type' => $mime])
            ->post($endpoint, [
                'message'      => mb_substr($message ?? '', 0, 1000),
                'published'    => 'true',
                'access_token' => $this->token,
            ]);

        if ($res->failed()) {
            Log::error('FB publish by FILE failed', ['status' => $res->status(), 'body' => $res->body()]);
            throw new RequestException($res);
        }

        return $res->json(); // { id, post_id }
    }

    /**
     * URL https pública y no-localhost.
     */
    private function isPublicHttpsUrl(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
        $parts = parse_url($url);
        if (($parts['scheme'] ?? '') !== 'https') return false;

        $host = strtolower($parts['host'] ?? '');
        if (!$host) return false;

        // No permitir localhost ni IPs privadas
        if ($host === 'localhost' || $host === '127.0.0.1') return false;
        if (preg_match('/^10\.|^172\.(1[6-9]|2\d|3[0-1])\.|^192\.168\./', $host)) return false;

        return true;
    }
}
