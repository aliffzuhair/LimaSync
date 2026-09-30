@extends('layouts.app')

@section('title', 'Finance Dashboard - LimaSync')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Finance Dashboard</h1>
        <p class="mb-4">Welcome back, {{ $user->full_name ?? $user->name }}!</p>
    </div>
</div>

<!-- Financial Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-arrow-down"></i> Total Income</h5>
                <h2 class="card-text">RM {{ number_format($totalIncome, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-arrow-up"></i> Total Expenses</h5>
                <h2 class="card-text">RM {{ number_format($totalExpense, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white {{ $netProfit >= 0 ? 'bg-primary' : 'bg-warning' }}">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chart-line"></i> Net Profit</h5>
                <h2 class="card-text">RM {{ number_format($netProfit, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Events to Manage Finances -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="color: white"><i class="fas fa-calendar"></i> Events - Manage Finances</h5>
        <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-light">
            View All <i class="fas fa-arrow-right"></i>
        </a>
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
                            <th>Budget</th>
                            <th>Income</th>
                            <th>Expense</th>
                            <th>Net Profit</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td><strong>{{ $event->event_name }}</strong></td>
                            <td>{{ $event->client->company_name ?? 'N/A' }}</td>
                            <td>{{ $event->start_date->format('d M Y') }}</td>
                            <td>RM {{ number_format($event->budget ?? 0, 2) }}</td>
                            <td class="text-success">
                                RM {{ number_format($event->total_income, 2) }}
                            </td>
                            <td class="text-danger">
                                RM {{ number_format($event->total_expense, 2) }}
                            </td>
                            <td class="{{ $event->net_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                <strong>RM {{ number_format($event->net_profit, 2) }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('finance.event', $event) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-money-bill-wave"></i> Manage Finance
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center mb-0">No events found.</p>
        @endif
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0" style="color: white"><i class="fas fa-history"></i> Recent Transactions</h5>
    </div>
    <div class="card-body">
        @if($recentTransactions->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Event</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                            <td>
                                @if($transaction->event)
                                    <a href="{{ route('finance.event', $transaction->event) }}">
                                        {{ $transaction->event->event_name }}
                                    </a>
                                @endif
                            </td>
                            <td>{{ $transaction->description }}</td>
                            <td>
                                <span class="badge bg-{{ $transaction->type_badge }}">
                                    {{ ucfirst($transaction->transaction_type) }}
                                </span>
                            </td>
                            <td class="{{ $transaction->transaction_type == 'income' ? 'text-success' : 'text-danger' }}">
                                <strong>RM {{ number_format($transaction->amount, 2) }}</strong>
                            </td>
                            <td>
                                @if($transaction->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted text-center mb-0">No transactions yet.</p>
        @endif
    </div>
</div>
@endsection