<?php
require_once __DIR__.'/../../config.php';
require_once __DIR__.'/Favoritos.php';

if ($app->usuarioLogueado()) { // Verificar si el usuario está autenticado
    if (isset($_POST['movieId'])) {
        $userId = $app->getUsuarioId(); // Obtener el ID del usuario autenticado
        $pelicula_id = $_POST['movieId']; // Obtener el ID de la película

        $resultado = \es\ucm\fdi\aw\favoritos\Favorito::crea($userId, $pelicula_id);

        if ($resultado) {
            $relativePath = '/AW/Proyecto-AW/vista_pelicula.php?id=' . urlencode($pelicula_id);
            header('Location: ' . $relativePath);
            exit();
        } else {
            echo "Error: No se pudo añadir a favoritos.";
            exit();
        }
    } else {
        // Si no se proporciona el ID de la película, mostrar un mensaje de error
        echo "ID de película no especificado.";
    }
} else {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    $relativePath = '/AW/Proyecto-AW/login.php';
    header('Location: ' . $relativePath);
    exit();
}
?>
