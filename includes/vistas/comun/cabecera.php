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
        $html .= "<a href='$perfilUrl'><img src='$fotoPerfil' alt='Foto de perfil' class='imagen-perfil'></a>";
        $html .= "<a href='$perfilUrl' class='nombre-perfil'>$nombrePerfil</a>"; 
        $html .= "</div>";

        //$html = "<a href='$perfilUrl'><img src='$fotoPerfil' alt='Foto de perfil' class='imagen-perfil'></a>";
    } else {
        // Si el usuario no está logueado, mostrar opciones de inicio de sesión y registro
        $loginUrl = $app->resuelve('/login.php');
        $registroUrl = $app->resuelve('/registro.php');

        $html = "<a href='$loginUrl'>Iniciar sesión</a> | <a href='$registroUrl'>Registrarse</a>";
    }

    return $html;
}

?>

<body>
<header>
    <div class="logo">
        <a href="<?= $app->resuelve('/index.php')?>">
            <img src="./img/logo_proyecto.jpg" alt="Logo">
        </a>
    </div>
    <div class="saludo-menu-container"> <!-- Contenedor para el saludo y el menú -->
        <nav class="menu">
            <a href="<?= $app->resuelve('/index.php')?>">Inicio</a>
            <a href="<?= $app->resuelve('/admin.php')?>">Administrar</a>
            <a href="<?= $app->resuelve('/noticias.php')?>">Noticias</a>
            <a href="<?= $app->resuelve('/peliculas_fav.php')?>">Películas <img src="./img/fav.png" alt="Películas" width="50" height="50"></a>
        </nav>
        <div class="saludo">
            <?= mostrarPerfil(); ?>
        </div>
    </div>
</header>
</body>
