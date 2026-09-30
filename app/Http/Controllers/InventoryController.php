<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::query();

        // ✅ Search by item name, category, or supplier
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('supplier', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // ✅ Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // ✅ Filter by stock status (uses accessor logic)
        if ($request->filled('status')) {
            $status = $request->status;

            // ✅ Use the same logic as the accessor: available = quantity - allocated
            if ($status === 'low_stock') {
                $query->whereRaw('(quantity - COALESCE((SELECT SUM(quantity_allocated) FROM event_inventory WHERE event_inventory.inventory_id = inventory.id AND status IN ("allocated", "in_use")), 0)) < min_quantity')
                    ->whereRaw('(quantity - COALESCE((SELECT SUM(quantity_allocated) FROM event_inventory WHERE event_inventory.inventory_id = inventory.id AND status IN ("allocated", "in_use")), 0)) > 0');
            } elseif ($status === 'out_of_stock') {
                $query->whereRaw('(quantity - COALESCE((SELECT SUM(quantity_allocated) FROM event_inventory WHERE event_inventory.inventory_id = inventory.id AND status IN ("allocated", "in_use")), 0)) <= 0');
            } elseif ($status === 'sufficient') {
                $query->whereRaw('(quantity - COALESCE((SELECT SUM(quantity_allocated) FROM event_inventory WHERE event_inventory.inventory_id = inventory.id AND status IN ("allocated", "in_use")), 0)) >= min_quantity');
            }
        }

        // ✅ Filter by active/inactive
        if ($request->filled('active')) {
            $query->where('is_active', $request->active === 'yes');
        }

        $inventory = $query->latest()->paginate(15)->appends($request->query());

        // Get all unique categories for the filter dropdown
        $categories = Inventory::distinct()->pluck('category')->filter()->sort();

        // Calculate stats
        $allItems = Inventory::where('is_active', true)->get();
        $totalItems = $allItems->sum('quantity');
        $sufficientCount = $allItems->filter(fn($i) => $i->stock_status === 'Sufficient')->count();
        $lowStockCount = $allItems->filter(fn($i) => $i->stock_status === 'Low Stock')->count();
        $outOfStockCount = $allItems->filter(fn($i) => $i->stock_status === 'Out of Stock')->count();

        return view('inventory.index', compact(
            'inventory',
            'categories',
            'totalItems',
            'sufficientCount',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'location' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:100',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = true;

        $inventory = Inventory::create($validated);

        // ✅ Log activity
        ActivityLogger::create(
            'Inventory',
            $inventory->id,
            Auth::user()->full_name . ' added inventory item: "' . $inventory->item_name . '" (' . $inventory->quantity . ' ' . $inventory->unit . ')'
        );

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item added successfully!');
    }

    public function show(Inventory $inventory)
    {
        $inventory->load('eventInventory.event');
        return view('inventory.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'location' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:100',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $inventory->update($validated);

        // ✅ Log activity
        ActivityLogger::update(
            'Inventory',
            $inventory->id,
            Auth::user()->full_name . ' updated inventory item: "' . $inventory->item_name . '"'
        );

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item updated successfully!');
}

    public function destroy(Inventory $inventory)
    {
        if ($inventory->eventInventory()->count() > 0) {
            return redirect()->route('inventory.index')
                ->with('error', 'Cannot delete item because it is allocated to events.');
        }

        $itemName = $inventory->item_name;
        $inventoryId = $inventory->id;

        $inventory->delete();

        // ✅ Log activity
        ActivityLogger::delete(
            'Inventory',
            $inventoryId,
            Auth::user()->full_name . ' deleted inventory item: "' . $itemName . '"'
        );

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully!');
}
}