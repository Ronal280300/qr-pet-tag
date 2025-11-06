#!/usr/bin/env php
<?php
/**
 * Script de diagnóstico para problemas de autoload
 * Ejecutar: php diagnose.php
 */

echo "=== DIAGNÓSTICO DE PRODUCCIÓN ===\n\n";

// 1. Verificar PHP
echo "1. Versión de PHP:\n";
echo "   " . PHP_VERSION . "\n";
if (version_compare(PHP_VERSION, '8.2.0', '<')) {
    echo "   ❌ ERROR: Se requiere PHP 8.2 o superior\n";
} else {
    echo "   ✅ OK\n";
}
echo "\n";

// 2. Verificar archivos
echo "2. Verificación de archivos:\n";
$files = [
    'app/Http/Controllers/Admin/SettingsController.php',
    'app/Http/Middleware/CheckMaintenanceMode.php',
    'app/Models/Setting.php',
    'bootstrap/app.php',
    'vendor/autoload.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "   ✅ $file\n";
    } else {
        echo "   ❌ FALTA: $file\n";
    }
}
echo "\n";

// 3. Verificar permisos
echo "3. Verificación de permisos:\n";
$dirs = [
    'storage',
    'bootstrap/cache',
    'app/Http/Controllers/Admin',
];

foreach ($dirs as $dir) {
    if (is_readable($dir) && is_writable($dir)) {
        echo "   ✅ $dir (lectura/escritura OK)\n";
    } else {
        echo "   ❌ $dir (problemas de permisos)\n";
    }
}
echo "\n";

// 4. Verificar autoload
echo "4. Verificación de autoload:\n";
if (file_exists('vendor/autoload.php')) {
    require 'vendor/autoload.php';

    $classes = [
        'App\\Http\\Controllers\\Admin\\SettingsController',
        'App\\Http\\Middleware\\CheckMaintenanceMode',
        'App\\Models\\Setting',
    ];

    foreach ($classes as $class) {
        if (class_exists($class)) {
            echo "   ✅ $class\n";
        } else {
            echo "   ❌ NO ENCONTRADA: $class\n";
        }
    }
} else {
    echo "   ❌ vendor/autoload.php no existe\n";
    echo "   Ejecutar: composer install\n";
}
echo "\n";

// 5. Verificar archivos de caché
echo "5. Archivos de caché:\n";
$cacheFiles = [
    'bootstrap/cache/config.php',
    'bootstrap/cache/routes-v7.php',
    'bootstrap/cache/packages.php',
];

foreach ($cacheFiles as $file) {
    if (file_exists($file)) {
        $age = time() - filemtime($file);
        $ageMinutes = round($age / 60);
        echo "   📄 $file (hace $ageMinutes minutos)\n";
    } else {
        echo "   ⚪ $file (no existe - OK si acabas de limpiar caché)\n";
    }
}
echo "\n";

// 6. Verificar composer.json
echo "6. Composer autoload configurado:\n";
if (file_exists('composer.json')) {
    $composer = json_decode(file_get_contents('composer.json'), true);
    if (isset($composer['autoload']['psr-4']['App\\\\'])) {
        echo "   ✅ PSR-4 configurado: " . $composer['autoload']['psr-4']['App\\\\'] . "\n";
    } else {
        echo "   ❌ PSR-4 no configurado correctamente\n";
    }
} else {
    echo "   ❌ composer.json no existe\n";
}
echo "\n";

// 7. Resumen
echo "=== RESUMEN ===\n";
echo "Si ves ❌ arriba, ejecuta:\n\n";
echo "  composer dump-autoload --optimize\n";
echo "  php artisan cache:clear\n";
echo "  php artisan config:clear\n";
echo "  php artisan route:clear\n";
echo "  chmod -R 755 storage bootstrap/cache\n";
echo "\n";
