@extends('layouts.app')

@section('title', 'Clients - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Clients</h1>
        <p class="mb-4">Manage all client records in the system</p>
    </div>
    @if(auth()->user()->role && auth()->user()->role->name === 'admin')
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Client
        </a>
    @endif
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Clients</h5>
                <h2 class="card-text">{{ $totalClients }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Active Clients</h5>
                <h2 class="card-text">{{ $activeClients }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-secondary">
            <div class="card-body">
                <h5 class="card-title">Inactive Clients</h5>
                <h2 class="card-text">{{ $inactiveClients }}</h2>
            </div>
        </div>
    </div>
</div>

{{-- Search and Filter Bar --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('clients.index') }}" method="GET" class="row g-3">
            {{-- Search Input --}}
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text"
                           class="form-control"
                           name="search"
                           placeholder="Search by name, contact, email, or phone..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Industry Filter --}}
            <div class="col-md-3">
                <select class="form-control" name="industry">
                    <option value="">All Industries</option>
                    @foreach($industries as $industry)
                        <option value="{{ $industry }}"
                                {{ request('industry') == $industry ? 'selected' : '' }}>
                            {{ $industry }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-2">
                <select class="form-control" name="status">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="col-md-2 d-flex">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>

        {{-- Active Filter Chips --}}
        @if(request('search') || request('industry') || request('status'))
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
                @if(request('industry'))
                    <span class="badge bg-info text-dark ms-2">
                        Industry: {{ request('industry') }}
                        <a href="{{ request()->fullUrlWithoutQuery('industry') }}" class="text-dark ms-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                @endif
                @if(request('status'))
                    <span class="badge bg-info text-dark ms-2">
                        Status: {{ ucfirst(request('status')) }}
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
            Showing <strong>{{ $clients->firstItem() ?? 0 }}</strong>
            to <strong>{{ $clients->lastItem() ?? 0 }}</strong>
            of <strong>{{ $clients->total() }}</strong> clients
        </small>
    </div>
</div>

{{-- Clients Table --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Industry</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>{{ $loop->iteration + ($clients->currentPage() - 1) * $clients->perPage() }}</td>
                        <td>
                            <strong>{{ $client->company_name }}</strong>
                        </td>
                        <td>{{ $client->contact_person }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone }}</td>
                        <td>{{ $client->industry ?? 'N/A' }}</td>
                        <td>
                            @if($client->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('clients.show', $client) }}" class="btn-action btn-view" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                    <a href="{{ route('clients.edit', $client) }}" class="btn-action btn-edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete"
                                                onclick="return confirm('Are you sure you want to delete {{ $client->company_name }}?')"
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
                                @if(request('search') || request('industry') || request('status'))
                                    No clients match your filters.
                                    <a href="{{ route('clients.index') }}">Clear filters</a>
                                @else
                                    No clients found.
                                    @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                        <a href="{{ route('clients.create') }}">Add your first client</a>
                                    @endif
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $clients->links() }}
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit on dropdown change
    document.querySelectorAll('select[name="industry"], select[name="status"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Debounced search (auto-submit after typing stops)
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const form = this.form;
            searchTimeout = setTimeout(function() {
                form.submit();
            }, 800); // 800ms delay
        });
    }
</script>
@endpush
@endsection