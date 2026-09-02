<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
            FramesSeeder::class,
            // El árbol del configurador toma precios de CatalogSeeder,
            // así que debe ejecutarse DESPUÉS de él.
            CatalogTreeSeeder::class,
        ]);
    }
}
