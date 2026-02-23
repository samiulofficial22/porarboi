<?php

use App\Services\SettingsService;

if (!function_exists('setting')) {
    /**
     * Get or set a setting.
     * 
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key = null, $default = null)
    {
        $service = app(SettingsService::class);

        if (is_null($key)) {
            return $service;
        }

        return $service->get($key, $default);
    }
}
