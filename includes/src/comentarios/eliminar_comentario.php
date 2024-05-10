<?php
require_once __DIR__. '/../../config.php';

use es\ucm\fdi\aw\comentarios\Comentario;

// Comprobamos si el usuario está logueado
if (!$app->usuarioLogueado()) {
    header('Location: login.php');
    exit();
}

//Validamos el id introducido por el usuario
$comentario_id = filter_input(INPUT_POST, 'comentario_id', FILTER_SANITIZE_NUMBER_INT);


if (empty($comentario_id)) {
    echo "Error: El comentario no puede ser identificado.";
    exit();
}

//Eliminamos el comentario
$resultado = Comentario::eliminarPorId($comentario_id);

if ($resultado) {
    if (!$app->tieneRol('admin')) {
        $relativePath = '/misComentarios.php';
        header('Location: ' . $relativePath);
        exit();
    }
    else{
        $relativePath = '/admin_comentarios.php';
        header('Location: ' . $relativePath);
        exit();
    }
   
} else {
    echo "Error: No se pudo eliminar el comentario.";
    exit();
}
?>
