<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactLensBrand extends Model
{
    protected $fillable = ['slug', 'nombre', 'orden'];

    public function lenses(): HasMany
    {
        return $this->hasMany(ContactLens::class)->orderBy('orden');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden');
    }
}
