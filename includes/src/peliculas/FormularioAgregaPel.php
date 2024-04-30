<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioAgregaPel extends Formulario
{
    //TO DO
    public function __construct() {
        parent::__construct('formAgregaPel', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php'), 'enctype' => 'multipart/form-data']);
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
            </div>
                <div class="campos-container">
                     <div class="add-campo-titulo">
                         <label for="tituloPelicula">Título:</label>
                         <input id="tituloPelicula" type="text" name="tituloPelicula"  required/>
                         {$erroresCampos['tituloPelicula']}
                         </div>
                     <div class="add-campo-director">
                        <label for="directorPelicula">Director:</label>
                        <input id="directorPelicula" type="text" name="directorPelicula"    required/>
                        {$erroresCampos['directorPelicula']}
                    </div>
                    <div class="add-campo-anio">
                        <label for="annioPelicula">Año de estreno:</label>
                        <input id="annioPelicula" type="text" name="annioPelicula"   placeholder="XXXX"     required/>
                        {$erroresCampos['annioPelicula']}
                    </div>
                    <div class="add-campo-portada">
                        <label for="portada">Portada de la Pelicula:</label>
                        <input id="portada" type="file"  name="portada" accept="image/*" multiple="false"    required >
                    </div>
                    <div class="add-campo-sinopsis">
                        <label for="sinopsisPelicula">Sinopsis:</label>
                        <textarea id="sinopsisPelicula" name="sinopsisPelicula"   placeholder="Añada aqui la sinopsis"   rows="5"   required></textarea>
                        {$erroresCampos['sinopsisPelicula']}
                    </div>
                    <div class="add-campo-imdb">
                        <label for="imdb">Puntuacion en IMdB:</label>
                        <input id="imdb" type="text" name="imdb"  placeholder="XX.X"  required/>
                        {$erroresCampos['imdb']}
                    </div>

                    <div class="add-campo-genero">
                        <label for="generoPelicula">Género:</label>
                        <select id="generoPelicula" name="generoPelicula">
                        <option value="-1">Seleccionar</option>
                    </div>
                
        EOS;
                 if($generos != FALSE){
                    foreach ($generos as $id => $genero){
                        $html .= "<option value='$id'>$genero</option>";
                    }
                    $html .= "</select></div>";
                 }
            $html .= <<<EOF
                 
                    <div class="add-campo-reparto">
                    <label>Actores/Personajes:</label>
                    <div id="reparto-container">
                        <div class="reparto-item">
                            <input type="text" name="actor[]" placeholder="Nombre del actor" required>
                            <input type="text" name="personaje[]" placeholder="Personaje" required>
                            <button type="button" class="eliminar-campo">Eliminar</button>
                        </div>
                    </div>
                    <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const agregarCampoBtn = document.getElementById('agregar-campo');
                                const repartoContainer = document.getElementById('reparto-container');

                                agregarCampoBtn.addEventListener('click', function() {
                                    const nuevoCampo = document.createElement('div');
                                    nuevoCampo.classList.add('reparto-item');
                                    nuevoCampo.innerHTML = `
                                        <input type="text" name="actor[]" placeholder="Nombre del actor" required>
                                        <input type="text" name="personaje[]" placeholder="Personaje" required>
                                        <button type="button" class="eliminar-campo">Eliminar</button>
                                    `;
                                    repartoContainer.appendChild(nuevoCampo);
                                });

                                repartoContainer.addEventListener('click', function(event) {
                                    if (event.target.classList.contains('eliminar-campo')) {
                                        event.target.parentElement.remove();
                                    }
                                });
                            });
                    </script>

                    <button type="button" id="agregar-campo">Agregar Actor/Personaje</button>
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
        $repartoPelicula = isset($datos['actor']) ? $datos['actor'] : [];
        $personajesPelicula = isset($datos['personaje']) ? $datos['personaje'] : [];
        $imdbPelicula = isset($datos['imdb']) ? $datos['imdb'] : '';
        $portadaPelicula = isset($datos['portada']) ? $datos['portada']: '';

         // Comprobamos si exxiste una pelicula con ese mismo titulo
         $peliculas = Pelicula::buscaPorTitulo($tituloPelicula);
         if ($peliculas === true) {
             $this->errores[] = "Ya existe una película con ese nombre, prueba de nuevo";
         } 
         // lOS DATOS ESTAN VACIOS?
         if($directorPelicula === '' || $annioPelicula === '' || $sinopsisPelicula === '' || $repartoPelicula === '' || $generoPelicula === ''|| $imdbPelicula === ''){
            $this->errores[] = "No puede haber campos vacios, rellene todos los campos";    
         }

        // Procesamos el formato del reparto
        $repartoOK = $this->procesarActores($repartoPelicula, $personajesPelicula);
        if (!$repartoOK) {
            $this->errores[] = "Error al procesar el reparto";
        }
    
        
         // Procesamos la portada de la película
        if($portadaPelicula != ''){
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
            echo ''.$portadaPelicula.'';
        }
        else{
            $this->errores[] = "ERROR: La portada es obligatoria.";
        }

        if (count($this->errores) === 0) {
            //$peliculas contiene un array de películas si la búsqueda ha encontrado alguna coincidencia, false en caso contrario
            $peliculas = Pelicula::crea($tituloPelicula, $directorPelicula, $annioPelicula, $generoPelicula, $sinopsisPelicula, $portadaPelicula, $repartoOK, $imdbPelicula);
        
            if ($peliculas === false) {
                echo "Error: No se ha podido crear la pelicula";
                exit();
            } else {
                $relativePath = '/AW/Proyecto-AW/admin_peliculas.php';
                header('Location: ' . $relativePath);
                exit();
            }
        }

    }

   // Método para procesar el formato del reparto
    protected function procesarActores($actores, $personajes)
    {
        $limite = 10;
        $resultado = [];

        if (count($actores) !== count($personajes)) {
            return false; // Número de actores y personajes no coincide
        }

        if (count($actores) > $limite) {
            return false; // Demasiados nombres
        }

        foreach ($actores as $indice => $nombre) {
            $resultado[] = ['nombre' => trim($nombre), 'personaje' => trim($personajes[$indice])];
        }

        // Convertir el resultado a JSON con el formato especificado
        $jsonReparto = json_encode($resultado, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        // Reemplazar los saltos de línea por \r\n
        $jsonReparto = str_replace(["\r\n", "\n", "\r"], '\r\n', $jsonReparto);

        return $jsonReparto;
    }


}