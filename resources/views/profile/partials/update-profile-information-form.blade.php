<section>
    <header class="mb-4">
        <h2 class="h6 text-muted mb-2">
            {{ __('Update your account profile information and email address.') }}
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="needs-validation" novalidate>
        @csrf
        @method('patch')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">
                    <i class="fas fa-user me-1"></i>
                    {{ __('Full Name') }}
                </label>
                <input type="text" 
                       class="form-control @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus 
                       autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1"></i>
                    {{ __('Email Address') }}
                </label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="email" 
                       name="email" 
                       value="{{ old('email', $user->email) }}" 
                       required 
                       autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning mt-2" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>{{ __('Your email address is unverified.') }}</strong>
                        <button form="send-verification" class="btn btn-link p-0 ms-2 text-decoration-underline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ __('Member Since') }}
                </label>
                <div class="form-control-plaintext">
                    {{ $user->created_at->format('F j, Y') }}
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label text-muted">
                    <i class="fas fa-clock me-1"></i>
                    {{ __('Last Updated') }}
                </label>
                <div class="form-control-plaintext">
                    {{ $user->updated_at->format('F j, Y g:i A') }}
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 align-items-center">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div class="text-success" id="saved-message">
                    <i class="fas fa-check me-1"></i>
                    {{ __('Saved successfully!') }}
                </div>

                <script>
                    setTimeout(() => {
                        const element = document.getElementById('saved-message');
                        if (element) element.style.display = 'none';
                    }, 3000);
                </script>
            @endif
        </div>
    </form>
</section>
