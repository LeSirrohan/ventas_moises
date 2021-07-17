/*=============================================
=         SUBIENDO LA FOTO DEL USUARIO        =
=============================================*/


$(".previsualizar").attr("src",  'vistas/img/usuarios/default/anonymous.png' );




/*=============================================
=         EDITAR USUARIO (+)       =
=============================================*/

$("tr#num").each(function(){
	var idUsuario = $(this).attr("opcion");
	// ejecutamos la función click sobr el elemento que estamos clickando
	$(this).off().on("click",function(ev){
		console.log(idUsuario);
		ev.preventDefault();
	
		var datos = new FormData();
		datos.append("idUsuario", idUsuario);
	
		$.ajax({
			url: "ajax/usuarios.ajax.php",
			method : "POST",
			data: datos,
			cache: false,
			contentType : false,
			processData : false,
			 
			success: function(response){
				var respuesta = JSON.parse(response);
				$("#editarNombre").val(respuesta["nomusuario"]);
				$("#editarUsuario").val(respuesta["codusuario"]);
				//$("#editarPerfil").html(respuesta["perfil"]);
				$("#editarPerfil").val(respuesta["codperfil"]).trigger('change.select2');
	
				$("#passwordActual").val(respuesta["clave"]);
	
			}	
		});	
	});
});
/*=============================================
=         ACTIVAR USUARIO        =
=============================================*/
$(document).on("click",".btnActivar",function(){
	var idUsuarioActivar = $(this).attr("idUsuario");
	var estadoUsuarioActivar = $(this).attr("estadoUsuario");

	var datos = new FormData();
	datos.append("idUsuarioActivar",idUsuarioActivar);
	datos.append("estadoUsuarioActivar",estadoUsuarioActivar);

	$.ajax({
		url : "ajax/usuarios.ajax.php",
		method: "POST",
		data : datos,
		cache: false,
		contentType : false,
		processData : false ,
		success: function(respuesta){
			if(window.matchMedia("(max-width:767px)").matches){
				swal({
					title : "El usuario ha sido actualizado",
					type: "success",
					confirmButtonText : "¡Cerrar!",

				}).then(function(result){
					window.location = "usuarios";
				})
			}

		}

	});

	if(estadoUsuarioActivar == 0 ){
		$(this).removeClass('btn-danger');
		$(this).addClass('btn-success');

		$(this).html('Activado');
		$(this).attr('estadoUsuario' , 1 );

	}else {
		$(this).removeClass('btn-success');
		$(this).addClass('btn-danger');

		$(this).html('Desactivado');
		$(this).attr('estadoUsuario' , 0 );


	}




});

/*=============================================
=         REVISAR SI USUARIO YA ESTA REPETIDO =
=============================================*/

$("#nuevoUsuario").keyup(function(){
	$(".alert").remove();
});

$("#nuevoUsuario").change(function(){
	var usuarioValidarRepetido = $(this).val();
	
	var datos = new FormData();
	datos.append("usuarioValidarRepetido",usuarioValidarRepetido);
	$.ajax({
		url : "ajax/usuarios.ajax.php",
		method: "POST",
		data : datos,
		cache: false,
		contentType : false,
		processData : false ,
		dataType : "json" ,
		success: function(respuesta){


			if(respuesta){ // Viene con informacion - Caso repetido
 
				$("#nuevoUsuario").parent().before('<div class="alert alert-danger">Este usuario ya existe en la base de datos</div>');
				$("#nuevoUsuario").val('') ;
			 }

		}


	});


});


/*=============================================
=        ALERTA PARA ELIMINAR USUARIO         =
=============================================*/


$(document).on("click",".btnEliminarUsuario" , function(){
 
 	var idUsuario = $(this).attr("idUsuarioEliminar");
 	var fotoUsuario = $(this).attr("fotoUsuarioEliminar");
 	var usuario = $(this).attr("usuarioEliminar");


 		swal({
		      title: "¿Está seguro que desea eliminar el usuario?",
		      text: "¡Si no lo está puede cancelar está acción!",
		      type: "warning",
		      showCancelButton: true,
		      confirmButtonColor: "#3085d6",
		      cancelButtonColor: "#d33",
		      cancelButtonText: "Cancelar",
		      confirmButtonText: "Si, borrar usuario!"
		}).then(function(result){
			if(result.value){
				window.location ='index.php?ruta=usuarios&idUsuario='+idUsuario+'&fotoUsuario='+fotoUsuario+'&usuario='+usuario; 
			}


		});


});