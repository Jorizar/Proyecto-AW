<?php

namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\comentarios\Comentario;

class FormularioEditaComent extends Formulario
{
    protected $comentario_id = " ";

    public function __construct($comentario_id)
    {
        parent::__construct('formCambioDatos', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/misComentarios.php'), 'enctype' => 'multipart/form-data']);
        $this->comentario_id = $comentario_id;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $htmlComentId= '';

        if (!empty($this->comentario_id)) {
            $htmlComentId = "<input type='hidden' name='ID_comentario' value='{$this->comentario_id}'>";
        }

        $comentario = Comentario::buscarComentPorId($this->comentario_id);

        $user = Comentario::traduceUser($comentario->getUserId());
        $peli = Comentario::traducePeli($comentario->getPeliculaId());
        $textoComent = $comentario->getTexto();
        $antiguoValor = $comentario->getValoracion();

        // Genera las opciones del desplegable
        $options = '';
        for ($i = 1; $i <= 10; $i++) {
            $selected = ($i == $antiguoValor) ? "selected" : "";
            $options .= "<option value='$i' $selected>$i</option>";
        }

        $erroresCampos = self::generaErroresCampos(['nuevo_texto', 'nueva_Valor'], $this->errores, 'span', array('class' => 'error'));
        // Se generan los campos del formulario para cambiar los datos del usuario.
        $html = <<<EOF
        <div class="titulo_editarComentarios">
        </div>
        <div class="contenedor_editarComentarios">
            <div class="comentario-formulario">
                {$htmlComentId}
                <div class="User_EditComent contenedor-etiqueta-campo">
                    <label for="nombreuserr">Usuario: $user</label>
                </div>
                <div class="Peli_EditComent contenedor-etiqueta-campo">
                    <label for="nombrepelii">Pelicula: $peli</label>
                </div>
                <div class="texto_EditComent contenedor-etiqueta-campo">
                    <label for="nuevo_texto">Comentario:</label>
                    <textarea id="nuevo_texto" name="nuevo_texto" rows="10" required>$textoComent</textarea>
                    {$erroresCampos['nuevo_texto']}
                </div>
                <div class="valoracion_EditComent contenedor-etiqueta-campo">
                    <label for="newvalor">Valoracion:</label>
                    <select id="newvalor" name="newvalor">
                        $options
                    </select>
                    {$erroresCampos['nueva_Valor']}
                </div>
                <div class="contenedor-etiqueta-campo">
                    <button type="submit" name="cambiar_datos">Editar Comentario</button>
                </div>
            </div>
        </div>

        EOF;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {    
        $nuevoComent = trim($datos['nuevo_texto'] ?? '');
        if (!empty($nuevoComent)) {
            $nuevoComent = filter_var($nuevoComent, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                Comentario::cambiarTexto($this->comentario_id, $nuevoComent); 
        }

        $valoracionNueva = ($datos['newvalor']) ? $datos['newvalor'] : '';
            if (!empty($valoracionNueva)){
                Comentario::cambiarValoracion($this->comentario_id,$valoracionNueva); 
            }

    }

}
