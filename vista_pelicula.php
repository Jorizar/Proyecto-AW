<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/peliculas/Pelicula.php'; // Adjust the path as necessary
require_once __DIR__.'/includes/src/comentarios/Comentario.php'; // Import the Comentario class

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

        // Recoge los comentarios
        $comentarios = \es\ucm\fdi\aw\comentarios\Comentario::buscarPorPeliculaId($movieId);

        //Calcula la valoracion de los comentarios
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

        // Reparto es un json así que lo desciframos para escribirlo
        $repartoData = json_decode($repartoJson);
        $repartoHtml = "";
        foreach ($repartoData as $obj) {
            $repartoHtml .= htmlspecialchars($obj->nombre) . " como " . htmlspecialchars($obj->personaje) . "<br>";
        }
        
        $contenidoPrincipal = <<<EOS
        <div style="display: flex; align-items: center; justify-content: start; margin-bottom: 20px;">
            <h2 style="margin-right: 20px;">$titulo ($anno)</h2>
            <form action="procesar_favorito.php" method="post" style="margin-top: 0;">
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
                <p><strong>Valoración 7thArt:</strong> $valoracionUsuariosHtml</p>
                <p><strong>Reparto:</strong><br>$repartoHtml</p>
                <p><strong>Sinopsis:</strong> $sinopsis</p>
            </div>
        </div>
        EOS;
        
    } else {
        $contenidoPrincipal = '<h1>Película no encontrada</h1>';
    }

    //Muestra los comentarios
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

    // Revisa si el usuario esta logueado para mostrarle la seccion añadir comentario
    if ($app->usuarioLogueado()) {
        $contenidoPrincipal .= <<<EOF

        <div class="comentario-formulario">
        <h3>Añadir un comentario</h3>
        <form id="comentarioForm" action="includes/src/comentarios/procesar_comentario.php" method="post">
            <input type="hidden" name="pelicula_id" value="<?= $movieId ?>">
            <textarea name="texto" id="comentario-texto" required></textarea>
            <select name="valoracion" id="comentario-valoracion" required>
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
        </div>
        <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
        <script type="text/javascript" src="js/ValidarFormulario.js"></script>
        EOF;
    }

} else {
    $contenidoPrincipal = '<h1>ID de película no especificado</h1>';
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
