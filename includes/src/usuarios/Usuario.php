<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Usuario
{
    use MagicProperties;

    public static function login($nombreUsuario, $password)
    {
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return $usuario;
        }
        return false;
    }
    
    public static function crea($nombreUsuario, $password, $id, $rol, $email, $foto)
    {
        $foto = './img/fotosPerfil/1.png';
        $hashedPassword = self::hashPassword($password);
        $user = new Usuario($nombreUsuario, $hashedPassword, $id, $rol, $email, $foto);
        return $user->guarda();
    }


    public static function buscaUsuario($nombreUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios U WHERE U.username='%s'", $conn->real_escape_string($nombreUsuario));
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['username'], $fila['password'], $fila['user_id'], $fila['rol'], $fila['email'], $fila['foto']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }


    public static function buscaPorId($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM usuarios WHERE user_id=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Usuario($fila['username'], $fila['password'], $fila['user_id'], $fila['rol'], $fila['email'], $fila['foto']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaNombrePorId($UserId)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT username FROM usuarios WHERE user_id = %d", $UserId);
        $rs = $conn->query($query);
        $UserNombre = null;
    
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $UserNombre = $fila['username']; 
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $UserNombre; 
    }
    
    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
   
    //Inserta un usuario nuevo en la base de datos
    private static function inserta($usuario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO usuarios(username, password, rol, email, foto) VALUES ('%s', '%s', '%s', '%s', '%s')"
            , $conn->real_escape_string($usuario->nombreUsuario)
            , $conn->real_escape_string($usuario->password)
            , $conn->real_escape_string($usuario->rol)
            , $conn->real_escape_string($usuario->email)
            , $conn->real_escape_string($usuario->foto)
        );
        if ( $conn->query($query) ) {
            $usuario->id = $conn->insert_id;
            return $usuario;
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
        $query=sprintf("UPDATE usuarios U SET username = '%s', password='%s', rol='%s', email='%s', foto='%s' WHERE U.user_id=%d"
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
     
    private static function borra($usuario)
    {
        return self::borraPorId($usuario->id);
    }
    
    public static function borraPorId($idUsuario)
    {
        if (!$idUsuario) {
            return false;
        }
    
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM usuarios WHERE user_id = %d", $idUsuario);
    
        if (!$conn->query($query)) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }
    

    private $id; //Id que identifica al user en la base de datos

    private $nombreUsuario; //Nombre de usuario

    private $password;  //Contraseña del usuario

    private $rol;   //Rol que posee el usuario

    private $email;  //Dirección email que posee

    private $foto;   //Ruta para cargar la imagen de perfil

    private function __construct($nombreUsuario, $password, $id, $rol, $email, $foto)
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->email = $email;
        $this->rol = $rol;
        $this->foto = $foto;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getRol(){
        return $this->rol;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getFoto(){
        return $this->foto;
    }

    public function compruebaPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function cambiaPassword($nuevoPassword)
    {
        $this->password = self::hashPassword($nuevoPassword);
    }

    //Si la instancia no posee id no se ha insertado todavía en la base de datos y se inserta. Si no, llama a actualiza para cambiar 
    //la información del usuario
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

    public function setRol($nuevoRol)
    {
        $this->rol = $nuevoRol;
        return self::actualizaRol($this->id, $nuevoRol);
    }


    public function cambiaFoto($nuevaFoto)
    {
        $this->foto = $nuevaFoto;
        return self::actualizaFoto($this->id, $nuevaFoto);
    }

    // Actualiza la ruta de la foto en la base de datos
    public static function actualizaFoto($idUsuario, $nuevaFoto)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE usuarios SET foto='%s' WHERE user_id=%d",
            $conn->real_escape_string($nuevaFoto),
            $idUsuario
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function actualizaRol($idUsuario, $nuevoRol)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE usuarios SET rol='%s' WHERE user_id=%d",
            $conn->real_escape_string($nuevoRol),
            $idUsuario
        );
        if ($conn->query($query)) {
            return true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
    }

    public static function cambiarNombre($idUsuario, $nuevoNombre)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE usuarios SET username='%s' WHERE user_id=%d",
            $conn->real_escape_string($nuevoNombre),
            $idUsuario
        );
        if ($conn->query($query)) {
            return true;
        } 
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }

    }

    public static function buscarTodos() {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $sql = "SELECT user_id, username, email FROM usuarios";
        $result = $conn->query($sql);

        $usuarios = [];
        if ($result) {
            while ($fila = $result->fetch_assoc()) {
                $usuarios[] = [
                    'user_id' => $fila['user_id'],
                    'username' => $fila['username'],
                    'email' => $fila['email']
                ];
            }
            $result->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }

        return $usuarios;
    }

    public static function cambiarEmail($idUsuario, $nuevoEmail)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("UPDATE usuarios SET email='%s' WHERE user_id=%d",
            $conn->real_escape_string($nuevoEmail),
            $idUsuario
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


    


