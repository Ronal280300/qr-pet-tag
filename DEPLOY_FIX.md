# 🚨 FIX DE EMERGENCIA PARA PRODUCCIÓN

## Problema Detectado
- Error 500 al acceder a `/planes` y `/configuracion`
- Middleware 'maintenance' no encontrado
- SettingsController no encontrado

## Causa
Falta ejecutar comandos de autoload y caché en producción después del deploy.

---

## ✅ SOLUCIÓN RÁPIDA (Ejecutar en producción)

### 1. Conectarse al servidor por SSH
```bash
ssh tu_usuario@tu_servidor
cd /home2/safewors/public_html/qr-pet-tag
```

### 2. Ejecutar estos comandos EN ORDEN:
```bash
# 1. Regenerar autoload de Composer (MUY IMPORTANTE)
composer dump-autoload

# 2. Limpiar TODOS los cachés de Laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 3. Recrear cachés optimizados
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Verificar permisos
chmod -R 755 storage bootstrap/cache
chown -R tu_usuario:tu_usuario storage bootstrap/cache
```

### 3. Verificar que los archivos existen:
```bash
# Verificar middleware
ls -la app/Http/Middleware/CheckMaintenanceMode.php

# Verificar controlador de settings
ls -la app/Http/Controllers/Admin/SettingsController.php

# Si NO existen, copiarlos del repositorio
```

---

## 🔄 Después de ejecutar los comandos:

1. Probar acceso a `/planes` - Debe funcionar
2. Probar acceso a `/configuracion` - Debe funcionar
3. Una vez confirmado que funciona, hacer pull del último commit que rehabilita el middleware

---

## 📝 Notas Importantes

**MIDDLEWARE DESHABILITADO TEMPORALMENTE:**
- El middleware 'maintenance' está comentado temporalmente
- Las rutas de planes funcionan SIN el middleware
- Esto es SEGURO y TEMPORAL hasta que se arregle el autoload

**REHABILITAR MIDDLEWARE (después de arreglar):**
Una vez que ejecutes los comandos y confirmes que todo funciona, puedes hacer pull del commit que rehabilita el middleware.

---

## 🆘 Si el problema persiste:

### Verificar que los archivos se subieron correctamente:
```bash
# Verificar estructura
ls -la app/Http/Middleware/
ls -la app/Http/Controllers/Admin/
ls -la bootstrap/

# Ver contenido de bootstrap/app.php
cat bootstrap/app.php
```

### Verificar permisos:
```bash
# Todos los archivos PHP deben ser legibles
find . -name "*.php" -type f -exec chmod 644 {} \;

# Directorios deben tener permisos 755
find . -type d -exec chmod 755 {} \;
```

### Revisar logs detallados:
```bash
tail -f storage/logs/laravel.log
```

---

## 📞 Contacto

Si después de ejecutar TODOS los comandos el problema persiste:
1. Verificar que composer.json tiene el autoload correcto
2. Ejecutar `composer install` (puede tardar)
3. Verificar versión de PHP (debe ser 8.2+)
