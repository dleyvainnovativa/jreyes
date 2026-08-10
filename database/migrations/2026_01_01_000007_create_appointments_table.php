<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Solicitudes de cita / cotización enviadas desde el sitio público.
     * Un administrador las revisa más adelante (sin login por ahora).
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono');
            $table->string('correo')->nullable();
            $table->string('motivo')->nullable();     // "Examen de la vista", "Cotización", "Lentes de contacto"
            $table->date('fecha_preferida')->nullable();
            $table->text('mensaje')->nullable();
            $table->string('estatus')->default('nueva'); // nueva, contactada, atendida
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
