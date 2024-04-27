const fila = document.querySelector('.contenedor-carousel');
const peliculas = document.querySelectorAll('.pelicula');

const flechaIzquierda = document.getElementById('flecha-izquierda');
const flechaDerecha = document.getElementById('flecha-derecha');

// ? ----- ----- Event Listener para la flecha derecha. ----- -----
flechaDerecha.addEventListener('click', () => {
	fila.scrollLeft += fila.offsetWidth;

	const indicadorActivo = document.querySelector('.indicadores .activo');
	if(indicadorActivo.nextSibling){
		indicadorActivo.nextSibling.classList.add('activo');
		indicadorActivo.classList.remove('activo');
	}
});

// ? ----- ----- Event Listener para la flecha izquierda. ----- -----
flechaIzquierda.addEventListener('click', () => {
	fila.scrollLeft -= fila.offsetWidth;

	const indicadorActivo = document.querySelector('.indicadores .activo');
	if(indicadorActivo.previousSibling){
		indicadorActivo.previousSibling.classList.add('activo');
		indicadorActivo.classList.remove('activo');
	}
});

// ? ----- ----- Paginacion ----- -----
const numeroPaginas = Math.ceil(peliculas.length / 5);
for(let i = 0; i < numeroPaginas; i++){
	const indicador = document.createElement('button');

	if(i === 0){
		indicador.classList.add('activo');
	}

	document.querySelector('.indicadores').appendChild(indicador);
	indicador.addEventListener('click', (e) => {
		fila.scrollLeft = i * fila.offsetWidth;

		document.querySelector('.indicadores .activo').classList.remove('activo');
		e.target.classList.add('activo');
	});
}

// ? ----- ----- Hover ----- -----
peliculas.forEach((pelicula) => {
	pelicula.addEventListener('mouseenter', (e) => {
		const elemento = e.currentTarget;
		setTimeout(() => {
			peliculas.forEach(pelicula => pelicula.classList.remove('hover'));
			elemento.classList.add('hover');
		}, 300);
	});
});

fila.addEventListener('mouseleave', () => {
	peliculas.forEach(pelicula => pelicula.classList.remove('hover'));
});

// ? ----- ----- Busqueda Dinamica de Peliculas ----- -----
function buscarPeliculas() {
	console.log('Buscando películas...');
	$.ajax({
		url: 'busquedaDinamica.php',  // Asegúrate de que esta URL es accesible
		type: 'POST',
		data: {
			tituloPelicula: $('#tituloPelicula').val(),
			directorPelicula: $('#directorPelicula').val(),
			generoPelicula: $('#generoPelicula').val(),
			annioPelicula: $('#annioPelicula').val()
		},
		success: function(response) {
			$('#resultadoBusqueda').html(response);
		},
		error: function() {
			// En caso de error en la solicitud, mostrar un mensaje adecuado
			$('#resultadoBusqueda').html('<p>Ocurrió un error al realizar la búsqueda.</p>');
		}
	});
}

//Botón para ver la lista
function verModificarLista(button, id_lista) {
    // Redirigir a otra página pasando el nombre de la lista como parámetro
    window.location.href = "ver_lista.php?id=" + id_lista;
}

