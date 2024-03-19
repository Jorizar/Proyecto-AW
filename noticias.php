<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Noticias';
$contenidoPrincipal= <<<EOS
<h2>Noticias</h2>
<p>Aquí estarán las noticias de la web publicadas.</p>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);