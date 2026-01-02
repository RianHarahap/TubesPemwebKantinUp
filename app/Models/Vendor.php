<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['nama_vendor', 'kategori'];

    public function menus() {
        return $this->hasMany(Menu::class);
    }
}

