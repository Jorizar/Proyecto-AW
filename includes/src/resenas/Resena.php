<?php
namespace es\ucm\fdi\aw\resenas;

use es\ucm\fdi\aw\Aplicacion;

class Resena
{
    private $reseña_id;
    private $user_id;
    private $pelicula_id;
    private $texto;
    private $valoracion;

    public function __construct($user_id, $pelicula_id, $texto, $valoracion, $reseña_id = null)
    {
        $this->reseña_id = $reseña_id;
        $this->user_id = $user_id;
        $this->pelicula_id = $pelicula_id;
        $this->texto = $texto;
        $this->valoracion = $valoracion;
    }

    public static function crea($user_id, $pelicula_id, $texto, $valoracion)
    {
        $reseña = new self($user_id, $pelicula_id, $texto, $valoracion);
        return $reseña->guarda();
    }

    private function guarda()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "INSERT INTO reseñas (user_id, pelicula_id, texto, valoracion) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisi', $this->user_id, $this->pelicula_id, $this->texto, $this->valoracion);
        if ($stmt->execute()) {
            $this->reseña_id = $stmt->insert_id;
            return $this;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return null;
        }
    }

    public static function buscarPorPeliculaId($pelicula_id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM reseñas WHERE pelicula_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $pelicula_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reseñas = [];
        while ($fila = $result->fetch_assoc()) {
            $reseñas[] = new self($fila['user_id'], $fila['pelicula_id'], $fila['texto'], $fila['valoracion'], $fila['reseña_id']);
        }
        $stmt->close(); // Liberar el recurso
        return $reseñas;
    }

    public static function eliminarPorId($reseña_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "DELETE FROM reseñas WHERE reseña_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $reseña_id);
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            $stmt->close(); 
            return false;
        }
    }

    public function getTexto() {
        return $this->texto;
    }
    public function getValoracion() {
        return $this->valoracion;
    }
    public function getPeliculaId() {
        return $this->pelicula_id;
    }
    public function getReseñaId() {
        return $this->reseña_id;
    }
    public function getUserId() {
        return $this->user_id;
    }
}
?>
