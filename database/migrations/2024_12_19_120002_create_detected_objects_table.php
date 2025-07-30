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
        Schema::create('detected_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_analysis_id')->constrained()->onDelete('cascade');
            
            // Detection UUID from API
            $table->string('detection_uuid')->nullable();
            
            // Object details
            $table->string('label_name');
            $table->integer('label_index');
            $table->decimal('confidence_score', 8, 6); // 0.9663617014884949
            
            // Bounding box coordinates
            $table->integer('x_min');
            $table->integer('y_min'); 
            $table->integer('x_max');
            $table->integer('y_max');
            
            // Additional API fields
            $table->bigInteger('defect_id')->nullable();
            
            // Calculated fields
            $table->integer('width')->storedAs('x_max - x_min');
            $table->integer('height')->storedAs('y_max - y_min');
            $table->integer('area')->storedAs('(x_max - x_min) * (y_max - y_min)');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['image_analysis_id', 'confidence_score']);
            $table->index('label_name');
            $table->index('detection_uuid');
            $table->index('defect_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detected_objects');
    }
};