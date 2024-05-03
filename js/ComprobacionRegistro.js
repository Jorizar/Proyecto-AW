$(document).ready(function() {

	$("#correoOK").hide();
	$("#correoMAL").hide();

	$("#userOK").hide();
	$("#userMAL").hide();

	$("#campoEmail").change(function(){
		const campo = $("#campoEmail"); // referencia jquery al campo
		campo[0].setCustomValidity(""); // limpia validaciones previas

		// validación html5, porque el campo es <input type="email" ...>
		const esCorreoValido = campo[0].checkValidity();
		if (esCorreoValido && correoValidoUCM(campo.val())) {
			// el correo es válido y acaba por @ucm.es: marcamos y limpiamos quejas
		
			// tu código aquí: coloca la marca correcta

			$("#correoOK").show();
			$("#correoMAL").hide();

			campo[0].setCustomValidity("");
		} else {			
			// correo invalido: ponemos una marca y nos quejamos

			// tu código aquí: coloca la marca correcta

			$("#correoOK").hide();
			$("#correoMAL").show();

			campo[0].setCustomValidity("El correo debe ser válido y acabar por @ucm.es");
		}
	});

	
	$("#campoUser").change(function(){
		var url = "comprobarUsuario.php?user=" + $("#campoUser").val();
		$.get(url,usuarioExiste);
  });


	function correoValidoUCM(correo) {
		// tu codigo aqui (devuelve true ó false)
		return correo.substring(correo.indexOf('@')) === "@ucm.es";
	}

	function usuarioExiste(data,status) {
		// tu codigo aqui
		if (status == "success") {
			 if (data == 'disponible') {
			 	$("#userMAL").hide();
			 	$("#userOK").show();
			 }	
			 else{
			 	$("#userMAL").show();
			 	$("#userOK").hide();
			 }
		 }
			
	}
})