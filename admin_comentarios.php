<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\comentarios\Comentario;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\peliculas\Pelicula;


if (!$app->tieneRol('admin')) {
    die("Acceso restringido a administradores.");
}

$tituloPagina = 'Administrar Comentarios';
$contenidoPrincipal = '<h3>Todos los Comentarios</h3>';

$comentarios = Comentario::buscarTodos();

if (!empty($comentarios)) {
    foreach ($comentarios as $comentario) {
        $textoComentario = htmlspecialchars($comentario->getTexto());
        $valoracionComentario = htmlspecialchars($comentario->getValoracion());
        $peliculaId = htmlspecialchars($comentario->getPeliculaId());
        $peliculaTitulo = Pelicula::buscaTituloPorId($peliculaId);
        $UserId = htmlspecialchars($comentario->getUserId());
        $UserNombre = Usuario::buscaNombrePorId($UserId);

        $editForm ="<form method='POST' action='editaComent.php'>
                        <input type='hidden' name='ID_comentario' value='{$comentario->getComentarioId()}'>
                        <input type='submit' value='Editar'>
                    </form>";
        
        $deleteForm = "<form method='POST' action='includes/src/comentarios/eliminar_comentario.php' onsubmit='return confirm(\"¿Estás seguro?\");'>
                            <input type='hidden' name='comentario_id' value='{$comentario->getComentarioId()}'>
                            <input type='submit' value='Eliminar'>
                       </form>";
        
        $contenidoPrincipal .= "<div class='comentario'>
                                <p>Usuario: $UserNombre</p>
                                <p>Película: $peliculaTitulo</p>
                                <p>Comentario: $textoComentario</p>
                                <p>Valoración: $valoracionComentario</p>
                                $editForm
                                $deleteForm
                             </div>";
    }
} else {
    $contenidoPrincipal .= "<p>No hay comentarios.</p>";
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
