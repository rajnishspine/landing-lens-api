<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('Image Analysis') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-9">
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
                            
                            <!-- Professional Drag & Drop Upload Zone -->
                            <div class="mb-4">
                                <label class="form-label mb-3">
                                    <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>
                                    <strong>Upload Image for AI Analysis</strong>
                                </label>
                                
                                <!-- Drag & Drop Zone -->
                                <div class="drag-drop-zone" id="dragDropZone">
                                    <div class="drag-drop-content">
                                        <div class="drag-drop-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <h6 class="drag-drop-title">Drag & Drop Your Image Here</h6>
                                        <p class="drag-drop-subtitle">or click to browse files</p>
                                        <div class="drag-drop-formats">
                                            <span class="format-badge">JPEG</span>
                                            <span class="format-badge">PNG</span>
                                            <span class="format-badge">JPG</span>
                                        </div>
                                        <small class="drag-drop-size">Maximum size: 10MB</small>
                                    </div>
                                    
                                    <!-- Hidden File Input -->
                                    <input type="file" 
                                           id="image" 
                                           name="image" 
                                           accept="image/jpeg,image/png,image/jpg"
                                           required
                                           style="display: none;">
                                </div>
                                
                                <!-- Error Display -->
                                <div class="invalid-feedback" id="imageError" style="display: none;"></div>
                                
                                <!-- Upload Status -->
                                <div class="upload-status mt-3" id="uploadStatus" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <span class="file-name"></span>
                                        <span class="file-size ms-auto text-muted"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Preview -->
                            <div class="mb-4" id="imagePreview" style="display: none;">
                                <label class="form-label"><strong>Preview</strong></label>
                                <div class="border rounded p-3 text-center image-preview">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 270px;">
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
                

                <!-- Sample Images -->
                <!-- <div class="card shadow mt-4">
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
                </div> -->
            </div>
            <div class="col-md-3">
                <div class="card shadow">
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
            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX Image Upload -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadForm = document.getElementById('uploadForm');
            const submitBtn = document.getElementById('submitBtn');
            const processingIndicator = document.getElementById('processingIndicator');
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');
            const technicalError = document.getElementById('technicalError');
            const resultsContainer = document.getElementById('resultsContainer');

            // Hide error and results initially
            function hideAjaxMessages() {
                errorAlert.style.display = 'none';
                resultsContainer.style.display = 'none';
                processingIndicator.style.display = 'none';
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

            // Note: Image validation and preview is now handled by the drag-drop system above

            // AJAX Form submission
            uploadForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                
                hideAjaxMessages();
                
                const file = fileInput.files[0];
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
                        showFileError(error.errors.image[0]);
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
                
                // Initialize GLightbox for new content
                initializeGLightbox();
                
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
                                    <h6 class="text-muted">
                                        <i class="fas fa-image me-1"></i>
                                        Original Image
                                        <small class="text-info">
                                            <i class="fas fa-search-plus ms-2"></i>
                                            Click to enlarge
                                        </small>
                                    </h6>
                                    <div class="border rounded p-2">
                                        <a href="${analysis.original_image_url}" class="glightbox" data-gallery="analysis-gallery" data-title="Original Image - ${analysis.original_filename}" data-description="Original uploaded image">
                                            <img src="${analysis.original_image_url}" alt="Original" class="img-fluid rounded image-hover" style="max-height: 300px; width: 100%; object-fit: contain;">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">
                                        <i class="fas fa-robot me-1"></i>
                                        AI Processed Image
                                        <small class="text-info">
                                            <i class="fas fa-search-plus ms-2"></i>
                                            Click to enlarge
                                        </small>
                                    </h6>
                                    <div class="border rounded p-2">
                                        ${analysis.processed_image_url ? 
                                            `<a href="${analysis.processed_image_url}" class="glightbox" data-gallery="analysis-gallery" data-title="AI Processed Image - ${analysis.original_filename}" data-description="AI processed image with object detection markers">
                                                <img src="${analysis.processed_image_url}" alt="Processed" class="img-fluid rounded image-hover" style="max-height: 300px; width: 100%; object-fit: contain;">
                                            </a>` 
                                            : '<div class="text-center text-muted p-5"><div class="spinner-border text-primary mb-2" role="status"></div><br>AI Processing...</div>'
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

            // Professional Drag & Drop Functionality
            const dragDropZone = document.getElementById('dragDropZone');
            const fileInput = document.getElementById('image');
            const uploadStatus = document.getElementById('uploadStatus');
            const imageError = document.getElementById('imageError');
            const imagePreviewDiv = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            // Click to browse files
            dragDropZone.addEventListener('click', () => {
                fileInput.click();
            });

            // Drag and drop events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dragDropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Visual feedback for drag states
            ['dragenter', 'dragover'].forEach(eventName => {
                dragDropZone.addEventListener(eventName, () => {
                    dragDropZone.classList.add('drag-over');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dragDropZone.addEventListener(eventName, () => {
                    dragDropZone.classList.remove('drag-over');
                }, false);
            });

            // Handle dropped files
            dragDropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelection(files[0]);
                }
            });

            // Handle file input change
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleFileSelection(e.target.files[0]);
                }
            });

            // File selection handler
            function handleFileSelection(file) {
                hideMessages(); // Hide drag-drop specific messages
                
                // Validate file
                const validation = validateFile(file);
                if (!validation.valid) {
                    showFileError(validation.error);
                    return;
                }

                // Update file input
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;

                // Show upload status
                showUploadStatus(file);
                
                // Show preview
                showImagePreview(file);
            }

            // File validation
            function validateFile(file) {
                // Check file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    return { valid: false, error: 'File size must be less than 10MB' };
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    return { valid: false, error: 'Please select a valid image file (JPEG, JPG, or PNG)' };
                }

                return { valid: true };
            }

            // Show file error
            function showFileError(message) {
                imageError.textContent = message;
                imageError.style.display = 'block';
                fileInput.classList.add('is-invalid');
                dragDropZone.style.borderColor = 'var(--color-danger)';
                
                // Reset border after 3 seconds
                setTimeout(() => {
                    dragDropZone.style.borderColor = '';
                }, 3000);
            }

            // Show upload status
            function showUploadStatus(file) {
                const fileName = uploadStatus.querySelector('.file-name');
                const fileSize = uploadStatus.querySelector('.file-size');
                
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                uploadStatus.style.display = 'block';
            }

            // Show image preview
            function showImagePreview(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreviewDiv.style.display = 'block';
                    
                    // Add GLightbox functionality to preview
                    setupPreviewLightbox(e.target.result);
                };
                reader.readAsDataURL(file);
            }

            // Setup preview with GLightbox
            function setupPreviewLightbox(imageSrc) {
                const imagePreviewDiv = document.getElementById('imagePreview');
                if (imagePreviewDiv) {
                    // Remove existing click hint if present
                    const existingHint = imagePreviewDiv.querySelector('.click-hint');
                    if (existingHint) existingHint.remove();
                    
                    const clickHint = document.createElement('small');
                    clickHint.className = 'text-info d-block text-center mt-2 click-hint';
                    clickHint.innerHTML = '<i class="fas fa-search-plus me-1"></i>Click image to enlarge';
                    imagePreviewDiv.appendChild(clickHint);
                    
                    // Make preview image clickable with GLightbox
                    previewImg.style.cursor = 'pointer';
                    previewImg.classList.add('image-hover');
                    previewImg.onclick = function() {
                        if (this.src) {
                            const tempGallery = GLightbox({
                                elements: [{
                                    href: this.src,
                                    type: 'image',
                                    title: 'Upload Preview',
                                    description: 'Preview of uploaded image'
                                }]
                            });
                            tempGallery.open();
                        }
                    };
                }
            }

            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Hide all messages
            function hideMessages() {
                imageError.style.display = 'none';
                fileInput.classList.remove('is-invalid');
                uploadStatus.style.display = 'none';
                dragDropZone.style.borderColor = '';
            }

            // Initialize GLightbox for dynamic content
            window.initializeGLightbox = function() {
                if (typeof GLightbox !== 'undefined') {
                    GLightbox({
                        selector: '.glightbox',
                        touchNavigation: true,
                        loop: true,
                        autoplayVideos: false,
                        zoomable: true,
                        draggable: true,
                        closeButton: true,
                        moreText: 'See more',
                        download: true,
                        counter: true,
                        skin: 'clean'
                    });
                }
            };

            // Initialize GLightbox when page loads
            setTimeout(initializeGLightbox, 100);
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
        
        /* GLightbox Image Hover Effects */
        .image-hover {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-hover:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            filter: brightness(1.05);
        }
        
        /* GLightbox Custom Styling */
        .glightbox-clean .gslide-description {
            background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.6) 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            font-family: var(--font-family-primary);
        }
        
        .glightbox-clean .gdesc-inner h4 {
            color: white;
            font-weight: var(--font-weight-semibold);
        }

        /* Professional Drag & Drop Zone Styling */
        .drag-drop-zone {
            border: 3px dashed var(--color-primary);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 50%, #f1f5f9 100%);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drag-drop-zone::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .drag-drop-zone:hover::before {
            left: 100%;
        }

        .drag-drop-zone:hover {
            border-color: var(--color-secondary);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #e2e8f0 100%);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.15);
        }

        .drag-drop-zone.drag-over {
            border-color: var(--color-accent);
            background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 50%, #81d4fa 100%);
            transform: scale(1.02);
            box-shadow: 0 25px 50px rgba(6, 182, 212, 0.25);
        }

        .drag-drop-zone.drag-over .drag-drop-icon i {
            animation: bounce 0.6s ease-in-out;
            color: var(--color-accent);
        }

        .drag-drop-content {
            position: relative;
            z-index: 2;
        }

        .drag-drop-icon {
            margin-bottom: 1.5rem;
        }

        .drag-drop-icon i {
            font-size: 4rem;
            color: var(--color-primary);
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(99, 102, 241, 0.2));
        }

        .drag-drop-zone:hover .drag-drop-icon i {
            color: var(--color-secondary);
            transform: scale(1.1);
        }

        .drag-drop-title {
            font-weight: var(--font-weight-semibold);
            color: var(--color-text-primary);
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .drag-drop-subtitle {
            color: var(--color-text-secondary);
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }

        .drag-drop-formats {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .format-badge {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: var(--font-weight-medium);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
        }

        .drag-drop-size {
            color: var(--color-text-muted);
            font-size: 0.875rem;
            font-weight: var(--font-weight-medium);
        }

        /* Upload Status Styling */
        .upload-status {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid var(--color-success);
            border-radius: 12px;
            padding: 1rem;
            animation: slideInUp 0.5s ease-out;
        }

        .upload-status .file-name {
            font-weight: var(--font-weight-semibold);
            color: var(--color-text-primary);
        }

        .upload-status .file-size {
            font-size: 0.875rem;
        }

        /* Animations */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .drag-drop-zone {
                padding: 2rem 1rem;
                min-height: 220px;
            }
            
            .drag-drop-icon i {
                font-size: 3rem;
            }
            
            .drag-drop-title {
                font-size: 1.1rem;
            }
            
            .drag-drop-formats {
                flex-wrap: wrap;
            }
        }
    </style>

    <!-- GLightbox CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
</x-app-layout> 