<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImageAnalysis;
use App\Models\User;
use App\Services\LandingLensService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PredictApiController extends Controller
{
    private LandingLensService $landingLensService;

    public function __construct(LandingLensService $landingLensService)
    {
        $this->landingLensService = $landingLensService;
    }

    /**
     * Process image analysis via API
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function predict(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', // 10MB max
                'email' => 'required|email', // Optional user identification
                'callback_url' => 'sometimes|url', // Optional webhook URL
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $uploadedFile = $request->file('image');
            
            // Get or create user (for API calls without authentication)
            $user = $this->getOrCreateApiUser($request);
            
            // Process the image
            $imageAnalysis = $this->landingLensService->predict($uploadedFile, $user);

            // Prepare response data
            $responseData = [
                'success' => true,
                'message' => 'Image analyzed successfully',
                'data' => [
                    'analysis_id' => $imageAnalysis->id,
                    'status' => $imageAnalysis->status,
                    'original_filename' => $imageAnalysis->original_filename,
                    'file_size' => $imageAnalysis->file_size,
                    'mime_type' => $imageAnalysis->mime_type,
                    'objects_detected_count' => $imageAnalysis->objects_detected_count,
                    'average_confidence' => $imageAnalysis->average_confidence,
                    'processing_time_seconds' => $imageAnalysis->total_latency_seconds,
                    'created_at' => $imageAnalysis->created_at->toISOString(),
                    'images' => [
                        'original_url' => $imageAnalysis->original_image_url,
                        'processed_url' => $imageAnalysis->processed_image_url,
                    ],
                    'detected_objects' => $imageAnalysis->detectedObjects->map(function($object) {
                        return [
                            'id' => $object->id,
                            'label' => $object->label_name,
                            'confidence' => $object->confidence_score,
                            'confidence_percentage' => $object->confidence_percentage,
                            'bounding_box' => [
                                'x_min' => $object->x_min,
                                'y_min' => $object->y_min,
                                'x_max' => $object->x_max,
                                'y_max' => $object->y_max,
                                'width' => $object->width,
                                'height' => $object->height,
                                'area' => $object->area,
                            ],
                            'defect_id' => $object->defect_id,
                        ];
                    })->toArray(),
                    'api_response' => [
                        'model_id' => $imageAnalysis->model_id,
                        'backbone_type' => $imageAnalysis->backbone_type,
                        'latency_seconds' => $imageAnalysis->total_latency_seconds,
                    ]
                ],
                'links' => [
                    'self' => route('api.predict.show', $imageAnalysis->id),
                    'original_image' => $imageAnalysis->original_image_url,
                    'processed_image' => $imageAnalysis->processed_image_url,
                ]
            ];

            // Include raw API response in debug mode
            if (config('app.debug')) {
                $responseData['debug'] = [
                    'raw_api_response' => $imageAnalysis->full_api_response,
                ];
            }

            return response()->json($responseData, 201);

        } catch (Exception $e) {
            $errorData = $this->landingLensService->handleError($e);
            
            return response()->json([
                'success' => false,
                'message' => 'Image analysis failed',
                'error' => $errorData['error'],
                'technical_error' => $errorData['technical_error'] ?? null,
                'timestamp' => now()->toISOString(),
            ], 500);
        }
    }

    /**
     * Get analysis by ID
     * 
     * @param string $id
     * @return JsonResponse
     */
    public function getAnalysis(string $id): JsonResponse
    {
        $analysis = ImageAnalysis::with('detectedObjects')->find($id);

        if (!$analysis) {
            return response()->json([
                'success' => false,
                'message' => 'Analysis not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'analysis_id' => $analysis->id,
                'status' => $analysis->status,
                'original_filename' => $analysis->original_filename,
                'file_size' => $analysis->file_size,
                'mime_type' => $analysis->mime_type,
                'objects_detected_count' => $analysis->objects_detected_count,
                'average_confidence' => $analysis->average_confidence,
                'processing_time_seconds' => $analysis->total_latency_seconds,
                'created_at' => $analysis->created_at->toISOString(),
                'updated_at' => $analysis->updated_at->toISOString(),
                'images' => [
                    'original_url' => $analysis->original_image_url,
                    'processed_url' => $analysis->processed_image_url,
                ],
                'detected_objects' => $analysis->detectedObjects->map(function($object) {
                    return [
                        'id' => $object->id,
                        'label' => $object->label_name,
                        'confidence' => $object->confidence_score,
                        'confidence_percentage' => $object->confidence_percentage,
                        'bounding_box' => [
                            'x_min' => $object->x_min,
                            'y_min' => $object->y_min,
                            'x_max' => $object->x_max,
                            'y_max' => $object->y_max,
                            'width' => $object->width,
                            'height' => $object->height,
                            'area' => $object->area,
                        ],
                        'defect_id' => $object->defect_id,
                    ];
                })->toArray(),
                'api_response' => [
                    'model_id' => $analysis->model_id,
                    'backbone_type' => $analysis->backbone_type,
                    'latency_seconds' => $analysis->total_latency_seconds,
                ],
                'error_message' => $analysis->error_message,
            ],
        ]);
    }

    /**
     * List recent analyses (public endpoint with pagination)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->get('per_page', 10), 50); // Max 50 per page
        $status = $request->get('status'); // Filter by status
        
        $query = ImageAnalysis::with('detectedObjects')
            ->orderBy('created_at', 'desc');
            
        if ($status) {
            $query->where('status', $status);
        }
        
        $analyses = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $analyses->items(),
            'pagination' => [
                'current_page' => $analyses->currentPage(),
                'per_page' => $analyses->perPage(),
                'total' => $analyses->total(),
                'last_page' => $analyses->lastPage(),
                'has_more' => $analyses->hasMorePages(),
            ],
        ]);
    }

    /**
     * Get user's analyses (authenticated endpoint)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function userAnalyses(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = min($request->get('per_page', 10), 50);
        
        $analyses = $user->imageAnalyses()
            ->with('detectedObjects')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $analyses->items(),
            'pagination' => [
                'current_page' => $analyses->currentPage(),
                'per_page' => $analyses->perPage(),
                'total' => $analyses->total(),
                'last_page' => $analyses->lastPage(),
                'has_more' => $analyses->hasMorePages(),
            ],
        ]);
    }

    /**
     * Delete analysis (authenticated endpoint)
     * 
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function deleteAnalysis(Request $request, string $id): JsonResponse
    {
        $user = $request->user();
        $analysis = $user->imageAnalyses()->find($id);

        if (!$analysis) {
            return response()->json([
                'success' => false,
                'message' => 'Analysis not found or not owned by user',
            ], 404);
        }

        // Delete associated files
        if ($analysis->original_image_path) {
            \Storage::disk('public')->delete($analysis->original_image_path);
        }
        if ($analysis->processed_image_path) {
            \Storage::disk('public')->delete($analysis->processed_image_path);
        }

        $analysis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Analysis deleted successfully',
        ]);
    }

    /**
     * Get or create API user for anonymous requests
     * 
     * @param Request $request
     * @return User
     */
    private function getOrCreateApiUser(Request $request): User
    {
        // If authenticated, use that user
        if ($request->user()) {
            return $request->user();
        }

        // If email provided, find or create user
        if ($request->has('email')) {
            $email = $request->get('email');
            return User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'API User - ' . explode('@', $email)[0],
                    'password' => bcrypt(\Str::random(32)), // Random password
                ]
            );
        }

        // Create or use anonymous API user
        return User::firstOrCreate(
            ['email' => 'api@landinglens.local'],
            [
                'name' => 'Anonymous API User',
                'password' => bcrypt(\Str::random(32)),
            ]
        );
    }
}