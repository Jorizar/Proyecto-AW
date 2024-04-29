<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\listas\Lista;
use es\ucm\fdi\aw\peliculas\Pelicula;


$tituloPagina = 'Mi Lista de Películas';
$contenidoPrincipal = '<h2>Peliculas de la lista</h2>';

if(isset($_GET['id'])) {
    // Obtener el valor del parámetro 'id'
    $id_lista = $_GET['id'];
    
    //Obtenemos los ids de las películas de la lista
    $peliculas_id = Lista::getPeliculasLista($id_lista);

    if(count($peliculas_id) > 0){
        //Obtenemos toda la información de las peliculas
        $peliculas = array();

        //Mostramos las películas de la lista
        $contenidoPrincipal .= '<div class="peliculas-container">';

        foreach($peliculas_id as $pelicula_id){
            $peliculas[] = Pelicula::buscaPorId($pelicula_id);
        }
            foreach ($peliculas as $pelicula) {
                $id = $pelicula->getId();
                $titulo = $pelicula->getTitulo();
                $imagen = $pelicula->getPortada();

                $deleteForm = <<<form
                 <form method='POST' action='includes/src/listas/eliminaPeliculaLista.php' onsubmit='return confirm(\"¿Estás seguro de que quieres eliminar esta película de la lista?\");'> 
                    <input type='hidden' name='pelicula_id' value='{$id}'>
                    <input type='hidden' name='lista_id' value='{$id_lista}'>
                    <input type='submit' value='Eliminar'>
                </form>
    form;
                // Enlace por cada película que redirige a la vista de la película
                $contenidoPrincipal .= <<<EOS
                    <div class="pelicula">
                        <a href="vista_pelicula.php?id=$id">
                            <img src="$imagen" alt="$titulo">
                            <span>$titulo</span>
                        </a>
                        $deleteForm
                  </div>
                EOS;    
            }
        $contenidoPrincipal .= '</div>';
    }
    else{
        $contenidoPrincipal .= "<p>Actualmente la lista se encuentra vacía.</p>";
    }
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
