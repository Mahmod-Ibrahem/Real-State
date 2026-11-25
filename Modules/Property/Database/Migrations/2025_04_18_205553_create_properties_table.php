<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('name');
            $table->text('description');
            $table->string('address');
            $table->string('image');
            $table->integer('price');
            $table->unsignedTinyInteger('maintenance_deposit_percent')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->string('garages')->nullable();
            $table->integer('year_built')->nullable();
            $table->integer('sqft');
            $table->mediumText('google_map')->nullable();
            $table->boolean('is_published')->default(0);
            $table->foreignId('property_type_id')->constrained('property_types')->onDelete('cascade');
            $table->foreignId('area_id')->constrained('areas');
            $table->foreignId('city_id')->constrained('cities');
            $table->string('purpose')->index(); // Modules\Property\Enums\ListingPurpose
            $table->string('status')->default('available')->index(); // Modules\Property\Enums\PropertyStatus
            $table->foreignId('property_classification_id')->nullable()->constrained('property_classifications')->onDelete('set null');
            $table->foreignId('owner_id')->constrained('users')->restrictOnDelete();
            $table->string('review_status')->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
