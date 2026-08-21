<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            // Etape 1
            $table->enum('need_type', ['engin', 'transport', 'terrassement', 'levage', 'securite', 'autre']);
            // Etape 2
            $table->text('description')->nullable();
            // Etape 3
            $table->string('location')->nullable();
            // Etape 4
            $table->date('needed_at')->nullable();
            // Etape 5 - coordonnées
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('company')->nullable();
            // Pipeline commercial
            $table->enum('status', ['nouveau', 'qualification', 'devis', 'negociation', 'gagne', 'perdu'])->default('nouveau');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source')->default('site_web');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
