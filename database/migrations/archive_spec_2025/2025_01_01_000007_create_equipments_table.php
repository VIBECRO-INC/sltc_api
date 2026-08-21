<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_category_id')->nullable()->constrained('equipment_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('year')->nullable();
            $table->longText('description')->nullable();
            $table->json('characteristics')->nullable(); // { "puissance": "...", "capacite": "...", ... }
            $table->string('capacity')->nullable();
            $table->string('power')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();
            $table->text('applications')->nullable();
            $table->enum('status', ['disponible', 'indisponible', 'maintenance'])->default('disponible');
            $table->string('location')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
