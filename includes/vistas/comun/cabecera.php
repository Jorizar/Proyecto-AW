<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\FormularioLogout;

function mostrarSaludo()
{
    $html = '';
    $app = Aplicacion::getInstance();
    if ($app->usuarioLogueado()) {
        $nombreUsuario = $app->nombreUsuario();

        $formLogout = new FormularioLogout();
        $htmlLogout = $formLogout->gestiona();
        $html = "Bienvenido, ${nombreUsuario}. $htmlLogout";
    } else {
        $loginUrl = $app->resuelve('/login.php');
        $registroUrl = $app->resuelve('/registro.php');
        $html = <<<EOS
        Usuario desconocido. <a href="{$loginUrl}">Login</a> <a href="{$registroUrl}">Registro</a>
      EOS;
    }

    return $html;
}

?>
<header>
    <div class ="logo">
        <img src="./img/logo_proyecto.jpg" alt="Logo">
    </div>
    <div class="saludo-menu-container"> <!-- Contenedor para el saludo y el menú -->
        <nav class="menu">
            <a href="<?= $app->resuelve('/index.php')?>">Inicio</a>
            <a href="<?= $app->resuelve('/admin.php')?>">Administrar</a>
            <a href="<?= $app->resuelve('/noticias.php')?>">Noticias</a>
            <a href="<?= $app->resuelve('/peliculas_fav.php')?>"> Películas <img src="/img/fav.png" alt="Películas" width="50" height="50"></a>
            <a href="<?= $app->resuelve('/perfil.php')?>">Perfil</a>
        </nav>
        <div class="saludo">
            <?= mostrarSaludo(); ?>
        </div>
    </div>
</header>