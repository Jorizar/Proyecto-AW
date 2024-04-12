<?php

require_once __DIR__.'/includes/config.php';

$formCambioDatos = new \es\ucm\fdi\aw\usuarios\FormularioCambioDatos();
$formCambioDatos = $formCambioDatos->gestiona();


$tituloPagina = 'Cambiar Datos';
$contenidoPrincipal=<<<EOF
  <div class="titulo_cambiarDatos">
      <h1>Cambiar Datos</h1>
  </div>
  <div class="contenedor_cambiarDatos">
      $formCambioDatos
  </div>
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);