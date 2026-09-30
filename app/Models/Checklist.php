<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    use HasFactory;

    protected $table = 'checklists';

    protected $fillable = [
        'phase_id',
        'item_name',
        'description',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
    ];

    public function phase()
    {
        return $this->belongsTo(EventPhase::class, 'phase_id');
    }

    public function eventChecklists()
    {
        return $this->hasMany(EventChecklist::class);
    }
}