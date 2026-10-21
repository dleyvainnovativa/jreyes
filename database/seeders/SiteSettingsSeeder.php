<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Database\Seeder;

/**
 * Valores iniciales de los ajustes del sitio. Usa updateOrCreate para no
 * pisar cambios hechos desde el panel si se vuelve a sembrar.
 */
class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $ajustes = [
            'whatsapp_number' => env('WHATSAPP_NUMBER', ''),
            'telefono'        => '(271) 000 0000',
            'correo'          => 'hola@jreyesopticos.com',
            'direccion'       => '',
            'ciudad'          => 'Córdoba',
            'estado'          => 'Veracruz',
            'horario'         => json_encode([
                ['etiqueta' => 'Lunes a viernes', 'horario' => '9:00 – 19:00'],
                ['etiqueta' => 'Sábado',          'horario' => '9:00 – 15:00'],
                ['etiqueta' => 'Domingo',         'horario' => 'Cerrado'],
            ], JSON_UNESCAPED_UNICODE),
        ];

        foreach ($ajustes as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Redes sociales iniciales (solo si no hay ninguna aún).
        if (SocialLink::count() === 0) {
            $base = [
                ['plataforma' => 'facebook',  'url' => '#'],
                ['plataforma' => 'instagram', 'url' => '#'],
                ['plataforma' => 'whatsapp',  'url' => '#'],
                ['plataforma' => 'tiktok',    'url' => '#'],
            ];

            foreach ($base as $i => $red) {
                [$label, $icono, $color] = SocialLink::PLATAFORMAS[$red['plataforma']];
                SocialLink::create([
                    'plataforma' => $red['plataforma'],
                    'icono'      => $icono,
                    'url'        => $red['url'],
                    'color'      => $color,
                    'orden'      => $i + 1,
                    'activo'     => true,
                ]);
            }
        }
    }
}
