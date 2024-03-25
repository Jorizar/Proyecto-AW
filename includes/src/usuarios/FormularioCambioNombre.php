<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioCambioNombre extends Formulario
{
    public function __construct() {
        parent::__construct('formCambioNombre', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se reutiliza el nombre de usuario introducido previamente o se deja en blanco
        $nuevoNombre = $datos['nuevoNombre'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nuevoNombre'], $this->errores, 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Cambiar nombre de usuario</legend>
            <div>
                <label for="nuevoNombre">Nuevo nombre:</label>
                <input id="nuevoNombre" type="text" name="nuevoNombre" value="$nuevoNombre" />
                {$erroresCampos['nuevoNombre']}
            </div>
            <div>
                <button type="submit" name="cambiarNombre">Cambiar Nombre</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        $nuevoNombre = trim($datos['nuevoNombre'] ?? '');
        $nuevoNombre = filter_var($nuevoNombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nuevoNombre || empty($nuevoNombre) ) {
            $this->errores['nuevoNombre'] = 'El nuevo nombre no puede estar vacío';
        }
        
        if (count($this->errores) === 0) {
            $user = Usuario::obtenerUsuario($nuevoNombre);
            if ($user !== null) {
                $this->errores[] = "Ese nombre ya está siendo utilizado por otro usuario";
            } else {
                 $cambioExitoso = Usuario::cambiarNombre($nuevoNombre);
                 if (!$cambioExitoso) {
                     $this->errores[] = "Error al cambiar el nombre";
                 } else {
                     return "Nombre actualizado: $nuevoNombre";
                 }
            }
        }
    }
}
