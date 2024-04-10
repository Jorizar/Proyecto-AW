<?php
require_once __DIR__.'/includes/config.php';

// Verificar si se proporcionó un ID de noticia en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idNoticia = $_GET['id'];

    // Obtener la conexión a la base de datos desde la instancia de la aplicación
    $app = \es\ucm\fdi\aw\Aplicacion::getInstance();
    $conexion = $app->getConexionBd();

    // Consultar la base de datos para obtener la noticia con el ID proporcionado
    $query = "SELECT titulo, texto FROM noticias WHERE post_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $idNoticia);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si se encontró la noticia, obtener sus detalles
        $stmt->bind_result($tituloNoticia, $contenidoNoticia);
        $stmt->fetch();

        // Construir el contenido de la página
        $tituloPagina = $tituloNoticia;
        $contenidoPrincipal = "<h2>$tituloNoticia</h2>";
        $contenidoPrincipal .= "<p>$contenidoNoticia</p>";
    } else {
        // Si no se encuentra la noticia, mostrar un mensaje de error
        $tituloPagina = 'Error';
        $contenidoPrincipal = '<p>La noticia solicitada no se encontró.</p>';
    }

    $stmt->close();
} else {
    // Si no se proporcionó un ID de noticia válido, redirigir a la página de inicio
    header('Location: index.php');
    exit();
}

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
?>
