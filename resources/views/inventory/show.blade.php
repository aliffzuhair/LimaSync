@extends('layouts.app')

@section('title', $inventory->item_name . ' - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $inventory->item_name }}</h1>
    <div>
        <a href="{{ route('inventory.edit', $inventory) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Item Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th style="width: 40%;">Item Name</th><td>{{ $inventory->item_name }}</td></tr>
                    <tr><th>Category</th><td>{{ $inventory->category }}</td></tr>
                    <tr><th>Description</th><td>{{ $inventory->description ?? 'N/A' }}</td></tr>
                    <tr><th>Quantity</th><td>{{ $inventory->quantity }} {{ $inventory->unit }}</td></tr>
                    <tr><th>Min Stock</th><td>{{ $inventory->min_quantity }} {{ $inventory->unit }}</td></tr>
                    <tr><th>Available</th><td>{{ $inventory->available_quantity }} {{ $inventory->unit }}</td></tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge bg-{{ $inventory->stock_status_badge }}">{{ $inventory->stock_status }}</span></td>
                    </tr>
                    <tr><th>Location</th><td>{{ $inventory->location ?? 'N/A' }}</td></tr>
                    <tr><th>Supplier</th><td>{{ $inventory->supplier ?? 'N/A' }}</td></tr>
                    <tr><th>Cost Per Unit</th><td>RM {{ number_format($inventory->cost_per_unit ?? 0, 2) }}</td></tr>
                    <tr><th>Notes</th><td>{{ $inventory->notes ?? 'N/A' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Allocated to Events</h5>
            </div>
            <div class="card-body">
                @if($inventory->eventInventory->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Allocated</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventory->eventInventory as $ei)
                                <tr>
                                    <td>
                                        <a href="{{ route('events.show', $ei->event) }}">
                                            {{ $ei->event->event_name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td>{{ $ei->quantity_allocated }} {{ $inventory->unit }}</td>
                                    <td>
                                        <span class="badge bg-{{ $ei->status == 'returned' ? 'success' : ($ei->status == 'in_use' ? 'warning' : 'info') }}">
                                            {{ ucfirst($ei->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Not allocated to any event yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection