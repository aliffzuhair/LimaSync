@extends('layouts.app')

@section('title', 'Admin Dashboard - LimaSync')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Admin Dashboard</h1>
        <p class="mb-4">Welcome back, {{ $user->full_name ?? $user->name }}!</p>
    </div>
</div>

<!-- Summary Cards -->
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="card-text">{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Clients</h5>
                <h2 class="card-text">{{ $totalClients }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Events</h5>
                <h2 class="card-text">{{ $totalEvents }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">Active Events</h5>
                <h2 class="card-text">{{ $activeEvents }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-tasks"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('events.create') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-calendar-plus"></i> Create Event
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('clients.create') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-user-plus"></i> Add Client
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-box"></i> Manage Inventory
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('reports.all') }}" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-file-alt"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0" style="color: white
                "><i class="fas fa-history"></i> Recent Activity Log</h5>
                <a href="{{ route('activity.index') }}" class="btn btn-sm btn-outline-secondary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                            <tr>
                                <td>
                                    <small>{{ $activity->created_at->format('d M Y H:i') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    @if($activity->user)
                                        <strong>{{ $activity->user->full_name ?? $activity->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $activity->user->email }}</small>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $activity->action_badge }}">
                                        <i class="fas {{ $activity->action_icon }}"></i>
                                        {{ ucfirst($activity->action) }}
                                    </span>
                                </td>
                                <td>{{ $activity->description }}</td>
                                <td>
                                    <small class="text-muted">{{ $activity->ip_address ?? 'N/A' }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No activities logged yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection