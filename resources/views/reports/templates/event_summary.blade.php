@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">{{ $event->event_name }}</div>
</div>

<div class="section">
    <h2>Event Information</h2>
    <table>
        <tr><th style="width: 30%;">Event Name</th><td>{{ $event->event_name }}</td></tr>
        <tr><th>Client</th><td>{{ $event->client->company_name ?? 'N/A' }}</td></tr>
        <tr><th>Event Type</th><td>{{ $event->event_type }}</td></tr>
        <tr><th>Venue</th><td>{{ $event->venue ?? 'N/A' }}</td></tr>
        <tr><th>Date</th><td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td></tr>
        <tr><th>Estimated Attendees</th><td>{{ $event->estimated_attendees ?? 'N/A' }}</td></tr>
        <tr><th>Actual Attendees</th><td>{{ $event->actual_attendees ?? 'N/A' }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($event->status) }}</td></tr>
        <tr><th>Progress</th><td>{{ $event->progress_percentage }}%</td></tr>
        <tr><th>Budget</th><td>RM {{ number_format($event->budget ?? 0, 2) }}</td></tr>
    </table>
</div>

<div class="section">
    <h2>Checklist Summary</h2>
    @php $summary = $event->getChecklistSummary(); @endphp
    <div class="summary-box">
        <div class="summary-item">
            <strong>Total Items:</strong> {{ $summary['total'] }}
        </div>
        <div class="summary-item">
            <strong>Completed:</strong> {{ $summary['completed'] }}
        </div>
        <div class="summary-item">
            <strong>In Progress:</strong> {{ $summary['in_progress'] }}
        </div>
        <div class="summary-item">
            <strong>Pending:</strong> {{ $summary['pending'] }}
        </div>
        <div class="summary-item">
            <strong>Progress:</strong> {{ $summary['percentage'] }}%
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Phase</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->eventChecklists as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->checklist->item_name ?? 'N/A' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $item->checklist->phase->name ?? 'N/A')) }}</td>
                <td>
                    <span class="badge badge-{{ $item->status == 'completed' ? 'success' : ($item->status == 'in_progress' ? 'warning' : 'secondary') }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection