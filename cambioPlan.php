<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\usuarios\FormularioCambioPlan;

$formCambioPlan = new FormularioCambioPlan();
$formCambioPlan = $formCambioPlan->gestiona();



$tituloPagina = 'Cambiar Plan';
$contenidoPrincipal=<<<EOF
<div class="titulo_cambiarPlan">
    <h1>Cambiar Plan</h1>
</div>
<div class="contenedor_cambiarPlan">
    $formCambioPlan
</div>


  	
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);