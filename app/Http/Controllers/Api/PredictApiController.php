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

/**
 * @OA\Tag(
 *     name="Image Analysis",
 *     description="API Endpoints for AI-powered image object detection"
 * )
 */
class PredictApiController extends Controller
{
    private LandingLensService $landingLensService;

    public function __construct(LandingLensService $landingLensService)
    {
        $this->landingLensService = $landingLensService;
    }

    /**
     * Analyze image for object detection
     *
     * @OA\Post(
     *     path="/api/v1/predict",
     *     summary="Analyze image for object detection using AI",
     *     tags={"Image Analysis"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="image", type="string", format="binary", description="Image file (JPEG, PNG, JPG, max 10MB)"),
     *                 @OA\Property(property="callback_url", type="string", format="url", example="https://example.com/webhook", description="Optional webhook URL for completion notification")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Image analyzed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Image analyzed successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ImageAnalysis"),
     *             @OA\Property(property="links", type="object",
     *                 @OA\Property(property="self", type="string", format="url"),
     *                 @OA\Property(property="original_image", type="string", format="url"),
     *                 @OA\Property(property="processed_image", type="string", format="url")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Processing error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
            
            // Get authenticated user
            $user = $request->user();
            
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
     * Get analysis results by ID
     *
     * @OA\Get(
     *     path="/api/v1/predict/{id}",
     *     summary="Retrieve analysis results by ID",
     *     tags={"Image Analysis"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Analysis ID",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Analysis results retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/ImageAnalysis")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Analysis not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Analysis not found")
     *         )
     *     )
     * )
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
     * List recent analyses
     *
     * @OA\Get(
     *     path="/api/v1/predict",
     *     summary="Get paginated list of recent analyses",
     *     tags={"Image Analysis"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page (max 50)",
     *         @OA\Schema(type="integer", example=10, minimum=1, maximum=50)
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter by status",
     *         @OA\Schema(type="string", enum={"pending", "processing", "completed", "failed"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recent analyses retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object",
     *                 @OA\Property(property="analysis_id", type="integer", example=123),
     *                 @OA\Property(property="status", type="string", example="completed"),
     *                 @OA\Property(property="objects_detected_count", type="integer", example=3),
     *                 @OA\Property(property="average_confidence", type="number", format="float", example=0.87),
     *                 @OA\Property(property="created_at", type="string", format="datetime")
     *             )),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=47),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="has_more", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
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
     * Get authenticated user's analysis history
     *
     * @OA\Get(
     *     path="/api/v1/my-analyses",
     *     summary="Get authenticated user's analysis history",
     *     tags={"Image Analysis"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page (max 50)",
     *         @OA\Schema(type="integer", example=10, minimum=1, maximum=50)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User's analyses retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ImageAnalysis")),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=25),
     *                 @OA\Property(property="last_page", type="integer", example=3),
     *                 @OA\Property(property="has_more", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
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
     * Delete analysis and associated files
     *
     * @OA\Delete(
     *     path="/api/v1/predict/{id}",
     *     summary="Delete a user's analysis and associated files",
     *     tags={"Image Analysis"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Analysis ID",
     *         @OA\Schema(type="integer", example=123)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Analysis deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Analysis deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Analysis not found or not owned by user",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Analysis not found or not owned by user")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
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


}