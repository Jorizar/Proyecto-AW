<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\peliculas\Pelicula;

class FormularioEditaPeli extends Formulario
{
    protected $pelicula_id;

    public function __construct($pelicula_id) {
        parent::__construct('formCambioDatos', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/admin_peliculas.php'), 'enctype' => 'multipart/form-data']);
        $this->pelicula_id = $pelicula_id;
    }
    
    protected function generaCamposFormulario(&$datos)
    {
            // Obtener la película con el ID proporcionado
        $pelicula = Pelicula::buscaPorId($this->pelicula_id);

        // Si no se encuentra la película, mostrar mensaje de error
        if (!$pelicula) {
            return "No se encontró la película con el ID {$this->pelicula_id}.";
        }

        // Obtener los valores de la película para prellenar los campos del formulario
            $tituloP = $pelicula->getTitulo();
            $directorP = $pelicula->getDirector();
            $annioP = $pelicula->getAnnio();
            $sinopsisP = $pelicula->getSinopsis();
            $imdbP = $pelicula->getVal_IMDb();
            $repartoP = $pelicula->getReparto();


            //Obtenemos los géneros de las películas
        $generos = Pelicula::getGeneros();

        $erroresCampos = self::generaErroresCampos(['tituloPelicula', 'directorPelicula', 'annioPelicula', 'generoPelicula',  'sinopsisPelicula', 'portada', 'reparto', 'imdb'], $this->errores, 'span', array('class' => 'error'));

        $htmlpeliId= '';

        if (!empty($this->pelicula_id)) {
            $htmlpeliId = "<input type='hidden' name='id_peli' value='{$this->pelicula_id}'>";
        }

        // Se generan los campos del formulario para cambiar los datos del usuario.
        $html = <<<EOF
        <div class="titulo_cambiarDatosPel">
        </div>
        <div class="contenedor_cambiarDatosPel">
            <div class="cambiar-datos-peli-formulario">
                {$htmlpeliId}
                <div class="add-campo-titulo">
                    <label for="tituloPelicula">Título:</label>
                    <input id="tituloPelicula" type="text" name="tituloPelicula"  placeholder="$tituloP"/>
                    {$erroresCampos['tituloPelicula']}
                </div>
                <div class="add-campo-director">
                    <label for="directorPelicula">Director:</label>
                    <input id="directorPelicula" type="text" name="directorPelicula"    value="$directorP"/>
                    {$erroresCampos['directorPelicula']}
                </div>
                <div class="add-campo-anio">
                    <label for="annioPelicula">Año de estreno:</label>
                    <input id="annioPelicula" type="text" name="annioPelicula"  value="$annioP"/>
                    {$erroresCampos['annioPelicula']}
                </div>
                <div class="portada-container">
                    <label for="portada">Portada de la Película:</label>
                    <input type="file" id="portada" name="portada" accept="image/*" multiple="false">
                    {$erroresCampos['portada']}
                </div>
                <div class="add-campo-sinopsis">
                    <label for="sinopsisPelicula">Sinopsis:</label>
                    <textarea id="sinopsisPelicula" name="sinopsisPelicula" rows="5">$sinopsisP</textarea>
                    {$erroresCampos['sinopsisPelicula']}
                </div>
                <div class="add-campo-reparto">
                    <label for="reparto">Actores/Personajes (separados por comas entre diferentes actores):</label>
                    <textarea id="reparto" name="reparto" rows="5">$repartoP</textarea>
                    {$erroresCampos['reparto']}
                </div>
                <div class="add-campo-genero">
                    <label for="generoPelicula">Género:</label>
                    <select id="generoPelicula" name="generoPelicula">
                        <option value="-1">Seleccionar</option>
                        {$erroresCampos['generoPelicula']}
    EOF;
    
        if ($generos != false) {
            foreach ($generos as $id => $genero) {
                $html .= "<option value='$id'>$genero</option>";
            }
            $html .= "</select>{$erroresCampos['generoPelicula']}</div>";
        }
    
        $html .= <<<EOF
                <div class="add-campo-imdb">
                    <label for="imdb">Puntuación en IMDb:</label>
                    <input id="imdb" type="text" name="imdb"    value="$imdbP" />
                    {$erroresCampos['imdb']}
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
            
            // NUEVO TITULO?
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
                    $result = Pelicula::cambiarTítulo($this->pelicula_id,$nuevoTitulo);
                }
            }

            //NUEVO DIRECTOR ?
            $nuevoDirector = trim($datos['directorPelicula'] ?? '');
            $nuevoDirector = filter_var($nuevoDirector, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoDirector)){
                $result = Pelicula::cambiarDirector($this->pelicula_id,$nuevoDirector);   
            }

            //NUEVO AÑO ?
            $nuevoAnnio = trim($datos['annioPelicula'] ?? '');
            $nuevoAnnio = filter_var($nuevoAnnio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoAnnio)){
                $year = date('Y');
                if($nuevoAnnio <= $year){
                $result = Pelicula::cambiarAnnio($this->pelicula_id,$nuevoAnnio); 
                }
                else{
                    $this->errores['annioPelicula'] = 'El año introducido no es valido';
                }  
            }

            //NUEVA SINOPSIS ?
            $nuevaSinopsis = trim($datos['sinopsisPelicula'] ?? '');
            $nuevaSinopsis = filter_var($nuevaSinopsis, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevaSinopsis)){
                $result = Pelicula::cambiarSinopsis($this->pelicula_id,$nuevaSinopsis); 
            }

            //NUEVA NOTA IMdB ?
            $nuevoIMdB = trim($datos['imdb'] ?? '');
            $nuevoIMdB = filter_var($nuevoIMdB, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoIMdB)){
                if($nuevoIMdB <= 10.0){
                $result = Pelicula::cambiarImdb($this->pelicula_id,$nuevoIMdB); 
                }
                else{
                    $this->errores['imdb'] = 'El valor introducido no es valido';
                }  
            }

            $generoPelicula = ($datos['generoPelicula'] != -1) ? $datos['generoPelicula'] : '';
            if (!empty($generoPelicula)){
                $result = Pelicula::cambiarGenero($this->pelicula_id,$generoPelicula); 
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
                        $result = Pelicula::actualizaPortada($this->pelicula_id, $targetFilePath);

                    } else {
                        $this->errores['portada'] = 'Hubo un error al subir el fichero';
                    }
                }
                else{
                    $this->errores['portada'] = 'La imagen que ha seleccionado debe tener extensión .jpg, .png o .jpeg'; 
                }

            }

            $relativePath = '/AW/Proyecto-AW/admin_peliculas.php';
            header('Location: ' . $relativePath);
            exit();
            
    }
}
