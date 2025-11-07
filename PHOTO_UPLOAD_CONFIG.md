# 📸 Configuración de Subida de Fotos - 100MB

## ✅ Cambios Realizados

Se han actualizado los límites de subida de fotos de mascotas:

- **Antes:** 4MB (foto principal) y 6MB (fotos adicionales)
- **Ahora:** 100MB para todas las fotos

### Archivos Modificados:

1. **Backend (Laravel):**
   - `app/Http/Controllers/CheckoutController.php` (líneas 235-236)
   - Validación actualizada a `max:102400` (100MB)

2. **Frontend (Blade + JavaScript):**
   - `resources/views/public/_pet-form-modal.blade.php`
   - Validación JavaScript actualizada a `100 * 1024 * 1024`
   - Hints actualizados: "Máximo 100MB"

3. **UX Mejoradas:**
   - Barra de progreso visual en el header del modal
   - Contador de mascotas completadas vs pendientes
   - Mensajes dinámicos según el progreso del usuario
   - Botón de submit con texto contextual ("Guardar y continuar (2/5)")

---

## ⚠️ IMPORTANTE: Configuración del Servidor

Para que los archivos de 100MB funcionen en producción, debes configurar tu servidor:

### 1. PHP.ini (obligatorio)

Edita tu archivo `php.ini` y actualiza estos valores:

```ini
upload_max_filesize = 120M
post_max_size = 120M
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
```

**Ubicación común del php.ini:**
- cPanel: `/usr/local/lib/php.ini` o desde "MultiPHP INI Editor"
- Ubuntu: `/etc/php/8.2/fpm/php.ini`
- Servidor web: Consulta con tu hosting

**Después de editar php.ini:**
```bash
# Reiniciar PHP-FPM (si usas Nginx)
sudo systemctl restart php8.2-fpm

# O reiniciar Apache
sudo systemctl restart apache2
```

### 2. Nginx (si aplica)

Si usas Nginx, edita tu configuración del servidor:

```nginx
# Agregar dentro del bloque server { }
client_max_body_size 120M;
client_body_timeout 300s;
```

Ubicación: `/etc/nginx/sites-available/tu-sitio.conf`

Reiniciar Nginx:
```bash
sudo nginx -t  # Verificar configuración
sudo systemctl restart nginx
```

### 3. Apache (.htaccess)

Si usas Apache, agrega esto a tu `.htaccess` en la raíz del proyecto:

```apache
php_value upload_max_filesize 120M
php_value post_max_size 120M
php_value memory_limit 256M
php_value max_execution_time 300
php_value max_input_time 300
```

### 4. cPanel / Hosting Compartido

Si usas cPanel:

1. Ve a **Software → MultiPHP INI Editor**
2. Selecciona tu dominio
3. Actualiza estos valores:
   - `upload_max_filesize` = 120M
   - `post_max_size` = 120M
   - `memory_limit` = 256M
   - `max_execution_time` = 300
4. Guarda cambios

---

## 🧪 Cómo Probar

1. Ve a `/checkout/confirmation/{order_id}`
2. Haz clic en "Registrar mascota"
3. Intenta subir una foto de más de 20MB pero menos de 100MB
4. La foto debe subirse sin errores
5. Verifica que la validación muestre "Máximo 100MB"

---

## 📝 Notas Adicionales

- **Límite de fotos adicionales:** Sigue siendo máximo 3 fotos opcionales ✅
- **Previews de fotos:** Ya funcionan correctamente, muestran thumbnails ✅
- **Validación frontend y backend:** Ambos validan 100MB ✅
- **Progreso visual:** Modal muestra cuántas mascotas faltan por registrar ✅

---

## 🆘 Troubleshooting

### Error: "La foto supera el límite de 100MB"
- Verifica que php.ini tenga los valores correctos
- Reinicia el servidor web después de cambiar php.ini
- Verifica que `upload_max_filesize` y `post_max_size` sean >= 120M

### Error: "413 Payload Too Large" (Nginx)
- Aumenta `client_max_body_size` en la configuración de Nginx
- Reinicia Nginx: `sudo systemctl restart nginx`

### Error: "The file failed to upload" (sin más detalles)
- Revisa los logs de Laravel: `storage/logs/laravel.log`
- Revisa los logs de PHP: `/var/log/php/error.log`
- Puede ser problema de permisos en `storage/app/public/`

### La subida se queda en 0% o se congela
- Aumenta `max_execution_time` y `max_input_time` en php.ini
- Verifica tu conexión a internet (100MB puede tardar varios minutos)

---

## ✅ Checklist de Despliegue

Antes de hacer deploy a producción:

- [ ] Actualizar php.ini con los valores correctos
- [ ] Reiniciar PHP-FPM o Apache
- [ ] Si usas Nginx, actualizar client_max_body_size
- [ ] Probar subida de archivo de 50-80MB en staging
- [ ] Verificar logs no muestran errores
- [ ] Confirmar que storage/app/public tiene permisos correctos (755)
- [ ] Hacer deploy del código actualizado
- [ ] Probar en producción con un archivo real de alta resolución

---

**Última actualización:** 2025-11-07
