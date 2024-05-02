<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\peliculas\Pelicula;
use es\ucm\fdi\aw\listas\FormularioCreaLista;
use es\ucm\fdi\aw\listas\Lista;



$tituloPagina = 'Contenido';
$contenidoPrincipal='';

if ($app->usuarioLogueado()) {
  //Cargamos las listas del usuario
  $listas = Lista::getListasUser($_SESSION['idUsuario']);
  if($listas == FALSE){
    $contenidoPrincipal = <<<EOS
      <div class="contenedor-titulo-icono">
          <h2 class="tituloMisListas">Mis Listas de Películas</h2>
          <a href="crearLista.php" class="crearListaIcono">+</a>
      </div>
      <p>No has creado ninguna lista todavía.</p>
  EOS;


  }
  else{
    //Mostramos al usuario las listas que ha creado
    $contenidoPrincipal = <<<EOS
    <div class="contenedor-titulo-icono">
        <h2 class="tituloMisListas">Mis Listas de Películas</h2>
        <a href="crearLista.php" class="crearListaIcono">+</a>
    </div>
    <p>No has creado ninguna lista todavía.</p>
EOS;
    $contenidoPrincipal .= '<table id="table" class="admin-usuarios-table"><thead><tr><th>Nombre_lista</th><th>Películas en la lista</th><th>Acción</th></tr></thead><tbody>';
    foreach($listas as $lista){
      $lista_id = $lista['lista_id'];
      $nombre_lista = $lista['nombre_lista'];
      $num_peliculas = Lista::getNumPeliculasLista($lista_id);

      $deleteForm = <<<form
             <form class="form-eliminar" method='POST' action='includes/src/listas/eliminaListaPeliculas.php' onsubmit='return confirm(\"¿Estás seguro de que quieres eliminar esta película de la lista?\");'> 
                <input type='hidden' name='lista_id' value='{$lista_id}'>
                <input type='submit' value='Eliminar'>
            </form>
form;

      $redirigeForm = <<<form
        <form class="form-ver-modificar" method='POST' action='ver_lista.php?id=$lista_id'> 
          <input type='submit' value='Ver/Modificar'>
      </form>
form;


      $contenidoPrincipal .= <<<EOS
                            <tr>
                              <td>{$nombre_lista}</td>
                              <td>{$num_peliculas}</td>
                              <td>
                                $deleteForm
                                $redirigeForm
                              </td>
                            </tr>
      EOS;
    }
    $contenidoPrincipal .= '</tbody></table>';    
  }


  $contenidoPrincipal .= '<script type="text/javascript" src="./js/main.js"></script>';

} else {
  $contenidoPrincipal=<<<EOS
    <h3>Mis Listas</h3>
    <p>Debes iniciar sesión para poder crear Listas de Películas.</p>
  EOS;
}


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);

