<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DiagnoseProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diagnose:production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostica problemas comunes en producción';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Diagnosticando entorno de producción...');
        $this->newLine();

        // 1. Verificar entorno
        $this->checkEnvironment();

        // 2. Verificar archivos clave
        $this->checkKeyFiles();

        // 3. Verificar permisos
        $this->checkPermissions();

        // 4. Verificar caché
        $this->checkCache();

        // 5. Verificar vistas
        $this->checkViews();

        $this->newLine();
        $this->info('✅ Diagnóstico completado');

        return 0;
    }

    protected function checkEnvironment()
    {
        $this->line('📌 Entorno:');
        $this->table(
            ['Variable', 'Valor'],
            [
                ['APP_ENV', config('app.env')],
                ['APP_DEBUG', config('app.debug') ? 'true' : 'false'],
                ['PHP Version', PHP_VERSION],
                ['Laravel Version', app()->version()],
            ]
        );
        $this->newLine();
    }

    protected function checkKeyFiles()
    {
        $this->line('📂 Verificando archivos clave:');

        $files = [
            'app/Http/Controllers/Admin/NotificationController.php',
            'resources/views/portal/admin/notifications/index.blade.php',
            'app/Http/Controllers/Admin/EmailCampaignController.php',
            'app/Models/EmailCampaign.php',
            'app/Models/EmailTemplate.php',
        ];

        foreach ($files as $file) {
            $exists = File::exists(base_path($file));
            $status = $exists ? '<info>✅</info>' : '<error>❌</error>';
            $this->line("  {$status} {$file}");

            if ($exists) {
                $size = File::size(base_path($file));
                $modified = date('Y-m-d H:i:s', File::lastModified(base_path($file)));
                $this->line("     Tamaño: {$size} bytes | Modificado: {$modified}");
            }
        }

        $this->newLine();
    }

    protected function checkPermissions()
    {
        $this->line('🔐 Verificando permisos:');

        $directories = [
            'storage',
            'storage/logs',
            'storage/framework/cache',
            'storage/framework/views',
            'bootstrap/cache',
        ];

        foreach ($directories as $dir) {
            $path = base_path($dir);
            if (File::exists($path)) {
                $perms = substr(sprintf('%o', fileperms($path)), -4);
                $writable = is_writable($path) ? '✅' : '❌';
                $this->line("  {$writable} {$dir} ({$perms})");
            } else {
                $this->line("  ❌ {$dir} (no existe)");
            }
        }

        $this->newLine();
    }

    protected function checkCache()
    {
        $this->line('💾 Estado del caché:');

        $cacheFiles = [
            'bootstrap/cache/config.php' => 'Config Cache',
            'bootstrap/cache/routes-v7.php' => 'Route Cache',
            'bootstrap/cache/packages.php' => 'Package Cache',
            'bootstrap/cache/services.php' => 'Service Cache',
        ];

        foreach ($cacheFiles as $file => $name) {
            $exists = File::exists(base_path($file));
            if ($exists) {
                $modified = date('Y-m-d H:i:s', File::lastModified(base_path($file)));
                $this->line("  ✅ {$name}: {$modified}");
            } else {
                $this->line("  ⚠️  {$name}: No cacheado");
            }
        }

        $this->newLine();
    }

    protected function checkViews()
    {
        $this->line('👁️  Verificando vistas en caché:');

        $viewCachePath = storage_path('framework/views');

        if (File::exists($viewCachePath)) {
            $viewFiles = File::files($viewCachePath);
            $count = count($viewFiles);
            $this->line("  Total de vistas cacheadas: {$count}");

            // Mostrar las 5 más recientes
            $recent = collect($viewFiles)
                ->sortByDesc(function($file) {
                    return File::lastModified($file);
                })
                ->take(5);

            $this->line("  5 vistas más recientes:");
            foreach ($recent as $file) {
                $name = basename($file);
                $modified = date('Y-m-d H:i:s', File::lastModified($file));
                $this->line("    - {$name}: {$modified}");
            }
        } else {
            $this->line("  ⚠️  Directorio de caché de vistas no existe");
        }

        $this->newLine();
    }
}
