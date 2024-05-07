<?php
// Se incluye el archivo de configuración
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\peliculas\FormularioBuscaPel;
use es\ucm\fdi\aw\peliculas\Pelicula;

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


// Se muestran los Carruseles de Peliculas.
$contenidoPrincipal .= mostrarCarruselesPeliculas();

// Parámetros para generar la vista final
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

// Se genera la vista utilizando la plantilla
$app->generaVista('/plantillas/plantilla.php', $params);

echo '<script src="js/carrusel-indicadores.js"></script>';


//--------------Funciones para mostrar los Carruseles-----------
function mostrarCarruselesPeliculas() {
    $contenidoPrincipal = '';
    $contenidoPrincipal .= mejorValoradas();
    $contenidoPrincipal .= peliculasGeneros();
    $contenidoPrincipal .= peliculasAnnioActual();
    $contenidoPrincipal .= peliculasDecada();
    return $contenidoPrincipal;
}
function peliculasGeneros() {
    $contenidoPrincipal = '';
    $generos = Pelicula::getGeneros();

    foreach ($generos as $id => $genero){
        $resultGenero = Pelicula::peliculasPorGenero($id, 0);
        $contenidoPrincipal .= mostrarCarrusel($resultGenero, "Películas de ".$genero);
    }
    return $contenidoPrincipal;
}
function peliculasDecada() {
    $contenidoPrincipal = '';
    for ($i = 1970; $i <= 2000; $i += 10) {
        $resultDecada = Pelicula::peliculasPorAnnio($i, $i+10, -1);
        if ($resultDecada && count($resultDecada) > 0) {
            $decada = str_replace("19", "", $i);
            $contenidoPrincipal .= mostrarCarrusel($resultDecada, "Películas de la decada de los ".$decada);
        }
    }
    return $contenidoPrincipal;
}
function peliculasAnnioActual() {
    $annio = date('Y');
    $result = Pelicula::peliculasPorAnnio($annio, $annio + 1, -1);
    return mostrarCarrusel($result, "Películas de este año");
}
function mejorValoradas(){
    $resultMejorValoradas = Pelicula::peliculasMejorVal(16);
    return mostrarCarrusel($resultMejorValoradas, "Top Películas");
}
function mostrarCarrusel($result, $nombreGrupo){
    if (!($result && count($result) > 0)){
        return "<p>No hay peliculas.</p>";
    }
    $contenidoPrincipal = '';
    
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>'.$nombreGrupo.'</h1>';
    $contenidoPrincipal .= '</div>';

    if (!($result && count($result) > 0)){
        $contenidoPrincipal .="<p>No hay peliculas.</p>";
        return $contenidoPrincipal;
    }

    $contenidoPrincipal .= '<div class="indicadores">';
    $contenidoPrincipal .= '</div>'; 

    
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';

    $contenidoPrincipal .= mostrarPeliculas($result);

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
    
    return $contenidoPrincipal;
}
function mostrarPeliculas($peliculas){
    $contenidoPrincipal='';
    foreach ($peliculas as $pelicula) {
        $contenidoPrincipal .= mostrarPelicula($pelicula);
    }
    return $contenidoPrincipal;
}
function mostrarPelicula ($pelicula){
    $id = $pelicula['id'];
    $titulo = $pelicula['titulo'];
    $imagen = $pelicula['portada'];
    $valoracion = $pelicula['val_IMDb'];
    // Enlace por cada película que redirige a la vista de la película
    $contenidoPrincipal = <<<HTML
        <div class="pelicula">
            <a href="vista_pelicula.php?id=$id">
                <img src="$imagen" alt="$titulo">
                <span>$titulo ($valoracion)</span>
            </a>
        </div>
    HTML;
    return $contenidoPrincipal;
}

?>