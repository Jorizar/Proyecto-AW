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
        $generoId = $movie->genero;
        $portada = $movie->portada;
        $repartoJson = $movie->reparto;
        $sinopsis = $movie->sinopsis;
        $valoracionIMDb = $movie->Val_IMDb;

        $genero = \es\ucm\fdi\aw\peliculas\Pelicula::convierteGenero($generoId); // Convertimos la id del genero a texto

        // Reparto es un json así que lo desciframos para escribirlo
        $repartoData = json_decode($repartoJson);
        $repartoHtml = "";
        foreach ($repartoData as $obj) {
            $repartoHtml .= htmlspecialchars($obj->nombre) . " como " . htmlspecialchars($obj->personaje) . "<br>";
        }
        
        $contenidoPrincipal = <<<EOS
        <div style="display: flex; align-items: center; justify-content: start; margin-bottom: 20px;">
            <h2 style="margin-right: 20px;">$titulo ($anno)</h2>
            <form action="add_favoritos.php" method="post" style="margin-top: 0;">
                <input type="hidden" name="movieId" value="$movieId">
                <button type="submit" class="btn btn-primary">Añadir a favoritos</button>
            </form>
        </div>
        <div style="display: flex; justify-content: start; align-items: center; margin-bottom: 20px;">
            <img src="$portada" alt="Portada de $titulo" width="200">
            <div style="margin-left: 20px;">
                <p><strong>Director:</strong> $director</p>
                <p><strong>Género:</strong> $genero</p>
                <p><strong>Valoración IMDb:</strong> $valoracionIMDb</p>
                <p><strong>Reparto:</strong><br>$repartoHtml</p>
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
