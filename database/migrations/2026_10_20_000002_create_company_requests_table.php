<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Solicitudes de empresas (convenios / cotizaciones corporativas) enviadas
 * desde el formulario para empresas. Separadas de 'appointments' porque los
 * campos son distintos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_requests', function (Blueprint $table) {
            $table->id();
            $table->string('empresa');
            $table->string('nombre_contacto');
            $table->string('puesto')->nullable();
            $table->string('telefono');
            $table->string('correo')->nullable();
            $table->string('num_empleados')->nullable(); // rango: "1–10", "11–50", etc.
            $table->text('mensaje')->nullable();
            $table->string('estatus')->default('nueva'); // nueva, contactada, atendida
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_requests');
    }
};
