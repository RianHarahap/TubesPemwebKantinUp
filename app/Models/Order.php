<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['nama_pembeli', 'menu_id', 'jumlah', 'status'];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }
}

