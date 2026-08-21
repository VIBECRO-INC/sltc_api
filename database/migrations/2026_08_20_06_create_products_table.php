<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique(); $table->string('category')->nullable(); $table->longText('description')->nullable(); $table->string('image')->nullable(); $table->string('availability')->nullable(); $table->string('price_type')->default('on_request'); $table->boolean('is_published')->default(true); $table->string('seo_title')->nullable(); $table->text('seo_description')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};
