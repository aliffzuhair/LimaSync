@extends('layouts.app')

@section('title', 'Activity Log - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Activity Log</h1>
    <form action="{{ route('activity.clear') }}" method="POST" onsubmit="return confirm('Clear all activity logs?')">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash"></i> Clear Log
        </button>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('activity.index') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="action" class="form-label">Action</label>
                <select class="form-control" id="action" name="action">
                    <option value="">All Actions</option>
                    <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
                    <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Logout</option>
                    <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                    <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                    <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>View</option>
                    <option value="download" {{ request('action') == 'download' ? 'selected' : '' }}>Download</option>
                    <option value="approve" {{ request('action') == 'approve' ? 'selected' : '' }}>Approve</option>
                    <option value="reject" {{ request('action') == 'reject' ? 'selected' : '' }}>Reject</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="user_id" class="form-label">User</label>
                <select class="form-control" id="user_id" name="user_id">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->full_name ?? $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('activity.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Activities Table -->
<div class="card">
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
                    @forelse($activities as $activity)
                    <tr>
                        <td>
                            <small>{{ $activity->created_at->format('d M Y H:i') }}</small>
                            <br>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($activity->user)
                                <div class="d-flex align-items-center">
                                    @if($activity->user->profile_picture_url)
                                        <img src="{{ $activity->user->profile_picture_url }}" 
                                            alt="{{ $activity->user->full_name }}"
                                            class="rounded-circle me-2"
                                            style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                            style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: 600;">
                                            {{ $activity->user->initials }}
                                        </div>
                                    @endif
                                        <div>
                                            <strong>{{ $activity->user->full_name ?? $activity->user->name }}</strong>
                                            <br>
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $activity->user->email }}
                                            </small>
                                        </div>
                                    </div>
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
                        <td colspan="5" class="text-center">No activities found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $activities->links() }}
    </div>
</div>
@endsection