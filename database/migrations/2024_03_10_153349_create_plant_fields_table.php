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
        Schema::create('plant_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_id')->constrained();
            $table->foreignId('field_id')->constrained();
            $table->text('text_value')->nullable();
            $table->bigInteger('number_value')->nullable();
            $table->timestamps();

            $table->index('plant_id');
            $table->index('field_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plant_fields');
    }
};
