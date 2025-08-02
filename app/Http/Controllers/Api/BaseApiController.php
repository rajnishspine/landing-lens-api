<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="Landing Lens API",
 *     version="1.0.0",
 *     description="A powerful REST API for AI-powered image object detection using LandingLens technology.",
 *     @OA\Contact(
 *         email="support@landinglens-api.com",
 *         name="API Support"
 *     ),
 *     @OA\License(
 *         name="MIT License",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Development Server"
 * )
 * 
 * @OA\Server(
 *     url="https://your-domain.com",
 *     description="Production Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your Bearer token in the format: Bearer {token}"
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="datetime", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime")
 * )
 * 
 * @OA\Schema(
 *     schema="ImageAnalysis",
 *     @OA\Property(property="analysis_id", type="integer", example=123),
 *     @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "failed"}, example="completed"),
 *     @OA\Property(property="original_filename", type="string", example="image.jpg"),
 *     @OA\Property(property="file_size", type="integer", example=245760),
 *     @OA\Property(property="mime_type", type="string", example="image/jpeg"),
 *     @OA\Property(property="objects_detected_count", type="integer", example=3),
 *     @OA\Property(property="average_confidence", type="number", format="float", example=0.87),
 *     @OA\Property(property="processing_time_seconds", type="number", format="float", example=2.45),
 *     @OA\Property(property="created_at", type="string", format="datetime"),
 *     @OA\Property(property="updated_at", type="string", format="datetime"),
 *     @OA\Property(property="images", type="object",
 *         @OA\Property(property="original_url", type="string", format="url"),
 *         @OA\Property(property="processed_url", type="string", format="url")
 *     ),
 *     @OA\Property(property="detected_objects", type="array", @OA\Items(ref="#/components/schemas/DetectedObject")),
 *     @OA\Property(property="api_response", type="object",
 *         @OA\Property(property="model_id", type="string", example="model_123"),
 *         @OA\Property(property="backbone_type", type="string", example="efficientnet"),
 *         @OA\Property(property="latency_seconds", type="number", format="float", example=2.45)
 *     ),
 *     @OA\Property(property="error_message", type="string", nullable=true)
 * )
 * 
 * @OA\Schema(
 *     schema="DetectedObject",
 *     @OA\Property(property="id", type="integer", example=456),
 *     @OA\Property(property="label", type="string", example="car"),
 *     @OA\Property(property="confidence", type="number", format="float", example=0.92),
 *     @OA\Property(property="confidence_percentage", type="string", example="92.0%"),
 *     @OA\Property(property="bounding_box", type="object",
 *         @OA\Property(property="x_min", type="integer", example=100),
 *         @OA\Property(property="y_min", type="integer", example=150),
 *         @OA\Property(property="x_max", type="integer", example=300),
 *         @OA\Property(property="y_max", type="integer", example=250),
 *         @OA\Property(property="width", type="integer", example=200),
 *         @OA\Property(property="height", type="integer", example=100),
 *         @OA\Property(property="area", type="integer", example=20000)
 *     ),
 *     @OA\Property(property="defect_id", type="integer", nullable=true)
 * )
 * 
 * @OA\Schema(
 *     schema="ApiResponse",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Operation completed successfully"),
 *     @OA\Property(property="data", type="object")
 * )
 * 
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="An error occurred"),
 *     @OA\Property(property="error", type="string", example="Detailed error message"),
 *     @OA\Property(property="technical_error", type="string", example="Technical details (optional)"),
 *     @OA\Property(property="timestamp", type="string", format="datetime")
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationErrorResponse",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Validation failed"),
 *     @OA\Property(property="errors", type="object",
 *         @OA\Property(property="field_name", type="array", @OA\Items(type="string", example="The field is required."))
 *     )
 * )
 */
class BaseApiController extends Controller
{
    //
}