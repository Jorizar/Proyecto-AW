<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\comentarios\Comentario;
use es\ucm\fdi\aw\peliculas\Pelicula;

$contenidoPrincipal = ''; 
$tituloPagina = 'Mis Comentarios'; 

$userId = $app->getUsuarioId();

// Mostramos los comentarios del usuario
$comentarios = Comentario::buscarPorUsuarioId($userId);
$comentariosHtml = '<h3>Mis Comentarios</h3>';
if (!empty($comentarios)) {
    foreach ($comentarios as $comentario) {
        $textoComentario = htmlspecialchars($comentario->getTexto());
        $valoracionComentario = htmlspecialchars($comentario->getValoracion());
        $peliculaId = htmlspecialchars($comentario->getPeliculaId());
        $peliculaTitulo = Pelicula::buscaTituloPorId($peliculaId);


        $editForm ="<form method='POST' action='editaComent.php'>
                        <input type='hidden' name='ID_comentario' value='{$comentario->getComentarioId()}'>
                        <input type='submit' value='Editar'>
                    </form>";

        $deleteForm = "<form method='POST' action='includes/src/comentarios/eliminar_comentario.php' onsubmit='return confirm(\"¿Estás seguro?\");'>
                            <input type='hidden' name='comentario_id' value='{$comentario->getComentarioId()}'>
                            <input type='submit' value='Eliminar'>
                       </form>";
        
        $comentariosHtml .= "<div class='comentario'>
                                <p>Película: $peliculaTitulo</p>
                                <p>Comentario: $textoComentario</p>
                                <p>Valoración: $valoracionComentario</p>
                                $editForm
                                $deleteForm
                             </div>";
    }
} else {
    $comentariosHtml .= "<p>No has realizado ningún comentario.</p>";
}

$contenidoPrincipal .= $comentariosHtml;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);

?>
