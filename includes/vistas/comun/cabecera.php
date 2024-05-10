<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\FormularioLogout;

$app = Aplicacion::getInstance();

function mostrarPerfil()
{
    $html = '';
    $app = Aplicacion::getInstance();
    if ($app->usuarioLogueado()) {
        $fotoPerfil = $app->fotoPerfil();
        $perfilUrl = $app->resuelve('/perfil.php');
        $nombrePerfil = $app->nombreUsuario();

        $html .= "<div class='perfil-info'>";
        $html .= "<a href='$perfilUrl'><img src='$fotoPerfil' alt='Foto de perfil' class='imagen-perfil-cabecera'></a>";
        $html .= "<a href='$perfilUrl' class='nombre-perfil-cabecera'>$nombrePerfil</a>"; 
        $html .= "</div>";

    } else {
        // Si el usuario no está logueado, mostrar opciones de inicio de sesión y registro
        $loginUrl = $app->resuelve('/login.php');
        $registroUrl = $app->resuelve('/registro.php');

        $html = "<div class='contenedor-login-registro'>";
        $html .= "<a href='$loginUrl'>Iniciar sesión</a> | <a href='$registroUrl'>Registrarse</a>";
        $html .= "</div>";
    }

    return $html;
}

?>
<header>
    <div class="logo">
        <a href="<?= $app->resuelve('/index.php')?>">
            <img src="./img/logo_proyecto.png" alt="Logo">
        </a>
    </div>
    <div class="centro">
    <nav class="menu">
        <a href="<?= $app->resuelve('/index.php')?>" class="nav-enlace">Inicio</a>
        <a href="<?= $app->resuelve('/noticias.php')?>" class="nav-enlace">Noticias</a>
        <a href="<?= $app->resuelve('/peliculas_fav.php')?>" class="nav-enlace">Películas<img src="./img/fav.png" alt="Películas" width="50" height="50"></a>
        <a href="<?= $app->resuelve('/misListas.php')?>" class="nav-enlace">Listas</a>
    </nav>
</div>
    <div class="saludo-menu-container">
        <div class="saludo">
            <?= mostrarPerfil(); ?>
        </div>
    </div>
</header>

