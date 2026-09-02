<?php

namespace App\Http\Controllers;

use App\Models\CatalogNode;
use App\Models\ContactLensBrand;
use App\Models\Frame;
use App\Models\LensType;
use App\Models\Package;
use App\Models\Treatment;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /** Tipos válidos del configurador y su etiqueta para el encabezado. */
    private const TIPOS = [
        'monofocal'  => 'Monofocales',
        'bifocal'    => 'Bifocales',
        'progresiva' => 'Progresivos',
    ];

    /** Página de inicio. */
    public function home()
    {
        $lensTypes = LensType::activos()->with('designs')->get();
        $premium = LensType::where('slug', 'progresiva')->first()
            ?->designs()->where('premium', true)->orderBy('orden')->take(3)->get() ?? collect();
        $treatments = Treatment::activos()->where('premium', true)->take(4)->get();

        return view('pages.home', compact('lensTypes', 'premium', 'treatments'));
    }

    /**
     * Catálogo de lentes + configurador.
     * Acepta ?tipo=monofocal|bifocal|progresiva para filtrar el árbol.
     * Sin parámetro (o inválido) muestra el primero disponible.
     */
    public function lentes(Request $request)
    {
        $tipo = $request->query('tipo');
        if (!array_key_exists($tipo, self::TIPOS)) {
            $tipo = array_key_first(self::TIPOS);
        }

        $logo = 'img/logo-jreyes.png';

        // Armazones (paso 1, obligatorio).
        $frames = Frame::activos()->get();

        // Árbol de catálogo del tipo elegido (raíces + descendientes).
        $raices = CatalogNode::raices()->deTipo($tipo)->activos()
            ->with('descendants')->get();

        $configData = [
            'whatsapp' => config('services.whatsapp.number'),
            'logo'     => asset($logo),
            'tipo'     => $tipo,
            'tipoNombre' => self::TIPOS[$tipo],
            'armazones' => $frames->map(fn ($f) => [
                'nombre' => $f->nombre,
                'precio' => (float) $f->precio,
                'imagen' => asset($f->imagen ?: $logo),
            ])->values(),
            // Árbol serializado: cada nodo lleva sus hijos.
            'arbol' => $raices->map(fn ($n) => $this->serializeNode($n, $logo))->values(),
        ];

        return view('pages.lentes', [
            'tipos'       => self::TIPOS,
            'tipoActual'  => $tipo,
            'tipoNombre'  => self::TIPOS[$tipo],
            'frames'      => $frames,
            'configData'  => $configData,
        ]);
    }

    /** Serializa un nodo del árbol (recursivo) para el JSON del cliente. */
    private function serializeNode(CatalogNode $n, string $logo): array
    {
        return [
            'slug'        => $n->slug,
            'kind'        => $n->kind,
            'nombre'      => $n->nombre,
            'precio'      => (float) $n->precio,
            'es_extra'    => (bool) $n->es_extra,
            'descripcion' => $n->descripcion,
            'imagen'      => $n->imagen ? asset($n->imagen) : null,
            'hijos'       => $n->children->map(fn ($c) => $this->serializeNode($c, $logo))->values(),
        ];
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
