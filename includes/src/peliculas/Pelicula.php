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
        $pelicula = new Pelicula($titulo, $director, null, $annio, $genero, $sinopsis, $portada, $reparto, $val_imdb);
        return $pelicula->guarda();
    }
    

    //Se utiliza en el buscador para obtener los ids de las películas que coinciden con los criterios de búsqueda
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
        if (!empty($annio)) {
            if (strlen($annio) === 4) {
                $sql .= " AND annio = $annio";
            } elseif (strlen($annio) === 3) {
                $decada_inicio = intval($annio) * 10;
                $decada_fin = $decada_inicio + 9;
                $sql .= " AND annio >= $decada_inicio AND annio <= $decada_fin";
            } elseif (strlen($annio) === 2) {
                $annio_inicio = intval($annio) * 100;
                $annio_fin = $annio_inicio + 99;
                $sql .= " AND annio >= $annio_inicio AND annio <= $annio_fin";
            } else {
                $annio_inicio = intval($annio) * 1000;
                $annio_fin = $annio_inicio + 999;
                $sql .= " AND annio >= $annio_inicio AND annio <= $annio_fin";
            }
        }

        $result = $conn->query($sql);
        $peliculas = false;

        if ($result) {
            $peliculas = array();
            while($fila = $result->fetch_assoc()) {
                $peliculas[] = $fila['id'];
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $peliculas;
    }



    public static function buscaPorTitulo($tituloPelicula)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM peliculas WHERE LOWER(titulo) LIKE LOWER('%$tituloPelicula%')";
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

    public static function buscaTituloPorId($idPelicula)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT titulo FROM peliculas WHERE id = %d", $idPelicula);
        $rs = $conn->query($query);
        $titulo = null; 
    
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $titulo = $fila['titulo']; 
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $titulo; 
    }
    
    public static function buscarTodas() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT id, titulo FROM peliculas";
        $result = $conn->query($sql);

        $peliculas = [];
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $peliculas[] = [
                    'id' => $fila['id'],
                    'titulo' => $fila['titulo']
                ];
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $peliculas;
    }

    public static function peliculasMejorVal($n){   //Devuelve las n películas mejor valoradas
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT id, titulo, portada, Val_IMDb FROM peliculas ORDER BY Val_IMDb DESC LIMIT $n";
        $result = $conn->query($sql);
        $peliculas = [];
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $peliculas[] = [
                    'id' => $fila['id'],
                    'titulo' => $fila['titulo'],
                    'portada' => $fila['portada'],
                    'val_IMDb' => $fila['Val_IMDb']
                ];
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $peliculas;
    }

    public static function peliculasPorGenero($idGenero, $n){ //Devuelve las mejores n peliculas de un género
        $conn = Aplicacion::getInstance()->getConexionBd();
        if ($n >0) {
            $sql = "SELECT id, titulo, portada, Val_IMDb FROM peliculas WHERE genero = $idGenero ORDER BY Val_IMDb DESC LIMIT $n";
        } else {
            $sql = "SELECT id, titulo, portada, Val_IMDb FROM peliculas WHERE genero = $idGenero ORDER BY Val_IMDb DESC";
        }
        $result = $conn->query($sql);
        $peliculas = [];
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $peliculas[] = [
                    'id' => $fila['id'],
                    'titulo' => $fila['titulo'],
                    'portada' => $fila['portada'],
                    'val_IMDb' => $fila['Val_IMDb']
                ];
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $peliculas;
    }

    public static function peliculasPorAnnio($annio_inf, $annio_sup, $n) { //Devuelve las mejores n peliculas de un periodo de años
        $conn = Aplicacion::getInstance()->getConexionBd();
        if ($n>0) {
            $sql = "SELECT id, titulo, portada, Val_IMDb FROM peliculas WHERE annio >= $annio_inf AND annio < $annio_sup ORDER BY annio ASC LIMIT $n";
        } else {
            $sql = "SELECT id, titulo, portada, Val_IMDb FROM peliculas WHERE annio >= $annio_inf AND annio < $annio_sup ORDER BY annio ASC";
        }
            $result = $conn->query($sql);
        $peliculas = [];
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $peliculas[] = [
                    'id' => $fila['id'],
                    'titulo' => $fila['titulo'],
                    'portada' => $fila['portada'],
                    'val_IMDb' => $fila['Val_IMDb']
                ];
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $peliculas;
    }

    //Inserta una pelicula nueva en la base de datos
    public static function inserta($pelicula)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        $sql = "INSERT INTO peliculas (titulo, director, annio, genero, sinopsis, portada, reparto, Val_IMDb) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    
        if ($stmt) {
            $stmt->bind_param("ssissssd", 
                $pelicula['titulo'],
                $pelicula['director'],
                $pelicula['anio'],
                $pelicula['genero'],
                $pelicula['sinopsis'],
                $pelicula['imagen'],
                $pelicula['reparto'],
                $pelicula['imdb']
            );
    
            if ($stmt->execute()) {
                $pelicula['id'] = $conn->insert_id;
                $stmt->close();
                return $pelicula;
            } else {
                error_log("Error BD ({$stmt->errno}): {$stmt->error}");
                $stmt->close();
                return false;
            }
        } else {
            error_log("Error preparing statement: " . $conn->error);
            return false;
        }
    }
    
    
    //Actualiza la información de la película en la base de datos
    private static function actualiza($pelicula)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
    
        if ($conn->connect_error) {
            error_log("Connection failed: " . $conn->connect_error);
            return false;
        }
    
        $query = sprintf("UPDATE peliculas SET titulo = '%s', director='%s', annio='%d', genero='%s', sinopsis='%s', portada='%s', reparto = '%s', Val_IMDb = '%d' WHERE id=%d"
            , $conn->real_escape_string($pelicula->titulo)
            , $conn->real_escape_string($pelicula->director)
            , $pelicula->annio
            , $conn->real_escape_string($pelicula->genero)
            , $conn->real_escape_string($pelicula->sinopsis)
            , $conn->real_escape_string($pelicula->portada) 
            , $conn->real_escape_string($pelicula->reparto)
            , $pelicula->Val_IMDb
            , $pelicula->id
        );

        if ($conn->query($query) === TRUE) {
            return $pelicula;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
    
    private static function borra($pelicula)
    {
        return self::borraPorId($pelicula->id);
    }
    
    public static function borraPorId($idPelicula)
    {
        if (!$idPelicula) {
            return false;
        }
    
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM peliculas WHERE id = ?";
    
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: ({$conn->errno}) {$conn->error}");
            return false;
        }
    
        $stmt->bind_param('i', $idPelicula);
        if (!$stmt->execute()) {
            error_log("Execution failed: ({$stmt->errno}) {$stmt->error}");
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
    public static function convierteGenero($idGenero){
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
                $generos[$fila['id']] = $fila['genero'];
            }
            $rs->free();
        } else {
            $generos = false;
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $generos;
    }


    public static function cambiarTítulo($pelicula_id,$nuevoTitulo){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE peliculas SET titulo='%s' WHERE id=%d",
            $conn->real_escape_string($nuevoTitulo),
            $pelicula_id
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarDirector($pelicula_id,$nuevoDirector){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE peliculas SET director='%s' WHERE id=%d",
            $conn->real_escape_string($nuevoDirector),
            $pelicula_id
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarAnnio($pelicula_id,$nuevoAnnio){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE peliculas SET annio='%s' WHERE id=%d",
            $conn->real_escape_string($nuevoAnnio),
            $pelicula_id
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarSinopsis($pelicula_id,$nuevaSinopsis){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE peliculas SET sinopsis='%s' WHERE id=%d",
            $conn->real_escape_string($nuevaSinopsis),
            $pelicula_id
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarImdb($pelicula_id,$nuevoImdb){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE peliculas SET Val_IMDb='%s' WHERE id=%d",
            $conn->real_escape_string($nuevoImdb),
            $pelicula_id
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function actualizaPortada($pelicula_id, $nuevaPortada){

        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE peliculas SET portada='%s' WHERE id=%d",
            $conn->real_escape_string($nuevaPortada),
            $pelicula_id
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function actualizaReparto($pelicula_id, $jsonReparto) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE peliculas SET reparto='%s' WHERE id=%d",
            $conn->real_escape_string($jsonReparto),
            $pelicula_id
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarGenero($pelicula_id,$generoPelicula){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE peliculas SET genero='%s' WHERE id=%d",
            $conn->real_escape_string($generoPelicula),
            $pelicula_id
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }


}
