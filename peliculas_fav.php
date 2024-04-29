<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\favoritos\Favorito;
use es\ucm\fdi\aw\peliculas\Pelicula;



require_once __DIR__.'/includes/src/peliculas/Pelicula.php'; // Para obtener títulos de películas u otra información

$contenidoPrincipal = ''; 
$tituloPagina = 'Mis Favoritos'; 

$userId = $app->getUsuarioId();

// Mostrar favoritos del usuario
$favoritos = Favorito::buscaPorUser($userId);
$favoritosHtml = '<h3>Mis Favoritos</h3>';
if (!empty($favoritos)) {
    $favoritosHtml .= '<div id="favoritos-container">';
    foreach ($favoritos as $favorito) {
        $peliculaId = htmlspecialchars($favorito->getPelicula());
        $pelicula = Pelicula::buscaPorId($peliculaId);
        $peliculaTitulo = htmlspecialchars($pelicula->titulo); // Suponiendo que el objeto tiene una propiedad 'titulo'
        $peliculaPortada = htmlspecialchars($pelicula->portada); // Suponiendo que el objeto tiene una propiedad 'portada'

        
        $favoritosHtml .= "<div class='favorito'>
                                <a href='vista_pelicula.php?id=$peliculaId'> <!-- Enlace a la página de la película -->
                                <img src='$peliculaPortada' alt='Portada de $peliculaTitulo'> 
                                </a>
                                <p><a href='vista_pelicula.php?id=$peliculaId'>$peliculaTitulo</a></p>
                            </div>";

    }
    $favoritosHtml .= '</div>'; 
} else {
    $favoritosHtml .= "<p>No tienes películas en favoritos.</p>";
}

$contenidoPrincipal .= $favoritosHtml;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>