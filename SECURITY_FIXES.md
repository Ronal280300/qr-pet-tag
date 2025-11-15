# GUÍA DE SOLUCIONES - PROBLEMAS DE SEGURIDAD

## PROBLEMAS CRÍTICOS - Deben Solucionarse ANTES de Producción

### 1. APP_DEBUG=true (Exposición Crítica)

**Archivo:** `.env.example:4`

**Problema Actual:**
```env
APP_DEBUG=true
```

**Solución:**
```env
APP_DEBUG=false
```

**Verificación:**
```bash
# En producción, no debe mostrar stack trace en errores
curl https://tu-app.com/ruta-inexistente
# Debe mostrar error genérico, NO stack trace completo
```

---

### 2. Falta SameSite en Cookies

**Archivo:** `config/session.php`

**Solución:**
```php
// Agregar esta línea en config/session.php
'same_site' => 'lax',  // o 'strict'

// Configuración completa:
return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'encrypt' => env('SESSION_ENCRYPT', true),  // Cambiar también a true
    'same_site' => 'lax',  // AGREGAR ESTA LÍNEA
    // ... resto de configuración
];
```

**Verificación:**
```bash
# Ver headers de cookies
curl -i https://tu-app.com | grep -i set-cookie
# Debe incluir: SameSite=Lax
```

---

### 3. SESSION_ENCRYPT=false

**Archivo:** `.env.example:32`

**Problema Actual:**
```env
SESSION_ENCRYPT=false
```

**Solución:**
```env
SESSION_ENCRYPT=true
```

---

## PROBLEMAS ALTOS - Deben Solucionarse Urgentemente

### 4. PetPolicy No Siendo Usada

**Archivo:** `app/Http/Controllers/Portal/PetController.php`

**Problema:** Existe `app/Policies/PetPolicy.php` pero no se usa en el controlador.

**Solución:**
```php
<?php
namespace App\Http\Controllers\Portal;

class PetController extends Controller
{
    // ... método existente ...
    
    public function show(Pet $pet)
    {
        // AGREGAR: Validar con Policy
        $this->authorize('view', $pet);  // <- NUEVA LÍNEA
        
        $this->authorizePetOrAdmin($pet);
        $pet->load(['photos', 'qrCode']);

        $qr = $pet->qrCode;
        return view('portal.pets.show', [
            'pet' => $pet,
            'qr'  => $qr,
        ]);
    }

    public function edit(Pet $pet)
    {
        // AGREGAR: Validar con Policy
        $this->authorize('update', $pet);  // <- NUEVA LÍNEA
        
        // ... resto del código
    }

    public function update(Request $request, Pet $pet)
    {
        // AGREGAR: Validar con Policy
        $this->authorize('update', $pet);  // <- NUEVA LÍNEA
        
        // ... resto del código
    }

    public function destroy(Pet $pet)
    {
        // AGREGAR: Validar con Policy
        $this->authorize('delete', $pet);  // <- NUEVA LÍNEA
        
        // ... resto del código
    }
}
```

**Alternativa en Routes:**
```php
// routes/web.php
Route::resource('pets', PetController::class)
    ->middleware([
        \App\Http\Middleware\EnsureClientCanManagePets::class,
        'can:update,pet'  // <- AGREGAR (para UPDATE)
    ]);
```

---

### 5. Middleware EnsureClientCanManagePets Incompleto

**Archivo:** `app/Http/Middleware/EnsureClientCanManagePets.php`

**Solución Completa:**
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureClientCanManagePets
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // 1. Validar que el usuario exista
        if (!$user) {
            abort(401, 'No autenticado');
        }

        // 2. Validar que la cuenta no esté bloqueada/inactiva
        if (!in_array($user->status ?? 'active', ['active', 'pending'])) {
            return redirect()
                ->route('portal.dashboard')
                ->with('error', 'Tu cuenta está ' . $user->status . '. Contacta a soporte.');
        }

        // 3. Validar que tenga suscripción activa (opcional pero recomendado)
        if (!$user->plan_is_active && !$this->isInFreeTrial($user)) {
            return redirect()
                ->route('portal.dashboard')
                ->with('error', 'Tu suscripción ha expirado. Renovla para gestionar mascotas.');
        }

        // 4. Validar que no exceda límite de mascotas
        if ($user->pets_limit > 0) {
            $petCount = $user->pets()->count();
            if ($petCount >= $user->pets_limit && $request->isMethod('POST')) {
                abort(403, "Has alcanzado el límite de {$user->pets_limit} mascotas");
            }
        }

        // 5. Registrar en logs (auditoría)
        \Illuminate\Support\Facades\Log::info('Pet management accessed', [
            'user_id' => $user->id,
            'route' => $request->path(),
        ]);

        return $next($request);
    }

    private function isInFreeTrial($user): bool
    {
        // Implementar lógica de prueba gratuita si existe
        return $user->created_at->diffInDays() < 7;
    }
}
```

---

### 6. Rate Limiting en Endpoints Críticos

**Archivo:** `routes/web.php`

**Solución:**
```php
<?php

// Auth routes con rate limiting
Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])
    ->middleware('throttle:5,1')  // 5 intentos por minuto
    ->name('login');

Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])
    ->middleware('throttle:3,1')  // 3 intentos por minuto
    ->name('register');

// Password reset
Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('throttle:3,1')
    ->name('password.email');

// Checkout - importante para evitar spam de órdenes
Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('/checkout/{plan}', [CheckoutController::class, 'proceedToPayment'])
        ->name('checkout.create');
    Route::post('/checkout/payment', [CheckoutController::class, 'uploadPayment'])
        ->name('checkout.upload');
});

// QR Ping - para evitar spam
Route::post('/p/{slug}/ping', [PublicPetPingController::class, 'store'])
    ->middleware('throttle:20,1')  // 20 requests por minuto por IP
    ->name('public.pet.ping');
```

**Personalizar límites en `config/rate-limiting.php`:**
```php
return [
    'global' => [
        'limit' => env('RATE_LIMIT', '60,1'),
    ],

    'api' => [
        'limit' => env('API_RATE_LIMIT', '60,1'),
    ],

    'login' => [
        'limit' => '5,1',
    ],

    'checkout' => [
        'limit' => '10,1',
    ],
];
```

---

## PROBLEMAS MEDIOS - Importante

### 7. Validación Incompleta de Fotos

**Archivo:** `app/Http/Controllers/Portal/PetController.php:47-65`

**Solución Completa:**
```php
$data = $request->validate([
    'name'               => ['required', 'string', 'max:120'],
    'breed'              => ['nullable', 'string', 'max:120'],
    'zone'               => ['nullable', 'string', 'max:255'],
    'age'                => ['nullable', 'integer', 'min:0', 'max:50'],
    'medical_conditions' => ['nullable', 'string', 'max:500'],
    
    // MEJORADO: Agregar validación de dimensiones
    'photo'    => [
        'nullable',
        'image',
        'mimes:jpeg,png,jpg,webp',
        'max:4096',  // 4MB
        'image_min_width:200',
        'image_min_height:200',
        'image_max_width:4000',
        'image_max_height:4000',
    ],
    
    'photos.*' => [
        'nullable',
        'image',
        'mimes:jpeg,png,jpg,webp',
        'max:6144',  // 6MB
        'image_min_width:200',
        'image_min_height:200',
        'image_max_width:4000',
        'image_max_height:4000',
    ],

    // ... resto de campos
]);
```

**Validación Personalizada (si es necesario más control):**
```php
use Illuminate\Validation\Rule;

$request->validate([
    'photo' => [
        'nullable',
        'image',
        Rule::dimensions()->minWidth(200)->minHeight(200)->maxWidth(4000)->maxHeight(4000),
        'max:4096',
    ],
    // ...
]);
```

---

### 8. IP Address sin Hashing (GDPR)

**Archivo:** `app/Http/Controllers/PublicController.php:29-34`

**Problema Actual:**
```php
Scan::create([
    'qr_code_id' => $qr->id,
    'ip_address' => request()->ip(),  // Guarda IP completa
    'user_agent' => request()->userAgent(),
    'location'   => null,
]);
```

**Solución:**
```php
Scan::create([
    'qr_code_id' => $qr->id,
    // Hashear IP para GDPR compliance
    'ip_address' => hash('sha256', request()->ip() . config('app.key')),
    // Limitar user agent
    'user_agent' => substr((string)request()->userAgent(), 0, 255),
    'location'   => null,
]);
```

---

### 9. No Exponer Excepciones en Respuestas

**Archivo:** `app/Http/Controllers/CheckoutController.php:191`

**Problema Actual:**
```php
catch (\Exception $e) {
    DB::rollBack();
    return back()
        ->with('error', 'Error al procesar el pago: ' . $e->getMessage())
        ->withInput();
}
```

**Solución:**
```php
catch (\Exception $e) {
    DB::rollBack();

    // Loguear la excepción completa
    \Illuminate\Support\Facades\Log::error('Checkout error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
        'user_id' => Auth::id(),
    ]);

    // Mostrar mensaje genérico al usuario
    return back()
        ->with('error', 'Error al procesar el pago. Contacta a soporte si persiste.')
        ->withInput();
}
```

---

### 10. Seguridad Headers (HSTS, X-Frame-Options, etc)

**Archivo:** Crear `app/Http/Middleware/SecurityHeaders.php`

**Solución:**
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Prevent clickjacking
        $response->header('X-Frame-Options', 'DENY');

        // Prevent MIME type sniffing
        $response->header('X-Content-Type-Options', 'nosniff');

        // Enable XSS filtering in older browsers
        $response->header('X-XSS-Protection', '1; mode=block');

        // HSTS - Force HTTPS (solo en producción)
        if (config('app.env') === 'production') {
            $response->header(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // Content Security Policy
        $response->header(
            'Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' cdn.jsdelivr.net; " .
            "style-src 'self' 'unsafe-inline' cdn.jsdelivr.net; " .
            "img-src 'self' data: https:; " .
            "font-src 'self' cdn.jsdelivr.net; " .
            "connect-src 'self' *.cloudflare.com; " .
            "frame-ancestors 'none'; " .
            "base-uri 'self'; " .
            "form-action 'self'"
        );

        // Referrer Policy
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Feature Policy
        $response->header(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=()'
        );

        return $response;
    }
}
```

**Registrar en `app/Http/Kernel.php`:**
```php
protected $middleware = [
    // ... otros middleware ...
    \App\Http\Middleware\SecurityHeaders::class,  // <- AGREGAR
];
```

---

### 11. Validación de Teléfono Mejorada

**Archivo:** `app/Http/Controllers/Auth/RegisterController.php:48`

**Solución:**
```php
// Antes
'phone_local' => ['nullable', 'regex:/^[\d\s\-\(\)\.]{4,20}$/'],

// Después (más restrictivo)
'phone_local' => [
    'nullable',
    'regex:/^[0-9\s\-\(\)]{4,20}$/',  // Sin puntos
    'min:4',
    'max:20',
],
```

---

### 12. Fillable Duplicados - Limpiar

**Archivo:** `app/Models/User.php:31-61`

**Problema Actual:**
```php
protected $fillable = [
    'name',
    'email',
    'phone',
    // ...
    'status',              // línea 44
    'pending_since',       // línea 45
    'status_changed_at',   // línea 46
    // ...
    'status',              // línea 57 - DUPLICADO
    'pending_since',       // línea 58 - DUPLICADO
];
```

**Solución:**
```php
protected $fillable = [
    'name',
    'email',
    'password',
    'phone',
    'address',
    'emergency_contact',
    'is_admin',
    'google_id',
    'avatar',
    'avatar_original',
    'status',
    'pending_since',
    'status_changed_at',
    'current_plan_id',
    'current_order_id',
    'pets_limit',
    'plan_started_at',
    'plan_expires_at',
    'plan_is_active',
    'notes',
    'tags',
];
```

---

## HERRAMIENTAS PARA VALIDAR SEGURIDAD

```bash
# Escanear vulnerabilidades PHP
composer require --dev enlightn/security-checker
php artisan security:check

# Auditar dependencias
composer audit

# Verificar rate limiting
ab -n 100 -c 1 https://tu-app.com/login

# Verificar headers de seguridad
curl -i https://tu-app.com | grep -iE "x-frame|x-content|strict-transport|content-security"

# Validar HTTPS
nmap --script ssl-enum-ciphers -p 443 tu-app.com
```

---

## CHECKLIST FINAL

Antes de llevar a producción, verificar:

```
[ ] APP_DEBUG=false en .env
[ ] SESSION_ENCRYPT=true
[ ] same_site='lax' en session.php
[ ] Rate limiting en /login, /register, /checkout
[ ] PetPolicy aplicada en PetController
[ ] EnsureClientCanManagePets completo
[ ] Image validation con dimensiones
[ ] IP hashed en Scan model
[ ] Excepciones loguadas, no mostradas
[ ] Security headers configurados
[ ] HTTPS forzado en producción
[ ] Password reset rate limited
[ ] 2FA para admin (opcional pero recomendado)
```

---

**Última actualización:** 15 Noviembre 2025
**Autor:** Security Analysis Tool
