@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">Final Event Report</div>
</div>

{{-- Event Overview --}}
<div class="section">
    <h2>Event Overview</h2>
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
            <th>Date</th>
            <td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td>
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
            <th>Estimated Attendees</th>
            <td>{{ $event->estimated_attendees ?? 0 }}</td>
            <th>Actual Attendees</th>
            <td>{{ $event->actual_attendees ?? 0 }}</td>
        </tr>
        <tr>
            <th>Progress</th>
            <td colspan="3"><strong>{{ $event->progress_percentage }}%</strong> Complete</td>
        </tr>
    </table>
</div>

{{-- Financial Summary --}}
<div class="section">
    <h2>Financial Summary</h2>
    <div class="summary-box">
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="label">Budget</div>
                    <div class="value">RM {{ number_format($event->budget ?? 0, 2) }}</div>
                </td>
                <td>
                    <div class="label">Total Income</div>
                    <div class="value text-success">RM {{ number_format($total_income, 2) }}</div>
                </td>
                <td>
                    <div class="label">Total Expenses</div>
                    <div class="value text-danger">RM {{ number_format($total_expense, 2) }}</div>
                </td>
                <td>
                    <div class="label">Net Profit</div>
                    <div class="value {{ $net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                        RM {{ number_format($net_profit, 2) }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- Sustainability Summary --}}
<div class="section">
    <h2>Sustainability Summary</h2>
    <div class="summary-box">
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="label">Carbon</div>
                    <div class="value">{{ number_format($total_carbon, 2) }}</div>
                    <div class="unit">kg CO₂</div>
                </td>
                <td>
                    <div class="label">Electricity</div>
                    <div class="value">{{ number_format($total_electricity, 2) }}</div>
                    <div class="unit">kWh</div>
                </td>
                <td>
                    <div class="label">Water</div>
                    <div class="value">{{ number_format($total_water, 2) }}</div>
                    <div class="unit">m³</div>
                </td>
                <td>
                    <div class="label">Waste</div>
                    <div class="value">{{ number_format($total_waste, 2) }}</div>
                    <div class="unit">kg</div>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- Checklist Completion --}}
<div class="section">
    <h2>Checklist Completion</h2>
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

{{-- Conclusion --}}
<div class="section">
    <h2>Conclusion</h2>
    <div class="conclusion-box">
        <p>
            The event <strong>{{ $event->event_name }}</strong> has been successfully completed 
            with <strong>{{ $event->progress_percentage }}%</strong> checklist completion.
        </p>
        <p>
            @if($net_profit >= 0)
                The event generated a net profit of <strong class="text-success">RM {{ number_format($net_profit, 2) }}</strong>.
            @else
                The event had a net loss of <strong class="text-danger">RM {{ number_format(abs($net_profit), 2) }}</strong>.
            @endif
        </p>
        <p>
            <strong>Environmental Impact:</strong><br>
            Carbon Footprint: {{ number_format($total_carbon, 2) }} kg CO₂ | 
            Electricity Used: {{ number_format($total_electricity, 2) }} kWh | 
            Water Used: {{ number_format($total_water, 2) }} m³ | 
            Waste Generated: {{ number_format($total_waste, 2) }} kg
        </p>
    </div>

    <table class="signature-table">
        <tr>
            <td style="width: 50%;">
                <strong>Prepared by:</strong>
                <div class="signature-line">
                    {{ $generated_by }}<br>
                    Date: {{ $generated_at }}
                </div>
            </td>
            <td style="width: 50%;">
                <strong>Client Acknowledgement:</strong>
                <div class="signature-line">
                    _____________________<br>
                    Date: _____________
                </div>
            </td>
        </tr>
    </table>
</div>
@endsection