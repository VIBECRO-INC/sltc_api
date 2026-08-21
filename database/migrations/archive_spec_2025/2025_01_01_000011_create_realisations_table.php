<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('client')->nullable();
            $table->string('sector')->nullable(); // Transport, BTP, Energie, Terrassement, Travaux routiers, Logistique, Manutention
            $table->string('location')->nullable();
            $table->string('prestation')->nullable();
            $table->string('year')->nullable();
            $table->longText('description')->nullable();
            $table->text('results')->nullable(); // ex: +23 000 poteaux transportés
            $table->string('video_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisations');
    }
};
