<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'item_name',
        'category',
        'description',
        'quantity',
        'min_quantity',
        'unit',
        'location',
        'supplier',
        'cost_per_unit',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'cost_per_unit' => 'decimal:2',
    ];

    public function index(Event $event)
    {
        $eventInventory = $event->eventInventory()->with('inventory', 'allocatedBy')->get();

        // ✅ Get items with actual available stock
        $availableInventory = Inventory::where('is_active', true)
            ->where('quantity', '>', 0)
            ->get()
            ->filter(function ($item) {
                return $item->available_quantity > 0;
            });

        return view('event_inventory.index', compact('event', 'eventInventory', 'availableInventory'));
    }

    public function eventInventory()
    {
        return $this->hasMany(EventInventory::class);
    }

    /**
     * Get available quantity (total - allocated to active events).
     */
    public function getAvailableQuantityAttribute()
    {
        $allocated = $this->eventInventory()
            ->whereIn('status', ['allocated', 'in_use'])
            ->sum('quantity_allocated');
        
        return $this->quantity - $allocated;
    }

    public function getStockStatusAttribute()
    {
        $available = $this->available_quantity;

        if ($available <= 0) {
            return 'Out of Stock';
        }
        if ($available < $this->min_quantity) {
            return 'Low Stock';
        }
        return 'Sufficient';
    }

    public function getStockStatusBadgeAttribute()
    {
        return match($this->stock_status) {
            'Out of Stock' => 'danger',
            'Low Stock' => 'warning',
            default => 'success',
        };
    }
}