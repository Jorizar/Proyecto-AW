<?php
require_once __DIR__.'/includes/config.php';
use es\ucm\fdi\aw\peliculas\Pelicula;

$tituloPagina = 'Escribir Reseña';

if (isset($_GET['id'])) {
    $movieId = $_GET['id'];
    $movie = Pelicula::buscaPorId($movieId);

    if (!$movie) {
        $contenidoPrincipal = "<h1>Película no encontrada.</h1>";
    } else {
        ob_start(); 
        ?>
        <div class="resena-form-container">
            <h1>Escribir Reseña para <?php echo htmlspecialchars($movie->titulo); ?></h1>
                <form action="includes/src/resenas/procesar_resenas.php" method="post">
                    <input type="hidden" name="pelicula_id" value="<?php echo htmlspecialchars($movieId); ?>">
                        <div>
                            <label for="texto">Reseña:</label>
                            <textarea id="texto" name="texto" required></textarea>
                        </div>
                        <div>
                            <label for="valoracion">Valoración:</label>
                            <select id="valoracion" name="valoracion" required>
                                <?php for ($i = 0; $i <= 10; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                } ?>
                            </select>
                        </div>
                        <div>
                            <button type="submit">Publicar Reseña</button>
                        </div>
                </form>
        </div>
        <?php
        $contenidoPrincipal = ob_get_clean(); 
    }
} else {
    $contenidoPrincipal = "<h1>ID de película no especificado.</h1>";
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
