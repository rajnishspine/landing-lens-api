<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <!-- Welcome Message -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Welcome back, {{ Auth::user()->name }}!</h5>
                        <p class="mb-0">Ready to analyze some images with {{ config('app.name', 'Venus') }}? Upload an image to get started.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Cards -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-cloud-upload-alt text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Upload & Analyze</h5>
                        <p class="card-text">
                            Upload an image and let {{ config('app.name', 'Venus') }} detect and identify objects with bounding boxes and confidence scores.
                        </p>
                        <a href="{{ route('predict.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-magic me-2"></i>
                            Start Analysis
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-chart-line text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">How It Works</h5>
                        <p class="card-text">
                            Learn about the image analysis process and see examples of object detection capabilities.
                        </p>
                        <button class="btn btn-outline-success btn-lg" data-bs-toggle="modal" data-bs-target="#howItWorksModal">
                            <i class="fas fa-question-circle me-2"></i>
                            Learn More
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Overview -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-star me-2"></i>
                            Key Features
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-bullseye text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1">Object Detection</h6>
                                        <small class="text-muted">Automatically detect and identify objects in your images</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-square text-danger me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1">Bounding Boxes</h6>
                                        <small class="text-muted">Visual red rectangles highlight detected objects</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-percentage text-warning me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1">Confidence Scores</h6>
                                        <small class="text-muted">Get accuracy percentages for each detection</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title mb-3">System Status</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h5 class="text-primary mb-0">✓</h5>
                                    <small class="text-muted">LandingLens API</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h5 class="text-success mb-0">✓</h5>
                                    <small class="text-muted">Image Processing</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h5 class="text-info mb-0">✓</h5>
                                    <small class="text-muted">Secure Upload</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="text-warning mb-0">{{ PHP_VERSION }}</h5>
                                <small class="text-muted">PHP Version</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works Modal -->
    <div class="modal fade" id="howItWorksModal" tabindex="-1" aria-labelledby="howItWorksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="howItWorksModalLabel">
                        <i class="fas fa-cogs me-2"></i>
                        How LandingLens Analysis Works
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <span class="badge bg-primary rounded-pill me-3 mt-1">1</span>
                                <div>
                                    <h6>Upload Your Image</h6>
                                    <p class="mb-0 text-muted">Select a JPEG or PNG image file (up to 10MB) from your device.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <span class="badge bg-primary rounded-pill me-3 mt-1">2</span>
                                <div>
                                    <h6>API Processing</h6>
                                    <p class="mb-0 text-muted">Your image is securely sent to {{ config('app.name', 'Venus') }} for analysis.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <span class="badge bg-primary rounded-pill me-3 mt-1">3</span>
                                <div>
                                    <h6>Object Detection</h6>
                                    <p class="mb-0 text-muted">AI algorithms identify objects and provide coordinates and confidence scores.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <span class="badge bg-primary rounded-pill me-3 mt-1">4</span>
                                <div>
                                    <h6>Visual Results</h6>
                                    <p class="mb-0 text-muted">Red bounding boxes are drawn around detected objects, and results are displayed in a table.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('predict.index') }}" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>
                        Try It Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
