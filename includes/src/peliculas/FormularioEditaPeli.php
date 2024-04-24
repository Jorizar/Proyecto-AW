<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\peliculas\Pelicula;

class FormularioEditaPeli extends Formulario
{
    public function __construct($pelicula_id) {
        parent::__construct('formCambioDatos', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/perfil.php'), 'enctype' => 'multipart/form-data']);
        $this->pelicula_id = $pelicula_id;
    }
    
    protected function generaCamposFormulario(&$datos)
    {
            //Obtenemos los géneros de las películas
        $generos = Pelicula::getGeneros();

        $erroresCampos = self::generaErroresCampos(['tituloPelicula', 'directorPelicula', 'annioPelicula', 'generoPelicula',  'sinopsis', 'portada', 'reparto', 'imdb'], $this->errores, 'span', array('class' => 'error'));

        // Se generan los campos del formulario para cambiar los datos del usuario.
        $html = <<<EOF
        <div class="titulo_cambiarDatosPel">
        </div>
        <div class="contenedor_cambiarDatosPel">
            <div class="cambiar-datos-peli-formulario">
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
                        <label for="portada">Portada de la Pelicula:</label>
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
                    
        EOF;

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
                <div>
                    <button type="submit" name="cambiar_datos">Cambiar Datos</button>
                </div>
            </div>
        </div>

        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
    // Podriamos obtener la pelicula con el id y hacer cambios con esa variable en vd
            
            // Obtiene los nuevos datos introducidos por el usuario
            $nuevoTitulo = trim($datos['tituloPelicula'] ?? '');
            $nuevoTitulo = filter_var($nuevoTitulo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoTitulo)){
                //Comprobamos que no exista una pelicula con ese titulo
                $pelicula = Pelicula::buscaPorTitulo($nuevoTitulo); 
               
                if($pelicula){
                    error_log('titulo_duplicado');
                    $this->errores['tituloPelicula'] = 'Ya existe una pelicula con ese titulo';
                }
                else{
                    $result = Pelicula::cambiarTítulo($pelicula_id,$nuevoTitulo);
                }
            }
            //NUEVO DIRECTOR ?
            $nuevoDirector = trim($datos['directorPelicula'] ?? '');
            $nuevoDirector = filter_var($nuevoDirector, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoTitulo)){
                $result = Pelicula::cambiarDirector($pelicula_id,$nuevoDirector);   
            }

            //NUEVO AÑO ?
            $nuevoAnnio = trim($datos['annioPelicula'] ?? '');
            $nuevoAnnio = filter_var($nuevoAnnio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoAnnio)){
                $year = date('Y');
                if($nuevoAnnio <= $year){
                $result = Pelicula::cambiarAnnio($pelicula_id,$nuevoAnnio); 
                }
                else{
                    $this->errores['annioPelicula'] = 'El año introducido no es valido';
                }  
            }

            //NUEVA SINOPSIS ?
            $nuevaSinopsis = trim($datos['sinopsisPelicula'] ?? '');
            $nuevaSinopsis = filter_var($nuevaSinopsis, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevaSinopsis)){
                $result = Pelicula::cambiarSinopsis($pelicula_id,$nuevaSinopsis); 
            }

            //NUEVA NOTA IMdB ?
            $nuevoIMdB = trim($datos['imdb'] ?? '');
            $nuevoIMdB = filter_var($nuevoIMdB, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoIMdB)){
                if($nuevoIMdB <= 10.0){
                $result = Pelicula::cambiarImdb($pelicula_id,$nuevoIMdB); 
                }
                else{
                    $this->errores['imdb'] = 'El valor introducido no es valido';
                }  
            }

            $generoPelicula = ($datos['generoPelicula'] != -1) ? $datos['generoPelicula'] : '';
            if (!empty($generoPelicula)){
                $result = Pelicula::cambiarGenero($pelicula_id,$generoPelicula); 
            }
            
    
            //Procesamo la portada que ha escogido cargar el usuario
            if(isset($_FILES['portada'])){
                $filetype = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . "." . $filetype;
                $targetFilePath = 'img/portadas/'.$filename;
                $filesize = $_FILES['portada']['name'];
                
                if($filesize > 1000000){
                    $this->errores['portada'] = 'La imagen no puede ocupar más de 1 MB';
                }
    
                $allowTypes = array('jpg', 'png', 'jpeg');
                if(in_array($filetype, $allowTypes)){ //Comprobamos que la extensión de la imagen se ajusta a las requeridas
                    if(move_uploaded_file($_FILES['portada']["tmp_name"], $targetFilePath)) {
                        //Actualizar la foto en la base de datos
                        $result = Pelicula::actualizaPortada($pelicula_id, $targetFilePath);

                        //Actualizar la foto en la sesión
                        $_SESSION['fotoPerfil'] = $targetFilePath;
                    } else {
                        $this->errores['portada'] = 'Hubo un error al subir el fichero';
                    }
                }
                else{
                    $this->errores['portada'] = 'La imagen que ha seleccionado debe tener extensión .jpg, .png o .jpeg'; 
                }

            }

            
    }
}
