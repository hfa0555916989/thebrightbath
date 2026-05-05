<?php

use App\Models\SiteSetting;

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = ''): mixed
    {
        try {
            return SiteSetting::get($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}
