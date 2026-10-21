<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Ajuste del sitio (par clave-valor).
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];
}
