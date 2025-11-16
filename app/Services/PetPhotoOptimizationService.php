<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PetPhotoOptimizationService
{
    /**
     * Tamaños predefinidos para las imágenes
     * OPTIMIZADO: Solo 2 versiones para velocidad
     */
    private const SIZES = [
        'thumb' => [
            'width' => 150,
            'height' => 150,
            'quality' => 75, // Reducido de 85 a 75
        ],
        'medium' => [
            'width' => 800,
            'height' => 800,
            'quality' => 80, // Reducido de 85 a 80
        ],
        // Removida versión 'large' para velocidad
    ];

    /**
     * Optimiza y guarda una imagen en múltiples tamaños
     *
     * @param UploadedFile $file Archivo subido
     * @param string $folder Carpeta donde guardar (ej: 'pets', 'pets/photos')
     * @return array Rutas de los archivos generados
     */
    public function optimizeAndStore(UploadedFile $file, string $folder = 'pets'): array
    {
        // Generar nombre único
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName);
        $uniqueName = $safeName . '-' . time() . '-' . Str::random(6);

        $paths = [];

        // Crear ImageManager con driver GD
        $manager = new ImageManager(new Driver());

        // Generar cada tamaño
        foreach (self::SIZES as $sizeName => $config) {
            $filename = "{$sizeName}_{$uniqueName}.webp";
            $fullPath = "{$folder}/{$filename}";
            $absolutePath = Storage::disk('public')->path($fullPath);

            // Asegurar que el directorio existe
            $directory = dirname($absolutePath);
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Procesar imagen con Intervention Image v3
            $image = $manager->read($file->getRealPath());

            // Redimensionar manteniendo aspecto (cover = recorta si es necesario)
            $image->cover($config['width'], $config['height']);

            // Convertir a WebP y guardar
            $encoded = $image->toWebp($config['quality']);
            file_put_contents($absolutePath, $encoded);

            $paths[$sizeName] = $fullPath;
        }

        return $paths;
    }

    /**
     * Optimización RÁPIDA: Solo genera versión medium (para respuesta instantánea)
     * Usar cuando el tiempo de respuesta es crítico
     *
     * @param UploadedFile $file Archivo subido
     * @param string $folder Carpeta donde guardar
     * @return string Ruta del archivo generado
     */
    public function optimizeQuick(UploadedFile $file, string $folder = 'pets'): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName);
        $uniqueName = $safeName . '-' . time() . '-' . Str::random(6);

        $filename = "medium_{$uniqueName}.webp";
        $fullPath = "{$folder}/{$filename}";
        $absolutePath = Storage::disk('public')->path($fullPath);

        // Asegurar que el directorio existe
        $directory = dirname($absolutePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Crear ImageManager
        $manager = new ImageManager(new Driver());

        // Procesar solo versión medium
        $image = $manager->read($file->getRealPath());
        $image->cover(800, 800);
        $encoded = $image->toWebp(80);
        file_put_contents($absolutePath, $encoded);

        return $fullPath;
    }

    /**
     * Genera versión thumb de un archivo existente (para procesar después)
     *
     * @param string $mediumPath Ruta de la imagen medium
     * @return string|null Ruta del thumbnail generado
     */
    public function generateThumb(string $mediumPath): ?string
    {
        if (!Storage::disk('public')->exists($mediumPath)) {
            return null;
        }

        $basename = basename($mediumPath);
        $folder = dirname($mediumPath);

        // Remover prefijo medium_ si existe
        $basename = preg_replace('/^medium_/', '', $basename);
        $thumbFilename = "thumb_{$basename}";
        $thumbPath = "{$folder}/{$thumbFilename}";
        $absolutePath = Storage::disk('public')->path($thumbPath);

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::disk('public')->path($mediumPath));
            $image->cover(150, 150);
            $encoded = $image->toWebp(75);
            file_put_contents($absolutePath, $encoded);

            return $thumbPath;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error generando thumbnail: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Elimina todas las versiones de una foto
     *
     * @param string $basePath Ruta base (puede ser cualquier versión)
     * @return void
     */
    public function deleteAllVersions(string $basePath): void
    {
        // Extraer el nombre base sin el prefijo de tamaño
        $basename = basename($basePath);

        // Si tiene prefijo de tamaño (thumb_, medium_, large_), removerlo
        foreach (array_keys(self::SIZES) as $sizeName) {
            $basename = preg_replace("/^{$sizeName}_/", '', $basename);
        }

        $folder = dirname($basePath);

        // Eliminar todas las versiones
        foreach (array_keys(self::SIZES) as $sizeName) {
            $path = "{$folder}/{$sizeName}_{$basename}";

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    /**
     * Verifica si una imagen necesita optimización
     *
     * @param UploadedFile $file
     * @return bool
     */
    public function needsOptimization(UploadedFile $file): bool
    {
        // Si es mayor a 500KB o no es WebP, necesita optimización
        return $file->getSize() > 500 * 1024 || $file->getMimeType() !== 'image/webp';
    }

    /**
     * Obtiene la ruta de una versión específica
     *
     * @param string $originalPath Ruta original guardada en BD
     * @param string $size Tamaño deseado (thumb, medium, large)
     * @return string
     */
    public function getVersionPath(string $originalPath, string $size = 'medium'): string
    {
        if (!isset(self::SIZES[$size])) {
            $size = 'medium'; // Fallback
        }

        $basename = basename($originalPath);
        $folder = dirname($originalPath);

        // Si ya tiene prefijo de tamaño, removerlo primero
        foreach (array_keys(self::SIZES) as $sizeName) {
            $basename = preg_replace("/^{$sizeName}_/", '', $basename);
        }

        return "{$folder}/{$size}_{$basename}";
    }
}
