<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetectedObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_analysis_id',
        'detection_uuid',
        'label_name',
        'label_index',
        'confidence_score',
        'x_min',
        'y_min',
        'x_max',
        'y_max',
        'defect_id',
    ];

    protected $casts = [
        'confidence_score' => 'decimal:6',
        'label_index' => 'integer',
        'x_min' => 'integer',
        'y_min' => 'integer',
        'x_max' => 'integer',
        'y_max' => 'integer',
        'defect_id' => 'integer',
    ];

    /**
     * Relationship: Detected object belongs to an image analysis
     */
    public function imageAnalysis(): BelongsTo
    {
        return $this->belongsTo(ImageAnalysis::class);
    }

    /**
     * Get the width of the bounding box
     */
    public function getWidthAttribute(): int
    {
        return $this->x_max - $this->x_min;
    }

    /**
     * Get the height of the bounding box
     */
    public function getHeightAttribute(): int
    {
        return $this->y_max - $this->y_min;
    }

    /**
     * Get the area of the bounding box
     */
    public function getAreaAttribute(): int
    {
        return $this->width * $this->height;
    }

    /**
     * Get the center point of the bounding box
     */
    public function getCenterXAttribute(): float
    {
        return ($this->x_min + $this->x_max) / 2;
    }

    /**
     * Get the center point of the bounding box
     */
    public function getCenterYAttribute(): float
    {
        return ($this->y_min + $this->y_max) / 2;
    }

    /**
     * Get coordinates as an array for easy manipulation
     */
    public function getCoordinatesAttribute(): array
    {
        return [
            'x_min' => $this->x_min,
            'y_min' => $this->y_min,
            'x_max' => $this->x_max,
            'y_max' => $this->y_max,
            'width' => $this->width,
            'height' => $this->height,
            'center_x' => $this->center_x,
            'center_y' => $this->center_y,
            'area' => $this->area,
        ];
    }

    /**
     * Get confidence score as percentage
     */
    public function getConfidencePercentageAttribute(): string
    {
        return round($this->confidence_score * 100, 2) . '%';
    }

    /**
     * Check if this is a high-confidence detection (above 80%)
     */
    public function isHighConfidence(): bool
    {
        return $this->confidence_score >= 0.8;
    }

    /**
     * Check if this is a medium-confidence detection (50-80%)
     */
    public function isMediumConfidence(): bool
    {
        return $this->confidence_score >= 0.5 && $this->confidence_score < 0.8;
    }

    /**
     * Check if this is a low-confidence detection (below 50%)
     */
    public function isLowConfidence(): bool
    {
        return $this->confidence_score < 0.5;
    }

    /**
     * Get confidence level as string
     */
    public function getConfidenceLevelAttribute(): string
    {
        if ($this->isHighConfidence()) {
            return 'high';
        } elseif ($this->isMediumConfidence()) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Get Bootstrap class for confidence level
     */
    public function getConfidenceColorClassAttribute(): string
    {
        return match($this->confidence_level) {
            'high' => 'text-success',
            'medium' => 'text-warning',
            'low' => 'text-danger',
            default => 'text-secondary'
        };
    }

    /**
     * Format coordinates for display
     */
    public function getFormattedCoordinatesAttribute(): string
    {
        return "({$this->x_min}, {$this->y_min}) → ({$this->x_max}, {$this->y_max})";
    }

    /**
     * Scope: High confidence detections
     */
    public function scopeHighConfidence($query)
    {
        return $query->where('confidence_score', '>=', 0.8);
    }

    /**
     * Scope: Medium confidence detections
     */
    public function scopeMediumConfidence($query)
    {
        return $query->where('confidence_score', '>=', 0.5)
                     ->where('confidence_score', '<', 0.8);
    }

    /**
     * Scope: Low confidence detections
     */
    public function scopeLowConfidence($query)
    {
        return $query->where('confidence_score', '<', 0.5);
    }

    /**
     * Scope: Objects by label name
     */
    public function scopeByLabel($query, string $labelName)
    {
        return $query->where('label_name', $labelName);
    }

    /**
     * Scope: Large objects (area > 10000 pixels)
     */
    public function scopeLarge($query)
    {
        return $query->whereRaw('(x_max - x_min) * (y_max - y_min) > 10000');
    }

    /**
     * Scope: Small objects (area < 1000 pixels)
     */
    public function scopeSmall($query)
    {
        return $query->whereRaw('(x_max - x_min) * (y_max - y_min) < 1000');
    }
}