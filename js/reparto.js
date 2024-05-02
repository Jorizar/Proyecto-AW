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