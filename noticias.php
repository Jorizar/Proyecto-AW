<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Noticias';
$contenidoPrincipal = '';
// Obtener la instancia de la aplicación
$app = \es\ucm\fdi\aw\Aplicacion::getInstance();

// Obtener la conexión a la base de datos desde la instancia de la aplicación
$conexion = $app->getConexionBd();

// Obtener las 5 últimas noticias de la base de datos
$query = "SELECT titulo, post_id, portada, texto, autor FROM noticias";
$result = $conexion->query($query);

if ($result && $result->num_rows > 0) {
    // Construir el contenido de las noticias
    $contenidoPrincipal = '<h2>Noticias</h2><div class="noticias">';
    while ($row = $result->fetch_assoc()) {
        $idNoticia = $row['post_id']; // Cambiado de 'id' a 'post_id'
        $tituloNoticia = $row['titulo'];
        $portadaNoticia = $row['portada']; 
        $nombreAutor = $row['autor'];
        //$contenidoNoticia = $row['texto']; // Cambiado de 'contenido' a 'texto'

        // Agregar el HTML de cada noticia
        $contenidoPrincipal .= "<div class='noticia'>";
        $contenidoPrincipal .= "<img src='$portadaNoticia' alt='Portada' class='portada-noticia'>"; 
        $contenidoPrincipal .= "<h3><a href='ver_noticia.php?id=$idNoticia'>$tituloNoticia</a></h3>";
        $contenidoPrincipal .= "<p class='autor'><i>$nombreAutor</i></p>";
        //$contenidoPrincipal .= "<p>$contenidoNoticia</p>";
        $contenidoPrincipal .= "</div>";
    }
    $contenidoPrincipal .= '</div>';
} else {
    $contenidoPrincipal = "<p>No se encontraron noticias.</p>";
}

// Renderizar la página utilizando la plantilla
$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
