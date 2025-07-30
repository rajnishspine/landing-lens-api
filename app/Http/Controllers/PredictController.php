<?php

namespace App\Http\Controllers;

use App\Services\LandingLensService;
use Exception;
use Illuminate\Http\Request;

class PredictController extends Controller
{
    private LandingLensService $landingLensService;

    public function __construct(LandingLensService $landingLensService)
    {
        $this->landingLensService = $landingLensService;
    }

    /**
     * Show the prediction form
     */
    public function index()
    {
        return view('predict.index');
    }

    /**
     * Handle image upload and prediction
     */
    public function predict(Request $request)
    {
        // Check if request is AJAX
        $isAjax = $request->ajax() || $request->wantsJson();

        // Validate the request
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB max
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }
            throw $e;
        }

        try {
            $uploadedFile = $request->file('image');
            $user = auth()->user();
            
            // Use the enhanced service that handles everything automatically
            $imageAnalysis = $this->landingLensService->predict($uploadedFile, $user);

            if ($isAjax) {
                // Return JSON response for AJAX requests
                return response()->json([
                    'success' => true,
                    'message' => 'Image analyzed successfully',
                    'imageAnalysis' => [
                        'id' => $imageAnalysis->id,
                        'original_filename' => $imageAnalysis->original_filename,
                        'original_image_url' => $imageAnalysis->original_image_url,
                        'processed_image_url' => $imageAnalysis->processed_image_url,
                        'objects_detected_count' => $imageAnalysis->objects_detected_count,
                        'average_confidence' => $imageAnalysis->average_confidence,
                        'status' => $imageAnalysis->status,
                        'formatted_file_size' => $imageAnalysis->formatted_file_size,
                        'total_latency_seconds' => $imageAnalysis->total_latency_seconds,
                        'created_at' => $imageAnalysis->created_at->diffForHumans(),
                        'detected_objects' => $imageAnalysis->detectedObjects->map(function($object) {
                            return [
                                'label_name' => $object->label_name,
                                'confidence_score' => $object->confidence_score,
                                'x_min' => $object->x_min,
                                'y_min' => $object->y_min,
                                'x_max' => $object->x_max,
                                'y_max' => $object->y_max,
                                'width' => $object->width,
                                'height' => $object->height,
                                'area' => $object->area,
                                'defect_id' => $object->defect_id,
                            ];
                        })->toArray()
                    ]
                ]);
            } else {
                // Return view for non-AJAX requests (fallback)
                return view('predict.result', compact('imageAnalysis'));
            }

        } catch (Exception $e) {
            $errorData = $this->landingLensService->handleError($e);
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'error' => $errorData['error'],
                    'technical_error' => $errorData['technical_error'] ?? '',
                    'message' => 'Failed to process image'
                ], 500);
            } else {
                return back()
                    ->with('error', $errorData['error'])
                    ->with('technical_error', $errorData['technical_error']);
            }
        }
    }

    /**
     * Show user's analysis history
     */
    public function history()
    {
        $user = auth()->user();
        $analyses = $user->imageAnalyses()
            ->with('detectedObjects')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('predict.history', compact('analyses'));
    }

    /**
     * Clean up old analysis files (can be called via a scheduled job)
     */
    public function cleanup()
    {
        $directory = storage_path('app/public/uploads/analyses/');
        
        // Clean up directories older than 24 hours with failed analyses
        $analysisDirectories = glob($directory . '*', GLOB_ONLYDIR);
        
        foreach ($analysisDirectories as $dir) {
            if (filemtime($dir) < time() - 86400) { // Older than 24 hours
                // Check if analysis exists and is failed
                $analysisId = basename($dir);
                $analysis = \App\Models\ImageAnalysis::find($analysisId);
                
                if (!$analysis || $analysis->status === 'failed') {
                    // Remove directory and files
                    array_map('unlink', glob("$dir/*"));
                    rmdir($dir);
                }
            }
        }
        
        return response()->json(['message' => 'Cleanup completed']);
    }
}
