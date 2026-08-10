<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Variante de precio de una mica: combina tipo de lente + diseño/marca +
     * material. Ej.: Progresiva · Varilux Physio · Policarbonato = $3650.
     */
    public function up(): void
    {
        Schema::create('lens_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lens_type_id')->constrained('lens_types')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('nombre');                 // "Varilux Physio", "Convencional", "F.Top"
            $table->string('marca')->nullable();      // "Varilux", "Younger", "Retilens"
            $table->string('material');               // "CR-39", "Policarbonato"
            $table->string('indice')->nullable();     // "1.59", etc.
            $table->decimal('precio', 10, 2);
            $table->boolean('premium')->default(false);
            $table->string('imagen')->nullable();
            $table->text('descripcion')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lens_designs');
    }
};
