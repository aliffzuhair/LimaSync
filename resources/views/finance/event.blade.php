@extends('layouts.app')

@section('title', 'Finance - ' . $event->event_name . ' - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>{{ $event->event_name }} - Finance</h1>
        <p class="mb-4">
            <i class="fas fa-users"></i> {{ $event->client->company_name ?? 'N/A' }} &nbsp;|&nbsp;
            <i class="fas fa-calendar"></i> {{ $event->start_date->format('d M Y') }}
        </p>
    </div>
    <div>
        <a href="{{ route('finance.create', $event) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Transaction
        </a>
        <a href="{{ route('events.show', $event) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Event
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Income</h5>
                <h2 class="card-text">RM {{ number_format($totalIncome, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">Total Expenses</h5>
                <h2 class="card-text">RM {{ number_format($totalExpense, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white {{ $netProfit >= 0 ? 'bg-primary' : 'bg-warning' }}">
            <div class="card-body">
                <h5 class="card-title">Net Profit</h5>
                <h2 class="card-text">RM {{ number_format($netProfit, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Budget Progress -->
@if($event->budget)
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-0">Budget Utilization</h5>
        <small class="text-muted">
            RM {{ number_format($event->total_expense, 2) }} spent of RM {{ number_format($event->budget, 2) }}
            ({{ $event->budget_utilization }}%)
        </small>
        <div class="progress mt-2" style="height: 25px;">
            <div class="progress-bar bg-{{ $event->budget_utilization > 100 ? 'danger' : ($event->budget_utilization > 80 ? 'warning' : 'success') }}" 
                 role="progressbar" 
                 style="width: {{ min($event->budget_utilization, 100) }}%">
                {{ $event->budget_utilization }}%
            </div>
        </div>
    </div>
</div>
@endif

<!-- Transactions Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Transactions</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($finances as $finance)
                    <tr>
                        <td>{{ $finance->transaction_date->format('d M Y') }}</td>
                        <td>{{ $finance->description }}</td>
                        <td>{{ $finance->category }}</td>
                        <td>
                            <span class="badge bg-{{ $finance->type_badge }}">
                                {{ ucfirst($finance->transaction_type) }}
                            </span>
                        </td>
                        <td class="{{ $finance->transaction_type == 'income' ? 'text-success' : 'text-danger' }}">
                            <strong>RM {{ number_format($finance->amount, 2) }}</strong>
                        </td>
                        <td>
                            @if($finance->is_approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('finance.edit', $finance) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('finance.destroy', $finance) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this transaction?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                                @if(!$finance->is_approved)
                                    <form action="{{ route('finance.approve', $finance) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No transactions yet. <a href="{{ route('finance.create', $event) }}">Add one</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection