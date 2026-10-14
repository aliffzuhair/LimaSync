@extends('layouts.app')

@section('title', 'Add Inventory Item - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Add Inventory Item</h1>
    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="item_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('item_name') is-invalid @enderror" 
                           id="item_name" name="item_name" value="{{ old('item_name') }}" required>
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
    <select class="form-control @error('category') is-invalid @enderror" 
            id="category" name="category" required>
        <option value="">Select Category</option>
        @foreach($categories as $category)
            <option value="{{ $category }}" 
                    {{ old('category') == $category ? 'selected' : '' }}>
                {{ $category }}
            </option>
        @endforeach
        <option value="__new__" {{ old('category') == '__new__' ? 'selected' : '' }}>
            + Add New Category
        </option>
    </select>
    @error('category')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                {{-- New Category Input (Hidden by default) --}}
                <div class="col-md-6 mb-3" id="new-category-wrapper" style="display: none;">
                    <label for="new_category" class="form-label">New Category Name <span class="text-danger">*</span></label>
                    <input type="text" 
                        class="form-control @error('new_category') is-invalid @enderror" 
                        id="new_category" 
                        name="new_category" 
                        value="{{ old('new_category') }}"
                        placeholder="e.g., Furniture, AV Equipment">
                    @error('new_category')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Enter the new category name</small>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                           id="quantity" name="quantity" value="{{ old('quantity', 0) }}" min="0" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="min_quantity" class="form-label">Minimum Stock <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('min_quantity') is-invalid @enderror" 
                           id="min_quantity" name="min_quantity" value="{{ old('min_quantity', 0) }}" min="0" required>
                    @error('min_quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('unit') is-invalid @enderror" 
                           id="unit" name="unit" value="{{ old('unit', 'unit') }}" required>
                    @error('unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                           id="location" name="location" value="{{ old('location') }}">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="supplier" class="form-label">Supplier</label>
                    <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                           id="supplier" name="supplier" value="{{ old('supplier') }}">
                    @error('supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cost_per_unit" class="form-label">Cost Per Unit (RM)</label>
                    <input type="number" step="0.01" class="form-control @error('cost_per_unit') is-invalid @enderror" 
                           id="cost_per_unit" name="cost_per_unit" value="{{ old('cost_per_unit') }}" min="0">
                    @error('cost_per_unit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="2">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                              id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Item
            </button>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category');
            const newCategoryWrapper = document.getElementById('new-category-wrapper');
            const newCategoryInput = document.getElementById('new_category');

            // Show or hide the "new category" input based on selection
            categorySelect.addEventListener('change', function() {
                if (this.value === '__new__') {
                    newCategoryWrapper.style.display = 'block';
                    newCategoryInput.required = true;
                    newCategoryInput.focus();
                } else {
                    newCategoryWrapper.style.display = 'none';
                    newCategoryInput.required = false;
                    newCategoryInput.value = '';
                }
            });

            // Trigger on page load if old value was "__new__"
            if (categorySelect.value === '__new__') {
                newCategoryWrapper.style.display = 'block';
                newCategoryInput.required = true;
            }
        });
    </script>
    @endpush
@endsection