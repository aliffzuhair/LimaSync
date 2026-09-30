@extends('layouts.app')

@section('title', 'Dashboard - LimaSync')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Dashboard</h1>
        <p class="text-muted">Welcome back, {{ $user->full_name ?? $user->name }}!</p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-calendar-check fa-4x text-primary mb-3"></i>
                <h3>LimaSync</h3>
                <p class="text-muted">Your integrated event management portal</p>
                <hr>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <a href="{{ route('clients.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users"></i> Clients
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('events.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-calendar"></i> Events
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-warning w-100">
                            <i class="fas fa-box"></i> Inventory
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-outline-danger w-100">
                            <i class="fas fa-file-alt"></i> Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection