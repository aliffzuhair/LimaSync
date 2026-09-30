@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">{{ $event->event_name }} - Sustainability Report</div>
</div>

<div class="section">
    <h2>Event Information</h2>
    <table>
        <tr><th style="width: 30%;">Event Name</th><td>{{ $event->event_name }}</td></tr>
        <tr><th>Client</th><td>{{ $event->client->company_name ?? 'N/A' }}</td></tr>
        <tr><th>Date</th><td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td></tr>
        <tr><th>Venue</th><td>{{ $event->venue ?? 'N/A' }}</td></tr>
    </table>
</div>

<div class="section">
    <h2>Sustainability Summary</h2>
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
    <h2>Detailed Metrics</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Metric</th>
                <th class="text-right">Value</th>
                <th>Unit</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->sustainability as $item)
            <tr>
                <td>
                    <span class="badge badge-{{ $item->metric_type == 'carbon' ? 'dark' : ($item->metric_type == 'electricity' ? 'warning' : ($item->metric_type == 'water' ? 'info' : 'secondary')) }}">
                        {{ ucfirst($item->metric_type) }}
                    </span>
                </td>
                <td>{{ $item->metric_name }}</td>
                <td class="text-right">{{ number_format($item->value, 2) }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->measurement_date->format('d M Y') }}</td>
                <td>
                    <span class="badge badge-{{ $item->is_verified ? 'success' : 'warning' }}">
                        {{ $item->is_verified ? 'Verified' : 'Pending' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection