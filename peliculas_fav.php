<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Favoritas';
$contenidoPrincipal='';

if ($app->usuarioLogueado()) {
  $contenidoPrincipal=<<<EOS
    <h2>Texto del contenido principal para usuarios</h2>
    <p>Aquí aparecerán todas las películas que has marcado como favoritas.</p>
  EOS;
} else {
  $contenidoPrincipal=<<<EOS
    <h2>Usuario no registrado!</h2>
    <p>Debes iniciar sesión para ver el contenido.</p>
  EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);