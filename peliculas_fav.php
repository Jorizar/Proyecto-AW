<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/favoritos/Favoritos.php'; // Adjust the path as necessary
require_once __DIR__.'/includes/src/peliculas/Pelicula.php'; // For fetching movie titles or other info

$contenidoPrincipal = ''; 
$tituloPagina = 'Mis Favoritos'; 

$userId = $app->getUsuarioId();

// Display user's favorites
$favoritos = \es\ucm\fdi\aw\favoritos\Favorito::buscaPorUser($userId);
$favoritosHtml = '<h3>Mis Favoritos</h3>';
if (!empty($favoritos)) {
    foreach ($favoritos as $favorito) {
        $peliculaId = htmlspecialchars($favorito->getPelicula());
        $pelicula = \es\ucm\fdi\aw\peliculas\Pelicula::buscaPorId($peliculaId);
        $peliculaTitulo = htmlspecialchars($pelicula->titulo); // Assuming the object has a 'titulo' property
        $peliculaPortada = htmlspecialchars($pelicula->portada); // Assuming the object has a 'portada' property

        // Modify actions here to include the portada image
        $favoritosHtml .= "<div class='favorito'>
                                <img src='$peliculaPortada' alt='Portada de $peliculaTitulo' style='width: 100px; height: auto;'> 
                                <p>Película: $peliculaTitulo</p>
                             </div>";
    }
} else {
    $favoritosHtml .= "<p>No tienes películas en favoritos.</p>";
}

$contenidoPrincipal .= $favoritosHtml;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
