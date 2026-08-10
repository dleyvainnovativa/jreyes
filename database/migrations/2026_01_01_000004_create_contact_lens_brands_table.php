<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_lens_brands', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');   // "Alcon", "Johnson & Johnson", "Bausch & Lomb", "Hidrosoft"
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_lens_brands');
    }
};
