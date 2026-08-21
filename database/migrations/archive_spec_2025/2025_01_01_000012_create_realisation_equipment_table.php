<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('realisation_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('realisation_id')->constrained('realisations')->cascadeOnDelete();
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['realisation_id', 'equipment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('realisation_equipment');
    }
};
