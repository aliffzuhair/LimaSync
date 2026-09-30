@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">{{ $event->event_name }} - Final Event Report</div>
</div>

<div class="section">
    <h2>Event Overview</h2>
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
    </table>
</div>

<div class="section">
    <h2>Financial Summary</h2>
    <div class="summary-box">
        <div class="summary-item">
            <strong>Budget:</strong>
            <span class="value">RM {{ number_format($event->budget ?? 0, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Income:</strong>
            <span class="value text-success">RM {{ number_format($total_income, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Total Expenses:</strong>
            <span class="value text-danger">RM {{ number_format($total_expense, 2) }}</span>
        </div>
        <div class="summary-item">
            <strong>Net Profit:</strong>
            <span class="value {{ $net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                RM {{ number_format($net_profit, 2) }}
            </span>
        </div>
    </div>
</div>

<div class="section">
    <h2>Sustainability Summary</h2>
    <div class="summary-box">
        <div class="summary-item">
            <strong>Carbon:</strong> {{ number_format($total_carbon, 2) }} kg CO₂
        </div>
        <div class="summary-item">
            <strong>Electricity:</strong> {{ number_format($total_electricity, 2) }} kWh
        </div>
        <div class="summary-item">
            <strong>Water:</strong> {{ number_format($total_water, 2) }} m³
        </div>
        <div class="summary-item">
            <strong>Waste:</strong> {{ number_format($total_waste, 2) }} kg
        </div>
    </div>
</div>

<div class="section">
    <h2>Checklist Completion</h2>
    @php $summary = $event->getChecklistSummary(); @endphp
    <div class="summary-box">
        <div class="summary-item">
            <strong>Total Items:</strong> {{ $summary['total'] }}
        </div>
        <div class="summary-item">
            <strong>Completed:</strong> {{ $summary['completed'] }}
        </div>
        <div class="summary-item">
            <strong>Progress:</strong> {{ $summary['percentage'] }}%
        </div>
    </div>
</div>

<div class="section">
    <h2>Conclusion</h2>
    <p>
        The event <strong>{{ $event->event_name }}</strong> has been successfully completed with 
        {{ $event->progress_percentage }}% checklist completion. 
        @if($net_profit >= 0)
            The event generated a net profit of RM {{ number_format($net_profit, 2) }}.
        @else
            The event had a net loss of RM {{ number_format(abs($net_profit), 2) }}.
        @endif
    </p>
    <p>
        Total carbon footprint: {{ number_format($total_carbon, 2) }} kg CO₂ | 
        Electricity used: {{ number_format($total_electricity, 2) }} kWh | 
        Water used: {{ number_format($total_water, 2) }} m³ | 
        Waste generated: {{ number_format($total_waste, 2) }} kg
    </p>
    <br>
    <table>
        <tr>
            <td style="width: 50%;">
                <strong>Prepared by:</strong><br>
                {{ $generated_by }}<br>
                <small>Date: {{ $generated_at }}</small>
            </td>
            <td style="width: 50%;">
                <strong>Client Acknowledgement:</strong><br>
                _____________________<br>
                <small>Date: _____________</small>
            </td>
        </tr>
    </table>
</div>
@endsection