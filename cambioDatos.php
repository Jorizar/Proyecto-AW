<?php

require_once __DIR__.'/includes/config.php';

$formCambioDatos = new \es\ucm\fdi\aw\usuarios\FormularioCambioDatos();
$formCambioDatos = $formCambioDatos->gestiona();


$tituloPagina = 'Cambiar Datos';
$contenidoPrincipal=<<<EOF
  	<h1>Acceso al sistema</h1>
    $formCambioDatos
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);