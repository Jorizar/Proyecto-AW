<?php

require_once __DIR__.'/includes/config.php';

$formRegistro = new \es\ucm\fdi\aw\usuarios\FormularioRegistro();
$formRegistro = $formRegistro->gestiona();

$tituloPagina = 'Registro';
$contenidoPrincipal = <<<EOF
        <div class="contenedor-registro">
            <h1>Registro de usuario</h1>
        </div>
        <div class="registro-formulario">
            $formRegistro
        </div>
    EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);