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
        $this->token   = config('services.facebook.page_access_token');
        $this->version = config('services.facebook.version', 'v23.0');
        $this->pageId  = config('services.facebook.page_id');
    }

    // ⚠️ Versión mínima: SOLO url + access_token (como en Postman/Tinker)
    public function postPhotoByUrl(string $imageUrl, ?string $message = null): array
    {
        $endpoint = "https://graph.facebook.com/{$this->version}/{$this->pageId}/photos";

        $payload = [
            'url'          => $imageUrl,      // debe ser https público
            'access_token' => $this->token,
        ];
        if ($message !== null && $message !== '') {
            $payload['message'] = mb_substr($message, 0, 1000); // por si acaso
        }

        $res = Http::asForm()->post($endpoint, $payload);
        if ($res->failed()) throw new \Illuminate\Http\Client\RequestException($res);
        return $res->json();
    }
}

//     // app/Services/FacebookPoster.php
//     public function postPhotoFile(string $message, string $absPath, string $mime = 'image/png'): array
//     {
//         $endpoint = "https://graph.facebook.com/{$this->version}/{$this->pageId}/photos";

//         // Validaciones duras
//         if (!is_file($absPath) || !is_readable($absPath) || filesize($absPath) <= 0) {
//             throw new \RuntimeException("Foto inválida: $absPath");
//         }
//         $stream = @fopen($absPath, 'r');
//         if (!$stream) {
//             throw new \RuntimeException("No se pudo abrir la foto: $absPath");
//         }

//         $res = Http::asMultipart()
//             ->attach('source', $stream, basename($absPath), ['Content-Type' => $mime])
//             ->post($endpoint, [
//                 'message'      => $message,
//                 'published'    => 'true',
//                 'access_token' => $this->token,
//             ]);

//         if (is_resource($stream)) @fclose($stream);

//         if ($res->failed()) throw new \Illuminate\Http\Client\RequestException($res);
//         return $res->json();
//     }
// }
