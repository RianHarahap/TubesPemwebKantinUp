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
        'role',
        'balance',
        'vendor_id',
        ];

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}