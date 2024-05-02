<?php
namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioAgregaPel extends Formulario
{
    public function __construct() {
        parent::__construct('formAgregaPel', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/admin_peliculas.php'), 'enctype' => 'multipart/form-data']);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $generos = Pelicula::getGeneros();
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['tituloPelicula', 'directorPelicula', 'annioPelicula', 'generoPelicula', 'sinopsisPelicula', 'portada', 'imdb'], $this->errores, 'span', array('class' => 'error'));
    
        $html = <<<EOS
        $htmlErroresGlobales
        <div class="agrega-pelicula">
            <div class="campos-container">
                <div class="add-campo-titulo">
                    <label for="tituloPelicula">Título:</label>
                    <input id="tituloPelicula" type="text" name="tituloPelicula" required/>
                    {$erroresCampos['tituloPelicula']}
                </div>
                <div class="add-campo-director">
                    <label for="directorPelicula">Director:</label>
                    <input id="directorPelicula" type="text" name="directorPelicula" required/>
                    {$erroresCampos['directorPelicula']}
                </div>
                <div class="add-campo-anio">
                    <label for="annioPelicula">Año de estreno:</label>
                    <input id="annioPelicula" type="text" name="annioPelicula" placeholder="XXXX" required/>
                    {$erroresCampos['annioPelicula']}
                </div>
                <div class="add-campo-portada">
                    <label for="portada">Portada de la Película:</label>
                    <input id="portada" type="file" name="portada" accept="image/*" required>
                </div>
                <div class="add-campo-sinopsis">
                    <label for="sinopsisPelicula">Sinopsis:</label>
                    <textarea id="sinopsisPelicula" name="sinopsisPelicula" placeholder="Añada aquí la sinopsis" rows="5" required></textarea>
                    {$erroresCampos['sinopsisPelicula']}
                </div>
                <div class="add-campo-imdb">
                    <label for="imdb">Puntuación en IMDb:</label>
                    <input id="imdb" type="text" name="imdb" placeholder="XX.X" required/>
                    {$erroresCampos['imdb']}
                </div>
                <div class="add-campo-genero">
                    <label for="generoPelicula">Género:</label>
                    <select id="generoPelicula" name="generoPelicula">
                        <option value="-1">Seleccionar</option>
    EOS;
        if ($generos != false) {
            foreach ($generos as $id => $genero) {
                $html .= "<option value='$id'>$genero</option>";
            }
        }
        $html .= "</select></div>";
        $html .= <<<EOS
                <div class="add-campo-reparto">
                    <label>Actores/Personajes:</label>
                    <div id="reparto-container">
                        <div class="reparto-item">
                            <input type="text" name="actor[]" placeholder="Nombre del actor" required>
                            <input type="text" name="personaje[]" placeholder="Personaje" required>
                            <button type="button" class="eliminar-campo">Eliminar</button>
                        </div>
                    </div>
                    <button type="button" id="agregar-campo">Agregar Actor/Personaje</button>
                </div>
                <div class="anyadir-boton">
                    <button type="submit" name="add">Añadir</button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const agregarCampoBtn = document.getElementById('agregar-campo');
                const repartoContainer = document.getElementById('reparto-container');
    
                agregarCampoBtn.addEventListener('click', function() {
                    const nuevoCampo = document.createElement('div');
                    nuevoCampo.classList.add('reparto-item');
                    nuevoCampo.innerHTML = `
                        <input type="text" name="actor[]" placeholder="Nombre del actor" required>
                        <input type="text" name="personaje[]" placeholder="Personaje" required>
                        <button type="button" class="eliminar-campo">Eliminar</button>
                    `;
                    repartoContainer.appendChild(nuevoCampo);
                });
    
                repartoContainer.addEventListener('click', function(event) {
                    if (event.target.classList.contains('eliminar-campo')) {
                        event.target.parentElement.remove();
                    }
                });
            });
        </script>
    EOS;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
    {
        // Extract and sanitize input data
        $tituloPelicula = $datos['tituloPelicula'] ?? null;
        $directorPelicula = $datos['directorPelicula'] ?? null;
        $generoPelicula = $datos['generoPelicula'] ?? null;
        $annioPelicula = $datos['annioPelicula'] ?? null;
        $sinopsisPelicula = $datos['sinopsisPelicula'] ?? null;
        $repartoActores = $datos['actor'] ?? [];
        $repartoPersonajes = $datos['personaje'] ?? [];
        $imdbPelicula = $datos['imdb'] ?? null;
        $portadaPelicula = $_FILES['portada'] ?? null;
    
        // Validate required fields
        if (!$tituloPelicula || !$directorPelicula || !$generoPelicula || !$annioPelicula || !$sinopsisPelicula || !$imdbPelicula || $generoPelicula == '-1' || empty($portadaPelicula)) {
            $this->errores[] = "Todos los campos son obligatorios. Por favor, completa el formulario completamente.";
            return $this->generaCamposFormulario($datos);
        }
    
        // Validate actors and characters
        if (empty($repartoActores) || empty($repartoPersonajes) || count($repartoActores) != count($repartoPersonajes)) {
            $this->errores[] = "Debes proporcionar al menos un actor y su personaje y asegurarte que cada actor tiene un personaje asignado.";
            return $this->generaCamposFormulario($datos);
        }
    
        // Format actors and characters into JSON
        $reparto = [];
        foreach ($repartoActores as $index => $actor) {
            if (!empty($actor) && !empty($repartoPersonajes[$index])) {
                $reparto[] = [
                    'nombre' => $actor,
                    'personaje' => $repartoPersonajes[$index]
                ];
            }
        }
        $jsonReparto = json_encode($reparto);
    
        // Validate and upload the movie cover image
        if ($portadaPelicula['error'] == UPLOAD_ERR_OK) {
            $tempPath = $portadaPelicula['tmp_name'];
            $uploadPath = 'img/portadas/' . basename($portadaPelicula['name']);
    
            // Check file size and type
            if ($portadaPelicula['size'] > 1000000) {
                $this->errores[] = "El archivo de la portada debe ser menor a 1MB.";
                return $this->generaCamposFormulario($datos);
            }
    
            $allowedTypes = ['jpg', 'jpeg', 'png'];
            $fileType = strtolower(pathinfo($uploadPath, PATHINFO_EXTENSION));
            if (!in_array($fileType, $allowedTypes)) {
                $this->errores[] = "Solo se permiten archivos JPG, JPEG y PNG para la portada.";
                return $this->generaCamposFormulario($datos);
            }
    
            if (!move_uploaded_file($tempPath, $uploadPath)) {
                $this->errores[] = "Hubo un error al subir el archivo de la portada.";
                return $this->generaCamposFormulario($datos);
            }
        } else {
            $this->errores[] = "Error al cargar la portada. Error code: " . $portadaPelicula['error'];
            return $this->generaCamposFormulario($datos);
        }
    
        // Proceed to store the new movie in the database
        if (empty($this->errores)) {
            $success = Pelicula::inserta([
                'titulo' => $tituloPelicula,
                'director' => $directorPelicula,
                'genero' => $generoPelicula,
                'anio' => $annioPelicula,
                'sinopsis' => $sinopsisPelicula,
                'imagen' => $uploadPath,
                'reparto' => $jsonReparto,
                'imdb' => $imdbPelicula
            ]);
    
            if (!$success) {
                $this->errores[] = "No se pudo registrar la película en la base de datos.";
                return $this->generaCamposFormulario($datos);
            } else {
                // Redirect on success
                header('Location: ' . $this->urlRedireccion);
                exit;
            }
        }
    }
    
}
