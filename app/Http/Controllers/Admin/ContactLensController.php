<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLens;
use App\Models\ContactLensBrand;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Administración de LENTES de contacto dentro de una marca (panel /admin).
 */
class ContactLensController extends Controller
{
    public const TIPOS = ['Esférico', 'Tórico', 'Multifocal', 'Color'];
    public const REEMPLAZOS = ['Diario', 'Quincenal', 'Mensual', 'Anual'];

    public function index(ContactLensBrand $brand)
    {
        $lentes = $brand->lenses()->get();

        return view('admin.lentes-contacto.lentes.index', compact('brand', 'lentes'));
    }

    public function create(ContactLensBrand $brand)
    {
        $lente = new ContactLens([
            'activo' => true,
            'orden'  => ((int) $brand->lenses()->max('orden')) + 1,
            'precio' => 0,
        ]);

        return view('admin.lentes-contacto.lentes.create', [
            'brand' => $brand,
            'lente' => $lente,
            'tipos' => self::TIPOS,
            'reemplazos' => self::REEMPLAZOS,
        ]);
    }

    public function store(Request $request, ContactLensBrand $brand)
    {
        $datos = $this->validar($request);
        $datos['activo'] = $request->boolean('activo');
        $datos['orden']  = $datos['orden'] ?? 0;
        $datos['precio'] = $datos['precio'] ?? 0;
        $datos['imagen'] = $request->hasFile('imagen') ? $this->guardarImagen($request->file('imagen')) : null;

        $brand->lenses()->create($datos);

        return redirect()->route('admin.lentes-contacto.lentes', $brand)
            ->with('exito', "Lente «{$datos['nombre']}» agregado.");
    }

    public function edit(ContactLensBrand $brand, ContactLens $lente)
    {
        $this->verificar($brand, $lente);

        return view('admin.lentes-contacto.lentes.edit', [
            'brand' => $brand,
            'lente' => $lente,
            'tipos' => self::TIPOS,
            'reemplazos' => self::REEMPLAZOS,
        ]);
    }

    public function update(Request $request, ContactLensBrand $brand, ContactLens $lente)
    {
        $this->verificar($brand, $lente);

        $datos = $this->validar($request);
        $datos['activo'] = $request->boolean('activo');
        $datos['orden']  = $datos['orden'] ?? 0;
        $datos['precio'] = $datos['precio'] ?? 0;

        if ($request->hasFile('imagen')) {
            $this->eliminarImagen($lente->imagen);
            $datos['imagen'] = $this->guardarImagen($request->file('imagen'));
        } elseif ($request->boolean('quitar_imagen')) {
            $this->eliminarImagen($lente->imagen);
            $datos['imagen'] = null;
        } else {
            unset($datos['imagen']);
        }

        $lente->update($datos);

        return redirect()->route('admin.lentes-contacto.lentes', $brand)
            ->with('exito', "Lente «{$lente->nombre}» actualizado.");
    }

    public function destroy(ContactLensBrand $brand, ContactLens $lente)
    {
        $this->verificar($brand, $lente);
        $this->eliminarImagen($lente->imagen);
        $nombre = $lente->nombre;
        $lente->delete();

        return redirect()->route('admin.lentes-contacto.lentes', $brand)
            ->with('exito', "Lente «{$nombre}» eliminado.");
    }

    /** Asegura que el lente pertenezca a la marca de la URL. */
    private function verificar(ContactLensBrand $brand, ContactLens $lente): void
    {
        abort_unless($lente->contact_lens_brand_id === $brand->id, 404);
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre'    => ['required', 'string', 'max:140'],
            'precio'    => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'tipo'      => ['nullable', 'string', 'max:40'],
            'reemplazo' => ['nullable', 'string', 'max:40'],
            'orden'     => ['nullable', 'integer', 'min:0'],
            'activo'    => ['nullable', 'boolean'],
            'imagen'    => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'quitar_imagen' => ['nullable', 'boolean'],
        ], [
            'nombre.required' => 'El nombre del lente es obligatorio.',
            'imagen.image'    => 'El archivo debe ser una imagen.',
            'imagen.mimes'    => 'Usa una imagen JPG, PNG o WebP.',
            'imagen.max'      => 'La imagen no debe pasar de 3 MB.',
        ]);
    }

    private function guardarImagen(UploadedFile $file): string
    {
        $ext     = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $nombre  = 'lc-' . Str::random(10) . '.' . $ext;
        $destino = public_path('img/lentes-contacto');
        if (! is_dir($destino)) {
            @mkdir($destino, 0755, true);
        }
        $file->move($destino, $nombre);

        return 'img/lentes-contacto/' . $nombre;
    }

    private function eliminarImagen(?string $ruta): void
    {
        if ($ruta && str_starts_with($ruta, 'img/lentes-contacto/')) {
            $archivo = public_path($ruta);
            if (is_file($archivo)) {
                @unlink($archivo);
            }
        }
    }
}
