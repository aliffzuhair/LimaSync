@extends('layouts.app')

@section('title', 'Finance Dashboard - LimaSync')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Finance Dashboard</h1>
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

<!-- Recent Transactions -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Recent Transactions</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Event</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Entered By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date->format('d M Y') }}</td>
                        <td>
                            @if($transaction->event)
                                <a href="{{ route('finance.event', $transaction->event) }}">
                                    {{ $transaction->event->event_name }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->category }}</td>
                        <td>
                            <span class="badge bg-{{ $transaction->type_badge }}">
                                {{ ucfirst($transaction->transaction_type) }}
                            </span>
                        </td>
                        <td class="{{ $transaction->transaction_type == 'income' ? 'text-success' : 'text-danger' }}">
                            <strong>RM {{ number_format($transaction->amount, 2) }}</strong>
                        </td>
                        <td>{{ $transaction->enteredBy->full_name ?? 'N/A' }}</td>
                        <td>
                            @if($transaction->is_approved)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('finance.edit', $transaction) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No transactions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $transactions->links() }}
    </div>
</div>
@endsection