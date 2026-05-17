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

/**
 * Generate the correct public URL for a file stored via Storage::disk('public').
 * Because usePublicPath(__DIR__) is used in index.php, the public root IS the
 * app root, so storage/app/public is directly web-accessible at /storage/app/public/.
 */
if (!function_exists('storage_asset')) {
    function storage_asset(?string $path): string
    {
        if (!$path) return '';
        if (str_starts_with($path, 'http')) return $path;
        return asset('storage/app/public/' . ltrim($path, '/'));
    }
}
