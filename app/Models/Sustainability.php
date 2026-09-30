<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sustainability extends Model
{
    use HasFactory;

    protected $table = 'sustainability';

    protected $fillable = [
        'event_id',
        'metric_type',
        'metric_name',
        'value',
        'unit',
        'measurement_date',
        'source',
        'recorded_by',
        'notes',
        'is_verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'measurement_date' => 'date',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get metric type badge color.
     */
    public function getTypeBadgeAttribute()
    {
        return match($this->metric_type) {
            'carbon' => 'dark',
            'electricity' => 'warning',
            'water' => 'info',
            'waste' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get metric icon.
     */
    public function getIconAttribute()
    {
        return match($this->metric_type) {
            'carbon' => 'fa-leaf',
            'electricity' => 'fa-bolt',
            'water' => 'fa-tint',
            'waste' => 'fa-trash',
            default => 'fa-chart-bar',
        };
    }
}