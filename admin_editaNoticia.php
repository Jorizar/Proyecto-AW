<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\noticias\FormularioEditaNoticia;

if (!$app->tieneRol('admin')) {
    echo "Acceso restringido solo a administradores.";
    exit();
}


$id_noticia = filter_input(INPUT_POST, 'noticia_id', FILTER_SANITIZE_NUMBER_INT);

if (empty($id_noticia)) {
    echo "Error: La noticia no puede ser identificada.";
    exit();
}

$formCambioDatos = new FormularioEditaNoticia($id_noticia);
$formCambioDatos = $formCambioDatos->gestiona();


$tituloPagina = 'Cambiar Datos de la Noticia';
$contenidoPrincipal=<<<EOF
  <div class="titulo_cambiarDatosNoticia">
      <h1>Cambiar Datos de la Noticia</h1>
  </div>
  <div class="contenedor_cambiarDatosNoticia">
      $formCambioDatos
  </div>
EOF;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);