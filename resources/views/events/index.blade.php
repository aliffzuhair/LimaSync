@extends('layouts.app')

@section('title', 'Events - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Events</h1>
        <p class="mb-4">Manage all events in the system</p>
    </div>
    @if(auth()->user()->role && auth()->user()->role->name === 'admin')
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Event
        </a>
    @endif
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Events</h5>
                <h2 class="card-text">{{ $totalEvents }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">Planning</h5>
                <h2 class="card-text">{{ $planningEvents }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">In Progress</h5>
                <h2 class="card-text">{{ $inProgressEvents }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Completed</h5>
                <h2 class="card-text">{{ $completedEvents }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- Search and Filter Bar --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('events.index') }}" method="GET" class="row g-3">
            {{-- Search Input --}}
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text"
                           class="form-control"
                           name="search"
                           placeholder="Search by event name, client, venue, or type..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-3">
                <select class="form-control" name="status">
                    <option value="">All Status</option>
                    <option value="planning" {{ request('status') == 'planning' ? 'selected' : '' }}>
                        Planning
                    </option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                        In Progress
                    </option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>

        {{-- Active Filter Chips --}}
        @if(request('search') || request('status'))
            <div class="mt-3">
                <strong>Active Filters:</strong>
                @if(request('search'))
                    <span class="badge bg-info text-dark ms-2">
                        Search: "{{ request('search') }}"
                        <a href="{{ request()->fullUrlWithoutQuery('search') }}" class="text-dark ms-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                @if(request('status'))
                    <span class="badge bg-info text-dark ms-2">
                        Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                        <a href="{{ request()->fullUrlWithoutQuery('status') }}" class="text-dark ms-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- Results Count --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <small class="text-muted">
            Showing <strong>{{ $events->firstItem() ?? 0 }}</strong>
            to <strong>{{ $events->lastItem() ?? 0 }}</strong>
            of <strong>{{ $events->total() }}</strong> events
        </small>
    </div>
</div>

{{-- Events Table --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-clickable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Client</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr onclick="window.location='{{ route('events.show', $event) }}'">
                        <td>{{ $loop->iteration + ($events->currentPage() - 1) * $events->perPage() }}</td>
                        <td>
                            <strong>{{ $event->event_name }}</strong>
                            @if($event->venue)
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> {{ $event->venue }}
                                </small>
                            @endif
                        </td>
                        <td>{{ $event->client->company_name ?? 'N/A' }}</td>
                        <td>{{ $event->start_date->format('d M Y') }}</td>
                        <td>{{ $event->end_date->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $event->status == 'completed' ? 'success' : 
                                ($event->status == 'in_progress' ? 'warning' : 
                                ($event->status == 'cancelled' ? 'danger' : 'secondary')) 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $event->status)) }}
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
                        <td onclick="event.stopPropagation();">
                            <div class="action-buttons">
                                <a href="{{ route('events.show', $event) }}" class="btn-action btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                    <a href="{{ route('events.edit', $event) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete"
                                                onclick="return confirm('Are you sure you want to delete {{ $event->event_name }}?')"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-search fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">
                                @if(request('search') || request('status'))
                                    No events match your filters.
                                    <a href="{{ route('events.index') }}">Clear filters</a>
                                @else
                                    No events found.
                                    @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                        <a href="{{ route('events.create') }}">Create your first event</a>
                                    @endif
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $events->links() }}
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit on status change
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }

    // Debounced search
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const form = this.form;
            searchTimeout = setTimeout(function() {
                form.submit();
            }, 800);
        });
    }
</script>
@endpush
@endsection