<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Mi Perfil';
$contenidoPrincipal='';


if ($app->usuarioLogueado()) {
    $nombreUsuario = $app->nombreUsuario();
    $plan = $app->rol();
    $fotoPerfil = $app->fotoPerfil();
    $urlDatos = $app->resuelve('/includes/src/usuarios/FormularioCambioDatos.php');
    $urlPlan = $app->resuelve('/includes/src/usuarios/FormularioCambioPlan.php');
    $urlComentarios = $app->resuelve('/misComentarios.php');
    $urlListas = $app->resuelve('/misListas.php');

    $contenidoPrincipal=<<<EOS
    <h2>Mi Perfil</h2>
    <div style="display: flex; align-items: center;">
        <div style="margin-right: 20px;">
            <img src="${fotoPerfil}" alt='Foto de perfil' width='100' height='100'>;
        </div>
        <div>
            <p>@usuario: ${nombreUsuario}</p>;                        
            <p>Plan: ${plan}</p>;
        </div>
    </div>
    <div style="margin-top: 20px;">
        <div style="text-align: right;">
            <a href="${urlDatos}">Cambiar Datos</a><br>
            <a href="${urlPlan}">Cambiar Plan</a><br>
            <a href="${urlComentarios}">Mis Comentarios</a><br>
            <a href="${urlListas}">Mis Listas</a><br>
        </div>
    </div>
EOS;
} else {
  $contenidoPrincipal=<<<EOS
    <h1>Usuario no registrado!</h1>
    <p>Debes iniciar sesión para ver el contenido.</p>
EOS;
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
