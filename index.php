<?php
// Se incluye el archivo de configuración
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\peliculas\FormularioBuscaPel;
use es\ucm\fdi\aw\peliculas\Pelicula;
use es\ucm\fdi\aw\Aplicacion;

// Título de la página
$tituloPagina = 'Portada';

// Contenido de la página
$contenidoPrincipal = '';

// Formulario de búsqueda para el buscador
$formBusqueda = new FormularioBuscaPel();
$formBusqueda = $formBusqueda->gestiona();

// Agregar el buscador al contenido de la página
$contenidoPrincipal .= <<<BUSCADOR
        <div class="buscador">
        $formBusqueda
        </div>
BUSCADOR;

// 8 películas mejor valoradas
$peliculasMV = Pelicula::peliculasMejorVal(8);

// Agregar el encabezado "Peliculas mejor valoradas"
$contenidoPrincipal .= '<div class="destacadas">';
$contenidoPrincipal .= '<h1>Películas mejor valoradas</h1>';
$contenidoPrincipal .= '</div>';
    
// Continuar con el contenido principal

if($peliculasMV){
    $contenidoPrincipal .= '<div class="peliculas-container">';
    foreach($peliculasMV as $pelicula){
        $id = $pelicula['id'];
        $titulo = $pelicula['titulo'];
        $imagen = $pelicula['portada'];
        $valoracion = $pelicula['val_imdb'];
        // Enlace por cada película que redirige a la vista de la película
        $contenidoPrincipal .= <<<HTML
            <div class="pelicula">
                <a href="vista_pelicula.php?id=$id">
                    <img src="$imagen" alt="$titulo">
                    <span>$titulo ($valoracion)</span>
                </a>
            </div>
HTML;
    }
    $contenidoPrincipal .= '</div>';
}
else{
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}
    


// Mostramos las mejores películas por géneros.

$htmlGeneros = array();
$htmlGeneros[1] = '<div class="destacadas"><h1>Películas Dramáticas</h1></div>';
$htmlGeneros[2] = '<div class="destacadas"><h1>Comedias</h1></div>';
$htmlGeneros[3] = '<div class="destacadas"><h1>Películas de Acción</h1></div>';
$htmlGeneros[4] = '<div class="destacadas"><h1>Películas Musicales</h1></div>';
$htmlGeneros[5] = '<div class="destacadas"><h1>Películas de Misterio</h1></div>';
$htmlGeneros[6] = '<div class="destacadas"><h1>SCFI</h1></div>';
$htmlGeneros[7] = '<div class="destacadas"><h1>Películas de Terror</h1></div>';
$htmlGeneros[8] = '<div class="destacadas"><h1>Películas Basadas en Hecho Reales</h1></div>';
$htmlGeneros[9] = '<div class="destacadas"><h1>Películas sobre Crímenes</h1></div>';

for($i = 1; $i <= 9; $i++){
    //Obtenemos las películas del primer género
    $peliculas = Pelicula::peliculasPorGenero($i, 4);
    $contenidoPrincipal .= $htmlGeneros[$i];
    if($peliculas){
        $contenidoPrincipal .= '<div class="peliculas-container">';
        foreach($peliculas as $pelicula){
            $id = $pelicula['id'];
            $titulo = $pelicula['titulo'];
            $imagen = $pelicula['portada'];
            // Enlace por cada película que redirige a la vista de la película
            $contenidoPrincipal .= <<<HTML
                <div class="pelicula">
                    <a href="vista_pelicula.php?id=$id">
                        <img src="$imagen" alt="$titulo">
                        <span>$titulo</span>
                    </a>
                </div>
HTML;
        }
        $contenidoPrincipal .= '</div>';
    }
    else{
        $contenidoPrincipal .= "<p>No hay peliculas de este género.</p>";
    }
}

$conn = Aplicacion::getInstance()->getConexionBd();
$queryActual = "SELECT id, titulo, portada
                FROM peliculas
                WHERE annio = 2024 
                ORDER BY annio ASC
                LIMIT 4";

$resultActual = $conn->query($queryActual);

if ($resultActual && $resultActual->num_rows > 0) {
    // Agregar el encabezado "Películas de la década de los 80"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de este año</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultActual->fetch_assoc()) {
        $id = $row['id'];
        $titulo = $row['titulo'];
        $imagen = $row['portada'];
        // Enlace por cada película que redirige a la vista de la película
        $contenidoPrincipal .= <<<HTML
            <div class="pelicula">
                <a href="vista_pelicula.php?id=$id">
                    <img src="$imagen" alt="$titulo">
                    <span>$titulo</span>
                </a>
            </div>
HTML;
    }
}
else{
    $contenidoPrincipal .= "<p>No hay peliculas de esta década.</p>";
}

$htmlDecadas = array();
$htmlDecadas[0] = '<div class="destacadas"><h1>Películas de la década de los 80</h1></div>'; 
$htmlDecadas[1] = '<div class="destacadas"><h1>Películas de la década de los 90</h1></div>'; 
$htmlDecadas[2] = '<div class="destacadas"><h1>Películas de la década de los 2000</h1></div>';

for($i = 0; $i < 3; $i++){
    //Obtenemos las películas del primer género
    $annio_inf = 1980;
    $annio_sup = 1990;
    $peliculas = Pelicula::peliculasPorAnnio($annio_inf, $annio_sup, 4);
    $annio_inf += 10;
    $annio_sup += 10;
    $contenidoPrincipal .= $htmlDecadas[$i];
    if($peliculas){
        $contenidoPrincipal .= '<div class="peliculas-container">';
        foreach($peliculas as $pelicula){
            $id = $pelicula['id'];
            $titulo = $pelicula['titulo'];
            $imagen = $pelicula['portada'];
            // Enlace por cada película que redirige a la vista de la película
            $contenidoPrincipal .= <<<HTML
                <div class="pelicula">
                    <a href="vista_pelicula.php?id=$id">
                        <img src="$imagen" alt="$titulo">
                        <span>$titulo</span>
                    </a>
                </div>
HTML;
        }
        $contenidoPrincipal .= '</div>';
    }
    else{
        $contenidoPrincipal .= "<p>No hay peliculas de esta década.</p>";
    }
}

// Parámetros para generar la vista final
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

// Se genera la vista utilizando la plantilla
$app->generaVista('/plantillas/plantilla.php', $params);
?>
