<section>
    <header class="mb-4">
        <h2 class="h6 text-muted mb-2">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </h2>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="needs-validation" novalidate>
        @csrf
        @method('put')

        <div class="row">
            <div class="col-12 mb-3">
                <label for="update_password_current_password" class="form-label">
                    <i class="fas fa-unlock me-1"></i>
                    {{ __('Current Password') }}
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                           id="update_password_current_password" 
                           name="current_password" 
                           autocomplete="current-password"
                           required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('update_password_current_password')">
                        <i class="fas fa-eye" id="update_password_current_password_icon"></i>
                    </button>
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="update_password_password" class="form-label">
                    <i class="fas fa-lock me-1"></i>
                    {{ __('New Password') }}
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                           id="update_password_password" 
                           name="password" 
                           autocomplete="new-password"
                           minlength="8"
                           required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('update_password_password')">
                        <i class="fas fa-eye" id="update_password_password_icon"></i>
                    </button>
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>
                    Minimum 8 characters required
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="update_password_password_confirmation" class="form-label">
                    <i class="fas fa-lock me-1"></i>
                    {{ __('Confirm Password') }}
                </label>
                <div class="input-group">
                    <input type="password" 
                           class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                           id="update_password_password_confirmation" 
                           name="password_confirmation" 
                           autocomplete="new-password"
                           minlength="8"
                           required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('update_password_password_confirmation')">
                        <i class="fas fa-eye" id="update_password_password_confirmation_icon"></i>
                    </button>
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Password Strength Indicator -->
        <div class="mb-3">
            <label class="form-label">Password Strength</label>
            <div class="progress" style="height: 8px;">
                <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%"></div>
            </div>
            <small class="text-muted" id="password-strength-text">Enter a password to see strength</small>
        </div>

        <!-- Security Tips -->
        <div class="alert alert-info">
            <h6 class="alert-heading">
                <i class="fas fa-shield-alt me-2"></i>
                Password Security Tips
            </h6>
            <ul class="mb-0 small">
                <li>Use a mix of uppercase and lowercase letters</li>
                <li>Include numbers and special characters</li>
                <li>Avoid common words or personal information</li>
                <li>Consider using a password manager</li>
            </ul>
        </div>

        <div class="d-flex gap-2 align-items-center">
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-key me-2"></i>
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <div class="text-success" id="password-saved-message">
                    <i class="fas fa-check me-1"></i>
                    {{ __('Password updated successfully!') }}
                </div>

                <script>
                    setTimeout(() => {
                        const element = document.getElementById('password-saved-message');
                        if (element) element.style.display = 'none';
                    }, 3000);
                </script>
            @endif
        </div>
    </form>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '_icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Password strength checker
        document.getElementById('update_password_password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            
            let strength = 0;
            let feedback = '';
            
            if (password.length >= 8) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 20;
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 40) {
                strengthBar.className = 'progress-bar bg-danger';
                feedback = 'Weak password';
            } else if (strength < 80) {
                strengthBar.className = 'progress-bar bg-warning';
                feedback = 'Medium strength';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                feedback = 'Strong password';
            }
            
            strengthText.textContent = feedback;
        });
    </script>
</section>
