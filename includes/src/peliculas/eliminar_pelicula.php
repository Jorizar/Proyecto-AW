<?php
require_once __DIR__. '/../../config.php';
require_once __DIR__.'/Pelicula.php';

use es\ucm\fdi\aw\peliculas\Pelicula;

if (!$app->tieneRol('admin')) {
    echo "Acceso restringido solo a administradores.";
    exit();
}

$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_SANITIZE_NUMBER_INT);

if (empty($pelicula_id)) {
    echo "Error: La película no puede ser identificada.";
    exit();
}

$resultado = Pelicula::borraPorId($pelicula_id);

if ($resultado) {
    $relativePath = '/admin_peliculas.php';
    header('Location: ' . $relativePath);
    exit();
} else {
    echo "Error: No se pudo eliminar la película.";
    exit();
}
?>
