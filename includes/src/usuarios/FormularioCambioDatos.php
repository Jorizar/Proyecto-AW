<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioCambioDatos extends Formulario
{
    public function __construct() {
        parent::__construct('formCambioDatos', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/perfil.php'), 'enctype' => 'multipart/form-data']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        //$erroresCampos = self::generaErroresCampos(['nueva_foto'], $this->errores, 'span', array('class' => 'error'));

        // Se generan los campos del formulario para cambiar los datos del usuario.
        $html = <<<EOF
        <fieldset>
            <legend>Cambiar Datos</legend>
            <div>
                <label for="nuevo_nombre">Nuevo Nombre:</label>
                <input type="text" id="nuevo_nombre" name="nuevo_nombre" required>
            </div>
            <div>
                <label for="nuevo_email">Nuevo Correo Electrónico:</label>
                <input type="email" id="nuevo_email" name="nuevo_email" required>
            </div>
            <div>
                <label for="nueva_foto">Nueva Foto de Perfil:</label>
                <input type="file" id="nueva_foto" name="nueva_foto" accept="image/*" multiple="false">
            </div>
            <div>
                <button type="submit" name="cambiar_datos">Cambiar Datos</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        // Verifica si se han enviado los datos del formulario
        if (isset($datos['nuevo_nombre'], $datos['nuevo_email'], $datos['nueva_foto'])) {
            // Obtiene los nuevos datos introducidos por el usuario
            $nuevoNombre = $datos['nuevo_nombre'];
            $nuevoEmail = $datos['nuevo_email'];
            
            // Actualiza los datos del usuario en la sesión
            $_SESSION['nombre'] = $nuevoNombre;
            $_SESSION['email'] = $nuevoEmail;
            

            //Actualiza los datos del usuario en la base de datos
            $result = Usuario::cambiarNombre($_SESSION['idUsuario'], $nuevoNombre);
            $result = Usuario::cambiarEmail($_SESSION['idUsuario'], $nuevoEmail);

            // Aquí deberías procesar la foto de perfil y guardarla en una ubicación específica
            if(isset($_FILES['nueva_foto'])){
                $filetype = pathinfo($_FILES['nueva_foto']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . "." . $filetype;
                $targetFilePath = 'img/fotosPerfil/'.$filename;
                $filesize = $_FILES['nueva_foto']['name'];
                
                if($filesize > 1000000){
                    $this->errores['nueva_foto'] = 'La imagen no puede ocupar más de 1 MB';
                }
    
                $allowTypes = array('jpg', 'png', 'jpeg');
                if(in_array($filetype, $allowTypes)){ //Comprobamos que la extensión de la imagen se ajusta a las requeridas
                    if(move_uploaded_file($_FILES['nueva_foto']["tmp_name"], $targetFilePath)) {
                        $_SESSION['fotoPerfil'] = $filename;
                    } else {
                        $this->errores['nueva_foto'] = 'Hubo un error al subir el fichero';
                    }
                }
                else{
                    $this->errores['nueva_foto'] = 'La imagen que ha seleccionado debe tener extensión .jpg, .png o .jpeg'; 
                }
            }
           
            // Redirige al usuario de vuelta a la página de perfil
            header("Location: " . $this->urlRedireccion);
            exit;
        }
        
        // Si no se han enviado los datos del formulario, se muestra un mensaje de error
        $this->errores[] = "Por favor, completa todos los campos.";
    }
}
