<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/peliculas/Pelicula.php'; // Ajusta la ruta según sea necesario
require_once __DIR__.'/includes/src/comentarios/Comentario.php'; // Importa la clase Comentario

$tituloPagina = 'Detalles de la Película';
$contenidoPrincipal='';

if (isset($_GET['id'])) {
    $movieId = $_GET['id'];
    // Suponiendo que buscaPorId devuelve un objeto de tipo Pelicula con los detalles de la película
    $movie = \es\ucm\fdi\aw\peliculas\Pelicula::buscaPorId($movieId);

    if ($movie) {
        // Ajusta estos nombres de propiedad según las propiedades reales de la clase Pelicula
        $titulo = $movie->titulo;
        $anno = $movie->annio;
        $director = $movie->director;
        $generoId = $movie->genero;
        $portada = $movie->portada;
        $repartoJson = $movie->reparto;
        $sinopsis = $movie->sinopsis;
        $valoracionIMDb = $movie->Val_IMDb;

        $genero = \es\ucm\fdi\aw\peliculas\Pelicula::convierteGenero($generoId); // Convertimos la ID del género a texto

        // Recoge los comentarios
        $comentarios = \es\ucm\fdi\aw\comentarios\Comentario::buscarPorPeliculaId($movieId);

        // Calcula la valoración de los comentarios
        $sumValoraciones = 0;
        $numeroValoraciones = count($comentarios);
    
        if ($numeroValoraciones > 0) {
            foreach ($comentarios as $comentario) {
                $sumValoraciones += $comentario->getValoracion();
            }
            $avgValoracion = $sumValoraciones / $numeroValoraciones;
            $valoracionUsuariosHtml = round($avgValoracion, 1);
        } else {
            $valoracionUsuariosHtml = "Aun no hay valoraciones";
        }

        // Reparto es un JSON así que lo desciframos para escribirlo
        $repartoData = json_decode($repartoJson);
        $repartoHtml = "";
        foreach ($repartoData as $obj) {
            $repartoHtml .= htmlspecialchars($obj->nombre) . " como " . htmlspecialchars($obj->personaje) . "<br>";
        }
        
        $contenidoPrincipal = <<<EOS
        <div class="info_pelicula">
            <h2>$titulo ($anno)</h2>
            <form action="includes/src/favoritos/procesar_favorito.php" method="post">
                <input type="hidden" name="movieId" value="$movieId">
                <button type="submit" class="btn btn-primary">Añadir a favoritos</button>
            </form>
            <div class="portada_detalles_pelicula">
                <img src="$portada" alt="Portada de $titulo" class="movie-poster">
                <div class="detalles-pelicula">
                    <p><strong>Director:</strong> $director</p>
                    <p><strong>Género:</strong> $genero</p>
                    <p><strong>Valoración IMDb:</strong> $valoracionIMDb</p>
                    <p><strong>Valoración 7thArt:</strong> $valoracionUsuariosHtml</p>
                    <p><strong>Reparto:</strong><br>$repartoHtml</p>
                    <p><strong>Sinopsis:</strong> $sinopsis</p>
                </div>
            </div>
        </div>
        EOS;
        
    } else {
        $contenidoPrincipal = '<h1>Película no encontrada</h1>';
    }

    // Muestra los comentarios
    $numComentarios = count($comentarios);
    $comentariosHtml = '<h3>Comentarios (' . $numComentarios . ')</h3>';
    foreach ($comentarios as $comentario) {
        $textoComentario = htmlspecialchars($comentario->getTexto());
        $valoracionComentario = htmlspecialchars($comentario->getValoracion());
        $UserId = htmlspecialchars($comentario->getUserId());
        $UserNombre = \es\ucm\fdi\aw\usuarios\Usuario::buscaNombrePorId($UserId);
    
        $comentariosHtml .= "<div class='comentario'>
            <p><strong>$UserNombre</strong> dijo:</p>
            <p>$textoComentario</p>
            <p>Valoración: $valoracionComentario</p>
        </div>";
    }
    $contenidoPrincipal .= $comentariosHtml;

    // Revisa si el usuario está logueado para mostrarle la sección añadir comentario
    if ($app->usuarioLogueado()) {
        $contenidoPrincipal .= <<<EOF
        <h3>Añadir un comentario</h3>
        <form action="includes/src/comentarios/procesar_comentario.php" method="post"> <!-- Ajusta la URL de acción según sea necesario -->
            <input type="hidden" name="pelicula_id" value="$movieId">
            <textarea name="texto" required></textarea>
            <select name="valoracion" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
            <button type="submit">Enviar comentario</button>
        </form>
        EOF;
    }

} else {
    $contenidoPrincipal = '<h1>ID de película no especificado</h1>';
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
