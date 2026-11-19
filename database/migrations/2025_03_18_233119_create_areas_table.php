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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            // A city can't be deleted while it still has areas.
            $table->foreignId('city_id')->constrained('cities')->restrictOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->timestamps();

            $table->unique(['city_id', 'name']);
            $table->unique(['city_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
