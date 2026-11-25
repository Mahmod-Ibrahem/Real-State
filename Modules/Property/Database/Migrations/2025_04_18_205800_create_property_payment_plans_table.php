<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('property_payment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->restrictOnDelete();
            $table->unsignedTinyInteger('down_payment_percent');
            $table->unsignedTinyInteger('installment_years');
            $table->string('installment_frequency', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_payment_plans');
    }
};
