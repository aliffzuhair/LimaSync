@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">ISO 20121 Compliance Report</div>
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
        <tr>
            <th>ISO Standard</th>
            <td colspan="3">ISO 20121:2012 — Event Sustainability Management Systems</td>
        </tr>
    </table>
</div>

{{-- ISO Compliance Metrics --}}
<div class="section">
    <h2>ISO Compliance Metrics</h2>
    <div class="summary-box">
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="label">Carbon Footprint</div>
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

{{-- Checklist Compliance --}}
<div class="section">
    <h2>Checklist Compliance by Phase</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Phase</th>
                <th style="width: 20%;" class="text-center">Total Items</th>
                <th style="width: 20%;" class="text-center">Completed</th>
                <th style="width: 30%;" class="text-center">Compliance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->eventChecklists->groupBy('checklist.phase.name') as $phase => $items)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $phase)) }}</td>
                <td class="text-center">{{ $items->count() }}</td>
                <td class="text-center">{{ $items->where('status', 'completed')->count() }}</td>
                <td class="text-center">
                    <span class="badge badge-{{ 
                        $items->where('status', 'completed')->count() == $items->count() ? 'success' : 'warning' 
                    }}">
                        {{ $items->count() > 0 ? round(($items->where('status', 'completed')->count() / $items->count()) * 100) : 0 }}%
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Declaration --}}
<div class="section">
    <h2>Declaration</h2>
    <div class="declaration">
        <p>This report confirms that the event <strong>{{ $event->event_name }}</strong> has been managed in accordance with <strong>ISO 20121</strong> sustainable event management principles.</p>
        <p>All data has been recorded and verified by <strong>Lima Deria Sdn Bhd</strong>.</p>

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
                    <strong>Verified by:</strong>
                    <div class="signature-line">
                        _____________________<br>
                        Date: _____________
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection