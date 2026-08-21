<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->string('category'); $table->string('brand')->nullable(); $table->string('model')->nullable(); $table->unsignedSmallInteger('year')->nullable(); $table->longText('description')->nullable(); $table->json('specifications')->nullable(); $table->string('capacity')->nullable(); $table->string('power')->nullable(); $table->string('dimensions')->nullable(); $table->string('weight')->nullable(); $table->json('applications')->nullable(); $table->string('availability')->nullable(); $table->string('status')->default('active'); $table->string('location')->nullable(); $table->string('seo_title')->nullable(); $table->text('seo_description')->nullable(); $table->boolean('is_published')->default(true); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('equipment'); }
};
