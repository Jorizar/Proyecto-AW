<?php
namespace es\ucm\fdi\aw\listas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\peliculas\Pelicula;

class FormAgregaPelLista extends Formulario
{
    public function __construct() {
        parent::__construct('formAgregaPel');
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        
        
         // Se generan los mensajes de error si existen.
         $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
         $erroresCampos = self::generaErroresCampos(['lista_seleccionada'], $this->errores, 'span', array('class' => 'error'));
 
        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        //Hacemos una consulta para obtener las listas del usuario
        $listas_user = Lista::getListasUser($_SESSION['idUsuario']);
        
        if (count($listas_user) == 0) {

            return "Crea una lista para poder añadirla";
        }

         $html = <<<EOS
         $htmlErroresGlobales
         <div class>
         <label for=lista_seleccionada">Añade la película a la lista:</label>
                <select id="lista_seleccionada" name="lista_seleccionada">
        EOS;
        foreach($listas_user as $lista){
            $lista_id = $lista['lista_id'];
            $nombre_lista = $lista['nombre_lista'];
            $html .= "<option value=$lista_id>$nombre_lista</option>";
        }
        $html .=<<<EOS
            </select></div>
        <div>
            <button type="submit" name="Añadir">Añadir</button>
        </div>
        EOS;
        return $html;
    }
    
    protected function procesaFormulario(&$datos)
    {
        $idLista = $datos['lista_seleccionada'] ?? '';
        if(!empty($idLista)){
            //Obtenemos el id de la película
            $idPelicula =  $_GET['id'];
            //Comprobamos que no la película no esté ya en la lista 
            $pelicula = Lista::buscaPeliculaLista($idPelicula, $idLista);
            if($pelicula){
                $this->errores['lista_seleccionada'] = 'Ya existe esta película en la lista que has seleccionado';
            }
            else{
                //Agregamos la película a la lista
                $result = Lista::agregaPeliculaLista($idPelicula, $idLista);
                if($result == false){
                    $this->errores[] = 'Error al agregar la pelicula a la lista';
                }
            }
        }
    }
}