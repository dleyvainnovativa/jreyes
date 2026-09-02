<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Árbol de catálogo para el configurador "Arma tus lentes".
 *
 * Un solo árbol auto-referenciado modela las tres taxonomías del formato
 * del cliente (monofocal, bifocal, progresiva), que tienen profundidades
 * distintas:
 *   monofocal  : nivel -> material -> tratamiento
 *   bifocal    : nivel -> diseno(flat top|blend) -> material -> tratamiento
 *   progresiva : nivel -> material/marca -> tratamiento
 *
 * El configurador solo muestra los hijos del nodo seleccionado, así que la
 * profundidad variable no requiere código especial: la maneja el árbol.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_nodes', function (Blueprint $table) {
            $table->id();

            // Padre en el árbol. Los nodos raíz (nivel) tienen parent_id nulo.
            $table->foreignId('parent_id')->nullable()
                ->constrained('catalog_nodes')->cascadeOnDelete();

            // A qué taxonomía pertenece el nodo raíz de esta rama.
            $table->string('tipo'); // monofocal | bifocal | progresiva

            // Rol del nodo dentro del árbol. Define el "paso" del configurador.
            $table->string('kind'); // nivel | diseno | material | tratamiento

            $table->string('nombre');
            $table->string('slug');

            // 0 = nodo agrupador o sin precio en catálogo ("Precio en tienda").
            // El total del configurador solo suma nodos con precio > 0.
            $table->decimal('precio', 10, 2)->default(0);

            // Extra aditivo (fotocromático, transitions): se suma pero no bloquea.
            $table->boolean('es_extra')->default(false);

            // Imagen opcional del nodo (public/img/catalog/<slug>.jpg).
            // Si es null, el resumen simplemente omite la miniatura.
            $table->string('imagen')->nullable();

            // Descripción corta opcional para la tarjeta de resumen.
            $table->string('descripcion')->nullable();

            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['tipo', 'parent_id', 'orden']);
            $table->index(['parent_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_nodes');
    }
};
