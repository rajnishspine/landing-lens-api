<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
        @csrf

        <h3 class="text-center mb-3">
            <i class="fas fa-sign-in-alt me-2"></i>
            Welcome Back
        </h3>

        <!-- Email Address -->
        <div class="mb-2">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-1"></i>
                {{ __('Email Address') }}
            </label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="Enter your email address">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-2">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-1"></i>
                {{ __('Password') }}
            </label>
            <div class="input-group">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       placeholder="Enter your password">
                <button class="btn btn-outline-secondary" type="button" onclick="toggleLoginPassword()">
                    <i class="fas fa-eye" id="login-password-icon"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label text-small" for="remember_me">
                    <i class="fas fa-memory me-1"></i>
                    {{ __('Remember me') }}
                </label>
            </div>
        </div>

        <!-- Login Button and Links -->
        <div class="d-grid gap-2 mb-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt me-2"></i>
                {{ __('Sign In') }}
            </button>
        </div>

        <div class="text-center mb-2">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none me-3">
                    <i class="fas fa-key me-1"></i>
                    {{ __('Forgot Password?') }}
                </a>
            @endif
            
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-decoration-none">
                    <i class="fas fa-user-plus me-1"></i>
                    {{ __('Create Account') }}
                </a>
            @endif
        </div>
    </form>

    <script>
        function toggleLoginPassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('login-password-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</x-guest-layout>
