<?php
namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\peliculas\Pelicula;

class Comentario
{
    private $comentario_id;
    private $user_id;
    private $pelicula_id;
    private $texto;
    private $valoracion;
    private $likes_count = 0;
    private $hora;  

    public function __construct($user_id, $pelicula_id, $texto, $valoracion, $comentario_id = null, $likes_count = 0, $hora = null)
    {
        $this->comentario_id = $comentario_id;
        $this->user_id = $user_id;
        $this->pelicula_id = $pelicula_id;
        $this->texto = $texto;
        $this->valoracion = $valoracion;
        $this->likes_count = $likes_count;
        $this->hora = $hora; 
    }

    public static function crea($user_id, $pelicula_id, $texto, $valoracion, $likes_count)
    {
        $comentario = new Comentario($user_id, $pelicula_id, $texto, $valoracion, null, $likes_count);
        return $comentario->guarda();
    }

    private function guarda()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "INSERT INTO comentarios (user_id, pelicula_id, texto, valoracion, likes_count) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('iissi', $this->user_id, $this->pelicula_id, $this->texto, $this->valoracion, $this->likes_count);
            if ($stmt->execute()) {
                $this->comentario_id = $stmt->insert_id;
                $stmt->close();
                return $this;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                $stmt->close();
                return null;
            }
        } else {
            error_log("Error preparing statement: " . $conn->error);
            return null;
        }
    }

    public static function buscarPorPeliculaId($pelicula_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM comentarios WHERE pelicula_id = ? ORDER BY likes_count DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $pelicula_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comentarios = [];
        while ($fila = $result->fetch_assoc()) {
            $comentarios[] = new Comentario($fila['user_id'], $fila['pelicula_id'], $fila['texto'], $fila['valoracion'], $fila['comentario_id'], $fila['likes_count'], $fila['hora']);
        }
        $stmt->close();
        $result->free();
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
            $comentarios[] = new Comentario($fila['user_id'], $fila['pelicula_id'], $fila['texto'], $fila['valoracion'], $fila['comentario_id'], $fila['likes_count'], $fila['hora']);
        }
        $stmt->close();
        $result->free();
        return $comentarios;
    }

    public static function buscarTodos() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM comentarios";
        $result = $conn->query($sql);
    
        $comentarios = [];
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $comentarios[] = new Comentario($fila['user_id'], $fila['pelicula_id'], $fila['texto'], $fila['valoracion'], $fila['comentario_id'], $fila['likes_count'], $fila['hora']);
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
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

    public static function traducePeli($peli_id){
        return Pelicula::buscaTituloPorId($peli_id);
    }

    public static function traduceUser($user_id){
        return Usuario::buscaNombrePorId($user_id);
    }

    public static function cambiarTexto($comentario_id, $nuevoComent){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE comentarios SET texto='%s' WHERE comentario_id=%d",
            $conn->real_escape_string($nuevoComent),
            $comentario_id
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarValoracion($comentario_id, $valoracionNueva){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE comentarios SET valoracion='%s' WHERE comentario_id=%d",
            $conn->real_escape_string($valoracionNueva),
            $comentario_id
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function eliminarPorId($comentario_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "DELETE FROM comentarios WHERE comentario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $comentario_id);
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }    

    public static function buscarComentPorId($comentario_id){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM comentarios WHERE comentario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $comentario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $fila = $result->fetch_assoc();
        $comentario = new Comentario($fila['user_id'], $fila['pelicula_id'], $fila['texto'], $fila['valoracion'], $fila['comentario_id'], $fila['likes_count'], $fila['hora']);
        
        $stmt->close();
        $result->free();
        
        return $comentario;
    }

    public static function usuarioYaComentoPelicula($user_id, $pelicula_id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM comentarios WHERE user_id = ? AND pelicula_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $user_id, $pelicula_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $yaComento = $result->num_rows > 0;
        $stmt->close();
        $result->free();
        return $yaComento;
    }
    

    public function getHora() {
        return $this->hora;
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
    public function getUserId() {
        return $this->user_id;
    }
    public function getLikesCount() {
        return $this->likes_count;
    }
}
?>
