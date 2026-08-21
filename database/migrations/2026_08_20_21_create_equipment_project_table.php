<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('equipment_project', function(Blueprint $table){
            $table->id(); $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->unique(['equipment_id','project_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('equipment_project'); }
};