<?php
require_once __DIR__.'/../../config.php';
use es\ucm\fdi\aw\likes\Like;

// Comprobamos si el usuario se ha logueado
if (!$app->usuarioLogueado()) {
    //Redirigimos al usuario al login
    header('Location: ./../../../login.php'); 
    exit();
}

$user_id = $app->getUsuarioId();  
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$comentario_id = filter_input(INPUT_POST, 'comentario_id', FILTER_VALIDATE_INT);
$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_VALIDATE_INT);

if (!$comentario_id || !$pelicula_id || !in_array($action, ['like', 'undo'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

if ($action == 'like') {
    $result = Like::crea($user_id, $comentario_id);

} else{
    $result = Like::elimina($user_id, $comentario_id);
}

if ($result) {
    echo json_encode(['success' => true, 'actionPerformed' => $action]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al procesar la acción']);
}
exit;

?>
