<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\peliculas\Pelicula;


if (!$app->tieneRol('admin')) {
    die("Acceso restringido a administradores.");
}

$tituloPagina = 'Administrar Películas';
$contenidoPrincipal = '<h3>Todas las Películas</h3>';

$peliculas = Pelicula::buscarTodas();

if (!empty($peliculas)) {
    foreach ($peliculas as $pelicula) {
        $deleteForm = "<form method='POST' action='includes/src/peliculas/eliminar_pelicula.php' onsubmit='return confirm(\"¿Estás seguro de que quieres eliminar esta película?\");'>
                            <input type='hidden' name='pelicula_id' value='{$pelicula['id']}'>
                            <input type='submit' value='Eliminar'>
                       </form>";
        
        $contenidoPrincipal .= "<div class='pelicula_admin'>
                                <p>ID: {$pelicula['id']} - Título: {$pelicula['titulo']}</p>
                                $deleteForm
                             </div>";
    }
} else {
    $contenidoPrincipal .= "<p>No hay películas disponibles.</p>";
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
