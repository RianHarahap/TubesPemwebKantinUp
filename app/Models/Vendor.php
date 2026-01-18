<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['nama_kantin', 'deskripsi', 'is_open'];

    public function menus() {
        return $this->hasMany(Menu::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function user() {
        return $this->hasOne(User::class, 'vendor_id');
    }
}

