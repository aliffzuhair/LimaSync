@extends('layouts.app')

@section('title', 'Logistics Dashboard - LimaSync')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Logistics Dashboard</h1>
        <p class="mb-4">Welcome back, {{ $user->full_name ?? $user->name }}!</p>
    </div>
</div>

<!-- Inventory Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-boxes"></i> Total Items</h5>
                <h2 class="card-text">{{ $totalItems }}</h2>
                <small>Across all inventory</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Low Stock</h5>
                <h2 class="card-text">{{ $lowStockCount }}</h2>
                <small>Items below minimum</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-times-circle"></i> Out of Stock</h5>
                <h2 class="card-text">{{ $outOfStockCount }}</h2>
                <small>Need restocking</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-tags"></i> Categories</h5>
                <h2 class="card-text">{{ $totalCategories }}</h2>
                <small>Different item types</small>
            </div>
        </div>
    </div>
</div>

<!-- Events List with Inventory Summary -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="color: white"><i class="fas fa-calendar"></i> Events - Manage Inventory</h5>
        <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-light">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    <div class="card-body">
        @if($events->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Items Allocated</th>
                            <th>Total Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr onclick="window.location='{{ route('event.inventory.index', $event) }}'" style="cursor: pointer;">
                            <td><strong>{{ $event->event_name }}</strong></td>
                            <td>{{ $event->client->company_name ?? 'N/A' }}</td>
                            <td>{{ $event->start_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $event->eventInventory->count() }} item{{ $event->eventInventory->count() != 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td>
                                {{ $event->eventInventory->sum('quantity_allocated') }}
                            </td>
                            <td>
                                <a href="{{ route('event.inventory.index', $event) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-boxes"></i> Manage
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center mb-0">No events found.</p>
        @endif
    </div>
</div>

<!-- Recent Inventory Allocations -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0" style="color: white"><i class="fas fa-history"></i> Recent Allocations</h5>
    </div>
    <div class="card-body">
        @if($recentAllocations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Event</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Allocated By</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAllocations as $allocation)
                        <tr>
                            <td>{{ $allocation->inventory->item_name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('event.inventory.index', $allocation->event) }}">
                                    {{ $allocation->event->event_name ?? 'N/A' }}
                                </a>
                            </td>
                            <td>
                                {{ $allocation->quantity_allocated }} 
                                {{ $allocation->inventory->unit ?? '' }}
                            </td>
                            <td>
                                <span class="badge bg-{{ 
                                    $allocation->status == 'returned' ? 'success' : 
                                    ($allocation->status == 'in_use' ? 'warning' : 
                                    ($allocation->status == 'lost' ? 'danger' : 'info')) 
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $allocation->status)) }}
                                </span>
                            </td>
                            <td>{{ $allocation->allocatedBy->full_name ?? 'N/A' }}</td>
                            <td>{{ $allocation->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center mb-0">No allocations yet.</p>
        @endif
    </div>
</div>
@endsection