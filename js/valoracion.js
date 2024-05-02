function mostrarValoracion(valoracion) {
    var estrellasHtml = '';
    for (var i = 1; i <= 10; i++) {
        if (i <= valoracion) {
            estrellasHtml += '<span class="star filled">&#9733;</span>';
        } else {
            estrellasHtml += '<span class="star">&#9734;</span>';
        }
    }
    return estrellasHtml;
}


document.addEventListener("DOMContentLoaded", function() {
    var valoraciones = document.querySelectorAll('.comentario .valoracion');
    valoraciones.forEach(function(valoracionElement) {
        var valoracion = parseInt(valoracionElement.textContent);
        valoracionElement.innerHTML = mostrarValoracion(valoracion);
    });
});
