<?php
namespace es\ucm\fdi\aw\favoritos;

use es\ucm\fdi\aw\Aplicacion;

class Favorito
{
    private $id;
    private $user_id;
    private $pelicula_id;

    // Constructor 
    public function __construct($user_id, $pelicula_id, $id = null)
    {
        $this->user_id = $user_id;
        $this->pelicula_id = $pelicula_id;
        $this->id = $id;
    }

    //Crea un favorito y lo guarda en la bbdd
    public static function crea($user_id, $pelicula_id)
    {
        $favorito = new Favorito($user_id, $pelicula_id);
        return $favorito->guarda() ? $favorito : null;
    }

    //Guarda un favorito en la base de datos
    private function guarda()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        if ($this->id === null) {
            $stmt = $conn->prepare("INSERT INTO favoritos (user_id, pelicula_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $this->user_id, $this->pelicula_id);
            if ($stmt->execute()) {
                $this->id = $stmt->insert_id;
                $stmt->close();
                return true;
            } else {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                $stmt->close();
                return false;
            }
        }

        return false;
    }

    //Busca los favoritos por el id de usuario
    public static function buscaPorUser($user_id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $stmt = $conn->prepare("SELECT * FROM favoritos WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $favoritos = [];
        while ($fila = $result->fetch_assoc()) {
            $favoritos[] = new self($fila['user_id'], $fila['pelicula_id'], $fila['id']);
        }

        $stmt->close(); // Liberar el recurso
        return $favoritos;
    }

    // Elimina un favorito por el id de usuario y el id de película
    public static function eliminaPorIdUsuarioYIdPelicula($userId, $peliculaId)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $stmt = $conn->prepare("DELETE FROM favoritos WHERE user_id = ? AND pelicula_id = ?");
        $stmt->bind_param("ii", $userId, $peliculaId);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

    // Comprueba si un favorito está asocidado a un usuario en la base de datos
    public static function existe($userId, $peliculaId)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $stmt = $conn->prepare("SELECT * FROM favoritos WHERE user_id = ? AND pelicula_id = ?");
        $stmt->bind_param("ii", $userId, $peliculaId);
        $stmt->execute();
        $result = $stmt->get_result();
        $existe = $result->num_rows > 0;
        $stmt->close(); 
        return $existe;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getPelicula()
    {
        return $this->pelicula_id;
    }
}
?>
