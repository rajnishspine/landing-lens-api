<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('Image Analysis') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cloud-upload-alt me-2"></i>
                            Upload Image for LandingLens Analysis
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Validation Errors -->
                        @if($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Please fix the following issues:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Upload Form -->
                        <form id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="image" class="form-label">
                                    <strong>Select Image File</strong>
                                </label>
                                <input type="file" 
                                       class="form-control form-control-lg" 
                                       id="image" 
                                       name="image" 
                                       accept="image/jpeg,image/png,image/jpg"
                                       required>
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Supported formats: JPEG, PNG, JPG | Maximum size: 10MB
                                </div>
                                <div class="invalid-feedback" id="imageError" style="display: none;"></div>
                            </div>

                            <!-- Image Preview -->
                            <div class="mb-4" id="imagePreview" style="display: none;">
                                <label class="form-label"><strong>Preview</strong></label>
                                <div class="border rounded p-3 text-center image-preview">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 300px;">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="fas fa-magic me-2"></i>
                                    Analyze Image with LandingLens
                                </button>
                            </div>
                        </form>

                        <!-- Processing Indicator -->
                        <div class="text-center mt-4" id="processingIndicator" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Processing...</span>
                            </div>
                            <p class="mt-2 text-muted">
                                <i class="fas fa-brain me-2"></i>
                                AI is analyzing your image... This may take a few seconds.
                            </p>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>

                        <!-- Error Display -->
                        <div class="alert alert-danger mt-4" id="errorAlert" style="display: none;">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Error Processing Image
                            </h6>
                            <p class="mb-0" id="errorMessage"></p>
                            <hr>
                            <p class="mb-0 small" id="technicalError"></p>
                        </div>

                        <!-- Results Display Area -->
                        <div id="resultsContainer" style="display: none;"></div>
                    </div>
                </div>

                <!-- Instructions Card -->
                <div class="card shadow mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            How it works
                        </h6>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0">
                            <li>Upload an image file (JPEG or PNG format)</li>
                            <li>The image will be sent to LandingLens AI for analysis</li>
                            <li>Objects will be detected and highlighted with red bounding boxes</li>
                            <li>View the results with confidence scores and labels</li>
                        </ol>
                    </div>
                </div>

                <!-- Sample Images -->
                <div class="card shadow mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-image me-2"></i>
                            Sample Results
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Upload images containing objects like:</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-2">
                                    <i class="fas fa-car text-primary fs-3"></i>
                                    <p class="small mt-2 mb-0">Vehicles</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-2">
                                    <i class="fas fa-user text-success fs-3"></i>
                                    <p class="small mt-2 mb-0">People</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-2">
                                    <i class="fas fa-cube text-warning fs-3"></i>
                                    <p class="small mt-2 mb-0">Objects</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX Image Upload -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const uploadForm = document.getElementById('uploadForm');
            const submitBtn = document.getElementById('submitBtn');
            const processingIndicator = document.getElementById('processingIndicator');
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');
            const technicalError = document.getElementById('technicalError');
            const resultsContainer = document.getElementById('resultsContainer');
            const imageError = document.getElementById('imageError');

            // Hide error and results initially
            function hideMessages() {
                errorAlert.style.display = 'none';
                resultsContainer.style.display = 'none';
                processingIndicator.style.display = 'none';
                imageError.style.display = 'none';
                imageInput.classList.remove('is-invalid');
            }

            // Show error message
            function showError(message, technical = '') {
                errorMessage.textContent = message;
                technicalError.textContent = technical;
                errorAlert.style.display = 'block';
                processingIndicator.style.display = 'none';
                resetSubmitButton();
            }

            // Reset submit button
            function resetSubmitButton() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-magic me-2"></i>Analyze Image with LandingLens';
            }

            // Image preview functionality
            imageInput.addEventListener('change', function() {
                hideMessages();
                const file = this.files[0];
                
                if (file) {
                    // Validate file size
                    if (file.size > 10 * 1024 * 1024) {
                        imageError.textContent = 'File size must be less than 10MB';
                        imageError.style.display = 'block';
                        imageInput.classList.add('is-invalid');
                        this.value = '';
                        imagePreview.style.display = 'none';
                        return;
                    }

                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!allowedTypes.includes(file.type)) {
                        imageError.textContent = 'Please select a valid image file (JPEG, JPG, or PNG)';
                        imageError.style.display = 'block';
                        imageInput.classList.add('is-invalid');
                        this.value = '';
                        imagePreview.style.display = 'none';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });

            // AJAX Form submission
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                hideMessages();
                
                const file = imageInput.files[0];
                if (!file) {
                    showError('Please select an image to upload');
                    return;
                }

                // Show processing indicator
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
                processingIndicator.style.display = 'block';

                // Create FormData object
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', document.querySelector('input[name="_token"]').value);

                // Make AJAX request
                fetch('{{ route('predict.process') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    // Success - display results
                    if (data.success) {
                        displayResults(data.imageAnalysis);
                    } else {
                        showError(data.error || 'Unknown error occurred', data.technical_error || '');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Handle validation errors
                    if (error.errors && error.errors.image) {
                        imageError.textContent = error.errors.image[0];
                        imageError.style.display = 'block';
                        imageInput.classList.add('is-invalid');
                        resetSubmitButton();
                    } else {
                        showError(
                            error.message || error.error || 'Failed to process image. Please try again.',
                            error.technical_error || ''
                        );
                    }
                })
                .finally(() => {
                    resetSubmitButton();
                    processingIndicator.style.display = 'none';
                });
            });

            // Display results function
            function displayResults(imageAnalysis) {
                resultsContainer.innerHTML = generateResultsHTML(imageAnalysis);
                resultsContainer.style.display = 'block';
                
                // Scroll to results
                resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }

            // Generate results HTML
            function generateResultsHTML(analysis) {
                const objectsCount = analysis.objects_detected_count || 0;
                const avgConfidence = analysis.average_confidence || 0;
                
                let detectionTableHTML = '';
                if (analysis.detected_objects && analysis.detected_objects.length > 0) {
                    detectionTableHTML = `
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Object</th>
                                        <th>Confidence</th>
                                        <th>Position</th>
                                        <th>Size</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${analysis.detected_objects.map((obj, index) => `
                                        <tr>
                                            <td><span class="badge bg-secondary">${index + 1}</span></td>
                                            <td><span class="badge bg-primary">${obj.label_name}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress me-2" style="width: 60px; height: 16px;">
                                                        <div class="progress-bar ${obj.confidence_score >= 0.8 ? 'bg-success' : obj.confidence_score >= 0.5 ? 'bg-warning' : 'bg-danger'}" 
                                                             style="width: ${obj.confidence_score * 100}%"></div>
                                                    </div>
                                                    <small class="fw-bold">${(obj.confidence_score * 100).toFixed(1)}%</small>
                                                </div>
                                            </td>
                                            <td><code class="small">(${obj.x_min}, ${obj.y_min}) → (${obj.x_max}, ${obj.y_max})</code></td>
                                            <td><code class="small">${obj.x_max - obj.x_min} × ${obj.y_max - obj.y_min}</code></td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                }

                return `
                    <div class="card shadow mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Analysis Complete!
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Image Display -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Original Image</h6>
                                    <div class="border rounded p-2">
                                        <img src="${analysis.original_image_url}" alt="Original" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: contain;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Processed Image</h6>
                                    <div class="border rounded p-2">
                                        ${analysis.processed_image_url ? 
                                            `<img src="${analysis.processed_image_url}" alt="Processed" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: contain;">` 
                                            : '<div class="text-center text-muted p-5">Processing...</div>'
                                        }
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics -->
                            <div class="row text-center mb-4">
                                <div class="col-md-4">
                                    <h4 class="text-primary">${objectsCount}</h4>
                                    <small class="text-muted">Objects Detected</small>
                                </div>
                                <div class="col-md-4">
                                    <h4 class="text-success">${(avgConfidence * 100).toFixed(1)}%</h4>
                                    <small class="text-muted">Average Confidence</small>
                                </div>
                                <div class="col-md-4">
                                    <h4 class="text-info">${analysis.formatted_file_size || 'N/A'}</h4>
                                    <small class="text-muted">File Size</small>
                                </div>
                            </div>

                            <!-- Detection Results -->
                            ${objectsCount > 0 ? `
                                <div class="mb-4">
                                    <h6><i class="fas fa-list me-2"></i>Detection Results</h6>
                                    ${detectionTableHTML}
                                </div>
                            ` : `
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No objects were detected in this image.
                                </div>
                            `}

                            <!-- Actions -->
                            <div class="text-center">
                                <button class="btn btn-primary me-2" onclick="document.getElementById('uploadForm').reset(); document.getElementById('imagePreview').style.display='none'; document.getElementById('resultsContainer').style.display='none';">
                                    <i class="fas fa-upload me-2"></i>Upload Another Image
                                </button>
                                ${analysis.processed_image_url ? `
                                    <a href="${analysis.processed_image_url}" download class="btn btn-success">
                                        <i class="fas fa-download me-2"></i>Download Result
                                    </a>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    </script>

    <style>
        /* Enhanced styling for AJAX upload */
        .image-preview {
            transition: all 0.3s ease;
        }
        
        .image-preview:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        #processingIndicator {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #resultsContainer {
            animation: slideInUp 0.5s ease-out;
        }
        
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .progress-bar-striped {
            background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
            background-size: 1rem 1rem;
        }
        
        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }
        
        @keyframes progress-bar-stripes {
            0% { background-position: 1rem 0; }
            100% { background-position: 0 0; }
        }
        
        /* Smooth transitions for error states */
        .is-invalid {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        /* File drop zone styling */
        .form-control:hover {
            border-color: var(--color-primary);
            transition: border-color 0.3s ease;
        }
        
        /* Results container styling */
        #resultsContainer .card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        #resultsContainer .card-header {
            background: linear-gradient(135deg, var(--color-success) 0%, var(--color-emerald) 100%);
        }
    </style>
</x-app-layout> 