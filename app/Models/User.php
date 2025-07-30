<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship: User has many image analyses
     */
    public function imageAnalyses(): HasMany
    {
        return $this->hasMany(ImageAnalysis::class);
    }

    /**
     * Relationship: Get only completed image analyses
     */
    public function completedAnalyses(): HasMany
    {
        return $this->hasMany(ImageAnalysis::class)->where('status', 'completed');
    }

    /**
     * Relationship: Get recent image analyses (last 30 days)
     */
    public function recentAnalyses(): HasMany
    {
        return $this->hasMany(ImageAnalysis::class)->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Get total number of objects detected across all analyses
     */
    public function getTotalObjectsDetectedAttribute(): int
    {
        return $this->imageAnalyses()->sum('objects_detected_count');
    }

    /**
     * Get total number of completed analyses
     */
    public function getTotalCompletedAnalysesAttribute(): int
    {
        return $this->completedAnalyses()->count();
    }

    /**
     * Get average processing time across all analyses
     */
    public function getAverageProcessingTimeAttribute(): float
    {
        return $this->completedAnalyses()->avg('total_latency_seconds') ?? 0.0;
    }
}
