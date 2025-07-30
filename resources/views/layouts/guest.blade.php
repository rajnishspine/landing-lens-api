<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Object Detection AI</title>

        <!-- Professional Typography -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        <!-- Professional Auth Styles -->
        <style>
            /* Vibrant & Eye-Catching Typography Variables */
            :root {
                --font-family-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                --font-weight-light: 300;
                --font-weight-normal: 400;
                --font-weight-medium: 500;
                --font-weight-semibold: 600;
                --font-weight-bold: 700;
                --color-primary: #6366f1;
                --color-secondary: #8b5cf6;
                --color-accent: #06b6d4;
                --color-pink: #ec4899;
                --color-orange: #f97316;
                --color-text-primary: #1f2937;
                --color-text-secondary: #4b5563;
                --color-text-muted: #6b7280;
            }
            
            body {
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 50%, var(--color-accent) 100%);
                min-height: 100vh;
                font-family: var(--font-family-primary);
                font-weight: var(--font-weight-normal);
                line-height: 1.6;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                position: relative;
                overflow-x: hidden;
            }
            
            /* Animated Background Elements */
            body::before {
                content: '';
                position: fixed;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, transparent 50%),
                           radial-gradient(circle, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
                           radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 50%);
                animation: float 20s ease-in-out infinite;
                z-index: -1;
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                33% { transform: translateY(-20px) rotate(120deg); }
                66% { transform: translateY(20px) rotate(240deg); }
            }
            
            .auth-container {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 16px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                max-width: 380px;
                width: 100%;
            }
            
            .logo-container {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
                border-radius: 50%;
                width: 70px;
                height: 70px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
                animation: logoFloat 3s ease-in-out infinite;
            }
            
            .logo-container .fa-eye {
                font-size: 1.8rem;
                color: white;
                position: relative;
            }
            
            .logo-container::before {
                content: '';
                position: absolute;
                width: 85px;
                height: 85px;
                border: 2px dashed rgba(79, 70, 229, 0.3);
                border-radius: 50%;
                animation: rotate 10s linear infinite;
            }
            
            @keyframes logoFloat {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-8px); }
            }
            
            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            .brand-text {
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 50%, var(--color-accent) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-weight: var(--font-weight-bold);
                font-size: 1.5rem;
                text-align: center;
                margin-bottom: 0.5rem;
                line-height: 1.3;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                animation: shimmer 3s ease-in-out infinite;
            }
            
            @keyframes shimmer {
                0%, 100% { filter: brightness(1); }
                50% { filter: brightness(1.2); }
            }
            
            .brand-subtitle {
                color: var(--color-text-secondary);
                text-align: center;
                font-size: 0.875rem;
                font-weight: var(--font-weight-medium);
                margin-bottom: 1.5rem;
            }
            
            .form-control {
                font-family: var(--font-family-primary);
                font-size: 0.9375rem;
                font-weight: var(--font-weight-normal);
                border-radius: 0.75rem;
                border: 1.5px solid #d1d5db;
                padding: 0.75rem 1rem;
                transition: all 0.3s ease;
                line-height: 1.5;
            }
            
            .form-control:focus {
                border-color: var(--color-primary);
                box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.1);
            }
            
            .form-label {
                font-family: var(--font-family-primary);
                font-weight: var(--font-weight-medium);
                font-size: 0.9375rem;
                color: var(--color-text-primary);
                margin-bottom: 0.5rem;
            }
            
            .btn {
                font-family: var(--font-family-primary);
                font-weight: var(--font-weight-medium);
                font-size: 0.9375rem;
                transition: all 0.3s ease;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
                border: none;
                border-radius: 0.75rem;
                padding: 0.75rem 2rem;
                font-weight: var(--font-weight-semibold);
                box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-size: 0.875rem;
            }
            
            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 35px rgba(99, 102, 241, 0.6);
                background: linear-gradient(135deg, #5855f0 0%, #7c3aed 100%);
            }
            
            /* Typography for headings */
            h1, h2, h3, h4, h5, h6 {
                font-family: var(--font-family-primary);
                color: var(--color-text-primary);
            }
            
            h3 {
                font-weight: var(--font-weight-semibold);
                font-size: 1.375rem;
                line-height: 1.4;
            }
            
            /* Text elements */
            p, small, .text-muted {
                font-family: var(--font-family-primary);
            }
            
            .text-muted {
                color: var(--color-text-muted) !important;
            }
            
            /* Links */
            a {
                font-family: var(--font-family-primary);
                font-weight: var(--font-weight-medium);
                color: var(--color-primary);
                text-decoration: none;
                transition: color 0.2s ease;
            }
            
            a:hover {
                color: #3730a3;
            }
            
            /* Alert styling */
            .alert {
                font-family: var(--font-family-primary);
                font-size: 0.875rem;
                border-radius: 0.75rem;
                border: none;
            }
            
            /* Form text */
            .form-text {
                font-size: 0.8125rem;
                color: var(--color-text-muted);
                font-weight: var(--font-weight-normal);
            }
            
            .floating-shapes {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: -1;
            }
            
            .floating-shape {
                position: absolute;
                animation: float 8s ease-in-out infinite, colorPulse 4s ease-in-out infinite;
                font-size: 2rem;
            }
            
            .floating-shape:nth-child(1) {
                top: 20%;
                left: 10%;
                animation-delay: 0s;
                color: rgba(236, 72, 153, 0.4);
            }
            
            .floating-shape:nth-child(2) {
                top: 60%;
                right: 15%;
                animation-delay: 2s;
                color: rgba(6, 182, 212, 0.4);
            }
            
            .floating-shape:nth-child(3) {
                bottom: 20%;
                left: 20%;
                animation-delay: 4s;
                color: rgba(249, 115, 22, 0.4);
            }
            
            .floating-shape:nth-child(4) {
                top: 15%;
                right: 25%;
                animation-delay: 1s;
                color: rgba(139, 92, 246, 0.4);
            }
            
            .floating-shape:nth-child(5) {
                bottom: 30%;
                right: 10%;
                animation-delay: 3s;
                color: rgba(16, 185, 129, 0.4);
            }
            
            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(180deg); }
            }
            
            @keyframes colorPulse {
                0%, 100% { opacity: 0.4; filter: brightness(1); }
                50% { opacity: 0.7; filter: brightness(1.3); }
            }
            
            .form-label {
                font-size: 0.85rem;
                font-weight: 600;
                margin-bottom: 0.4rem;
            }
            
            .alert {
                padding: 0.6rem 0.9rem;
                font-size: 0.8rem;
                border-radius: 8px;
            }
            
            .text-center a {
                font-size: 0.85rem;
            }
            
            .auth-footer {
                font-size: 0.7rem;
                margin-top: 0.75rem;
            }
        </style>
    </head>
    <body>
        <!-- Vibrant Floating Background Shapes -->
        <div class="floating-shapes">
            <i class="fas fa-eye floating-shape"></i>
            <i class="fas fa-camera floating-shape"></i>
            <i class="fas fa-brain floating-shape"></i>
            <i class="fas fa-robot floating-shape"></i>
            <i class="fas fa-bullseye floating-shape"></i>
        </div>

        <div class="min-vh-100 d-flex align-items-center justify-content-center p-3">
            <div class="auth-container p-4">
                <!-- Compact Object Detection Logo -->
                <div class="text-center mb-3">
                    <a href="/" class="text-decoration-none">
                        <div class="logo-container">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h2 class="brand-text">LandingLens AI</h2>
                        <p class="brand-subtitle">
                            <i class="fas fa-robot me-1"></i>
                            Object Detection & Analysis
                        </p>
                    </a>
                </div>

                <!-- Auth Form Content -->
                {{ $slot }}

                <!-- Compact Footer -->
                <div class="text-center auth-footer">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Powered by AI Technology
                    </small>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
