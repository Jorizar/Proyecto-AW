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
        <fieldset>
            <legend>Cambiar Datos</legend>
            <div>
                <label for="nuevo_nombre">Nuevo Nombre:</label>
                <input type="text" id="nuevo_nombre" name="nuevo_nombre">
                {$erroresCampos['nuevo_nombre']}
            </div>
            <div>
                <label for="nuevo_email">Nuevo Correo Electrónico:</label>
                <input type="email" id="nuevo_email" name="nuevo_email">
                {$erroresCampos['nuevo_email']}
            </div>
            <div>
                <label><img src='./img/fotosPerfil/1.png' width='48' height='48'></label>
                <input type='radio' name='nueva_foto' value='/img/fotosPerfil/1.png'>

                <label><img src='./img/fotosPerfil/2.png' width='48' height='48'></label>
                <input type='radio' name='nueva_foto' value='/img/fotosPerfil/2.png'>

                <label><img src='./img/fotosPerfil/brad.png' width='48' height='48'></label>
                <input type='radio' name='nueva_foto' value='./img/fotosPerfil/brad.png'>

                <label><img src='./img/fotosPerfil/quentin.png' width='48' height='48'></label>
                <input type='radio' name='nueva_foto' value='./img/fotosPerfil/quentin.png'>
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
            $nuevoNombre = trim($datos['nuevo_nombre']);
            $nuevoNombre = filter_var($nuevoNombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(!empty($nuevoNombre)){
                //Comprobamos que no exista un usuario con ese nombre
                $user = Usuario::buscaUsuario($nuevoNombre);
                if($user){
                    $this->errores['nuevo_nombre'] = 'Alguien ya utiliza ese nombre de usuario';
                }
            }

            $nuevoEmail = trim($datos['nuevo_email']);
            $nuevoEmail = filter_var($nuevoEmail, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( ! filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
                $this->errores['nuevo_email'] = 'El email no es válido';
            }
           
            $nuevaFoto = $datos['nueva_foto'];´

            if (count($this->errores) === 0) {

                //Actualizamos los datos de la sesión
                $_SESSION['nombre'] = $nuevoNombre;
                $_SESSION['email'] = $nuevoEmail;
                $_SESSION['fotoPerfil'] = $nuevaFoto;

                //Actualiza los datos del usuario en la base de datos
                $result = Usuario::cambiarNombre($_SESSION['idUsuario'], $nuevoNombre);
                $result = Usuario::cambiarEmail($_SESSION['idUsuario'], $nuevoEmail);
                $result = Usuario::actualizaFoto($_SESSION['idUsuario'], $nuevaFoto);
            }

            // Redirige al usuario de vuelta a la página de perfil
            header("Location: " . $this->urlRedireccion);
            exit;
        }
    }
}
