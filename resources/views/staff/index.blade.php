@extends('layouts.app')

@section('title', 'User Management - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>User Management</h1>
        <p class="mb-4">Manage all user accounts in the system</p>
    </div>
    <a href="{{ route('staff.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Add User
    </a>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Total User</h6>
                <h3 class="mb-0">{{ \App\Models\User::count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Active User</h6>
                <h3 class="mb-0">{{ \App\Models\User::where('is_active', true)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6 class="card-title">Inactive User</h6>
                <h3 class="mb-0">{{ \App\Models\User::where('is_active', false)->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6 class="card-title">Roles</h6>
                <h3 class="mb-0">{{ \App\Models\Role::count() }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('staff.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" 
                       placeholder="Search by name, email, or username..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select class="form-control" name="role_id">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Staff Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                    <tr>
                        <td>{{ $loop->iteration + ($staff->currentPage() - 1) * $staff->perPage() }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($member->profile_picture_url)
                                    <img src="{{ $member->profile_picture_url }}" 
                                        alt="{{ $member->full_name }}"
                                        class="rounded-circle me-2"
                                        style="width: 35px; height: 35px; object-fit: cover; border: 2px solid #B5C401;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                        style="width: 35px; height: 35px; font-size: 14px; font-weight: 600;">
                                        {{ $member->initials }}
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $member->full_name ?? $member->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>{{ $member->username }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($member->role->name ?? 'N/A') }}</span>
                        </td>
                        <td>{{ $member->department ?? 'N/A' }}</td>
                        <td>
                            @if($member->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($member->last_login)
                                <small class="text-muted">
                                    {{ $member->last_login->diffForHumans() }}
                                </small>
                                <br>
                                <small class="text-muted" style="font-size: 0.7rem;">
                                    {{ $member->last_login->format('d M Y H:i') }}
                                </small>
                            @else
                                <span class="badge bg-secondary">Never</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                            <a href="{{ route('staff.show', $member) }}" class="btn-action btn-view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('staff.edit', $member) }}" class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                                @if($member->id !== auth()->id())
                                <form action="{{ route('staff.toggle-status', $member) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn-action btn-toggle"
                                            title="{{ $member->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $member->is_active ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('staff.destroy', $member) }}" method="POST"
                                    onsubmit="return confirm('Delete {{ $member->full_name }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            No user found. 
                            <a href="{{ route('staff.create') }}">Add your first user</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $staff->appends(request()->query())->links() }}
    </div>
</div>
@endsection