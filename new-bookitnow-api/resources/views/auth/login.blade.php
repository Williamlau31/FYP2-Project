<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Login - BookItNow Healthcare</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .logo-circle i {
            font-size: 2.5rem;
            color: white;
        }
        
        .app-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .app-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            margin: 0.5rem 0 0;
            font-weight: 300;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }
        
        .login-header {
            background: var(--secondary-gradient);
            color: white;
            text-align: center;
            padding: 2rem 1.5rem 1.5rem;
        }
        
        .login-title {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .login-form-content {
            padding: 2rem 1.5rem;
        }
        
        .input-group-custom {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-control-custom {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem 0.75rem 3rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            height: 56px;
        }
        
        .form-control-custom:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 1.2rem;
            z-index: 2;
        }
        
        .login-button {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            height: 56px;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }
        
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .demo-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e9ecef;
        }
        
        .demo-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .demo-header i {
            color: #667eea;
            margin-right: 8px;
            font-size: 1.2rem;
        }
        
        .demo-title {
            color: #495057;
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }
        
        .credential-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        
        .role-chip {
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            margin-right: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .role-chip.secondary {
            background: #f093fb;
        }
        
        .credential-details {
            flex: 1;
        }
        
        .credential-details p {
            margin: 0.25rem 0;
            font-size: 0.9rem;
            color: #495057;
        }
        
        .credential-details strong {
            color: #667eea;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 2rem;
        }
        
        .login-footer p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin: 0;
        }
        
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 10px;
            }
            
            .app-title {
                font-size: 2rem;
            }
            
            .login-form-content {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-circle">
                <i class="bi bi-heart-pulse"></i>
            </div>
            <h1 class="app-title">BookItNow</h1>
            <p class="app-subtitle">Healthcare Management System</p>
        </div>

        <!-- Login Card -->
        <div class="card login-card">
            <div class="login-header">
                <h2 class="login-title">Welcome Back</h2>
                <p class="login-subtitle">Please sign in to continue</p>
            </div>
            
            <div class="login-form-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="input-group-custom">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" 
                               class="form-control form-control-custom @error('email') is-invalid @enderror" 
                               name="email" 
                               placeholder="Email Address" 
                               value="{{ old('email') }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="input-group-custom">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" 
                               class="form-control form-control-custom @error('password') is-invalid @enderror" 
                               name="password" 
                               placeholder="Password" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    
                    <button type="submit" class="btn login-button" id="loginBtn">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    </button>
                </form>
                
                <!-- Demo Credentials -->
                <div class="demo-section">
                    <div class="demo-header">
                        <i class="bi bi-info-circle"></i>
                        <h4 class="demo-title">Demo Credentials</h4>
                    </div>
                    <div class="demo-credentials">
                        <div class="credential-item">
                            <div class="role-chip">
                                <i class="bi bi-shield-check"></i>
                                Admin
                            </div>
                            <div class="credential-details">
                                <p><strong>Email:</strong> admin@test.com</p>
                                <p><strong>Password:</strong> admin</p>
                            </div>
                        </div>
                        <div class="credential-item">
                            <div class="role-chip secondary">
                                <i class="bi bi-person"></i>
                                User
                            </div>
                            <div class="credential-details">
                                <p><strong>Email:</strong> user@test.com</p>
                                <p><strong>Password:</strong> user</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; 2024 BookItNow. All rights reserved.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const btn = $('#loginBtn');
                const originalText = btn.html();
                
                // Show loading state
                btn.html('<span class="loading-spinner"></span>Signing In...').prop('disabled', true);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        }
                    },
                    error: function(xhr) {
                        btn.html(originalText).prop('disabled', false);
                        
                        if (xhr.status === 401) {
                            const response = xhr.responseJSON;
                            Swal.fire({
                                icon: 'error',
                                title: 'Login Failed',
                                text: response.message || 'Invalid credentials. Please try again.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred. Please try again.'
                            });
                        }
                    }
                });
            });
            
            // Demo credential quick fill
            $('.credential-item').on('click', function() {
                const email = $(this).find('p:first').text().split(': ')[1];
                const password = $(this).find('p:last').text().split(': ')[1];
                
                $('input[name="email"]').val(email);
                $('input[name="password"]').val(password);
            });
        });
    </script>
</body>
</html>
