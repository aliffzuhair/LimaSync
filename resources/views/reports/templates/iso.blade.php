@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">{{ $event->event_name }} - ISO 20121 Compliance Report</div>
</div>

<div class="section">
    <h2>Event Information</h2>
    <table>
        <tr><th style="width: 30%;">Event Name</th><td>{{ $event->event_name }}</td></tr>
        <tr><th>Client</th><td>{{ $event->client->company_name ?? 'N/A' }}</td></tr>
        <tr><th>Date</th><td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td></tr>
        <tr><th>Venue</th><td>{{ $event->venue ?? 'N/A' }}</td></tr>
        <tr><th>ISO Standard</th><td>ISO 20121:2012 - Event Sustainability Management Systems</td></tr>
    </table>
</div>

<div class="section">
    <h2>ISO Compliance Metrics</h2>
    <div class="summary-box">
        <div class="summary-item">
            <strong>Carbon Footprint:</strong>
            <span class="value">{{ number_format($total_carbon, 2) }} kg CO₂</span>
        </div>
        <div class="summary-item">
            <strong>Electricity Usage:</strong>
            <span class="value">{{ number_format($total_electricity, 2) }} kWh</span>
        </div>
        <div class="summary-item">
            <strong>Water Usage:</strong>
            <span class="value">{{ number_format($total_water, 2) }} m³</span>
        </div>
        <div class="summary-item">
            <strong>Waste Generated:</strong>
            <span class="value">{{ number_format($total_waste, 2) }} kg</span>
        </div>
    </div>
</div>

<div class="section">
    <h2>Checklist Compliance</h2>
    @php $summary = $event->getChecklistSummary(); @endphp
    <table>
        <thead>
            <tr>
                <th>Phase</th>
                <th>Total Items</th>
                <th>Completed</th>
                <th>Compliance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->eventChecklists->groupBy('checklist.phase.name') as $phase => $items)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $phase)) }}</td>
                <td>{{ $items->count() }}</td>
                <td>{{ $items->where('status', 'completed')->count() }}</td>
                <td>
                    <span class="badge badge-{{ $items->where('status', 'completed')->count() == $items->count() ? 'success' : 'warning' }}">
                        {{ $items->count() > 0 ? round(($items->where('status', 'completed')->count() / $items->count()) * 100) : 0 }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="section">
    <h2>Declaration</h2>
    <p>This report confirms that the event <strong>{{ $event->event_name }}</strong> has been managed in accordance with ISO 20121 sustainable event management principles.</p>
    <p>All data has been recorded and verified by Lima Deria Sdn Bhd.</p>
    <br>
    <table>
        <tr>
            <td style="width: 50%;">
                <strong>Prepared by:</strong><br>
                {{ $generated_by }}<br>
                <small>Date: {{ $generated_at }}</small>
            </td>
            <td style="width: 50%;">
                <strong>Verified by:</strong><br>
                _____________________<br>
                <small>Date: _____________</small>
            </td>
        </tr>
    </table>
</div>
@endsection