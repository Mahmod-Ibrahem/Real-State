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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            // A country can't be deleted while it still has cities.
            $table->foreignId('country_id')->constrained('countries')->restrictOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->timestamps();

            // Same city name allowed in different countries, not twice in one country.
            $table->unique(['country_id', 'name']);
            $table->unique(['country_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
