<?php

class ControladorInventario {

	/*=============================================
	MOSTRAR VENTAS TABLAS
	=============================================*/


	
static public function ctrMostrarInventario(){

 
		$respuesta = ModeloInventario::mdlMostrarInventario( );

		return $respuesta;



}
static public function ctrMostrarInventarioTrx(){

 
		$respuesta = ModeloInventario::mdlMostrarInventarioTrx( );

		return $respuesta;



}
	static public function ctrInventarioProducto( $id )
	{
		$respuesta = ModeloInventario::mdlInventarioProducto( $id );
		return $respuesta;
	}




	static public function ctrMostrarInventarioById( $obj ){

	
		$respuesta = ModeloInventario::mdlMostrarInventarioById( $obj );

		return $respuesta;



	}


	static public function ctrMostrarNombreInventarioById( $obj ){

	
		$respuesta = ModeloInventario::mdlMostrarNombreInventarioById( $obj );

		return $respuesta;



	}

	static public function ctrEliminarUnidadMedidaInventario($idUnidadMedidaInventario ){

	  

		$respuesta = ModeloInventario::mdlEliminarUnidadMedidaInventario($idUnidadMedidaInventario );

		return $respuesta;

	}



	/*=============================================
	CREAR CLIENTES
	=============================================*/

	static public function ctrAgregarProductoInventario(){

		if(isset($_POST["nuevaDescripcion"])){

			
/*

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevoDocumentoId"]) &&
			   preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) && 
			   preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDireccion"]))*/

			   	$tabla = "inventario";


	date_default_timezone_set('America/Bogota');
			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$fecha = $fecha.' '.$hora;


			   	$datos = array("codigo_barras"=>$_POST["nuevoCodigo"],
					           "nombre"=>$_POST["nuevaDescripcion"],

					           "cantidad_alerta"=>$_POST["crearStockMinimo"],
					           "costo_ideal"=>$_POST["costoIdeal"],
					        "fecha"=> $fecha,
					        

					           "medida_ingreso"=>$_POST["nuevaMedida"]);

					
			   	$respuesta = ModeloInventario::mdlIngresarInventario($tabla, $datos);


	if(  	$respuesta  == "ok" ) {


 
					echo'<script>

					swal({
						  type: "success",
						  title: "El producto ha sido creado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "inventario";

									}
								})

					</script>';


			 

				} else 
								echo '<script>
					swal({
						type: "error",
						title: "'.$respuesta. '",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "inventario";
						}
					});
				</script>';

		

		}   

	}



	static public function ctrCrearUnidadMedida(){


			if(isset($_POST["nuevaUnidadMedidaNombre"])){

 

			   	$datos = array("unidad_medida_salida"=>$_POST["nuevaUnidadMedidaNombre"],
					           "id_inventario"=>$_POST["idInventarioUnidadMedida"],
					           "equivalencia"=>$_POST["nuevaUnidadMedidaEquivalencia"]);

					
			   	$respuesta = ModeloInventario::mdlCrearUnidadMedida(  $datos  );


	if(  	$respuesta  == "ok" ) {


 
					echo'<script>

					swal({
						  type: "success",
						  title: "El producto ha sido creado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "inventario";

									}
								})

					</script>';


			 

				} else 
								echo '<script>
					swal({
						type: "error",
						title: "'.$respuesta. '",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "inventario";
						}
					});
				</script>';

		



			}

	}

	/*=============================================
	CREAR CLIENTES
	=============================================*/

	static public function ctrEditarProductoInventario(){

		if(isset($_POST["editarNombre"])){

			
/*

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevoDocumentoId"]) &&
			   preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) && 
			   preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDireccion"]))*/

			   	$tabla = "inventario";

 
			   	$datos = array(
			   		"id"=>$_POST["idInventarioEdicion"],
			   		"codigo_barras"=>$_POST["editarCodigo"],
					           "nombre"=>$_POST["editarNombre"],

					           "cantidad_alerta"=>$_POST["editarCantidadAlerta"],
					           "costo_ideal"=>$_POST["editarCostoIdeal"],
				 
					        

					           "medida_ingreso"=>$_POST["editarMedida"]);

					
			   	$respuesta = ModeloInventario::mdlEditarInventario($tabla, $datos);


	if(  	$respuesta  == "ok" ) {


 
					echo'<script>

					swal({
						  type: "success",
						  title: "El producto ha sido creado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "inventario";

									}
								})

					</script>';


			 

				} else echo '<script>
					swal({
						type: "error",
						title: "'.$respuesta. '",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "inventario";
						}
					});
				</script>';

		

		}   

	}





	/*=============================================
			UNIDAD DE MEDIDA
	=============================================*/

static public function ctrMostrarUnidadMedida($id_inventario){

		$tabla = "inventario_x_unidad_medida_salida";

		$respuesta = ModeloInventario::mdlMostrarUnidadMedida($tabla , $id_inventario);

		return $respuesta;



}





	/*=============================================
			UNIDAD DE MEDIDA x INVENTARIO
	=============================================*/

static public function ctrMostrarUnidadMedidaxInventario(){

		$respuesta = ModeloInventario::mdlMostrarUnidadMedidaxInventario();

		return $respuesta;

}



	/*=============================================
	ELIMINAR PRODUCTO INVENTARIO
	=============================================*/

	static public function ctrEliminarProductoInventario($id){
 
		$tabla = "inventario";

		$respuesta = ModeloInventario::mdlEliminarProductoInventario($tabla, $id);

		return $respuesta;


		}

	static public function ctrAddProductoInventario( $inventario ){

        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $inventario->Fecha = $fecha.' '.$hora;

        $listaProductos = array(
            'id_inventario'     => $inventario->id_inventario,
            'id_producto'       => $inventario->id_producto,
            'nombre_producto'   => $inventario->nuevaDescripcion,
            'cantidad_producto' => $inventario->Cantidad,
            'Precio'            => $inventario->Precio,
            'Fecha'             => $inventario->Fecha,
            'tipo_transaccion'  => $inventario->tipo_transaccion,
            'Nota'              => $inventario->Nota
        );
		return ModeloInventario::mdlAddProductoInventario( $listaProductos );

	}
	static public function ctrDisminuirProductoInventario( $inventario ){

        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $inventario->Fecha = $fecha.' '.$hora;

        $listaProductos = array(
            'id_inventario'     => $inventario->id_inventario,
            'id_producto'       => $inventario->id_producto,
            'nombre_producto'   => $inventario->nuevaDescripcion,
            'cantidad_producto' => $inventario->Cantidad,
            'Precio'            => $inventario->Precio,
            'Fecha'             => $inventario->Fecha,
            'tipo_transaccion'  => $inventario->tipo_transaccion,
            'Nota'              => $inventario->Nota
		);	

		return ModeloInventario::mdlDisminuirProductoInventario( $listaProductos );

	}

	static public function ctrGetListUndMedidaProducto(){
	
			$respuesta = ModeloInventario::mdlGetListUndMedidaProducto();
	
			return $respuesta;
	
	}
	 
	static public function ctrGetListInvUndMedidaSalida(){
	
		$respuesta = ModeloInventario::mdlGetListInvUndMedidaSalida();

		return $respuesta;

	}
	 
	static public function ctrGetListInventario(){
	
		$respuesta = ModeloInventario::mdlGetListInventario();

		return $respuesta;

	}

}