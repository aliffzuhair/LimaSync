@extends('layouts.app')

@section('title', 'Events - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Events</h1>
    @if(auth()->user()->role && auth()->user()->role->name === 'admin')
        <a href="{{ route('events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Event
        </a>
    @endif
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
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
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $event->event_name }}</td>
                        <td>{{ $event->client->company_name ?? 'N/A' }}</td>
                        <td>{{ $event->start_date->format('d M Y') }}</td>
                        <td>{{ $event->end_date->format('d M Y') }}</td>
                        <td>
                            @php
                                // Calculate live progress
                                $liveProgress = $event->calculateProgress();
                                
                                // Determine live status
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
                            }}">
                                <i class="fas fa-{{ 
                                    $liveStatus == 'completed' ? 'check-circle' : 
                                    ($liveStatus == 'in_progress' ? 'spinner' : 
                                    ($liveStatus == 'cancelled' ? 'ban' : 'clock')) 
                                }}"></i>
                                {{ ucfirst($liveStatus) }}
                            </span>
                        </td>
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
                        <td>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No events found. <a href="{{ route('events.create') }}">Create your first event</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $events->links() }}
    </div>
</div>
@endsection