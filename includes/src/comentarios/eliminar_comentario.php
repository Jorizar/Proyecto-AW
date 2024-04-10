<?php
require_once __DIR__. '/../../config.php';
require_once __DIR__.'/Comentario.php';

// Check if the user is logged in
if (!$app->usuarioLogueado()) {
    header('Location: login.php');
    exit();
}

// Validate and sanitize input data
$comentario_id = filter_input(INPUT_POST, 'comentario_id', FILTER_SANITIZE_NUMBER_INT);

// Ensure that the required field is not empty
if (empty($comentario_id)) {
    echo "Error: El comentario no puede ser identificado.";
    exit();
}

// Perform the deletion operation
$resultado = \es\ucm\fdi\aw\comentarios\Comentario::eliminarPorId($comentario_id);

if ($resultado) {
    $relativePath = '/AW/Proyecto-AW/misComentarios.php';
    header('Location: ' . $relativePath);
    exit();
} else {
    echo "Error: No se pudo eliminar el comentario.";
    exit();
}
?>
