<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('function'); // fonction / poste
            $table->string('department')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('expertise')->nullable();
            $table->unsignedInteger('years_experience')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
