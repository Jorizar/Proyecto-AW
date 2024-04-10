<?php
namespace es\ucm\fdi\aw\favoritos;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;
use es\ucm\fdi\aw\peliculas\Pelicula;

class Favorito
{
    use MagicProperties;

    
    //Se utiliza cuando se registra una nueva pelicula
    public static function crea($user_id, $pelicula_id)
    {
        $favorito = new Favorito($user_id, $pelicula_id);
        return $favorito->guarda();
    }
    


    //Plantear si la necesitamos o no 
    //TO DO
    public static function buscaPorUser($user_id)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM favoritos WHERE user_id=%d", $user_id);
        $result = $conn->query($query);
        $favoritos = false;
        if ($result) {
            /*$favoritos = array();
            while($fila = $result->fetch_assoc()) {
                $favoritos[] = new Favorito($fila['user_id'], $fila['pelicula_id']);
            }*/
            //$result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    //Inserta una pelicula nueva en la base de datos
    private static function inserta($favorito)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        // Comprobamos si ya existe una entrada con los mismos user_id y pelicula_id
        $query = sprintf("SELECT id FROM favoritos WHERE user_id = '%d' AND pelicula_id = '%d'",
            $favorito->user_id,
            $favorito->pelicula_id
        );
    
        $result = $conn->query($query);
    
        if ($result && $result->num_rows > 0) {
            // Ya existe una pareja de datos
            return false;
        }
    
        // No existe, procedemos a la inserción
        $query = sprintf("INSERT INTO favoritos (user_id, pelicula_id) VALUES ('%d', '%d')",
            $favorito->user_id,
            $favorito->pelicula_id
        );
    
        if ($conn->query($query)) {
            $favorito->id = $conn->insert_id;
            return $favorito;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
    
   
    
    private static function borra($favorito)
    {
        return self::borraPorId($favorito->id);
    }

    private static function borraPorId($idFavorito)
    {
        if (!$idFavorito) {
            return false;
        } 
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM favoritos F WHERE F.id = %d", $idFavorito);
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }
    
    private static function borraPorUser($user_id)
    {
        if (!$user_id) {
            return false;
        } 
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM favoritos F WHERE F.user_id = %d", $user_id);
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    private static function borraPorPelicula($pelicula_id)
    {
        if (!$pelicula_id) {
            return false;
        } 
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM favoritos F WHERE F.pelicula_id = %d", $pelicula_id);
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }
    
    
    private $id; //Id que identifica a la película en la base de datos

    private $user_id;

    private $pelicula_id;

    private function __construct($user_id, $pelicula_id, $id = null)
    {
        $this->user_id = $user_id;
        $this->pelicula_id = $pelicula_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user_id;
    }

    public function getPelicula(){
        return $this->pelicula_id;
    }

    //Si la instancia no posee id no se ha insertado todavía en la base de datos y se inserta. Si no, llama a actualiza para cambiar 
    //la información de la película
    public function guarda()
    {
        if ($this->id === null) {
            return self::inserta($this);
        }
    }
    
    //LLama a borra(). Sirve para eliminar un usuario de la base de datos
    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }
}
