<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /** Muestra la página de contacto / solicitud de cita. */
    public function create()
    {
        return view('pages.contacto');
    }

    /** Guarda la solicitud de cita enviada desde el sitio público. */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'telefono' => ['required', 'string', 'max:20'],
            'correo' => ['nullable', 'email', 'max:150'],
            'motivo' => ['nullable', 'string', 'max:120'],
            'fecha_preferida' => ['nullable', 'date', 'after_or_equal:today'],
            'mensaje' => ['nullable', 'string', 'max:1000'],
        ], [
            'nombre.required' => 'Escribe tu nombre para poder contactarte.',
            'telefono.required' => 'Necesitamos un teléfono para confirmar tu cita.',
            'correo.email' => 'Revisa que el correo esté bien escrito.',
            'fecha_preferida.after_or_equal' => 'Elige una fecha de hoy en adelante.',
        ]);

        Appointment::create($datos);

        return redirect()
            ->route('contacto')
            ->with('exito', '¡Gracias! Recibimos tu solicitud y te contactaremos muy pronto.');
    }
}
