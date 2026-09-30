@extends('layouts.app')

@section('title', $staff->full_name . ' - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $staff->full_name ?? $staff->name }}</h1>
    <div>
        <a href="{{ route('staff.edit', $staff) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('staff.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($staff->profile_picture_url)
                    <img src="{{ $staff->profile_picture_url }}" 
                        alt="{{ $staff->full_name }}"
                        class="rounded-circle mb-3 mx-auto d-block"
                        style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #B5C401;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                        style="width: 100px; height: 100px; font-size: 40px; font-weight: 600;">
                        {{ $staff->initials }}
                    </div>
                @endif
                <h4>{{ $staff->full_name ?? $staff->name }}</h4>
                <p class="text-muted mb-1">{{ $staff->email }}</p>
                <span class="badge bg-secondary">{{ ucfirst($staff->role->name ?? 'N/A') }}</span>
                <br><br>
                @if($staff->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Staff Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">Full Name</th>
                        <td>{{ $staff->full_name ?? $staff->name }}</td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $staff->username }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $staff->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>{{ ucfirst($staff->role->name ?? 'N/A') }}</td>
                    </tr>
                    @if($staff->client)
                    <tr>
                        <th>Linked Client</th>
                        <td>
                            <a href="{{ route('clients.show', $staff->client) }}">
                                {{ $staff->client->company_name }}
                            </a>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Department</th>
                        <td>{{ $staff->department ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $staff->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($staff->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Last Login</th>
                        <td>
                            @if($staff->last_login)
                                {{ $staff->last_login->format('d M Y, H:i') }}
                                <br>
                                <small class="text-muted">{{ $staff->last_login->diffForHumans() }}</small>
                            @else
                                <span class="badge bg-secondary">Never</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Account Created</th>
                        <td>{{ $staff->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Reset Password -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Reset Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('staff.reset-password', $staff) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" 
                                   name="password_confirmation" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-key"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection