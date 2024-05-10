<?php
require_once __DIR__. '/../../config.php';
require_once __DIR__.'/Usuario.php';

use es\ucm\fdi\aw\usuarios\Usuario;

if (!$app->tieneRol('admin')) {
    echo "Acceso restringido solo a administradores.";
    exit();
}

$usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_SANITIZE_NUMBER_INT);
if (empty($usuario_id)) {
    echo "Error: El usuario no puede ser identificado.";
    exit();
}

$resultado = Usuario::borraPorId($usuario_id);

if ($resultado) {
    $relativePath = '/admin_usuarios.php';
    header('Location: ' . $relativePath);
    exit();
} else {
    echo "Error: No se pudo eliminar el usuario.";
    exit();
}
?>
