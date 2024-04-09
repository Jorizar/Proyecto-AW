<?php
namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;

class Comentario
{
    private $comentario_id;
    private $user_id;
    private $pelicula_id;
    private $texto;
    private $valoracion;

    public function __construct($user_id, $pelicula_id, $texto, $valoracion, $comentario_id = null)
    {
        $this->comentario_id = $comentario_id;
        $this->user_id = $user_id;
        $this->pelicula_id = $pelicula_id;
        $this->texto = $texto;
        $this->valoracion = $valoracion;
    }

    public static function crea($user_id, $pelicula_id, $texto, $valoracion)
    {
        $comentario = new Comentario($user_id, $pelicula_id, $texto, $valoracion);
        return $comentario->guarda();
    }

    private function guarda()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "INSERT INTO comentarios (user_id, pelicula_id, texto, valoracion) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisi', $this->user_id, $this->pelicula_id, $this->texto, $this->valoracion);
        if ($stmt->execute()) {
            $this->comentario_id = $stmt->insert_id;
            return $this;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return null;
        }
    }

    public static function buscarPorPeliculaId($pelicula_id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM comentarios WHERE pelicula_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $pelicula_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comentarios = [];
        while ($fila = $result->fetch_assoc()) {
            $comentarios[] = new Comentario($fila['user_id'], $fila['pelicula_id'], $fila['texto'], $fila['valoracion'], $fila['comentario_id']);
        }
        return $comentarios;
    }

    public static function buscarPorUsuarioId($user_id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM comentarios WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comentarios = [];
        while ($fila = $result->fetch_assoc()) {
            $comentarios[] = new Comentario($fila['user_id'], $fila['pelicula_id'], $fila['texto'], $fila['valoracion'], $fila['comentario_id']);
        }
        return $comentarios;
    }

    public function actualiza($texto, $valoracion)
    {
        $this->texto = $texto;
        $this->valoracion = $valoracion;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "UPDATE comentarios SET texto = ?, valoracion = ? WHERE comentario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sii', $texto, $valoracion, $this->comentario_id);
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public function borra()
    {
        if ($this->comentario_id !== null) {
            $conn = Aplicacion::getInstance()->getConexionBd();
            $sql = "DELETE FROM comentarios WHERE comentario_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $this->comentario_id);
            if ($stmt->execute()) {
                $this->comentario_id = null;
                return true;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return false;
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
    public function getComentarioId() {
        return $this->comentario_id;
    }

}
