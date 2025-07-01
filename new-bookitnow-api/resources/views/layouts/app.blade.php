<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'BookItNow - Healthcare Management')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --warning-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 16px 40px rgba(0, 0, 0, 0.2);
            
            --border-radius: 12px;
            --border-radius-lg: 16px;
            --border-radius-xl: 20px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #2c3e50;
            line-height: 1.6;
        }
        
        .gradient-bg {
            background: var(--primary-gradient);
        }
        
        .card-custom {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }
        
        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: white;
        }
        
        .btn-gradient-secondary {
            background: var(--secondary-gradient);
        }
        
        .btn-gradient-success {
            background: var(--success-gradient);
        }
        
        .btn-gradient-warning {
            background: var(--warning-gradient);
        }
        
        .btn-gradient-danger {
            background: var(--danger-gradient);
        }
        
        .navbar-custom {
            background: var(--primary-gradient);
            box-shadow: var(--shadow-md);
            padding: 1rem 0;
        }
        
        .navbar-brand-custom {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
            text-decoration: none;
        }
        
        .nav-link-custom {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
        }
        
        .nav-link-custom:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
        }
        
        .sidebar {
            background: white;
            box-shadow: var(--shadow-md);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #6c757d;
            text-decoration: none;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }
        
        .sidebar-link:hover,
        .sidebar-link.active {
            background: var(--primary-gradient);
            color: white;
            transform: translateX(4px);
        }
        
        .sidebar-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-card.primary {
            border-left-color: #667eea;
        }
        
        .stat-card.secondary {
            border-left-color: #f093fb;
        }
        
        .stat-card.success {
            border-left-color: #43e97b;
        }
        
        .stat-card.warning {
            border-left-color: #4facfe;
        }
        
        .stat-card.danger {
            border-left-color: #fa709a;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }
        
        .stat-icon.primary {
            background: var(--primary-gradient);
        }
        
        .stat-icon.secondary {
            background: var(--secondary-gradient);
        }
        
        .stat-icon.success {
            background: var(--success-gradient);
        }
        
        .stat-icon.warning {
            background: var(--warning-gradient);
        }
        
        .stat-icon.danger {
            background: var(--danger-gradient);
        }
        
        .page-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0 0 var(--border-radius-xl) var(--border-radius-xl);
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .table-custom {
            background: white;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        
        .table-custom thead {
            background: var(--primary-gradient);
            color: white;
        }
        
        .table-custom th {
            border: none;
            padding: 1rem;
            font-weight: 600;
        }
        
        .table-custom td {
            border: none;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table-custom tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        
        .badge-primary {
            background: var(--primary-gradient);
            color: white;
        }
        
        .badge-success {
            background: var(--success-gradient);
            color: white;
        }
        
        .badge-warning {
            background: var(--warning-gradient);
            color: white;
        }
        
        .badge-danger {
            background: var(--danger-gradient);
            color: white;
        }
        
        .form-control-custom {
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control-custom:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .modal-custom .modal-content {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-xl);
        }
        
        .modal-custom .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        }
        
        .alert-custom {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        
        .empty-state p {
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem 0;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .stat-card {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .sidebar {
                margin-bottom: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand-custom" href="{{ route('dashboard') }}">
                <i class="bi bi-heart-pulse me-2"></i>
                BookItNow
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2 me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">
                            <i class="bi bi-calendar-event me-1"></i>
                            Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                            <i class="bi bi-people me-1"></i>
                            Patients
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('staff.*') ? 'active' : '' }}" href="{{ route('staff.index') }}">
                            <i class="bi bi-person-badge me-1"></i>
                            Staff
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link-custom {{ request()->routeIs('queue.*') ? 'active' : '' }}" href="{{ route('queue.index') }}">
                            <i class="bi bi-clock-history me-1"></i>
                            Queue
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            {{ auth()->user()->name }}
                            <span class="badge badge-primary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script>
        // CSRF Token Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Global notification function
        function showNotification(message, type = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: type,
                title: message
            });
        }
        
        // Global confirmation function
        function confirmAction(title, text, confirmText = 'Yes, proceed!') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#fa709a',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancel'
            });
        }
        
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Add loading state to buttons
        $(document).on('click', '.btn-loading', function() {
            const btn = $(this);
            const originalText = btn.html();
            btn.html('<span class="loading-spinner me-2"></span>Loading...').prop('disabled', true);
            
            setTimeout(function() {
                btn.html(originalText).prop('disabled', false);
            }, 2000);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
