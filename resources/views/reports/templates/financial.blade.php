@extends('reports.templates.layout')

@section('content')
<div class="header">
    <div class="brand">LimaSync</div>
    <h1>{{ $title }}</h1>
    <div class="subtitle">{{ $event->event_name }} - Financial Report</div>
</div>

<div class="section">
    <h2>Event Information</h2>
    <table>
        <tr><th style="width: 30%;">Event Name</th><td>{{ $event->event_name }}</td></tr>
        <tr><th>Client</th><td>{{ $event->client->company_name ?? 'N/A' }}</td></tr>
        <tr><th>Date</th><td>{{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}</td></tr>
        <tr><th>Budget</th><td>RM {{ number_format($event->budget ?? 0, 2) }}</td></tr>
    </table>
</div>

<div class="section">
    <h2>Financial Summary</h2>
    <div class="summary-box">
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
    <h2>Transaction Details</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Category</th>
                <th>Type</th>
                <th class="text-right">Amount (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($event->finances as $finance)
            <tr>
                <td>{{ $finance->transaction_date->format('d M Y') }}</td>
                <td>{{ $finance->description }}</td>
                <td>{{ $finance->category }}</td>
                <td>
                    <span class="badge badge-{{ $finance->transaction_type == 'income' ? 'success' : 'danger' }}">
                        {{ ucfirst($finance->transaction_type) }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($finance->amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection