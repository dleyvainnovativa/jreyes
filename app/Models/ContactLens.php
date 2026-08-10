<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactLens extends Model
{
    protected $table = 'contact_lenses';

    protected $fillable = [
        'contact_lens_brand_id',
        'nombre',
        'precio',
        'tipo',
        'reemplazo',
        'activo',
        'orden',
        'imagen'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(ContactLensBrand::class, 'contact_lens_brand_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}
