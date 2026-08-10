<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tratamientos / capas: A.R. Básico, Blueray, línea Crizal, Saphir,
     * fotocromático y Transitions. Se suman al precio de la mica.
     */
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');                 // "Crizal Sapphire", "A.R. Básico"
            $table->string('familia')->nullable();    // "Crizal", "Saphir", "Antirreflejante", "Fotocromático"
            $table->decimal('precio', 10, 2);
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            // Puntuaciones 0-3 para la tabla comparativa (estilo baterías Crizal)
            $table->unsignedTinyInteger('proteccion_uv')->default(0);
            $table->unsignedTinyInteger('luz_azul')->default(0);
            $table->unsignedTinyInteger('reflejos')->default(0);
            $table->unsignedTinyInteger('rayas')->default(0);
            $table->unsignedTinyInteger('manchas')->default(0);
            $table->unsignedTinyInteger('agua')->default(0);
            $table->boolean('premium')->default(false);
            $table->boolean('es_extra')->default(false); // fotocromáticos y similares: paso 4 (aditivo)
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
