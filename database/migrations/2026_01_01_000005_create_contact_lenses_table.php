<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_lenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_lens_brand_id')->constrained('contact_lens_brands')->cascadeOnDelete();
            $table->string('nombre');
            $table->decimal('precio', 10, 2);
            $table->string('tipo')->nullable();       // "Esférico", "Tórico", "Multifocal", "Color"
            $table->string('reemplazo')->nullable();  // "Diario", "Mensual", "Anual", "Quincenal"
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_lenses');
    }
};
