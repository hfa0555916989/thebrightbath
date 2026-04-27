<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PaymentSetting extends Model
{
    protected $fillable = [
        'gateway',
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
        'is_sandbox' => 'boolean',
        'is_active' => 'boolean',
        'supported_methods' => 'array',
        'extra_settings' => 'array',
    ];

    protected $hidden = [
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
     * Get Paymob settings
     */
    public static function getPaymob(): ?self
    {
        return Cache::remember('payment_settings_paymob', 3600, function () {
            return self::where('gateway', 'paymob')->where('is_active', true)->first();
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('payment_settings_active');
        Cache::forget('payment_settings_paymob');
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
     * Get the Paymob base URL (V2 KSA Intention API)
     */
    public function getPaymobBaseUrl(): string
    {
        return 'https://ksa.paymob.com/v1/intention';
    }

    /**
     * Get the Paymob Checkout URL
     */
    public function getPaymobCheckoutUrl(): string
    {
        return 'https://ksa.paymob.com/unifiedcheckout/';
    }

    /**
     * Check if gateway is configured (V2 requires secret_key)
     */
    public function isConfigured(): bool
    {
        if ($this->gateway === 'paymob') {
            // V2 Intention API requires secret_key
            return !empty($this->secret_key);
        }
        
        return !empty($this->api_key) || !empty($this->secret_key);
    }
}
