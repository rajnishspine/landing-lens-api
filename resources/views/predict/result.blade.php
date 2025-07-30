<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('Analysis Results') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <!-- Back Button -->
            <div class="col-12 mb-3">
                <a href="{{ route('predict.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Upload Another Image
                </a>
            </div>

            <!-- Success Alert -->
            @if($imageAnalysis->isCompleted())
                <div class="col-12 mb-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Success!</strong> Your image has been processed successfully. 
                        <strong>{{ $imageAnalysis->objects_detected_count }}</strong> objects detected.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @elseif($imageAnalysis->isFailed())
                <div class="col-12 mb-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Error!</strong> {{ $imageAnalysis->error_message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <!-- Image Analysis Info -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h6 class="mb-1">
                                    <i class="fas fa-file-image me-2 text-primary"></i>
                                    {{ $imageAnalysis->original_filename }}
                                </h6>
                                <small class="text-muted">
                                    {{ $imageAnalysis->formatted_file_size }} • 
                                    {{ ucfirst($imageAnalysis->mime_type) }} • 
                                    Processed {{ $imageAnalysis->created_at->diffForHumans() }}
                                    @if($imageAnalysis->total_latency_seconds)
                                        • API Time: {{ number_format($imageAnalysis->total_latency_seconds, 2) }}s
                                    @endif
                                </small>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge {{ $imageAnalysis->isCompleted() ? 'bg-success' : ($imageAnalysis->isFailed() ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($imageAnalysis->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Comparison -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image me-2"></i>
                            Original Image
                        </h5>
                    </div>
                    <div class="card-body text-center p-2">
                        <div class="image-container" style="max-height: 450px; overflow: hidden; border-radius: 0.5rem; border: 2px solid #e9ecef;">
                            <img src="{{ $imageAnalysis->original_image_url }}" 
                                 alt="Original Image" 
                                 class="img-fluid w-100 h-100" 
                                 style="object-fit: contain; max-height: 446px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bullseye me-2"></i>
                            Processed Image (with Detections)
                        </h5>
                    </div>
                    <div class="card-body text-center p-2">
                        @if($imageAnalysis->processed_image_url)
                            <div class="image-container" style="max-height: 450px; overflow: hidden; border-radius: 0.5rem; border: 2px solid #e9ecef;">
                                <img src="{{ $imageAnalysis->processed_image_url }}" 
                                     alt="Processed Image" 
                                     class="img-fluid w-100 h-100" 
                                     style="object-fit: contain; max-height: 446px;">
                            </div>
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="height: 450px; background-color: #f8f9fa; border-radius: 0.5rem;">
                                <div class="text-center">
                                    <i class="fas fa-spinner fa-spin fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Processing image...</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($imageAnalysis->objects_detected_count === 0 && $imageAnalysis->isCompleted())
                            <p class="text-muted mt-3 mb-1">
                                <i class="fas fa-search me-1"></i>
                                No objects detected in this image.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detection Results -->
            @if($imageAnalysis->objects_detected_count > 0)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>
                                Detection Results ({{ $imageAnalysis->objects_detected_count }} objects found)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="8%">#</th>
                                            <th width="20%">Object</th>
                                            <th width="25%">Confidence</th>
                                            <th width="20%">Position</th>
                                            <th width="15%">Size</th>
                                            <th width="12%">Area</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($imageAnalysis->detectedObjects as $index => $object)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary fs-6">{{ $object->label_name }}</span>
                                                    @if($object->defect_id)
                                                        <br><small class="text-muted">ID: {{ $object->defect_id }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress me-2" style="width: 80px; height: 18px;">
                                                            <div class="progress-bar 
                                                                @if($object->isHighConfidence()) bg-success 
                                                                @elseif($object->isMediumConfidence()) bg-warning 
                                                                @else bg-danger @endif" 
                                                                 role="progressbar" 
                                                                 style="width: {{ ($object->confidence_score * 100) }}%">
                                                            </div>
                                                        </div>
                                                        <small class="fw-bold {{ $object->confidence_color_class }}">
                                                            {{ $object->confidence_percentage }}
                                                        </small>
                                                    </div>
                                                    <small class="text-muted d-block">{{ ucfirst($object->confidence_level) }}</small>
                                                </td>
                                                <td>
                                                    <code class="small">{{ $object->formatted_coordinates }}</code>
                                                </td>
                                                <td>
                                                    <code class="small">{{ $object->width }} × {{ $object->height }}</code>
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ number_format($object->area) }}px</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Detection Statistics
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-primary mb-0">{{ $imageAnalysis->objects_detected_count }}</h4>
                                        <small class="text-muted">Objects Detected</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-success mb-0">{{ number_format($imageAnalysis->average_confidence * 100, 1) }}%</h4>
                                        <small class="text-muted">Average Confidence</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-end">
                                        <h4 class="text-info mb-0">{{ number_format($imageAnalysis->detectedObjects->max('confidence_score') * 100, 1) }}%</h4>
                                        <small class="text-muted">Highest Score</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="text-warning mb-0">{{ $imageAnalysis->detectedObjects->pluck('label_name')->unique()->count() }}</h4>
                                    <small class="text-muted">Unique Labels</small>
                                </div>
                            </div>
                            
                            <!-- Confidence Level Breakdown -->
                            <div class="row mt-4 text-center">
                                <div class="col-md-4">
                                    <div class="p-2 bg-success bg-opacity-10 rounded">
                                        <h5 class="text-success mb-1">{{ $imageAnalysis->detectedObjects->where('confidence_score', '>=', 0.8)->count() }}</h5>
                                        <small class="text-muted">High Confidence (≥80%)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 bg-warning bg-opacity-10 rounded">
                                        <h5 class="text-warning mb-1">{{ $imageAnalysis->detectedObjects->whereBetween('confidence_score', [0.5, 0.8])->count() }}</h5>
                                        <small class="text-muted">Medium Confidence (50-80%)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 bg-danger bg-opacity-10 rounded">
                                        <h5 class="text-danger mb-1">{{ $imageAnalysis->detectedObjects->where('confidence_score', '<', 0.5)->count() }}</h5>
                                        <small class="text-muted">Low Confidence (<50%)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Raw API Response (for debugging) -->
            @if(config('app.debug') && $imageAnalysis->full_api_response)
                <div class="col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-code me-2"></i>
                                Raw API Response (Debug Mode)
                            </h6>
                        </div>
                        <div class="card-body">
                            <details>
                                <summary class="mb-3 text-muted cursor-pointer">Click to expand API response data</summary>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted">Analysis Metadata:</h6>
                                        <ul class="list-unstyled small">
                                            <li><strong>Model ID:</strong> {{ $imageAnalysis->model_id ?? 'N/A' }}</li>
                                            <li><strong>Backbone Type:</strong> {{ $imageAnalysis->backbone_type ?? 'N/A' }}</li>
                                            <li><strong>Processing Time:</strong> {{ $imageAnalysis->total_latency_seconds }}s</li>
                                            <li><strong>Status:</strong> {{ $imageAnalysis->status }}</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted">Full API Response:</h6>
                                        <pre class="bg-light p-3 rounded small" style="max-height: 250px; overflow-y: auto;"><code>{{ json_encode($imageAnalysis->full_api_response, JSON_PRETTY_PRINT) }}</code></pre>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="card-title">What would you like to do next?</h6>
                        <div class="d-flex gap-2 justify-content-center flex-wrap mt-3">
                            <a href="{{ route('predict.index') }}" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>
                                Analyze Another Image
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-home me-2"></i>
                                Back to Dashboard
                            </a>
                            @if($imageAnalysis->processed_image_url)
                                <a href="{{ $imageAnalysis->processed_image_url }}" download="{{ $imageAnalysis->original_filename }}_processed.jpg" class="btn btn-outline-success">
                                    <i class="fas fa-download me-2"></i>
                                    Download Result
                                </a>
                            @endif
                            @if($imageAnalysis->objects_detected_count > 0)
                                <button class="btn btn-outline-info" onclick="shareResults()">
                                    <i class="fas fa-share-alt me-2"></i>
                                    Share Results
                                </button>
                            @endif
                            <a href="{{ route('predict.index') }}#history" class="btn btn-outline-warning">
                                <i class="fas fa-history me-2"></i>
                                View History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for additional functionality -->
    <script>
        function shareResults() {
            if (navigator.share) {
                navigator.share({
                    title: 'LandingLens Analysis Results',
                    text: 'Check out my image analysis results from LandingLens AI!',
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Results link copied to clipboard!');
                });
            }
        }

        // Auto-highlight table rows on hover
        document.querySelectorAll('table tbody tr').forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    </script>

    <style>
        .cursor-pointer {
            cursor: pointer;
        }
        
        details summary {
            outline: none;
        }
        
        .progress {
            background-color: #e9ecef;
        }
        
        .table th {
            border-top: none;
        }
        
        .image-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        
        .image-container img {
            display: block;
            max-width: 100%;
            height: auto;
        }
        
        /* Ensure proper responsive behavior */
        @media (max-width: 768px) {
            .image-container {
                max-height: 300px !important;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
        }
    </style>
</x-app-layout> 