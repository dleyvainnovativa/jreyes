<?php

namespace App\Http\Controllers;

use App\Models\ContactLensBrand;
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
        $treatments = Treatment::activos()->where('es_extra', false)->get();
        $extras     = Treatment::activos()->where('es_extra', true)->get();
        $logo = 'img/logo-jreyes.png';
        // Datos para el configurador (JSON en el cliente): sin llamadas al backend.
        $configData = [
            'whatsapp' => config('services.whatsapp.number'),
            'logo' => asset($logo),
            'tipos' => $lensTypes->map(fn($t) => [
                'slug' => $t->slug,
                'nombre' => $t->nombre,
                'disenos' => $t->designs->map(fn($d) => [
                    'slug' => $d->slug,
                    'nombre' => $d->nombre,
                    'material' => $d->material,
                    'precio' => (float) $d->precio,
                    'premium' => (bool) $d->premium,
                ])->values(),
            ])->values(),
            'tratamientos' => $treatments->map(fn($t) => [
                'slug' => $t->slug,
                'nombre' => $t->nombre,
                'familia' => $t->familia,
                'imagen' => $t->imagen,
                'precio' => (float) $t->precio,
            ])->values(),
            'extras' => $extras->map(fn($t) => [
                'slug' => $t->slug,
                'nombre' => $t->nombre,
                'familia' => $t->familia,
                'precio' => (float) $t->precio,
                'descripcion' => $t->descripcion,
                'imagen' => $t->imagen,
            ])->values(),
        ];
        return view('pages.lentes', compact('lensTypes', 'treatments', 'extras', 'configData'));
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
        $logo = 'img/logo-jreyes.png';
        return view('pages.lentes-contacto', compact('brands', 'whatsapp', 'logo'));
    }

    /** Paquetes "lentes completos". */
    public function paquetes()
    {
        $packages = Package::activos()->get()->groupBy('nombre');
        $whatsapp = config('services.whatsapp.number');
        $logo = 'img/logo-jreyes.png';

        return view('pages.paquetes', compact('packages', 'whatsapp', 'logo'));
    }
}
