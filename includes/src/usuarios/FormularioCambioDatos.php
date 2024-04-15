<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\usuarios\Usuario;

class FormularioCambioDatos extends Formulario
{
    public function __construct() {
        parent::__construct('formCambioDatos', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/perfil.php'), 'enctype' => 'multipart/form-data']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $erroresCampos = self::generaErroresCampos(['nuevo_nombre', 'nuevo_email'], $this->errores, 'span', array('class' => 'error'));

        // Se generan los campos del formulario para cambiar los datos del usuario.
        $html = <<<EOF
        <div class="titulo_cambiarDatos">
        </div>
        <div class="contenedor_cambiarDatos">
            <div class="cambiar-datos-formulario">
                <div class="nombre_cambioPlan">
                    <label for="nuevo_nombre">Nuevo Nombre:</label>
                    <input type="text" id="nuevo_nombre" name="nuevo_nombre">
                    {$erroresCampos['nuevo_nombre']}
                </div>
                <div class="email_cambioPlan">
                    <label for="nuevo_email">Nuevo Correo Electrónico:</label>
                    <input type="email" id="nuevo_email" name="nuevo_email">
                    {$erroresCampos['nuevo_email']}
                </div>
                <div class="fotos_cambioDatos-container">
                    <label for="nueva_foto">Nueva Foto de Perfil:</label>
                    <input type="file" id="nueva_foto" name="nueva_foto" accept="image/*" multiple="false">
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
            
            // Obtiene los nuevos datos introducidos por el usuario
            $nuevoNombre = trim($datos['nuevo_nombre'] ?? '');
            $nuevoNombre = filter_var($nuevoNombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoNombre)){
                //Comprobamos que no exista un usuario con ese 
                $user = Usuario::buscaUsuario($nuevoNombre);
                if($user){
                    error_log('nombre_duplicado');
                    $this->errores['nuevo_nombre'] = 'Alguien ya utiliza ese nombre de usuario';
                }
                else{
                    $_SESSION['nombre'] = $nuevoNombre;
                    $result = Usuario::cambiarNombre($_SESSION['idUsuario'], $nuevoNombre);
                }
            }


            $nuevoEmail = trim($datos['nuevo_email'] ?? '');
            if(!empty($nuevoEmail)){
                $nuevoEmail = filter_var($nuevoEmail, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                if ( ! filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
                    $this->errores['nuevo_email'] = 'El email no es válido';
                }
                else{
                    $result = Usuario::cambiarEmail($_SESSION['idUsuario'], $nuevoEmail);
                    $_SESSION['email'] = $nuevoEmail;
                }
            }
            

            //Procesamo la foto que ha escogido cargar el usuario
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
                        //Actualizar la foto en la base de datos
                        $result = Usuario::actualizaFoto($_SESSION['idUsuario'], $targetFilePath);

                        //Actualizar la foto en la sesión
                        $_SESSION['fotoPerfil'] = $targetFilePath;
                    } else {
                        $this->errores['nueva_foto'] = 'Hubo un error al subir el fichero';
                    }
                }
                else{
                    $this->errores['nueva_foto'] = 'La imagen que ha seleccionado debe tener extensión .jpg, .png o .jpeg'; 
                }

            }
    }
}
