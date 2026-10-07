@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">Event Summary Report</div>
</div>

{{-- Event Information --}}
<div class="section">
    <h2>Event Information</h2>
    <table class="info-table">
        <tr>
            <th>Event Name</th>
            <td>{{ $event->event_name }}</td>
            <th>Client</th>
            <td>{{ $event->client->company_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Event Type</th>
            <td>{{ $event->event_type }}</td>
            <th>Venue</th>
            <td>{{ $event->venue ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Start Date</th>
            <td>{{ $event->start_date->format('d M Y') }}</td>
            <th>End Date</th>
            <td>{{ $event->end_date->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Estimated Attendees</th>
            <td>{{ $event->estimated_attendees ?? 0 }}</td>
            <th>Actual Attendees</th>
            <td>{{ $event->actual_attendees ?? 0 }}</td>
        </tr>
        <tr>
            <th>Budget</th>
            <td>RM {{ number_format($event->budget ?? 0, 2) }}</td>
            <th>Status</th>
            <td>
                <span class="badge badge-{{ 
                    $event->live_status == 'completed' ? 'success' : 
                    ($event->live_status == 'in_progress' ? 'warning' : 'secondary') 
                }}">
                    {{ ucfirst($event->live_status) }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Progress</th>
            <td colspan="3">
                <strong>{{ $event->progress_percentage }}%</strong> Complete
            </td>
        </tr>
    </table>
</div>

{{-- Progress Overview --}}
<div class="section">
    <h2>Progress Overview</h2>
    @php $summary = $event->getChecklistSummary(); @endphp
    <div class="summary-box">
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="label">Total Items</div>
                    <div class="value">{{ $summary['total'] }}</div>
                </td>
                <td>
                    <div class="label">Completed</div>
                    <div class="value text-success">{{ $summary['completed'] }}</div>
                </td>
                <td>
                    <div class="label">In Progress</div>
                    <div class="value">{{ $summary['in_progress'] }}</div>
                </td>
                <td>
                    <div class="label">Pending</div>
                    <div class="value">{{ $summary['pending'] }}</div>
                </td>
                <td>
                    <div class="label">Progress</div>
                    <div class="value">{{ $summary['percentage'] }}%</div>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- Checklist Details --}}
<div class="section">
    <h2>Checklist Details</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Item</th>
                <th style="width: 25%;">Phase</th>
                <th style="width: 20%;">Status</th>
                <th style="width: 20%;">Completed By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->eventChecklists as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->checklist->item_name ?? 'N/A' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->checklist->phase->name ?? 'N/A')) }}</td>
                <td>
                    <span class="badge badge-{{ 
                        $item->status == 'completed' ? 'success' : 
                        ($item->status == 'in_progress' ? 'warning' : 'secondary') 
                    }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    </span>
                </td>
                <td>{{ $item->completedBy->full_name ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection