<?php
require_once __DIR__. '/../../config.php';


use es\ucm\fdi\aw\noticias\Noticia;

if (!$app->tieneRol('admin')) {
    echo "Acceso restringido solo a administradores.";
    exit();
}

$id = filter_input(INPUT_POST, 'noticia_id', FILTER_SANITIZE_NUMBER_INT);

if (empty($id)) {
    echo "Error: La noticia no puede ser identificada.";
    exit();
}

$resultado = Noticia::borraPorId($id);

if ($resultado) {
    $relativePath = '/admin_noticias.php';
    header('Location: ' . $relativePath);
    exit();
} else {
    echo "Error: No se pudo eliminar la noticia.";
    exit();
}
?>
