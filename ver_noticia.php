<?php
require_once __DIR__.'/includes/config.php';
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\noticias\Noticia;

// Verificar si se proporcionó un ID de noticia en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idNoticia = $_GET['id'];

    // Obtener la conexión a la base de datos desde la instancia de la aplicación
    $app = Aplicacion::getInstance();
    $conexion = $app->getConexionBd();

    // Consultar la base de datos para obtener la noticia con el ID proporcionado
    $smnt = Noticia::buscaPorId($idNoticia);

    if ($smnt) {
        // Si se encontró la noticia, obtener sus detalles

        $rol = $smnt->getRol();

        if ($rol == 1 && (!$app->usuarioLogueado() || $_SESSION['rol'] == "free")){
            header('Location: index.php');
            exit();
        }
        $tituloNoticia = $smnt->getTitulo();
        $contenidoNoticia = $smnt->getTexto();
        // Construir el contenido de la página
        $tituloPagina = $tituloNoticia;
        $contenidoPrincipal = "<h2>$tituloNoticia</h2>";
        $contenidoPrincipal .= "<p>$contenidoNoticia</p>";
    } else {
        // Si no se encuentra la noticia, mostrar un mensaje de error
        $tituloPagina = 'Error';
        $contenidoPrincipal = '<p>La noticia solicitada no se encontró.</p>';
    }


} else {
    // Si no se proporcionó un ID de noticia válido, redirigir a la página de inicio
    header('Location: index.php');
    exit();
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
