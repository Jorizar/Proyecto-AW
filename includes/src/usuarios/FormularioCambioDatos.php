<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioCambioDatos extends Formulario
{
    public function __construct() {
        parent::__construct('formCambioDatos', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Genera los campos para cambiar el email
        $formularioCambioEmail = new FormularioCambioEmail();
        $camposEmail = $formularioCambioEmail->generaCamposFormulario($datos);

        // Genera los campos para cambiar la foto de perfil
        $formularioCambioFoto = new FormularioCambioFoto();
        $camposFoto = $formularioCambioFoto->generaCamposFormulario($datos);

        // Genera los campos para cambiar el nombre de usuario
        $formularioCambioNombre = new FormularioCambioNombre();
        $camposNombre = $formularioCambioNombre->generaCamposFormulario($datos);

        // Combina los campos de los tres formularios en uno solo
        $html = <<<EOF
        <fieldset>
            <legend>Cambiar Datos</legend>
            <div>
                $camposEmail
            </div>
            <div>
                $camposFoto
            </div>
            <div>
                $camposNombre
            </div>
            <div>
                <button type="submit" name="cambiarDatos">Guardar Cambios</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        // Procesa los datos para cambiar el email
        $formularioCambioEmail = new FormularioCambioEmail();
        $respuestaEmail = $formularioCambioEmail->procesaFormulario($datos);

        // Procesa los datos para cambiar la foto de perfil
        $formularioCambioFoto = new FormularioCambioFoto();
        $respuestaFoto = $formularioCambioFoto->procesaFormulario($datos);

        // Procesa los datos para cambiar el nombre de usuario
        $formularioCambioNombre = new FormularioCambioNombre();
        $respuestaNombre = $formularioCambioNombre->procesaFormulario($datos);

        // Comprueba si hubo algún error en alguno de los formularios
        if ($respuestaEmail !== true || $respuestaFoto !== true || $respuestaNombre !== true) {
            // Devuelve los errores combinados de los tres formularios
            return array_merge($respuestaEmail, $respuestaFoto, $respuestaNombre);
        } else {
            // Devuelve true para indicar que se procesaron correctamente los datos
            return true;
        }
    }
}
?>
