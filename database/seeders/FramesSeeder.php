<?php

namespace Database\Seeders;

use App\Models\Frame;
use Illuminate\Database\Seeder;

/**
 * Genera los 41 armazones "Modelo N".
 * Las imágenes deben colocarse en public/img/armazones/ con el nombre
 * modelo-01.jpg … modelo-41.jpg (dos dígitos para que ordenen bien).
 */
class FramesSeeder extends Seeder
{
    public function run(): void
    {
        $total = 41;
        $rows = [];

        for ($n = 1; $n <= $total; $n++) {
            $pad = str_pad((string) $n, 2, '0', STR_PAD_LEFT);
            $rows[] = [
                'numero' => $n,
                'nombre' => "Modelo {$n}",
                'imagen' => "img/armazones/modelo-{$pad}.jpg",
                'precio' => 0,
                'orden' => $n,
                'activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Frame::insert($rows);
    }
}
