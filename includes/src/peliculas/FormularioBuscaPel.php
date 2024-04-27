<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\peliculas\Pelicula;



class FormularioBuscaPel extends Formulario
{
    //TO DO
    public function __construct() {
        parent::__construct('formBuscaPel', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/busqueda.php')]);
    }
    
    //TO DO
    protected function generaCamposFormulario(&$datos)
    {
         //Obtenemos los géneros de las películas
         $generos = Pelicula::getGeneros();
        
         // Se generan los mensajes de error si existen.
         $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
 
         // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
         $html = <<<EOS
         $htmlErroresGlobales
            <div class="campos-container">
                     <div class="buscador-campo-titulo">
                         <label for="tituloPelicula">Título:</label>
                         <input id="tituloPelicula" type="text" name="tituloPelicula"/>
                     </div>
                     <div class="buscador-campo-director">
                        <label for="directorPelicula">Director:</label>
                        <input id="directorPelicula" type="text" name="directorPelicula"/>
                    </div>
                    <div class="buscador-campo-anio">
                        <label for="annioPelicula">Año de estreno:</label>
                        <input id="annioPelicula" type="text" name="annioPelicula"/>
                    </div>
                    <div class="buscador-campo-genero">
                         <label for="generoPelicula">Género:</label>
                         <select id="generoPelicula" name="generoPelicula">
                         <option value="-1">Seleccionar</option>
        EOS;
                 if($generos != FALSE){
                    foreach ($generos as $id => $genero){
                        $html .= "<option value='$id'>$genero</option>";
                    }
                    $html .= "</select></div>";
                 }
            $html .= <<<EOF
                     <div class="buscador-boton">
                         <button type="submit" name="buscar">Buscar</button>
                     </div>
         </div>
        EOF;
         return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        // Recoger los datos del formulario
        $tituloPelicula = isset($datos['tituloPelicula']) ? $datos['tituloPelicula'] : '';
        $directorPelicula = isset($datos['directorPelicula']) ? $datos['directorPelicula'] : '';
        $generoPelicula = ($datos['generoPelicula'] != -1) ? $datos['generoPelicula'] : '';
        $annioPelicula = isset($datos['annioPelicula']) ? $datos['annioPelicula'] : '';

        if (count($this->errores) === 0) {
            //$peliculas contiene un array de películas si la búsqueda ha encontrado alguna coincidencia, false en caso contrario
            $peliculas = Pelicula::buscaPelicula($tituloPelicula, $directorPelicula, $generoPelicula, $annioPelicula);
        
            if ($peliculas === false) {
                $this->errores[] = "No existen películas con esos criterios de búsqueda";
            } else {
                //Almacenamos los ids de las películas encontradas en $_SESSION
                $_SESSION['busquedaPeliculas'] = $peliculas;
            }
        }
    }
}

