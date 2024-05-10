<?php

namespace es\ucm\fdi\aw\listas;
use es\ucm\fdi\aw\Aplicacion;

class Lista{
     //Funciones relacionadas con la creación/consulta y eliminación de listas
     public static function creaListaPeliculas($user_id, $nombre_lista){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO listas(user_id, nombre_lista) VALUES ('%d', '%s')"
            , $conn->real_escape_string($user_id)
            , $conn->real_escape_string($nombre_lista)
        );
        if ( $conn->query($query) ) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function eliminaListaPeliculas($lista_id){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("DELETE FROM listas WHERE lista_id = %d"
            , $conn->real_escape_string($lista_id)
        );
        if ( $conn->query($query) ) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function getListasUser($user_id){  //Obtenemos las listas de películas que tiene creada un usuario
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT lista_id, nombre_lista FROM listas WHERE user_id = %d ORDER BY lista_id ASC", $user_id);
        $rs = $conn->query($query);
        $listas_user = array();
        if ($rs) {
            while($fila = $rs->fetch_assoc()){
                $listas_user[] = array(
                    'lista_id' => $fila['lista_id'],
                    'nombre_lista' => $fila['nombre_lista']
                );
                
            }
            $rs->free();
        } else {
            $listas_user = false;
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $listas_user;
    }

    public static function getPeliculasLista($lista_id){    //Obtenemos las peliculas de la lista de un usuario
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT pelicula_id FROM peliculas_lista WHERE lista_id = %d", $lista_id);
        $rs = $conn->query($query);
        $peliculas = array();
        if ($rs) {
            while($fila = $rs->fetch_assoc()){
                $peliculas[] = $fila['pelicula_id'];
            }
            $rs->free();
        } else {
            $peliculas = false;
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $peliculas;
    }

    public static function getNumPeliculasLista($lista_id){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT COUNT(pelicula_id) AS numPel FROM peliculas_lista WHERE lista_id = %d", $lista_id);
        $rs = $conn->query($query);
        if ($rs) {
            $numPeliculas = $rs->fetch_assoc()['numPel'];
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $numPeliculas;
    }

    public static function buscaPeliculaLista($idPelicula, $idLista){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT pelicula_id FROM peliculas_lista WHERE lista_id= %d AND pelicula_id = %d", $idLista, $idPelicula);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = true;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function agregaPeliculaLista($idPelicula, $idLista){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO peliculas_lista(pelicula_id, lista_id) VALUES (%d, %d)"
            , $conn->real_escape_string($idPelicula)
            , $conn->real_escape_string($idLista)
        );
        if ( $conn->query($query) ) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
    
    public static function eliminaPeliculaLista($idPelicula, $idLista){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("DELETE FROM peliculas_lista WHERE pelicula_id = %d AND lista_id = %d"
            , $conn->real_escape_string($idPelicula)
            , $conn->real_escape_string($idLista)
        );
        if ( $conn->query($query) ) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }
}