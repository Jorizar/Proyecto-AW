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
    public static function buscaPelicula($titulo, $director, $genero, $annio)
    {   
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT * FROM peliculas WHERE 1=1";    
        if (!empty($titulo)) {
            $sql .= " AND LOWER(titulo) LIKE LOWER('%$titulo%')";
        }
        if (!empty($director)) {
            $sql .= " AND LOWER(director) LIKE LOWER('%$director%')";
        }
        if (!empty($genero)) {
            $sql .= " AND genero = $genero";
        }
        if (!empty($anno)) {
            $sql .= " AND annio = $annio";
        }
        $result = $conn->query($sql);
        $peliculas = false;
        if ($result) {
            $peliculas = array();
            while($fila = $result->fetch_assoc()) {
                $peliculas[] = new Pelicula($fila['titulo'], $fila['director'],$fila['id'], $fila['annio'], $fila['genero'], $fila['sinopsis'], $fila['portada'], $fila['reparto'], $fila['Val_IMDb']);
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $peliculas;
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
                $result = new Pelicula($fila['titulo'], $fila['director'], $fila['id'], $fila['annio'], $fila['genero'], $fila['sinopsis'], $fila['portada'], $fila['reparto'], $fila['Val_IMDb']);
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
            , $pelicula->genero
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
    
    //Actualiza la información de la película en la base de datos
    private static function actualiza($pelicula)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        // Check if $conn is a valid connection
        if ($conn->connect_error) {
            error_log("Connection failed: " . $conn->connect_error);
            return false;
        }
    
        // Prepare query
        $query = sprintf("UPDATE peliculas SET titulo = '%s', director='%s', annio='%d', genero='%s', sinopsis='%s', portada='%s', reparto = '%s', Val_IMDb = '%d' WHERE id=%d"
            , $conn->real_escape_string($pelicula->titulo)
            , $conn->real_escape_string($pelicula->director)
            , $pelicula->annio
            , $conn->real_escape_string($pelicula->genero)
            , $conn->real_escape_string($pelicula->sinopsis)
            , $conn->real_escape_string($pelicula->portada) // Assuming $portada was meant to be $pelicula->portada
            , $conn->real_escape_string($pelicula->reparto)
            , $pelicula->Val_IMDb
            , $pelicula->id
        );
    
        // Execute query
        if ($conn->query($query) === TRUE) {
            return $pelicula;
        } else {
            // Log errors
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
        $query = sprintf("DELETE FROM peliculas P WHERE P.id = %d", $idPelicula);
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

    //Convierte el valor entero del Género a su valor en la tabla de Géneros
    private function convierteGenero($idGenero){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT genero FROM generos WHERE id = %d", $idGenero);
        $rs = $conn->query($query);
        $genero = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $genero = $fila['genero'];
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $genero;
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

    //Obtiene los géneros de las películas que se encuentran en la base de datos. Útil para el buscador
    public static function getGeneros(){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM generos");
        $rs = $conn->query($query);
        $generos = array();
        if ($rs) {
            while($fila = $rs->fetch_assoc()){
                $generos[] = $fila['genero'];
            }
            $rs->free();
        } else {
            $generos = false;
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $generos;
    }

}
