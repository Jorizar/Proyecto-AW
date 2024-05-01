<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\comentarios\FormularioEditaComent;
use es\ucm\fdi\aw\comentarios\FormularioEditaComentAdmin;

$comentario_id = filter_input(INPUT_POST, 'ID_comentario', FILTER_SANITIZE_NUMBER_INT);

if (empty($comentario_id)) {
  echo "No hemos podido obtener el id del comentario";
  exit();
}


if (!$app->tieneRol('admin')) {

  $formEditorComents = new FormularioEditaComent($comentario_id);
  $formEditorComents = $formEditorComents->gestiona();

  $tituloPagina = 'Editar mis Comentarios';
  $contenidoPrincipal=<<<EOF
    <div class="titulo_editorComents">
        <h1>Editar mis Comentarios</h1>
    </div>
    <div class="contenedor_editorComents">
        $formEditorComents
    </div>
  EOF;
}
else{
 
  $formEditorComentsAdmin = new FormularioEditaComentAdmin($comentario_id);
  $formEditorComentsAdmin = $formEditorComentsAdmin->gestiona();

  $tituloPagina = 'Editor de Comentarios';
    $contenidoPrincipal=<<<EOF
    <div class="titulo_editorComentarios">
        <h1>Admin: Editor de Comentarios</h1>
    </div>
    <div class="contenedor_editorComentarios">
        $formEditorComentsAdmin
    </div>
  EOF;
}


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);