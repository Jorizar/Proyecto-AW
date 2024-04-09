<?php

// Se incluye el archivo de configuración
require_once __DIR__.'/includes/config.php';

// Título de la página
$tituloPagina = 'Portada';

// URL para el inicio de sesión
$login = resuelve('/login.php');

$contenidoPrincipal = '';

// Array de películas con su información (id, título, imagen)
$peliculas = [
    ['id' => 1, 'titulo' => 'La La Land', 'imagen' => './img/portadas/la_la_land.jpg'],
    ['id' => 2, 'titulo' => 'La playa de los ahogados', 'imagen' => './img/portadas/la_playa_de_los_ahogados.jpg'],
    ['id' => 3, 'titulo' => 'La ciudad no es para mí', 'imagen' => './img/portadas/la_ciudad_no_es_para_mi.jpg'],
    ['id' => 4, 'titulo' => 'Unico testigo', 'imagen' => './img/portadas/witness.jpg'],
    ['id' => 5, 'titulo' => 'The Fast and the Furious', 'imagen' => './img/portadas/the_fast_and_the_furious.jpg'],
    ['id' => 6, 'titulo' => 'Ocho apellidos vascos', 'imagen' => './img/portadas/ocho_apellidos_vascos.jpg'],
    ['id' => 7, 'titulo' => 'Estoy hecho un chaval', 'imagen' => './img/portadas/estoy_hecho_un_chaval.jpg'],
    ['id' => 8, 'titulo' => 'Forrest Gump', 'imagen' => './img/portadas/forrest_gump.jpg'],
    // Agregar más películas según sea necesario
];

// Agregar el encabezado "Peliculas destacadas de la semana"
$contenidoPrincipal .= '<div class="destacadas">';
$contenidoPrincipal .= '<h1>Peliculas destacadas de la semana</h1>';
$contenidoPrincipal .= '</div>';

// Continuar con el contenido principal
$contenidoPrincipal .= '<div class="peliculas-container">';
foreach ($peliculas as $pelicula) {
    $id = $pelicula['id'];
    $titulo = $pelicula['titulo'];
    $imagen = $pelicula['imagen'];
    // Formulario por cada película con un botón que redirija a la vista de la película
    $contenidoPrincipal .= <<<HTML
        <form action="vista_pelicula.php" method="get">
            <input type="hidden" name="id" value="$id">
            <button type="submit">
                <img src="$imagen" alt="$titulo">
                <span>$titulo</span>
            </button>
        </form>
HTML;
}
$contenidoPrincipal .= '</div>';


// Parámetros para generar la vista final
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

// Se genera la vista utilizando la plantilla
$app->generaVista('/plantillas/plantilla.php', $params);

?>
