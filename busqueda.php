<?php
// Se incluye el archivo de configuración
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\peliculas\Pelicula;


// Título de la página
$tituloPagina = 'Búsqueda';

// Contenido principal de la página
$contenidoPrincipal = '';

//Obtenemos el id de las peliculas buscadas
$peliculasId = $_SESSION['busquedaPeliculas'];

if(count($peliculasId) > 0){

    //Realizamos una consulta para cargar todos los datos de las películas
    $peliculas = array();
    foreach($peliculasId as $peliculaId){
        $peliculas[] = Pelicula::buscaPorId($peliculaId);
    }
    //Mostramos las películas de la búsqueda
    $contenidoPrincipal .= '<div class="busqueda-peliculas-container">';
    foreach ($peliculas as $pelicula) {
        $id = $pelicula->getId();
        $titulo = $pelicula->getTitulo();
        $imagen = $pelicula->getPortada();
        // Enlace por cada película que redirige a la vista de la película
        $contenidoPrincipal .= <<<HTML
            <div class="pelicula">
                <a href="vista_pelicula.php?id=$id">
                    <img src="$imagen" alt="$titulo">
                    <span>$titulo</span>
                </a>
            </div>
HTML;
    }
    $contenidoPrincipal .= '</div>';
} else {
    $contenidoPrincipal .= "<p>No se encontraron películas con los criterios establecidos.</p>";
}

// Parámetros para generar la vista final
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

// Se genera la vista utilizando la plantilla
$app->generaVista('/plantillas/plantilla.php', $params);
?>