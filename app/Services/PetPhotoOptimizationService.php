<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;

class PetPhotoOptimizationService
{
    /**
     * Tamaños predefinidos para las imágenes
     */
    private const SIZES = [
        'thumb' => [
            'width' => 150,
            'height' => 150,
            'quality' => 85,
        ],
        'medium' => [
            'width' => 600,
            'height' => 600,
            'quality' => 85,
        ],
        'large' => [
            'width' => 1200,
            'height' => 1200,
            'quality' => 90,
        ],
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

        // Generar cada tamaño
        foreach (self::SIZES as $sizeName => $config) {
            $filename = "{$sizeName}_{$uniqueName}.webp";
            $fullPath = "{$folder}/{$filename}";

            // Leer y procesar imagen
            $image = Image::read($file->getRealPath());

            // Redimensionar manteniendo aspecto (cover = recorta si es necesario)
            $image->cover($config['width'], $config['height']);

            // Convertir a WebP con calidad especificada
            $encoded = $image->toWebp($config['quality']);

            // Guardar en storage/public
            Storage::disk('public')->put($fullPath, $encoded);

            $paths[$sizeName] = $fullPath;
        }

        return $paths;
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
