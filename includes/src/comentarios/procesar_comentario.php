<?php
require_once __DIR__. '/../../config.php';

use es\ucm\fdi\aw\comentarios\Comentario;

// Comprobamos si el usuario se ha logueado
if (!$app->usuarioLogueado()) {
    //Redirigimos al usuario al login
    header('Location: ./../../../login.php'); 
    exit();
}

$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_SANITIZE_NUMBER_INT);
$texto = filter_input(INPUT_POST, 'texto', FILTER_SANITIZE_SPECIAL_CHARS);
$valoracion = filter_input(INPUT_POST, 'valoracion', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1, 'max_range' => 10]
]);

$user_id = $app->getUsuarioId();

if (!$pelicula_id || !$texto || $valoracion === false) {
    http_response_code(400);
    echo json_encode(['error' => 'Todos los campos son obligatorios y deben ser válidos.']);
    exit;
}

$usuarioYaComento = Comentario::usuarioYaComentoPelicula($user_id, $pelicula_id);

if ($usuarioYaComento) {
    http_response_code(400);
    echo json_encode(['error' => 'Ya has dejado un comentario en esta película.']);
    exit;
}

$comentario = Comentario::crea($user_id, $pelicula_id, $texto, $valoracion, 0);


if ($comentario) {
    echo json_encode(['success' => 'Comentario agregado con éxito.']);
} else {

    http_response_code(500);
    echo json_encode(['error' => 'Error al guardar el comentario.']);

}
exit;
?>
