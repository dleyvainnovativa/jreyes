<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Nodo del árbol de catálogo del configurador.
 * Ver migración create_catalog_nodes_table para la forma del árbol.
 */
class CatalogNode extends Model
{
    protected $fillable = [
        'parent_id', 'tipo', 'kind', 'nombre', 'slug',
        'precio', 'es_extra', 'imagen', 'descripcion', 'orden', 'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'es_extra' => 'boolean',
        'activo' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('orden');
    }

    /** Sub-árbol completo desde este nodo (para serializar al cliente). */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }

    public function scopeRaices($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeDeTipo($query, string $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
