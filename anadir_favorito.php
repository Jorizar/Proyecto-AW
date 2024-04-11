<?php
require_once __DIR__.'/../../config.php'; // Ajusta la ruta según sea necesario
require_once __DIR__.'/../../src/favoritos/Favorito.php'; // Ajusta la ruta según sea necesario

if ($app->usuarioLogueado() && isset($_POST['movieId'])) {
    $userId = $app->getUsuarioId();
    $movieId = $_POST['movieId'];

    // Comprobar si la película ya está en favoritos
    if (!\es\ucm\fdi\aw\favoritos\Favorito::existe($userId, $movieId)) {
        // Añadir la película a favoritos
        \es\ucm\fdi\aw\favoritos\Favorito::crea($userId, $movieId);
    }
}

// Redirigir de vuelta a la página anterior o a la página de inicio
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
