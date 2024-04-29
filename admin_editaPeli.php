<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\peliculas\FormularioEditaPeli;

$pelicula_id = filter_input(INPUT_POST, 'pelicula_id', FILTER_SANITIZE_NUMBER_INT);

if (empty($pelicula_id)) {
    echo "Error: La película no puede ser identificada.";
    exit();
}

$formCambioDatos = new FormularioEditaPeli($pelicula_id);
$formCambioDatos = $formCambioDatos->gestiona();


$tituloPagina = 'Cambiar Datos de la Película';
$contenidoPrincipal=<<<EOF
  <div class="titulo_cambiarDatosPel">
      <h1>Cambiar Datos de la Película</h1>
  </div>
  <div class="contenedor_cambiarDatosPel">
      $formCambioDatos
  </div>
EOF;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);