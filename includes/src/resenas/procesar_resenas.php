<?php
require_once __DIR__. '/../../config.php';

use es\ucm\fdi\aw\resenas\Resena;

if (!$app->usuarioLogueado()) {
    header('Location: login.php'); 
    exit();
}


$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_SANITIZE_NUMBER_INT);
$texto = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_SPECIAL_CHARS);
$valoracion = filter_input(INPUT_POST, 'valoracion', FILTER_SANITIZE_NUMBER_INT);

if ($pelicula_id === null || $texto === '' || $valoracion === null) {
    echo "Error: Todos los campos son obligatorios.";
    exit();
}

$user_id = $app->getUsuarioId();


$resena = Resena::crea($user_id, $pelicula_id, $texto, $valoracion);

if ($resena) {
    $relativePath = '/ver_resenas.php?id=' . urlencode($pelicula_id);
    header('Location: ' . $relativePath);
    exit();
} else {
    echo "Error: No se pudo guardar la reseña.";
    exit();
}

?>
