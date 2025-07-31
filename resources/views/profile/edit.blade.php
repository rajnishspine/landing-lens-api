<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-user-cog me-2 text-primary"></i>
                {{ __('Profile Settings') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="profile-page-wrapper">
        <div class="container-fluid py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-xl-10">
                    
                    <!-- Success Messages -->
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3 fs-4"></i>
                                <div>
                                    <strong>Profile Updated!</strong><br>
                                    <small>Your profile information has been saved successfully.</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt text-success me-3 fs-4"></i>
                                <div>
                                    <strong>Password Updated!</strong><br>
                                    <small>Your password has been changed successfully.</small>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Profile Overview Card -->
                    <div class="card profile-overview-card shadow-lg mb-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="profile-avatar">
                                        <i class="fas fa-user-circle text-primary"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                                    <p class="text-muted mb-1">{{ Auth::user()->email }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Member since {{ Auth::user()->created_at->format('F Y') }}
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <div class="profile-stats">
                                        <div class="stat-item">
                                            <div class="stat-number">{{ Auth::user()->imageAnalyses()->count() }}</div>
                                            <div class="stat-label">Analyses</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabbed Interface -->
                    <div class="profile-tabs-container">
                        <nav class="profile-tabs">
                            <button class="profile-tab active" data-tab="profile">
                                <i class="fas fa-user me-2"></i>
                                Profile Info
                            </button>
                            <button class="profile-tab" data-tab="security">
                                <i class="fas fa-shield-alt me-2"></i>
                                Security
                            </button>
                            <button class="profile-tab" data-tab="danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Account
                            </button>
                        </nav>

                        <!-- Tab Content -->
                        <div class="profile-tab-content">
                            
                            <!-- Profile Information Tab -->
                            <div class="tab-pane active" id="profile-tab">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-gradient-primary text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-edit me-2"></i>
                                            Update Profile Information
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        @include('profile.partials.update-profile-information-form')
                                    </div>
                                </div>
                            </div>

                            <!-- Security Tab -->
                            <div class="tab-pane" id="security-tab">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-gradient-warning text-dark">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-key me-2"></i>
                                            Change Password
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        @include('profile.partials.update-password-form')
                                    </div>
                                </div>
                            </div>

                            <!-- Danger Zone Tab -->
                            <div class="tab-pane" id="danger-tab">
                                <div class="card shadow-sm border-danger">
                                    <div class="card-header bg-gradient-danger text-white">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-trash-alt me-2"></i>
                                            Delete Account
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        @include('profile.partials.delete-user-form')
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern Profile Page Styling */
        .profile-page-wrapper {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: calc(100vh - 76px);
        }

        .profile-overview-card {
            border: none;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .profile-avatar i {
            font-size: 4rem;
            opacity: 0.8;
        }

        .profile-stats {
            text-align: center;
        }

        .stat-item {
            padding: 1rem;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            min-width: 80px;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .stat-label {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        /* Tab System */
        .profile-tabs-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            margin: 0;
            padding: 0;
        }

        .profile-tab {
            flex: 1;
            padding: 1rem 1.5rem;
            border: none;
            background: none;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .profile-tab:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .profile-tab.active {
            background: white;
            color: #667eea;
            border-bottom: 3px solid #667eea;
        }

        .profile-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .profile-tab-content {
            padding: 0;
        }

        .tab-pane {
            display: none;
            padding: 2rem;
        }

        .tab-pane.active {
            display: block;
        }

        /* Card Headers with Gradients */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%) !important;
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important;
        }

        /* Enhanced Cards */
        .card {
            border: none;
            border-radius: 12px;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
            border-bottom: none;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .profile-tabs {
                flex-direction: column;
            }

            .profile-tab {
                text-align: left;
                border-bottom: 1px solid #dee2e6;
            }

            .profile-tab.active::after {
                left: 0;
                width: 4px;
                height: 100%;
                top: 0;
                bottom: auto;
            }

            .profile-overview-card .row {
                text-align: center;
            }

            .profile-overview-card .col-auto,
            .profile-overview-card .col {
                flex: 0 0 100%;
                margin-bottom: 1rem;
            }
        }

        /* Animation */
        .tab-pane {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        // Tab System JavaScript - Non-conflicting with partial scripts
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.profile-tab');
            const tabPanes = document.querySelectorAll('.tab-pane');

            if (tabs.length > 0 && tabPanes.length > 0) {
                tabs.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const targetTab = this.getAttribute('data-tab');

                        // Remove active class from all tabs and panes
                        tabs.forEach(t => t.classList.remove('active'));
                        tabPanes.forEach(p => p.classList.remove('active'));

                        // Add active class to clicked tab
                        this.classList.add('active');

                        // Show corresponding tab pane
                        const targetPane = document.getElementById(targetTab + '-tab');
                        if (targetPane) {
                            targetPane.classList.add('active');
                        }
                    });
                });
            }

            // Test delete form functionality
            console.log('Profile page loaded successfully');
            console.log('Delete form elements check:', {
                deleteInitial: !!document.getElementById('deleteInitial'),
                deleteFormContainer: !!document.getElementById('deleteFormContainer'),
                deleteAccountForm: !!document.getElementById('deleteAccountForm'),
                finalDeleteButton: !!document.getElementById('finalDeleteButton')
            });
        });
    </script>
</x-app-layout>
