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
    $urlAdminUsuarios = $app->resuelve('/admin_usuarios.php');
    $urlAdminNoticias= $app->resuelve('/admin_noticias.php');

    // Contenido principal del perfil del usuario
    $contenidoPrincipal = <<<EOS
    <h2>Mi Perfil</h2>
    <div class="info-perfil">
        <div>
            <img src="${fotoPerfil}" alt='Foto de perfil' class='imagen-perfil'>
        </div>
        <div>
            <p class='nombre-perfil'>@usuario: ${nombreUsuario}</p>                        
            <p>Plan: ${plan}</p>
        </div>
    </div>
   
EOS;

    // Agregar el enlace para administradores
    if ($_SESSION['rol'] === 'admin') {
        $contenidoPrincipal .= '<a class="enlace-perfil-admin" href="' . $urlAdminComentarios . '">ADMINISTRAR COMENTARIOS</a><br>';
        $contenidoPrincipal .= '<a class="enlace-perfil-admin" href="' . $urlAdminPeliculas . '">ADMINISTRAR PELÍCULAS</a><br>';
        $contenidoPrincipal .= '<a class="enlace-perfil-admin" href="' . $urlAdminUsuarios . '">ADMINISTRAR USUARIOS</a><br>';
        $contenidoPrincipal .= '<a class="enlace-perfil-admin" href="' . $urlAdminNoticias . '">ADMINISTRAR NOTICIAS</a><br>';

    }

    $contenidoPrincipal .= <<<EOS
            <a href="${cambioDatosUrl}" class='enlace-perfil'>Cambiar Datos</a><br>
            <a href="${cambioPlanUrl}" class='enlace-perfil'>Cambiar Plan</a><br>
            <a href="${urlComentarios}" class='enlace-perfil'>Mis Comentarios</a><br>
            <a href="${urlListas}" class='enlace-perfil'>Mis Listas</a><br>
            <a href="${cerrarSesionUrl}" class='enlace-perfil'>Cerrar Sesión</a><br>
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
