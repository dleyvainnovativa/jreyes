<?php

namespace Database\Seeders;

use App\Models\CatalogNode;
use Illuminate\Database\Seeder;

/**
 * Construye el árbol de catálogo del configurador.
 *
 * PRECIOS
 * -------
 * Los precios viven EXPLÍCITOS en este seeder, por nodo. A diferencia de la
 * versión anterior, ya NO se toman de las tablas treatments/lens_designs,
 * porque el formato del cliente (CORRECION_DE_PRECIOS) define precios
 * DISTINTOS para el mismo tratamiento según el nivel (p. ej. Fotocromático
 * cuesta $650 en convencional pero $800 en intermedio). Una tabla plana de
 * tratamientos con un solo precio no puede representar eso.
 *
 * Esto convierte a este archivo en la ÚNICA fuente de verdad de precios del
 * configurador: para actualizar un precio, edítalo aquí y vuelve a sembrar.
 *
 * Estructura por tipo (profundidad variable; el configurador solo muestra
 * los hijos del nodo elegido, así que la profundidad la maneja el árbol):
 *   Monofocal : nivel -> material -> tratamiento
 *   Bifocal   : nivel -> diseño (flat top|blend) -> tratamiento   (SOLO CR-39)
 *   Progresiva convencional/intermedio: nivel -> material -> tratamiento
 *   Progresiva alta gama: nivel -> material/línea -> [línea] -> tratamiento
 *
 * Nodos con precio 0 (agrupadores: nivel, diseño, material agrupador) no
 * suman al total. El total suma solo las hojas/opciones con precio > 0.
 */
class CatalogTreeSeeder extends Seeder
{
    public function run(): void
    {
        CatalogNode::query()->delete();

        $this->seedMonofocal();
        $this->seedBifocal();
        $this->seedProgresiva();
    }

    /* ----------------------------------------------------------------------
       Imágenes concretas por slug (reutiliza las que ya existen en public/img).
       Amplía esta lista cuando el cliente entregue más imágenes.
       ---------------------------------------------------------------------- */
    private array $overrideImg = [
        'prog-altagama-cr39-liberty'    => 'img/varilux-liberty-360.jpg',
        'prog-altagama-cr39-comfort'    => 'img/varilux-comfort.jpg',
        'prog-altagama-cr39-physio'     => 'img/varilux-physio.jpg',
        'prog-altagama-airwear-liberty' => 'img/varilux-liberty-360.jpg',
        'prog-altagama-airwear-comfort' => 'img/varilux-comfort.jpg',
        'prog-altagama-airwear-physio'  => 'img/varilux-physio.jpg',
    ];

    /* ----------------------------------------------------------------------
       Helper de creación de nodos
       ---------------------------------------------------------------------- */
    private function node(array $attrs, ?int $parentId, string $tipo, int $orden): CatalogNode
    {
        return CatalogNode::create([
            'parent_id'   => $parentId,
            'tipo'        => $tipo,
            'kind'        => $attrs['kind'],
            'nombre'      => $attrs['nombre'],
            'slug'        => $attrs['slug'],
            'precio'      => $attrs['precio'] ?? 0,
            'es_extra'    => $attrs['es_extra'] ?? false,
            'imagen'      => $attrs['imagen'] ?? ($this->overrideImg[$attrs['slug']] ?? null),
            'descripcion' => $attrs['descripcion'] ?? null,
            'orden'       => $orden,
            'activo'      => true,
        ]);
    }

    /**
     * Crea las hojas de tratamiento bajo un nodo padre.
     * Cada item: ['Nombre', precio, es_extra?]
     */
    private function trat(CatalogNode $padre, string $tipo, string $prefijo, array $items): void
    {
        foreach ($items as $idx => $item) {
            $this->node([
                'kind'     => 'tratamiento',
                'nombre'   => $item[0],
                'slug'     => $prefijo . '-t' . ($idx + 1),
                'precio'   => $item[1],
                'es_extra' => $item[2] ?? false,
            ], $padre->id, $tipo, $idx + 1);
        }
    }

    /* ======================================================================
       MONOFOCAL :  nivel -> material -> tratamiento
       Material: CR-39 $150 / Policarbonato $250
       ====================================================================== */
    private function seedMonofocal(): void
    {
        $tipo = 'monofocal';

        // --- Convencional ---
        $conv = $this->node(['kind' => 'nivel', 'nombre' => 'Convencional', 'slug' => 'mono-conv',
            'descripcion' => 'Diseño estándar, la opción más accesible.'], null, $tipo, 1);
        $convTrats = [
            ['Antirreflejante', 300],
            ['Fotocromático', 650, true],
            ['Antirreflejante + Fotocromático', 750],
        ];
        $this->materialesMono($conv, $tipo, 'mono-conv', 150, 250, $convTrats);

        // --- Intermedio ---
        $inter = $this->node(['kind' => 'nivel', 'nombre' => 'Intermedio', 'slug' => 'mono-inter',
            'descripcion' => 'Mejor calidad óptica y tratamientos de gama media.'], null, $tipo, 2);
        $interTrats = [
            ['Blueray', 500],
            ['Saphir Retilens', 700],
            ['Fotocromático', 800, true],
            ['Fotocromático + Saphir Retilens', 1100],
        ];
        $this->materialesMono($inter, $tipo, 'mono-inter', 150, 250, $interTrats);

        // --- Alta gama ---
        $alta = $this->node(['kind' => 'nivel', 'nombre' => 'Alta gama', 'slug' => 'mono-alta',
            'descripcion' => 'Tratamientos premium de la línea Crizal.'], null, $tipo, 3);
        $altaTrats = [
            ['Crizal Easy', 1400],
            ['Crizal Rock', 1600],
            ['Crizal Sapphire', 1950],
            ['Crizal Prevencia', 1950],
            ['+ Transition', 1100, true],
        ];
        $this->materialesMono($alta, $tipo, 'mono-alta', 150, 250, $altaTrats);
    }

    /** Crea CR-39 y Policarbonato bajo un nivel monofocal, con sus tratamientos. */
    private function materialesMono(CatalogNode $nivel, string $tipo, string $prefijo, int $precioCr, int $precioPoli, array $trats): void
    {
        $cr = $this->node(['kind' => 'material', 'nombre' => 'CR-39', 'slug' => "$prefijo-cr39",
            'precio' => $precioCr, 'descripcion' => 'Mica plástica estándar, ligera y económica.'],
            $nivel->id, $tipo, 1);
        $this->trat($cr, $tipo, "$prefijo-cr39", $trats);

        $poli = $this->node(['kind' => 'material', 'nombre' => 'Policarbonato', 'slug' => "$prefijo-poli",
            'precio' => $precioPoli, 'descripcion' => 'Más delgada y resistente a impactos.'],
            $nivel->id, $tipo, 2);
        $this->trat($poli, $tipo, "$prefijo-poli", $trats);
    }

    /* ======================================================================
       BIFOCAL :  nivel -> diseño (flat top|blend) -> tratamiento
       SOLO material CR-39 (el formato del cliente anula Policarbonato).
       Diseño: Flat Top $250 / Blend $550
       ====================================================================== */
    private function seedBifocal(): void
    {
        $tipo = 'bifocal';

        // --- Convencional ---
        $conv = $this->node(['kind' => 'nivel', 'nombre' => 'Convencional', 'slug' => 'bi-conv',
            'descripcion' => 'Bifocal clásico con segmento de lectura. Solo en CR-39.'], null, $tipo, 1);
        $convTrats = [
            ['+ Antirreflejante', 300],
            ['+ Fotocromático', 650, true],
            ['Antirreflejante + Fotocromático', 750],
        ];
        $this->disenosBifocal($conv, $tipo, 'bi-conv', $convTrats);

        // --- Intermedio ---
        $inter = $this->node(['kind' => 'nivel', 'nombre' => 'Intermedio', 'slug' => 'bi-inter',
            'descripcion' => 'Bifocal con tratamientos de gama media. Solo en CR-39.'], null, $tipo, 2);
        $interTrats = [
            ['+ Antirreflejante', 450],
            ['+ Fotocromático', 750, true],
            ['Antirreflejante + Fotocromático', 750],
        ];
        $this->disenosBifocal($inter, $tipo, 'bi-inter', $interTrats);
    }

    /** Crea Flat Top y Blend bajo un nivel bifocal (sin paso de material). */
    private function disenosBifocal(CatalogNode $nivel, string $tipo, string $prefijo, array $trats): void
    {
        $ftop = $this->node(['kind' => 'diseno', 'nombre' => 'Flat Top', 'slug' => "$prefijo-ftop",
            'precio' => 250, 'descripcion' => 'Segmento de lectura visible en forma de "D".'],
            $nivel->id, $tipo, 1);
        $this->trat($ftop, $tipo, "$prefijo-ftop", $trats);

        $blend = $this->node(['kind' => 'diseno', 'nombre' => 'Blend', 'slug' => "$prefijo-blend",
            'precio' => 550, 'descripcion' => 'Transición mezclada, línea menos marcada.'],
            $nivel->id, $tipo, 2);
        $this->trat($blend, $tipo, "$prefijo-blend", $trats);
    }

    /* ======================================================================
       PROGRESIVA
         convencional/intermedio: nivel -> material -> tratamiento
         alta gama              : nivel -> material/línea -> [línea] -> tratamiento
       ====================================================================== */
    private function seedProgresiva(): void
    {
        $tipo = 'progresiva';

        // --- Convencional ---
        $conv = $this->node(['kind' => 'nivel', 'nombre' => 'Convencional', 'slug' => 'prog-conv',
            'descripcion' => 'Progresiva de diseño estándar, sin líneas.'], null, $tipo, 1);
        $convTrats = [
            ['+ Antirreflejante', 300],
            ['+ Fotocromático', 650, true],
            ['Antirreflejante + Fotocromático', 750],
        ];
        $this->materialesProg($conv, $tipo, 'prog-conv', 900, 1250, $convTrats);

        // --- Intermedio ---
        $inter = $this->node(['kind' => 'nivel', 'nombre' => 'Intermedio', 'slug' => 'prog-inter',
            'descripcion' => 'Progresiva con tratamientos de gama media.'], null, $tipo, 2);
        $interTrats = [
            ['+ Blueray', 450],
            ['+ Fotocromático', 650, true],
            ['Blueray + Fotocromático', 750],
        ];
        $this->materialesProg($inter, $tipo, 'prog-inter', 950, 1050, $interTrats);

        // --- Alta gama ---
        // La línea Varilux se queda IGUAL en CR-39 y Airwear (precios sin cambio).
        // Easy Fit es su PROPIA línea (NO va bajo Airwear) con sus propios
        // tratamientos y precio.
        $alta = $this->node(['kind' => 'nivel', 'nombre' => 'Alta gama', 'slug' => 'prog-alta',
            'descripcion' => 'Diseños premium Varilux y Easy Fit.'], null, $tipo, 3);

        // Tratamientos de las líneas Varilux (Crizal) — precios de alta gama.
        $tratsVarilux = [
            ['Crizal Easy', 1400],
            ['Crizal Rock', 1600],
            ['Crizal Sapphire', 1950],
            ['Crizal Prevencia', 1950],
            ['+ Transition', 1100, true],
        ];

        // Líneas Varilux (precio del diseño, sin cambio respecto al catálogo).
        $lineasVarilux = [
            ['Varilux Liberty 360°', 'liberty', 2650, 3250],
            ['Varilux Comfort',      'comfort', 2350, 2950],
            ['Varilux Physio 3.0',   'physio',  2950, 3650],
        ];

        // Material 1: CR-39 (agrupador) -> líneas Varilux (precio CR-39).
        $matCr = $this->node(['kind' => 'material', 'nombre' => 'CR-39', 'slug' => 'prog-altagama-cr39',
            'descripcion' => 'Mica estándar para diseños progresivos premium.'],
            $alta->id, $tipo, 1);
        foreach ($lineasVarilux as $li => [$lNombre, $lKey, $pCr, $pPoli]) {
            $linea = $this->node(['kind' => 'linea', 'nombre' => $lNombre,
                'slug' => "prog-altagama-cr39-$lKey", 'precio' => $pCr],
                $matCr->id, $tipo, $li + 1);
            $this->trat($linea, $tipo, "prog-altagama-cr39-$lKey", $tratsVarilux);
        }

        // Material 2: Airwear (agrupador) -> líneas Varilux (precio Policarbonato).
        $matAir = $this->node(['kind' => 'material', 'nombre' => 'Airwear', 'slug' => 'prog-altagama-airwear',
            'descripcion' => 'Material de policarbonato, más ligero y resistente.'],
            $alta->id, $tipo, 2);
        foreach ($lineasVarilux as $li => [$lNombre, $lKey, $pCr, $pPoli]) {
            $linea = $this->node(['kind' => 'linea', 'nombre' => $lNombre,
                'slug' => "prog-altagama-airwear-$lKey", 'precio' => $pPoli],
                $matAir->id, $tipo, $li + 1);
            $this->trat($linea, $tipo, "prog-altagama-airwear-$lKey", $tratsVarilux);
        }

        // Línea 3: Easy Fit — su propia rama (NO bajo Airwear), precio $1,450,
        // con tratamientos exclusivos. No tiene opción Airwear.
        $easyFit = $this->node(['kind' => 'linea', 'nombre' => 'Easy Fit', 'slug' => 'prog-altagama-easyfit',
            'precio' => 1450, 'descripcion' => 'Diseño de adaptación sencilla. Solo en esta línea.'],
            $alta->id, $tipo, 3);
        $this->trat($easyFit, $tipo, 'prog-altagama-easyfit', [
            ['+ Antirreflejante', 300],
            ['+ Antirreflejante + Fotocromático', 650],
            ['Retilens', 1050],
        ]);
    }

    /** Crea CR-39 y Policarbonato bajo un nivel progresivo, con sus tratamientos. */
    private function materialesProg(CatalogNode $nivel, string $tipo, string $prefijo, int $precioCr, int $precioPoli, array $trats): void
    {
        $cr = $this->node(['kind' => 'material', 'nombre' => 'CR-39', 'slug' => "$prefijo-cr39",
            'precio' => $precioCr], $nivel->id, $tipo, 1);
        $this->trat($cr, $tipo, "$prefijo-cr39", $trats);

        $poli = $this->node(['kind' => 'material', 'nombre' => 'Policarbonato', 'slug' => "$prefijo-poli",
            'precio' => $precioPoli], $nivel->id, $tipo, 2);
        $this->trat($poli, $tipo, "$prefijo-poli", $trats);
    }
}
