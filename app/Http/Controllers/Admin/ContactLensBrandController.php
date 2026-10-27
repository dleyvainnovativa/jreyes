<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLensBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Administración de MARCAS de lentes de contacto (panel /admin).
 * Cada marca agrupa sus lentes (ver ContactLensController).
 */
class ContactLensBrandController extends Controller
{
    public function index()
    {
        $brands = ContactLensBrand::ordenadas()->withCount('lenses')->get();

        return view('admin.lentes-contacto.index', compact('brands'));
    }

    public function create()
    {
        $brand = new ContactLensBrand(['orden' => (ContactLensBrand::max('orden') ?? 0) + 1]);

        return view('admin.lentes-contacto.create', compact('brand'));
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request, null);
        $datos['slug'] = $this->slugUnico($request->input('slug') ?: $request->input('nombre'), null);
        $datos['orden'] = $datos['orden'] ?? 0;

        ContactLensBrand::create($datos);

        return redirect()->route('admin.lentes-contacto')
            ->with('exito', "Marca «{$datos['nombre']}» agregada.");
    }

    public function edit(ContactLensBrand $brand)
    {
        return view('admin.lentes-contacto.edit', compact('brand'));
    }

    public function update(Request $request, ContactLensBrand $brand)
    {
        $datos = $this->validar($request, $brand->id);
        $datos['slug'] = $this->slugUnico($request->input('slug') ?: $request->input('nombre'), $brand->id);
        $datos['orden'] = $datos['orden'] ?? 0;

        $brand->update($datos);

        return redirect()->route('admin.lentes-contacto')
            ->with('exito', "Marca «{$brand->nombre}» actualizada.");
    }

    public function destroy(ContactLensBrand $brand)
    {
        $nombre = $brand->nombre;
        $brand->delete(); // la BD elimina en cascada sus lentes

        return redirect()->route('admin.lentes-contacto')
            ->with('exito', "Marca «{$nombre}» eliminada (con sus lentes).");
    }

    private function validar(Request $request, ?int $id): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'slug'   => ['nullable', 'string', 'max:140', Rule::unique('contact_lens_brands', 'slug')->ignore($id)],
            'orden'  => ['nullable', 'integer', 'min:0'],
        ], [
            'nombre.required' => 'El nombre de la marca es obligatorio.',
            'slug.unique'     => 'Ese slug ya lo usa otra marca.',
        ]);
    }

    /** Genera un slug único a partir de un texto. */
    private function slugUnico(string $texto, ?int $id): string
    {
        $base = Str::slug($texto) ?: 'marca';
        $slug = $base;
        $i = 2;
        while (ContactLensBrand::where('slug', $slug)->when($id, fn ($q) => $q->where('id', '!=', $id))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
