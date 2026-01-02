<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['vendor_id', 'nama_menu', 'harga', 'estimasi_menit'];

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }
}


