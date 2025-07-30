<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
        @csrf

        <h3 class="text-center mb-3">
            <i class="fas fa-key me-2"></i>
            Forgot Password?
        </h3>

        <p class="text-center text-muted mb-3 text-small">
            No problem! Enter your email address and we'll send you a password reset link.
        </p>

        <!-- Email Address -->
        <div class="mb-3">
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
                   placeholder="Enter your email address">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2 mb-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-2"></i>
                {{ __('Send Reset Link') }}
            </button>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none">
                <i class="fas fa-arrow-left me-1"></i>
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>

    <script>
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
