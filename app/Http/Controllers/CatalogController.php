<?php

namespace App\Http\Controllers;

use App\Models\ContactLensBrand;
use App\Models\Frame;
use App\Models\LensType;
use App\Models\Package;
use App\Models\Treatment;

class CatalogController extends Controller
{
    /** Página de inicio. */
    public function home()
    {
        $lensTypes = LensType::activos()->with('designs')->get();
        $premium = LensType::where('slug', 'progresiva')->first()
            ?->designs()->where('premium', true)->orderBy('orden')->take(3)->get() ?? collect();
        $treatments = Treatment::activos()->where('premium', true)->take(4)->get();

        return view('pages.home', compact('lensTypes', 'premium', 'treatments'));
    }

    /** Catálogo de lentes + configurador. */
    public function lentes()
    {
        $lensTypes = LensType::activos()->with('designs')->get();

        // Tratamientos normales (paso 3) y extras aditivos como fotocromático (paso 4).
        $treatments = Treatment::activos()->where('es_extra', false)->get();
        $extras = Treatment::activos()->where('es_extra', true)->get();

        // Armazones (paso 1, obligatorio). Precio incluido (0) por ahora.
        $frames = Frame::activos()->get();

        $logo = 'img/logo-jreyes.png';

        // Datos para el configurador (JSON en el cliente): sin llamadas al backend.
        $configData = [
            'whatsapp' => config('services.whatsapp.number'),
            'logo' => asset($logo),
            'armazones' => $frames->map(fn($f) => [
                'numero' => $f->numero,
                'nombre' => $f->nombre,
                'precio' => (float) $f->precio,
                'imagen' => asset($f->imagen ?: $logo),
            ])->values(),
            'tipos' => $lensTypes->map(fn($t) => [
                'slug' => $t->slug,
                'nombre' => $t->nombre,
                'descripcion' => $t->resumen,
                'disenos' => $t->designs->map(fn($d) => [
                    'slug' => $d->slug,
                    'nombre' => $d->nombre,
                    'material' => $d->material,
                    'precio' => (float) $d->precio,
                    'premium' => (bool) $d->premium,
                    'descripcion' => $d->descripcion,
                    'imagen' => asset($d->imagen ?: $logo),
                ])->values(),
            ])->values(),
            'tratamientos' => $treatments->map(fn($t) => [
                'slug' => $t->slug,
                'nombre' => $t->nombre,
                'familia' => $t->familia,
                'precio' => (float) $t->precio,
                'descripcion' => $t->descripcion,
                'imagen' => asset($t->imagen ?: $logo),
            ])->values(),
            'extras' => $extras->map(fn($t) => [
                'slug' => $t->slug,
                'nombre' => $t->nombre,
                'familia' => $t->familia,
                'precio' => (float) $t->precio,
                'descripcion' => $t->descripcion,
                'imagen' => asset($t->imagen ?: $logo),
            ])->values(),
        ];

        return view('pages.lentes', compact('lensTypes', 'treatments', 'extras', 'frames', 'configData'));
    }

    /** Marcas: Varilux y Crizal, más la tabla comparativa de tratamientos. */
    public function marcas()
    {
        $varilux = LensType::where('slug', 'progresiva')->first()
            ?->designs()->where('marca', 'Varilux')->orderBy('orden')->get() ?? collect();
        $crizal = Treatment::activos()->where('familia', 'Crizal')->get();
        $comparativa = Treatment::activos()->get();

        return view('pages.marcas', compact('varilux', 'crizal', 'comparativa'));
    }

    /** Catálogo de lentes de contacto agrupado por marca. */
    public function contacto()
    {
        $brands = ContactLensBrand::ordenadas()->with('lenses')->get();
        $whatsapp = config('services.whatsapp.number');
        $logo = asset('img/logo-jreyes.png');

        return view('pages.lentes-contacto', compact('brands', 'whatsapp', 'logo'));
    }

    /** Paquetes "lentes completos". */
    public function paquetes()
    {
        $packages = Package::activos()->get()->groupBy('nombre');
        $whatsapp = config('services.whatsapp.number');

        return view('pages.paquetes', compact('packages', 'whatsapp'));
    }

    /** Página institucional "Empresa". Contenido estático. */
    public function empresa()
    {
        $whatsapp = config('services.whatsapp.number');

        return view('pages.empresa', compact('whatsapp'));
    }
    public function programas()
    {
        $whatsapp = config('services.whatsapp.number');

        return view('pages.programas', compact('whatsapp'));
    }
}
