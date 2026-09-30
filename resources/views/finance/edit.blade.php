@extends('layouts.app')

@section('title', 'Edit Transaction - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Transaction</h1>
    <a href="{{ route('finance.event', $event) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('finance.update', $finance) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="transaction_type" class="form-label">Transaction Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="transaction_type" name="transaction_type" required>
                        <option value="income" {{ old('transaction_type', $finance->transaction_type) == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ old('transaction_type', $finance->transaction_type) == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="category" name="category" 
                           value="{{ old('category', $finance->category) }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="description" name="description" 
                           value="{{ old('description', $finance->description) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="amount" class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" 
                           value="{{ old('amount', $finance->amount) }}" min="0.01" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="transaction_date" class="form-label">Transaction Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" 
                           value="{{ old('transaction_date', $finance->transaction_date->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="reference_no" class="form-label">Reference No</label>
                    <input type="text" class="form-control" id="reference_no" name="reference_no" 
                           value="{{ old('reference_no', $finance->reference_no) }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $finance->notes) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Transaction
            </button>
            <a href="{{ route('finance.event', $event) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection