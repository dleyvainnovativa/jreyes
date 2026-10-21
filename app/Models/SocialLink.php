<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Enlace de red social (con color y orden propios).
 */
class SocialLink extends Model
{
    /**
     * Plataformas conocidas: etiqueta, ícono Font Awesome y color sugerido.
     * 'otro' permite un ícono personalizado desde el admin.
     */
    public const PLATAFORMAS = [
        'whatsapp'  => ['WhatsApp',  'fa-brands fa-whatsapp',    '#25D366'],
        'facebook'  => ['Facebook',  'fa-brands fa-facebook-f',  '#1877F2'],
        'instagram' => ['Instagram', 'fa-brands fa-instagram',   '#E4405F'],
        'tiktok'    => ['TikTok',    'fa-brands fa-tiktok',      '#010101'],
        'x'         => ['X',         'fa-brands fa-x-twitter',   '#000000'],
        'youtube'   => ['YouTube',   'fa-brands fa-youtube',     '#FF0000'],
        'linkedin'  => ['LinkedIn',  'fa-brands fa-linkedin-in', '#0A66C2'],
        'otro'      => ['Otro',      'fa-solid fa-link',         '#888888'],
    ];

    protected $fillable = ['plataforma', 'icono', 'url', 'color', 'orden', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function scopeActivos($query)
    {
        return $query->where('activo', true)->orderBy('orden');
    }
}
