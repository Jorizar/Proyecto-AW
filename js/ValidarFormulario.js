// Validar Formularios

$(document).ready(function() {
    
    
    //--Formulario de Dar me gusta a los comentarios, DINAMICO

    $('.comentarioLike').submit(function(event) {
        event.preventDefault(); // Evita el envío normal del formulario
        var comentarioId = $(this).find('input[name="comentario_id"]').val();
        var action = $(this).find('input[name="action"]').val();
        
        if (!comentarioId || (action !== 'like' && action !== 'undo')) {
            alert('Datos del formulario inválidos.');
            return; // Detener la ejecución si la validación falla
        }

        var form = $(this);
        var formData = form.serialize(); // Recolecta los datos del formulario

        $.ajax({
            type: 'POST',
            url: './includes/src/likes/procesar_like.php', // Asegúrate de que la URL es correcta
            data: formData,
            dataType: 'json', // Esperando una respuesta JSON
            success: function(response) {
                if (response.success) {
                    // Actualiza el contador de "me gusta" y cambia el estado del corazón
                    var likesSpan = form.next('.likes-count');
                    var currentLikes = parseInt(likesSpan.text(), 10);
                    var newLikes = response.actionPerformed === 'like' ? currentLikes + 1 : currentLikes - 1;
                    likesSpan.text(newLikes); // Actualiza el texto del contador desde el usuario

                    // Cambia el botón
                    var heartButton = form.find('.heart');
                    if (response.actionPerformed === 'like') {
                        heartButton.html('♥').addClass('liked');
                        form.data('action', 'undo');
                        form.find('input[name="action"]').val('undo');
                    } else {
                        heartButton.html('♡').removeClass('liked');
                        form.data('action', 'like');
                        form.find('input[name="action"]').val('like');
                    }
                } else {
                    alert('Error al procesar la acción. Intente de nuevo.');
                }
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
                console.log("Status: " + status);
                console.dir(xhr);
                alert('Error al conectar al servidor. Por favor, intente más tarde.');
            }
        });
    });   
});

document.addEventListener("DOMContentLoaded", function() {

    const loginForm = document.getElementById('formLogin'); // Asegúrate que este es el ID correcto del form
    const registroForm = document.getElementById('formRegistro');
    const cambioDatosForm = document.getElementById('formCambioDatos'); // Asegúrate de que este es el ID correcto de tu formulario
    const comentarioForm = document.getElementById('comentarioForm');

    
    if (comentarioForm) {
        comentarioForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío normal del formulario
            var formData = $(this).serialize(); // Codifica los datos del formulario para su envío
            const texto = document.getElementById('comentario-texto').value.trim();
            const valoracion = document.getElementById('comentario-valoracion').value;

            if (texto.length === 0 || texto.length > 500) { // Suponiendo un máximo de 500 caracteres
                alert("Por favor, asegúrate de que tu comentario no esté vacío y no exceda los 500 caracteres.");

                return;
            }

            if (valoracion < 1 || valoracion > 10) {
                alert("La valoración debe estar entre 1 y 10.");

                return;
            }
            $.ajax({
                type: "POST",
                url: "./includes/src/comentarios/procesar_comentario.php",
                data: formData,
                success: function(data) {
                    location.reload();

                },
                error: function() {
                    alert("Error en el envío de datos");
                }
            });
        });
    }


//--------------------------LOGIN------------------------------------


    if (loginForm){
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene el envío del formulario antes de la validación
            let valid = true;

            const nombreUsuario = document.getElementById('nombreUsuario').value.trim();
            const password = document.getElementById('password').value.trim();

            // limpiamos los errores.
            clearError('nombreUsuario');
            clearError('password');
            

            // Validación del nombre de usuario
            if (!nombreUsuario) {
                displayError('nombreUsuario', 'El nombre de usuario no puede estar vacío.');
                valid = false;
            } else if (!validarNombre(nombreUsuario)) {
                displayError('nombreUsuario', 'El nombre solo puede contener letras, números y guiones.');
                valid = false;
            }

            // Validación de la contraseña
            if (!password) {
                displayError('password', 'La contraseña no puede estar vacía.');
                valid = false;
            }

            // Envía el formulario si todo es válido
            if (valid) {
                loginForm.submit();
            }
        });
    }


    //-------------------------------REGISTRO---------------------------


    if (registroForm){
        registroForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío del formulario hasta verificar la validez de todos los campos
            let valid = true;

            // Obtener valores de los campos
            const nombreUsuario = document.getElementById('nombreUsuario').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const password2 = document.getElementById('password2').value;
            const rol = document.getElementById('rol').value;

            //limpiamos los errores
            clearError('nombreUsuario');
            clearError('email');
            clearError('password');
            clearError('password2');
            clearError('rol');


            // Validaciones
            if (nombreUsuario === '') {
                displayError('nombreUsuario', 'El nombre de usuario es requerido.');
                valid = false;
            } else if (!validarNombre(nombreUsuario)) {
                displayError('nombreUsuario', 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.');
                valid = false;
            } 

            if (!email) {
                displayError('email', 'El correo electrónico es requerido.');
                valid = false;
            } else if (!validarEmail(email)) {
                displayError('email', 'Por favor, ingresa un correo electrónico válido.');
                valid = false;
            } 

            if (!password) {
                displayError('password', 'La contraseña es requerida.');
                valid = false;
            } else if (password.length < 8) {
                displayError('password', 'La contraseña debe tener al menos 8 caracteres.');
                valid = false;
            } 

            if (!password2 || password !== password2) {
                displayError('password2', 'Las contraseñas no coinciden.');
                valid = false;
            } 

            if (rol !== 'free' && rol !== 'premium') {
                displayError('rol', 'Por favor, selecciona un rol válido.');
                valid = false;
            } 

            if (valid) {
                registroForm.submit(); // Enviar el formulario si todo es válido
            }
        });
    }


//--------------------------------CAMBIO DATOS -------------------------------


    if(cambioDatosForm) {
        cambioDatosForm.addEventListener('submit', function(event) {
            // Inicialmente asumimos que no hay errores
            let valid = true;

            // Obtener los valores de los inputs
            const nuevoNombre = document.getElementById('nuevo_nombre').value.trim();
            const nuevoEmail = document.getElementById('nuevo_email').value.trim();
 
            // Limpiar errores previos
            clearError('nuevo_nombre'); 
            clearError('nuevo_email');

            // Validación del nuevo nombre
            if (!nuevoNombre) {
                displayError('nuevo_nombre', 'El campo nombre no puede estar vacío');
                valid = false; // Marcar que hay un error
            } else if (!validarNombre(nuevoNombre)) {
                displayError('nuevo_nombre', 'El campo nombre solo puede contener letras, números y guiones');
                valid = false;
            }

            // Validación del nuevo email
            if (!nuevoEmail) {
                displayError('nuevo_email', 'El campo email no puede estar vacío');
                valid = false; // Marcar que hay un error
            } else if (!validarEmail(nuevoEmail)) {
                displayError('nuevo_email', 'El email introducido no es válido');
                valid = false;
            }

            // Si no es válido, prevenir el envío del formulario
            if (!valid) {
                event.preventDefault();
            }
        });
    }

    // Función para validar el formato del email
    function validarEmail(email) {
        const expresionRegular = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return expresionRegular.test(email);
    }

    // Funcion para validar los nombres
    function validarNombre(nombre) {
        const expresionRegular = /^[a-zA-Z0-9_-]+$/;
        return expresionRegular.test(nombre);
    }

    // Funcion para mostrar los errores
    function displayError(fieldId, message) {
        const errorDiv = document.getElementById(fieldId + 'Error');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    }

    // Funcion para limpiar los errores
    function clearError(fieldId) {
        const errorDiv = document.getElementById(fieldId + 'Error');
        if (errorDiv) {
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
        }
    }

});
