<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('Analysis History') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <!-- Header Actions -->
            <div class="col-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Your Image Analysis History</h5>
                        <p class="text-muted mb-0">View and manage your previous image analyses</p>
                    </div>
                    <div>
                        <a href="{{ route('predict.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            New Analysis
                        </a>
                    </div>
                </div>
            </div>

            <!-- Analysis Statistics -->
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="text-primary">{{ auth()->user()->total_completed_analyses }}</h4>
                                <small class="text-muted">Total Analyses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="text-success">{{ auth()->user()->total_objects_detected }}</h4>
                                <small class="text-muted">Objects Detected</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="text-info">{{ number_format(auth()->user()->average_processing_time, 2) }}s</h4>
                                <small class="text-muted">Avg Processing Time</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h4 class="text-warning">{{ auth()->user()->recentAnalyses()->count() }}</h4>
                                <small class="text-muted">This Month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analysis List -->
            <div class="col-12">
                @if($analyses->count() > 0)
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-history me-2"></i>
                                Recent Analyses ({{ $analyses->total() }} total)
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Image</th>
                                            <th>Filename</th>
                                            <th>Objects</th>
                                            <th>Status</th>
                                            <th>Processed</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($analyses as $analysis)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($analysis->original_image_url)
                                                            <img src="{{ $analysis->original_image_url }}" 
                                                                 alt="Thumbnail" 
                                                                 class="rounded"
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ Str::limit($analysis->original_filename, 30) }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $analysis->formatted_file_size }} • 
                                                            {{ ucfirst($analysis->mime_type) }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <span class="badge {{ $analysis->objects_detected_count > 0 ? 'bg-success' : 'bg-secondary' }} fs-6">
                                                            {{ $analysis->objects_detected_count }}
                                                        </span>
                                                        @if($analysis->objects_detected_count > 0)
                                                            <br>
                                                            <small class="text-muted">
                                                                {{ number_format($analysis->average_confidence * 100, 1) }}% avg
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $analysis->isCompleted() ? 'bg-success' : ($analysis->isFailed() ? 'bg-danger' : 'bg-warning') }}">
                                                        {{ ucfirst($analysis->status) }}
                                                    </span>
                                                    @if($analysis->total_latency_seconds)
                                                        <br>
                                                        <small class="text-muted">{{ number_format($analysis->total_latency_seconds, 2) }}s</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $analysis->created_at->format('M j, Y') }}
                                                        <br>
                                                        <small class="text-muted">{{ $analysis->created_at->format('g:i A') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if($analysis->isCompleted())
                                                            <a href="{{ route('predict.process') }}?id={{ $analysis->id }}" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if($analysis->processed_image_url)
                                                                <a href="{{ $analysis->processed_image_url }}" 
                                                                   download="{{ $analysis->original_filename }}_processed.jpg" 
                                                                   class="btn btn-sm btn-outline-success">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @endif
                                                        @elseif($analysis->isFailed())
                                                            <button class="btn btn-sm btn-outline-danger" 
                                                                    title="{{ $analysis->error_message }}">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-outline-warning" disabled>
                                                                <i class="fas fa-clock"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        @if($analyses->hasPages())
                            <div class="card-footer">
                                {{ $analyses->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-image text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted">No analyses yet</h5>
                        <p class="text-muted">Upload your first image to get started with AI-powered object detection.</p>
                        <a href="{{ route('predict.index') }}" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>
                            Upload Your First Image
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>