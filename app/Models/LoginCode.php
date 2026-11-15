<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A one-time sign-in code sent by email. Only the hash is stored.
 */
class LoginCode extends Model
{
    protected $fillable = [
        'email',
        'code_hash',
        'attempts',
        'ip_address',
        'expires_at',
        'consumed_at',
    ];

    protected $hidden = [
        'code_hash',
    ];

    protected function casts(): array
    {
        return [
            'attempts' => 'integer',
            'expires_at' => 'datetime',
            'consumed_at' => 'datetime',
        ];
    }
}
