<?php
namespace es\ucm\fdi\aw\noticias;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioAgregaNoticia extends Formulario
{

    public function __construct() {
        parent::__construct('formAgregaNoticia', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php'), 'enctype' => 'multipart/form-data']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['titulo', 'autor', 'fecha', 'portada', 'texto'], $this->errores, 'span', array('class' => 'error'));
    
        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOS
        $htmlErroresGlobales
        <div class="agrega-noticia">
        </div>
        <div class="agregar-noticia-container">
                <div class="add-campo-titulo">
                    <label for="titulo">Título:</label>
                    <input id="titulo" type="text" name="titulo" required/>
                    {$erroresCampos['titulo']}
                </div>
                <div class="add-campo-autor">
                    <label for="autor">Autor:</label>
                    <input id="autor" type="text" name="autor" required/>
                    {$erroresCampos['autor']}
                </div>
                <div class="add-campo-fecha">
                    <label for="fecha">Fecha de la Noticia:</label>
                    <input id="fecha" type="text" name="fecha" placeholder="XX/XX/XXXX" required/>
                    {$erroresCampos['fecha']}
                </div>
                <div class="add-campo-portada">
                    <label for="portada">Portada de la Noticia:</label>
                    <input id="portada" type="file" name="portada" accept="image/*" required>
                </div>
                <div class="add-campo-texto">
                    <label for="texto">Texto de la Noticia:</label>
                    <textarea id="texto" name="texto" placeholder="Añade aquí la noticia" rows="10" required></textarea>
                    {$erroresCampos['texto']}
                </div>
                <div class="add-campo-rol">
                    <label for="suscripcion">Suscripción:</label>
                    <select id="suscripcion" name="suscripcion">
                    <option value="0">free</option>
                    <option value="1">€ Premium €</option>
                    </select>
                </div>
                <div class="anyadir-boton">
                    <button type="submit" name="add">Añadir</button>
                </div>
        </div>
    EOS;
        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        // Recoger los datos del formulario
        $titulo = isset($datos['titulo']) ? $datos['titulo'] : '';
        $autor = isset($datos['autor']) ? $datos['autor'] : '';
        $fecha = isset($datos['fecha']) ? $datos['fecha'] : '';
        $texto = isset($datos['texto']) ? $datos['texto'] : '';
        $portada = isset($_FILES['portada']) ? $_FILES['portada']: '';
        $rol = isset($datos['suscripcion']) ? $datos['suscripcion'] : '';

         // Comprobamos si exxiste una pelicula con ese mismo titulo
         $noticias = Noticia::buscaPorTitulo($titulo);
         if ($noticias === true) {
             $this->errores[] = "Ya existe una noticia con ese titulo, prueba de nuevo";
         } 
  
         if($autor === '' || $fecha === '' || $texto === '' ){
            $this->errores[] = "No puede haber campos vacios, rellene todos los campos";    
         }
    
         // Procesamos la portada de la noticia
        if($portada != ''){
            $filetype = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $filetype;
            $targetFilePath = 'img/noticias/' . $filename;
            $filesize = $_FILES['portada']['size'];
            
            if($filesize > 1000000){
                    $this->errores['portada'] = 'La imagen no puede ocupar más de 1 MB';
                }

            $allowTypes = array('jpg', 'png', 'jpeg');
            if(in_array($filetype, $allowTypes)){ 
                if(move_uploaded_file($_FILES['portada']["tmp_name"], $targetFilePath)) {
                    $portada = './' . $targetFilePath;
                } else {
                    $this->errores['portada'] = 'Hubo un error al subir el fichero';
                }
            } else {
                $this->errores['portada'] = 'La imagen que ha seleccionado debe tener extensión .jpg, .png o .jpeg'; 
            }
        }
        else{
            $this->errores[] = "ERROR: La portada es obligatoria.";
        }

        if (count($this->errores) === 0) {
            $noticia = Noticia::crea($titulo, $portada, $texto, $autor, $fecha, $rol);
        
            if ($noticia === false) {
                echo "Error: No se ha podido crear la pelicula";
                exit();
            } else {
                $relativePath = '/admin_noticias.php';
                header('Location: ' . $relativePath);
                exit();
            }
        }

    }
}