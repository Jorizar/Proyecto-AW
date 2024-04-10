<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioCambioDatos extends Formulario
{
    public function __construct() {
        parent::__construct('formCambioDatos', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/perfil.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
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
                <input type="file" id="nueva_foto" name="nueva_foto" accept="image/*">
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
            $nuevaFoto = $datos['nueva_foto'];
            
            // Actualiza los datos del usuario en la sesión
            $_SESSION['nombreUsuario'] = $nuevoNombre;
            $_SESSION['email'] = $nuevoEmail;
            // Aquí deberías procesar la foto de perfil y guardarla en una ubicación específica
            
            // Redirige al usuario de vuelta a la página de perfil
            header("Location: " . $this->urlRedireccion);
            exit;
        }
        
        // Si no se han enviado los datos del formulario, se muestra un mensaje de error
        $this->errores[] = "Por favor, completa todos los campos.";
    }
}
