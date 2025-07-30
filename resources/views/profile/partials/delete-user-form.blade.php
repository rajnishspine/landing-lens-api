<section class="space-y-6">
    <header class="mb-4">
        <h2 class="h6 text-muted mb-2">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </h2>
    </header>

    <!-- Warning Alert -->
    <div class="alert alert-danger border-danger">
        <h6 class="alert-heading">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ __('Permanent Account Deletion') }}
        </h6>
        <p class="mb-3">This action <strong>cannot be undone</strong>. When you delete your account:</p>
        <ul class="mb-3">
            <li>All your profile information will be permanently removed</li>
            <li>Any uploaded images and analysis results will be deleted</li>
            <li>You will lose access to all LandingLens analysis history</li>
            <li>This action is immediate and irreversible</li>
        </ul>
        <p class="mb-0 small">
            <i class="fas fa-info-circle me-1"></i>
            Make sure to download any important data before proceeding.
        </p>
    </div>

    <!-- Account Summary -->
    <div class="card bg-light">
        <div class="card-body">
            <h6 class="card-title">
                <i class="fas fa-user me-2"></i>
                Account Summary
            </h6>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Member Since:</strong> {{ Auth::user()->created_at->format('F j, Y') }}</p>
                    <p class="mb-1"><strong>Account ID:</strong> #{{ Auth::user()->id }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 align-items-center">
        <button type="button" 
                class="btn btn-danger" 
                data-bs-toggle="modal" 
                data-bs-target="#confirmDeleteModal">
            <i class="fas fa-trash-alt me-2"></i>
            {{ __('Delete Account') }}
        </button>
        
        <small class="text-muted">
            <i class="fas fa-shield-alt me-1"></i>
            You will be asked to confirm this action
        </small>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('Confirm Account Deletion') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>{{ __('Are you sure you want to delete your account?') }}</strong>
                        </div>

                        <p class="mb-3">{{ __('This action cannot be undone. All your data will be permanently deleted.') }}</p>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                {{ __('Please confirm with your password') }}
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="{{ __('Enter your current password') }}"
                                       required>
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleDeletePassword()">
                                    <i class="fas fa-eye" id="delete-password-icon"></i>
                                </button>
                            </div>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Final confirmation checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                            <label class="form-check-label" for="confirmDelete">
                                {{ __('I understand this action is permanent and cannot be undone') }}
                            </label>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-danger" id="finalDeleteButton" disabled>
                            <i class="fas fa-trash-alt me-2"></i>
                            {{ __('Yes, Delete My Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleDeletePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('delete-password-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Enable delete button only when checkbox is checked and password is entered
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('confirmDelete');
            const passwordInput = document.getElementById('password');
            const deleteButton = document.getElementById('finalDeleteButton');

            function updateDeleteButton() {
                deleteButton.disabled = !(checkbox.checked && passwordInput.value.length > 0);
            }

            checkbox.addEventListener('change', updateDeleteButton);
            passwordInput.addEventListener('input', updateDeleteButton);
        });

        // Clear form when modal is hidden
        document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('password').value = '';
            document.getElementById('confirmDelete').checked = false;
            document.getElementById('finalDeleteButton').disabled = true;
        });
    </script>
</section>
