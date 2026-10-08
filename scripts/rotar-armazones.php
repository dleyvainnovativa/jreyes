<?php
/**
 * rotar-armazones.php
 * -------------------------------------------------------------------------
 * Rota 90° en sentido ANTIHORARIO las fotos de armazones que estén en
 * vertical (más altas que anchas) y las deja en horizontal.
 *
 * - Solo toca imágenes verticales; las que ya están horizontales las deja
 *   intactas.
 * - Hace un respaldo de cada original en una subcarpeta _backup/ antes de
 *   sobrescribir, así que es seguro de correr.
 * - Funciona con JPG y PNG. Usa la extensión GD de PHP (ya viene con PHP).
 *
 * USO (desde la raíz del proyecto Laravel):
 *     php scripts/rotar-armazones.php
 *
 * O apuntando a otra carpeta:
 *     php scripts/rotar-armazones.php public/img/armazones
 *
 * Opciones:
 *     --dry        Solo muestra qué haría, sin modificar nada.
 *     --no-backup  No crea respaldos (no recomendado).
 * -------------------------------------------------------------------------
 */

$args = $argv;
array_shift($args);

$dir = 'public/img/armazones';
$dry = false;
$backup = true;

foreach ($args as $a) {
    if ($a === '--dry') { $dry = true; }
    elseif ($a === '--no-backup') { $backup = false; }
    elseif (!str_starts_with($a, '--')) { $dir = rtrim($a, '/'); }
}

if (!extension_loaded('gd')) {
    fwrite(STDERR, "ERROR: la extensión GD de PHP no está activa. Actívala (php-gd) e inténtalo de nuevo.\n");
    exit(1);
}

if (!is_dir($dir)) {
    fwrite(STDERR, "ERROR: no existe la carpeta: $dir\n");
    fwrite(STDERR, "Corre el script desde la raíz del proyecto, o pásale la ruta:\n");
    fwrite(STDERR, "   php scripts/rotar-armazones.php ruta/a/armazones\n");
    exit(1);
}

$backupDir = $dir . '/_backup';
if ($backup && !$dry && !is_dir($backupDir)) {
    mkdir($backupDir, 0775, true);
}

$files = glob($dir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE) ?: [];
if (!$files) {
    echo "No se encontraron imágenes (.jpg/.png) en: $dir\n";
    exit(0);
}

$rotadas = 0;
$saltadas = 0;
$errores = 0;

foreach ($files as $file) {
    // No procesar lo que esté dentro del respaldo.
    if (str_contains($file, '/_backup/')) continue;

    $info = @getimagesize($file);
    if (!$info) {
        echo "  [ERROR] no es imagen válida: " . basename($file) . "\n";
        $errores++;
        continue;
    }

    [$w, $h, $type] = $info;

    if ($h <= $w) {
        // Ya es horizontal (o cuadrada): no se toca.
        $saltadas++;
        continue;
    }

    echo ($dry ? "  [DRY] " : "  [ROTAR] ") . basename($file) . "  {$w}x{$h} -> {$h}x{$w}\n";
    if ($dry) { $rotadas++; continue; }

    // Cargar según tipo.
    switch ($type) {
        case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($file); break;
        case IMAGETYPE_PNG:  $src = @imagecreatefrompng($file);  break;
        default:
            echo "  [SALTO] formato no soportado: " . basename($file) . "\n";
            $saltadas++;
            continue 2;
    }
    if (!$src) {
        echo "  [ERROR] no se pudo abrir: " . basename($file) . "\n";
        $errores++;
        continue;
    }

    // Respaldo del original.
    if ($backup) {
        copy($file, $backupDir . '/' . basename($file));
    }

    // Conservar transparencia en PNG.
    if ($type === IMAGETYPE_PNG) {
        imagealphablending($src, false);
        imagesavealpha($src, true);
    }

    // 90° antihorario. En GD, imagerotate gira en sentido ANTIHORARIO
    // con ángulos positivos, así que 90 = antihorario.
    $dst = imagerotate($src, 90, 0);

    if ($type === IMAGETYPE_PNG) {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
    }

    $ok = $type === IMAGETYPE_JPEG
        ? imagejpeg($dst, $file, 90)
        : imagepng($dst, $file);

    imagedestroy($src);
    imagedestroy($dst);

    if ($ok) { $rotadas++; }
    else { echo "  [ERROR] no se pudo guardar: " . basename($file) . "\n"; $errores++; }
}

echo "\n" . str_repeat('-', 50) . "\n";
echo ($dry ? "SIMULACRO (--dry): " : "Listo: ");
echo "{$rotadas} rotadas, {$saltadas} ya horizontales (sin tocar), {$errores} errores.\n";
if ($backup && !$dry && $rotadas > 0) {
    echo "Respaldo de los originales en: {$backupDir}/\n";
}
