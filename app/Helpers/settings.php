<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Obtener un setting por su clave
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('setting_set')) {
    /**
     * Establecer un setting
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    function setting_set(string $key, $value): void
    {
        Setting::set($key, $value);
    }
}

if (!function_exists('theme_color')) {
    /**
     * Obtener un color del tema
     *
     * @param string $type (primary, secondary, success, danger, warning, info)
     * @param string $default
     * @return string
     */
    function theme_color(string $type, string $default = '#3b82f6'): string
    {
        return setting("theme_{$type}_color", $default);
    }
}
