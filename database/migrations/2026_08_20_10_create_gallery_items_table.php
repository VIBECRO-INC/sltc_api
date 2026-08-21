<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id(); $table->string('title')->nullable(); $table->string('category'); $table->string('image')->nullable(); $table->string('video_url')->nullable(); $table->string('alt')->nullable(); $table->unsignedInteger('sort_order')->default(0); $table->boolean('is_published')->default(true); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('gallery_items'); }
};
