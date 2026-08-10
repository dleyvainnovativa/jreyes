<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'nombre', 'telefono', 'correo', 'motivo', 'fecha_preferida', 'mensaje', 'estatus',
    ];

    protected $casts = [
        'fecha_preferida' => 'date',
    ];
}
