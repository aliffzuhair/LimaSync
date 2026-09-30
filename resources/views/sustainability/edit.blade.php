@extends('layouts.app')

@section('title', 'Edit Sustainability Data - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Sustainability Data</h1>
    <a href="{{ route('sustainability.index', $event) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('sustainability.update', $sustainability) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="metric_type" class="form-label">Metric Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="metric_type" name="metric_type" required>
                        <option value="carbon" {{ old('metric_type', $sustainability->metric_type) == 'carbon' ? 'selected' : '' }}>Carbon Footprint</option>
                        <option value="electricity" {{ old('metric_type', $sustainability->metric_type) == 'electricity' ? 'selected' : '' }}>Electricity Usage</option>
                        <option value="water" {{ old('metric_type', $sustainability->metric_type) == 'water' ? 'selected' : '' }}>Water Usage</option>
                        <option value="waste" {{ old('metric_type', $sustainability->metric_type) == 'waste' ? 'selected' : '' }}>Waste Generated</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="metric_name" class="form-label">Metric Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="metric_name" name="metric_name" 
                           value="{{ old('metric_name', $sustainability->metric_name) }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="value" class="form-label">Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" id="value" name="value" 
                           value="{{ old('value', $sustainability->value) }}" min="0" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="unit" class="form-label">Unit <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="unit" name="unit" 
                           value="{{ old('unit', $sustainability->unit) }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="measurement_date" class="form-label">Measurement Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="measurement_date" name="measurement_date" 
                           value="{{ old('measurement_date', $sustainability->measurement_date->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="source" class="form-label">Source</label>
                    <input type="text" class="form-control" id="source" name="source" 
                           value="{{ old('source', $sustainability->source) }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $sustainability->notes) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Data
            </button>
            <a href="{{ route('sustainability.index', $event) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection