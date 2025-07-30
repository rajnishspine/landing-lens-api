<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
        @csrf

        <h3 class="text-center mb-3">
            <i class="fas fa-user-plus me-2"></i>
            Create Your Account
        </h3>

        <!-- Name -->
        <div class="mb-2">
            <label for="name" class="form-label">
                <i class="fas fa-user me-1"></i>
                {{ __('Full Name') }}
            </label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   autocomplete="name"
                   placeholder="Enter your full name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

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
                       autocomplete="new-password"
                       minlength="8"
                       placeholder="Create a secure password">
                <button class="btn btn-outline-secondary" type="button" onclick="toggleRegisterPassword()">
                    <i class="fas fa-eye" id="register-password-icon"></i>
                </button>
            </div>
            <div class="form-text text-xs">
                <i class="fas fa-info-circle me-1"></i>
                Minimum 8 characters required
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-2">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock me-1"></i>
                {{ __('Confirm Password') }}
            </label>
            <div class="input-group">
                <input type="password" 
                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required 
                       autocomplete="new-password"
                       minlength="8"
                       placeholder="Confirm your password">
                <button class="btn btn-outline-secondary" type="button" onclick="toggleRegisterPasswordConfirm()">
                    <i class="fas fa-eye" id="register-password-confirm-icon"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Strength Indicator -->
        <div class="mb-2">
            <div class="progress" style="height: 5px;">
                <div class="progress-bar" id="register-password-strength" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted text-xs" id="register-password-feedback">Enter a password to see strength</small>
        </div>

        <!-- Terms and Conditions -->
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms" required>
                <label class="form-check-label text-small" for="terms">
                    <i class="fas fa-file-contract me-1"></i>
                    {{ __('I agree to the Terms of Service and Privacy Policy') }}
                </label>
            </div>
        </div>

        <!-- Register Button -->
        <div class="d-grid gap-2 mb-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>
                {{ __('Create Account') }}
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mb-2">
            <span class="text-muted text-small">Already have an account?</span>
            <a href="{{ route('login') }}" class="text-decoration-none ms-1">
                <i class="fas fa-sign-in-alt me-1"></i>
                {{ __('Sign In') }}
            </a>
        </div>
    </form>

    <script>
        function toggleRegisterPassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('register-password-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        function toggleRegisterPasswordConfirm() {
            const input = document.getElementById('password_confirmation');
            const icon = document.getElementById('register-password-confirm-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('register-password-strength');
            const feedback = document.getElementById('register-password-feedback');
            
            let strength = 0;
            let message = '';
            
            if (password.length >= 8) strength += 25;
            if (/[a-z]/.test(password)) strength += 25;
            if (/[A-Z]/.test(password)) strength += 25;
            if (/[0-9]/.test(password)) strength += 12.5;
            if (/[^A-Za-z0-9]/.test(password)) strength += 12.5;
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 25) {
                strengthBar.className = 'progress-bar bg-danger';
                message = 'Very weak password';
            } else if (strength < 50) {
                strengthBar.className = 'progress-bar bg-warning';
                message = 'Weak password';
            } else if (strength < 75) {
                strengthBar.className = 'progress-bar bg-info';
                message = 'Good password';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                message = 'Strong password';
            }
            
            feedback.textContent = message;
        });

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

        // Password match validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (confirmation && password !== confirmation) {
                this.setCustomValidity('Passwords do not match');
                this.classList.add('is-invalid');
            } else {
                this.setCustomValidity('');
                this.classList.remove('is-invalid');
            }
        });
    </script>
</x-guest-layout>
