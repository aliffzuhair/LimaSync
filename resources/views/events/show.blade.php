@extends('layouts.app')

@section('title', 'Event Details - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>{{ $event->event_name }}</h1>
        <p class="mb-4">
            <i class="fas fa-users"></i> {{ $event->client->company_name ?? 'N/A' }} &nbsp;|&nbsp;
            <i class="fas fa-calendar"></i> {{ $event->start_date->format('d M Y') }}
        </p>
    </div>
    <div>
        {{-- Edit button: Admin only --}}
        @if(auth()->user()->role && auth()->user()->role->name === 'admin')
            <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
        @endif
        <a href="{{ route('events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color: white">Event Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">Event Name</th>
                        <td>{{ $event->event_name }}</td>
                    </tr>
                    <tr>
                        <th>Client</th>
                        <td>
                            <a href="{{ route('clients.show', $event->client) }}">
                                {{ $event->client->company_name ?? 'N/A' }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Event Type</th>
                        <td>{{ $event->event_type }}</td>
                    </tr>
                    <tr>
                        <th>Venue</th>
                        <td>{{ $event->venue ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{{ $event->start_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{{ $event->end_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Start Time</th>
                        <td>{{ $event->start_time ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>End Time</th>
                        <td>{{ $event->end_time ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Estimated Attendees</th>
                        <td>{{ $event->estimated_attendees ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Budget</th>
                        <td>RM {{ number_format($event->budget ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @php
                                $liveProgress = $event->calculateProgress();
                                if ($event->status === 'cancelled') {
                                    $liveStatus = 'cancelled';
                                } elseif ($liveProgress >= 100) {
                                    $liveStatus = 'completed';
                                } elseif ($liveProgress > 0) {
                                    $liveStatus = 'in_progress';
                                } else {
                                    $liveStatus = 'planning';
                                }
                            @endphp
                            <span class="badge bg-{{ 
                                $liveStatus == 'completed' ? 'success' : 
                                ($liveStatus == 'in_progress' ? 'warning' : 
                                ($liveStatus == 'cancelled' ? 'danger' : 'secondary')) 
                            }}" style="font-size: 0.9rem; padding: 6px 12px;">
                                <i class="fas fa-{{ 
                                    $liveStatus == 'completed' ? 'check-circle' : 
                                    ($liveStatus == 'in_progress' ? 'spinner' : 
                                    ($liveStatus == 'cancelled' ? 'ban' : 'clock')) 
                                }}"></i>
                                {{ ucfirst($liveStatus) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Progress</th>
                        <td>
                            @php $liveProgress = $event->calculateProgress(); @endphp
                            <div class="progress" style="width: 120px; height: 20px;">
                                <div class="progress-bar bg-{{ 
                                    $liveProgress == 100 ? 'success' : 
                                    ($liveProgress >= 50 ? 'warning' : 'danger') 
                                }}" 
                                    role="progressbar" 
                                    style="width: {{ $liveProgress }}%">
                                    {{ $liveProgress }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $event->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td>{{ $event->notes ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created By</th>
                        <td>{{ $event->createdBy->full_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $event->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        
<div class="card">
    <div class="card-header">
        <h5 class="mb-0" style="color: white">Quick Actions</h5>
    </div>
    <div class="card-body">
        <div class="d-grid gap-2">
            {{-- Checklist: Admin + Operations --}}
            @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'operations']))
                <a href="{{ route('checklists.index', $event) }}" class="btn btn-outline-primary">
                    <i class="fas fa-check-circle"></i> Manage Checklists
                </a>
            @endif

            {{-- Sustainability: Admin + Operations --}}
            @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'operations']))
                <a href="{{ route('sustainability.index', $event) }}" class="btn btn-outline-info">
                    <i class="fas fa-leaf"></i> Sustainability
                </a>
            @endif

            {{-- Finance: Admin + Finance --}}
            @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'finance']))
                <a href="{{ route('finance.event', $event) }}" class="btn btn-outline-warning">
                    <i class="fas fa-money-bill-wave"></i> Manage Finance
                </a>
            @endif

            {{-- Inventory: Admin + Logistics --}}
            @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'logistics']))
                <a href="{{ route('event.inventory.index', $event) }}" class="btn btn-outline-success">
                    <i class="fas fa-boxes"></i> Manage Inventory
                </a>
            @endif

            {{-- Reports: Admin + Operations --}}
            @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'operations']))
                <a href="{{ route('reports.index', $event) }}" class="btn btn-outline-danger">
                    <i class="fas fa-file-alt"></i> Reports
                </a>
            @endif
        </div>
    </div>
</div>


        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0" style="color: white">Event Summary</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>Phase:</strong> Pre-Event</li>
                    <li><strong>Checklist Items:</strong> 0 / 7</li>
                    <li><strong>Total Income:</strong> RM 0.00</li>
                    <li><strong>Total Expenses:</strong> RM 0.00</li>
                    <li><strong>Net Profit:</strong> RM 0.00</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0" style="color: white">Financial Summary</h5>
    </div>
    <div class="card-body">
        <ul class="list-unstyled">
            <li><strong>Budget:</strong> RM {{ number_format($event->budget ?? 0, 2) }}</li>
            <li><strong>Income:</strong> <span class="text-success">RM {{ number_format($event->total_income, 2) }}</span></li>
            <li><strong>Expenses:</strong> <span class="text-danger">RM {{ number_format($event->total_expense, 2) }}</span></li>
            <li><strong>Net Profit:</strong> 
                <span class="{{ $event->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                    RM {{ number_format($event->net_profit, 2) }}
                </span>
            </li>
        </ul>
        <a href="{{ route('finance.event', $event) }}" class="btn btn-sm btn-primary w-100">
            <i class="fas fa-edit"></i> Manage Finance
        </a>
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0" style="color: white">Sustainability Summary</h5>
    </div>
    <div class="card-body">
        <ul class="list-unstyled">
            <li><i class="fas fa-leaf text-dark"></i> Carbon: {{ number_format($event->total_carbon, 2) }} kg CO₂</li>
            <li><i class="fas fa-bolt text-warning"></i> Electricity: {{ number_format($event->total_electricity, 2) }} kWh</li>
            <li><i class="fas fa-tint text-info"></i> Water: {{ number_format($event->total_water, 2) }} m³</li>
            <li><i class="fas fa-trash text-secondary"></i> Waste: {{ number_format($event->total_waste, 2) }} kg</li>
        </ul>
        <a href="{{ route('sustainability.index', $event) }}" class="btn btn-sm btn-info w-100">
            <i class="fas fa-edit"></i> Manage Sustainability
        </a>
    </div>
</div>

{{-- Inventory Summary (Admin + Logistics) --}}
@if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'logistics']))
<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0" style="color: white">Inventory Summary</h5>
    </div>
    <div class="card-body">
        @if($event->eventInventory->count() > 0)
            <ul class="list-unstyled mb-0">
                @foreach($event->eventInventory->take(5) as $ei)
                <li class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $ei->inventory->item_name ?? 'N/A' }}</span>
                    <span class="badge bg-{{ $ei->status == 'returned' ? 'success' : ($ei->status == 'in_use' ? 'warning' : 'info') }}">
                        {{ $ei->quantity_allocated }} {{ $ei->inventory->unit ?? '' }}
                    </span>
                </li>
                @endforeach
                @if($event->eventInventory->count() > 5)
                    <li class="text-muted small">
                        + {{ $event->eventInventory->count() - 5 }} more items
                    </li>
                @endif
            </ul>
        @else
            <p class="text-muted mb-0">No inventory allocated yet.</p>
        @endif
        <a href="{{ route('event.inventory.index', $event) }}" class="btn btn-sm btn-success w-100 mt-2">
            <i class="fas fa-edit"></i> Manage Inventory
        </a>
    </div>
</div>
@endif

<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0" style="color: white">Checklist Summary</h5>
    </div>
    <div class="card-body">
        @php
            $total = $event->eventChecklists->count();
            $completed = $event->eventChecklists->where('status', 'completed')->count();
            $inProgress = $event->eventChecklists->where('status', 'in_progress')->count();
            $pending = $event->eventChecklists->where('status', 'pending')->count();
        @endphp
        <ul class="list-unstyled">
            <li>
                <strong>Progress:</strong>
                <div class="progress mt-1" style="height: 20px;">
                    <div class="progress-bar" role="progressbar" 
                         style="width: {{ $event->progress_percentage }}%">
                        {{ $event->progress_percentage }}%
                    </div>
                </div>
            </li>
            <li class="mt-2">
                <strong>Items:</strong>
                <span class="badge bg-success">{{ $completed }} Completed</span>
                <span class="badge bg-warning">{{ $inProgress }} In Progress</span>
                <span class="badge bg-secondary">{{ $pending }} Pending</span>
            </li>
            <li>
                <strong>Total:</strong> {{ $total }} items
            </li>
        </ul>
        <a href="{{ route('checklists.index', $event) }}" class="btn btn-sm btn-primary w-100">
            <i class="fas fa-edit"></i> Manage Checklists
        </a>
    </div>
</div>
@endsection