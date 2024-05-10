<?php
namespace es\ucm\fdi\aw\likes;

use es\ucm\fdi\aw\Aplicacion;

class Like
{
    private $id;
    private $user_id;
    private $comentario_id;

    // Constructor
    public function __construct($user_id, $comentario_id, $id = null)
    {
        $this->user_id = $user_id;
        $this->comentario_id = $comentario_id;
        $this->id = $id;
    }

    public static function crea($user_id, $comentario_id)
    {
        $like = new Like($user_id, $comentario_id);
        return $like->guarda() ? $like : null;
    }

    private function guarda()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        if ($this->id === null) {
            $stmt = $conn->prepare("INSERT INTO likes (user_id, comentario_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $this->user_id, $this->comentario_id);
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

   
    public static function existe($user_id, $comentario_id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $stmt = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND comentario_id = ?");
        $stmt->bind_param("ii", $user_id, $comentario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $existe = $result->num_rows > 0;
        $stmt->close();
        return $existe;
    }

 
    public static function elimina($user_id, $comentario_id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $stmt = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND comentario_id = ?");
        $stmt->bind_param("ii", $user_id, $comentario_id);
        $resultado = $stmt->execute();
        $stmt->close();
        return $resultado;
    }

   
    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getComentarioId()
    {
        return $this->comentario_id;
    }
}
?>
