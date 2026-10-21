<?php

namespace App\Http\Controllers;

use App\Models\CompanyRequest;
use Illuminate\Http\Request;

/**
 * Formulario de contacto para empresas (convenios / cotizaciones).
 */
class CompanyRequestController extends Controller
{
    /** Muestra el formulario para empresas. */
    public function create()
    {
        return view('pages.contacto-empresas');
    }

    /** Guarda la solicitud de empresa. */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'empresa'         => ['required', 'string', 'max:150'],
            'nombre_contacto' => ['required', 'string', 'max:120'],
            'puesto'          => ['nullable', 'string', 'max:120'],
            'telefono'        => ['required', 'string', 'max:20'],
            'correo'          => ['nullable', 'email', 'max:150'],
            'num_empleados'   => ['nullable', 'string', 'max:40'],
            'mensaje'         => ['nullable', 'string', 'max:1000'],
        ], [
            'empresa.required'         => 'Escribe el nombre de la empresa.',
            'nombre_contacto.required' => 'Escribe el nombre de quien contactamos.',
            'telefono.required'        => 'Necesitamos un teléfono para contactarte.',
            'correo.email'             => 'Revisa que el correo esté bien escrito.',
        ]);

        CompanyRequest::create($datos);

        return redirect()
            ->route('contacto.empresas')
            ->with('exito', '¡Gracias! Recibimos la solicitud de tu empresa y te contactaremos muy pronto.');
    }
}
