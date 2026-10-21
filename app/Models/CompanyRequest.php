<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Solicitud enviada desde el formulario para empresas.
 */
class CompanyRequest extends Model
{
    protected $fillable = [
        'empresa', 'nombre_contacto', 'puesto', 'telefono',
        'correo', 'num_empleados', 'mensaje', 'estatus',
    ];
}
