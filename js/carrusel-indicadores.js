document.addEventListener("DOMContentLoaded", function() {
    const carruseles = document.querySelectorAll('.carrusel');

    carruseles.forEach(function(carrusel) {
        const peliculasContainer = carrusel.querySelector('.peliculas-container');
        const prevBtn = carrusel.querySelector('.prev');
        const nextBtn = carrusel.querySelector('.next');

        let scrollAmount = 0;
        let numPelis = 4;

        const peliculaWidth = calcularAnchoPelicula();

        // Función para calcular el ancho de cada película incluyendo el margen
        function calcularAnchoPelicula() {
            const primeraPelicula = peliculasContainer.querySelector('.pelicula');
            const computedStyle = window.getComputedStyle(primeraPelicula);
            const width = primeraPelicula.offsetWidth; 
            const marginRight = parseFloat(computedStyle.marginRight); 
            const totalWidth = width + marginRight;
            return totalWidth;
        }

        // Función para calcular el ancho total de las películas
        function calcularAnchoTotal() {
            let numPeliculas = peliculasContainer.querySelectorAll('.pelicula').length;
            return numPeliculas * (peliculaWidth * numPelis);
        }

        // Función para desplazarse a la izquierda
        prevBtn.addEventListener('click', function() {
            if (scrollAmount > 0) {
                scrollAmount -= (peliculaWidth * numPelis);
                if (scrollAmount < 0) scrollAmount = 0;
                peliculasContainer.style.transform = `translateX(-${scrollAmount}px)`;
            }
        });

        // Función para desplazarse a la derecha
        nextBtn.addEventListener('click', function() {
            let anchoVisible = peliculasContainer.clientWidth;
            let anchoTotal = peliculasContainer.scrollWidth;

            if (scrollAmount + anchoVisible < anchoTotal) {
                scrollAmount += (peliculaWidth * numPelis);
            } else {
                scrollAmount = 0; // Vuelve al principio
            }
            peliculasContainer.style.transform = `translateX(-${scrollAmount}px)`;

            // Calcular la nueva página y actualizar el indicador
            let paginaActual = Math.floor(scrollAmount / (peliculaWidth * numPelis));
            actualizarIndicadores(paginaActual);
        });
    });

    // Obtener referencia al contenedor de películas
    const peliculasContainer = document.querySelector('.peliculas-container');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');

    // Calcular el número de páginas basado en el número de películas y cuántas quieres mostrar por página
    const peliculasPorPagina = 4;
    const numeroPaginas = Math.ceil(peliculasContainer.children.length / peliculasPorPagina);

    // Crear botones de indicadores y agregarlos al contenedor de indicadores
    for (let i = 0; i < numeroPaginas; i++) {
        const indicador = document.createElement('button');
        if (i === 0) {
            indicador.classList.add('activo');
        }
        document.querySelector('.indicadores').appendChild(indicador);
        indicador.addEventListener('click', (e) => {
            peliculasContainer.style.transform = `translateX(-${i * (peliculasPorPagina * 285)}px)`; // 260px es el ancho de cada película, ajusta según sea necesario
            document.querySelector('.indicadores .activo').classList.remove('activo');
            e.target.classList.add('activo');
        });
    }

    prevBtn.addEventListener('click', () => {
        const paginaActual = document.querySelector('.indicadores .activo');
        const indicePagina = [...paginaActual.parentElement.children].indexOf(paginaActual);
        if (indicePagina > 0) {
            const nuevaPagina = paginaActual.previousElementSibling;
            peliculasContainer.style.transform = `translateX(-${(indicePagina - 1) * (peliculasPorPagina * 285)}px)`;
            paginaActual.classList.remove('activo');
            nuevaPagina.classList.add('activo');
        }
    });

    nextBtn.addEventListener('click', () => {
        const paginaActual = document.querySelector('.indicadores .activo');
        const indicePagina = [...paginaActual.parentElement.children].indexOf(paginaActual);
        if (indicePagina < numeroPaginas - 1) {
            const nuevaPagina = paginaActual.nextElementSibling;
            peliculasContainer.style.transform = `translateX(-${(indicePagina + 1) * (peliculasPorPagina * 285)}px)`;
            paginaActual.classList.remove('activo');
            nuevaPagina.classList.add('activo');
        } else {
            const primeraPagina = document.querySelector('.indicadores button');
            peliculasContainer.style.transform = `translateX(0)`;
            paginaActual.classList.remove('activo');
            primeraPagina.classList.add('activo');
        }
    });
});

