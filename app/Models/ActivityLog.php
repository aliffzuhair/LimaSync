<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action badge color.
     */
    public function getActionBadgeAttribute()
    {
        return match($this->action) {
            'login' => 'success',
            'logout' => 'secondary',
            'register' => 'primary',
            'create' => 'info',
            'update' => 'warning',
            'delete' => 'danger',
            'view' => 'info',
            'download' => 'primary',
            'approve' => 'success',    // <-- ADD
            'reject' => 'danger',      // <-- ADD
            default => 'dark',
        };
    }

    public function getActionIconAttribute()
    {
        return match($this->action) {
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'register' => 'fa-user-plus',
            'create' => 'fa-plus-circle',
            'update' => 'fa-edit',
            'delete' => 'fa-trash',
            'view' => 'fa-eye',
            'download' => 'fa-download',
            'approve' => 'fa-check-circle',   // <-- ADD
            'reject' => 'fa-times-circle',    // <-- ADD
            default => 'fa-circle',
        };
    }
}