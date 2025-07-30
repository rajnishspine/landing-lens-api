<?php

namespace App\Services;

use App\Models\ImageAnalysis;
use App\Models\DetectedObject;
use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class LandingLensService
{
    private string $apiKey;
    private string $endpointId;
    private string $baseUrl = 'https://api.landing.ai/inference/v1/predict';
    
    public function __construct()
    {
        $this->apiKey = config('services.landinglens.api_key');
        $this->endpointId = config('services.landinglens.endpoint_id');
        
        if (empty($this->apiKey) || empty($this->endpointId)) {
            throw new Exception('LandingLens API credentials are not configured properly');
        }
    }
    
    /**
     * Send image to LandingLens API for prediction and save to database
     */
    public function predict(UploadedFile $image, User $user): ImageAnalysis
    {
        return DB::transaction(function () use ($image, $user) {
            try {
                // Validate image
                $this->validateImage($image);
                
                // Create initial database record
                $imageAnalysis = $this->createImageAnalysisRecord($image, $user);
                
                // Store original image
                $originalPath = $this->storeOriginalImage($image, $imageAnalysis);
                $imageAnalysis->update(['original_image_path' => $originalPath]);
                
                // Mark as processing
                $imageAnalysis->update(['status' => 'processing']);
                
                // Send to API
                $apiResponse = $this->callLandingLensAPI($image);
                
                // Process API response and save detected objects
                $this->processAPIResponse($apiResponse, $imageAnalysis);
                
                // Create processed image with bounding boxes
                $processedPath = $this->createProcessedImage($imageAnalysis);
                $imageAnalysis->update(['processed_image_path' => $processedPath]);
                
                // Mark as completed
                $imageAnalysis->markAsCompleted();
                
                return $imageAnalysis->fresh(['detectedObjects']);
                
            } catch (Exception $e) {
                Log::error('LandingLens Processing Error: ' . $e->getMessage());
                
                if (isset($imageAnalysis)) {
                    $imageAnalysis->markAsFailed($e->getMessage());
                }
                
                throw $e;
            }
        });
    }
    
    /**
     * Create initial image analysis record
     */
    private function createImageAnalysisRecord(UploadedFile $image, User $user): ImageAnalysis
    {
        return ImageAnalysis::create([
            'user_id' => $user->id,
            'original_filename' => $image->getClientOriginalName(),
            'mime_type' => $image->getMimeType(),
            'file_size' => $image->getSize(),
            'status' => 'pending',
        ]);
    }
    
    /**
     * Store original uploaded image
     */
    private function storeOriginalImage(UploadedFile $image, ImageAnalysis $imageAnalysis): string
    {
        $filename = 'original_' . $imageAnalysis->id . '_' . time() . '.' . $image->getClientOriginalExtension();
        $path = 'uploads/analyses/' . $imageAnalysis->id . '/' . $filename;
        
        Storage::disk('public')->putFileAs(
            'uploads/analyses/' . $imageAnalysis->id,
            $image,
            $filename
        );
        
        return $path;
    }
    
    /**
     * Call LandingLens API
     */
    private function callLandingLensAPI(UploadedFile $image): array
    {
        // Build URL with endpoint_id as query parameter
        $url = $this->baseUrl . '?endpoint_id=' . $this->endpointId;
        
        $response = Http::timeout(30)
            ->withHeaders([
                'apikey' => $this->apiKey,
                'Accept' => 'application/json',
            ])
            ->attach('file', file_get_contents($image->getPathname()), $image->getClientOriginalName())
            ->post($url); // No body parameters needed
        
        if (!$response->successful()) {
            throw new Exception('LandingLens API request failed: ' . $response->body());
        }
        
        return $response->json();
    }
    
    /**
     * Validate uploaded image
     */
    private function validateImage(UploadedFile $image): void
    {
        // Check if file is valid
        if (!$image->isValid()) {
            throw new Exception('Invalid image file uploaded');
        }
        
        // Check file size (max 10MB)
        if ($image->getSize() > 10 * 1024 * 1024) {
            throw new Exception('Image file too large. Maximum size is 10MB');
        }
        
        // Check file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($image->getMimeType(), $allowedTypes)) {
            throw new Exception('Invalid image format. Only JPEG and PNG are allowed');
        }
    }
    
    /**
     * Process API response and save detected objects to database
     */
    private function processAPIResponse(array $apiResponse, ImageAnalysis $imageAnalysis): void
    {
        // Update image analysis with API response data
        $totalLatency = 0;
        if (isset($apiResponse['latency'])) {
            $latency = $apiResponse['latency'];
            $totalLatency = ($latency['preprocess_s'] ?? 0) + 
                           ($latency['infer_s'] ?? 0) + 
                           ($latency['postprocess_s'] ?? 0) + 
                           ($latency['serialize_s'] ?? 0);
        }
        
        $imageAnalysis->update([
            'model_id' => $apiResponse['model_id'] ?? null,
            'backbone_type' => $apiResponse['backbonetype'] ?? null,
            'full_api_response' => $apiResponse,
            'total_latency_seconds' => $totalLatency,
        ]);
        
        // Process backbone predictions (object detections with coordinates)
        if (isset($apiResponse['backbonepredictions']) && is_array($apiResponse['backbonepredictions'])) {
            foreach ($apiResponse['backbonepredictions'] as $detectionUuid => $detection) {
                $coordinates = $detection['coordinates'] ?? [];
                
                DetectedObject::create([
                    'image_analysis_id' => $imageAnalysis->id,
                    'detection_uuid' => $detectionUuid,
                    'label_name' => $detection['labelName'] ?? 'Unknown',
                    'label_index' => $detection['labelIndex'] ?? 0,
                    'confidence_score' => $detection['score'] ?? 0.0,
                    'x_min' => $coordinates['xmin'] ?? 0,
                    'y_min' => $coordinates['ymin'] ?? 0,
                    'x_max' => $coordinates['xmax'] ?? 0,
                    'y_max' => $coordinates['ymax'] ?? 0,
                    'defect_id' => $detection['defect_id'] ?? null,
                ]);
            }
        }
    }
    
    /**
     * Create processed image with bounding boxes drawn
     */
    private function createProcessedImage(ImageAnalysis $imageAnalysis): string
    {
        $manager = new ImageManager(new Driver());
        
        // Load original image
        $originalPath = Storage::disk('public')->path($imageAnalysis->original_image_path);
        $image = $manager->read($originalPath);
        
        // Draw bounding boxes for each detected object
        foreach ($imageAnalysis->detectedObjects as $object) {
            $this->drawBoundingBox($image, $object);
        }
        
        // Save processed image
        $filename = 'processed_' . $imageAnalysis->id . '_' . time() . '.jpg';
        $path = 'uploads/analyses/' . $imageAnalysis->id . '/' . $filename;
        $fullPath = Storage::disk('public')->path($path);
        
        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $image->save($fullPath, quality: 90);
        
        return $path;
    }
    
    /**
     * Draw bounding box on image for detected object
     */
    private function drawBoundingBox($image, DetectedObject $object): void
    {
        // Choose color based on confidence level
        $color = $this->getColorForConfidence($object->confidence_score);
        
        // Draw rectangle border
        $image->drawRectangle($object->x_min, $object->y_min, function ($rectangle) use ($color, $object) {
            $rectangle->size($object->width, $object->height);
            $rectangle->border($color, 3);
            $rectangle->background('transparent');
        });
        
        // Draw label background
        $label = $object->label_name . ' ' . $object->confidence_percentage;
        $labelWidth = strlen($label) * 8; // Approximate width
        $labelHeight = 20;
        
        $image->drawRectangle($object->x_min, $object->y_min - $labelHeight, function ($rectangle) use ($color, $labelWidth, $labelHeight) {
            $rectangle->size($labelWidth, $labelHeight);
            $rectangle->background($color);
            $rectangle->border($color, 1);
        });
        
        // Draw label text
        $image->text($label, $object->x_min + 4, $object->y_min - 6, function ($font) {
            $font->size(12);
            $font->color('white');
            // Note: font file path can be added if needed: $font->filename(storage_path('fonts/arial.ttf'));
        });
    }
    
    /**
     * Get color for bounding box based on confidence level
     */
    private function getColorForConfidence(float $confidence): string
    {
        if ($confidence >= 0.8) {
            return '#10b981'; // Green for high confidence
        } elseif ($confidence >= 0.5) {
            return '#f59e0b'; // Orange for medium confidence
        } else {
            return '#ef4444'; // Red for low confidence
        }
    }
    
    /**
     * Handle API errors gracefully
     */
    public function handleError(Exception $e): array
    {
        $errorMessage = 'An error occurred while processing your image';
        
        if (str_contains($e->getMessage(), 'API key')) {
            $errorMessage = 'Invalid API key. Please check your configuration';
        } elseif (str_contains($e->getMessage(), 'timeout')) {
            $errorMessage = 'Request timeout. Please try again later';
        } elseif (str_contains($e->getMessage(), 'upload')) {
            $errorMessage = 'Image upload failed. Please try again';
        } elseif (str_contains($e->getMessage(), 'Invalid image')) {
            $errorMessage = $e->getMessage();
        }
        
        return [
            'success' => false,
            'error' => $errorMessage,
            'technical_error' => $e->getMessage()
        ];
    }
} 