<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Pelicula
{
    use MagicProperties;

    
    //Se utiliza cuando se registra una nueva pelicula
    public static function crea($titulo, $director, $annio, $genero, $sinopsis, $portada, $reparto, $val_imdb)
    {
        $pelicula = new Pelicula($titulo, $director, $annio, $genero, $sinopsis, $portada, $reparto, $val_imdb);
        return $pelicula->guarda();
    }
    

    //Se utilizaría en el buscador. Hay que hacer que el título coincida con alguna película de la base de datos
    // TO DO
    public static function buscaPelicula($titulo)
    {
        return self::buscaPorId($pelicula->id);
    }

    //Plantear si la necesitamos o no 
    //TO DO
    public static function buscaPorId($idPelicula)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM peliculas WHERE id=%d", $idPelicula);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Pelicula($fila['username'], $fila['password'], $fila['user_id'], $fila['rol'], $fila['email'], $fila['foto']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
   
    //Inserta una pelicula nueva en la base de datos
    private static function inserta($pelicula)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO peliculas(titulo, director, annio, genero, sinopsis, portada, reparto, Val_IMDb) VALUES ('%s', '%s', '%d', '%s', '%s', '%s', '%0.1f')"
            , $conn->real_escape_string($pelicula->titulo)
            , $conn->real_escape_string($pelicula->director)
            , $conn->real_escape_string($pelicula->annio)
            , $conn->real_escape_string($pelicula->genero)
            , $pelicula->sinopsis
            , $pelicula->reparto
            , $pelicula->val_imdb
        );
        if ( $conn->query($query) ) {
            $pelicula->id = $conn->insert_id;
            return $pelicula;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
    
    //Actualiza la información del usuario en la base de datos
    private static function actualiza($usuario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE Usuarios U SET username = '%s', password='%s', rol='%s', email='%s', foto='%s' WHERE U.user_id=%d"
            , $conn->real_escape_string($usuario->nombreUsuario)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->rol)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->foto)
            , $usuario->id
        );
        if ( $conn->query($query) ) {
            return $usuario;
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
     
    private static function borra($pelicula)
    {
        return self::borraPorId($pelicula->id);
    }
    
    private static function borraPorId($idPelicula)
    {
        if (!$idPelicula) {
            return false;
        } 
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM peliculas P WHERE P.id = %d"
            , $idPelicula
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }
    
    private $titulo;    //Titulo de la película

    private $director;  //Director de la película
    
    private $id; //Id que identifica a la película en la base de datos

    private $annio; //Año de estreno

    private $genero;  //Género de la película

    private $sinopsis;   //Breve descripción de la trama

    private $portada;  //Ruta de la imagen que carga la portada

    private $reparto;  //JSON con el reparto de la película

    private $val_imdb;   //Valoración IMDb

    $titulo, $director, $annio, $genero, $sinopsis, $portada, $reparto, $val_imdb

    private function __construct($titulo, $director, $id = null, $annio, $genero, $sinopsis, $portada, $reparto, $val_imdb)
    {
        $this->titulo = $titulo;
        $this->director = $director;
        $this->id = $id;
        $this->annio = $annio;
        $this->genero = $genero;
        $this->sinopsis = $sinopsis;
        $this->portada = $portada;
        $this->reparto = $reparto;
        $this->val_imdb = $val_imdb;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDirector(){
        return $this->director;
    }

    public function getAnnio(){
        return $this->annio;
    }

    public function getGenero(){
        return $this->genero;
    }

    public function getSinopsis(){
        return $this->sinopsis;
    }

    public function getPortada(){
        return $this->portada;
    }

    public function getReparto(){
        return $this->reparto;
    }

    public function getVal_IMDb(){
        return $this->val_imdb;
    }


    

    //Si la instancia no posee id no se ha insertado todavía en la base de datos y se inserta. Si no, llama a actualiza para cambiar 
    //la información de la película
    public function guarda()
    {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
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
