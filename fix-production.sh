#!/bin/bash

# Script para resolver problemas de código viejo en producción
# Uso: ./fix-production.sh

echo "🔧 Fix de código viejo en producción"
echo "===================================="
echo ""

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Verificar si estamos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: No se encuentra el archivo artisan${NC}"
    echo "Ejecuta este script desde la raíz del proyecto Laravel"
    exit 1
fi

echo -e "${YELLOW}Paso 1: Verificando archivos...${NC}"
# Verificar que el controlador existe
if [ ! -f "app/Http/Controllers/Admin/NotificationController.php" ]; then
    echo -e "${RED}❌ NotificationController.php no encontrado${NC}"
    exit 1
fi
echo -e "${GREEN}✅ NotificationController.php encontrado${NC}"

# Verificar que la vista existe
if [ ! -f "resources/views/portal/admin/notifications/index.blade.php" ]; then
    echo -e "${RED}❌ Vista index.blade.php no encontrada${NC}"
    exit 1
fi
echo -e "${GREEN}✅ Vista index.blade.php encontrada${NC}"
echo ""

echo -e "${YELLOW}Paso 2: Limpiando cachés de PHP...${NC}"
# Limpiar OPcache si está disponible
if command -v php &> /dev/null; then
    php -r "if (function_exists('opcache_reset')) { opcache_reset(); echo 'OPcache limpiado\n'; } else { echo 'OPcache no disponible\n'; }"
fi

# Limpiar autoload de Composer
composer dump-autoload --optimize --no-dev
echo -e "${GREEN}✅ Autoload de Composer regenerado${NC}"
echo ""

echo -e "${YELLOW}Paso 3: Limpiando cachés de Laravel...${NC}"
php artisan config:clear
echo -e "${GREEN}✅ Config cache cleared${NC}"

php artisan cache:clear
echo -e "${GREEN}✅ Application cache cleared${NC}"

php artisan view:clear
echo -e "${GREEN}✅ View cache cleared${NC}"

php artisan route:clear
echo -e "${GREEN}✅ Route cache cleared${NC}"
echo ""

echo -e "${YELLOW}Paso 4: Optimizando para producción...${NC}"
php artisan config:cache
echo -e "${GREEN}✅ Config cached${NC}"

php artisan route:cache
echo -e "${GREEN}✅ Routes cached${NC}"

php artisan view:cache
echo -e "${GREEN}✅ Views cached${NC}"

php artisan optimize
echo -e "${GREEN}✅ Application optimized${NC}"
echo ""

echo -e "${YELLOW}Paso 5: Verificando permisos...${NC}"
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✅ Permisos ajustados${NC}"
echo ""

echo -e "${GREEN}================================${NC}"
echo -e "${GREEN}🎉 ¡Proceso completado!${NC}"
echo -e "${GREEN}================================${NC}"
echo ""
echo "Ahora prueba acceder a:"
echo "  /portal/admin/notifications"
echo ""
echo "Si el problema persiste, verifica que el código se subió correctamente con:"
echo "  git status"
echo "  git log --oneline -5"
