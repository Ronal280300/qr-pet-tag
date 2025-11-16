<?php

namespace App\Jobs;

use App\Models\PetPhoto;
use App\Services\PetPhotoOptimizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OptimizePetPhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int ID de la foto a optimizar
     */
    protected int $photoId;

    /**
     * @var string Ruta temporal del archivo original
     */
    protected string $tempPath;

    /**
     * Número de intentos
     */
    public int $tries = 3;

    /**
     * Timeout en segundos
     */
    public int $timeout = 120;

    /**
     * Create a new job instance.
     */
    public function __construct(int $photoId, string $tempPath)
    {
        $this->photoId = $photoId;
        $this->tempPath = $tempPath;
    }

    /**
     * Execute the job.
     */
    public function handle(PetPhotoOptimizationService $photoService): void
    {
        try {
            $photo = PetPhoto::find($this->photoId);

            if (!$photo) {
                Log::warning("PetPhoto {$this->photoId} no encontrada, skip optimización");
                return;
            }

            // Verificar que el archivo temporal existe
            if (!Storage::disk('public')->exists($this->tempPath)) {
                Log::warning("Archivo temporal {$this->tempPath} no existe, skip optimización");
                return;
            }

            $absolutePath = Storage::disk('public')->path($this->tempPath);

            // Simular UploadedFile desde el archivo temporal
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $absolutePath,
                basename($this->tempPath),
                Storage::disk('public')->mimeType($this->tempPath),
                null,
                true // test mode
            );

            // Optimizar y generar versiones
            $folder = dirname($photo->path);
            $optimizedPaths = $photoService->optimizeAndStore($uploadedFile, $folder);

            // Actualizar la ruta en BD (solo si cambió)
            if ($photo->path !== $optimizedPaths['medium']) {
                // Eliminar archivo temporal original
                if (Storage::disk('public')->exists($photo->path)) {
                    Storage::disk('public')->delete($photo->path);
                }

                $photo->path = $optimizedPaths['medium'];
                $photo->save();
            }

            // Limpiar archivo temporal
            if (Storage::disk('public')->exists($this->tempPath)) {
                Storage::disk('public')->delete($this->tempPath);
            }

            Log::info("Foto {$this->photoId} optimizada correctamente");

        } catch (\Exception $e) {
            Log::error("Error optimizando foto {$this->photoId}: " . $e->getMessage());
            throw $e; // Re-lanzar para que se reintente
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Job OptimizePetPhotoJob falló después de {$this->tries} intentos: " . $exception->getMessage());
    }
}
