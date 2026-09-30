@extends('layouts.app')

@section('title', 'Client Details - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $client->company_name }}</h1>
    <div>
        @if(auth()->user()->role && auth()->user()->role->name === 'admin')
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
        @endif
        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Client Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%;">Company Name</th>
                        <td>{{ $client->company_name }}</td>
                    </tr>
                    <tr>
                        <th>Contact Person</th>
                        <td>{{ $client->contact_person }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $client->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $client->phone }}</td>
                    </tr>
                    <tr>
                        <th>Industry</th>
                        <td>{{ $client->industry ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $client->address ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($client->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Notes</th>
                        <td>{{ $client->notes ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $client->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Related Events</h5>
                <small class="text-muted">{{ $client->events->count() }} events</small>
            </div>
            <div class="card-body">
                @if($client->events->count() > 0)
                    <div class="list-group">
                        @foreach($client->events as $event)
                            <a href="{{ route('events.show', $event) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $event->event_name }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $event->status == 'completed' ? 'success' : ($event->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No events associated with this client.</p>
                    <a href="{{ route('events.create') }}?client_id={{ $client->id }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Create Event for this Client
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection