<?php

namespace Database\Seeders;

use App\Models\CatalogNode;
use App\Models\LensDesign;
use App\Models\Treatment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Construye el árbol de catálogo del configurador a partir del formato del
 * cliente (formato.xlsx: hojas MONOFOCAL, BIFOCALES, PROGRESIVAS).
 *
 * PRECIOS
 * -------
 * "Reusar la tabla ya sembrada": los precios se toman de las tablas
 * existentes (treatments, lens_designs) cuando el nodo coincide con un
 * registro ya sembrado. Los nodos que no coinciden o que solo agrupan
 * (nivel, diseño) quedan en 0 y en el sitio se muestran como
 * "Precio en tienda". El total del configurador solo suma nodos con
 * precio > 0.
 *
 * Los precios sin coincidencia (materiales de tiers superiores, blend,
 * airwear, etc.) se centralizan aquí abajo en $sinCoincidencia para que el
 * cliente los pueda llenar en un solo lugar cuando entregue los números.
 */
class CatalogTreeSeeder extends Seeder
{
    /** Precios tomados de treatments, cacheados por slug de tratamiento xlsx. */
    private array $tratamientoPrecios = [];

    /** Precios tomados de lens_designs para materiales/marcas. */
    private array $disenoPrecios = [];

    /**
     * Precios que el formato del cliente aún no tiene sembrados.
     * Déjalos en 0 para mostrar "Precio en tienda", o pon el número cuando
     * el cliente lo entregue. La clave es el slug del nodo.
     */
    private array $sinCoincidencia = [
        // Monofocal: solo hay precio sembrado para el par cr-39/poli base.
        'mono-intermedio-cr39'       => 0,
        'mono-intermedio-poli'       => 0,
        'mono-altagama-cr39'         => 0,
        'mono-altagama-poli'         => 0,
        // Bifocal: solo flat top cr-39 tiene precio sembrado.
        'bi-conv-ftop-poli'          => 0,
        'bi-conv-blend-cr39'         => 0,
        'bi-conv-blend-poli'         => 0,
        'bi-inter-ftop-cr39'         => 0,
        'bi-inter-ftop-poli'         => 0,
        'bi-inter-blend-cr39'        => 0,
        'bi-inter-blend-poli'        => 0,
        // Progresiva alta gama: rama airwear "easy fit".
        'prog-altagama-airwear'      => 0,
    ];

    public function run(): void
    {
        $this->cachePrecios();

        CatalogNode::query()->delete();

        $this->seedMonofocal();
        $this->seedBifocal();
        $this->seedProgresiva();
    }

    /* ----------------------------------------------------------------------
       Caché de precios de las tablas ya sembradas
       ---------------------------------------------------------------------- */
    private function cachePrecios(): void
    {
        // Tratamientos por nombre normalizado -> precio.
        foreach (Treatment::all() as $t) {
            $this->tratamientoPrecios[$this->norm($t->nombre)] = (float) $t->precio;
        }

        // Diseños indexados por NOMBRE + material (el nombre distingue entre
        // Varilux Comfort/Liberty/Physio, que comparten la marca "Varilux").
        // Indexar por marca colisiona, así que priorizamos nombre.
        foreach (LensDesign::all() as $d) {
            $this->disenoPrecios[$this->norm($d->nombre . '|' . $d->material)] = (float) $d->precio;
        }
    }

    /**
     * Normaliza: minúsculas, sin acentos, quita símbolos y colapsa espacios
     * DESPUÉS de quitarlos (para que "sapphire 360°" -> "sapphire 360",
     * no "sapphire  360").
     */
    private function norm(string $s): string
    {
        return Str::of($s)->lower()->ascii()
            ->replace(['+', '.', '°'], ' ')
            ->squish()->toString();
    }

    /** Precio de un tratamiento xlsx por nombre; 0 si no hay coincidencia. */
    private function precioTrat(string $nombre): float
    {
        return $this->tratamientoPrecios[$this->norm($nombre)] ?? 0.0;
    }

    /** Precio de un diseño por NOMBRE + material; 0 si no coincide. */
    private function precioDiseno(string $nombre, string $material): float
    {
        return $this->disenoPrecios[$this->norm($nombre . '|' . $material)] ?? 0.0;
    }

    private function precioForzado(string $slug, float $fallback = 0.0): float
    {
        return $this->sinCoincidencia[$slug] ?? $fallback;
    }

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
            'imagen'      => $attrs['imagen'] ?? $this->imgSiExiste($attrs['slug']),
            'descripcion' => $attrs['descripcion'] ?? null,
            'orden'       => $orden,
            'activo'      => true,
        ]);
    }

    /** Convención de imagen por slug: public/img/catalog/<slug>.jpg (opcional). */
    private function imgSiExiste(string $slug): ?string
    {
        // No comprobamos el disco aquí; la vista degrada con onerror.
        // Devolvemos null para que el resumen omita miniaturas por defecto.
        // El cliente puede fijar rutas concretas en $overrideImg.
        return $this->overrideImg[$slug] ?? null;
    }

    /**
     * Imágenes concretas por slug (reutiliza las que ya existen en public/img).
     * Amplía esta lista cuando el cliente entregue más imágenes.
     */
    private array $overrideImg = [
        // Materiales base
        'mono-conv-cr39'      => 'img/varilux-comfort.jpg',
        // Marcas Varilux (reutilizan imágenes ya sembradas)
        'prog-altagama-liberty' => 'img/varilux-liberty-360.jpg',
        'prog-altagama-comfort' => 'img/varilux-comfort.jpg',
        'prog-altagama-physio'  => 'img/varilux-physio.jpg',
        // Tratamientos con imagen ya sembrada
        'saphir-mono-inter'   => 'img/saphir-retilens.jpg',
    ];

    /* ======================================================================
       MONOFOCAL  :  nivel -> material -> tratamiento
       ====================================================================== */
    private function seedMonofocal(): void
    {
        $tipo = 'monofocal';

        // --- Convencional ---
        $conv = $this->node([
            'kind' => 'nivel',
            'nombre' => 'Convencional',
            'slug' => 'mono-conv',
            'descripcion' => 'Diseño estándar, la opción más accesible.'
        ], null, $tipo, 1);

        $convCr = $this->node([
            'kind' => 'material',
            'nombre' => 'CR-39',
            'slug' => 'mono-conv-cr39',
            'precio' => $this->precioDiseno('Monofocal', 'CR-39'),
            'descripcion' => 'Mica plástica estándar, ligera y económica.'
        ], $conv->id, $tipo, 1);
        $convPoli = $this->node([
            'kind' => 'material',
            'nombre' => 'Policarbonato',
            'slug' => 'mono-conv-poli',
            'precio' => $this->precioDiseno('Monofocal', 'Policarbonato'),
            'descripcion' => 'Más delgada y resistente a impactos.'
        ], $conv->id, $tipo, 2);

        foreach ([$convCr, $convPoli] as $i => $mat) {
            $suf = $i === 0 ? 'cr39' : 'poli';
            $this->trat($mat, $tipo, "mono-conv-$suf", [
                ['Antirreflejante', 'antirreflejante'],
                ['Fotocromático', 'fotocromatico', true],
                ['Fotocromático + Antirreflejante', 'fotocromatico + antireflejante'],
            ]);
        }

        // --- Intermedio ---
        $inter = $this->node([
            'kind' => 'nivel',
            'nombre' => 'Intermedio',
            'slug' => 'mono-intermedio',
            'descripcion' => 'Mejor calidad óptica y tratamientos de gama media.'
        ], null, $tipo, 2);

        $interCr = $this->node([
            'kind' => 'material',
            'nombre' => 'CR-39',
            'slug' => 'mono-intermedio-cr39',
            'precio' => $this->precioForzado('mono-intermedio-cr39')
        ], $inter->id, $tipo, 1);
        $interPoli = $this->node([
            'kind' => 'material',
            'nombre' => 'Policarbonato',
            'slug' => 'mono-intermedio-poli',
            'precio' => $this->precioForzado('mono-intermedio-poli')
        ], $inter->id, $tipo, 2);

        // CR-39: blueray, saphir, fotocromatico, fotocromatico saphir retilens
        $this->trat($interCr, $tipo, 'mono-inter-cr39', [
            ['Blueray', 'blueray'],
            ['Saphir Retilens', 'saphir retilens'],
            ['Fotocromático', 'fotocromatico', true],
            ['Fotocromático Saphir Retilens', 'saphir retilens'],
        ]);
        // Policarbonato: blueray, fotocromatico
        $this->trat($interPoli, $tipo, 'mono-inter-poli', [
            ['Blueray', 'blueray'],
            ['Fotocromático', 'fotocromatico', true],
        ]);

        // --- Alta gama ---
        $alta = $this->node([
            'kind' => 'nivel',
            'nombre' => 'Alta gama',
            'slug' => 'mono-altagama',
            'descripcion' => 'Tratamientos premium de la línea Crizal.'
        ], null, $tipo, 3);

        $altaCr = $this->node([
            'kind' => 'material',
            'nombre' => 'CR-39',
            'slug' => 'mono-altagama-cr39',
            'precio' => $this->precioForzado('mono-altagama-cr39')
        ], $alta->id, $tipo, 1);
        $altaPoli = $this->node([
            'kind' => 'material',
            'nombre' => 'Policarbonato',
            'slug' => 'mono-altagama-poli',
            'precio' => $this->precioForzado('mono-altagama-poli')
        ], $alta->id, $tipo, 2);

        foreach ([$altaCr, $altaPoli] as $i => $mat) {
            $suf = $i === 0 ? 'cr39' : 'poli';
            $this->trat($mat, $tipo, "mono-alta-$suf", [
                ['Crizal Easy', 'crizal easy'],
                ['Crizal Rock', 'crizal rock'],
                ['Crizal Sapphire 360°', 'Crizal Sapphire 360°'],
                ['Crizal Prevencia', 'crizal prevencia'],
                ['+ Transitions', 'transitions', true],
            ]);
        }
    }

    /* ======================================================================
       BIFOCAL  :  nivel -> diseno(flat top|blend) -> material -> tratamiento
       ====================================================================== */
    private function seedBifocal(): void
    {
        $tipo = 'bifocal';

        // --- Convencional ---
        $conv = $this->node([
            'kind' => 'nivel',
            'nombre' => 'Convencional',
            'slug' => 'bi-conv',
            'descripcion' => 'Bifocal clásico con segmento de lectura.'
        ], null, $tipo, 1);
        $this->bifocalDisenos($conv, $tipo, 'conv', [
            ['Antirreflejante', 'antirreflejante'],
            ['Fotocromático', 'fotocromatico', true],
            ['Fotocromático + Antirreflejante', 'fotocromatico + antireflejante']
        ]);

        // --- Intermedio ---
        $inter = $this->node([
            'kind' => 'nivel',
            'nombre' => 'Intermedio',
            'slug' => 'bi-inter',
            'descripcion' => 'Bifocal con tratamientos de gama media.'
        ], null, $tipo, 2);
        $this->bifocalDisenos($inter, $tipo, 'inter', [
            ['Blueray', 'blueray'],
            ['Fotocromático', 'fotocromatico', true],
            ['Fotocromático + Blueray', 'fotocromatico + blueray']
        ]);
    }

    /** Crea la sub-rama diseño(flat top|blend) -> material -> tratamientos. */
    private function bifocalDisenos(CatalogNode $nivel, string $tipo, string $nivelSlug, array $trats): void
    {
        $disenos = [
            ['Flat Top', 'ftop'],
            ['Blend', 'blend'],
        ];
        foreach ($disenos as $di => [$dNombre, $dSlug]) {
            $diseno = $this->node(
                [
                    'kind' => 'diseno',
                    'nombre' => $dNombre,
                    'slug' => "bi-$nivelSlug-$dSlug",
                    'descripcion' => $dSlug === 'ftop'
                        ? 'Segmento de lectura visible en forma de "D".'
                        : 'Transición mezclada, línea menos marcada.'
                ],
                $nivel->id,
                $tipo,
                $di + 1
            );

            $materiales = [
                ['CR-39', 'cr39'],
                ['Policarbonato', 'poli'],
            ];
            foreach ($materiales as $mi => [$mNombre, $mSlug]) {
                $slug = "bi-$nivelSlug-$dSlug-$mSlug";
                // Único precio sembrado: flat top cr-39 convencional.
                $precio = ($nivelSlug === 'conv' && $dSlug === 'ftop' && $mSlug === 'cr39')
                    ? $this->precioDiseno('Flat Top', 'CR-39')
                    : $this->precioForzado($slug);

                $mat = $this->node([
                    'kind' => 'material',
                    'nombre' => $mNombre,
                    'slug' => $slug,
                    'precio' => $precio
                ], $diseno->id, $tipo, $mi + 1);

                $this->trat($mat, $tipo, $slug, $trats);
            }
        }
    }

    /* ======================================================================
       PROGRESIVA :  nivel -> material/marca -> tratamiento
       ====================================================================== */
    private function seedProgresiva(): void
    {
        $tipo = 'progresiva';

        // --- Convencional ---
        $conv = $this->node([
            'kind' => 'nivel',
            'nombre' => 'Convencional',
            'slug' => 'prog-conv',
            'descripcion' => 'Progresiva de diseño estándar, sin líneas.'
        ], null, $tipo, 1);
        $convCr = $this->node([
            'kind' => 'material',
            'nombre' => 'CR-39',
            'slug' => 'prog-conv-cr39',
            'precio' => $this->precioDiseno('Convencional', 'CR-39')
        ], $conv->id, $tipo, 1);
        $convPoli = $this->node([
            'kind' => 'material',
            'nombre' => 'Policarbonato',
            'slug' => 'prog-conv-poli',
            'precio' => $this->precioDiseno('Convencional', 'Policarbonato')
        ], $conv->id, $tipo, 2);
        foreach ([$convCr, $convPoli] as $i => $mat) {
            $this->trat($mat, $tipo, 'prog-conv-' . ($i === 0 ? 'cr39' : 'poli'), [
                ['Antirreflejante', 'antirreflejante'],
                ['Fotocromático', 'fotocromatico', true],
                ['Fotocromático + Antirreflejante', 'fotocromatico + antireflejante']
            ]);
        }

        // --- Intermedio ---
        $inter = $this->node([
            'kind' => 'nivel',
            'nombre' => 'Intermedio',
            'slug' => 'prog-inter',
            'descripcion' => 'Progresiva con tratamientos de gama media.'
        ], null, $tipo, 2);
        $interCr = $this->node(
            [
                'kind' => 'material',
                'nombre' => 'CR-39',
                'slug' => 'prog-inter-cr39',
                'precio' => $this->precioForzado('prog-inter-cr39', $this->precioDiseno('Convencional', 'CR-39'))
            ],
            $inter->id,
            $tipo,
            1
        );
        $interPoli = $this->node(
            [
                'kind' => 'material',
                'nombre' => 'Policarbonato',
                'slug' => 'prog-inter-poli',
                'precio' => $this->precioForzado('prog-inter-poli', $this->precioDiseno('Convencional', 'Policarbonato'))
            ],
            $inter->id,
            $tipo,
            2
        );
        foreach ([$interCr, $interPoli] as $i => $mat) {
            $this->trat($mat, $tipo, 'prog-inter-' . ($i === 0 ? 'cr39' : 'poli'), [
                ['Blueray', 'blueray'],
                ['Fotocromático', 'fotocromatico', true],
                ['Fotocromático + Blueray', 'fotocromatico + blueray']
            ]);
        }

        // --- Alta gama (marcas Varilux + Airwear) ---
        $alta = $this->node([
            'kind' => 'nivel',
            'nombre' => 'Alta gama',
            'slug' => 'prog-altagama',
            'descripcion' => 'Diseños premium Varilux con tratamientos Crizal.'
        ], null, $tipo, 3);

        // Aquí el "material" es la marca/diseño. Precio del diseño Varilux
        // sembrado (usamos la variante CR-39 como precio base de referencia).
        // El precio se busca por NOMBRE del diseño (Varilux Comfort/Liberty/
        // Physio tienen precios distintos). Airwear no está sembrado -> 0.
        $marcas = [
            ['Varilux Liberty 360°', 'prog-altagama-liberty'],
            ['Varilux Comfort',      'prog-altagama-comfort'],
            ['Varilux Physio 3.0',   'prog-altagama-physio'],
            ['Airwear (easy fit)',   'prog-altagama-airwear'],
        ];
        $trats = [
            ['Crizal Easy', 'crizal easy'],
            ['Crizal Rock', 'crizal rock'],
            ['Crizal Sapphire 360°', 'Crizal Sapphire 360°'],
            ['Crizal Prevencia', 'crizal prevencia'],
            ['+ Transitions', 'transitions', true],
            ['Retilens', 'saphir retilens'],
            ['Retilens + Fotocromático', 'saphir retilens'],
        ];
        foreach ($marcas as $oi => [$mNombre, $mSlug]) {
            // Precio: Varilux sembrado por nombre; Airwear sin coincidencia -> 0.
            $precio = $mSlug === 'prog-altagama-airwear'
                ? $this->precioForzado($mSlug)
                : $this->precioDiseno($mNombre, 'CR-39');

            $marcaNode = $this->node([
                'kind' => 'material',
                'nombre' => $mNombre,
                'slug' => $mSlug,
                'precio' => $precio
            ], $alta->id, $tipo, $oi + 1);

            $this->trat($marcaNode, $tipo, $mSlug, $trats);
        }
    }

    /* ----------------------------------------------------------------------
       Crea los tratamientos (hojas) bajo un material.
       Cada item: [Nombre visible, nombre-para-precio, es_extra?]
       ---------------------------------------------------------------------- */
    private function trat(CatalogNode $material, string $tipo, string $prefijo, array $items): void
    {
        foreach ($items as $idx => $item) {
            [$nombre, $matchNombre] = [$item[0], $item[1]];
            $esExtra = $item[2] ?? false;

            $precio = $this->precioTrat($matchNombre);
            // "fotocromatico + antireflejante" = suma de ambos precios.
            if ($this->norm($matchNombre) === $this->norm('fotocromatico + antireflejante')) {
                $precio = $this->precioTrat('Fotocromático') + $this->precioTrat('Antirreflejante');
            }

            $this->node([
                'kind'     => 'tratamiento',
                'nombre'   => $nombre,
                'slug'     => $prefijo . '-t' . ($idx + 1),
                'precio'   => $precio,
                'es_extra' => $esExtra,
            ], $material->id, $tipo, $idx + 1);
        }
    }
}
