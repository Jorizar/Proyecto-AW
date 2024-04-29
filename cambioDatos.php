<?php

require_once __DIR__.'/includes/config.php';

use es\ucm\fdi\aw\usuarios\FormularioCambioDatos;
use es\ucm\fdi\aw\usuarios\FormularioCambioAdmin;


if (!$app->tieneRol('admin')) {

  $formCambioDatos = new FormularioCambioDatos();
  $formCambioDatos = $formCambioDatos->gestiona();

  $tituloPagina = 'Cambiar Datos';
  $contenidoPrincipal=<<<EOF
    <div class="titulo_cambiarDatos">
        <h1>Cambiar Datos</h1>
    </div>
    <div class="contenedor_cambiarDatos">
        $formCambioDatos
    </div>
  EOF;
}
else{

  // Obtenemos el id de usuario
  $usuario_id = filter_input(INPUT_POST, 'usuario_id', FILTER_SANITIZE_NUMBER_INT);
 
  $formCambioDatosAdmin = new FormularioCambioAdmin($usuario_id);
  $formCambioDatosAdmin = $formCambioDatosAdmin->gestiona();

  //$_SESSION['idUsuario'] = $usuario_id;
  $tituloPagina = 'Cambio de Datos Administrador';
    $contenidoPrincipal=<<<EOF
    <div class="titulo_cambiarDatos">
        <h1>Cambio de Datos Administrador</h1>
    </div>
    <div class="contenedor_cambiarDatos">
        $formCambioDatosAdmin
    </div>
  EOF;
}


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);