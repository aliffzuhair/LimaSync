@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">Financial Report</div>
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
            <th>Budget</th>
            <td>RM {{ number_format($event->budget ?? 0, 2) }}</td>
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

{{-- Transaction Details --}}
<div class="section">
    <h2>Transaction Details</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 12%;">Date</th>
                <th style="width: 30%;">Description</th>
                <th style="width: 18%;">Category</th>
                <th style="width: 10%;">Type</th>
                <th style="width: 15%;" class="text-right">Amount (RM)</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($event->finances as $index => $finance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $finance->transaction_date->format('d M Y') }}</td>
                <td>{{ $finance->description }}</td>
                <td>{{ $finance->category }}</td>
                <td>
                    <span class="badge badge-{{ $finance->transaction_type == 'income' ? 'success' : 'danger' }}">
                        {{ ucfirst($finance->transaction_type) }}
                    </span>
                </td>
                <td class="text-right">{{ number_format((float) $finance->amount, 2) }}</td>
                <td>
                    @if($finance->is_approved)
                        <span class="badge badge-success">Approved</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No transactions recorded.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Total</strong></td>
                <td class="text-right">
                    <strong class="{{ $net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($net_profit, 2) }}
                    </strong>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection