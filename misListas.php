<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Contenido';
$contenidoPrincipal='';

if ($app->usuarioLogueado()) {
  $contenidoPrincipal=<<<EOS
    <h2>Mis listas de películas</h2>
    <p>Aquí podrás crear listas de películas, así como eliminar y agregar películas a estas</p>
  EOS;
} else {
  $contenidoPrincipal=<<<EOS
    <h2>Usuario no registrado!</h2>
    <p>Debes iniciar sesión para ver el contenido.</p>
  EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);