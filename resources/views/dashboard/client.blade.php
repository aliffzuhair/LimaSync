@extends('layouts.app')

@section('title', 'Client Dashboard - LimaSync')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Client Dashboard</h1>
        <p class="mb-4">Welcome back, {{ $user->full_name ?? $user->name }}!</p>
    </div>
</div>

<!-- Event Progress Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-calendar-check"></i> Total Events</h5>
                <h2 class="card-text">{{ $allEvents->count() }}</h2>
                <small>All events</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-spinner"></i> In Progress</h5>
                <h2 class="card-text">{{ $allEvents->where('status', 'in_progress')->count() }}</h2>
                <small>Currently active</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-check-circle"></i> Completed</h5>
                <h2 class="card-text">{{ $allEvents->where('status', 'completed')->count() }}</h2>
                <small>Successfully done</small>
            </div>
        </div>
    </div>
</div>

{{-- Top Row: Event Progress + Overall Progress --}}
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-tasks"></i> Event Progress</h5>
            </div>
            <div class="card-body">
                @if($allEvents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-clickable">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th style="width: 200px;">Progress</th>
                                    <th>Status</th>
                                    <th style="width: 30px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allEvents as $event)
                                <tr onclick="window.location='{{ route('client.reports.index', $event) }}'">
                                    <td>
                                        <strong>{{ $event->event_name }}</strong>
                                    </td>
                                    <td>{{ $event->client->company_name ?? 'N/A' }}</td>
                                    <td>{{ $event->start_date->format('d M Y') }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ 
                                                $event->progress_percentage == 100 ? 'success' : 
                                                ($event->progress_percentage >= 50 ? 'warning' : 'danger') 
                                            }}" 
                                                role="progressbar" 
                                                style="width: {{ $event->progress_percentage }}%">
                                                {{ $event->progress_percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $event->status == 'completed' ? 'success' : 
                                            ($event->status == 'in_progress' ? 'warning' : 'secondary') 
                                        }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center arrow-cell">
                                        <i class="fas fa-arrow-right arrow-icon"></i>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No events available yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-chart-line"></i> Overall Progress</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <div style="position: relative; height: 180px; width: 180px;">
                    <canvas id="progressOverviewChart"></canvas>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                        <h2 class="mb-0 fw-bold text-{{ $averageProgress == 100 ? 'success' : ($averageProgress >= 50 ? 'warning' : 'danger') }}" 
                            style="font-size: 2.2rem;">
                            {{ $averageProgress }}%
                        </h2>
                    </div>
                </div>
                <p class="text-muted mb-0 mt-3 text-center">
                    Average progress across
                    <br>
                    <strong>{{ $totalEvents }}</strong> event{{ $totalEvents != 1 ? 's' : '' }}
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Sustainability Cards Row (no gap needed) --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-secondary h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-leaf"></i> Carbon Footprint</h5>
                <h2 class="card-text">{{ number_format($sustainabilityData['carbon'], 2) }}</h2>
                <small>kg CO₂</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-bolt"></i> Electricity</h5>
                <h2 class="card-text">{{ number_format($sustainabilityData['electricity'], 2) }}</h2>
                <small>kWh</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-tint"></i> Water</h5>
                <h2 class="card-text">{{ number_format($sustainabilityData['water'], 2) }}</h2>
                <small>m³</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-trash"></i> Waste</h5>
                <h2 class="card-text">{{ number_format($sustainabilityData['waste'], 2) }}</h2>
                <small>kg</small>
            </div>
        </div>
    </div>
</div>

<!-- Sustainability Charts Row -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-chart-bar"></i> Carbon & Electricity by Event</h5>
            </div>
            <div class="card-body">
                <canvas id="carbonChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0" style="color: white"><i class="fas fa-chart-pie"></i> Sustainability Breakdown</h5>
            </div>
            <div class="card-body">
                <canvas id="breakdownChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Events with Sustainability Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0" style="color: white"><i class="fas fa-calendar-check"></i> Events with Sustainability Data</h5>
    </div>
    <div class="card-body">
        @if($events->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Carbon (kg CO₂)</th>
                            <th>Electricity (kWh)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td>{{ $event->event_name }}</td>
                            <td>{{ $event->client->company_name ?? 'N/A' }}</td>
                            <td>{{ $event->start_date->format('d M Y') }}</td>
                            <td>{{ number_format($event->total_carbon, 2) }}</td>
                            <td>{{ number_format($event->total_electricity, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $event->sustainability_verified ? 'success' : 'warning' }}">
                                    {{ $event->sustainability_verified ? 'Verified' : 'Pending' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No verified sustainability data available yet.</p>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ==========================================
        // Overall Progress Chart (Gauge Style)
        // ==========================================
        const progressOverviewCtx = document.getElementById('progressOverviewChart');
        if (progressOverviewCtx) {
            const averageProgress = {{ $averageProgress }};
            const remaining = 100 - averageProgress;

            // Choose color based on progress
            let progressColor = '#dc3545'; // red
            if (averageProgress >= 100) progressColor = '#28a745'; // green
            else if (averageProgress >= 50) progressColor = '#B5C401'; // lime
            else if (averageProgress >= 25) progressColor = '#ffc107'; // yellow

            new Chart(progressOverviewCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [averageProgress, remaining],
                        backgroundColor: [progressColor, '#E9ECEF'],
                        borderWidth: 0,
                        hoverOffset: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '78%',
                    rotation: -90,
                    circumference: 360,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataIndex === 0 
                                        ? 'Progress: ' + context.parsed + '%' 
                                        : 'Remaining: ' + context.parsed + '%';
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: false,
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // ==========================================
        // Carbon & Electricity Chart
        // ==========================================
        const carbonCtx = document.getElementById('carbonChart');
        if (carbonCtx) {
            new Chart(carbonCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($chartData['labels']),
                    datasets: [
                        {
                            label: 'Carbon (kg CO₂)',
                            data: @json($chartData['carbon']),
                            backgroundColor: 'rgba(27, 27, 27, 0.7)',
                            borderColor: '#1B1B1B',
                            borderWidth: 1
                        },
                        {
                            label: 'Electricity (kWh)',
                            data: @json($chartData['electricity']),
                            backgroundColor: 'rgba(181, 196, 1, 0.7)',
                            borderColor: '#B5C401',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // ==========================================
        // Sustainability Breakdown Doughnut Chart
        // ==========================================
        const breakdownCtx = document.getElementById('breakdownChart');
        if (breakdownCtx) {
            new Chart(breakdownCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Carbon', 'Electricity', 'Water', 'Waste'],
                    datasets: [{
                        data: [
                            {{ $sustainabilityData['carbon'] }},
                            {{ $sustainabilityData['electricity'] }},
                            {{ $sustainabilityData['water'] }},
                            {{ $sustainabilityData['waste'] }}
                        ],
                        backgroundColor: [
                            'rgba(27, 27, 27, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(23, 162, 184, 0.8)',
                            'rgba(108, 117, 125, 0.8)'
                        ],
                        borderColor: [
                            '#1B1B1B',
                            '#ffc107',
                            '#17a2b8',
                            '#6c757d'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    });
</script>
@endpush