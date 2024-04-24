<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioAgregaPel extends Formulario
{
    //TO DO
    public function __construct() {
        parent::__construct('formAgregaPel', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    //TO DO
    protected function generaCamposFormulario(&$datos)
    {

         //Obtenemos los géneros de las películas
    $generos = Pelicula::getGeneros();

     // Se generan los mensajes de error si existen.
    $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
    $erroresCampos = self::generaErroresCampos(['tituloPelicula', 'directorPelicula', 'annioPelicula', 'generoPelicula',  'sinopsis', 'portada', 'reparto', 'imdb'], $this->errores, 'span', array('class' => 'error'));

   
         // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
         $html = <<<EOS
         $htmlErroresGlobales
         <div class="agrega-pelicula">
             <h1>Añadir Película</h1>
             <form id="formAddPel" action="{$this->action}" method="POST">

                <div class="campos-container">
                     <div class="add-campo-titulo">
                         <label for="tituloPelicula">Título:</label>
                         <input id="tituloPelicula" type="text" name="tituloPelicula"/>
                     </div>
                     <div class="add-campo-director">
                        <label for="directorPelicula">Director:</label>
                        <input id="directorPelicula" type="text" name="directorPelicula"/>
                    </div>
                    <div class="add-campo-anio">
                        <label for="annioPelicula">Año de estreno:</label>
                        <input id="annioPelicula" type="text" name="annioPelicula"/>
                    </div>
                    <div class="portada-container">
                        <label for="portada">portada de la Pelicula:</label>
                        <input type="file" id="portada" name="portada" accept="image/*" multiple="false">
                    </div>
                    <div class="add-campo-sinopsis">
                        <label for="sinopsisPelicula">Sinopsis:</label>
                        <textarea id="sinopsisPelicula" name="sinopsisPelicula" rows="5"></textarea>
                    </div>
                    <div class="add-campo-reparto">
                        <label for="reparto">Actores/Personajes (separados por comas entre  diferentes actores):</label>
                        <input id="reparto" type="text" name="reparto"/>
                    </div>
                    <div class="add-campo-genero">
                         <label for="generoPelicula">Género:</label>
                         <select id="generoPelicula" name="generoPelicula">
                         <option value="-1">Seleccionar</option>
                    </div>
                </div>
                    
        EOS;

                 if($generos != FALSE){
                    foreach ($generos as $id => $genero){
                        $html .= "<option value='$id'>$genero</option>";
                    }
                    $html .= "</select></div>";
                 }
            $html .= <<<EOF
                <div class="add-campo-imdb">
                    <label for="imdb">Puntuacion en IMdB:</label>
                    <input id="imdb" type="text" name="imdb"/>
                </div>
                     
                     <div class="anyadir-boton">
                         <button type="submit" name="add">Añadir</button>
                     </div>
             </form>
         </div>
        EOF;
         return $html; 
    }
    
    //TO DO
    protected function procesaFormulario(&$datos)
    {
        // Recoger los datos del formulario
        $tituloPelicula = isset($datos['tituloPelicula']) ? $datos['tituloPelicula'] : '';
        $directorPelicula = isset($datos['directorPelicula']) ? $datos['directorPelicula'] : '';
        $generoPelicula = ($datos['generoPelicula'] != -1) ? $datos['generoPelicula'] : '';
        $annioPelicula = isset($datos['annioPelicula']) ? $datos['annioPelicula'] : '';
        $sinopsisPelicula = isset($datos['sinopsisPelicula']) ? $datos['sinopsisPelicula'] : '';
        $repartoPelicula = isset($datos['reparto']) ? $datos['reparto'] : '';
        $imdbPelicula = isset($datos['imdb']) ? $datos['imdb'] : '';

         // Comprobamos si exxiste una pelicula con ese mismo titulo
         $peliculas = Pelicula::buscaPorTitulo($tituloPelicula);
         if ($peliculas === true) {
             $this->errores[] = "Ya existe una película con ese nombre, prueba de nuevo";
         } 
         // lOS DATOS ESTAN VACIOS?
         if($directorPelicula === '' || $annioPelicula === '' || $sinopsisPelicula === '' || $repartoPelicula === '' || $generoPelicula === ''|| $imdbPelicula === ''){
            $this->errores[] = "No puede haber campos vacios, rellene todos los campos";    
         }

         //Procesamos el formato del reparto

         $repartoOK =self:: procesarActores($repartoPelicula);
         if ($repartoOK == false) {
            $this->errores[] = "ERROR:";   
        }
    
        
         // Procesamos la portada de la película
        if(isset($_FILES['portada'])){
            $filetype = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $filetype;
            $targetFilePath = 'img/portadas/' . $filename;
            $filesize = $_FILES['portada']['size'];
            
            if($filesize > 1000000){
                    $this->errores['portada'] = 'La imagen no puede ocupar más de 1 MB';
                }

            $allowTypes = array('jpg', 'png', 'jpeg');
            if(in_array($filetype, $allowTypes)){ // Comprobamos que la extensión de la imagen se ajusta a las requeridas
                if(move_uploaded_file($_FILES['portada']["tmp_name"], $targetFilePath)) {
                    // Guardar la ruta de la portada en la variable $portadaPelicula
                    $portadaPelicula = './' . $targetFilePath;
                } else {
                    $this->errores['portada'] = 'Hubo un error al subir el fichero';
                }
            } else {
                $this->errores['portada'] = 'La imagen que ha seleccionado debe tener extensión .jpg, .png o .jpeg'; 
            }
        }

        if (count($this->errores) === 0) {
            //$peliculas contiene un array de películas si la búsqueda ha encontrado alguna coincidencia, false en caso contrario
            $peliculas = Pelicula::crea($tituloPelicula, $directorPelicula, $annioPelicula, $generoPelicula, $sinopsisPelicula, $portadaPelicula, $repartoOK, $imdbPelicula);

        
            if ($peliculas === false) {
                $this->errores[] = "No se ha podido crear la pelicula";
            } else {
                header("Location: {$_SERVER['PHP_SELF']}");
                exit;
            }

        }

    }

    function procesarActores($entrada) {
        $limite = 10;

        // Comprobamos que la entrada cumpla con el formato requerido
        if (!preg_match('/^(?:\w+\s\w+\/\w+\s\w+(?:,\s\w+\s\w+\/\w+\s\w+)*)$/', $entrada)) {
            $this->errores[] = "Formato de entrada de actores incorrecto. (Ej:Pau Gasol/Bugs Bunny, etc.)";
        }
    
        // Convertimos la entrada en un array de nombres
        $actores = explode(',', $entrada);
    

        if (count($actores) > $limite) {
            $this->errores[] = "Demasiados nombres, como máximo 10 actores.";
        }
    
    
        $resultado = [];
        foreach ($actores as $nombre) {
            list($nombreCompleto, $personaje) = explode('/', trim($nombre));
            $resultado[] = ['nombre' => trim($nombreCompleto), 'personaje' => trim($personaje)];
        }
    
        return json_encode($resultado, JSON_PRETTY_PRINT);
    }

}