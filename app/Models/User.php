<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; // ✅ Add this line

class User extends Authenticatable implements JWTSubject // ✅ Implement JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ Required by JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // usually 'id'
    }

    // ✅ Required by JWTSubject
    public function getJWTCustomClaims()
    {
        return [];
    }
}
