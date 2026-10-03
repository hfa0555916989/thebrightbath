<?php

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
 * Uploaded files live on the "uploads" disk (config/filesystems.php): a folder in the
 * web root locally, a public bucket on Laravel Cloud. The database keeps paths like
 * "uploads/chapters/abc.jpg"; the disk key is the part after "uploads/".
 */
if (!function_exists('upload_key')) {
    function upload_key(string $path): string
    {
        return Str::after(ltrim($path, '/'), 'uploads/');
    }
}

/**
 * Public URL of an uploaded file (or an external http URL as-is).
 */
if (!function_exists('storage_asset')) {
    function storage_asset(?string $path): string
    {
        if (!$path) return '';
        if (str_starts_with($path, 'http')) return $path;

        return Storage::disk('uploads')->url(upload_key($path));
    }
}

/**
 * Store an uploaded file under {folder} on the uploads disk and return its path,
 * like "uploads/chapters/filename.jpg". The extension comes from the file's real
 * content, never from the client-supplied name.
 */
if (!function_exists('store_upload')) {
    function store_upload(\Illuminate\Http\UploadedFile $file, string $folder): string
    {
        // Public folder: never accept anything a web server could execute or render as script (svg).
        $extension = strtolower((string) $file->extension());
        abort_unless(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'ico', 'pdf'], true), 422, 'نوع الملف غير مسموح');

        $filename = Str::random(40).'.'.$extension;
        Storage::disk('uploads')->putFileAs($folder, $file, $filename, 'public');

        return 'uploads/'.$folder.'/'.$filename;
    }
}

/**
 * Delete an uploaded file (external http URLs are ignored).
 */
if (!function_exists('delete_upload')) {
    function delete_upload(?string $path): void
    {
        if (!$path || str_starts_with($path, 'http')) return;

        Storage::disk('uploads')->delete(upload_key($path));
    }
}

/**
 * Site-wide image set from the admin panel (logo, favicon...), falling back to the
 * default file shipped in public/.
 */
if (!function_exists('site_image')) {
    function site_image(string $key, string $default): string
    {
        $path = setting($key);

        return $path ? storage_asset($path) : asset($default);
    }
}
