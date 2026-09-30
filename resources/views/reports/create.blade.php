@extends('layouts.app')

@section('title', 'Generate Report - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Generate Report</h1>
    <a href="{{ route('reports.index', $event) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('reports.generate', $event) }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="report_type" class="form-label">Report Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="report_type" name="report_type" required>
                        <option value="">Select type...</option>
                        <option value="event_summary">Event Summary Report</option>
                        <option value="financial">Financial Report</option>
                        <option value="sustainability">Sustainability Report</option>
                        <option value="iso">ISO Compliance Report</option>
                        <option value="final" {{ $event->isReadyForReport() ? '' : 'disabled' }}>
                            Final Event Report {{ $event->isReadyForReport() ? '' : '(Event not complete)' }}
                        </option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="report_title" class="form-label">Report Title</label>
                    <input type="text" class="form-control" id="report_title" name="report_title" 
                           placeholder="Leave blank for default title">
                </div>

                <div class="col-md-12 mb-3">
                    <label for="notes" class="form-label">Notes (Internal)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                The report will be generated as a PDF file and saved to the system.
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Generate Report
            </button>
            <a href="{{ route('reports.index', $event) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection