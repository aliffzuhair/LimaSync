@extends('layouts.app')

@section('title', 'Sustainability - ' . $event->event_name . ' - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>{{ $event->event_name }} - Sustainability</h1>
        <p class="mb-4">
            <i class="fas fa-users"></i> {{ $event->client->company_name ?? 'N/A' }} &nbsp;|&nbsp;
            <i class="fas fa-calendar"></i> {{ $event->start_date->format('d M Y') }}
        </p>
    </div>
    <div>
        <a href="{{ route('sustainability.create', $event) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Data
        </a>
        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Event
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-dark">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-leaf"></i> Carbon</h5>
                <h2 class="card-text">{{ number_format($summary['carbon'], 2) }}</h2>
                <small>kg CO₂</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-bolt"></i> Electricity</h5>
                <h2 class="card-text">{{ number_format($summary['electricity'], 2) }}</h2>
                <small>kWh</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-tint"></i> Water</h5>
                <h2 class="card-text">{{ number_format($summary['water'], 2) }}</h2>
                <small>m³</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-trash"></i> Waste</h5>
                <h2 class="card-text">{{ number_format($summary['waste'], 2) }}</h2>
                <small>kg</small>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Recorded Data</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Metric</th>
                        <th>Value</th>
                        <th>Unit</th>
                        <th>Date</th>
                        <th>Recorded By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sustainability as $item)
                    <tr>
                        <td>
                            <span class="badge bg-{{ $item->type_badge }}">
                                <i class="fas {{ $item->icon }}"></i> {{ ucfirst($item->metric_type) }}
                            </span>
                        </td>
                        <td>{{ $item->metric_name }}</td>
                        <td>{{ number_format($item->value, 2) }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ $item->measurement_date->format('d M Y') }}</td>
                        <td>{{ $item->recordedBy->full_name ?? 'N/A' }}</td>
                        <td>
                            @if($item->is_verified)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('sustainability.edit', $item) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('sustainability.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this data?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                @if(!$item->is_verified)
                                    <form action="{{ route('sustainability.verify', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Verify">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('sustainability.unverify', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary" title="Unverify">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            No sustainability data yet. 
                            <a href="{{ route('sustainability.create', $event) }}">Add data</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection