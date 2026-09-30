@extends('layouts.app')

@section('title', 'Sales Dashboard - LimaSync')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Sales Dashboard</h1>
        <p class="mb-4">Welcome back, {{ $user->full_name ?? $user->name }}!</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Clients</h5>
                <h2 class="card-text">{{ \App\Models\Client::count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Active Clients</h5>
                <h2 class="card-text">{{ \App\Models\Client::where('is_active', true)->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fas fa-users"></i> Recent Clients
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Client::latest()->take(5)->get() as $client)
                    <tr>
                        <td>{{ $client->company_name }}</td>
                        <td>{{ $client->contact_person }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No clients found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection