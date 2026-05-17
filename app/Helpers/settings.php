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
 * Generate the correct public URL for an uploaded file.
 * Files are stored in /uploads/ in the web root (public_path('uploads/')).
 * Supports legacy storage/app/public/ paths and external http URLs.
 */
if (!function_exists('storage_asset')) {
    function storage_asset(?string $path): string
    {
        if (!$path) return '';
        if (str_starts_with($path, 'http')) return $path;
        // Paths stored in uploads/ folder (web-accessible in public root)
        if (str_starts_with($path, 'uploads/')) {
            return asset($path);
        }
        // All other local paths → assume uploads/ prefix
        return asset('uploads/' . ltrim($path, '/'));
    }
}

/**
 * Store an uploaded file in public_path('uploads/{folder}') and return the relative path.
 * Returns a path like "uploads/chapters/filename.jpg".
 */
if (!function_exists('store_upload')) {
    function store_upload(\Illuminate\Http\UploadedFile $file, string $folder): string
    {
        $dir = public_path('uploads/' . $folder);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($dir, $filename);
        return 'uploads/' . $folder . '/' . $filename;
    }
}

/**
 * Delete an uploaded file from public_path if it's a local uploads/ path.
 */
if (!function_exists('delete_upload')) {
    function delete_upload(?string $path): void
    {
        if (!$path || str_starts_with($path, 'http')) return;
        $fullPath = public_path(ltrim($path, '/'));
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
