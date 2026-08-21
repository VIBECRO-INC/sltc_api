<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id(); $table->string('reference')->unique(); $table->string('need_type'); $table->longText('description')->nullable(); $table->string('project_location'); $table->date('needed_at')->nullable(); $table->string('first_name'); $table->string('last_name')->nullable(); $table->string('company')->nullable(); $table->string('email'); $table->string('phone'); $table->string('whatsapp')->nullable(); $table->string('status')->default('new'); $table->string('source')->default('website'); $table->boolean('consent')->default(false); $table->timestamps(); $table->index('status');
        });
    }
    public function down(): void { Schema::dropIfExists('quote_requests'); }
};
