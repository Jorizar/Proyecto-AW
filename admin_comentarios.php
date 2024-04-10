<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/comentarios/Comentario.php';

if (!$app->tieneRol('admin')) {
    die("Acceso restringido a administradores.");
}

$tituloPagina = 'Administrar Comentarios';
$contenidoPrincipal = '<h3>Todos los Comentarios</h3>';

$comentarios = \es\ucm\fdi\aw\comentarios\Comentario::buscarTodos();

if (!empty($comentarios)) {
    foreach ($comentarios as $comentario) {
        $textoComentario = htmlspecialchars($comentario->getTexto());
        $valoracionComentario = htmlspecialchars($comentario->getValoracion());
        $peliculaId = htmlspecialchars($comentario->getPeliculaId());
        $peliculaTitulo = \es\ucm\fdi\aw\peliculas\Pelicula::buscaTituloPorId($peliculaId);
        $UserId = htmlspecialchars($comentario->getUserId());
        $UserNombre = \es\ucm\fdi\aw\usuarios\Usuario::buscaNombrePorId($UserId);
        
        $deleteForm = "<form method='POST' action='includes/src/comentarios/eliminar_comentario_admin.php' onsubmit='return confirm(\"¿Estás seguro?\");'>
                            <input type='hidden' name='comentario_id' value='{$comentario->getComentarioId()}'>
                            <input type='submit' value='Eliminar'>
                       </form>";
        
        $contenidoPrincipal .= "<div class='comentario'>
                                <p>Usuario: $UserNombre</p>
                                <p>Película: $peliculaTitulo</p>
                                <p>Comentario: $textoComentario</p>
                                <p>Valoración: $valoracionComentario</p>
                                $deleteForm
                             </div>";
    }
} else {
    $contenidoPrincipal .= "<p>No hay comentarios.</p>";
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
