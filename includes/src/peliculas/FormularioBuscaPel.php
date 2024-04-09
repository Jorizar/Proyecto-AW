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
         if($generos === FALSE){

         }
         // Se generan los mensajes de error si existen.
         $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
         $erroresCampos = self::generaErroresCampos(['tituloPelicula', 'genero', 'annio', 'director'], $this->errores, 'span', array('class' => 'error'));
 
         // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
         $html = <<<EOF
         $htmlErroresGlobales
         <fieldset>
             <legend>Introduce los datos de la película en los campos que te interesen</legend>
             <div>
                 <label for="tituloPelicula">Título:</label>
                 <input id="tituloPelicula" type="text" name="tituloPelicula"/>
                 {$erroresCampos['tituloPelicula']}
             </div>
             <div>
                 <label for="genero">Género:</label>
                 <select id="generoPelicula" name="generoPelicula">
                 <option value="-1">Seleccionar</option>
                 EOF;
                 foreach ($generos as $id => $genero){
                    $html .= "<option value='$id'>$genero</option>";
                }
            $html .= <<<EOF
                 {$erroresCampos['generoPelicula']}
             </div>
             <div>
                 <label for="directorPelicula">Director:</label>
                 <input id="directorPelicula" type="text" name="directorPelicula"/>
                 {$erroresCampos['directorPelicula']}
             </div>
             <div>
                 <label for="annioPelicula">Año de estreno:</label>
                 <input type="number" id="annioPelicula" name="annioPelicula"/>
                 {$erroresCampos['annioPelicula']}
             </div>
             <div>
                 <button type="submit" name="buscar">Buscar</button>
             </div>
         </fieldset>
         EOF;
         return $html;
    }
    
    //TO DO
    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        $tituloPelicula = filter_var($n, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreUsuario || empty($nombreUsuario) ) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }
        
        // Recoger los datos del formulario
        $titulo = isset($datos['tituloPelicula']) ? $datos['tituloPelicula'] : '';
        $director = isset($datos['directorPelicula']) ? $datos['directorPelicula'] : '';
        $genero = ($datos['generoPelicula'] != -1) ? $datos['generoPelicula'] : '';
        $anno = isset($datos['annioPelicula']) ? $datos['annioPelicula'] : '';

        if (count($this->errores) === 0) {
            //$peliculas contiene un array de películas si la búsqueda ha encontrado alguna coincidencia, false en caso contrario
            $peliculas = Pelicula::buscaPelicula($tituloPelicula, $directorPelicula, $generoPelicula, $annio);
        
            if ($peliculas === false) {
                $this->errores[] = "No existen películas con esos criterios de búsqueda";
            } else {
                //Mostramos las películas encontradas
                
            }
        }
    }
}