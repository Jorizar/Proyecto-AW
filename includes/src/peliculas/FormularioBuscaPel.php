<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioBuscaPel extends Formulario
{
    //TO DO
    public function __construct() {
        parent::__construct('formBuscaPel', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    //TO DO
    protected function generaCamposFormulario(&$datos)
    {
        
    }
    
    //TO DO
    protected function procesaFormulario(&$datos)
    {

    }
}