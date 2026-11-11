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

    /** Roles válidos de un nodo (definen el "paso" del configurador). */
    private const KINDS = [
        'nivel'       => 'Nivel (precio de micas)',
        'diseno'      => 'Diseño',
        'material'    => 'Material',
        'linea'       => 'Línea',
        'tratamiento' => 'Tratamiento',
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

    /**
     * Formulario para crear un nodo. Acepta ?tipo= y ?parent= para
     * preseleccionar dónde nace el nodo. El tipo se hereda del padre cuando
     * hay uno; si no, el nodo es una raíz del tipo indicado.
     */
    public function create(Request $request)
    {
        $parent = null;
        $parentId = $request->query('parent');
        if ($parentId) {
            $parent = CatalogNode::find($parentId);
        }

        if ($parent) {
            $tipo = $parent->tipo;
        } else {
            $tipo = $request->query('tipo');
            if (! array_key_exists($tipo, self::TIPOS)) {
                $tipo = array_key_first(self::TIPOS);
            }
        }

        // Sugerencias a partir de los hermanos (mismo padre, mismo tipo).
        $hermanos = CatalogNode::where('parent_id', $parent?->id)
            ->where('tipo', $tipo)->orderBy('orden')->get();
        $kindSugerido  = $hermanos->first()->kind ?? ($parent ? 'tratamiento' : 'nivel');
        $ordenSugerido = ($hermanos->max('orden') ?? -1) + 1;

        return view('admin.catalogo.create', [
            'tipos'         => self::TIPOS,
            'tipo'          => $tipo,
            'tipoNombre'    => self::TIPOS[$tipo] ?? $tipo,
            'kinds'         => self::KINDS,
            'parent'        => $parent,
            'kindSugerido'  => $kindSugerido,
            'ordenSugerido' => $ordenSugerido,
            'opcionesPadre' => $this->opcionesPadreLista($tipo),
        ]);
    }

    /** Guarda un nodo nuevo. */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'      => ['required', 'string', 'max:255'],
            'tipo'        => ['required', Rule::in(array_keys(self::TIPOS))],
            'kind'        => ['required', Rule::in(array_keys(self::KINDS))],
            'precio'      => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'orden'       => ['required', 'integer', 'min:0'],
            'es_extra'    => ['nullable', 'boolean'],
            'activo'      => ['nullable', 'boolean'],
            'parent_id'   => ['nullable', 'integer'],
            'slug'        => ['nullable', 'string', 'max:255'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'kind.required'   => 'Elige el tipo de nodo (nivel, material, tratamiento…).',
            'precio.numeric'  => 'El precio debe ser un número.',
            'precio.min'      => 'El precio no puede ser negativo.',
            'orden.integer'   => 'El orden debe ser un número entero.',
        ]);

        // Padre: si se indicó, debe existir; el nodo hereda su tipo.
        $parentId = $request->input('parent_id');
        $parentId = ($parentId === null || $parentId === '') ? null : (int) $parentId;
        $tipo = $datos['tipo'];

        if ($parentId !== null) {
            $padre = CatalogNode::find($parentId);
            if (! $padre) {
                return back()->withInput()->withErrors(['parent_id' => 'El padre seleccionado no existe.']);
            }
            $tipo = $padre->tipo; // el tipo siempre lo manda el padre
        }

        $base = Str::slug((string) ($datos['slug'] ?: $datos['nombre']));

        $node = CatalogNode::create([
            'parent_id'   => $parentId,
            'tipo'        => $tipo,
            'kind'        => $datos['kind'],
            'nombre'      => $datos['nombre'],
            'slug'        => $this->slugUnico($base),
            'precio'      => $datos['precio'],
            'descripcion' => $datos['descripcion'] ?? null,
            'orden'       => $datos['orden'],
            'es_extra'    => $request->boolean('es_extra'),
            'activo'      => $request->boolean('activo'),
        ]);

        return redirect()
            ->route('admin.catalogo', ['tipo' => $node->tipo])
            ->with('exito', "Nodo «{$node->nombre}» creado.");
    }

    /** Formulario de edición de un nodo. */
    public function edit(CatalogNode $node)
    {
        return view('admin.catalogo.edit', [
            'node'             => $node,
            'padre'            => $node->parent,
            'numHijos'         => $node->children()->count(),
            'numDescendientes' => count($this->subtreeIds($node)) - 1,
            'tipoNombre'       => self::TIPOS[$node->tipo] ?? $node->tipo,
            'opcionesPadre'    => $this->opcionesPadre($node),
        ]);
    }

    /**
     * Lista de padres válidos para mover un nodo: todos los del mismo tipo,
     * en orden de árbol y con sangría, excluyendo el propio nodo y su subárbol
     * (para no crear ciclos). Devuelve [ ['id'=>int, 'label'=>string], ... ].
     */
    private function opcionesPadre(CatalogNode $node): array
    {
        // Excluye el propio nodo y su subárbol para no crear ciclos.
        return $this->opcionesPadreLista($node->tipo, $this->subtreeIds($node));
    }

    /**
     * Lista indentada de nodos de un tipo, en orden de árbol, para un <select>
     * de padre. $prohibidos salta esos ids y sus ramas (vacío al crear).
     * Devuelve [ ['id'=>int, 'label'=>string], ... ].
     */
    private function opcionesPadreLista(string $tipo, array $prohibidos = []): array
    {
        $todos    = CatalogNode::deTipo($tipo)->get();
        $opciones = [];

        $recorrer = function ($parentId, int $depth) use (&$recorrer, $todos, $prohibidos, &$opciones) {
            foreach ($todos->where('parent_id', $parentId)->sortBy('orden') as $n) {
                if (in_array($n->id, $prohibidos, true)) {
                    continue; // salta el nodo y todo su subárbol
                }
                $opciones[] = [
                    'id'    => $n->id,
                    'label' => str_repeat('— ', $depth) . $n->nombre,
                ];
                $recorrer($n->id, $depth + 1);
            }
        };
        $recorrer(null, 0);

        return $opciones;
    }

    /** Devuelve un slug libre a partir de $base, añadiendo -2, -3… si choca. */
    private function slugUnico(string $base): string
    {
        $base = $base !== '' ? $base : 'nodo';
        $slug = $base;
        $i = 2;
        while (CatalogNode::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    /** IDs del nodo y de todos sus descendientes (recursivo). */
    private function subtreeIds(CatalogNode $node): array
    {
        $ids = [$node->id];
        foreach ($node->children as $hijo) {
            $ids = array_merge($ids, $this->subtreeIds($hijo));
        }

        return $ids;
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
            'parent_id'   => ['nullable', 'integer'],
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

        // --- Padre (mover el nodo) -------------------------------------------
        // Validación extra: el padre debe ser del mismo tipo y no puede ser el
        // propio nodo ni uno de sus descendientes (evita ciclos).
        $parentId = $request->input('parent_id');
        $parentId = ($parentId === null || $parentId === '') ? null : (int) $parentId;

        if ($parentId !== null) {
            $padre = CatalogNode::find($parentId);
            $prohibidos = $this->subtreeIds($node);

            if (! $padre || $padre->tipo !== $node->tipo || in_array($parentId, $prohibidos, true)) {
                return back()
                    ->withInput()
                    ->withErrors(['parent_id' => 'Padre inválido: debe ser del mismo tipo y no puede ser el nodo ni uno de sus descendientes.']);
            }
        }
        $datos['parent_id'] = $parentId;

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
     * Elimina un nodo y TODO su subárbol. La BD borra los descendientes en
     * cascada (cascadeOnDelete); aquí limpiamos antes los archivos de imagen
     * del nodo y de cada descendiente, que la cascada no toca.
     */
    public function destroy(CatalogNode $node)
    {
        $tipo   = $node->tipo;
        $nombre = $node->nombre;

        $this->eliminarImagenesSubarbol($node);
        $node->delete();

        return redirect()
            ->route('admin.catalogo', ['tipo' => $tipo])
            ->with('exito', "Nodo «{$nombre}» eliminado.");
    }

    /** Borra las imágenes del nodo y de todos sus descendientes (recursivo). */
    private function eliminarImagenesSubarbol(CatalogNode $node): void
    {
        foreach ($node->children as $hijo) {
            $this->eliminarImagenesSubarbol($hijo);
        }
        $this->eliminarImagen($node->imagen);
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
