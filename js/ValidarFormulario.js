// Validar Formulario de Comentarios

$(document).ready(function() {
    $('#comentarioForm').submit(function(event) {
        event.preventDefault(); // Evita el envío normal del formulario
        var formData = $(this).serialize(); // Codifica los datos del formulario para su envío
        var texto = $('#comentario-texto').val().trim();
        var valoracion = $('#comentario-valoracion').val();

        if (texto.length === 0 || texto.length > 500) { // Suponiendo un máximo de 500 caracteres
            alert("Por favor, asegúrate de que tu comentario no esté vacío y no exceda los 500 caracteres.");
            //event.preventDefault();
            return;
        }

        if (valoracion < 1 || valoracion > 10) {
            alert("La valoración debe estar entre 1 y 10.");
            //event.preventDefault();
            return;
        }
        $.ajax({
            type: "POST",
            url: "./includes/src/comentarios/procesar_comentario.php",
            data: formData,
            success: function(data) {
                location.reload();
                //alert("Datos enviados correctamente");
            },
            error: function() {
                alert("Error en el envío de datos");
            }
        });
    });
    
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
            url: 'includes/src/likes/procesar_like.php', // Asegúrate de que la URL es correcta
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
    const form = document.getElementById('formRegistro');
    form.addEventListener('submit', function(event) {
        let hasErrors = false;
        const nombreUsuario = document.getElementById('nombreUsuario').value;
        const password = document.getElementById('password').value;
        const password2 = document.getElementById('password2').value;
        const email = document.getElementById('email').value;
        const rol = document.getElementById('rol').value;
        console.log("HOLO");
        // Validación del nombre de usuario
        if (!/^[a-zA-Z0-9_-]+$/.test(nombreUsuario)) {
            alert('Nombre de usuario inválido.');
            hasErrors = true;
        }

        // Validación de la contraseña
        if (password.length < 8) {
            alert('La contraseña debe tener al menos 8 caracteres.');
            hasErrors = true;
        } else if (password !== password2) {
            alert('Las contraseñas no coinciden.');
            hasErrors = true;
        }

        // Validación de email
        if (!/\S+@\S+\.\S+/.test(email)) {
            alert('Email no válido.');
            hasErrors = true;
        }

        // Validación del rol
        if (rol !== 'free' && rol !== 'premium') {
            alert('Seleccione un rol válido.');
            hasErrors = true;
        }

        if (hasErrors) {
            event.preventDefault();
        }
    });
});
