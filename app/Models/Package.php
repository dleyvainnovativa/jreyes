<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'slug', 'nombre', 'tratamiento', 'precio', 'incluye', 'destacado', 'orden', 'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'destacado' => 'boolean',
        'activo' => 'boolean',
    ];

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}
