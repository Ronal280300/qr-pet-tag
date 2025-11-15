# RECOMENDACIONES DE FIXES INMEDIATOS

## CRÍTICOS - IMPLEMENTAR YA

### 1. WhatsAppService: Null checks en acceso a relaciones
**Archivo:** `/home/user/qr-pet-tag/app/Services/WhatsAppService.php`
**Líneas afectadas:** 154-160, 169, 178-179, 189, 197-199, 209, 230, 261, 280

**Fix 1 - En sendPaymentUploadedToAdmin():**
```php
public function sendPaymentUploadedToAdmin(Order $order): bool
{
    if (!$order->user || !$order->plan) {
        Log::warning('Order missing user or plan', ['order_id' => $order->id]);
        return false;
    }
    
    $adminPhone = $this->setting('twilio_admin_phone') ?: config('services.twilio.admin_phone');
    if (!$adminPhone) {
        return false;
    }

    $message = "🔔 *Nuevo Comprobante*\n\n"
        . "Orden: #{$order->order_number}\n"
        . "Cliente: {$order->user->name}\n"
        . "Plan: {$order->plan->name}\n"
        . "Monto: ₡" . number_format($order->total, 0, ',', '.') . "\n\n"
        . "Revisar en el panel de administración.";

    return $this->send($adminPhone, $message, 'admin_payment_notification', $order->id, $order->user_id);
}
```

**Fix 2 - En todos los métodos con $order->user o $order->plan:**
Agregar al inicio del método:
```php
if (!$order->user || !$order->plan) {
    Log::warning('Order missing relation', ['order_id' => $order->id, 'method' => __METHOD__]);
    return false;
}
```

---

### 2. PetShareCardService: Memory limits y validación de archivos
**Archivo:** `/home/user/qr-pet-tag/app/Services/PetShareCardService.php`
**Líneas afectadas:** 28, 46-48, 86, 88

**Fix 1 - Validar memoria antes de crear imagen:**
```php
public function generate(Pet $pet): string
{
    // Verificar memoria disponible
    $memoryLimit = ini_get('memory_limit');
    $maxMemory = 256 * 1024 * 1024; // 256MB
    if ($memoryLimit === '-1' || (int)$memoryLimit > $maxMemory) {
        throw new \RuntimeException('Memoria insuficiente para generar tarjeta');
    }

    $m = new ImageManager(new Driver());
    
    // ... resto del código
```

**Fix 2 - Validar tamaño de foto:**
```php
private function mainPhotoAbsolute(Pet $pet): ?string
{
    if ($pet->photo && Storage::disk('public')->exists($pet->photo)) {
        $size = Storage::disk('public')->size($pet->photo);
        // Máximo 5MB
        if ($size > 5 * 1024 * 1024) {
            Log::warning('Photo too large', ['pet_id' => $pet->id, 'size' => $size]);
            return null;
        }
        return Storage::disk('public')->path($pet->photo);
    }
    
    // ... resto del código
```

**Fix 3 - Usar Storage::put() en lugar de save():**
```php
// EN LUGAR DE:
// $img->save(Storage::disk('public')->path($file));

// HACER:
$stream = $img->toStream('png');
Storage::disk('public')->put($file, $stream);
```

---

### 3. WhatsAppService: Agregar retry logic
**Archivo:** `/home/user/qr-pet-tag/app/Services/WhatsAppService.php`
**Líneas afectadas:** 61-67

**Fix:**
```php
private function sendWithRetry(string $to, string $message, array $options = []): ?object
{
    $maxRetries = 3;
    $delayMs = 1000;
    
    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        try {
            $response = $this->client->messages->create(
                "whatsapp:{$to}",
                array_merge([
                    'from' => "whatsapp:{$this->from}",
                    'body' => $message
                ], $options)
            );
            
            return $response;
        } catch (\Exception $e) {
            if ($attempt === $maxRetries) {
                throw $e;
            }
            
            $delay = $delayMs * pow(2, $attempt - 1); // exponential backoff
            usleep($delay * 1000);
        }
    }
}

// Reemplazar línea 61-67 con:
try {
    $response = $this->sendWithRetry($to, $message);
```

---

### 4. PetQrService: Fix race condition en slug
**Archivo:** `/home/user/qr-pet-tag/app/Services/PetQrService.php`
**Líneas afectadas:** 17-58

**Fix 1 - Verificación de unicidad:**
```php
public function ensureSlugAndImage(QrCodeModel $qr, Pet $pet): void
{
    // ... código previo
    
    // Slug único (si falta)
    if (blank($qr->slug)) {
        $slug = null;
        $attempts = 0;
        
        do {
            $slug = Str::slug($pet->name . '-' . $pet->id . '-' . Str::lower(Str::random(6)));
            $attempts++;
            
            if ($attempts > 10) {
                throw new \RuntimeException('No se pudo generar slug único después de 10 intentos');
            }
        } while (QrCodeModel::where('slug', $slug)->exists());
        
        $qr->slug = $slug;
    }
    
    // ... resto del código
```

**Fix 2 - Una sola operación de save():**
```php
public function ensureSlugAndImage(QrCodeModel $qr, Pet $pet): void
{
    // ... todos los cambios aquí
    
    // Una única operación de save al final
    $qr->save();
    
    // Luego generar imagen
    $url = url('/p/' . $qr->slug);
    $filename = 'qrcodes/' . $qr->slug . '.svg';
    
    try {
        $svg = QrCode::format('svg')
            ->size(512)
            ->margin(1)
            ->generate($url);
        
        if (empty($svg)) {
            throw new \RuntimeException('QR generation returned empty content');
        }
        
        Storage::disk('public')->put($filename, $svg);
        
        // Actualizar imagen en segundo save (si cambió)
        if ($qr->image !== $filename) {
            $qr->update(['image' => $filename]);
        }
    } catch (\Exception $e) {
        Log::error('QR generation failed', [
            'pet_id' => $pet->id,
            'qr_id' => $qr->id,
            'error' => $e->getMessage()
        ]);
        throw $e;
    }
}
```

---

### 5. FacebookPoster: Agregar retry logic
**Archivo:** `/home/user/qr-pet-tag/app/Services/FacebookPoster.php`
**Líneas afectadas:** 39-44, 63-70

**Fix:**
```php
private function postWithRetry(string $method, string $endpoint, array $payload, $stream = null): array
{
    $maxRetries = 3;
    $delayMs = 1000;
    
    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        try {
            if ($method === 'form') {
                $res = Http::timeout(30)
                    ->retry(3, 100)
                    ->asForm()
                    ->post($endpoint, $payload);
            } else if ($method === 'multipart') {
                $res = Http::timeout(60)
                    ->retry(3, 100)
                    ->asMultipart()
                    ->attach('source', $stream, basename($payload['file']), 
                        ['Content-Type' => $payload['mime']])
                    ->post($endpoint, $payload['data']);
            }
            
            if ($res->failed()) {
                throw new RequestException($res);
            }
            
            return $res->json();
        } catch (\Exception $e) {
            if ($attempt === $maxRetries) {
                throw $e;
            }
            
            $delay = $delayMs * pow(2, $attempt - 1);
            usleep($delay * 1000);
        }
    }
}
```

---

### 6. FacebookPoster: Validación de configuración
**Archivo:** `/home/user/qr-pet-tag/app/Services/FacebookPoster.php`
**Líneas afectadas:** 14-19

**Fix:**
```php
public function __construct()
{
    $this->token   = (string) config('services.facebook.page_access_token');
    $this->version = (string) config('services.facebook.version', 'v23.0');
    $this->pageId  = (string) config('services.facebook.page_id');
    
    if (empty($this->token) || empty($this->pageId)) {
        Log::warning('Facebook credentials not configured', [
            'token_empty' => empty($this->token),
            'pageId_empty' => empty($this->pageId)
        ]);
    }
}
```

---

## ALTOS - IMPLEMENTAR EN PRÓXIMO SPRINT

### 7. PetShareCardService: Usar sStream en lugar de path
**Recomendación:** Refactorizar el método `generate()` para usar streams internamente en lugar de rutas absolutas

---

### 8. Setting Model: Mejorar json_decode
**Archivo:** `/home/user/qr-pet-tag/app/Models/Setting.php`
**Línea 82:**

**Fix:**
```php
protected static function castValue($value, string $type)
{
    return match ($type) {
        'integer' => (int) $value,
        'boolean' => (bool) $value,
        'json', 'array' => json_decode($value, true, 512, JSON_THROW_ON_ERROR),
        default => $value,
    };
}
```

---

### 9. Setting Model: Mejorar clearCache
**Archivo:** `/home/user/qr-pet-tag/app/Models/Setting.php`
**Línea 106:**

**Fix:**
```php
public static function clearCache(): void
{
    Cache::forget('settings.all');
    // Limpiar también los cachés individuales
    // Esto es más seguro que Cache::flush()
}
```

---

## BAJO IMPACTO - TECHNICAL DEBT

### 10. FacebookPoster: Remover @ error suppression
**Líneas 58, 72-74**
- Usar proper exception handling en lugar de `@`

### 11. PetShareCardService: Remover paths Windows
**Línea 184**
- Usar solo paths portables (Linux/Mac compatible)

### 12. PetShareCardService: Validación de encoding
**Línea 59**
- Validar encoding de `$pet->name` antes de usar mb_strtoupper

---

## CHECKLIST DE IMPLEMENTACIÓN

- [ ] Fix 1: WhatsAppService null checks (CRÍTICO - 1-2 horas)
- [ ] Fix 2: PetShareCardService memory limits (CRÍTICO - 2 horas)
- [ ] Fix 3: PetShareCardService Storage::put (CRÍTICO - 1 hora)
- [ ] Fix 4: WhatsAppService retry logic (CRÍTICO - 2 horas)
- [ ] Fix 5: PetQrService slug uniqueness (CRÍTICO - 2 horas)
- [ ] Fix 6: FacebookPoster retry logic (CRÍTICO - 2 horas)
- [ ] Fix 7: FacebookPoster config validation (CRÍTICO - 1 hora)
- [ ] Fix 8: Setting Model json_decode (ALTO - 30 min)
- [ ] Fix 9: Setting Model clearCache (ALTO - 30 min)
- [ ] Fixes 10-12: Technical debt (BAJO - próximo sprint)

**Tiempo total estimado:** 12-14 horas

