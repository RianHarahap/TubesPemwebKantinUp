<?php

namespace App\Models;

// use Laravel\Sanctum\HasApiTokens; <--- Hapus atau komentari baris ini
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // Cukup gunakan Notifiable saja, hapus HasApiTokens
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // PASTIKAN ADA INI
        ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}