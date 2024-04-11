<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/favoritos/Favoritos.php'; // Ajusta la ruta según sea necesario
require_once __DIR__.'/includes/src/peliculas/Pelicula.php'; // Para obtener títulos de películas u otra información

$contenidoPrincipal = ''; 
$tituloPagina = 'Mis Favoritos'; 

$userId = $app->getUsuarioId();

// Mostrar favoritos del usuario
$favoritos = \es\ucm\fdi\aw\favoritos\Favorito::buscaPorUser($userId);
$favoritosHtml = '<h3>Mis Favoritos</h3>';
if (!empty($favoritos)) {
    foreach ($favoritos as $favorito) {
        $peliculaId = htmlspecialchars($favorito->getPelicula());
        $pelicula = \es\ucm\fdi\aw\peliculas\Pelicula::buscaPorId($peliculaId);
        $peliculaTitulo = htmlspecialchars($pelicula->titulo); // Suponiendo que el objeto tiene una propiedad 'titulo'
        $peliculaPortada = htmlspecialchars($pelicula->portada); // Suponiendo que el objeto tiene una propiedad 'portada'

        // Modifica las acciones aquí para incluir la imagen de la portada como un enlace
        $favoritosHtml .= "<div class='favorito'>
                                <a href='vista_pelicula.php?id=$peliculaId'> <!-- Enlace a la página de la película -->
                                    <img src='$peliculaPortada' alt='Portada de $peliculaTitulo' style='width: 100px; height: auto;'> 
                                </a>
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
