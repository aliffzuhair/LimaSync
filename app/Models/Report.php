<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'event_id',
        'report_type',
        'report_title',
        'file_path',
        'generated_by',
        'generated_at',
        'client_viewed',
        'client_viewed_at',
        'notes',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'client_viewed' => 'boolean',
        'client_viewed_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Get report type badge color.
     */
    public function getTypeBadgeAttribute()
    {
        return match($this->report_type) {
            'event_summary' => 'primary',
            'financial' => 'success',
            'sustainability' => 'info',
            'iso' => 'warning',
            'final' => 'dark',
            default => 'secondary',
        };
    }

    /**
     * Get report type label.
     */
    public function getTypeLabelAttribute()
    {
        return match($this->report_type) {
            'event_summary' => 'Event Summary',
            'financial' => 'Financial Report',
            'sustainability' => 'Sustainability Report',
            'iso' => 'ISO Compliance Report',
            'final' => 'Final Event Report',
            default => 'Report',
        };
    }

    /**
     * Check if report file exists.
     */
    public function getFileExistsAttribute()
    {
        return $this->file_path && file_exists(storage_path('app/public/' . $this->file_path));
    }
}