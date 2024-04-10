<?php
require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Mi Perfil';
$contenidoPrincipal='';

if ($app->usuarioLogueado()) {
    $nombreUsuario = $app->nombreUsuario();
    $plan = $app->rol();
    $fotoPerfil = $app->fotoPerfil();
    $cambioDatosUrl = $app->resuelve('/cambioDatos.php');
    $cambioPlanUrl = $app->resuelve('/cambioPlan.php');
    $urlComentarios = $app->resuelve('/misComentarios.php');
    $urlListas = $app->resuelve('/misListas.php');
    $cerrarSesionUrl = $app->resuelve('/logout.php'); // URL para cerrar sesión
    $urlAdminComentarios = $app->resuelve('/admin_comentarios.php');
    $urlAdminPeliculas = $app->resuelve('/admin_peliculas.php');
    $urlAdminUsuarios = $app->resuelve('/admin_ususarios.php');

    // Contenido principal del perfil del usuario
    $contenidoPrincipal = <<<EOS
    <h2>Mi Perfil</h2>
    <div style="display: flex; align-items: center;">
        <div style="margin-right: 20px;">
            <img src="${fotoPerfil}" alt='Foto de perfil' width='100' height='100'>
        </div>
        <div>
            <p>@usuario: ${nombreUsuario}</p>                        
            <p>Plan: ${plan}</p>
        </div>
    </div>
    <div style="margin-top: 20px;">
        <div style="text-align: right;">
EOS;

    // Agregar el enlace para administradores
    if ($_SESSION['rol'] === 'admin') {
        $contenidoPrincipal .= "<a href='${urlAdminComentarios}'>ADMINISTRAR COMENTARIOS</a><br>";
        $contenidoPrincipal .= "<a href='${urlAdminPeliculas}'>ADMINISTRAR PELICULAS</a><br>";
        $contenidoPrincipal .= "<a href='${urlAdminUsuarios}'>ADMINISTRAR USUARIOS</a><br>";
    }

    $contenidoPrincipal .= <<<EOS
            <a href="${cambioDatosUrl}">Cambiar Datos</a><br>
            <a href="${cambioPlanUrl}">Cambiar Plan</a><br>
            <a href="${urlComentarios}">Mis Comentarios</a><br>
            <a href="${urlListas}">Mis Listas</a><br>
            <!-- Enlace para cerrar sesión -->
            <a href="${cerrarSesionUrl}">Cerrar Sesión</a><br>
        </div>
    </div>
EOS;

} else {
    $contenidoPrincipal = <<<EOS
    <h1>Usuario no registrado!</h1>
    <p>Debes iniciar sesión para ver el contenido.</p>
EOS;
}



$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
