<?php
require_once __DIR__. '/../../config.php';

use es\ucm\fdi\aw\resenas\Resena;

// Check if the user is logged in
if (!$app->usuarioLogueado()) {
    // Redirect to the login page
    header('Location: login.php'); 
    exit();
}

// Validate the input
$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_SANITIZE_NUMBER_INT);
$texto = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_SPECIAL_CHARS);
$valoracion = filter_input(INPUT_POST, 'valoracion', FILTER_SANITIZE_NUMBER_INT);

if ($pelicula_id === null || $texto === '' || $valoracion === null) {
    echo "Error: Todos los campos son obligatorios.";
    exit();
}

$user_id = $app->getUsuarioId();

// Create the new review
$resena = Resena::crea($user_id, $pelicula_id, $texto, $valoracion);

if ($resena) {
    $relativePath = '/AW/Proyecto-AW/ver_resenas.php?id=' . urlencode($pelicula_id);
    header('Location: ' . $relativePath);
    exit();
} else {
    echo "Error: No se pudo guardar la reseña.";
    exit();
}

?>
