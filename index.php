<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Portada';
$login = resuelve('/login.php');
$contenidoPrincipal=<<<EOS
  <h2>Página principal</h2>
  <p> Aquí está el contenido público, visible para todos los usuarios. </p>
  <a href="${login}">Login</a>
  <form action="pelicula.php" method="get">
    <input type="hidden" name="id" value="1">
    <button type="submit">Go to Movie</button>
  </form>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
