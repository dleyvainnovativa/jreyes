<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'nombre', 'telefono', 'correo', 'motivo', 'fecha_preferida', 'mensaje', 'estatus',
        'conoce_graduacion', 'graduacion', 'ultimo_examen', 'antiguedad_armazon',
    ];

    protected $casts = [
        'fecha_preferida' => 'date',
        'conoce_graduacion' => 'boolean',
        'graduacion' => 'array',
    ];
}
