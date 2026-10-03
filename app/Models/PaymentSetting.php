<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PaymentSetting extends Model
{
    protected $fillable = [
        'gateway',
        'tranportal_id',
        'tranportal_password',
        'resource_key',
        'endpoint_url',
        'api_key',
        'secret_key',
        'public_key',
        'integration_id',
        'iframe_id',
        'hmac_secret',
        'merchant_id',
        'currency',
        'is_sandbox',
        'is_active',
        'supported_methods',
        'extra_settings',
    ];

    protected $casts = [
        'tranportal_password' => 'encrypted',
        'resource_key' => 'encrypted',
        'is_sandbox' => 'boolean',
        'is_active' => 'boolean',
        'supported_methods' => 'array',
        'extra_settings' => 'array',
    ];

    protected $hidden = [
        'tranportal_password',
        'resource_key',
        'api_key',
        'secret_key',
        'public_key',
        'hmac_secret',
    ];

    /**
     * Get active payment settings (cached)
     */
    public static function getActive(): ?self
    {
        return Cache::remember('payment_settings_active', 3600, function () {
            return self::where('is_active', true)->first();
        });
    }

    /**
     * Get Neoleap (Al Rajhi payment gateway) settings
     */
    public static function getNeoleap(): ?self
    {
        return Cache::remember('payment_settings_neoleap', 3600, function () {
            return self::where('gateway', 'neoleap')->where('is_active', true)->first();
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('payment_settings_active');
        Cache::forget('payment_settings_neoleap');
    }

    /**
     * Boot method to clear cache on changes
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    /**
     * Check if the gateway has everything needed to take payments
     */
    public function isConfigured(): bool
    {
        if ($this->gateway === 'neoleap') {
            return filled($this->tranportal_id)
                && filled($this->tranportal_password)
                && filled($this->resource_key)
                && filled($this->endpoint_url);
        }

        return !empty($this->api_key) || !empty($this->secret_key);
    }
}
