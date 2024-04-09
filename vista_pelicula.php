<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/peliculas/Pelicula.php'; // Adjust the path as necessary

$tituloPagina = 'Detalles de la Película';
$contenidoPrincipal='';

if (isset($_GET['id'])) {
    $movieId = $_GET['id'];
    // Assuming buscaPorId returns an object of type Pelicula with movie details
    $movie = \es\ucm\fdi\aw\peliculas\Pelicula::buscaPorId($movieId);

    if ($movie) {
        // Adjust these property names based on the actual properties of the Pelicula class
        $titulo = $movie->titulo;
        $anno = $movie->annio;
        $director = $movie->director;
        $genero = $movie->genero;
        $portada = $movie->portada;
        $reparto = $movie->reparto;
        $sinopsis = $movie->sinopsis;
        $valoracionIMDb = $movie->Val_IMDb;

        $contenidoPrincipal=<<<EOS
        <h2>$titulo ($anno)</h2>
        <div style="display: flex; justify-content: start; align-items: center; margin-bottom: 20px;">
            <img src="$portada" alt="Portada de $titulo" width="200">
            <div style="margin-left: 20px;">
                <p><strong>Director:</strong> $director</p>
                <p><strong>Género:</strong> $genero</p>
                <p><strong>Valoración IMDb:</strong> $valoracionIMDb</p>
                <p><strong>Reparto:</strong> $reparto</p>
                <p><strong>Sinopsis:</strong> $sinopsis</p>
            </div>
        </div>
EOS;
    } else {
        $contenidoPrincipal = '<h1>Película no encontrada</h1>';
    }
} else {
    $contenidoPrincipal = '<h1>ID de película no especificado</h1>';
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
