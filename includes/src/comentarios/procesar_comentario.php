<?php
require_once __DIR__. '/../../config.php';

use es\ucm\fdi\aw\comentarios\Comentario;

// Comprobamos si el usuario se ha logueado
if (!$app->usuarioLogueado()) {
    //Redirigimos al usuario al login
    header('Location: login.php'); 
    exit();
}

// Validamos la entrada 
$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_SANITIZE_NUMBER_INT);
$texto = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_SPECIAL_CHARS);
$valoracion = filter_input(INPUT_POST, 'valoracion', FILTER_SANITIZE_NUMBER_INT);

if (empty($pelicula_id) || empty($texto) || empty($valoracion)) {
    echo "Error: Todos los campos son obligatorios.";
    exit();
}

$user_id = $app->getUsuarioId();

//Creamos el nuevo comentario
$comentario = Comentario::crea($user_id, $pelicula_id, $texto, $valoracion);

if ($comentario) {
    $relativePath = '/AW/Proyecto-AW/vista_pelicula.php?id=' . urlencode($pelicula_id);
    header('Location: ' . $relativePath);
    exit();
} else {
    echo "Error: No se pudo guardar el comentario.";
    exit();
}

?>
