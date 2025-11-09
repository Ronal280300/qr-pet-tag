# 🔧 Troubleshooting - Problemas Comunes en Producción

## ❌ Error: "View [admin.notifications.index] not found"

### 📋 Síntomas
```
View [admin.notifications.index] not found.
```

El archivo existe pero Laravel no lo encuentra.

### 🔍 Diagnóstico

**Este error indica que el código en producción está DESACTUALIZADO.**

El controlador viejo tenía:
```php
return view('admin.notifications.index', compact('notifications'));
```

Pero el código nuevo debe tener:
```php
return view('portal.admin.notifications.index', compact('notifications'));
```

### ✅ Solución Paso a Paso

#### **Opción 1: Script Automatizado (Recomendado)**

```bash
cd /home2/safewors/public_html/qr-pet-tag

# Dar permisos
chmod +x fix-production.sh

# Ejecutar
./fix-production.sh
```

#### **Opción 2: Comandos Artisan**

```bash
cd /home2/safewors/public_html/qr-pet-tag

# 1. Diagnosticar el problema
php artisan diagnose:production

# 2. Asegurarse de tener el código actualizado
git status
git pull origin main  # o tu branch

# 3. Limpiar caché de Composer
composer dump-autoload --optimize --no-dev

# 4. Limpiar cachés de Laravel
php artisan cache:clear-all --optimize
```

#### **Opción 3: Manual (paso por paso)**

```bash
cd /home2/safewors/public_html/qr-pet-tag

# 1. Verificar versión del código
git log --oneline -5

# 2. Actualizar código si es necesario
git pull origin main

# 3. Limpiar OPcache (si está disponible)
# En cPanel: PHP Selector → OPcache → Reset
# O reiniciar PHP-FPM

# 4. Composer autoload
composer dump-autoload --optimize --no-dev

# 5. Limpiar cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 6. Cachear de nuevo
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 7. Permisos
chmod -R 775 storage bootstrap/cache
```

---

## ❌ Error: "Class not found" después de actualizar código

### 🔍 Causa
Autoload de Composer desactualizado

### ✅ Solución
```bash
composer dump-autoload --optimize --no-dev
php artisan config:clear
php artisan config:cache
```

---

## ❌ Error: Cambios no se reflejan en producción

### 🔍 Causas posibles
1. Código no se subió correctamente
2. Caché viejo (config, view, route)
3. OPcache de PHP
4. Browser cache

### ✅ Solución

```bash
# 1. Verificar que el código se subió
git status
git log --oneline -3

# 2. Si no está actualizado
git pull origin main

# 3. Limpiar TODO
./fix-production.sh

# 4. Si el problema persiste, reiniciar PHP-FPM
# (Desde cPanel o contactar hosting)
```

---

## ❌ Error: "500 Internal Server Error" sin mensaje

### 🔍 Diagnóstico
Ver los logs:

```bash
# Últimas 50 líneas del log
tail -50 storage/logs/laravel.log

# Seguir en tiempo real
tail -f storage/logs/laravel.log
```

### ✅ Solución según el error

**Si dice "Permission denied":**
```bash
chmod -R 775 storage bootstrap/cache
chown -R usuario:usuario storage bootstrap/cache
```

**Si dice "View not found":**
```bash
php artisan view:clear
php artisan view:cache
```

**Si dice "Class not found":**
```bash
composer dump-autoload --optimize --no-dev
php artisan config:clear
php artisan config:cache
```

---

## 🛠️ Comandos de Diagnóstico

### Verificar estado del sistema
```bash
php artisan diagnose:production
```

Esto mostrará:
- Entorno (APP_ENV, APP_DEBUG, PHP version)
- Archivos clave (existen, tamaño, última modificación)
- Permisos de directorios
- Estado del caché
- Vistas cacheadas

### Limpiar TODO el caché
```bash
php artisan cache:clear-all --optimize
```

### Ver información de Laravel
```bash
php artisan --version
php artisan about
```

---

## 🔥 Solución de Emergencia

**Si NADA funciona:**

```bash
# 1. Modo mantenimiento
php artisan down

# 2. Resetear TODO
rm -rf bootstrap/cache/*.php
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*

# 3. Cachear de nuevo
composer dump-autoload --optimize --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# 4. Permisos
chmod -R 775 storage bootstrap/cache

# 5. Reactivar
php artisan up
```

---

## 📞 Escalar al Hosting

**Si el problema persiste, contacta al hosting pidiendo:**

1. **Reiniciar PHP-FPM:**
   ```
   sudo systemctl restart php-fpm
   ```

2. **Limpiar OPcache:**
   ```
   sudo service php8.2-fpm reload
   ```

3. **Verificar permisos del usuario web:**
   ```
   chown -R www-data:www-data /ruta/del/proyecto/storage
   ```

---

## ✅ Checklist Post-Fix

Después de aplicar cualquier solución:

- [ ] Limpiar caché del navegador (Ctrl+Shift+R)
- [ ] Probar en modo incógnito
- [ ] Verificar `/portal/admin/notifications`
- [ ] Verificar `/portal/admin/email-campaigns`
- [ ] Revisar logs: `tail -20 storage/logs/laravel.log`
- [ ] Verificar permisos: `ls -la storage`

---

## 📚 Recursos

- [Documentación Laravel - Deployment](https://laravel.com/docs/deployment)
- [Laravel - Configuration Cache](https://laravel.com/docs/configuration#configuration-caching)
- [Composer - Autoload Optimization](https://getcomposer.org/doc/articles/autoloader-optimization.md)
