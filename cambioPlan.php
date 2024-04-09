<?php

require_once __DIR__.'/includes/config.php';

$formCambioPlan = new \es\ucm\fdi\aw\usuarios\FormularioCambioPlan();
$formCambioPlan = $formCambioPlan->gestiona();



$tituloPagina = 'Cambiar Plan';
$contenidoPrincipal=<<<EOF
  	<h1>Cambiar Plan</h1>
    $formCambioPlan
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);