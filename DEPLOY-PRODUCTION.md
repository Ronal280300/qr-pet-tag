# 🚀 Guía de Despliegue a Producción

## 📋 Checklist Pre-Despliegue

- [ ] Todos los cambios commiteados
- [ ] Tests pasando (si existen)
- [ ] Variables de entorno configuradas en `.env` de producción
- [ ] Base de datos respaldada

---

## 🔧 Pasos para Desplegar

### 1. Subir código a producción
```bash
git pull origin main
# o
git pull origin tu-branch
```

### 2. Instalar dependencias
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 3. **IMPORTANTE: Limpiar caché de Laravel**

#### Opción A: Script automatizado (recomendado)
```bash
chmod +x clear-cache.sh
./clear-cache.sh
```

#### Opción B: Manual
```bash
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

### 4. Ejecutar migraciones (si hay nuevas)
```bash
php artisan migrate --force
```

### 5. Optimizar para producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 6. Ajustar permisos
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## ⚠️ Solución de Problemas Comunes

### Error: "View [xxx] not found"
**Causa:** Caché de vistas desactualizado

**Solución:**
```bash
php artisan view:clear
php artisan view:cache
```

### Error: "Class not found" o "Route not found"
**Causa:** Caché de configuración/rutas desactualizado

**Solución:**
```bash
php artisan config:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
```

### Error: "Undefined constant" en Blade
**Causa:** Caché de vistas con sintaxis vieja

**Solución:**
```bash
php artisan view:clear
# Esperar 5 segundos
php artisan view:cache
```

### Cambios no se reflejan en producción
**Solución completa:**
```bash
php artisan down --message="Actualizando sistema"
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
php artisan up
```

---

## 📝 Notas Importantes

1. **Siempre respalda la base de datos antes de desplegar**
2. **Usa modo mantenimiento (`php artisan down`) durante actualizaciones grandes**
3. **Limpia caché después de CADA despliegue**
4. **Verifica que `.env` de producción esté actualizado**
5. **Revisa los logs después de desplegar:** `storage/logs/laravel.log`

---

## 🐛 Debugging en Producción

### Ver logs en tiempo real
```bash
tail -f storage/logs/laravel.log
```

### Limpiar logs viejos
```bash
echo "" > storage/logs/laravel.log
```

### Modo debug (solo temporal)
```bash
# En .env
APP_DEBUG=true
APP_ENV=local

# Después de debuggear, VOLVER A:
APP_DEBUG=false
APP_ENV=production
```

---

## 📊 Post-Despliegue

- [ ] Verificar que la aplicación carga correctamente
- [ ] Probar rutas críticas (login, registro, dashboard)
- [ ] Verificar notificaciones
- [ ] Revisar logs de errores
- [ ] Confirmar que email funciona (si aplica)

---

## 🆘 Rollback de Emergencia

Si algo sale mal:

```bash
# 1. Volver al commit anterior
git log --oneline -5  # Ver últimos commits
git reset --hard COMMIT_HASH

# 2. Limpiar caché
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 3. Restaurar base de datos (si fue modificada)
# Usar el respaldo que hiciste antes

# 4. Volver a subir
php artisan config:cache
php artisan route:cache
php artisan up
```
