<?php
require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\listas\FormularioCreaLista;
use es\ucm\fdi\aw\listas\Lista;

$tituloPagina = 'Crear Lista';
$contenidoPrincipal = '';

// Comprobamos si el usuario está logueado
if ($app->usuarioLogueado()) {
    // Inicializamos el formulario de creación de lista
    $formCreaLista = new FormularioCreaLista();
    $formCreaLista = $formCreaLista->gestiona();

    $contenidoPrincipal .= <<<EOS
        <h2>Crea una nueva lista</h2>
        <div class ="form_nueva_lista">
            $formCreaLista
        </div>
    EOS;
} else {
    // Si el usuario no está logueado, mostramos un mensaje de error
    $contenidoPrincipal .= '<p>Debes iniciar sesión para crear una lista.</p>';
}

// Agregar enlace para volver a Mis Listas
$contenidoPrincipal .= '<a href="misListas.php" class="enlace-mis-listas">Volver a Mis Listas</a>';

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
