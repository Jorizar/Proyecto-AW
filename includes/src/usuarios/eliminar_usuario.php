<?php
require_once __DIR__. '/../../config.php';
require_once __DIR__.'/Usuario.php';

use es\ucm\fdi\aw\usuarios\Usuario;

if (!$app->tieneRol('admin')) {
    echo "Acceso restringido solo a administradores.";
    exit();
}

// Sanitize and validate user ID from POST data
$usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_SANITIZE_NUMBER_INT);

// Ensure a user ID was provided
if (empty($usuario_id)) {
    echo "Error: El usuario no puede ser identificado.";
    exit();
}

// Attempt to delete the user by ID
$resultado = Usuario::borraPorId($usuario_id);

if ($resultado) {
    // Redirect to the admin users page on success
    $relativePath = '/AW/Proyecto-AW/admin_usuarios.php';
    header('Location: ' . $relativePath);
    exit();
} else {
    // Display error message if deletion fails
    echo "Error: No se pudo eliminar el usuario.";
    exit();
}
?>
