<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'vendor_id', 'menu_id', 'menu_name', 'jumlah', 'nomor_antrean', 'total_harga', 'status', 'estimasi_menit', 'nama_pembeli'];

    public function menu() {
        return $this->belongsTo(Menu::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }

    // Helper untuk badge warna status
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'menunggu' => 'badge-warning',
            'dimasak' => 'badge-info',
            'siap' => 'badge-success',
            'selesai' => 'badge-primary',
            'dibatalkan' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function getStatusLabel()
    {
        return match($this->status) {
            'menunggu' => 'Menunggu',
            'dimasak' => 'Sedang Dimasak',
            'siap' => 'Siap Diambil',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status)
        };
    }
}


