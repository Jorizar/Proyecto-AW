<?php
require_once __DIR__ . "/includes/config.php";
use es\ucm\fdi\aw\peliculas\Pelicula;

// Recolecta datos del POST
$titulo = $_POST["tituloPelicula"] ?? "";
$director = $_POST["directorPelicula"] ?? "";
$genero = $_POST["generoPelicula"] ?? "";
$genero = $genero == "-1" ? "" : $genero;
$annio = $_POST["annioPelicula"] ?? "";
if (!(empty($titulo) && empty($director) && empty($annio))) {
    $peliculasId = Pelicula::buscaPelicula($titulo, $director, $genero, $annio);

    $contenidoPrincipal = "";
    if (count($peliculasId) > 0) {
        //Realizamos una consulta para cargar todos los datos de las películas
        $peliculas = [];
        foreach ($peliculasId as $peliculaId) {
            $peliculas[] = Pelicula::buscaPorId($peliculaId);
        }
        //Mostramos las películas de la búsqueda
        $contenidoPrincipal .= '<h1>Coincidencias</h1>';
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
        $contenidoPrincipal .=
            "<p>No se encontraron películas con los criterios establecidos.</p>";
    }
    echo $contenidoPrincipal;
}
?>
