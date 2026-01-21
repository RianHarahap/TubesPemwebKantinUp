<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'order_group_id', 
        'total_amount', 
        'payment_status', 
        'qris_code', 
        'expired_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];
}
