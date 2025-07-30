<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('image_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // File paths
            $table->string('original_image_path')->nullable();
            $table->string('processed_image_path')->nullable();
            $table->string('original_filename');
            $table->string('mime_type');
            $table->integer('file_size'); // in bytes
            
            // API Response data
            $table->string('model_id')->nullable();
            $table->string('backbone_type')->nullable();
            $table->json('full_api_response')->nullable(); // Store complete response
            
            // Processing status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            
            // Performance metrics
            $table->decimal('total_latency_seconds', 8, 4)->nullable();
            $table->integer('objects_detected_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'created_at']);
            $table->index('status');
            $table->index('model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_analyses');
    }
};