<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->string('slug')->unique(); $table->string('client')->nullable(); $table->string('sector')->nullable(); $table->string('service')->nullable(); $table->string('location')->nullable(); $table->date('project_date')->nullable(); $table->longText('description')->nullable(); $table->longText('results')->nullable(); $table->string('video_url')->nullable(); $table->boolean('is_featured')->default(false); $table->boolean('is_published')->default(true); $table->string('seo_title')->nullable(); $table->text('seo_description')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('projects'); }
};
