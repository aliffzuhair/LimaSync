@extends('layouts.app')

@section('title', 'Edit Inventory Item - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Inventory Item</h1>
    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory.update', $inventory) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="item_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('item_name') is-invalid @enderror" 
                           id="item_name" name="item_name" value="{{ old('item_name', $inventory->item_name) }}" required>
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('category') is-invalid @enderror" 
                           id="category" name="category" value="{{ old('category', $inventory->category) }}" required>
                    @error('category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                           id="quantity" name="quantity" value="{{ old('quantity', $inventory->quantity) }}" min="0" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="min_quantity" class="form-label">Minimum Stock <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('min_quantity') is-invalid @enderror" 
                           id="min_quantity" name="min_quantity" value="{{ old('min_quantity', $inventory->min_quantity) }}" min="0" required>
                    @error('min_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                           id="unit" name="unit" value="{{ old('unit', $inventory->unit) }}" required>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                           id="location" name="location" value="{{ old('location', $inventory->location) }}">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="supplier" class="form-label">Supplier</label>
                    <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                           id="supplier" name="supplier" value="{{ old('supplier', $inventory->supplier) }}">
                    @error('supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cost_per_unit" class="form-label">Cost Per Unit (RM)</label>
                    <input type="number" step="0.01" class="form-control @error('cost_per_unit') is-invalid @enderror" 
                           id="cost_per_unit" name="cost_per_unit" value="{{ old('cost_per_unit', $inventory->cost_per_unit) }}" min="0">
                    @error('cost_per_unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="is_active" class="form-label">Status</label>
                    <select class="form-control" id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', $inventory->is_active) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active', $inventory->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $inventory->description) }}</textarea>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $inventory->notes) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Item
            </button>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection