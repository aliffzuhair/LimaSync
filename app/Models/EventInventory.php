<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventInventory extends Model
{
    use HasFactory;

    protected $table = 'event_inventory';

    protected $fillable = [
        'event_id',
        'inventory_id',
        'quantity_allocated',
        'quantity_used',
        'quantity_returned',
        'status',
        'notes',
        'allocated_by',
    ];

    protected $casts = [
        'quantity_allocated' => 'integer',
        'quantity_used' => 'integer',
        'quantity_returned' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function allocatedBy()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }
}