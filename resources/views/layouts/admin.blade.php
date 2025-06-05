<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
            padding-top: 1rem;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
            list-style: none;
        }
        
        .sidebar-menu li {
            padding: 0.5rem 1.5rem;
        }
        
        .sidebar-menu a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .sidebar-menu i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
        }
        
        .top-navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            padding: 1rem 2rem;
            margin-bottom: 2rem;
            border-radius: 15px;
        }
        
        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            color: #2d3748;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,.08);
            margin-bottom: 1rem;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.25rem;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
            color: #2d3748;
        }
        
        /* Stats Card */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            height: 100%;
            transition: all 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stats-icon {
            width: 48px;
            height: 48px;
            background: #4b6cb7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stats-info h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #2d3748;
        }
        
        .stats-info p {
            color: #718096;
            margin: 0;
        }
        
        /* Table Styles */
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            color: #2d3748;
            border-bottom: 2px solid #edf2f7;
            padding: 1rem;
        }
        
        .table td {
            vertical-align: middle;
            color: #4a5568;
            padding: 1rem;
        }
        
        /* Form Styles */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4b6cb7;
            box-shadow: 0 0 0 0.2rem rgba(75, 108, 183, 0.25);
        }
        
        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-primary {
            background: #4b6cb7;
            border-color: #4b6cb7;
        }
        
        .btn-primary:hover {
            background: #3b5998;
            border-color: #3b5998;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="/admin" class="sidebar-brand">
                <i class="fas fa-shopping-cart me-2"></i>
                POS Admin
            </a>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    Products
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    Categories
                </a>
            </li>
            <li>
                <a href="{{ route('admin.sales') }}" class="{{ request()->routeIs('admin.sales*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    Sales
                </a>
            </li>
            <li>
                <a href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Customers
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-chart-bar"></i>
                    Reports
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cog"></i>
                    Settings
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title">@yield('title')</h1>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-link text-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
                            @if(auth()->user()->profile)
                                <img src="{{ asset('profile/'.auth()->user()->profile) }}" alt="Profile Image" class="rounded-circle" style="width: 45px;height: 45px;object-fit: cover;margin-right: 0.5rem;border: 1px solid dimgray;">
                            @else
                                <i class="fas fa-user-circle me-1"></i>
                            @endif
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html> 