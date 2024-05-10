<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\peliculas\Pelicula;



class FormularioBuscaPel extends Formulario
{
    public function __construct() {
        parent::__construct('formBuscaPel', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/busqueda.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
         //Obtenemos los géneros de las películas
         $generos = Pelicula::getGeneros();
        
         // Se generan los mensajes de error si existen.
         $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
         $erroresCampos = self::generaErroresCampos(['tituloPelicula', 'generoPelicula', 'annioPelicula', 'directorPelicula'], $this->errores, 'span', array('class' => 'error'));
 
         // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
         $html = <<<EOS
         $htmlErroresGlobales
         <div class="buscador">
             <h1>Buscador de Películas</h1>
             <form id="formBuscaPel" action="{$this->action}" method="POST">
                <div class="campos-container">
                     <div class="buscador-campo-titulo">
                         <label for="tituloPelicula">Título:</label>
                         <input id="tituloPelicula" type="text" name="tituloPelicula" onkeyup="buscarPeliculas()"/>
                     </div>
                     
                     <div class="buscador-campo-director">
                        <label for="directorPelicula">Director:</label>
                        <input id="directorPelicula" type="text" name="directorPelicula" onkeyup="buscarPeliculas()"/>
                    </div>
                    <div class="buscador-campo-anio">
                        <label for="annioPelicula">Año de estreno:</label>
                        <input id="annioPelicula" type="text" name="annioPelicula" onkeyup="buscarPeliculas()"/>
                    </div>
                    <div class="buscador-campo-genero">
                         <label for="generoPelicula">Género:</label>
                         <select id="generoPelicula" name="generoPelicula" onchange="buscarPeliculas()">
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
                     
             </form>
         </div>
         <div id="resultadoBusqueda"></div>
         
            <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
            <script type="text/javascript" src="js/busqueda.js"></script>
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


    if ($annioPelicula === '0') {
        // Limpiar la variable de sesión de búsqueda de películas
        unset($_SESSION['busquedaPeliculas']);
        $this->errores[] = "Introduce un año de estreno válido";
    } else {
        if (count($this->errores) === 0) {
            $peliculas = Pelicula::buscaPelicula($tituloPelicula, $directorPelicula, $generoPelicula, $annioPelicula);
        
            if ($peliculas === false) {
                $this->errores[] = "No existen películas con esos criterios de búsqueda";
            } else {
                //Mostramos las películas encontradas
                $_SESSION['busquedaPeliculas'] = $peliculas;
            }
        }
    }
}

}