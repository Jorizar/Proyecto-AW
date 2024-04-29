<?php
<<<<<<< Updated upstream
require_once __DIR__. '/../../config.php';
require_once __DIR__.'/Comentario.php';

// Check if the user is logged in
if (!$app->usuarioLogueado()) {
    // Redirect the user to the login page or show an error message
    header('Location: login.php'); // Adjust the path as necessary
    exit();
}

// Validate and sanitize input data
=======
require_once __DIR__ . '/../../config.php';
use es\ucm\fdi\aw\comentarios\Comentario;

header('Content-Type: application/json');

if (!$app->usuarioLogueado()) {
    http_response_code(401);
    echo json_encode(['error' => 'Acceso denegado. Usuario no logueado.']);
    exit;
}

>>>>>>> Stashed changes
$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_SANITIZE_NUMBER_INT);
$texto = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_SPECIAL_CHARS);
$valoracion = filter_input(INPUT_POST, 'valoracion', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 10]
]);

<<<<<<< Updated upstream
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
=======
if (!$pelicula_id || !$texto || $valoracion === false) {
    http_response_code(400);
    echo json_encode(['error' => 'Todos los campos son obligatorios y deben ser válidos.']);
    exit;
}

$comentario = Comentario::crea($app->getUsuarioId(), $pelicula_id, $texto, $valoracion, 0);
>>>>>>> Stashed changes

if ($comentario) {
    echo json_encode(['success' => 'Comentario agregado con éxito.']);
} else {
<<<<<<< Updated upstream
    // Handle the error appropriately
    echo "Error: No se pudo guardar el comentario.";
    exit();
=======
    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar el comentario.']);
>>>>>>> Stashed changes
}
exit;
?>
