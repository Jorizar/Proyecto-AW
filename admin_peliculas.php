<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\peliculas\Pelicula;
use es\ucm\fdi\aw\peliculas\FormularioAgregaPel;


if (!$app->tieneRol('admin')) {
    die("Acceso restringido a administradores.");
}

$tituloPagina = 'Administrar Películas';


// Formulario de búsqueda para el buscador
$ADDForm = new FormularioAgregaPel();
$ADDForm = $ADDForm->gestiona();

// Agregar el formulario de agregar películas encima de la lista de películas disponibles

// Contenido de la página
$contenidoPrincipal = '';
$contenidoPrincipal .= "<h3>Añadir Nueva Película</h3>";
$contenidoPrincipal .= "<div class= 'añadirPel'>
                            $ADDForm
                        </div>";


$contenidoPrincipal .= '<h3>Todas las Películas</h3>';

$peliculas = Pelicula::buscarTodas();

if (!empty($peliculas)) {

    foreach ($peliculas as $pelicula) {
        $editForm = "<form method='POST' action='./admin_editaPeli.php''>
                            <input type='hidden' name='id_peli' value='{$pelicula['id']}'>
                            <input type='submit' value='Editar'>
                        </form>";

        $deleteForm = "<form method='POST' action='includes/src/peliculas/eliminar_pelicula.php' onsubmit='return confirm(\"¿Estás seguro de que quieres eliminar esta película?\");'>
                            <input type='hidden' name='pelicula_id' value='{$pelicula['id']}'>
                            <input type='submit' value='Eliminar'>
                       </form>";
        
        $contenidoPrincipal .= "<div class='pelicula_admin'>
                                <p>ID: {$pelicula['id']} - Título: {$pelicula['titulo']}</p>
                                $editForm
                                $deleteForm                                
                             </div>";
    }
} else {
    $contenidoPrincipal .= "<p>No hay películas disponibles.</p>";
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
