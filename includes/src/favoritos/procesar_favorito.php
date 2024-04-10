<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/Favoritos.php';

if ($app->usuarioLogueado()) { // Verificar si el usuario está autenticado
    if (isset($_POST['pelicula_id'])) {
        $userId = $app->idUsuario(); // Obtener el ID del usuario autenticado
        $pelicula_idId = $_POST['pelicula_id']; // Obtener el ID de la película

        $resultado = \es\ucm\fdi\aw\peliculas\Favorito::crea($userId, $pelicula_idId);

        if ($resultado) {
            // La película se añadió a favoritos
            echo "Película añadida a favoritos.";
        } else {
            // Ocurrio un error al añadir la película a favoritos
            echo "Error al añadir la película a favoritos.";
        }
    } else {
        // Si no se proporciona el ID de la película, mostrar un mensaje de error
        echo "ID de película no especificado.";
    }
} else {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header('Location: /login.php');
    exit();
}
?>