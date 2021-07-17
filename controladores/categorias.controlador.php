<?php
class ControladorCategorias {




/*=============================================
=            MODAL CREAR CATEGORIA          =
=============================================*/



	static public function ctrCrearCategoria(){



		if(isset($_POST["nuevaDescripcionCateg"] )){
			if(preg_match( '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDescripcionCateg"]) && !empty($_POST["nuevaDescripcionCateg"])){
				$tabla = "categoria";
				$datos = array ("descripcion" => $_POST["nuevaDescripcionCateg"], "id_local" => $_POST["id_local"]);
				unset($_POST["id_local"]);
				unset($_POST["nuevaDescripcionCateg"]);

				$respuesta = ModeloCategorias::mdlIngresarCategoria($tabla,$datos);

 				if($respuesta == "ok" ){

 				echo '<script>

					swal({
						type: "success",
						title: "¡La categoría ha sido guardada correctamente!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location.href = "categorias";

						}

					});
				

				</script>';

 				}else 		
				echo '<script>

					swal({
						type: "error",
						title: "¡La categoría no se ha logrado guardar!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location = "categorias";

						}

					});
				

				</script>';

			}else
			 	echo '<script>

					swal({
						type: "error",
						title: "¡La categoría no puede ir vacía o llevar caracteres especiales.!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location = "categorias";

						}

					});
				

				</script>';


		}

	}




/*=============================================
=            MODAL MOSTRAR CATEGORIA          =
=============================================*/


	static public function ctrMostrarCategorias( $item , $valor){

				$tabla = "categoria";
				$respuesta = ModeloCategorias::mdlMostrarCategorias($tabla, $item , $valor );
				return $respuesta;
			}//

	static public function ctrMostrarCategoriasLocal( $item , $valor){

		$tabla = "categoria";
		$respuesta = ModeloCategorias::mdlMostrarCategoriasLocal($tabla, $item , $valor );
		return $respuesta;
	}

	

/*=============================================
=            MODAL EDITAR CATEGORIA          =
=============================================*/


		static public function ctrEditarCategoria(){



		if(isset($_POST["editarCategoria"] )){
			if(preg_match( '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCategoria"]) && !empty($_POST["editarCategoria"])){
				
				$tabla = "categoria";
 				$datos = array ("id" => $_POST["idCategoria"],
 								"descripcion" => $_POST["editarCategoria"]);
				unset($_POST["editarCategoria"]);

				$respuesta = ModeloCategorias::mdlEditarCategoria($tabla,$datos);

 				if($respuesta == "ok" ){

 				echo '<script>

					swal({
						type: "success",
						title: "¡La categoría ha sido actualizada correctamente!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location = "categorias";

						}

					});
				

				</script>';

 				}else 		
				echo '<script>

					swal({
						type: "error",
						title: "¡La categoría no se ha logrado actualizar!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location = "categorias";

						}

					});
				

				</script>';

			}else
			 	echo '<script>

					swal({
						type: "error",
						title: "¡La categoría no puede ir vacía o llevar caracteres especiales.!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"

					}).then(function(result){

						if(result.value){
						
							window.location = "categorias";

						}

					});
				

				</script>';


		}

	}

}