<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogNode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Editor del árbol de catálogo (panel /admin).
 *
 * Paso (b): edición en sitio de los campos de cada nodo (nombre, slug, precio,
 * descripción, extra, visibilidad y orden). La imagen se edita en el paso (c)
 * y el cambio de padre (relaciones) en el paso (d); aquí son de solo lectura.
 */
class CatalogNodeController extends Controller
{
    /** Tipos válidos del árbol y su etiqueta. */
    private const TIPOS = [
        'monofocal'  => 'Monofocales',
        'bifocal'    => 'Bifocales',
        'progresiva' => 'Progresivos',
    ];

    /** Árbol completo de un tipo (incluye nodos ocultos, para el admin). */
    public function index(Request $request)
    {
        $tipo = $request->query('tipo');
        if (! array_key_exists($tipo, self::TIPOS)) {
            $tipo = array_key_first(self::TIPOS);
        }

        $raices = CatalogNode::raices()->deTipo($tipo)->with('descendants')->get();

        return view('admin.catalogo.index', [
            'tipos'      => self::TIPOS,
            'tipoActual' => $tipo,
            'raices'     => $raices,
        ]);
    }

    /** Formulario de edición de un nodo. */
    public function edit(CatalogNode $node)
    {
        return view('admin.catalogo.edit', [
            'node'      => $node,
            'padre'     => $node->parent,
            'numHijos'  => $node->children()->count(),
            'tipoNombre' => self::TIPOS[$node->tipo] ?? $node->tipo,
        ]);
    }

    /** Guarda los cambios de un nodo. */
    public function update(Request $request, CatalogNode $node)
    {
        // Normaliza el slug antes de validar.
        $request->merge(['slug' => Str::slug((string) $request->input('slug'))]);

        $datos = $request->validate([
            'nombre'      => ['required', 'string', 'max:255'],
            'slug'        => ['required', 'string', 'max:255', Rule::unique('catalog_nodes', 'slug')->ignore($node->id)],
            'precio'      => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'orden'       => ['required', 'integer', 'min:0'],
            'es_extra'    => ['nullable', 'boolean'],
            'activo'      => ['nullable', 'boolean'],
            'imagen'      => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'quitar_imagen' => ['nullable', 'boolean'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'slug.required'   => 'El slug es obligatorio.',
            'slug.unique'     => 'Ese slug ya lo usa otro nodo.',
            'precio.numeric'  => 'El precio debe ser un número.',
            'precio.min'      => 'El precio no puede ser negativo.',
            'orden.integer'   => 'El orden debe ser un número entero.',
            'imagen.image'    => 'El archivo debe ser una imagen.',
            'imagen.mimes'    => 'Usa una imagen JPG, PNG o WebP.',
            'imagen.max'      => 'La imagen no debe pasar de 3 MB.',
        ]);

        $datos['es_extra'] = $request->boolean('es_extra');
        $datos['activo']   = $request->boolean('activo');

        // Guarda primero los campos de texto (para que el slug quede definitivo
        // antes de nombrar la imagen). La imagen se maneja aparte.
        unset($datos['imagen'], $datos['quitar_imagen']);
        $node->update($datos);

        // --- Imagen -----------------------------------------------------------
        if ($request->hasFile('imagen')) {
            $this->eliminarImagen($node->imagen);

            $ext    = strtolower($request->file('imagen')->getClientOriginalExtension() ?: 'jpg');
            $nombre = $node->slug . '.' . $ext;
            $destino = public_path('img/catalog');
            if (! is_dir($destino)) {
                @mkdir($destino, 0755, true);
            }
            $request->file('imagen')->move($destino, $nombre);

            $node->update(['imagen' => 'img/catalog/' . $nombre]);
        } elseif ($request->boolean('quitar_imagen')) {
            $this->eliminarImagen($node->imagen);
            $node->update(['imagen' => null]);
        }

        return redirect()
            ->route('admin.catalogo', ['tipo' => $node->tipo])
            ->with('exito', "Nodo «{$node->nombre}» actualizado.");
    }

    /**
     * Borra del disco una imagen anterior del nodo, solo si vive dentro de
     * img/catalog/ (para no tocar imágenes sembradas en otras carpetas).
     */
    private function eliminarImagen(?string $ruta): void
    {
        if ($ruta && str_starts_with($ruta, 'img/catalog/')) {
            $archivo = public_path($ruta);
            if (is_file($archivo)) {
                @unlink($archivo);
            }
        }
    }
}
