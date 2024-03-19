<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Mis Comentarios';
$contenidoPrincipal='';

if ($app->usuarioLogueado()) {
  $contenidoPrincipal=<<<EOS
    <h2>Comentarios que has publicado</h2>
    <p>Aquí aparecerán todos los comentarios que has dejado en las películas de nuestra web.</p>
  EOS;
} else {
  $contenidoPrincipal=<<<EOS
    <h2>Usuario no registrado!</h2>
    <p>Debes iniciar sesión para ver el contenido.</p>
  EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);