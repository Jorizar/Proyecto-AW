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
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $resultMejorValoradas->fetch_assoc()) {
        $id = $row['id'];
        $titulo = $row['titulo'];
        $imagen = $row['portada'];
        $valoracion = $row['Val_IMDb'];
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
    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
} else {
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}


// Consulta SQL para obtener las películas del género 1
$queryGenero1 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 1
                ORDER BY Val_IMDb DESC";

$resultGenero1 = $conexion->query($queryGenero1);

if ($resultGenero1 && $resultGenero1->num_rows > 0) {
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas Dramáticas</h1>';
    $contenidoPrincipal .= '</div>';


    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="carrusel">';
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
    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
} else {
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}





// Consulta SQL para obtener las películas del género 1
$queryGenero2 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 2
                ORDER BY Val_IMDb DESC";

$resultGenero2 = $conexion->query($queryGenero2);

if ($resultGenero2 && $resultGenero2->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Comedias</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
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
    

    //$contenidoPrincipal .= '</div>'; // Cierre de div.carousel
    //$contenidoPrincipal .= '</div>'; // Cierre de div.contenedor-carousel
    //$contenidoPrincipal .= '<button role="button" id="flecha-derecha" class="flecha-derecha"><i class="fas fa-angle-right"></i></button>';
    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
} else {
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}

// Consulta SQL para obtener las películas del género 1
$queryGenero3 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 3
                ORDER BY Val_IMDb DESC";

$resultGenero3 = $conexion->query($queryGenero3);

if ($resultGenero3 && $resultGenero3->num_rows > 0) {
    // Agregar el encabezado "Películas de Acción"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de Acción</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
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
    

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
} else {
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}
// Consulta SQL para obtener las películas del género 1
$queryGenero4 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 4
                ORDER BY Val_IMDb DESC";

$resultGenero4 = $conexion->query($queryGenero4);

if ($resultGenero4 && $resultGenero4->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas Musicales</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
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
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
} else {
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}
// Consulta SQL para obtener las películas del género 1
$queryGenero5 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 5
                ORDER BY Val_IMDb DESC";

$resultGenero5 = $conexion->query($queryGenero5);

if ($resultGenero5 && $resultGenero5->num_rows > 0) {
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de Misterio</h1>';
    $contenidoPrincipal .= '</div>';

    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
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
    

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
} else {
    $contenidoPrincipal .= "<p>No hay peliculas.</p>";
}
// Consulta SQL para obtener las películas del género 1
$queryGenero6 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 6
                ORDER BY Val_IMDb DESC";

$resultGenero6 = $conexion->query($queryGenero6);

if ($resultGenero6 && $resultGenero6->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>SCFI</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
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

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
}

$queryGenero7 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 7
                ORDER BY Val_IMDb DESC";

$resultGenero7 = $conexion->query($queryGenero7);

if ($resultGenero7 && $resultGenero7->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de Terror</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
    while ($row = $resultGenero7->fetch_assoc()) {
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

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
}

$queryGenero8 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 8
                ORDER BY Val_IMDb DESC";

$resultGenero8 = $conexion->query($queryGenero8);

if ($resultGenero8 && $resultGenero8->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas Basadas en Hechos Reales</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
    while ($row = $resultGenero8->fetch_assoc()) {
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

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
}

$queryGenero9 = "SELECT id, titulo, portada
                FROM peliculas
                WHERE genero = 9
                ORDER BY Val_IMDb DESC";

$resultGenero9 = $conexion->query($queryGenero9);

if ($resultGenero9 && $resultGenero9->num_rows > 0) {
    // Agregar el encabezado "Películas del género 1"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas sobre Crímenes</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
   //$contenidoPrincipal .= '<div class="indicadores"></div>';
   $contenidoPrincipal .= '<div class="carrusel">';
   $contenidoPrincipal .= '<div class="peliculas-container">';
   //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
   //$contenidoPrincipal .= '<div class="contenedor-carousel">';
   //$contenidoPrincipal .= '<div class="carousel">';
    while ($row = $resultGenero9->fetch_assoc()) {
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

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
}

$queryActual = "SELECT id, titulo, portada
                FROM peliculas
                WHERE annio = 2024 
                ORDER BY Val_IMDb DESC";

$resultActual = $conexion->query($queryActual);

if ($resultActual && $resultActual->num_rows > 0) {
    // Agregar el encabezado "Películas de la década de los 80"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de este año</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
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

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
}

    $queryDecada80s = "SELECT id, titulo, portada
                FROM peliculas
                WHERE annio >= 1980 AND annio < 1990
                ORDER BY annio ASC";

$resultDecada80s = $conexion->query($queryDecada80s);

if ($resultDecada80s && $resultDecada80s->num_rows > 0) {
    // Agregar el encabezado "Películas de la década de los 80"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de la década de los 80</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
   //$contenidoPrincipal .= '<div class="indicadores"></div>';
   $contenidoPrincipal .= '<div class="carrusel">';
   $contenidoPrincipal .= '<div class="peliculas-container">';
   //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
   //$contenidoPrincipal .= '<div class="contenedor-carousel">';
   //$contenidoPrincipal .= '<div class="carousel">';
    while ($row = $resultDecada80s->fetch_assoc()) {
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

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
}

$queryDecada90s = "SELECT id, titulo, portada
                FROM peliculas
                WHERE annio >= 1990 AND annio < 2000
                ORDER BY annio ASC";

$resultDecada90s = $conexion->query($queryDecada90s);

if ($resultDecada90s && $resultDecada90s->num_rows > 0) {
    // Agregar el encabezado "Películas de la década de los 80"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de la década de los 90</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
    while ($row = $resultDecada90s->fetch_assoc()) {
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

    $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel

$queryDecada2000s = "SELECT id, titulo, portada
                FROM peliculas
                WHERE annio >= 2000 AND annio < 2010
                ORDER BY annio ASC";

$resultDecada82000s = $conexion->query($queryDecada2000s);

if ($resultDecada82000s && $resultDecada82000s->num_rows > 0) {
    // Agregar el encabezado "Películas de la década de los 80"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Películas de la década de los 2000</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    //$contenidoPrincipal .= '<div class="indicadores"></div>';
    $contenidoPrincipal .= '<div class="carrusel">';
    $contenidoPrincipal .= '<div class="peliculas-container">';
    //$contenidoPrincipal .= '<button role="button" id="flecha-izquierda" class="flecha-izquierda"><i class="fas fa-angle-left"></i></button>';
    //$contenidoPrincipal .= '<div class="contenedor-carousel">';
    //$contenidoPrincipal .= '<div class="carousel">';
    while ($row = $resultDecada82000s->fetch_assoc()) {
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

   $contenidoPrincipal .= '</div>'; // Cierre de div.peliculas-container

    // Agregar botones de navegación
    $contenidoPrincipal .= '<button class="prev">&#10094;</button>';
    $contenidoPrincipal .= '<button class="next">&#10095;</button>';
    
    $contenidoPrincipal .= '</div>'; // Cierre de div.carrusel
    } else {
        $contenidoPrincipal .= "<p>No hay peliculas.</p>";
    }

// Parámetros para generar la vista final
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

// Se genera la vista utilizando la plantilla
$app->generaVista('/plantillas/plantilla.php', $params);

echo '<script src="js/carrusel.js"></script>';
?>

