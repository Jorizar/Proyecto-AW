<?php
namespace es\ucm\fdi\aw\noticias;

use es\ucm\fdi\aw\Aplicacion;

class Noticia
{
    private $titulo;
    private $post_id;
    private $portada;
    private $texto;
    private $autor;
    private $fecha;
    private $rol;

    public function __construct($titulo, $post_id=null, $portada, $texto, $autor, $fecha, $rol)
    {
        $this->titulo = $titulo;
        $this->post_id = $post_id;
        $this->portada = $portada;
        $this->texto = $texto;
        $this->autor = $autor;
        $this->fecha = $fecha;
        $this->rol = $rol;
    }

    public function getTitulo() {
        return $this->titulo;
    }
    public function getID() {
        return $this->post_id;
    }
    public function getPortada() {
        return $this->portada;
    }
    public function getTexto() {
        return $this->texto;
    }
    public function getAutor() {
        return $this->autor;
    }
    public function getFecha() {
        return $this->fecha;
    }
    public function getRol() {
        return $this->rol;
    }

    public static function crea($titulo, $portada, $texto, $autor, $fecha, $rol)
    { //Falla el id (se crea 0)
        $noticia = new Noticia($titulo, null, $portada, $texto, $autor, $fecha, $rol);
        return $noticia->guarda();
    }

    private function guarda()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "INSERT INTO noticias (titulo, portada, texto, autor, fecha, rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sssssi', $this->titulo, $this->portada, $this->texto, $this->autor, $this->fecha, $this->rol);
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
            error_log("Error: " . $conn->error);
            return null;
        }
    }
    

    public static function buscarTodas() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        
        $sql = "SELECT * FROM noticias";
      
        $result = $conn->query($sql);
    
        $noticias = [];
        if ($result) { 
            while ($fila = $result->fetch_assoc()) {
                $noticias[] = new Noticia($fila['titulo'], $fila['post_id'], $fila['portada'], $fila['texto'], $fila['autor'], $fila['fecha'], $fila['rol']);
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
    
        return $noticias;
    }

    public static function eliminarPorId($id) {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "DELETE FROM noticias WHERE post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }    

    public static function buscaPorTitulo($titulo)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "SELECT * FROM noticias WHERE LOWER(titulo) LIKE LOWER('%$titulo%')";
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $noticias = new Noticia($fila['titulo'], $fila['post_id'], $fila['portada'], $fila['texto'], $fila['autor'], $fila['fecha'], $fila['rol']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }


    public static function borraPorId($id)
    {
        if (!$id) {
            return false;
        }
    
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = "DELETE FROM noticias WHERE post_id = ?";
    
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: ({$conn->errno}) {$conn->error}");
            return false;
        }
    
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) {
            error_log("Execution failed: ({$stmt->errno}) {$stmt->error}");
            return false;
        }
    
        return true;
    }

    public static function buscaPorId($idNoticia)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM noticias WHERE post_id=%d", $idNoticia);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Noticia($fila['titulo'], $fila['post_id'], $fila['portada'], $fila['texto'], $fila['autor'], $fila['fecha'], $fila['rol']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function actualizaPortada($id_noticia, $portada){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE noticias SET portada='%s' WHERE post_id=%d",
            $conn->real_escape_string($portada),
            $id_noticia
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarTítulo($id_noticia,$nuevoTitulo){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE noticias SET titulo='%s' WHERE post_id=%d",
            $conn->real_escape_string($nuevoTitulo),
            $id_noticia
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarAutor($id_noticia,$nuevoAutor){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE noticias SET autor='%s' WHERE post_id=%d",
            $conn->real_escape_string($nuevoAutor),
            $id_noticia
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarFecha($id_noticia,$nuevaFecha){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE noticias SET fecha='%s' WHERE post_id=%d",
            $conn->real_escape_string($nuevaFecha),
            $id_noticia
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarTexto($id_noticia,$nuevoTexto){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE noticias SET texto='%s' WHERE post_id=%d",
            $conn->real_escape_string($nuevoTexto),
            $id_noticia
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
