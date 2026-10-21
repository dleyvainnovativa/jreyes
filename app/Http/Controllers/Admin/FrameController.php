<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frame;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Administración de armazones (panel /admin).
 * CRUD completo: listar, agregar, editar, ocultar y eliminar, con subida de
 * imagen a public/img/armazones/ (mismo enfoque que el catálogo, sin symlink).
 */
class FrameController extends Controller
{
    /** Listado (incluye ocultos, para el admin). */
    public function index()
    {
        $frames = Frame::orderBy('orden')->orderBy('numero')->get();

        return view('admin.armazones.index', compact('frames'));
    }

    /** Formulario de alta. */
    public function create()
    {
        $frame = new Frame([
            'numero' => (Frame::max('numero') ?? 0) + 1,
            'precio' => 0,
            'orden'  => (Frame::max('orden') ?? 0) + 1,
            'activo' => true,
        ]);

        return view('admin.armazones.create', compact('frame'));
    }

    /** Guarda un armazón nuevo. */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'numero' => ['nullable', 'integer', 'min:1', Rule::unique('frames', 'numero')],
            'nombre' => ['required', 'string', 'max:120'],
            'precio' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'orden'  => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
            'imagen' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
        ], $this->mensajes());

        $datos['numero'] = $datos['numero'] ?? (Frame::max('numero') ?? 0) + 1;
        $datos['precio'] = $datos['precio'] ?? 0;
        $datos['orden']  = $datos['orden'] ?? 0;
        $datos['activo'] = $request->boolean('activo');
        $datos['imagen'] = $this->guardarImagen($request->file('imagen'));

        Frame::create($datos);

        return redirect()->route('admin.armazones')
            ->with('exito', "Armazón «{$datos['nombre']}» agregado.");
    }

    /** Formulario de edición. */
    public function edit(Frame $armazon)
    {
        return view('admin.armazones.edit', ['frame' => $armazon]);
    }

    /** Guarda cambios de un armazón. */
    public function update(Request $request, Frame $armazon)
    {
        $datos = $request->validate([
            'numero' => ['nullable', 'integer', 'min:1', Rule::unique('frames', 'numero')->ignore($armazon->id)],
            'nombre' => ['required', 'string', 'max:120'],
            'precio' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'orden'  => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
        ], $this->mensajes());

        $datos['numero'] = $datos['numero'] ?? $armazon->numero;
        $datos['precio'] = $datos['precio'] ?? 0;
        $datos['orden']  = $datos['orden'] ?? 0;
        $datos['activo'] = $request->boolean('activo');

        if ($request->hasFile('imagen')) {
            $this->eliminarImagen($armazon->imagen);
            $datos['imagen'] = $this->guardarImagen($request->file('imagen'));
        } else {
            unset($datos['imagen']);
        }

        $armazon->update($datos);

        return redirect()->route('admin.armazones')
            ->with('exito', "Armazón «{$armazon->nombre}» actualizado.");
    }

    /** Elimina un armazón (y su imagen). */
    public function destroy(Frame $armazon)
    {
        $this->eliminarImagen($armazon->imagen);
        $nombre = $armazon->nombre;
        $armazon->delete();

        return redirect()->route('admin.armazones')
            ->with('exito', "Armazón «{$nombre}» eliminado.");
    }

    /** Guarda la imagen subida y devuelve su ruta relativa a public/. */
    private function guardarImagen(UploadedFile $file): string
    {
        $ext     = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $nombre  = 'armazon-' . Str::random(10) . '.' . $ext;
        $destino = public_path('img/armazones');
        if (! is_dir($destino)) {
            @mkdir($destino, 0755, true);
        }
        $file->move($destino, $nombre);

        return 'img/armazones/' . $nombre;
    }

    /** Borra una imagen anterior solo si vive dentro de img/armazones/. */
    private function eliminarImagen(?string $ruta): void
    {
        if ($ruta && str_starts_with($ruta, 'img/armazones/')) {
            $archivo = public_path($ruta);
            if (is_file($archivo)) {
                @unlink($archivo);
            }
        }
    }

    /** Mensajes de validación en español. */
    private function mensajes(): array
    {
        return [
            'numero.unique' => 'Ese número de armazón ya existe.',
            'nombre.required' => 'El nombre es obligatorio.',
            'imagen.required' => 'Sube una imagen del armazón.',
            'imagen.image'    => 'El archivo debe ser una imagen.',
            'imagen.mimes'    => 'Usa una imagen JPG, PNG o WebP.',
            'imagen.max'      => 'La imagen no debe pasar de 3 MB.',
        ];
    }
}
