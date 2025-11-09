#!/bin/bash

echo "🧹 Limpiando caché de Laravel..."

# Limpiar caché de configuración
php artisan config:clear
echo "✅ Config cache cleared"

# Limpiar caché de vistas
php artisan view:clear
echo "✅ View cache cleared"

# Limpiar caché de rutas
php artisan route:clear
echo "✅ Route cache cleared"

# Limpiar caché de aplicación
php artisan cache:clear
echo "✅ Application cache cleared"

# Opcional: Optimizar para producción después de limpiar
read -p "¿Optimizar caché para producción? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    php artisan config:cache
    echo "✅ Config cached"

    php artisan route:cache
    echo "✅ Routes cached"

    php artisan view:cache
    echo "✅ Views cached"
fi

echo ""
echo "🎉 ¡Limpieza completada!"
