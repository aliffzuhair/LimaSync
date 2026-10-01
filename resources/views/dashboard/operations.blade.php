@extends('layouts.app')

@section('title', 'Operations Dashboard - LimaSync')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Operations Dashboard</h1>
        <p class="mb-4">Welcome back, {{ $user->full_name ?? $user->name }}!</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">My Events</h5>
                <h2 class="card-text">{{ \App\Models\Event::where('created_by', $user->id)->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Completed Events</h5>
                <h2 class="card-text">{{ \App\Models\Event::where('status', 'completed')->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">In Progress</h5>
                <h2 class="card-text">{{ \App\Models\Event::where('status', 'in_progress')->count() }}</h2>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <i class="fas fa-calendar"></i> Recent Events
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-clickable">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Client</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th style="width: 30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Event::with('client')->latest()->take(5)->get() as $event)
                    <tr onclick="window.location='{{ route('checklists.index', $event) }}'">
                        <td>
                            <strong>{{ $event->event_name }}</strong>
                        </td>
                        <td>{{ $event->client->company_name ?? 'N/A' }}</td>
                        <td>{{ $event->start_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $event->status == 'completed' ? 'success' : ($event->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="progress" style="width: 100px;">
                                <div class="progress-bar bg-{{ 
                                    $event->progress_percentage == 100 ? 'success' : 
                                    ($event->progress_percentage >= 50 ? 'warning' : 'danger') 
                                    }}" 
                                    role="progressbar" 
                                    style="width: {{ $event->progress_percentage }}%">
                                    {{ $event->progress_percentage }}%
                                </div>
                            </div>
                        </td>
                        <td class="row-arrow-cell">
                            <span class="row-arrow">→</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No events found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection