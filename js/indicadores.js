// Obtener referencia al contenedor de películas
const peliculasContainer = document.querySelector('.peliculas-container');

// Obtener referencia a los botones de navegación
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');

// Calcular el número de páginas basado en el número de películas y cuántas quieres mostrar por página
const peliculasPorPagina = 4; // Cambia esto según el número de películas que desees mostrar por página
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

// Agregar listeners de evento para los botones de navegación
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
    }
});
