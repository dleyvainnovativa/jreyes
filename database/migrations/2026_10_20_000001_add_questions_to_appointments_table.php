<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Nuevas preguntas del formulario de contacto principal:
 *  - ¿Conoces tu graduación? (y la graduación capturada, si aplica)
 *  - ¿Hace cuánto hiciste tu examen de la vista?
 *  - ¿Hace cuánto tienes tu armazón actual?
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->boolean('conoce_graduacion')->default(false)->after('mensaje');
            $table->json('graduacion')->nullable()->after('conoce_graduacion');
            $table->string('ultimo_examen')->nullable()->after('graduacion');
            $table->string('antiguedad_armazon')->nullable()->after('ultimo_examen');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['conoce_graduacion', 'graduacion', 'ultimo_examen', 'antiguedad_armazon']);
        });
    }
};
