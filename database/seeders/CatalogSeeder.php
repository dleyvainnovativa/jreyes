<?php

namespace Database\Seeders;

use App\Models\ContactLens;
use App\Models\ContactLensBrand;
use App\Models\LensDesign;
use App\Models\LensType;
use App\Models\Package;
use App\Models\Treatment;
use Illuminate\Database\Seeder;

/**
 * Datos tomados de "Lista de Precios Actual" de JReyes Ópticos.
 * Todos los precios están en pesos mexicanos (MXN).
 */
class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedLensTypes();
        $this->seedTreatments();
        $this->seedContactLenses();
        $this->seedPackages();
    }

    private function seedLensTypes(): void
    {
        // ---- MONOFOCAL ----
        $mono = LensType::create([
            'slug' => 'monofocal',
            'nombre' => 'Monofocal',
            'resumen' => 'Una sola graduación para ver de lejos o de cerca.',
            'descripcion' => 'La mica monofocal corrige una distancia a la vez. Es la opción ideal para miopía, hipermetropía o astigmatismo cuando solo necesitas una graduación.',
            'icono' => 'fa-solid fa-circle',
            'orden' => 1,
        ]);
        LensDesign::insert([
            ['lens_type_id' => $mono->id, 'slug' => 'monofocal-cr39', 'nombre' => 'Monofocal', 'marca' => null, 'material' => 'CR-39', 'indice' => '1.50', 'precio' => 150, 'premium' => false, 'imagen' => null, 'descripcion' => 'Mica plástica estándar, ligera y económica.', 'orden' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $mono->id, 'slug' => 'monofocal-poli', 'nombre' => 'Monofocal', 'marca' => null, 'material' => 'Policarbonato', 'indice' => '1.59', 'precio' => 350, 'premium' => false, 'imagen' => null, 'descripcion' => 'Más delgada y resistente a impactos que el CR-39.', 'orden' => 2, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ---- BIFOCAL ----
        $bi = LensType::create([
            'slug' => 'bifocal',
            'nombre' => 'Bifocal',
            'resumen' => 'Dos graduaciones en una mica, con línea visible.',
            'descripcion' => 'La mica bifocal combina visión de lejos y de cerca separadas por una línea. Buena opción para presbicia con presupuesto ajustado.',
            'icono' => 'fa-solid fa-circle-half-stroke',
            'orden' => 2,
        ]);
        LensDesign::insert([
            ['lens_type_id' => $bi->id, 'slug' => 'bifocal-ftop-cr39', 'nombre' => 'Flat Top', 'marca' => null, 'material' => 'CR-39', 'indice' => '1.50', 'precio' => 350, 'premium' => false, 'imagen' => null, 'descripcion' => 'Bifocal clásico con segmento de lectura visible.', 'orden' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $bi->id, 'slug' => 'bifocal-younger', 'nombre' => 'Younger', 'marca' => 'Younger', 'material' => 'CR-39', 'indice' => '1.50', 'precio' => 550, 'premium' => false, 'imagen' => null, 'descripcion' => 'Bifocal de segmento mezclado, línea menos marcada.', 'orden' => 2, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ---- PROGRESIVA ----
        $pro = LensType::create([
            'slug' => 'progresiva',
            'nombre' => 'Progresiva',
            'resumen' => 'Lejos, intermedio y cerca sin líneas visibles.',
            'descripcion' => 'La mica progresiva ofrece una transición continua entre todas las distancias, sin líneas. Los diseños premium Varilux amplían las zonas de visión nítida y reducen las zonas difusas.',
            'icono' => 'fa-solid fa-layer-group',
            'orden' => 3,
        ]);
        LensDesign::insert([
            // CR-39
            ['lens_type_id' => $pro->id, 'slug' => 'prog-convencional-cr39', 'nombre' => 'Convencional', 'marca' => null, 'material' => 'CR-39', 'indice' => '1.50', 'precio' => 850, 'premium' => false, 'imagen' => null, 'descripcion' => 'Progresiva de diseño estándar en plástico CR-39.', 'orden' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $pro->id, 'slug' => 'prog-retilens-cr39', 'nombre' => 'Retilens', 'marca' => 'Retilens', 'material' => 'CR-39', 'indice' => '1.50', 'precio' => 2100, 'premium' => true, 'imagen' => 'img/saphir-retilens.jpg', 'descripcion' => 'Diseño digital de alto rendimiento con amplio campo de visión.', 'orden' => 2, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $pro->id, 'slug' => 'prog-varilux-comfort-cr39', 'nombre' => 'Varilux Comfort', 'marca' => 'Varilux', 'material' => 'CR-39', 'indice' => '1.50', 'precio' => 2350, 'premium' => true, 'imagen' => 'img/varilux-comfort.jpg', 'descripcion' => 'El progresivo más recetado del mundo: transiciones suaves y visión estable.', 'orden' => 3, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $pro->id, 'slug' => 'prog-varilux-liberty-cr39', 'nombre' => 'Varilux Liberty 360°', 'marca' => 'Varilux', 'material' => 'CR-39', 'indice' => '1.50', 'precio' => 2650, 'premium' => true, 'imagen' => 'img/varilux-liberty-360.jpg', 'descripcion' => 'Zonas de visión lejana, intermedia y cercana bien definidas.', 'orden' => 4, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $pro->id, 'slug' => 'prog-varilux-physio-cr39', 'nombre' => 'Varilux Physio 3.0', 'marca' => 'Varilux', 'material' => 'CR-39', 'indice' => '1.50', 'precio' => 2950, 'premium' => true, 'imagen' => 'img/varilux-physio.jpg', 'descripcion' => 'Máxima nitidez y contraste, incluso en condiciones de poca luz.', 'orden' => 5, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            // Policarbonato
            ['lens_type_id' => $pro->id, 'slug' => 'prog-convencional-poli', 'nombre' => 'Convencional', 'marca' => null, 'material' => 'Policarbonato', 'indice' => '1.59', 'precio' => 1250, 'premium' => false, 'imagen' => null, 'descripcion' => 'Progresiva estándar en policarbonato, más delgada y resistente.', 'orden' => 6, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $pro->id, 'slug' => 'prog-varilux-comfort-poli', 'nombre' => 'Varilux Comfort', 'marca' => 'Varilux', 'material' => 'Policarbonato', 'indice' => '1.59', 'precio' => 2950, 'premium' => true, 'imagen' => 'img/varilux-comfort.jpg', 'descripcion' => 'Varilux Comfort en policarbonato: ligereza y resistencia.', 'orden' => 7, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $pro->id, 'slug' => 'prog-varilux-liberty-poli', 'nombre' => 'Varilux Liberty 360°', 'marca' => 'Varilux', 'material' => 'Policarbonato', 'indice' => '1.59', 'precio' => 3250, 'premium' => true, 'imagen' => 'img/varilux-liberty-360.jpg', 'descripcion' => 'Varilux Liberty en policarbonato para mayor comodidad.', 'orden' => 8, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['lens_type_id' => $pro->id, 'slug' => 'prog-varilux-physio-poli', 'nombre' => 'Varilux Physio 3.0', 'marca' => 'Varilux', 'material' => 'Policarbonato', 'indice' => '1.59', 'precio' => 3650, 'premium' => true, 'imagen' => 'img/varilux-physio.jpg', 'descripcion' => 'Lo mejor de Varilux Physio en material ultrarresistente.', 'orden' => 9, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    private function seedTreatments(): void
    {
        // Puntuaciones 0-3 basadas en la tabla comparativa de la línea Crizal
        // y en las capacidades de cada tratamiento (uv, luz_azul, reflejos, rayas, manchas, agua).
        Treatment::insert([
            ['slug' => 'ar-basico', 'nombre' => 'A.R. Básico', 'familia' => 'Antirreflejante', 'precio' => 300, 'descripcion' => 'Antirreflejante de entrada que reduce los reflejos molestos y mejora la nitidez.', 'imagen' => null, 'proteccion_uv' => 1, 'luz_azul' => 0, 'reflejos' => 2, 'rayas' => 1, 'manchas' => 1, 'agua' => 1, 'premium' => false, 'orden' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => false],
            ['slug' => 'blueray', 'nombre' => 'Blueray', 'familia' => 'Filtro luz azul', 'precio' => 450, 'descripcion' => 'Filtra la luz azul de pantallas y reduce la fatiga visual digital.', 'imagen' => null, 'proteccion_uv' => 1, 'luz_azul' => 2, 'reflejos' => 2, 'rayas' => 1, 'manchas' => 1, 'agua' => 1, 'premium' => false, 'orden' => 2, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => false],
            ['slug' => 'saphir-retilens', 'nombre' => 'Saphir Retilens', 'familia' => 'Saphir', 'precio' => 1250, 'descripcion' => '9 capas antirreflejantes con red hexagonal imperceptible. Repele grasa, polvo y agua, y ofrece 99% de protección UV.', 'imagen' => 'img/saphir-retilens.jpg', 'proteccion_uv' => 3, 'luz_azul' => 1, 'reflejos' => 3, 'rayas' => 3, 'manchas' => 2, 'agua' => 3, 'premium' => true, 'orden' => 3, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => false],
            ['slug' => 'crizal-easy', 'nombre' => 'Crizal Easy', 'familia' => 'Crizal', 'precio' => 1550, 'descripcion' => 'Protección Crizal esencial: antirreflejante, antirrayas y fácil de limpiar.', 'imagen' => 'img/crizal-comparativa.jpg', 'proteccion_uv' => 2, 'luz_azul' => 0, 'reflejos' => 2, 'rayas' => 2, 'manchas' => 1, 'agua' => 1, 'premium' => false, 'orden' => 4, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => false],
            ['slug' => 'crizal-rock', 'nombre' => 'Crizal Rock', 'familia' => 'Crizal', 'precio' => 1750, 'descripcion' => 'La resistencia a rayaduras más alta de Crizal para el uso diario más exigente.', 'imagen' => 'img/crizal-comparativa.jpg', 'proteccion_uv' => 3, 'luz_azul' => 1, 'reflejos' => 3, 'rayas' => 3, 'manchas' => 2, 'agua' => 2, 'premium' => true, 'orden' => 5, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => false],
            ['slug' => 'crizal-sapphire', 'nombre' => 'Crizal Sapphire 360°', 'familia' => 'Crizal', 'precio' => 2100, 'descripcion' => 'Reduce reflejos desde todos los ángulos para una transparencia casi total.', 'imagen' => 'img/crizal-decidete.jpg', 'proteccion_uv' => 3, 'luz_azul' => 1, 'reflejos' => 3, 'rayas' => 3, 'manchas' => 3, 'agua' => 3, 'premium' => true, 'orden' => 6, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => false],
            ['slug' => 'crizal-prevencia', 'nombre' => 'Crizal Prevencia', 'familia' => 'Crizal', 'precio' => 2100, 'descripcion' => 'Filtra la luz azul-violeta dañina y protege contra los rayos UV manteniendo la nitidez.', 'imagen' => 'img/crizal-comparativa.jpg', 'proteccion_uv' => 3, 'luz_azul' => 3, 'reflejos' => 3, 'rayas' => 3, 'manchas' => 3, 'agua' => 3, 'premium' => true, 'orden' => 7, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => false],
            ['slug' => 'fotocromatico', 'nombre' => 'Fotocromático', 'familia' => 'Fotocromático', 'precio' => 850, 'descripcion' => 'La mica se oscurece con el sol y se aclara en interiores, dos lentes en uno.', 'imagen' => null, 'proteccion_uv' => 3, 'luz_azul' => 1, 'reflejos' => 1, 'rayas' => 1, 'manchas' => 1, 'agua' => 1, 'premium' => false, 'orden' => 8, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => true],
            ['slug' => 'transition', 'nombre' => 'Transitions', 'familia' => 'Fotocromático', 'precio' => 1550, 'descripcion' => 'Tecnología Transitions: adaptación rápida a la luz y protección UV total.', 'imagen' => null, 'proteccion_uv' => 3, 'luz_azul' => 2, 'reflejos' => 2, 'rayas' => 2, 'manchas' => 2, 'agua' => 2, 'premium' => true, 'orden' => 9, 'activo' => true, 'created_at' => now(), 'updated_at' => now(), 'es_extra' => true],
        ]);
    }

    private function seedContactLenses(): void
    {
        $alcon = ContactLensBrand::create(['slug' => 'alcon', 'nombre' => 'Alcon', 'orden' => 1]);
        $jj = ContactLensBrand::create(['slug' => 'johnson-johnson', 'nombre' => 'Johnson & Johnson', 'orden' => 2]);
        $bl = ContactLensBrand::create(['slug' => 'bausch-lomb', 'nombre' => 'Bausch & Lomb', 'orden' => 3]);
        $hidro = ContactLensBrand::create(['slug' => 'hidrosoft', 'nombre' => 'Hidrosoft', 'orden' => 4]);

        ContactLens::insert([
            // Alcon
            ['imagen' => null, 'contact_lens_brand_id' => $alcon->id, 'nombre' => 'Air Optix Night & Day (6 pzs)', 'precio' => 2210, 'tipo' => 'Esférico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $alcon->id, 'nombre' => 'Air Optix Plus HydraGlyde Multifocal', 'precio' => 2110, 'tipo' => 'Multifocal', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $alcon->id, 'nombre' => 'Air Optix Colors (2 pzs)', 'precio' => 750, 'tipo' => 'Color', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $alcon->id, 'nombre' => 'Air Optix Plus HydraGlyde (6 pzs)', 'precio' => 890, 'tipo' => 'Esférico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $alcon->id, 'nombre' => 'Air Optix Plus HydraGlyde Tórico (6 pzs)', 'precio' => 1350, 'tipo' => 'Tórico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $alcon->id, 'nombre' => 'FreshLook ColorBlends (1 par)', 'precio' => 450, 'tipo' => 'Color', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $alcon->id, 'nombre' => 'FreshLook ColorBlends One Day', 'precio' => 550, 'tipo' => 'Color', 'reemplazo' => 'Diario', 'activo' => true, 'orden' => 7, 'created_at' => now(), 'updated_at' => now()],
            // Johnson & Johnson
            ['imagen' => null, 'contact_lens_brand_id' => $jj->id, 'nombre' => 'Acuvue 2 (6 pzs)', 'precio' => 520, 'tipo' => 'Esférico', 'reemplazo' => 'Quincenal', 'activo' => true, 'orden' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $jj->id, 'nombre' => 'Acuvue Oasys Esférico (6 pzs)', 'precio' => 1245, 'tipo' => 'Esférico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $jj->id, 'nombre' => 'Acuvue Oasys Tórico (6 pzs)', 'precio' => 1800, 'tipo' => 'Tórico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $jj->id, 'nombre' => 'Acuvue One Day Oasys (30 pzs)', 'precio' => 1160, 'tipo' => 'Esférico', 'reemplazo' => 'Diario', 'activo' => true, 'orden' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $jj->id, 'nombre' => 'Acuvue Vita (6 pzs)', 'precio' => 1250, 'tipo' => 'Esférico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $jj->id, 'nombre' => 'Acuvue Vita Tórico (6 pzs)', 'precio' => 1560, 'tipo' => 'Tórico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 6, 'created_at' => now(), 'updated_at' => now()],
            // Bausch & Lomb
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'Optima 38 (1 par, anual)', 'precio' => 1250, 'tipo' => 'Esférico', 'reemplazo' => 'Anual', 'activo' => true, 'orden' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'SofLens 59 Comfort (6 pzs)', 'precio' => 750, 'tipo' => 'Esférico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'SofLens 66 Tórico (6 pzs)', 'precio' => 1380, 'tipo' => 'Tórico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'Star Colors (1 par)', 'precio' => 450, 'tipo' => 'Color', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'PureVision 2 Esférico (6 pzs)', 'precio' => 1650, 'tipo' => 'Esférico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'PureVision 2 Tórico (6 pzs)', 'precio' => 2100, 'tipo' => 'Tórico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'Ultra (6 pzs)', 'precio' => 1260, 'tipo' => 'Esférico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'Ultra Tórico (6 pzs)', 'precio' => 1875, 'tipo' => 'Tórico', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'Ultra Multifocal (6 pzs)', 'precio' => 2100, 'tipo' => 'Multifocal', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'Lunare Colors (2 pzs)', 'precio' => 450, 'tipo' => 'Color', 'reemplazo' => 'Mensual', 'activo' => true, 'orden' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'Biotrue One Day (30 pzs)', 'precio' => 1170, 'tipo' => 'Esférico', 'reemplazo' => 'Diario', 'activo' => true, 'orden' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $bl->id, 'nombre' => 'Biotrue Tórico', 'precio' => 1650, 'tipo' => 'Tórico', 'reemplazo' => 'Diario', 'activo' => true, 'orden' => 12, 'created_at' => now(), 'updated_at' => now()],
            // Hidrosoft
            ['imagen' => null, 'contact_lens_brand_id' => $hidro->id, 'nombre' => 'UV Soft Anual (1 par)', 'precio' => 1590, 'tipo' => 'Esférico', 'reemplazo' => 'Anual', 'activo' => true, 'orden' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['imagen' => null, 'contact_lens_brand_id' => $hidro->id, 'nombre' => 'UV Soft Tórico Anual (1 par)', 'precio' => 4710, 'tipo' => 'Tórico', 'reemplazo' => 'Anual', 'activo' => true, 'orden' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    private function seedPackages(): void
    {
        // "Lentes completos": armazón + mica a precio cerrado.
        Package::insert([
            ['slug' => 'monofocal-cr39-blanco', 'nombre' => 'Monofocal', 'tratamiento' => 'CR-39 Blanco', 'precio' => 250, 'incluye' => 'Armazón + mica monofocal CR-39 sin tratamiento.', 'destacado' => false, 'orden' => 1, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'monofocal-ar-basico', 'nombre' => 'Monofocal', 'tratamiento' => 'A.R. Básico', 'precio' => 450, 'incluye' => 'Armazón + mica monofocal con antirreflejante básico.', 'destacado' => true, 'orden' => 2, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'monofocal-blueray', 'nombre' => 'Monofocal', 'tratamiento' => 'Blueray', 'precio' => 650, 'incluye' => 'Armazón + mica monofocal con filtro de luz azul.', 'destacado' => false, 'orden' => 3, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'monofocal-fotocromatico', 'nombre' => 'Monofocal', 'tratamiento' => 'Fotocromático', 'precio' => 850, 'incluye' => 'Armazón + mica monofocal fotocromática.', 'destacado' => false, 'orden' => 4, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],

            ['slug' => 'bifocal-cr39-blanco', 'nombre' => 'Bifocal', 'tratamiento' => 'CR-39 Blanco', 'precio' => 250, 'incluye' => 'Armazón + mica bifocal CR-39 sin tratamiento.', 'destacado' => false, 'orden' => 5, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'bifocal-ar-basico', 'nombre' => 'Bifocal', 'tratamiento' => 'A.R. Básico', 'precio' => 450, 'incluye' => 'Armazón + mica bifocal con antirreflejante básico.', 'destacado' => false, 'orden' => 6, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'bifocal-blueray', 'nombre' => 'Bifocal', 'tratamiento' => 'Blueray', 'precio' => 750, 'incluye' => 'Armazón + mica bifocal con filtro de luz azul.', 'destacado' => false, 'orden' => 7, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'bifocal-fotocromatico', 'nombre' => 'Bifocal', 'tratamiento' => 'Fotocromático', 'precio' => 1250, 'incluye' => 'Armazón + mica bifocal fotocromática.', 'destacado' => false, 'orden' => 8, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],

            ['slug' => 'progresiva-cr39-blanco', 'nombre' => 'Progresiva', 'tratamiento' => 'CR-39 Blanco', 'precio' => 650, 'incluye' => 'Armazón + mica progresiva CR-39 sin tratamiento.', 'destacado' => false, 'orden' => 9, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'progresiva-ar-basico', 'nombre' => 'Progresiva', 'tratamiento' => 'A.R. Básico', 'precio' => 850, 'incluye' => 'Armazón + mica progresiva con antirreflejante básico.', 'destacado' => true, 'orden' => 10, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'progresiva-blueray', 'nombre' => 'Progresiva', 'tratamiento' => 'Blueray', 'precio' => 1250, 'incluye' => 'Armazón + mica progresiva con filtro de luz azul.', 'destacado' => false, 'orden' => 11, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ['slug' => 'progresiva-fotocromatico', 'nombre' => 'Progresiva', 'tratamiento' => 'Fotocromático', 'precio' => 1550, 'incluye' => 'Armazón + mica progresiva fotocromática.', 'destacado' => false, 'orden' => 12, 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
