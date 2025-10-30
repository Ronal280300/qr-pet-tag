<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'order',
    ];

    /**
     * Boot del modelo - limpiar cache cuando se actualiza
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('settings.all');
        });

        static::deleted(function () {
            Cache::forget('settings.all');
        });
    }

    /**
     * Obtener un setting por su clave
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Establecer un setting
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general'): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
                'group' => $group,
            ]
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }

    /**
     * Castear el valor según su tipo
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'integer' => (int) $value,
            'boolean' => (bool) $value,
            'json' => json_decode($value, true),
            'array' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Obtener todos los settings de un grupo
     */
    public static function getGroup(string $group): array
    {
        return self::where('group', $group)
            ->get()
            ->mapWithKeys(function ($setting) {
                return [$setting->key => self::castValue($setting->value, $setting->type)];
            })
            ->toArray();
    }

    /**
     * Limpiar cache de settings
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }

    /**
     * Obtener settings por grupo ordenados
     */
    public static function getByGroup(string $group)
    {
        return self::where('group', $group)->orderBy('order')->orderBy('label')->get();
    }

    /**
     * Scope por grupo
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }
}
