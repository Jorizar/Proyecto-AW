<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\noticias\Noticia;
use es\ucm\fdi\aw\noticias\FormularioAgregaNoticia;


if (!$app->tieneRol('admin')) {
    die("Acceso restringido a administradores.");
}

$tituloPagina = 'Administrar Noticias';


// Formulario de búsqueda para el buscador
$ADDForm = new FormularioAgregaNoticia();
$ADDForm = $ADDForm->gestiona();

// Agregar el formulario de agregar películas encima de la lista de películas disponibles

// Contenido de la página
$contenidoPrincipal = '';
$contenidoPrincipal .= "<h3>Añadir Nueva Noticia</h3>";
$contenidoPrincipal .= "<div class= 'añadirNoticia'>
                            $ADDForm
                        </div>";


$contenidoPrincipal .= '<h3>Todas las Noticias</h3>';

$noticias = Noticia::buscarTodas(0);

if (!empty($noticias)) {

    foreach ($noticias as $noticia) {
        $editForm = "<form method='POST' action='./admin_editaNoticia.php''>
                <input type='hidden' name='noticia_id' value='{$noticia->getID()}'>
                <input type='submit' value='Editar' class='editar-button'>
            </form>";

        $deleteForm = "<form method='POST' action='includes/src/noticias/eliminar_noticia.php' onsubmit='return confirm(\"¿Estás seguro de que quieres eliminar esta noticia?\");'>
                <input type='hidden' name='noticia_id' value='{$noticia->getID()}'>
                <input type='submit' value='Eliminar' class='eliminar-button'>
              </form>";

        
        $contenidoPrincipal .= "<div class='noticias_admin'>
                                <p>ID: {$noticia->getID()} - Título: {$noticia->getTitulo()}</p>
                                $editForm
                                $deleteForm                                
                             </div>";
    }
} else {
    $contenidoPrincipal .= "<p>No hay noticias disponibles.</p>";
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
