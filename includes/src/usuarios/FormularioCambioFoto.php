<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioCambioFoto extends Formulario
{
    public function __construct() {
        parent::__construct('formCambioFoto', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se generan las opciones de selección de fotos
        $opcionesFotos = [
            './imagenes/fotosPerfil/1.png',
            './imagenes/fotosPerfil/2.png',
            './imagenes/fotosPerfil/brad.png',
            './imagenes/fotosPerfil/quentin.png'
        ];

        // Se genera el HTML asociado a las opciones de selección de fotos
        $htmlOpciones = '';
        foreach ($opcionesFotos as $foto) {
            $htmlOpciones .= "<label><img src='$foto' width='48' height='48'><br>";
            $htmlOpciones .= "<input type='radio' name='foto' value='$foto'></label>";
        }

        // Se genera el HTML completo del formulario
        $html = <<<EOF
        <fieldset>
            <legend>Seleccionar nueva foto de perfil</legend>
            <div>
                $htmlOpciones
            </div>
            <div>
                <button type="submit" name="cambiarFoto">Seleccionar</button>
            </div>
        </fieldset>
        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        // No es necesario realizar ninguna acción en el lado del servidor
        // ya que el formulario se envía directamente a través de HTML y JavaScript
    }
}
