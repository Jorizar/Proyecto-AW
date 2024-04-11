<?php
// Se incluye el archivo de configuración
require_once __DIR__.'/includes/config.php';

// Título de la página
$tituloPagina = 'Portada';

// Contenido de la página
$contenidoPrincipal = '';

// Formulario de búsqueda para el buscador
$formBusqueda = new \es\ucm\fdi\aw\peliculas\FormularioBuscaPel();
$formBusqueda = $formBusqueda->gestiona();

// Agregar el buscador al contenido de la página
$contenidoPrincipal .= <<<BUSCADOR
        <div class="buscador">
        <h1>Buscador</h1>
        $formBusqueda
        </div>
BUSCADOR;

// Obtener la instancia de la aplicación
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();

// Obtener la conexión a la base de datos desde la instancia de la aplicación
$conexion = $app->getConexionBd();

// Consulta SQL para obtener las 8 películas mejor valoradas
$queryMejorValoradas = "SELECT id, titulo, portada, Val_IMDb
                        FROM peliculas
                        ORDER BY Val_IMDb DESC
                        LIMIT 8";

$resultMejorValoradas = $conexion->query($queryMejorValoradas);

if ($resultMejorValoradas && $resultMejorValoradas->num_rows > 0) {
    // Agregar el encabezado "Peliculas mejor valoradas"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas mejor valoradas</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultMejorValoradas->fetch_assoc()) {
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
    $contenidoPrincipal .= '</div>';
} else {
    $contenidoPrincipal .= "<p>No se encontraron películas mejor valoradas.</p>";
}

// Consulta SQL para obtener las películas del género 1
$queryGenero1 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 1
                ORDER BY Val_IMDb DESC
                LIMIT 4";

$resultGenero1 = $conexion->query($queryGenero1);

if ($resultGenero1 && $resultGenero1->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="generoDrama">';
    $contenidoPrincipal .= '<h2>Películas Dramáticas</h2>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultGenero1->fetch_assoc()) {
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
    $contenidoPrincipal .= '</div>';
} else {
    $contenidoPrincipal .= "<p>No se encontraron películas del género 1.</p>";
}

// Parámetros para generar la vista final
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

// Se genera la vista utilizando la plantilla
$app->generaVista('/plantillas/plantilla.php', $params);
?>
