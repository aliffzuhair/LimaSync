@extends('layouts.app')

@section('title', 'Event Inventory - ' . $event->event_name . ' - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>{{ $event->event_name }} - Inventory</h1>
        <p class="mb-4">
            <i class="fas fa-users"></i> {{ $event->client->company_name ?? 'N/A' }} &nbsp;|&nbsp;
            <i class="fas fa-calendar"></i> {{ $event->start_date->format('d M Y') }}
        </p>
    </div>
    <div>
        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Event
        </a>
    </div>
</div>

<div class="row">
    <!-- Allocate Inventory Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-plus-circle"></i> Allocate Inventory</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('event.inventory.store', $event) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="inventory_id" class="form-label">Select Item</label>
                        <select class="form-control" id="inventory_id" name="inventory_id" required>
                        <option value="">Choose item...</option>
                            @foreach($availableInventory as $item)
                                <option value="{{ $item->id }}" 
                                        data-available="{{ $item->available_quantity }}"
                                        data-unit="{{ $item->unit }}">
                                    {{ $item->item_name }} 
                                    ({{ $item->available_quantity }} {{ $item->unit }} available)
                                    @if($item->available_quantity < $item->min_quantity)
                                        ⚠️ Low Stock
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @if($availableInventory->isEmpty())
                            <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>No items available!</strong>
                            All inventory items are either out of stock or fully allocated.
                            <a href="{{ route('inventory.index') }}" class="alert-link">Manage inventory</a>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="quantity_allocated" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity_allocated" 
                               name="quantity_allocated" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> Allocate to Event
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Allocated Inventory List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-list"></i> Allocated Items</h5>
            </div>
            <div class="card-body">
                @if($eventInventory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Allocated</th>
                                    <th>Used</th>
                                    <th>Returned</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eventInventory as $ei)
                                <tr>
                                    <td>{{ $ei->inventory->item_name ?? 'N/A' }}</td>
                                    <td>{{ $ei->quantity_allocated }} {{ $ei->inventory->unit ?? '' }}</td>
                                    <td>{{ $ei->quantity_used }}</td>
                                    <td>{{ $ei->quantity_returned }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $ei->status == 'returned' ? 'success' : 
                                            ($ei->status == 'in_use' ? 'warning' : 
                                            ($ei->status == 'lost' ? 'danger' : 'info')) 
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $ei->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('event.inventory.destroy', $ei) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Remove this item?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No items allocated to this event yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const itemSelect = document.getElementById('inventory_id');
                const quantityInput = document.getElementById('quantity_allocated');

                if (itemSelect && quantityInput) {
                    itemSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        const available = parseInt(selectedOption.dataset.available || 0);
                        const unit = selectedOption.dataset.unit || 'unit';

                        // Set max to available quantity
                        quantityInput.max = available;
                        quantityInput.value = available > 0 ? 1 : 0;

                        // Show helper text
                        let helpText = document.getElementById('quantity-help');
                        if (!helpText) {
                            helpText = document.createElement('small');
                            helpText.id = 'quantity-help';
                            helpText.className = 'text-muted';
                            quantityInput.parentNode.appendChild(helpText);
                        }

                        if (available > 0) {
                            helpText.textContent = `Maximum: ${available} ${unit}`;
                            helpText.className = 'text-muted';
                        } else {
                            helpText.textContent = 'No stock available';
                            helpText.className = 'text-danger';
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection