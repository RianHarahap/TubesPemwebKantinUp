<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent'
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper method untuk mencatat aktivitas
     */
    public static function log($action, $description, $userId = null)
    {
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Scope untuk filter berdasarkan action
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope untuk aktivitas terbaru
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->take($limit);
    }

    /**
     * Badge class untuk UI
     */
    public function getBadgeClass()
    {
        return match($this->action) {
            'login' => 'badge-success',
            'logout' => 'badge-secondary',
            'create_vendor', 'create_menu', 'create_order' => 'badge-primary',
            'update_vendor', 'update_menu', 'update_order' => 'badge-info',
            'delete_vendor', 'delete_menu', 'delete_order' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    /**
     * Icon untuk UI
     */
    public function getIcon()
    {
        return match($this->action) {
            'login' => '🔓',
            'logout' => '🔒',
            'create_vendor', 'create_menu', 'create_order' => '➕',
            'update_vendor', 'update_menu', 'update_order' => '✏️',
            'delete_vendor', 'delete_menu', 'delete_order' => '🗑️',
            'toggle_status' => '🔄',
            default => '📝'
        };
    }
}
