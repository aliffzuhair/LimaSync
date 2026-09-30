@extends('layouts.app')

@section('title', 'Page Not Found - LimaSync')

@section('content')
<div class="row justify-content-center" style="margin-top: 5rem;">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body py-5">
                <i class="fas fa-search" style="font-size: 5rem; color: #B5C401;"></i>
                <h1 class="display-1 fw-bold mt-3" style="color: #B5C401;">404</h1>
                <h3>Page Not Found</h3>
                <p class="text-muted">
                    The page you're looking for doesn't exist or has been moved.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-home"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection