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
        Schema::create('area_routes', function (Blueprint $table) {
            $table->id();
    
            $table->string('area_name');           // Area name (Ex: Chennai, Coimbatore)
            $table->string('route_name');          // Route name (Ex: Route A, Route 12)
            $table->string('start_point');         // Route starting place
            $table->string('end_point');           // Route ending place
                        
            $table->integer('distance_km')->nullable(); // Distance in KM
            $table->integer('priority')->default(0);    // Sorting purpose
            
            $table->boolean('status')->default(true);   // Active / Inactive
            
            $table->timestamps(); // created_at, updated_at
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arealines');
    }
};
