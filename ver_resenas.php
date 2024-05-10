<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\peliculas\Pelicula;
use es\ucm\fdi\aw\resenas\Resena;

$tituloPagina = 'Reseñas de Críticos';
$contenidoPrincipal = '';

if (isset($_GET['id'])) {
    $movieId = $_GET['id'];
    $movie = Pelicula::buscaPorId($movieId);

    if ($movie) {
        $titulo = $movie->titulo;
        $portada = $movie->portada;

        $resenas = Resena::buscarPorPeliculaId($movieId);

        ob_start(); 
        ?>
        <div class="movie-info">
            <h1><?php echo htmlspecialchars($titulo); ?></h1>
            <a href='vista_pelicula.php?id=<?php echo $movieId; ?>'>
                <img src="<?php echo htmlspecialchars($portada); ?>" alt="Portada de <?php echo htmlspecialchars($titulo); ?>" class="portada-pelicula">
            </a>
        </div>

        <h2>Reseñas de Críticos</h2>
        <?php
        if (count($resenas) > 0) {
            foreach ($resenas as $resena) {
                $critic = Usuario::buscaPorId($resena->getUserId());
                ?>
                <div class="review">
                    <div class="info-critico">
                        <img src="<?php echo htmlspecialchars($critic->getFoto()); ?>" alt="Foto de <?php echo htmlspecialchars($critic->getNombreUsuario()); ?>" class="foto-critico">
                        <h3><?php echo htmlspecialchars($critic->getNombreUsuario()); ?></h3>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($resena->getTexto())); ?></p>
                    <p>Valoración: <?php echo htmlspecialchars($resena->getValoracion()); ?></p>
                </div>
                <?php
            }
        } else {
            echo "<p>No hay reseñas disponibles para esta película.</p>";
        }
        $contenidoPrincipal = ob_get_clean(); 
    } else {
        $contenidoPrincipal = "<h1>Película no encontrada.</h1>";
    }
} else {
    $contenidoPrincipal = "<h1>ID de película no especificado.</h1>";
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
