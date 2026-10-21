<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\Request;

/**
 * Ajustes del sitio (panel /admin): contacto, ubicación, horario y redes
 * sociales. Todo se edita en una sola página y se guarda de una vez.
 */
class SettingsController extends Controller
{
    private const ESCALARES = ['whatsapp_number', 'telefono', 'correo', 'direccion', 'ciudad', 'estado'];

    public function edit()
    {
        $valores = Setting::pluck('value', 'key')->toArray();

        $horario = [];
        if (! empty($valores['horario'])) {
            $horario = json_decode($valores['horario'], true) ?: [];
        }

        return view('admin.ajustes.edit', [
            'valores'     => $valores,
            'horario'     => $horario,
            'redes'       => SocialLink::orderBy('orden')->orderBy('id')->get(),
            'plataformas' => SocialLink::PLATAFORMAS,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'telefono'        => ['nullable', 'string', 'max:60'],
            'correo'          => ['nullable', 'email', 'max:150'],
            'direccion'       => ['nullable', 'string', 'max:200'],
            'ciudad'          => ['nullable', 'string', 'max:100'],
            'estado'          => ['nullable', 'string', 'max:100'],
            'horario'         => ['nullable', 'array'],
            'horario.*.etiqueta' => ['nullable', 'string', 'max:80'],
            'horario.*.horario'  => ['nullable', 'string', 'max:80'],
            'redes'           => ['nullable', 'array'],
            'redes.*.url'     => ['nullable', 'string', 'max:255'],
            'redes.*.color'   => ['nullable', 'string', 'max:20'],
            'redes.*.icono'   => ['nullable', 'string', 'max:80'],
            'redes.*.orden'   => ['nullable', 'integer', 'min:0'],
        ], [
            'correo.email' => 'Revisa que el correo esté bien escrito.',
        ]);

        // --- Valores simples ---
        foreach (self::ESCALARES as $clave) {
            Setting::updateOrCreate(['key' => $clave], ['value' => $request->input($clave)]);
        }

        // --- Horario (JSON de renglones no vacíos) ---
        $filas = collect($request->input('horario', []))
            ->map(fn ($f) => [
                'etiqueta' => trim((string) ($f['etiqueta'] ?? '')),
                'horario'  => trim((string) ($f['horario'] ?? '')),
            ])
            ->filter(fn ($f) => $f['etiqueta'] !== '' || $f['horario'] !== '')
            ->values()
            ->all();
        Setting::updateOrCreate(['key' => 'horario'], ['value' => json_encode($filas, JSON_UNESCAPED_UNICODE)]);

        // --- Redes sociales (sincronización: crear / actualizar / eliminar) ---
        foreach ($request->input('redes', []) as $row) {
            $id        = $row['id'] ?? null;
            $eliminar  = ! empty($row['eliminar']);
            $plataforma = $row['plataforma'] ?? '';
            $url       = trim((string) ($row['url'] ?? ''));

            if ($id && $eliminar) {
                SocialLink::whereKey($id)->delete();
                continue;
            }

            // Fila nueva vacía: ignorar.
            if (! $id && ($plataforma === '' || $url === '')) {
                continue;
            }

            $conocida = array_key_exists($plataforma, SocialLink::PLATAFORMAS) ? SocialLink::PLATAFORMAS[$plataforma] : SocialLink::PLATAFORMAS['otro'];
            $icono = ($plataforma === 'otro' || ! array_key_exists($plataforma, SocialLink::PLATAFORMAS))
                ? (trim((string) ($row['icono'] ?? '')) ?: $conocida[1])
                : $conocida[1];
            $color = trim((string) ($row['color'] ?? '')) ?: $conocida[2];

            $datos = [
                'plataforma' => $plataforma !== '' ? $plataforma : 'otro',
                'icono'      => $icono ?: 'fa-solid fa-link',
                'url'        => $url,
                'color'      => $color,
                'orden'      => (int) ($row['orden'] ?? 0),
                'activo'     => ! empty($row['activo']),
            ];

            if ($id) {
                SocialLink::whereKey($id)->update($datos);
            } else {
                SocialLink::create($datos);
            }
        }

        return redirect()->route('admin.ajustes')->with('exito', 'Ajustes guardados.');
    }
}
