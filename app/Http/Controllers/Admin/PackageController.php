<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Administración de paquetes "lentes completos" (panel /admin).
 */
class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::orderBy('nombre')->orderBy('orden')->get()->groupBy('nombre');

        return view('admin.paquetes.index', compact('packages'));
    }

    public function create()
    {
        $paquete = new Package([
            'activo' => true,
            'orden'  => (Package::max('orden') ?? 0) + 1,
            'precio' => 0,
        ]);

        return view('admin.paquetes.create', compact('paquete'));
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);
        $datos['slug'] = $this->slugUnico($request->input('nombre') . '-' . $request->input('tratamiento'), null);
        $datos['destacado'] = $request->boolean('destacado');
        $datos['activo'] = $request->boolean('activo');
        $datos['orden'] = $datos['orden'] ?? 0;
        $datos['precio'] = $datos['precio'] ?? 0;

        Package::create($datos);

        return redirect()->route('admin.paquetes')
            ->with('exito', "Paquete «{$datos['nombre']} · {$datos['tratamiento']}» agregado.");
    }

    public function edit(Package $paquete)
    {
        return view('admin.paquetes.edit', compact('paquete'));
    }

    public function update(Request $request, Package $paquete)
    {
        $datos = $this->validar($request);
        $datos['destacado'] = $request->boolean('destacado');
        $datos['activo'] = $request->boolean('activo');
        $datos['orden'] = $datos['orden'] ?? 0;
        $datos['precio'] = $datos['precio'] ?? 0;

        $paquete->update($datos);

        return redirect()->route('admin.paquetes')
            ->with('exito', "Paquete «{$paquete->nombre} · {$paquete->tratamiento}» actualizado.");
    }

    public function destroy(Package $paquete)
    {
        $etiqueta = $paquete->nombre . ' · ' . $paquete->tratamiento;
        $paquete->delete();

        return redirect()->route('admin.paquetes')
            ->with('exito', "Paquete «{$etiqueta}» eliminado.");
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre'      => ['required', 'string', 'max:120'],
            'tratamiento' => ['required', 'string', 'max:140'],
            'precio'      => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'incluye'     => ['nullable', 'string', 'max:1000'],
            'destacado'   => ['nullable', 'boolean'],
            'activo'      => ['nullable', 'boolean'],
            'orden'       => ['nullable', 'integer', 'min:0'],
        ], [
            'nombre.required'      => 'El grupo (p. ej. Monofocal) es obligatorio.',
            'tratamiento.required' => 'El tratamiento es obligatorio.',
        ]);
    }

    private function slugUnico(string $texto, ?int $id): string
    {
        $base = Str::slug($texto) ?: 'paquete';
        $slug = $base;
        $i = 2;
        while (Package::where('slug', $slug)->when($id, fn ($q) => $q->where('id', '!=', $id))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
