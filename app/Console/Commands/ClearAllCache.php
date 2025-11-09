<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearAllCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-all {--optimize : Optimizar después de limpiar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpia todos los cachés de Laravel (config, view, route, cache)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Limpiando todos los cachés de Laravel...');
        $this->newLine();

        // Config cache
        $this->call('config:clear');
        $this->line('✅ Config cache cleared');

        // View cache
        $this->call('view:clear');
        $this->line('✅ View cache cleared');

        // Route cache
        $this->call('route:clear');
        $this->line('✅ Route cache cleared');

        // Application cache
        $this->call('cache:clear');
        $this->line('✅ Application cache cleared');

        $this->newLine();

        // Si se solicita optimización
        if ($this->option('optimize')) {
            $this->info('⚡ Optimizando para producción...');
            $this->newLine();

            $this->call('config:cache');
            $this->line('✅ Config cached');

            $this->call('route:cache');
            $this->line('✅ Routes cached');

            $this->call('view:cache');
            $this->line('✅ Views cached');

            $this->call('optimize');
            $this->line('✅ Optimized');

            $this->newLine();
            $this->info('🎉 ¡Caché limpiado y optimizado para producción!');
        } else {
            $this->info('🎉 ¡Caché limpiado exitosamente!');
            $this->newLine();
            $this->comment('💡 Tip: Usa --optimize para cachear después de limpiar');
            $this->comment('   Ejemplo: php artisan cache:clear-all --optimize');
        }

        return 0;
    }
}
