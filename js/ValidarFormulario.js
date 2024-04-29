// Validar Formulario de Comentarios

function validarFormularioComentarios() {
    let texto = document.getElementById('comentario-texto').value;
    let valoracion = document.getElementById('comentario-valoracion').value;
    if (texto.trim() === '' || valoracion.trim() === '') {
        alert('Por favor, completa todos los campos antes de enviar.');
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('comentarioForm');
    form.addEventListener('submit', function(e) {
        if (!validarFormularioComentarios()) {
            e.preventDefault();  // Evitar el envío del formulario si la validación falla
            return;
        }

        const data = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            body: data
        })
        .then(response => {
            if (!response.ok) throw new Error('Error en la respuesta del servidor');
            return response.json();
        })
        .then(data => {
            if (data.error) {
                console.error('Error:', data.error);
            } else {
                alert('Comentario enviado con éxito');
                location.reload(); // Recargar la página para mostrar el nuevo comentario
            }
        })
        .catch(error => {
            console.error('Error al enviar el formulario:', error);
        });
    });
});
