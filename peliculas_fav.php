<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/peliculas/Pelicula.php'; // Adjust the path as necessary
require_once __DIR__.'/src/favoritos/Favoritos.php';

$tituloPagina = 'Favoritas';
$contenidoPrincipal='';

if ($app->usuarioLogueado()) {
  $user_id=$app->getUsuarioId();
  
  $favoritos= \es\ucm\fdi\aw\favoritos\Favorito::buscaPorUser($user_id);
  if($favorito){
    $contenidoPrincipal .= '<div class="peliculas-container">';
    foreach ($favoritos as $favorito) {
      $pelicula= \es\ucm\fdi\aw\peliculas\Pelicula::buscaPorId($favorito[$pelicula_id]);
      if ($pelicula){
        $id = $pelicula['id'];
        $titulo = $pelicula['titulo'];
        $imagen = $pelicula['imagen'];
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
      $contenidoPrincipal=<<<EOS
        <h2>Texto del contenido principal para usuarios</h2>
        <p>Aquí aparecerán todas las películas que has marcado como favoritas.</p>
      EOS;
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