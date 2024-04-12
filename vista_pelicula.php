<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\peliculas\Pelicula;
use es\ucm\fdi\aw\comentarios\Comentario;
use es\ucm\fdi\aw\favoritos\Favorito;

$tituloPagina = 'Detalles de la Película';
$contenidoPrincipal='';

if (isset($_GET['id'])) {
    $movieId = $_GET['id'];
    // Suponiendo que buscaPorId devuelve un objeto de tipo Pelicula con los detalles de la película
    $movie = Pelicula::buscaPorId($movieId);

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

        $genero = Pelicula::convierteGenero($generoId); // Convertimos la ID del género a texto

        // Recoge los comentarios
        $comentarios = Comentario::buscarPorPeliculaId($movieId);

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
        
        // Verificar si la película está en la lista de favoritos del usuario
        $estaEnFavoritos = Favorito::existe($app->getUsuarioId(), $movieId);

        ob_start(); // Inicia el almacenamiento en el buffer de salida
        ?>
        <div class="info_pelicula">
            <h2><?php echo $titulo . ' (' . $anno . ')'; ?></h2>
            
            <!-- Mostrar botón para añadir o eliminar de favoritos según corresponda -->
            <?php if ($estaEnFavoritos): ?>
                <form action="includes/src/favoritos/procesar_favorito.php" method="post" class="eliminar-favoritos-form"> 
                    <input type="hidden" name="eliminarMovieId" value="<?php echo $movieId; ?>">
                    <button type="submit" class="boton-fav">Eliminar de <img src="./img/fav.png" alt="Películas"></button>
                </form>
            <?php else: ?>
                <form action="includes/src/favoritos/procesar_favorito.php" method="post"class="añadir-favoritos-form">
                    <input type="hidden" name="movieId" value="<?php echo $movieId; ?>">
                    <button type="submit" class="boton-fav">Añadir a <img src="./img/fav.png" alt="Películas"></button>
                </form>
            <?php endif; ?>

            <div class="portada_detalles_pelicula">
                <img src="<?php echo $portada; ?>" alt="Portada de <?php echo $titulo; ?>" class="portada-pelicula">
                <div class="detalles-pelicula">
                    <p><strong>Director:</strong> <?php echo $director; ?></p>
                    <p><strong>Género:</strong> <?php echo $genero; ?></p>
                    <p><strong>Valoración IMDb:</strong> <?php echo $valoracionIMDb; ?></p>
                    <p><strong>Valoración 7thArt:</strong> <?php echo $valoracionUsuariosHtml; ?></p>
                    <p><strong>Reparto:</strong><br><?php echo $repartoHtml; ?></p>
                    <p><strong>Sinopsis:</strong> <?php echo $sinopsis; ?></p>
                </div>
            </div>
        </div>
        <?php
        $contenidoPrincipal = ob_get_clean(); // Guarda y limpia el contenido del buffer de salida
    }
    // Muestra los comentarios
    $numComentarios = count($comentarios);
    $comentariosHtml = '<h3>Comentarios (' . $numComentarios . ')</h3>';
    foreach ($comentarios as $comentario) {
        $textoComentario = htmlspecialchars($comentario->getTexto());
        $valoracionComentario = htmlspecialchars($comentario->getValoracion());
        $UserId = htmlspecialchars($comentario->getUserId());
        $UserNombre = Usuario::buscaNombrePorId($UserId);
    
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
        <div class="comentario-formulario">
            <h3>Añadir un comentario</h3>
            <form action="includes/src/comentarios/procesar_comentario.php" method="post">
            <input type="hidden" name="pelicula_id" value="<?= $movieId ?>">
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
        </div>
        EOF;
    }

} else {
    $contenidoPrincipal = '<h1>ID de película no especificado</h1>';
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
