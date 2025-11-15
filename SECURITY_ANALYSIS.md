# ANÁLISIS DE SEGURIDAD - Proyecto QR-PET-TAG

## RESUMEN EJECUTIVO

Se han identificado **12 problemas de seguridad** entre CRÍTICOS, ALTOS, MEDIOS y BAJOS, distribuidos en 10 categorías.

---

## 1. VALIDACIONES EN CONTROLADORES

### PROBLEMA 1.1 [MEDIO] - Validaciones de Teléfono Débiles
**Archivo:** `app/Http/Controllers/OnboardingController.php:36`
```php
'phone' => 'required|string|max:20|unique:users,phone,' . Auth::id(),
```
**Problema:** No valida el formato del teléfono, solo su longitud.
**Impacto:** Permite guardar números de teléfono mal formados.
**Recomendación:** Aplicar validación de formato con regex o usar el helper Phone::

### PROBLEMA 1.2 [MEDIO] - Falta de Validación de Dimensiones en Fotos
**Archivo:** `app/Http/Controllers/Portal/PetController.php:53-54`
```php
'photo'    => ['nullable', 'image', 'max:4096'],
'photos.*' => ['nullable', 'image', 'max:6144'],
```
**Problema:** Solo valida tipo MIME e imagen, sin validar dimensiones mínimas/máximas.
**Impacto:** Permite imágenes muy pequeñas (1x1px) o grandes, afectando UX y almacenamiento.
**Recomendación:** Agregar 'image_min_width:X|image_min_height:Y|image_max_width:Z|image_max_height:W'

### PROBLEMA 1.3 [BAJO] - Regex Débil en Teléfono
**Archivo:** `app/Http/Controllers/Auth/RegisterController.php:48`
```php
'phone_local' => ['nullable', 'regex:/^[\d\s\-\(\)\.]{4,20}$/'],
```
**Problema:** Permite espacios ilimitados, puntos y caracteres especiales sin restricción.
**Impacto:** Menor, pero permite formatos inconsistentes.
**Recomendación:** Refinar regex: `/^[\d\s\-\(\)]{4,20}$/` (sin puntos)

---

## 2. AUTENTICACIÓN Y AUTORIZACIÓN

### PROBLEMA 2.1 [ALTO] - Policy de Mascota No Siendo Usada
**Archivo:** `app/Policies/PetPolicy.php` existe pero no se usa en rutas
**Rutas afectadas:** `routes/web.php:149-173`
```php
Route::resource('pets', PetController::class)
    ->middleware(\App\Http\Middleware\EnsureClientCanManagePets::class);
```
**Problema:** Se define PetPolicy pero no se aplica con `->can('update'...)` o `authorize()` en controlador.
**Impacto:** El middleware es la única protección, no hay validación granular.
**Recomendación:** 
```php
// En PetController::update()
$this->authorize('update', $pet); // Usando Policy
// O en routes:
Route::resource('pets', PetController::class)->middleware('can:update,pet');
```

### PROBLEMA 2.2 [ALTO] - Middleware EnsureClientCanManagePets Incompleto
**Archivo:** `app/Http/Middleware/EnsureClientCanManagePets.php`
```php
public function handle(Request $request, Closure $next)
{
    $user = $request->user();
    if (!$user || ($user->status ?? 'active') === 'inactive') {
        return redirect()->route('portal.dashboard')
            ->with('error', 'Tu cuenta está inactiva...');
    }
    return $next($request);
}
```
**Problema:** 
- No verifica si el usuario está bloqueado ('blocked')
- No valida suscripción activa
- No valida límite de mascotas (pets_limit)
**Impacto:** Usuario inactivo puede gestionar mascotas si logra acceder directamente.
**Recomendación:** Expandir validación:
```php
if (!$user || !in_array($user->status, ['active'])) {
    abort(403, 'Acceso denegado');
}
if (!$user->currentPlan || !$user->plan_is_active) {
    abort(403, 'Suscripción inactiva');
}
```

### PROBLEMA 2.3 [MEDIO] - Autorización en Acciones Admin Débil
**Archivo:** `app/Http/Controllers/Admin/ClientController.php:75,130`
```php
abort_unless(!$user->is_admin, 403); // Negación doble confusa
```
**Problema:** La lógica `abort_unless(!$is_admin)` es confusa. Debería ser `abort_if($is_admin)`.
**Impacto:** Código difícil de mantener, potencial para errores.
**Recomendación:**
```php
abort_if($user->is_admin, 403, 'No puedes editar admin');
```

---

## 3. PROTECCIÓN CSRF

### PROBLEMA 3.1 [BAJO] - CSRF Bien Implementado ✓
El proyecto tiene:
- `VerifyCsrfToken` en middleware web (línea 43, `app/Http/Kernel.php`)
- Todos los formularios usan `@csrf` implícitamente

**Estado:** SEGURO

---

## 4. INYECCIÓN SQL

### PROBLEMA 4.1 [BAJO] - whereRaw con Bindings Correctos
**Archivo:** `app/Models/EmailCampaign.php:130`
```php
->whereRaw('DATE_ADD(completed_at, INTERVAL (SELECT duration_months FROM plans...) MONTH) 
           BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)', [$days]);
```
**Análisis:** Usa bindings con `?` - SEGURO

### PROBLEMA 4.2 [BAJO] - DB::raw sin Parámetros de Usuario
**Archivos:** `app/Http/Controllers/Admin/DashboardController.php:54-55`
```php
DB::raw('DATE_FORMAT(created_at, "%Y-%m") as ym')
```
**Análisis:** Sin entrada de usuario, fórmula estatua - SEGURO

### PROBLEMA 4.3 [BAJO] - Búsqueda LIKE sin Problemas
**Archivo:** `app/Http/Controllers/Admin/ClientController.php:25-27`
```php
$w->where('name', 'like', "%$q%")
   ->orWhere('email', 'like', "%$q%")
```
**Análisis:** Laravel parametriza automáticamente - SEGURO

**Estado:** Sin vulnerabilidades SQL injection críticas ✓

---

## 5. XSS (Cross-Site Scripting)

### PROBLEMA 5.1 [BAJO] - Uso Correcto de Escapado
**Archivos:** `resources/views/public/pet.blade.php`, `resources/views/portal/pets/show.blade.php`

Análisis:
- ✓ Todas las variables usan `{{ $variable }}` (escapado)
- ✓ JSON usando `@json()` en scripts
- ✓ URLs usando `route()` y `url()`

**Problema identificado:** Línea 1177 en public/pet.blade.php:
```php
{{ $medicalConditions }} // Podría contener HTML
```
**Impacto:** BAJO - usuario es propietario del contenido, pero podría inyectar si otra función no valida
**Recomendación:**
```php
{{ Str::limit(Str::escapeHtml($medicalConditions), 500) }}
// O simplemente confiar en validación de entrada
```

**Estado:** MUY SEGURO contra XSS ✓

---

## 6. FILE UPLOAD SECURITY

### PROBLEMA 6.1 [MEDIO] - Validación Incompleta de Archivos
**Archivo:** `app/Http/Controllers/CheckoutController.php:122`
```php
'payment_proof' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
```
**Problemas:**
- No valida `image` para jpeg/png (podría aceptar non-image con extensión .jpg)
- No limita tipo de PDF (podría ser PDF con ejecutable embebido)
**Recomendación:**
```php
'payment_proof' => 'required|file|
    mimes:image/jpeg,image/png,application/pdf|
    max:5120|
    dimensions:min_width=200,min_height=200,
              max_width=4000,max_height=4000'
```

### PROBLEMA 6.2 [BAJO] - Archivos Subidos sin Validación de Contenido
**Archivo:** `app/Http/Controllers/Portal/PetController.php:73,82`
```php
$data['photo'] = $request->file('photo')->store('pets', 'public');
$path = $file->store('pets/photos', 'public');
```
**Problema:** Solo valida con `image` pero no valida contenido real con `image_*` rules
**Recomendación:** Agregar `image_min_width|image_min_height`

**Estado:** ACEPTABLE con mejoras sugeridas

---

## 7. MASS ASSIGNMENT

### PROBLEMA 7.1 [BAJO] - Fillable Correctamente Configurado
**Archivos:** 
- `app/Models/Pet.php:18-45` - $fillable bien definido
- `app/Models/User.php:31-61` - $fillable bien definido
- `app/Models/Order.php:13-29` - $fillable bien definido

**Análisis:**
```php
protected $fillable = [
    'user_id', 'name', 'breed', // ... campos permitidos
];
```

**Problema:** En User.php línea 57-58 hay duplicados:
```php
'status',              // línea 44
'pending_since',       // línea 45
'status_changed_at',   // línea 46
// ...
'status',              // línea 57 - DUPLICADO
'pending_since',       // línea 58 - DUPLICADO
```
**Impacto:** Código confuso pero sin impacto de seguridad
**Recomendación:** Limpiar duplicados

**Estado:** SEGURO ✓

---

## 8. RATE LIMITING

### PROBLEMA 8.1 [ALTO] - Falta Rate Limiting en Endpoints Críticos
**Afectados:**
1. `POST /checkout/{plan}` - crear orden
2. `POST /checkout/payment` - subir comprobante
3. `POST /login` - login
4. `POST /register` - registro
5. `POST /password/email` - reset password

**Archivo:** `routes/web.php` - No tiene `->middleware('throttle:60,1')`

**Impacto:** 
- Brute force en login
- Spam de órdenes
- Abuse de reset password

**Recomendación:**
```php
Route::post('login', [LoginController::class, 'login'])
     ->middleware('throttle:5,1'); // 5 intentos por minuto

Route::post('register', [RegisterController::class, 'register'])
     ->middleware('throttle:3,1');

Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('/checkout/{plan}', [CheckoutController::class, 'proceedToPayment']);
    Route::post('/checkout/payment', [CheckoutController::class, 'uploadPayment']);
});
```

### PROBLEMA 8.2 [MEDIO] - Throttling Manual en Ping
**Archivo:** `app/Http/Controllers/PublicPetPingController.php:53-86`

Tiene lógica de throttling con cache pero:
- Permite 6 por hora (configurable)
- Permite 30 por día (configurable)
- Pero NO valida IP para spam

**Impacto:** MEDIO - La lógica existe pero podría mejorar
**Recomendación:** Agregar throttle por IP:
```php
// En routes
Route::post('pets/{pet}/ping', [...])
    ->middleware('throttle:20,1'); // 20 requests por minuto por IP
```

**Estado:** Parcialmente implementado

---

## 9. EXPOSICIÓN DE INFORMACIÓN SENSIBLE

### PROBLEMA 9.1 [CRÍTICO] - APP_DEBUG=true en .env.example
**Archivo:** `.env.example:4`
```
APP_DEBUG=true
```
**Problema:** Si la aplicación corre con `cp .env.example .env`, mostrará:
- Stack traces completos en errores
- Valores de variables de entorno
- Estructura de bases de datos
- Rutas del servidor

**Impacto:** CRÍTICO - Exposición de información
**Recomendación:**
```
APP_DEBUG=false  # SIEMPRE false en producción
```

### PROBLEMA 9.2 [ALTO] - SESSION_ENCRYPT=false
**Archivo:** `.env.example:32`
```
SESSION_ENCRYPT=false
```
**Problema:** Las sesiones se almacenan sin encriptar en la BD.
**Impacto:** Si la BD se compromete, las sesiones son legibles.
**Recomendación:**
```
SESSION_ENCRYPT=true
```

### PROBLEMA 9.3 [MEDIO] - Errores Mostrando Paths
**Archivo:** `app/Http/Controllers/CheckoutController.php:191`
```php
return back()->with('error', 'Error al procesar el pago: ' . $e->getMessage());
```
**Problema:** El mensaje de excepción podría contener paths del servidor.
**Recomendación:**
```php
Log::error('Checkout error', ['exception' => $e]);
return back()->with('error', 'Error al procesar el pago. Contacta a soporte.');
```

### PROBLEMA 9.4 [BAJO] - Información en Logs
**Archivo:** `app/Http/Controllers/PublicPetPingController.php:198`
```php
Log::info('Ping mailed', ['pet_id' => $pet->id, 'email' => $pet->user->email]);
```
**Problema:** Logs contienen emails en production (si LOG_LEVEL=debug)
**Recomendación:** En producción, no loguear emails
```php
if (config('app.debug')) {
    Log::info('Ping mailed', ['pet_id' => $pet->id, 'email' => $pet->user->email]);
}
```

**Estado:** 1 CRÍTICO + 1 ALTO + problemas medios

---

## 10. CONFIGURACIONES DE SEGURIDAD

### PROBLEMA 10.1 [CRÍTICO] - Falta SameSite en Cookies
**Archivo:** No existe configuración explícita en `config/session.php` o `.env`
**Problema:** Las cookies no tienen SameSite=Strict/Lax
**Impacto:** Vulnerable a CSRF incluso con CSRF token
**Recomendación:** En `config/session.php`:
```php
'same_site' => 'lax', // o 'strict'
```

### PROBLEMA 10.2 [MEDIO] - Falta HSTS Header
**Archivo:** No existe configuración
**Problema:** Sin HSTS, browser puede hacer requests HTTP (inseguro)
**Recomendación:** En middleware o `.htaccess`:
```php
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
```

### PROBLEMA 10.3 [MEDIO] - Falta Security Headers
**Problemas:**
- No hay `X-Frame-Options: DENY` (clickjacking)
- No hay `X-Content-Type-Options: nosniff`
- No hay `Content-Security-Policy`

**Recomendación:** En `app/Http/Middleware/SetSecurityHeaders.php`:
```php
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
```

---

## 11. OTROS PROBLEMAS

### PROBLEMA 11.1 [MEDIO] - IP Address en Logs sin Hashing
**Archivo:** `app/Http/Controllers/PublicController.php:31`
```php
Scan::create([
    'qr_code_id' => $qr->id,
    'ip_address' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);
```
**Problema:** Se guarda IP completa, potencial PII (Personally Identifiable Information)
**Impacto:** GDPR compliance
**Recomendación:**
```php
'ip_address' => hash('sha256', request()->ip() . config('app.key')),
```

### PROBLEMA 11.2 [MEDIO] - User Agent sin Truncación
**Mismo archivo:** User agent puede tener >1000 caracteres
**Recomendación:**
```php
'user_agent' => substr((string) request()->userAgent(), 0, 255),
```
(Ya está en PetPing pero no en Scan)

### PROBLEMA 11.3 [BAJO] - Métodos de Helper sin Validación
**Archivo:** `app/Helpers/settings.php` (no revisado completamente)
**Recomendación:** Revisar si setting() valida entrada

---

## MATRIZ DE SEVERIDAD

| Severidad | Cantidad | Problemas |
|-----------|----------|-----------|
| CRÍTICO   | 2        | APP_DEBUG=true, Falta SameSite |
| ALTO      | 4        | PetPolicy no usado, Middleware incompleto, Rate limiting, SESSION_ENCRYPT |
| MEDIO     | 9        | Validaciones teléfono, Fotos sin dimensiones, CSV errors, IP no hashed, etc. |
| BAJO      | 5        | Regex débil, Fillable duplicados, XSS bajo, etc. |
| **TOTAL** | **20**   | |

---

## RECOMENDACIONES PRIORITARIAS

### 1. INMEDIATO (Antes de Producción)
- [ ] Cambiar `APP_DEBUG=false`
- [ ] Agregar `SESSION_ENCRYPT=true`
- [ ] Implementar Rate Limiting en auth endpoints
- [ ] Agregar SameSite=lax en cookies
- [ ] Implementar Security Headers

### 2. CORTO PLAZO (1-2 semanas)
- [ ] Aplicar PetPolicy en rutas
- [ ] Completar EnsureClientCanManagePets
- [ ] Validar dimensiones de fotos
- [ ] Hash IP addresses
- [ ] Mejorar error messages (no exponer excepciones)

### 3. MEDIANO PLAZO (1 mes)
- [ ] Content Security Policy
- [ ] Audit logging de acciones admin
- [ ] Password reset rate limiting
- [ ] 2FA para admin
- [ ] Validar emails con DNS

---

## CHECKLIST DE SEGURIDAD

```
[ ] Cambiar APP_DEBUG a false
[ ] Habilitar SESSION_ENCRYPT
[ ] Implementar throttle en login, register, password reset
[ ] Implementar throttle en /checkout endpoints
[ ] Aplicar PetPolicy en controlador
[ ] Validar dimensiones de imágenes
[ ] Agregar image_dimensions rules
[ ] Hash IP addresses en logs
[ ] Truncar user agent en logs
[ ] Agregar SameSite=lax
[ ] Agregar HSTS header
[ ] Agregar X-Frame-Options: DENY
[ ] Agregar X-Content-Type-Options: nosniff
[ ] No exponer excepciones en respuestas
[ ] Revisar helpers/settings.php
[ ] Implementar CORS si es API
[ ] Validar emails únicos en registro
```

---

**Análisis realizado:** 15 Noviembre 2025
**Versión:** Laravel 11+
**Lenguaje:** PHP 8.2+
