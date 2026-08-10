<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tipos de lente (mica): monofocal, bifocal, progresiva.
     * Cada tipo tiene precios por material y, en progresivas, por marca/diseño.
     */
    public function up(): void
    {
        Schema::create('lens_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');                 // "Monofocal", "Bifocal", "Progresiva"
            $table->string('resumen')->nullable();    // frase corta
            $table->text('descripcion')->nullable();
            $table->string('icono')->nullable();      // clase Font Awesome
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lens_types');
    }
};
