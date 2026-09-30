<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventInventory;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ActivityLogger;

class EventInventoryController extends Controller
{
    public function index(Event $event)
    {
        $eventInventory = $event->eventInventory()->with('inventory', 'allocatedBy')->get();

        // ✅ Only items with available stock
        $availableInventory = Inventory::where('is_active', true)
            ->where('quantity', '>', 0)
            ->orderBy('item_name')
            ->get()
            ->filter(function ($item) {
                return $item->available_quantity > 0;
            });

        return view('event_inventory.index', compact('event', 'eventInventory', 'availableInventory'));
    }
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventory,id',
            'quantity_allocated' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($validated['inventory_id']);

        // Check available stock
        $allocated = $inventory->eventInventory()
            ->whereIn('status', ['allocated', 'in_use'])
            ->sum('quantity_allocated');
        $available = $inventory->quantity - $allocated;

        if ($validated['quantity_allocated'] > $available) {
            return redirect()->back()
                ->with('error', "Not enough stock available. Only {$available} {$inventory->unit} available.");
        }

        $eventInventory = EventInventory::create([
            'event_id' => $event->id,
            'inventory_id' => $validated['inventory_id'],
            'quantity_allocated' => $validated['quantity_allocated'],
            'status' => 'allocated',
            'notes' => $validated['notes'],
            'allocated_by' => Auth::id(),
        ]);

        // ✅ Log activity
        ActivityLogger::create(
            'EventInventory',
            $eventInventory->id,
            Auth::user()->full_name . ' allocated ' . $validated['quantity_allocated'] . ' ' . $inventory->unit . ' of "' . $inventory->item_name . '" to event "' . $event->event_name . '"'
        );

        return redirect()->back()
            ->with('success', 'Inventory allocated to event successfully!');
    }

    public function update(Request $request, EventInventory $eventInventory)
    {
        $validated = $request->validate([
            'quantity_used' => 'nullable|integer|min:0',
            'quantity_returned' => 'nullable|integer|min:0',
            'status' => 'required|in:allocated,in_use,returned,lost',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $eventInventory->status;
        $eventInventory->update($validated);

        // ✅ Log activity
        ActivityLogger::update(
            'EventInventory',
            $eventInventory->id,
            Auth::user()->full_name . ' updated allocation of "' . ($eventInventory->inventory->item_name ?? 'N/A') . '" to "' . ($eventInventory->event->event_name ?? 'N/A') . '" (' . $oldStatus . ' → ' . $validated['status'] . ')'
        );

        return redirect()->back()
            ->with('success', 'Event inventory updated successfully!');
    }

    public function destroy(EventInventory $eventInventory)
    {
        $eventInventory->delete();

        return redirect()->back()
            ->with('success', 'Inventory allocation removed successfully!');
    }
}