<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Armazones. El cliente entregó 41 imágenes rotuladas "Modelo N", sin
     * marca ni atributos. El precio va incluido (0) por ahora, pero se deja
     * la columna para poder cotizarlos más adelante sin cambiar el esquema.
     */
    public function up(): void
    {
        Schema::create('frames', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('numero')->unique(); // 1..41
            $table->string('nombre');                     // "Modelo 1"
            $table->string('imagen');                     // img/armazones/modelo-01.jpg
            $table->decimal('precio', 10, 2)->default(0); // incluido por ahora
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frames');
    }
};
