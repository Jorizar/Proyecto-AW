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
