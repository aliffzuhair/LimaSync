@extends('layouts.app')

@section('styles')
<style>
    body {
        background-color: #F8F5F0;
        font-family: 'Montserrat', sans-serif;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        background-color: #FFFFFF;
    }
    .card-header {
        background-color: #1B1B1B;
        color: #FFFFFF;
        font-weight: 700;
        border-radius: 12px 12px 0 0 !important;
        border-bottom: 4px solid #B5C401;
        text-align: center;
        font-size: 1.5rem;
        padding: 1.5rem;
    }
    .btn-primary {
        background-color: #B5C401;
        border-color: #B5C401;
        color: #1B1B1B;
        font-weight: 600;
        border-radius: 50px;
        padding: 0.6rem 2rem;
        width: 100%;
    }
    .btn-primary:hover {
        background-color: #A0B000;
        border-color: #A0B000;
        color: #1B1B1B;
    }
    .form-control:focus {
        border-color: #B5C401;
        box-shadow: 0 0 0 0.2rem rgba(181, 196, 1, 0.25);
    }
    .text-lime {
        color: #B5C401 !important;
    }
    .login-icon {
        font-size: 3rem;
        color: #B5C401;
        display: block;
        text-align: center;
        margin-bottom: 1rem;
    }
    .auth-links {
        text-align: center;
        margin-top: 1rem;
    }
    .auth-links a {
        color: #1B1B1B;
        text-decoration: none;
        font-weight: 400;
        transition: color 0.3s ease;
    }
    .auth-links a:hover {
        color: #B5C401;
    }
    .form-label {
        font-weight: 600;
        color: #1B1B1B;
    }
    .form-check-label {
        font-weight: 400;
        color: #1B1B1B;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-check login-icon"></i>
                LimaSync
            </div>
            <div class="card-body p-4">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt"></i> {{ __('Log In') }}
                        </button>
                    </div>

                    <div class="auth-links">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Brand Footer -->
        <div class="text-center mt-4">
            <small class="text-muted"> 
                LimaSync — Built for Lima Deria Sdn Bhd
            </small>
            <br>
            <small class="text-muted">
                <a href="https://limaderia.com" target="_blank" class="text-lime" style="text-decoration: none;">
                    Spectacular Sustainable Events
                </a>
            </small>
        </div>
    </div>
</div>
@endsection