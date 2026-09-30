<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventChecklist extends Model
{
    use HasFactory;

    protected $table = 'event_checklists';

    protected $fillable = [
        'event_id',
        'checklist_id',
        'status',
        'completed_by',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}