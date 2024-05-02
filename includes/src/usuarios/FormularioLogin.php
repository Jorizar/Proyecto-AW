<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioLogin extends Formulario
{
    public function __construct() {
        parent::__construct('formLogin', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se reutiliza el nombre de usuario introducido previamente o se deja en blanco
        $nombreUsuario = $datos['nombreUsuario'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'password'], $this->errores, 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<EOF
        $htmlErroresGlobales
            <div class="login-usuario">
                <label for="nombreUsuario">Nombre de usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
                <span id="nombreUsuarioError" class="error"></span>
                {$erroresCampos['nombreUsuario']}
            </div>
            <div class="login-password">
                <label for="password">Contraseña:</label>
                <input id="password" type="password" name="password" />
                <span id="passwordError" class="error"></span>
                {$erroresCampos['password']}
            </div>
            <div>
                <button type="submit" name="login">Entrar</button>
            </div>
            <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
            <script type="text/javascript" src="js/ValidarFormulario.js"></script>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        //Lista Blanca
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $nombreUsuario)) {
            $this->errores['nombreUsuario'] = 'El nombre solo puede contener letras, numeros y guiones';
        }
        if ( ! $nombreUsuario || empty($nombreUsuario) ) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }
      
        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || empty($password) ) {
            $this->errores['password'] = 'La contraseña no puede estar vacía.';
        }
        
        if (count($this->errores) === 0) {
            $usuario = Usuario::login($nombreUsuario, $password);
        
            if (!$usuario) {
                $this->errores[] = "El usuario o la contraseña no coinciden";
            } else {
                $app = Aplicacion::getInstance();
                $app->login($usuario);
            }
        }
    }
}
