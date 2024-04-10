<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/peliculas/Pelicula.php'; // Adjust the path as necessary
require_once __DIR__.'/includes/src/favoritos/Favoritos.php'; // Adjust the path as necessary

//use \es\ucm\fdi\aw\includes\src\favoritos\Favorito;

$tituloPagina = 'Favoritas';
$contenidoPrincipal='';

if ($app->usuarioLogueado()) {
  $user_id=$app->getUsuarioId();
  $favoritos=array();
  $favoritos= \es\ucm\fdi\aw\favoritos\Favorito::buscaPorUser($user_id);

  if($favoritos){
    $contenidoPrincipal .= '<div class="peliculas-container">';
    $contenidoPrincipal .=<<<EOS
      <h2>Texto del contenido principal para usuarios</h2>
      <p>Aquí aparecerán todas las películas que has marcado como favoritas.</p><br><br>
    EOS;
    foreach ($favoritos as $favorito) {
      $pelicula= \es\ucm\fdi\aw\peliculas\Pelicula::buscaPorId($favorito);
      if ($pelicula){
        $id = $pelicula->getId();
        $titulo = $pelicula->getTitulo();
        $imagen = $pelicula->getPortada();
        // Formulario por cada película con un botón que redirija a la vista de la película
        $contenidoPrincipal .= <<<HTML
            <form action="vista_pelicula.php" method="get">
                <input type="hidden" name="id" value="$id">
                <button type="submit">
                    <img src="$imagen" alt="$titulo">
                    <span>$titulo</span>
                </button>
            </form>
          HTML;

      }
    }
  } else{
    $contenidoPrincipal.= "<p>No hay favoritos</p>";
  }

} else {
  $contenidoPrincipal=<<<EOS
    <h2>Usuario no registrado!</h2>
    <p>Debes iniciar sesión para ver el contenido.</p>
  EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);