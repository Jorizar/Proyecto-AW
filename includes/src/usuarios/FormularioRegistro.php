<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioRegistro extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $nombreUsuario = $datos['nombreUsuario'] ?? '';

    // Se generan los mensajes de error si existen.
    $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
    $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'password', 'password2', 'email', 'rol'], $this->errores, 'span', array('class' => 'error'));

    // Opciones para el campo de selección de roles
    $opcionesRol = [
        'free' => 'Free',
        'premium' => '€ Premium €'
    ];

    // Genera las opciones HTML para el campo de selección de roles
    $optionsRol = '';
    foreach ($opcionesRol as $value => $label) {
        $selected = ($datos['rol'] ?? '') === $value ? 'selected' : '';
        $optionsRol .= "<option value='$value' $selected>$label</option>";
    }

        $html = <<<EOF
        $htmlErroresGlobales
            <div class="registro-usuario">
                <label for="nombreUsuario">Nombre de usuario:</label>
                <input id="nombreUsuario" type="text" name="nombreUsuario" value="$nombreUsuario" />
                {$erroresCampos['nombreUsuario']}
            </div>
            <div class="registro-password">
                <label for="password">Contraseña:</label>
                <input id="password" type="password" name="password" />
                {$erroresCampos['password']}
            </div>
            <div class="registro-password2">
                <label for="password2">Repite la contraseña:</label>
                <input id="password2" type="password" name="password2" />
                {$erroresCampos['password2']}
            </div>
            <div class="registro-email">
                <label for="email">Introduce el email:</label>
                <input id="email" type="email" name="email" />
                {$erroresCampos['email']}
            </div>
            <div class="registro-rol">
            <label for="rol">Rol:</label>
            <select id="rol" name="rol">
                $optionsRol
            </select>
            </div>
            <div>
                <button type="submit" name="registro">Registrar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
{
    $this->errores = [];

    $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
    // Lista blanca
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $nombreUsuario)) {
        $this->errores['nombreUsuario'] = 'El nombre solo puede contener letras, numeros y guiones';
    }

    /*$password = trim($datos['password'] ?? '');
    $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ( ! $password || mb_strlen($password) < 5 ) {
        $this->errores['password'] = 'La contraseña tiene que tener una longitud de al menos 5 caracteres.';
    }*/
    $password = trim($datos['password'] ?? '');
    if (empty($password) || mb_strlen($password) < 8) {
        $this->errores['password'] = 'La contraseña debe tener al menos 8 caracteres.';
    }

    
    // Verificar similitud con información personal
    if (stripos($password, $nombreUsuario) !== false) {
        $this->errores['password'] = 'La contraseña no puede contener tu nombre de usuario.';
    }

    // Verificar secuencias y patrones comunes
    $patterns = ['patata','123', 'abc', 'password']; // Ejemplos de patrones comunes
    foreach ($patterns as $pattern) {
        if (stripos($password, $pattern) !== false) {
            $this->errores['password'] = 'La contraseña no puede contener secuencias o patrones comunes.';
            break;
        }
    }


    // Evaluar la fortaleza de la contraseña
    $puntaje = 0;
    $puntaje += mb_strlen($password); // Longitud de la contraseña
    if (preg_match('/[A-Z]/', $password)) {
        $puntaje += 2; // Sumar puntaje si hay letras mayúsculas
    }
    if (preg_match('/[a-z]/', $password)) {
        $puntaje += 2; // Sumar puntaje si hay letras minúsculas
    }
    if (preg_match('/[0-9]/', $password)) {
        $puntaje += 2; // Sumar puntaje si hay números
    }
    if (preg_match('/[^a-zA-Z0-9]/', $password)) {
        $puntaje += 3; // Sumar puntaje si hay caracteres especiales
    }

    
    // Proporcionar retroalimentación
    if ($puntaje < 8) {
        $this->errores['password'] = 'La contraseña es débil. Debe incluir al menos 8 caracteres, letras mayúsculas y minúsculas, números y caracteres especiales.';
    } elseif ($puntaje < 12) {
        $this->errores['password'] = 'La contraseña es moderadamente segura.';
    } else {
        // La contraseña se considera fuerte
    }

    $password2 = trim($datos['password2'] ?? '');
    $password2 = filter_var($password2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ( ! $password2 || $password != $password2 ) {
        $this->errores['password2'] = 'Las contraseñas deben coincidir';
    }

    $email = trim($datos['email'] ?? '');
    $email = filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if ( ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->errores['email'] = 'El email no es válido';
    }

    $rol = $datos['rol'] ?? '';
    if (!in_array($rol, ['free', 'premium'])) {
        $this->errores['rol'] = 'Elige un rol válido';
    }

    if (count($this->errores) === 0) {
        
        $usuario = Usuario::buscaUsuario($nombreUsuario);
    
        if ($usuario) {
            $this->errores[] = "El usuario ya existe";
        } else {
            $usuario = Usuario::crea($nombreUsuario, $password, $id, $rol, $email, $foto);
            $app = Aplicacion::getInstance();
            $app->login($usuario);
        }
    }
}
}
