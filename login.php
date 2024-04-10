<?php

require_once __DIR__.'/includes/config.php';

$formLogin = new \es\ucm\fdi\aw\usuarios\FormularioLogin();
$formLogin = $formLogin->gestiona();


$tituloPagina = 'Login';
$contenidoPrincipal = <<<EOF
        <div class="contenedor-login">
            <h1>Iniciar Sesión</h1>
        </div>
        <div class="login-formulario">
            $formLogin
        </div>
    EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal, 'cabecera' => 'Login'];
$app->generaVista('/plantillas/plantilla.php', $params);