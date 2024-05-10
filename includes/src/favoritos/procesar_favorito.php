<?php
require_once __DIR__.'/../../config.php';

use es\ucm\fdi\aw\favoritos\Favorito;


if ($app->usuarioLogueado()) { // Verificar si el usuario está autenticado
    if (isset($_POST['movieId'])) {
        $userId = $app->getUsuarioId(); // Obtener el ID del usuario autenticado
        $pelicula_id = $_POST['movieId']; // Obtener el ID de la película

        $resultado = Favorito::crea($userId, $pelicula_id);

        if ($resultado) {
            $relativePath = '/vista_pelicula.php?id=' . urlencode($pelicula_id);
            header('Location: ' . $relativePath);
            exit();
        } else {
            echo "Error: No se pudo añadir a favoritos.";
            exit();
        }
    } elseif (isset($_POST['eliminarMovieId'])) {
        $userId = $app->getUsuarioId(); // Obtener el ID del usuario autenticado
        $pelicula_id = $_POST['eliminarMovieId']; // Obtener el ID de la película a eliminar de favoritos

        $resultado = Favorito::eliminaPorIdUsuarioYIdPelicula($userId, $pelicula_id);

        if ($resultado) {
            $relativePath = '/vista_pelicula.php?id=' . urlencode($pelicula_id);
            header('Location: ' . $relativePath);
            exit();
        } else {
            echo "Error: No se pudo eliminar de favoritos.";
            exit();
        }
    } else {
        // Si no se proporciona el ID de la película, mostrar un mensaje de error
        echo "ID de película no especificado.";
    }
} else {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    $relativePath = './../../../login.php';
    header('Location: ' . $relativePath);
    exit();
}
?>
