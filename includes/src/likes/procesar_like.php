<?php
require_once __DIR__.'/../../config.php';
use es\ucm\fdi\aw\likes\Like;

$user_id = $app->getUsuarioId();  // Assuming you have a method to get the current user ID
$comentario_id = $_POST['comentario_id'];
$pelicula_id = $_POST['pelicula_id'];
$action = $_POST['action'];

if ($action == 'like') {
    Like::crea($user_id, $comentario_id);
} elseif ($action == 'undo') {
    Like::elimina($user_id, $comentario_id);
}

$relativePath = '/AW/Proyecto-AW/vista_pelicula.php?id=' . urlencode($pelicula_id);
header('Location: ' . $relativePath);
exit;
?>
