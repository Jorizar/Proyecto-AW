<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\usuarios\Usuario;


class FormularioCambioPlan extends Formulario
{
    public function __construct() {
        parent::__construct('formCambioPlan', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/perfil.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
 
        // Se genera el HTML asociado a los campos del formulario.
        $html = <<<EOF
        <div class="cambiar-plan-formulario">
                <div class="plan-actual">
                    <p>Plan Actual: {$_SESSION["rol"]}</p>
                </div>
                <div class="nuevo-plan">
                <label for="nuevo_plan">Nuevo Plan:</label>
                <select id="nuevo_plan" name="nuevo_plan">
                <option value="free">Free</option>
                <option value="premium">€ Premium €</option>
                </select>
                </div>
                <div class="enviar_plan">
                <button type="submit" name="cambiar_plan">Cambiar Plan</button>
                </div>
        </div>

        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        // Verifica si se ha enviado el plan seleccionado
        if (isset($datos['nuevo_plan'])) {
            // Obtiene el nuevo plan seleccionado por el usuario
            $nuevoPlan = $datos['nuevo_plan'];
            
            // Obtiene el plan actual del usuario
            $planActual = $_SESSION["rol"];

            // Verifica si el nuevo plan es igual al plan actual
            if ($nuevoPlan === $planActual) {
                // Muestra un mensaje de error
                $this->errores[] = "Ese es tu plan actual.";
            } else {
                // Actualiza el plan del usuario en la sesión
                $_SESSION["rol"] = $nuevoPlan;

                //Actualizamos su rol en la bd
                $result = Usuario::actualizaRol($_SESSION['idUsuario'], $nuevoPlan);
            }
        }

        // Redirige al usuario de vuelta a la página de perfil
        header("Location: " . $this->urlRedireccion);
        exit;
    }
}
