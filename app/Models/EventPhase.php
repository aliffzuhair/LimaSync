<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventPhase extends Model
{
    use HasFactory;

    protected $table = 'event_phases';

    protected $fillable = [
        'name',
        'description',
        'sort_order',
    ];

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }
}