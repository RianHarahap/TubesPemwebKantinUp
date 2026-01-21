<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'order_group_id',
        'vendor_id', 
        'menu_id', 
        'menu_name', 
        'jumlah', 
        'nomor_antrean', 
        'total_harga', 
        'harga_satuan', 
        'status', 
        'estimasi_menit', 
        'nama_pembeli',
        'payment_status',
        'qris_code',
        'payment_expired_at'
    ];

    protected $casts = [
        'payment_expired_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_group_id', 'order_group_id');
    }

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

    public function getPaymentStatusBadgeClass()
    {
        return match($this->payment_status) {
            'pending' => 'badge-warning',
            'paid' => 'badge-success',
            'expired' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function getPaymentStatusLabel()
    {
        return match($this->payment_status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'expired' => 'Kadaluarsa',
            default => ucfirst($this->payment_status)
        };
    }
}


