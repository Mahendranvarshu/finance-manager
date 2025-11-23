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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('collector_id');
            $table->date('date');
            $table->decimal('amount_collected', 15, 2);
            $table->decimal('remaining_amount', 15, 2)->nullable();
            $table->integer('day_number')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
