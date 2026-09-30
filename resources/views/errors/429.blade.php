@extends('layouts.app')

@section('title', 'Too Many Requests - LimaSync')

@section('content')
<div class="row justify-content-center" style="margin-top: 5rem;">
    <div class="col-md-6 col-lg-5">
        <div class="card text-center">
            <div class="card-body py-5">

                {{-- Icon --}}
                <div class="mb-4">
                    <i class="fas fa-shield-alt" style="font-size: 5rem; color: #dc3545;"></i>
                </div>

                {{-- Error Code --}}
                <h1 class="display-1 fw-bold mb-2" 
                    style="color: #dc3545; font-size: 5rem; border: none; padding: 0;">
                    429
                </h1>

                {{-- Title --}}
                <h3 class="mb-3">Too Many Requests</h3>

                {{-- Message --}}
                <p class="text-muted mb-4" style="font-size: 1rem;">
                    You've made too many login attempts in a short period of time. 
                    For security reasons, please wait a moment before trying again.
                </p>

                {{-- Info Box --}}
                <div class="alert" 
                     style="background-color: rgba(181, 196, 1, 0.15); 
                            border: 1px solid #B5C401; 
                            color: #1B1B1B; 
                            border-radius: 8px;">
                    <i class="fas fa-info-circle" style="color: #B5C401;"></i>
                    <strong>Brute-force protection is active.</strong>
                    <br>
                    <small>Wait 60 seconds, then try again.</small>
                </div>

                {{-- Countdown Timer --}}
                <div class="mb-4">
                    <div id="countdown" 
                         class="badge" 
                         style="background-color: #1B1B1B; 
                                color: #B5C401; 
                                font-size: 1.5rem; 
                                padding: 10px 25px;">
                        Try again in <span id="timer">60</span>s
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" 
                       class="btn btn-primary"
                       id="retry-btn"
                       style="pointer-events: none; opacity: 0.5;">
                        <i class="fas fa-redo"></i> Try Again
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>

            </div>
        </div>

        {{-- Help Text --}}
        <div class="text-center mt-4">
            <small class="text-muted">
                <i class="fas fa-lock"></i> 
                This is a security feature to protect your account from unauthorized access.
            </small>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let seconds = 60;
        const timerEl = document.getElementById('timer');
        const retryBtn = document.getElementById('retry-btn');

        const interval = setInterval(function() {
            seconds--;
            timerEl.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(interval);
                // Enable retry button
                retryBtn.style.pointerEvents = 'auto';
                retryBtn.style.opacity = '1';
                retryBtn.innerHTML = '<i class="fas fa-redo"></i> Try Again';
                document.getElementById('countdown').innerHTML = 
                    '<span style="color: #B5C401;">✓ You can try again now</span>';
            }
        }, 1000);
    });
</script>
@endpush
@endsection