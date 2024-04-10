<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/src/usuarios/Usuario.php';

if (!$app->tieneRol('admin')) {
    die("Acceso restringido a administradores.");
}

$tituloPagina = 'Administrar Usuarios';
$contenidoPrincipal = '<h3>Lista de Usuarios</h3>';

$usuarios = es\ucm\fdi\aw\usuarios\Usuario::buscarTodos();

if (!empty($usuarios)) {
    $contenidoPrincipal .= '<table><thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Acción</th></tr></thead><tbody>';
    foreach ($usuarios as $usuario) {
        $contenidoPrincipal .= "<tr>
                                    <td>" . htmlspecialchars($usuario['user_id']) . "</td>
                                    <td>" . htmlspecialchars($usuario['username']) . "</td>
                                    <td>" . htmlspecialchars($usuario['email']) . "</td>
                                    <td>
                                        <form method='post' action='includes/src/usuarios/eliminar_usuario.php'>
                                            <input type='hidden' name='usuario_id' value='" . $usuario['user_id'] . "'>
                                            <input type='submit' value='Eliminar' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este usuario?\");'>
                                        </form>
                                    </td>
                                </tr>";
    }
    $contenidoPrincipal .= '</tbody></table>';
} else {
    $contenidoPrincipal .= '<p>No hay usuarios registrados.</p>';
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
