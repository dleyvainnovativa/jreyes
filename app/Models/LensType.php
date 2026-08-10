<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LensType extends Model
{
    protected $fillable = [
        'slug', 'nombre', 'resumen', 'descripcion', 'icono', 'orden', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function designs(): HasMany
    {
        return $this->hasMany(LensDesign::class)->orderBy('orden');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}
