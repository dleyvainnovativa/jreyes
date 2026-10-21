<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Enlaces de redes sociales (repetibles, con color propio y orden).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('plataforma');          // whatsapp, facebook, instagram, tiktok, otro...
            $table->string('icono');               // clase Font Awesome, p. ej. "fa-brands fa-facebook-f"
            $table->string('url');
            $table->string('color')->default('#888888');
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
