<?php
namespace es\ucm\fdi\aw\favoritos;

use es\ucm\fdi\aw\Aplicacion;

class Favorito
{
    private $id;
    private $user_id;
    private $pelicula_id;

    // Constructor with optional $id for existing entries
    public function __construct($user_id, $pelicula_id, $id = null)
    {
        $this->user_id = $user_id;
        $this->pelicula_id = $pelicula_id;
        $this->id = $id;
    }

    // Static method for creation and saving instance
    public static function crea($user_id, $pelicula_id)
    {
        $favorito = new Favorito($user_id, $pelicula_id);
        return $favorito->guarda() ? $favorito : null;
    }

    // Method to save instance into database
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
        // No update logic as favoritos are typically added or removed
        return false;
    }

    // Static method to fetch Favorito instances by user ID
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

        $stmt->close();
        return $favoritos;
    }

    // Getters
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
