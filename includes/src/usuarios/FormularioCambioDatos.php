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
                <input type="text" id="nuevo_nombre" name="nuevo_nombre">
            </div>
            <div>
                <label for="nuevo_email">Nuevo Correo Electrónico:</label>
                <input type="email" id="nuevo_email" name="nuevo_email">
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
            $nuevoNombre = trim($datos['nuevo_nombre'] ?? '');
            $nuevoNombre = filter_var($nuevoNombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( ! $nuevoNombre || empty($nuevoNombre) ) {
                $this->errores['nuevo_nombre'] = 'El nombre de usuario no puede estar vacío';
            }

            $nuevoEmail = trim($datos['nuevo_email'] ?? '');
            $nuevoEmail = filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL);
            if ( ! $nuevoEmail || empty($nuevoEmail) ) {
                $this->errores['nuevo_email'] = 'El email de usuario no puede estar vacío';
            }
            
            $nuevaFoto = $datos['nueva_foto'];

            //Actualiza los datos del usuario en la base de datos
            $result = Usuario::cambiarNombre($_SESSION['idUsuario'], $nuevoNombre);
            $result = Usuario::cambiarEmail($_SESSION['idUsuario'], $nuevoEmail);
            $result = Usuario::actualizaFoto($_SESSION['idUsuario'], $nuevaFoto);
                       
            // Redirige al usuario de vuelta a la página de perfil
            header("Location: " . $this->urlRedireccion);
            exit;
        }
        
        // Si no se han enviado los datos del formulario, se muestra un mensaje de error
        $this->errores[] = "Por favor, completa todos los campos.";
    }
}
