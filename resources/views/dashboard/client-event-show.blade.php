@extends('layouts.app')

@section('title', $event->event_name . ' - LimaSync')

@section('content')
{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>{{ $event->event_name }}</h1>
        <p class="mb-4">
            <i class="fas fa-building"></i> {{ $event->client->company_name ?? 'N/A' }} &nbsp;|&nbsp;
            <i class="fas fa-calendar"></i> {{ $event->start_date->format('d M Y') }}
            @if($event->end_date != $event->start_date)
                - {{ $event->end_date->format('d M Y') }}
            @endif
        </p>
    </div>
    <div>
        <a href="{{ route('client.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

{{-- Status Banner --}}
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-1">Overall Progress</h5>
                <small class="text-muted">{{ $checklistSummary['percentage'] }}% Complete</small>
                <div class="progress mt-2" style="height: 22px;">
                    <div class="progress-bar bg-{{ 
                        $checklistSummary['percentage'] == 100 ? 'success' : 
                        ($checklistSummary['percentage'] >= 50 ? 'warning' : 'danger') 
                    }}" 
                         role="progressbar" 
                         style="width: {{ $checklistSummary['percentage'] }}%">
                        {{ $checklistSummary['percentage'] }}%
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <span class="badge bg-{{ 
                    $event->live_status == 'completed' ? 'success' : 
                    ($event->live_status == 'in_progress' ? 'warning' : 'secondary') 
                }}" style="font-size: 1rem; padding: 10px 20px;">
                    {{ ucfirst($event->live_status) }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Event Details + Phase Progress --}}
<div class="row mb-4">
    {{-- Event Information --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0" style="color: white">Event Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <th style="width: 40%;">Event Name</th>
                        <td>{{ $event->event_name }}</td>
                    </tr>
                    <tr>
                        <th>Event Type</th>
                        <td>{{ $event->event_type }}</td>
                    </tr>
                    <tr>
                        <th>Venue</th>
                        <td>{{ $event->venue ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td>
                    </tr>
                    @if($event->start_time)
                    <tr>
                        <th>Time</th>
                        <td>{{ $event->start_time }} - {{ $event->end_time ?? 'N/A' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Estimated Attendees</th>
                        <td>{{ $event->estimated_attendees ?? 'N/A' }}</td>
                    </tr>
                    @if($event->description)
                    <tr>
                        <th>Description</th>
                        <td>{{ $event->description }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Phase Progress --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0" style="color: white">Phase Progress</h5>
            </div>
            <div class="card-body">
                {{-- Pre-Event --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Pre-Event</strong>
                        <small class="text-muted">
                            {{ $phaseProgress['pre_event']['completed'] }} / {{ $phaseProgress['pre_event']['total'] }}
                        </small>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-{{ 
                            $phaseProgress['pre_event']['percentage'] == 100 ? 'success' : 'info' 
                        }}" 
                             style="width: {{ $phaseProgress['pre_event']['percentage'] }}%">
                            {{ $phaseProgress['pre_event']['percentage'] }}%
                        </div>
                    </div>
                </div>

                {{-- During-Event --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>During-Event</strong>
                        <small class="text-muted">
                            {{ $phaseProgress['during_event']['completed'] }} / {{ $phaseProgress['during_event']['total'] }}
                        </small>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-{{ 
                            $phaseProgress['during_event']['percentage'] == 100 ? 'success' : 'warning' 
                        }}" 
                             style="width: {{ $phaseProgress['during_event']['percentage'] }}%">
                            {{ $phaseProgress['during_event']['percentage'] }}%
                        </div>
                    </div>
                </div>

                {{-- Post-Event --}}
                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>Post-Event</strong>
                        <small class="text-muted">
                            {{ $phaseProgress['post_event']['completed'] }} / {{ $phaseProgress['post_event']['total'] }}
                        </small>
                    </div>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-{{ 
                            $phaseProgress['post_event']['percentage'] == 100 ? 'success' : 'secondary' 
                        }}" 
                             style="width: {{ $phaseProgress['post_event']['percentage'] }}%">
                            {{ $phaseProgress['post_event']['percentage'] }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Sustainability Metrics --}}
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0" style="color: white">Sustainability Metrics</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <div class="card text-white bg-dark">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-leaf"></i> Carbon</h6>
                        <h3 class="mb-0" style="color: white">{{ number_format($sustainabilitySummary['carbon'], 2) }}</h3>
                        <small>kg CO₂</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-bolt"></i> Electricity</h6>
                        <h3 class="mb-0">{{ number_format($sustainabilitySummary['electricity'], 2) }}</h3>
                        <small>kWh</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-tint"></i> Water</h6>
                        <h3 class="mb-0">{{ number_format($sustainabilitySummary['water'], 2) }}</h3>
                        <small>m³</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-secondary">
                    <div class="card-body">
                        <h6 class="card-title"><i class="fas fa-trash"></i> Waste</h6>
                        <h3 class="mb-0">{{ number_format($sustainabilitySummary['waste'], 2) }}</h3>
                        <small>kg</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reports --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="color: white"></i> Available Reports</h5>
        <small class="text-muted">{{ $reports->count() }} report(s)</small>
    </div>
    <div class="card-body">
        @if($reports->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Report Title</th>
                            <th>Type</th>
                            <th>Generated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->report_title }}</td>
                            <td>
                                <span class="badge bg-{{ $report->type_badge }}">
                                    {{ $report->type_label }}
                                </span>
                            </td>
                            <td>{{ $report->generated_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('client.reports.view', $report) }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('client.reports.download', $report) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">No reports available yet.</p>
                <small class="text-muted">Reports will appear here once Lima Deria generates them.</small>
            </div>
        @endif
    </div>
</div>
@endsection