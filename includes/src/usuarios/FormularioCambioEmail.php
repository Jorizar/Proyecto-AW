<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioCambioEmail extends Formulario
{
    public function __construct() {
        parent::__construct('formCambioEmail', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se reutiliza el email introducido previamente o se deja en blanco
        $nuevoEmail = $datos['nuevoEmail'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nuevoEmail'], $this->errores, 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Cambiar email de usuario</legend>
            <div>
                <label for="nuevoEmail">Nuevo email:</label>
                <input id="nuevoEmail" type="text" name="nuevoEmail" value="$nuevoEmail" />
                {$erroresCampos['nuevoEmail']}
            </div>
            <div>
                <button type="submit" name="cambiarEmail">Cambiar Email</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        $nuevoEmail = trim($datos['nuevoEmail'] ?? '');
        $nuevoEmail = filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL);
        if ( ! $nuevoEmail || empty($nuevoEmail) ) {
            $this->errores['nuevoEmail'] = 'El nuevo email no es válido';
        }
        
        if (count($this->errores) === 0) {
            $user = Usuario::obtenerEmail($nuevoEmail);
            if ($user !== null) {
                $this->errores[] = "Este email ya está siendo utilizado por otro usuario";
            } else {
                 $cambioExitoso = Usuario::cambiarEmail($nuevoEmail);
                 if (!$cambioExitoso) {
                     $this->errores[] = "Error al cambiar el email";
                 } else {
                     return "Email actualizado: $nuevoEmail";
                 }
            }
        }
    }
}
