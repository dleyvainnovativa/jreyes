<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LensDesign extends Model
{
    protected $fillable = [
        'lens_type_id', 'slug', 'nombre', 'marca', 'material', 'indice',
        'precio', 'premium', 'imagen', 'descripcion', 'orden', 'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'premium' => 'boolean',
        'activo' => 'boolean',
    ];

    public function lensType(): BelongsTo
    {
        return $this->belongsTo(LensType::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}
