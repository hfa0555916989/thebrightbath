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
        'password_changed_at',
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
                'password_changed_at' => 'datetime',
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
        return $this->role === 'admin';
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

}
