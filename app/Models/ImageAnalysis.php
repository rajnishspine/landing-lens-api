<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ImageAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_image_path',
        'processed_image_path',
        'original_filename',
        'mime_type',
        'file_size',
        'model_id',
        'backbone_type',
        'full_api_response',
        'status',
        'error_message',
        'total_latency_seconds',
        'objects_detected_count',
    ];

    protected $casts = [
        'full_api_response' => 'array',
        'total_latency_seconds' => 'decimal:4',
        'file_size' => 'integer',
        'objects_detected_count' => 'integer',
    ];

    protected $attributes = [
        'status' => 'pending',
        'objects_detected_count' => 0,
    ];

    /**
     * Relationship: Image analysis belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Image analysis has many detected objects
     */
    public function detectedObjects(): HasMany
    {
        return $this->hasMany(DetectedObject::class);
    }

    /**
     * Relationship: Get high-confidence detections (above 80%)
     */
    public function highConfidenceObjects(): HasMany
    {
        return $this->hasMany(DetectedObject::class)->where('confidence_score', '>=', 0.8);
    }

    /**
     * Get the URL for the original image
     */
    public function getOriginalImageUrlAttribute(): string
    {
        return Storage::url($this->original_image_path);
    }

    /**
     * Get the URL for the processed image
     */
    public function getProcessedImageUrlAttribute(): ?string
    {
        return $this->processed_image_path ? Storage::url($this->processed_image_path) : null;
    }

    /**
     * Check if analysis is completed successfully
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if analysis failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if analysis is still processing
     */
    public function isProcessing(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Mark analysis as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'objects_detected_count' => $this->detectedObjects()->count()
        ]);
    }

    /**
     * Mark analysis as failed with error message
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage
        ]);
    }

    /**
     * Get formatted file size in human readable format
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get the average confidence score of all detected objects
     */
    public function getAverageConfidenceAttribute(): float
    {
        return $this->detectedObjects()->avg('confidence_score') ?? 0.0;
    }

    /**
     * Scope: Only completed analyses
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope: Recent analyses (within last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Scope: Analyses for a specific user
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}