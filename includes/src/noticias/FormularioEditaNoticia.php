<?php
namespace es\ucm\fdi\aw\noticias;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\noticias\Noticia;

class FormularioEditaNoticia extends Formulario
{
    protected $id_noticia;

    public function __construct($id_noticia) {
        parent::__construct('formCambioDatosNoticia', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/admin_noticias.php'), 'enctype' => 'multipart/form-data']);
        $this->id_noticia = $id_noticia;
    }
    
    protected function generaCamposFormulario(&$datos)
    {
            // Obtener la noticia con el ID proporcionado
        $noticia = Noticia::buscaPorId($this->id_noticia);

        // Si no se encuentra la noticia, mostrar mensaje de error
        if (!$noticia) {
            return "No se encontró la película con el ID {$this->id_noticia}.";
        }

        // Obtener los valores de la noticia para prellenar los campos del formulario
            $tituloN = $noticia->getTitulo();
            $autorN = $noticia->getAutor();
            $textoN = $noticia->getTexto();
            $fechaN = $noticia->getFecha();

        $erroresCampos = self::generaErroresCampos(['titulo', 'autor', 'fecha', 'portada', 'texto'], $this->errores, 'span', array('class' => 'error'));

        $htmlNoticiaId= '';

        if (!empty($this->id_noticia)) {
            $htmlNoticiaId = "<input type='hidden' name='noticia_id' value='{$this->id_noticia}'>";
        }

        // Se generan los campos del formulario para cambiar los datos del usuario.
        $html = <<<EOF
        <div class="titulo_cambiarDatosNoticia">
        </div>
        <div class="contenedor_cambiarDatosNoticia">
            <div class="cambiar-datos-noticia-formulario">
                {$htmlNoticiaId}
                <div class="edit-campo-titulo contenedor-etiqueta-campo">
                    <label for="titulo">Título:</label>
                    <input id="titulo" type="text" name="titulo"  placeholder="$tituloN"/>
                    {$erroresCampos['titulo']}
                </div>
                <div class="edit-campo-autor contenedor-etiqueta-campo">
                    <label for="autor">Autor:</label>
                    <input id="autor" type="text" name="autor" value="$autorN"/>
                    {$erroresCampos['autor']}
                </div>
                <div class="edit-campo-fecha contenedor-etiqueta-campo">
                    <label for="fecha">Fecha de la Noticia:</label>
                    <input id="fecha" type="text" name="fecha" value="$fechaN"/>
                    {$erroresCampos['fecha']}
                </div>
                <div class="edit-campo-portada contenedor-etiqueta-campo">
                    <label for="portada">Portada de la Noticia:</label>
                    <input id="portada" type="file" name="portada" accept="image/*" >
                </div>
                <div class="edit-campo-texto contenedor-etiqueta-campo">
                    <label for="texto">Texto de la Noticia:</label>
                    <textarea id="texto" name="texto" rows="10" >$textoN</textarea>
                    {$erroresCampos['texto']}
                </div>
                <div class="contenedor-etiqueta-campo">
                    <button type="submit" name="cambiar_datosNoticia">Cambiar Datos</button>
                </div>
            </div>
        </div>
    EOF;
    
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        
        echo "El id de la noticia es: ".$this->id_noticia."";
        //NUEVO TITULO
        $nuevoTitulo = trim($datos['titulo'] ?? '');
        $nuevoTitulo = filter_var($nuevoTitulo, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(!empty($nuevoTitulo)){
            //Comprobamos que no exista una pelicula con ese titulo
            $noticia = Noticia::buscaPorTitulo($nuevoTitulo); 
               
            if($noticia){
                error_log('titulo_duplicado');
                $this->errores['titulo'] = 'Ya existe una noticia con ese titulo';
            }
            else{
                Noticia::cambiarTítulo($this->id_noticia,$nuevoTitulo);
            }
        }
        // NUEVO AUTOR
        $nuevoAutor = trim($datos['autor'] ?? '');
        $nuevoAutor = filter_var($nuevoAutor, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(!empty($nuevoAutor)){
            Noticia::cambiarAutor($this->id_noticia,$nuevoAutor);   
        }
        // NUEVA FECHA
        $nuevaFecha = trim($datos['fecha'] ?? '');
        $nuevaFecha = filter_var($nuevaFecha, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(!empty($nuevaFecha)){
            Noticia::cambiarFecha($this->id_noticia,$nuevaFecha);   
        }
        // NUEVO TEXTO
        $nuevoTexto = trim($datos['texto'] ?? '');
        $nuevoTexto = filter_var($nuevoTexto, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(!empty($nuevoTexto)){
            Noticia::cambiarTexto($this->id_noticia,$nuevoTexto);   
        }

        // Procesamos la portada de la noticia

        $portada = isset($_FILES['portada']) ? $_FILES['portada']: '';
         
        if($portada != ''){
            $filetype = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $filetype;
            $targetFilePath = 'img/noticias/' . $filename;
            $filesize = $_FILES['portada']['size'];
            
            if($filesize > 1000000){
                    $this->errores['portada'] = 'La imagen no puede ocupar más de 1 MB';
                }

            $allowTypes = array('jpg', 'png', 'jpeg');
            if(in_array($filetype, $allowTypes)){ // Comprobamos que la extensión de la imagen se ajusta a las requeridas
                if(move_uploaded_file($_FILES['portada']["tmp_name"], $targetFilePath)) {
                    // Guardar la ruta de la portada en la variable $portada
                    $portada = './' . $targetFilePath;

                    Noticia::actualizaPortada($this->id_noticia, $portada);

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

      
        $relativePath = '/admin_noticias.php';
        header('Location: ' . $relativePath);
        exit();

    }
}
