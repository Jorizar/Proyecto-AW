<?php
namespace es\ucm\fdi\aw\listas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\listas\Lista;

class FormularioCreaLista extends Formulario
{
    public function __construct() {
        parent::__construct('formCreaLista', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/misListas.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        
         // Se generan los mensajes de error si existen.
         $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
         $erroresCampos = self::generaErroresCampos(['nombre_lista'], $this->errores, 'span', array('class' => 'error'));
 
         // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
         $html = <<<EOS
         $htmlErroresGlobales
         <div>
            <label for="nombre_lista">Nombre de la Lista:</label>
            <input id="nombre_lista" type="text" name="nombre_lista"/>
        </div>
        <div>
            <button type="submit" name="crear">Crear</button>
        </div>
        EOS;
         return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        $nombre_lista = $datos['nombre_lista'] ?? '';
        $nombre_lista = filter_var($nombre_lista, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mismoNombre = false;
        if(!empty($nombre_lista)){
            //Comprobamos que no exista una lista con ese nombre para ese usuario 
            $listas = Lista::getListasUser($_SESSION['idUsuario']);
            if(count($listas) > 0){
                foreach($listas as $lista){
                    $nombre = $lista['nombre_lista'];
                    if($nombre_lista == $nombre){
                        $this->errores['nombre_lista'] = 'Ya tienes una lista con el mismo nombre';
                        $mismoNombre = true;
                        break;
                    }
                }
                if($mismoNombre == false){
                    //Creamos la nueva lista vacía en la base de datos
                    $result = Lista::creaListaPeliculas($_SESSION['idUsuario'], $nombre_lista);
                    if(!$result){
                        $this->errores[] = 'Error al crear la nueva lista.';
                    }
                }
            }
            else{
                 //Creamos la nueva lista vacía en la base de datos
                 $result = Lista::creaListaPeliculas($_SESSION['idUsuario'], $nombre_lista);
                 if(!$result){
                     $this->errores[] = 'Error al crear la nueva lista.';
                 }
            } 
        }
        else{
            $this->errores['nombre_lista'] = 'Introduce un nombre válido para la lista';
        }
    }
}