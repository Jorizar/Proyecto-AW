<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioRegistroAdmin extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/admin_usuarios.php')]);
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
        'premium' => '€ Premium €',
        'critico' => 'Critico de Cine'
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
                <span id="nombreUsuarioError" class="error"</span>
                {$erroresCampos['nombreUsuario']}
            </div>
            <div class="registro-password">
                <label for="password">Contraseña:</label>
                <input id="password" type="password" name="password" />
                <span id="passwordError" class="error"</span>
                {$erroresCampos['password']}
            </div>
            <div class="registro-password2">
                <label for="password2">Repite la contraseña:</label>
                <input id="password2" type="password" name="password2" />
                <span id="password2Error" class="error"</span>
                {$erroresCampos['password2']}
            </div>
            <div class="registro-email">
                <label for="email">Introduce el email:</label>
                <input id="email" type="email" name="email" />
                <span id="emailError" class="error"</span>
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
        <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
        <script type="text/javascript" src="js/ValidarFormulario.js"></script>
        EOF;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
{
    $this->errores = [];

    $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
    $password = trim($datos['password'] ?? '');

    
    // Validar nombre de usuario
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $nombreUsuario)) {
        $this->errores['nombreUsuario'] = 'El nombre solo puede contener letras, números y guiones.';
    }
   
    
    //Esto esta bien, solo hay que cambiar la ruta y el nombre del txt
    // Cargar palabras prohibidas desde el archivo
    $rutaArchivoPalabrasProhibidas = './seguridad/palabrasProhibidas.txt';
    $contenidoArchivo = file_get_contents($rutaArchivoPalabrasProhibidas);

    if (!file_exists($rutaArchivoPalabrasProhibidas)) {
        echo "Error: El archivo de palabras prohibidas no existe.";
        exit;
    }
    // Convertir el contenido del archivo en un array de palabras prohibidas
    $palabrasProhibidas = explode(',', $contenidoArchivo);

    // Eliminar espacios en blanco al inicio y final de cada palabra prohibida
    foreach ($palabrasProhibidas as &$palabraProhibida) {
    $palabraProhibida = trim($palabraProhibida);
    }

    // Verificar que la contraseña no sea igual a ninguna de las palabras prohibidas
    if (in_array(trim($password), $palabrasProhibidas)) {
    $this->errores['password'] = 'La contraseña no puede ser una palabra común.';
    }
    

    // Validar contraseña
    if (empty($password) || mb_strlen($password) < 8) {
        $this->errores['password'] = 'La contraseña debe tener al menos 8 caracteres.';
    } elseif (stripos($password, $nombreUsuario) !== false) {
        $this->errores['password'] = 'La contraseña no puede contener tu nombre de usuario.';
    } else {
        // Fortaleza de la contraseña
        $puntos = 0;
        $puntos += preg_match('/[A-Z]/', $password) ? 2 : 0; // Sumar puntos si hay letras mayúsculas
        $puntos += preg_match('/[a-z]/', $password) ? 1 : 0; // Sumar puntos si hay letras minúsculas
        $puntos += preg_match('/[0-9]/', $password) ? 2 : 0; // Sumar puntos si hay números
        $puntos += preg_match('/[^a-zA-Z0-9]/', $password) ? 3 : 0; // Sumar puntos si hay caracteres especiales

        // Puntuación de la contraseña
        if ($puntos < 5) {
            $this->errores['password'] = 'La contraseña es débil. Debe incluir al menos 8 caracteres, letras mayúsculas y minúsculas, números y caracteres especiales.';
        } else {
            // La contraseña es fuerte
        }

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
    if (!in_array($rol, ['free', 'premium','critico'])) {
        $this->errores['rol'] = 'Elige un rol válido';
    }

    if (count($this->errores) === 0) {
        
        $usuario = Usuario::buscaUsuario($nombreUsuario);
    
        if ($usuario) {
            $this->errores[] = "El usuario ya existe";
        } else {
            $usuario = Usuario::crea($nombreUsuario, $password, null, $rol, $email, $foto);
        }
    }
}
}
