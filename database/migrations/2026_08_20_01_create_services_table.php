<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('services', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->string('short_description')->nullable(); $table->longText('description')->nullable(); $table->string('image')->nullable(); $table->string('cta_label')->nullable(); $table->string('cta_url')->nullable(); $table->unsignedInteger('sort_order')->default(0); $table->boolean('is_published')->default(true); $table->string('seo_title')->nullable(); $table->text('seo_description')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('services'); }
};
