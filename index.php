<?php
// Se incluye el archivo de configuración
require_once __DIR__.'/includes/config.php';

// Título de la página
$tituloPagina = 'Portada';

//Contenido de la página
$contenidoPrincipal = '';

//Formulario de búsqueda para el buscador
$formBusqueda = new \es\ucm\fdi\aw\peliculas\FormularioBuscaPel();
$formBusqueda = $formBusqueda->gestiona();

//Agregar el buscador al contenido de la página
$contenidoPrincipal .= <<<BUSCADOR
        <div class="buscador">
        $formBusqueda
        </div>
BUSCADOR;

// Obtener la instancia de la aplicación
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();

// Obtener la conexión a la base de datos desde la instancia de la aplicación
$conexion = $app->getConexionBd();

// Consulta SQL para obtener todas las películas
$query = "SELECT id, titulo, portada FROM peliculas";
$result = $conexion->query($query);

if ($result && $result->num_rows > 0) {
    // Agregar el encabezado "Peliculas destacadas de la semana"
    $contenidoPrincipal .= '<div class="destacadas">';
    $contenidoPrincipal .= '<h1>Peliculas destacadas de la semana</h1>';
    $contenidoPrincipal .= '</div>';

    // Continuar con el contenido principal
    $contenidoPrincipal .= '<div class="peliculas-container">';
    while ($row = $result->fetch_assoc()) {
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
    $contenidoPrincipal .= "<p>No se encontraron películas destacadas.</p>";
}

// Parámetros para generar la vista final
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];

// Se genera la vista utilizando la plantilla
$app->generaVista('/plantillas/plantilla.php', $params);
?>
