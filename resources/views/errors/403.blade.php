@extends('layouts.app')

@section('title', 'Access Denied - LimaSync')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body py-5">
                <i class="fas fa-lock fa-5x text-danger mb-4"></i>
                <h1 class="display-4">403</h1>
                <h3>Access Denied</h3>
                <p class="text-muted">
                    You do not have permission to access this page. 
                    This area is restricted to administrators only.
                </p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-home"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection