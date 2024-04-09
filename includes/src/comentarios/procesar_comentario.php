<?php
require_once __DIR__. '/../../config.php';
require_once __DIR__.'/Comentario.php'; // Import the Comentario class

// Check if the user is logged in
if (!$app->usuarioLogueado()) {
    // Redirect the user to the login page or show an error message
    header('Location: login.php'); // Adjust the path as necessary
    exit();
}

// Validate and sanitize input data
$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_SANITIZE_NUMBER_INT);
$texto = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_SPECIAL_CHARS);
$valoracion = filter_input(INPUT_POST, 'valoracion', FILTER_SANITIZE_NUMBER_INT);

// Ensure that the required fields are not empty
if (empty($pelicula_id) || empty($texto) || empty($valoracion)) {
    // Handle the error appropriately
    echo "Error: Todos los campos son obligatorios.";
    exit();
}

// Assuming you have a method to get the currently logged-in user's ID
$user_id = $app->getUsuarioId(); // This method needs to be implemented in your application

// Create and save the new comment
$comentario = \es\ucm\fdi\aw\comentarios\Comentario::crea($user_id, $pelicula_id, $texto, $valoracion);

if ($comentario) {
    $relativePath = '/AW/Proyecto-AW/vista_pelicula.php?id=' . urlencode($pelicula_id);
    header('Location: ' . $relativePath);
    exit();
} else {
    // Handle the error appropriately
    echo "Error: No se pudo guardar el comentario.";
    exit();
}

?>
