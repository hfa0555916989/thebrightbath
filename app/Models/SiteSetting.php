<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['group', 'key', 'value', 'type', 'label', 'description'];

    const CACHE_KEY = 'site_settings_all';
    const CACHE_TTL = 3600;

    public static function get(string $key, $default = null): mixed
    {
        $settings = static::getAllCached();
        return $settings[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::clearCache();
    }

    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        static::clearCache();
    }

    public static function getAllCached(): array
    {
        return Cache::remember(static::CACHE_KEY, static::CACHE_TTL, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    public static function getGroup(string $group): array
    {
        return static::where('group', $group)->pluck('value', 'key')->toArray();
    }

    public static function clearCache(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
