<?php
require_once __DIR__.'/includes/config.php';

use \es\ucm\fdi\aw\peliculas\Pelicula;


$tituloPagina = 'Contenido';
$contenidoPrincipal='';

if ($app->usuarioLogueado()) {
  //Cargamos las listas del usuario
  $listas = Pelicula::getListasUser($_SESSION['idUsuario']);
  if($listas == FALSE){
    $contenidoPrincipal= <<<EOS
    <h2>Mis listas de películas</h2>
    <p>No has creado ninguna lista todavía</p>
    EOS;
  }
  else{
    //Mostramos al usuario las listas que ha creado
    $contenidoPrincipal= '<h2>Mis listas de películas</h2>';
    foreach($listas as $lista){
      
    }
    
  EOS;
  }

} else {
  $contenidoPrincipal=<<<EOS
    <h2>Usuario no registrado!</h2>
    <p>Debes iniciar sesión para ver el contenido.</p>
  EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);