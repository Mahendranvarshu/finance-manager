<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('dl_no')->nullable();
            $table->string('name');
            $table->string('store_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
    
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('interest_amount', 15, 2)->default(0);
            $table->decimal('daily_amount', 15, 2)->default(0);
    
            $table->integer('total_days')->default(0);
            $table->date('starting_date');
            $table->date('ending_date')->nullable();
    
            $table->unsignedBigInteger('collector_id')->nullable();
    
            $table->enum('status', ['active', 'completed', 'default'])->default('active');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
