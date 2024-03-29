<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Portada';
$login = resuelve('/login.php');
$contenidoPrincipal=<<<EOS
  <h2>Página principal</h2>
  <p> Aquí está el contenido público, visible para todos los usuarios. </p>
  <a href="${login}">Login</a>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);