<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Support\Collection;

/**
 * Lee los ajustes del sitio (tabla settings + social_links) una sola vez por
 * petición y los expone a las vistas. Si las tablas aún no existen (antes de
 * migrar), degrada a valores vacíos sin romper la app.
 */
class SiteSettings
{
    protected array $valores = [];
    protected Collection $redes;

    public function __construct()
    {
        $this->redes = collect();

        try {
            $this->valores = Setting::pluck('value', 'key')->toArray();
            $this->redes = SocialLink::activos()->get();
        } catch (\Throwable $e) {
            // Tablas inexistentes (migración pendiente) o BD no disponible.
        }
    }

    /** Valor crudo de un ajuste. */
    public function get(string $clave, $defecto = null)
    {
        $v = $this->valores[$clave] ?? null;

        return ($v === null || $v === '') ? $defecto : $v;
    }

    /** Acceso cómodo: $ajustes->telefono, $ajustes->correo, etc. */
    public function __get($name)
    {
        return $this->get($name);
    }

    /** Número de WhatsApp solo con dígitos (para wa.me). */
    public function whatsapp(): string
    {
        return preg_replace('/\D/', '', (string) $this->get('whatsapp_number', ''));
    }

    /** Enlace wa.me con mensaje opcional, o null si no hay número. */
    public function whatsappLink(string $texto = ''): ?string
    {
        $n = $this->whatsapp();
        if ($n === '') {
            return null;
        }

        return 'https://wa.me/' . $n . ($texto !== '' ? '?text=' . rawurlencode($texto) : '');
    }

    /** Renglones de horario: [ ['etiqueta' => ..., 'horario' => ...], ... ]. */
    public function horario(): array
    {
        $raw = $this->get('horario');
        $arr = $raw ? json_decode($raw, true) : [];

        return is_array($arr) ? $arr : [];
    }

    /** Enlaces de redes sociales activos (colección de SocialLink). */
    public function redes(): Collection
    {
        return $this->redes;
    }

    /** Ubicación compuesta: "Dirección, Ciudad, Estado" (omite vacíos). */
    public function ubicacion(): string
    {
        return collect([$this->get('direccion'), $this->get('ciudad'), $this->get('estado')])
            ->filter()
            ->implode(', ');
    }
}
