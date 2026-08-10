<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = [
        'slug',
        'nombre',
        'familia',
        'precio',
        'descripcion',
        'imagen',
        'proteccion_uv',
        'luz_azul',
        'reflejos',
        'rayas',
        'manchas',
        'agua',
        'premium',
        'orden',
        'activo',
        'es_extra',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'premium' => 'boolean',
        'activo' => 'boolean',
        'es_extra' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }

    /** Puntuaciones para la tabla comparativa estilo "baterías" de Crizal. */
    public function puntuaciones(): array
    {
        return [
            'Protección UV'   => $this->proteccion_uv,
            'Luz azul'        => $this->luz_azul,
            'Reflejos'        => $this->reflejos,
            'Rayas'           => $this->rayas,
            'Manchas'         => $this->manchas,
            'Agua'            => $this->agua,
        ];
    }
}
