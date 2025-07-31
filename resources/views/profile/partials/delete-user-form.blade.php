<section>
    <header class="mb-4">
        <h2 class="h6 text-muted mb-2">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </h2>
    </header>

    <!-- Warning Information -->
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <div class="d-flex align-items-start">
            <i class="fas fa-exclamation-triangle text-danger me-3 mt-1"></i>
            <div>
                <h6 class="alert-heading mb-2">Account Deletion Warning</h6>
                <p class="mb-2">This action permanently deletes your account and all associated data. This cannot be undone.</p>
                <ul class="mb-0 small">
                    <li>All profile information will be removed</li>
                    <li>Image analysis history will be deleted</li>
                    <li>Account access will be revoked immediately</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Account Statistics -->
    <div class="card bg-light border-0 mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h6 class="card-title mb-2">
                        <i class="fas fa-user-circle text-muted me-2"></i>
                        Your Account Details
                    </h6>
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Name</small>
                            <strong>{{ Auth::user()->name }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ Auth::user()->email }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Member Since</small>
                            <strong>{{ Auth::user()->created_at->format('M j, Y') }}</strong>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Total Analyses</small>
                            <strong>{{ Auth::user()->imageAnalyses()->count() }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="account-id-badge">
                        <small class="text-muted d-block">Account ID</small>
                        <code class="text-dark">#{{ Auth::user()->id }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simple Delete Button -->
    <div class="text-center mb-4" id="deleteInitial">
        <button type="button" class="btn btn-outline-danger btn-lg" onclick="showDeleteForm()">
            <i class="fas fa-trash-alt me-2"></i>
            I Want to Delete My Account
        </button>
        <p class="text-muted mt-2 small">
            <i class="fas fa-info-circle me-1"></i>
            Click to begin the account deletion process
        </p>
    </div>

    <!-- Delete Form (Hidden Initially) -->
    <div id="deleteFormContainer" style="display: none;">
        <div class="alert alert-warning border-0 mb-4">
            <h6 class="alert-heading">
                <i class="fas fa-question-circle me-2"></i>
                Are you absolutely sure?
            </h6>
            <p class="mb-0">This will permanently delete your account and all associated data. This action cannot be undone.</p>
        </div>

        <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
            @csrf
            @method('delete')
            
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Security Verification Required
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label for="deletePassword" class="form-label fw-bold">
                            <i class="fas fa-lock me-1"></i>
                            Enter your current password to confirm
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control form-control-lg @error('password', 'userDeletion') is-invalid @enderror" 
                                   id="deletePassword" 
                                   name="password" 
                                   placeholder="Your current password"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" onclick="toggleDeletePassword()">
                                <i class="fas fa-eye" id="deletePasswordIcon"></i>
                            </button>
                        </div>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input form-check-input-lg" type="checkbox" id="confirmDeletion" required>
                        <label class="form-check-label fw-bold text-danger" for="confirmDeletion">
                            I understand this action is permanent and cannot be undone
                        </label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="dataLoss" required>
                        <label class="form-check-label" for="dataLoss">
                            I acknowledge that all my data will be permanently lost
                        </label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="finalConfirm" required>
                        <label class="form-check-label" for="finalConfirm">
                            I want to permanently delete my account: <strong>{{ Auth::user()->email }}</strong>
                        </label>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-secondary" onclick="hideDeleteForm()">
                            <i class="fas fa-arrow-left me-2"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger btn-lg" id="finalDeleteButton" disabled>
                            <i class="fas fa-trash-alt me-2"></i>
                            Permanently Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .account-id-badge {
            padding: 0.75rem;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        .form-check-input-lg {
            width: 1.25rem;
            height: 1.25rem;
        }

        .form-check-input-lg:checked {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            transition: all 0.3s ease;
        }

        @media (max-width: 576px) {
            .card-footer .d-flex {
                flex-direction: column;
                gap: 1rem;
            }

            .card-footer .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        // Simple Delete Account System
        function showDeleteForm() {
            document.getElementById('deleteInitial').style.display = 'none';
            document.getElementById('deleteFormContainer').style.display = 'block';
            
            // Focus password field
            setTimeout(() => {
                const passwordInput = document.getElementById('deletePassword');
                if (passwordInput) passwordInput.focus();
            }, 100);
        }

        function hideDeleteForm() {
            document.getElementById('deleteInitial').style.display = 'block';
            document.getElementById('deleteFormContainer').style.display = 'none';
            
            // Reset form
            const form = document.getElementById('deleteAccountForm');
            if (form) {
                form.reset();
                updateDeleteButton();
                
                // Reset password visibility
                const passwordInput = document.getElementById('deletePassword');
                const icon = document.getElementById('deletePasswordIcon');
                if (passwordInput) passwordInput.type = 'password';
                if (icon) icon.className = 'fas fa-eye';
            }
        }

        function toggleDeletePassword() {
            const input = document.getElementById('deletePassword');
            const icon = document.getElementById('deletePasswordIcon');
            
            if (input && icon) {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fas fa-eye-slash';
                } else {
                    input.type = 'password';
                    icon.className = 'fas fa-eye';
                }
            }
        }

        function updateDeleteButton() {
            const password = document.getElementById('deletePassword');
            const confirm1 = document.getElementById('confirmDeletion');
            const confirm2 = document.getElementById('dataLoss');
            const confirm3 = document.getElementById('finalConfirm');
            const deleteButton = document.getElementById('finalDeleteButton');
            
            if (password && confirm1 && confirm2 && confirm3 && deleteButton) {
                const allValid = password.value.length > 0 && 
                               confirm1.checked && 
                               confirm2.checked && 
                               confirm3.checked;
                
                deleteButton.disabled = !allValid;
            }
        }

        // Initialize form validation when document loads
        document.addEventListener('DOMContentLoaded', function() {
            // Set up form validation for all required elements
            const elementsToWatch = ['deletePassword', 'confirmDeletion', 'dataLoss', 'finalConfirm'];
            
            elementsToWatch.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    if (element.type === 'checkbox') {
                        element.addEventListener('change', updateDeleteButton);
                    } else {
                        element.addEventListener('input', updateDeleteButton);
                        element.addEventListener('keyup', updateDeleteButton);
                    }
                }
            });
            
            // Add final confirmation before submission
            const form = document.getElementById('deleteAccountForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const finalConfirm = confirm(
                        'FINAL WARNING: This will permanently delete your account and all data. ' +
                        'This action cannot be undone. Are you absolutely sure?'
                    );
                    if (!finalConfirm) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });
    </script>
</section>
