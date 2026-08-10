<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Paquetes "lentes completos": armazón + mica ya con tratamiento,
     * a precio cerrado. Ej.: Monofocal · Blueray = $650.
     */
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');                 // "Monofocal", "Bifocal", "Progresivas"
            $table->string('tratamiento');            // "CR-39 Blanco", "A.R. Básico", "Blueray", "Fotocromático"
            $table->decimal('precio', 10, 2);
            $table->text('incluye')->nullable();
            $table->boolean('destacado')->default(false);
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
