<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LimaSync')</title>

    <!-- Google Fonts: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Global Styles */
        body {
            font-family: 'Montserrat', sans-serif;
            background-image: url("{{ asset('image/bg.jpg') }}");
            background-size: cover;          /* fills the whole viewport */
            background-position: center;     /* centers the image */
            background-repeat: no-repeat;    /* no tiling */
            background-attachment: fixed;    /* image stays put while scrolling */
            color: #1B1B1B;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar-custom {
        /* Semi-transparent dark background instead of solid #1B1B1B */
            background: linear-gradient(
                180deg,
                #1F4D3D 0%,
                #2E8B5F 60%,
                #3FBF7F 100%
            ) !important;
            
            /* The frosted glass blur effect */
            backdrop-filter: blur(12px) saturate(180%);
            -webkit-backdrop-filter: blur(12px) saturate(180%);
            
            /* Subtle inner border to simulate glass edge */
            border-bottom: 1px solid rgba(181, 196, 1, 0.35);
            
            /* Soft glow / depth */
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.35),
                        inset 0 1px 0 rgba(255, 255, 255, 0.08);
            
            padding: 1rem 0;
            
            /* Keeps it sticky-friendly if you use position:sticky/fixed */
            position: sticky;
            top: 0;
            z-index: 1030;
            
            transition: background-color 0.3s ease, backdrop-filter 0.3s ease;
        }

        /* Brand */
        .navbar-custom .navbar-brand {
            font-weight: 700;
            color: #B5C401 !important;
            font-size: 1.6rem;
            letter-spacing: -0.5px;
            text-shadow: 0 0 12px rgba(181, 196, 1, 0.35);
        }

        .navbar-custom .navbar-brand i {
            color: #B5C401;
        }

        /* Nav links */
        .navbar-custom .nav-link {
            color: #FFFFFF !important;
            font-weight: 400;
            font-size: 0.95rem;
            padding: 0.5rem 1.2rem !important;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link:focus {
            color: #B5C401 !important;
            background-color: rgba(181, 196, 1, 0.12);
            box-shadow: 0 0 18px rgba(181, 196, 1, 0.15);
        }

        .navbar-custom .nav-link i {
            margin-right: 8px;
            color: #B5C401;
        }

        /* Toggler */
        .navbar-custom .navbar-toggler {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        .navbar-custom .navbar-toggler:focus,
        .navbar-custom .navbar-toggler:active {
            outline: none !important;
            box-shadow: none !important;
            border: none !important;
        }

        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%233A3F4A' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
            color: #3A3F4A;
        }       


        /* Dropdown */
        .dropdown-menu-custom {
            background-color: #1B1B1B;
            border: 1px solid #B5C401;
            border-radius: 8px;
        }

        .dropdown-menu-custom .dropdown-item {
            color: #F8F5F0;
            font-weight: 400;
            padding: 0.6rem 1.5rem;
        }

        .dropdown-item-text {
            color: #F8F5F0;
        }

        .dropdown-menu-custom .dropdown-item:hover {
            background-color: #B5C401;
            color: #1B1B1B;
        }

        .dropdown-menu-custom .dropdown-divider {
            border-color: #B5C401;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            background-color: #FFFFFF;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card-header {
            background-color: #1B1B1B;
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 12px 12px 0 0 !important;
            border-bottom: 3px solid #B5C401;
        }

        .card-header i {
            color: #B5C401;
            margin-right: 10px;
        }

        /* Buttons */
        .btn-primary {
            background-color: #B5C401;
            border-color: #B5C401;
            color: #1B1B1B;
            font-weight: 600;
            padding: 0.6rem 1.8rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #A0B000;
            border-color: #A0B000;
            color: #1B1B1B;
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(181, 196, 1, 0.4);
        }

        .btn-secondary {
            background-color: #1B1B1B;
            border-color: #1B1B1B;
            color: #FFFFFF;
            font-weight: 600;
            padding: 0.6rem 1.8rem;
            border-radius: 50px;
        }

        .btn-secondary:hover {
            background-color: #333333;
            border-color: #333333;
            color: #FFFFFF;
        }

        .btn-outline-primary {
            color: #B5C401;
            border-color: #B5C401;
            border-width: 2px;
            font-weight: 600;
            border-radius: 50px;
        }

        .btn-outline-primary:hover {
            background-color: #B5C401;
            border-color: #B5C401;
            color: #1B1B1B;
        }

        /* ─── Action buttons (row of icons) ─────────────────── */
        .action-buttons {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-buttons form {
            margin: 0;                /* kill default form margin */
            display: inline-flex;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            padding: 0;
            border: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            cursor: pointer;
            background-color: transparent;
            color: #6c757d;
            transition: all 0.18s ease;
        }

        /* Soft tinted backgrounds — subtle color, not shouting */
        .btn-action.btn-view    { background-color: rgba(13, 202, 240, 0.12); color: #0dcaf0; }
        .btn-action.btn-edit    { background-color: rgba(255, 193, 7, 0.15); color: #b8860b; }
        .btn-action.btn-toggle  { background-color: rgba(27, 27, 27, 0.08);  color: #1B1B1B; }
        .btn-action.btn-delete  { background-color: rgba(220, 53, 69, 0.12); color: #dc3545; }

        /* Hover — slightly stronger tint + lift */
        .btn-action:hover {
            transform: translateY(-1px);
        }

        .btn-action.btn-view:hover    { background-color: rgba(13, 202, 240, 0.25); }
        .btn-action.btn-edit:hover    { background-color: rgba(255, 193, 7, 0.3); }
        .btn-action.btn-toggle:hover  { background-color: rgba(27, 27, 27, 0.18); }
        .btn-action.btn-delete:hover  { background-color: rgba(220, 53, 69, 0.25); }

        /* Focus ring — on brand */
        .btn-action:focus,
        .btn-action:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(181, 196, 1, 0.35);
        }

        /* Alerts */
        .alert-success {
            background-color: #B5C401;
            border-color: #B5C401;
            color: #1B1B1B;
            border-radius: 8px;
        }

        .alert-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #FFFFFF;
            border-radius: 8px;
        }

        /* Tables */
        .table {
            color: #1B1B1B;
        }

        .table thead th {
            background-color: #1B1B1B;
            color: #FFFFFF;
            font-weight: 600;
            border-bottom: 3px solid #B5C401;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(181, 196, 1, 0.08);
        }

        /* Clickable Table Rows - Fixed */
        .table-clickable tbody .row-arrow-cell {
            width: 30px;
            text-align: center;
            padding-left: 0;
            padding-right: 12px;
        }

        .table-clickable tbody .row-arrow {
            display: inline-block;
            color: #B5C401;
            font-weight: bold;
            font-size: 16px;
            opacity: 0;
            transform: translateX(-6px);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .table-clickable tbody tr:hover .row-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .table-clickable tbody tr {
            cursor: pointer;
            transition: background-color 0.15s ease;
            }

        .table-clickable tbody tr:hover {
            background-color: rgba(181, 196, 1, 0.08);   /* lime tint on hover */
        }

        /* Locked Phase Styling */
        .phase-locked {
            opacity: 0.7;
            position: relative;
        }

        .phase-locked .card-header {
            background-color: #4a4a4a;
            cursor: not-allowed;
        }

        .phase-locked .btn:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .phase-locked .table {
            background-color: #f9f9f9;
        }

        .phase-locked .card-body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.05);
            pointer-events: none;
            z-index: 1;
        }

        .phase-locked .card-body {
            position: relative;
        }

        /* Make Locked badge stand out */
        .badge.bg-danger {
            background-color: #dc3545 !important;
            color: #ffffff !important;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
            color: #ffffff !important;
        }

        /* Badges */
        .badge-success {
            background-color: #B5C401;
            color: #1B1B1B;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: #FFFFFF;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #1B1B1B;
        }

        .badge-danger {
            background-color: #dc3545;
            color: #FFFFFF;
        }

        .badge-info {
            background-color: #17a2b8;
            color: #FFFFFF;
        }

        /* Progress Bars */
        .progress {
            background-color: #E9ECEF;
            border-radius: 50px;
            height: 20px;
        }

        .progress-bar {
            background-color: #B5C401;
            color: #1B1B1B;
            font-weight: 600;
            font-size: 0.75rem;
            line-height: 20px;
            border-radius: 50px;
        }

        /* Pagination */
        .page-link {
            color: #1B1B1B;
            border-color: #B5C401;
        }

        .page-link:hover {
            background-color: #B5C401;
            color: #1B1B1B;
            border-color: #B5C401;
        }

        .page-item.active .page-link {
            background-color: #B5C401;
            border-color: #B5C401;
            color: #1B1B1B;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border-radius: 8px;
            border: 2px solid #E9ECEF;
            padding: 0.6rem 1rem;
            font-family: 'Montserrat', sans-serif;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #B5C401;
            box-shadow: 0 0 0 0.2rem rgba(181, 196, 1, 0.25);
        }

        /* Headers */
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            color: #1B1B1B;
        }

        h1 {
            border-left: 5px solid #B5C401;
            padding-left: 15px;
        }

        /* Footer */
        .footer-custom {
            background-color: #1B1B1B;
            color: #F8F5F0;
            padding: 1rem 0;
            margin-top: 3rem;
            border-top: 4px solid #B5C401;
        }

        .footer-custom a {
            color: #B5C401;
            text-decoration: none;
        }

        .footer-custom a:hover {
            color: #FFFFFF;
        }

        /* Lime Green Highlights */
        .text-lime {
            color: #B5C401 !important;
        }

        .bg-lime {
            background-color: #B5C401 !important;
        }

        .border-lime {
            border-color: #B5C401 !important;
        }

        /* Override Bootstrap's text-muted */
        .text-muted {
            color: #4a4a4a !important;
        }

        .text-muted-white{
            color: #ffffff !important;
        }
    </style>

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('image/LimaDeriaLogo.png') }}" alt="LimaDeria Logo" style="height: 40px; width: auto;"> 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="color: #B5C401">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-home" style="color: #3A3F4A"></i> Dashboard
                        </a>
                    </li>
                    @auth
                        {{-- Clients: Admin + Operations + Sales --}}
                        @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'operations', 'sales']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clients.index') }}">
                                <i class="fas fa-users" style="color: #3A3F4A"></i> Clients
                            </a>
                        </li>
                        @endif

                        {{-- Events: Admin + Operations + Finance + Logistics --}}
                        @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'operations', 'finance', 'logistics']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('events.index') }}">
                                <i class="fas fa-calendar" style="color: #3A3F4A"></i> Events
                            </a>
                        </li>
                        @endif

                        {{-- Inventory: Admin + Logistics --}}
                        @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'logistics']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('inventory.index') }}">
                                <i class="fas fa-boxes" style="color: #3A3F4A"></i> Inventory
                            </a>
                        </li>
                        @endif

                        {{-- Finance: Admin + Finance --}}
                        @if(auth()->user()->role && in_array(auth()->user()->role->name, ['admin', 'finance']))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('finance.index') }}">
                                <i class="fas fa-money-bill-wave" style="color: #3A3F4A"></i> Finance
                            </a>
                        </li>
                        @endif

                        {{-- Admin Dropdown: Admin only --}}
                        @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-cog" style="color: #3A3F4A"></i> Admin
                            </a>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                <li>
                                    <a class="dropdown-item" href="{{ route('staff.index') }}">
                                        <i class="fas fa-users-cog"></i> User Management
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('activity.index') }}">
                                        <i class="fas fa-history"></i> Activity Log
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('reports.all') }}">
                                        <i class="fas fa-file-alt"></i> All Reports
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            {{-- ✅ Show profile picture or initials --}}
                            @if(auth()->user()->profile_picture_url)
                                <img src="{{ auth()->user()->profile_picture_url }}" 
                                    alt="{{ auth()->user()->full_name }}"
                                    class="rounded-circle me-2"
                                    style="width: 32px; height: 32px; object-fit: cover; border: 2px solid #B5C401;">
                            @else
                                <div class="rounded-circle bg-lime text-dark d-flex align-items-center justify-content-center me-2"
                                    style="width: 32px; height: 32px; font-size: 0.75rem; font-weight: 600; background-color: #B5C401;">
                                    {{ auth()->user()->initials }}
                                </div>
                            @endif
                            <span>{{ auth()->user()->full_name ?? auth()->user()->name }}</span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-custom dropdown-menu-end">
                            {{-- User Info Header --}}
                            <li class="px-3 py-2 text-center" style="border-bottom: 1px solid #B5C401;">
                                @if(auth()->user()->profile_picture_url)
                                    <img src="{{ auth()->user()->profile_picture_url }}" 
                                        alt="{{ auth()->user()->full_name }}"
                                        class="rounded-circle mb-2"
                                        style="width: 60px; height: 60px; object-fit: cover; border: 3px solid #B5C401;">
                                @else
                                    <div class="rounded-circle bg-lime text-dark d-inline-flex align-items-center justify-content-center mb-2"
                                        style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: 600; background-color: #B5C401;">
                                        {{ auth()->user()->initials }}
                                    </div>
                                @endif
                                <div>
                                    <strong class="text-muted-white">{{ auth()->user()->full_name ?? auth()->user()->name }}</strong>
                                    <br>
                                    <small class="text-muted-white">{{ auth()->user()->email }}</small>
                                    <br>
                                    <span class="badge bg-secondary mt-1">
                                        <i class="fas fa-tag"></i> {{ auth()->user()->role->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </li>

                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4  flex-grow-1">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="fas fa-info-circle"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer-custom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        LimaSync &copy; {{ date('Y') }} 
                        <span class="text-muted">|</span> 
                        <small>Built for Lima Deria</small>
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small>
                        <a href="https://limaderia.com" target="_blank" class="text-lime">
                            <i class="fas fa-leaf"></i> Spectacular Sustainable Events
                        </a>
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>