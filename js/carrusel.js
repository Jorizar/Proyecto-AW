document.addEventListener("DOMContentLoaded", function() {
    const carruseles = document.querySelectorAll('.carrusel');

    carruseles.forEach(function(carrusel) {
        const peliculasContainer = carrusel.querySelector('.peliculas-container');
        const prevBtn = carrusel.querySelector('.prev');
        const nextBtn = carrusel.querySelector('.next');

        let scrollAmount = 0;
        let peliculaWidth = 1140; // Ancho de cada película incluyendo margen
        //let peliculaWidth = (window.innerWidth * 0.25) - 10;
       

        // Función para calcular el ancho total de las películas
        function calcularAnchoTotal() {
            let numPeliculas = peliculasContainer.querySelectorAll('.pelicula').length;
            return numPeliculas * (peliculaWidth);
        }

        // Función para desplazarse a la izquierda
        prevBtn.addEventListener('click', function() {
            if (scrollAmount > 0) {
                scrollAmount -= (peliculaWidth);
                if (scrollAmount < 0) scrollAmount = 0;
                peliculasContainer.style.transform = `translateX(-${scrollAmount}px)`;
            }
        });

        nextBtn.addEventListener('click', function() {
            let anchoVisible = peliculasContainer.clientWidth;
            let anchoTotal = peliculasContainer.scrollWidth;

            if (scrollAmount + anchoVisible < anchoTotal) {
                scrollAmount += (peliculaWidth);
            } else {
                scrollAmount = 0; // Vuelve al principio
            }
            peliculasContainer.style.transform = `translateX(-${scrollAmount}px)`;
        });
    });
});

