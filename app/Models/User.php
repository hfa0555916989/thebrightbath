<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'has_book_access',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'password_changed_at',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
        'verification_token',
        'verification_token_expires_at',
        'password_reset_token',
        'password_reset_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_book_access' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'last_login_at' => 'datetime',
            'verification_token_expires_at' => 'datetime',
            'password_reset_expires_at' => 'datetime',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is counselor
     */
    public function isCounselor(): bool
    {
        return $this->role === 'counselor';
    }

    /**
     * Check if user is client
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdmin(): bool
    {
        return in_array($this->role, ['admin', 'counselor']);
    }

    /**
     * Check if user has 2FA enabled
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Get decrypted 2FA secret
     */
    public function getTwoFactorSecret(): ?string
    {
        if (!$this->two_factor_secret) {
            return null;
        }

        try {
            return decrypt($this->two_factor_secret);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get recovery codes
     */
    public function getRecoveryCodes(): array
    {
        if (!$this->two_factor_recovery_codes) {
            return [];
        }

        try {
            return json_decode(decrypt($this->two_factor_recovery_codes), true) ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Update password and invalidate other sessions
     */
    public function updatePassword(string $newPassword): void
    {
        $this->password = Hash::make($newPassword);
        $this->password_changed_at = now();
        $this->setRememberToken(null);
        $this->save();

        // Invalidate all other sessions
        $this->invalidateOtherSessions();
    }

    /**
     * Invalidate all other sessions
     */
    public function invalidateOtherSessions(): void
    {
        // Update session table if using database sessions
        if (config('session.driver') === 'database') {
            DB::table('sessions')
                ->where('user_id', $this->id)
                ->where('id', '!=', session()->getId())
                ->delete();
        }
    }

    /**
     * Record login
     */
    public function recordLogin(): void
    {
        $this->last_login_at = now();
        $this->last_login_ip = request()->ip();
        $this->save();

        // Log activity
        AdminActivityLog::log('login', "User {$this->email} logged in");
    }

    /**
     * Get user's consultant profile
     */
    public function consultant(): HasOne
    {
        return $this->hasOne(Consultant::class);
    }

    /**
     * Get user's assessment attempts
     */
    public function assessmentAttempts(): HasMany
    {
        return $this->hasMany(AssessmentAttempt::class);
    }

    /**
     * Get attempts assigned to this counselor
     */
    public function assignedAttempts(): HasMany
    {
        return $this->hasMany(AssessmentAttempt::class, 'counselor_id');
    }

    /**
     * Get admin activity logs
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(AdminActivityLog::class);
    }

    /**
     * Requires 2FA for admin/counselor roles
     */
    public function requiresTwoFactor(): bool
    {
        return in_array($this->role, ['admin', 'counselor']);
    }
}
