<?php
// Se incluye el archivo de configuración
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\peliculas\FormularioBuscaPel;

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
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
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
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas Dramáticas</h1>';
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
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}

// Consulta SQL para obtener las películas del género 1
$queryGenero2 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 2
                ORDER BY Val_IMDb DESC
                LIMIT 4";

$resultGenero2 = $conexion->query($queryGenero2);

if ($resultGenero2 && $resultGenero2->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Comedias</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultGenero2->fetch_assoc()) {
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
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}

// Consulta SQL para obtener las películas del género 1
$queryGenero3 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 3
                ORDER BY Val_IMDb DESC
                LIMIT 4";

$resultGenero3 = $conexion->query($queryGenero3);

if ($resultGenero3 && $resultGenero3->num_rows > 0) {
    // Agregar el encabezado "Películas de Acción"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de Acción</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultGenero3->fetch_assoc()) {
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
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}
// Consulta SQL para obtener las películas del género 1
$queryGenero4 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 4
                ORDER BY Val_IMDb DESC
                LIMIT 4";

$resultGenero4 = $conexion->query($queryGenero4);

if ($resultGenero4 && $resultGenero4->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas Musicales</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultGenero4->fetch_assoc()) {
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
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}
// Consulta SQL para obtener las películas del género 1
$queryGenero5 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 5
                ORDER BY Val_IMDb DESC
                LIMIT 4";

$resultGenero5 = $conexion->query($queryGenero5);

if ($resultGenero5 && $resultGenero5->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas Misteriosas</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultGenero5->fetch_assoc()) {
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
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}
// Consulta SQL para obtener las películas del género 1
$queryGenero6 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 6
                ORDER BY Val_IMDb DESC
                LIMIT 4";

$resultGenero6 = $conexion->query($queryGenero6);

if ($resultGenero6 && $resultGenero6->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>SCFI</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultGenero6->fetch_assoc()) {
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
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}

// Parámetros para generar la vista final
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

// Se genera la vista utilizando la plantilla
$app->generaVista('/plantillas/plantilla.php', $params);
?>
