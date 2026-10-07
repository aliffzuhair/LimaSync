@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">Sustainability Report</div>
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
            <th>Date</th>
            <td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td>
            <th>Venue</th>
            <td>{{ $event->venue ?? 'N/A' }}</td>
        </tr>
    </table>
</div>

{{-- Sustainability Summary --}}
<div class="section">
    <h2>Sustainability Summary</h2>
    <div class="summary-box">
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="label">Carbon Footprint</div>
                    <div class="value">{{ number_format($total_carbon, 2) }}</div>
                    <div class="unit">kg CO₂</div>
                </td>
                <td>
                    <div class="label">Electricity Usage</div>
                    <div class="value">{{ number_format($total_electricity, 2) }}</div>
                    <div class="unit">kWh</div>
                </td>
                <td>
                    <div class="label">Water Usage</div>
                    <div class="value">{{ number_format($total_water, 2) }}</div>
                    <div class="unit">m³</div>
                </td>
                <td>
                    <div class="label">Waste Generated</div>
                    <div class="value">{{ number_format($total_waste, 2) }}</div>
                    <div class="unit">kg</div>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- Detailed Metrics --}}
<div class="section">
    <h2>Detailed Metrics</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 15%;">Type</th>
                <th style="width: 25%;">Metric</th>
                <th style="width: 12%;" class="text-right">Value</th>
                <th style="width: 10%;">Unit</th>
                <th style="width: 13%;">Date</th>
                <th style="width: 10%;">Source</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($event->sustainability as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <span class="badge badge-{{ 
                        $item->metric_type == 'carbon' ? 'dark' : 
                        ($item->metric_type == 'electricity' ? 'warning' : 
                        ($item->metric_type == 'water' ? 'info' : 'secondary')) 
                    }}">
                        {{ ucfirst($item->metric_type) }}
                    </span>
                </td>
                <td>{{ $item->metric_name }}</td>
                <td class="text-right">{{ number_format((float) $item->value, 2) }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->measurement_date->format('d M Y') }}</td>
                <td>{{ $item->source ?? '-' }}</td>
                <td>
                    @if($item->is_verified)
                        <span class="badge badge-success">Verified</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No sustainability data recorded.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection