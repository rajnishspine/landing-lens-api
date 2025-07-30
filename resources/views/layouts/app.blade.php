<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - LandingLens AI</title>

        <!-- Professional Typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <!-- Professional Typography & Styling System -->
        <style>
            /* ===== TYPOGRAPHY SYSTEM ===== */
            :root {
                --font-family-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                --font-weight-light: 300;
                --font-weight-normal: 400;
                --font-weight-medium: 500;
                --font-weight-semibold: 600;
                --font-weight-bold: 700;
                
                /* Vibrant & Eye-Catching Color Palette */
                --color-primary: #6366f1;
                --color-secondary: #8b5cf6;
                --color-accent: #06b6d4;
                --color-success: #10b981;
                --color-warning: #f59e0b;
                --color-danger: #ef4444;
                --color-info: #3b82f6;
                --color-pink: #ec4899;
                --color-orange: #f97316;
                --color-emerald: #059669;
                --color-text-primary: #1f2937;
                --color-text-secondary: #4b5563;
                --color-text-muted: #6b7280;
                --color-bg-light: #f8fafc;
                --color-bg-card: #ffffff;
            }
            
            /* Base Typography */
            body, html {
                font-family: var(--font-family-primary);
                font-weight: var(--font-weight-normal);
                line-height: 1.6;
                color: var(--color-text-primary);
                font-size: 16px;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            
            /* Headings Hierarchy */
            h1, .h1 { 
                font-weight: var(--font-weight-bold); 
                font-size: 2.25rem; 
                line-height: 1.2; 
                margin-bottom: 1rem;
                color: var(--color-text-primary);
            }
            h2, .h2 { 
                font-weight: var(--font-weight-semibold); 
                font-size: 1.875rem; 
                line-height: 1.3; 
                margin-bottom: 0.875rem;
                color: var(--color-text-primary);
            }
            h3, .h3 { 
                font-weight: var(--font-weight-semibold); 
                font-size: 1.5rem; 
                line-height: 1.4; 
                margin-bottom: 0.75rem;
                color: var(--color-text-primary);
            }
            h4, .h4 { 
                font-weight: var(--font-weight-medium); 
                font-size: 1.25rem; 
                line-height: 1.4; 
                margin-bottom: 0.625rem;
                color: var(--color-text-primary);
            }
            h5, .h5 { 
                font-weight: var(--font-weight-medium); 
                font-size: 1.125rem; 
                line-height: 1.5; 
                margin-bottom: 0.5rem;
                color: var(--color-text-primary);
            }
            h6, .h6 { 
                font-weight: var(--font-weight-medium); 
                font-size: 1rem; 
                line-height: 1.5; 
                margin-bottom: 0.5rem;
                color: var(--color-text-primary);
            }
            
            /* Body Text */
            p, .body-text {
                font-size: 1rem;
                line-height: 1.6;
                margin-bottom: 1rem;
                color: var(--color-text-primary);
            }
            
            .text-large {
                font-size: 1.125rem;
                line-height: 1.6;
            }
            
            .text-small {
                font-size: 0.875rem;
                line-height: 1.5;
            }
            
            .text-xs {
                font-size: 0.75rem;
                line-height: 1.4;
            }
            
            /* Text Colors */
            .text-primary { color: var(--color-primary) !important; }
            .text-secondary { color: var(--color-text-secondary) !important; }
            .text-muted { color: var(--color-text-muted) !important; }
            
            /* ===== COMPONENT STYLING ===== */
            
            /* Navigation - Vibrant Gradient */
            .navbar {
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 50%, var(--color-accent) 100%) !important;
                box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
                border: none !important;
            }
            
            .navbar-brand {
                font-weight: var(--font-weight-bold);
                font-size: 1.25rem;
                color: white !important;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }
            
            .nav-link {
                font-weight: var(--font-weight-medium);
                font-size: 0.9375rem;
                color: rgba(255, 255, 255, 0.9) !important;
                transition: all 0.3s ease;
            }
            
            .nav-link:hover {
                color: white !important;
                transform: translateY(-1px);
                text-shadow: 0 2px 8px rgba(255, 255, 255, 0.3);
            }
            
            /* Cards - Vibrant & Modern */
            .card {
                box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15);
                border: 2px solid transparent;
                border-radius: 1rem;
                background: var(--color-bg-card);
                transition: all 0.3s ease;
                overflow: hidden;
            }
            
            .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 15px 40px rgba(99, 102, 241, 0.25);
                border-color: var(--color-primary);
            }
            
            .card-title {
                font-weight: var(--font-weight-semibold);
                color: var(--color-text-primary);
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .card-header {
                font-weight: var(--font-weight-medium);
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
                color: white;
                border-bottom: none;
                padding: 1rem 1.25rem;
            }
            
            /* Buttons - Vibrant & Eye-catching */
            .btn {
                font-weight: var(--font-weight-medium);
                font-size: 0.9375rem;
                border-radius: 0.75rem;
                padding: 0.75rem 1.5rem;
                transition: all 0.3s ease;
                border: none;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                position: relative;
                overflow: hidden;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            }
            
            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(99, 102, 241, 0.6);
                background: linear-gradient(135deg, #5855f0 0%, #7c3aed 100%);
            }
            
            .btn-success {
                background: linear-gradient(135deg, var(--color-success) 0%, var(--color-emerald) 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
            }
            
            .btn-success:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
            }
            
            .btn-info {
                background: linear-gradient(135deg, var(--color-info) 0%, var(--color-accent) 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
            }
            
            .btn-info:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(6, 182, 212, 0.6);
            }
            
            .btn-warning {
                background: linear-gradient(135deg, var(--color-warning) 0%, var(--color-orange) 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
            }
            
            .btn-warning:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(245, 158, 11, 0.6);
            }
            
            .btn-danger {
                background: linear-gradient(135deg, var(--color-danger) 0%, #dc2626 100%);
                color: white;
                box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4);
            }
            
            .btn-danger:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 25px rgba(239, 68, 68, 0.6);
            }
            
            /* Forms - Vibrant & Interactive */
            .form-label {
                font-weight: var(--font-weight-medium);
                font-size: 0.9375rem;
                color: var(--color-primary);
                margin-bottom: 0.5rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-size: 0.8125rem;
            }
            
            .form-control {
                font-family: var(--font-family-primary);
                font-size: 0.9375rem;
                line-height: 1.5;
                border-radius: 0.75rem;
                border: 2px solid #e2e8f0;
                transition: all 0.3s ease;
                padding: 0.75rem 1rem;
                background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            }
            
            .form-control:focus {
                border-color: var(--color-primary);
                box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.2), 0 4px 12px rgba(99, 102, 241, 0.15);
                background: white;
                transform: translateY(-1px);
            }
            
            .form-text {
                font-size: 0.8125rem;
                color: var(--color-text-secondary);
                font-weight: var(--font-weight-medium);
            }
            
            /* Alerts - Vibrant & Attention-grabbing */
            .alert {
                font-size: 0.9375rem;
                border-radius: 1rem;
                border: none;
                padding: 1rem 1.25rem;
                margin-bottom: 1.5rem;
                font-weight: var(--font-weight-medium);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
            
            .alert-success {
                background: linear-gradient(135deg, var(--color-success) 0%, var(--color-emerald) 100%);
                color: white;
            }
            
            .alert-info {
                background: linear-gradient(135deg, var(--color-info) 0%, var(--color-accent) 100%);
                color: white;
            }
            
            .alert-warning {
                background: linear-gradient(135deg, var(--color-warning) 0%, var(--color-orange) 100%);
                color: white;
            }
            
            .alert-danger {
                background: linear-gradient(135deg, var(--color-danger) 0%, #dc2626 100%);
                color: white;
            }
            
            .alert-heading {
                font-weight: var(--font-weight-semibold);
                margin-bottom: 0.5rem;
            }
            
            /* Tables */
            .table {
                font-size: 0.9375rem;
            }
            
            .table th {
                font-weight: var(--font-weight-semibold);
                color: var(--color-text-primary);
                border-top: none;
            }
            
            /* Links */
            a {
                color: var(--color-primary);
                text-decoration: none;
                font-weight: var(--font-weight-medium);
                transition: color 0.2s ease;
            }
            
            a:hover {
                color: #3730a3;
            }
            
            /* Utility Classes */
            .min-vh-100 {
                min-height: 100vh;
            }
            
            .image-preview {
                max-height: 300px;
                border: 3px dashed var(--color-primary);
                border-radius: 1rem;
                padding: 1.5rem;
                background: linear-gradient(145deg, #f8fafc 0%, #ffffff 100%);
                transition: all 0.3s ease;
            }
            
            .image-preview:hover {
                border-color: var(--color-secondary);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(99, 102, 241, 0.15);
            }
            
            /* Professional spacing */
            .section-spacing {
                padding: 2rem 0;
            }
            
            .content-spacing {
                margin-bottom: 1.5rem;
            }
            
            /* Vibrant Background Effects */
            body {
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
                min-height: 100vh;
            }
            
            /* Header with gradient */
            header {
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.9) 100%) !important;
                backdrop-filter: blur(10px);
                border-bottom: 2px solid rgba(99, 102, 241, 0.1) !important;
            }
            
            /* Page titles with gradient */
            .page-title {
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-weight: var(--font-weight-bold);
            }
            
            /* Responsive Typography */
            @media (max-width: 768px) {
                body { font-size: 15px; }
                h1, .h1 { font-size: 2rem; }
                h2, .h2 { font-size: 1.75rem; }
                h3, .h3 { font-size: 1.375rem; }
                .btn { padding: 0.5rem 1rem; }
            }
        </style>
    </head>
    <body class="bg-light">
        <div class="min-vh-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
            <header class="bg-white shadow-sm border-bottom mb-4">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
            @endisset

            <!-- Page Content -->
            <main>
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="container mb-4">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="container mb-4">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        
        <!-- Custom Scripts -->
        <script>
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        </script>
    </body>
</html>
