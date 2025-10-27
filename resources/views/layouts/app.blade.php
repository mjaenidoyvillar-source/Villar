<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stuff\'d - Inventory Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #388087;
            color: white;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #2a5f66;
        }
        
        .sidebar-brand {
            color: white;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: bold;
        }
        
        .sidebar-brand:hover {
            color: #adb5bd;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .logo-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        
        .brand-text {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin: 0.25rem 0;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            background-color: #2a5f66;
            color: white;
        }
        
        .nav-link.active {
            background-color: #2a5f66;
            color: white;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }
        
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            padding: 2rem;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .btn {
            border-radius: 0.375rem;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
        }
        
        .alert {
            border-radius: 0.5rem;
        }
        
        /* Override Bootstrap primary color */
        .btn-primary {
            background-color: #388087;
            border-color: #388087;
        }
        
        .btn-primary:hover {
            background-color: #2a5f66;
            border-color: #2a5f66;
        }
        
        .btn-outline-primary {
            color: #388087;
            border-color: #388087;
        }
        
        .btn-outline-primary:hover {
            background-color: #388087;
            border-color: #388087;
        }
        
        .text-primary {
            color: #388087 !important;
        }
        
        .badge.bg-primary {
            background-color: #388087 !important;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .mobile-toggle {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-toggle {
                display: none;
            }
        }
        
        /* Pagination Styling */
        .pagination {
            margin: 0;
            padding: 0;
        }
        
        .pagination .page-link {
            color: #388087;
            border-color: #dee2e6;
            padding: 0.5rem 0.75rem;
        }
        
        .pagination .page-link:hover {
            color: #2a5f66;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #388087;
            border-color: #388087;
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }
    </style>
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="btn mobile-toggle" type="button" onclick="toggleSidebar()" style="background-color: #388087; border-color: #388087;">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a class="sidebar-brand" href="{{ route('products.index') }}">
                <div class="logo-container">
                    <img src="{{ asset('logo/stuff\'d.png') }}" alt="Stuff'd Logo" class="logo-image">
                    <span class="brand-text">Stuff'd</span>
                </div>
            </a>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="fas fa-box"></i>Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-tags"></i>Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                        <i class="fas fa-shopping-cart"></i>Transactions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('buyers.*') ? 'active' : '' }}" href="{{ route('buyers.index') }}">
                        <i class="fas fa-users"></i>Buyers
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>
