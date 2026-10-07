@extends('layouts.app')

@section('title', 'Inventory - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Inventory</h1>
    @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'logistics']))
        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Item
        </a>
    @endif
</div>

<!-- ✅ Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Sufficient</h5>
                <h2 class="card-text">{{ $sufficientCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Low Stock</h5>
                <h2 class="card-text">{{ $lowStockCount }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Out of Stock</h5>
                <h2 class="card-text">{{ $outOfStockCount }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Search & Filter Bar -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('inventory.index') }}" method="GET" class="row g-3">
            <!-- Search Input -->
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" 
                           class="form-control" 
                           name="search" 
                           placeholder="Search by name, category, supplier..." 
                           value="{{ request('search') }}">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="col-md-3">
                <select class="form-control" name="category">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" 
                                {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="col-md-2">
                <select class="form-control" name="status">
                    <option value="">All Status</option>
                    <option value="sufficient" {{ request('status') == 'sufficient' ? 'selected' : '' }}>
                        Sufficient
                    </option>
                    <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>
                        Low Stock
                    </option>
                    <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>
                        Out of Stock
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-md-3 d-flex">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-filter"></i> Apply
                </button>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                </a>
            </div>
        </form>

        <!-- ✅ Active Filter Chips -->
        @if(request('search') || request('category') || request('status'))
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
                @if(request('category'))
                    <span class="badge bg-info text-dark ms-2">
                        Category: {{ request('category') }}
                        <a href="{{ request()->fullUrlWithoutQuery('category') }}" class="text-dark ms-1">
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

<!-- ✅ Results Count -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <small class="text-muted">
            Showing <strong>{{ $inventory->firstItem() ?? 0 }}</strong> 
            to <strong>{{ $inventory->lastItem() ?? 0 }}</strong> 
            of <strong>{{ $inventory->total() }}</strong> items
        </small>
    </div>
</div>

<!-- ✅ Inventory Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Min Stock</th>
                        <th>Available</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory as $item)
                    <tr>
                        <td>{{ $loop->iteration + ($inventory->currentPage() - 1) * $inventory->perPage() }}</td>
                        <td>
                            <strong>{{ $item->item_name }}</strong>
                            @if($item->supplier)
                                <br>
                                <small class="text-muted">{{ $item->supplier }}</small>
                            @endif
                        </td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->quantity }} {{ $item->unit }}</td>
                        <td>{{ $item->min_quantity }} {{ $item->unit }}</td>
                        <td>
                            <strong>{{ $item->available_quantity }}</strong> {{ $item->unit }}
                        </td>
                        <td>
                            <span class="badge bg-{{ $item->stock_status_badge }}">
                                {{ $item->stock_status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('inventory.show', $item) }}" class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'logistics']))
                                <a href="{{ route('inventory.edit', $item) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Delete {{ $item->item_name }}?')"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-search fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">
                                @if(request('search') || request('category') || request('status'))
                                    No items match your filters.
                                    <a href="{{ route('inventory.index') }}">Clear filters</a>
                                @else
                                    No inventory items found.
                                    <a href="{{ route('inventory.create') }}">Add your first item</a>
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $inventory->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit on dropdown change
    document.querySelectorAll('select[name="category"], select[name="status"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    // Debounced search (auto-submit after typing stops)
    let searchTimeout;
    document.querySelector('input[name="search"]').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const form = this.form;
        searchTimeout = setTimeout(function() {
            form.submit();
        }, 800); // Wait 800ms after last keystroke
    });
</script>
@endpush
@endsection