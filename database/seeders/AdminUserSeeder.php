<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Crea (o actualiza) el usuario administrador del panel.
 *
 * Define las credenciales en .env antes de sembrar:
 *   ADMIN_NAME="Administrador"
 *   ADMIN_EMAIL=admin@jreyes.mx
 *   ADMIN_PASSWORD="una-contraseña-fuerte"
 *
 * Si no defines nada, usa los valores por defecto de abajo — CÁMBIALOS.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@jreyes.mx')],
            [
                'name'     => env('ADMIN_NAME', 'Administrador'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'JReyes2026!')),
            ]
        );
    }
}
