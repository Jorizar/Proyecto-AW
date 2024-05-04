<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\noticias\Noticia;
$tituloPagina = 'Noticias';
$contenidoPrincipal = '';
// Obtener la instancia de la aplicación
$app = Aplicacion::getInstance();

// Obtener la conexión a la base de datos desde la instancia de la aplicación
$result = Noticia::buscarTodas();

if ($result && count($result) > 0) {
    // Construir el contenido de las noticias
    $contenidoPrincipal = '<h2>Noticias</h2><div class="noticias">';
    foreach ($result as $row) {
        $idNoticia = $row->getID(); 
        $tituloNoticia = $row->getTitulo();
        $portadaNoticia = $row->getPortada(); 
        $nombreAutor = $row->getAutor();
        $fechaNoticia = $row->getFecha();
        $rolVisualizacion = $row->getRol();
        
        $contenidoPrincipal .= "<div class='noticia'>";
        $contenidoPrincipal .= "<img src='$portadaNoticia' alt='Portada' class='portada-noticia'>";
    

        if ($rolVisualizacion == 1) {
            if (!$app->usuarioLogueado()) {
                $contenidoPrincipal .= "<h3><a href='login.php'>$tituloNoticia <span class='solo-premium'> (Noticia Premium €)</span></a></h3>";
            } 
            elseif ($_SESSION['rol'] == "free") {
                $contenidoPrincipal .= "<h3><a href='cambioPlan.php'>$tituloNoticia <span class='solo-premium'> (Noticia Premium €)</span></a></h3>";
            } 
            else {
                // Rol == 0, puede ver la noticia
                $contenidoPrincipal .= "<h3><a href='ver_noticia.php?id=$idNoticia'>$tituloNoticia <span class='solo-premium'> (Noticia Premium €)</span></a></h3>";
            }
        } else {
            $contenidoPrincipal .= "<h3><a href='ver_noticia.php?id=$idNoticia'>$tituloNoticia</a></h3>";        
        }        
        
        $contenidoPrincipal .= "<p class='autor'><i>$nombreAutor</i> </p>";
        $contenidoPrincipal .= "<p class='fecha'>$fechaNoticia</p>";
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
