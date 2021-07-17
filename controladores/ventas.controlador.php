<?php
class ControladorVentas {

	/*=============================================
				MOSTRAR VENTAS TABLAS CAJA
	=============================================*/

static public function ctrMostrarVentasTabla(  $id_caja){

		$tabla = "ventas";


		//$respuesta = ModeloVentas::mdlMostrarVentasTablas($fechaInicio, $fechaFin,$id_caja);
		$respuesta = ModeloVentas::mdlMostrarVentasTablas($id_caja);

		return $respuesta;

}
/*=============================================
			MOSTRAR VENTAS TABLAS CAJA
=============================================*/

static public function ctrListadoVentasTabla( $fechaInicio, $fechaFin ){

	$tabla = "ventas";


	$respuesta = ModeloVentas::mdlListadoVentasTablas( $fechaInicio, $fechaFin );

	return $respuesta;

}
/*=============================================
			MOSTRAR VENTAS DETALLE
=============================================*/

static public function ctrListadoVentasDetalle( $obj ){

	$tabla = "ventas";

	$respuesta = ModeloVentas::mdlListadoVentasDetalle( $obj );

	return $respuesta;

}
/*=============================================
			MOSTRAR VENTAS DETALLE
=============================================*/

static public function ctrReporteVentasDetalle( $obj ){

	$tabla = "ventas";

	$respuesta = ModeloVentas::mdlReporteVentasDetalle( $obj );

	return $respuesta;

}
/*=============================================
			MOSTRAR PRECIOS MODIFICADOS
=============================================*/

static public function ctrReportePreciosMod( $obj ){

	$tabla = "ventas";

	$respuesta = ModeloVentas::mdlReportePreciosMod( $obj );

	return $respuesta;

}

/*=============================================
			MOSTRAR CLIENTES VENTAS
=============================================*/

static public function ctrReporteClienteVentas( $obj ){

	return ModeloVentas::mdlReporteClienteVentas( $obj );

}

/*=============================================
			MOSTRAR CLIENTES VENTAS
=============================================*/

static public function ctrReporteProductoVentas( $obj ){


	return ModeloVentas::mdlReporteProductoVentas( $obj );

}

/*=============================================
			MOSTRAR PRODUCTOS VENTAS
=============================================*/

static public function ctrReporteProductoVentasCaja( $obj ){


	return ModeloVentas::mdlReporteProductoVentasCaja( $obj );

}

/*=============================================
			MOSTRAR VENTAS CAJA
=============================================*/

static public function ctrReporteVentasCaja( $obj ){


	return ModeloVentas::mdlReporteVentasCaja( $obj );

}

/*=============================================
			MOSTRAR VENTAS DETALLE
=============================================*/

static public function ctrAnularVenta( $obj ){

	$tabla = "ventas";
	$confirmaClave = ModeloLocal::mdlClaveConfimacion( $obj );
	if($confirmaClave){
		return ModeloVentas::mdlAnularVenta( $tabla , $obj );

	}
	else
	{

		$respo = "CLAVE DE CONFIRMACIÓN INVÁLIDA";
		return $respo;

	}


}

/*=============================================
			MOSTRAR VENTAS DETALLE
=============================================*/

static public function ctrClaveConfimacion( $obj )
{
	return $confirmaClave = ModeloLocal::mdlClaveConfimacion( $obj );

}
	/*=============================================
	MOSTRAR VENTAS TABLAS
	=============================================*/

static public function ctrMostrarVentasGraficoVentasxDia($fechaInicio, $fechaFin){

		$respuesta = ModeloVentas::mdlMostrarVentasGraficoVentasxDia($fechaInicio, $fechaFin);



   $ventas_fecha_array = array();
    $ventas_fecha_objeto = array();
	
   
		 


    foreach ($respuesta as $key => $value) {
      
      $ventas_fecha_objeto = array();

      $cantidad = $value["cantidad"] ;
      $ventas = $value["ventas"] ;
      $fecha = $value["fecha"] ;
 

      $ventas_fecha_objeto = array('fecha'=> $fecha , 'ventas S/'=> $ventas , 'cantidad' => $cantidad );


     	 array_push( $ventas_fecha_array , $ventas_fecha_objeto ) ;

    };


	$datos = json_encode($ventas_fecha_array);




		return $datos;

}




	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

static public function ctrMostrarVentas($item, $valor){

		$tabla = "ventas";

		$respuesta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

		return $respuesta;

}


	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

static public function ctrMostrarDetalleVentas($id_sesion_caja){
 

		$respuesta = ModeloVentas::mdlMostrarDetalleVentas($id_sesion_caja );

		return $respuesta;

}

	/*=============================================
	MOSTRAR FORMAS DE COBRO VENTAS
	=============================================*/

static public function ctrMostrarFormasDeCobro($id_venta){
 

		$respuesta = ModeloVentas::mdlMostrarFormasDeCobro($id_venta );


		

		return $respuesta;

}




	
/*=============================================
			CREAR VENTA
=============================================*/
 
	static public function ctrCrearVenta( $id_sesion_caja, $listaProductos_par , $listaCobros_par, $descuento , $descuentoMotivo , $vuelto , $id_vendedor, $nombre_vendedor,$id_local , $comprobante_par, $comentario_venta){


 

		//echo "<script> console.log('estoy aqui'); </script>";

//		if(isset($_POST["nuevaVentaListaProductos"])){

			//echo "<script> console.log('si entre	'); </script>";

			/*=============================================
			ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
			=============================================*/


			$listaProductos = array();
			$listaCobros = array();

			$listaProductos = json_decode(  $listaProductos_par , true);		
			$listaCobros = json_decode(   $listaCobros_par, true );		
			$comprobante= json_decode(   $comprobante_par, true );		



			if(  $descuento !== '' )
				$descuento 	=  $descuento ;
			else 
				$descuento 	= 0 ;

 


 
/*
			foreach ($listaProductos as $key => $value) {

			   array_push($totalProductosComprados, $value["cantidad"]);
				
			   $tablaProductos = "producto";

// ARREGLAR ESTA PARTE PARA QUE SOLO SEA UNA LLAMADA A BD SIMPLEMENTE DEBERIA SE UNA LLAMADA A LA BD ACTUALIZANDO EL PRODUCTO EN SU CANDIDAD DE VENTAS Y SU STOCK
			    $item = "id";
			    $valor = $value["id"];
			    $traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor);
				$item1a = "ventas";
				$valor1a = $value["cantidad"] + $traerProducto["ventas"];
			    $nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);
				$item1b = "stock";
				$valor1b = $traerProducto["stock"] - $value["cantidad"];
				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);
// ARREGLAR ESTA PARTE PARA QUE SOLO SEA UNA LLAMADA A BD

			}

			$tablaClientes = "cliente";

			$item = "id";
			//$valor = $_POST["nuevaVentaSeleccionarCliente"];
			$valor = $_POST["nuevaVentaClienteSeleccionado"] ;

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $item, $valor);

			$item1a = "compras";
			$valor1a = array_sum($totalProductosComprados) + $traerCliente["compras"];

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valor);

			$item1b = "fecha_ultima_compra";



			$fechaCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1b, $valor1b, $valor);
			// ARREGLAR ESTA PARTE PARA QUE SOLO SEA UNA LLAMADA A BD
*/


			date_default_timezone_set('America/Bogota');
			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$fecha = $fecha.' '.$hora;


			/*=============================================
			GUARDAR LA COMPRA
			=============================================*/	

			$respuesta = ModeloVentas::mdlIngresarVenta( $listaProductos, $listaCobros , $descuento , $descuentoMotivo,$fecha ,$id_sesion_caja  , $vuelto , $id_vendedor, $nombre_vendedor, $id_local , $comprobante, $comentario_venta);

			return $respuesta;

/*
			if( $respuesta =="ok"  ){




				echo'<script>			

				swal({
					  type: "success",
					  title: "La compra ha sido guardada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								if (result.value) {

								window.location = "crear-ventas";

								}
							})

				</script>';

			} else 
				
			 	echo '<script>
					swal({
						type: "error",
						title: "'.$respuesta .'",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "crear-ventas";
						}
					});
				</script>';
*/

	

		}

	


/*=============================================
			EDITAR VENTA
=============================================*/
 

	/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function ctrEditarVenta(){

		if(isset($_POST["editarVentaListaProductos"])){

			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/
			$tabla = "ventas";

			$item = "id";
			$valor = $_POST["editarVentaIdVenta"];

			echo '<script>alert('.$valor.')</script>';
			
			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			/*=============================================
			REVISAR SI VIENE PRODUCTOS EDITADOS
			=============================================*/

			if($_POST["editarVentaListaProductos"] == ""){

				$listaProductos = $traerVenta["productos"];
				$cambioProducto = false;


			}else{

				$listaProductos = $_POST["editarVentaListaProductos"];
				$cambioProducto = true;
			}

			if($cambioProducto){

				$productos =  json_decode($traerVenta["productos"], true);

				$totalProductosComprados = array();

				foreach ($productos as $key => $value) {

					array_push($totalProductosComprados, $value["cantidad"]);
					
					$tablaProductos = "productos";

					$item = "id";
					$valor = $value["id"];

					$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor);

					$item1a = "ventas";
					$valor1a = $traerProducto["ventas"] - $value["cantidad"];

					$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

					$item1b = "stock";
					$valor1b = $value["cantidad"] + $traerProducto["stock"];

					$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

				}

				$tablaClientes = "cliente";

				$itemCliente = "id";
				$valorCliente = $_POST["nuevaVentaClienteSeleccionado"];

				$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

				$item1a = "compras";
				$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);

				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

				/*=============================================
				ACTUALIZAR LAS COMPRAS DEL CLIENTE Y REDUCIR EL STOCK Y AUMENTAR LAS VENTAS DE LOS PRODUCTOS
				=============================================*/

				$listaProductos_2 = json_decode($listaProductos, true);

				$totalProductosComprados_2 = array();

				foreach ($listaProductos_2 as $key => $value) {

					array_push($totalProductosComprados_2, $value["cantidad"]);
					
					$tablaProductos_2 = "productos";

					$item_2 = "id";
					$valor_2 = $value["id"];

					$traerProducto_2 = ModeloProductos::mdlMostrarProductos($tablaProductos_2, $item_2, $valor_2);

					$item1a_2 = "ventas";
					$valor1a_2 = $value["cantidad"] + $traerProducto_2["ventas"];

					$nuevasVentas_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1a_2, $valor1a_2, $valor_2);

					$item1b_2 = "stock";
					$valor1b_2 = $traerProducto_2["stock"] - $value["cantidad"];

					$nuevoStock_2 = ModeloProductos::mdlActualizarProducto($tablaProductos_2, $item1b_2, $valor1b_2, $valor_2);

				}

				$tablaClientes_2 = "clientes";

				$item_2 = "id";
				$valor_2 = $_POST["nuevaVentaClienteSeleccionado"];

				$traerCliente_2 = ModeloClientes::mdlMostrarClientes($tablaClientes_2, $item_2, $valor_2);

				$item1a_2 = "compras";
				$valor1a_2 = array_sum($totalProductosComprados_2) + $traerCliente_2["compras"];

				$comprasCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1a_2, $valor1a_2, $valor_2);

				$item1b_2 = "fecha_ultima_compra";

				date_default_timezone_set('America/Bogota');

				$fecha = date('Y-m-d');
				$hora = date('H:i:s');
				$valor1b_2 = $fecha.' '.$hora;

				$fechaCliente_2 = ModeloClientes::mdlActualizarCliente($tablaClientes_2, $item1b_2, $valor1b_2, $valor_2);

			}

			/*=============================================
			GUARDAR CAMBIOS DE LA COMPRA
			=============================================*/	

			$datos = array("id_vendedor"=>$_POST["nuevaVentaIdVendedor"],
						   "id_cliente"=>$_POST["nuevaVentaClienteSeleccionado"],
						   "id"=>$_POST["editarVentaIdVenta"],
						   "productos"=>$listaProductos,
						   "total"=>$_POST["nuevaVentaTotalVenta"],
						   "metodo_pago"=>$_POST["listaMetodoPago"]);


			$respuesta = ModeloVentas::mdlEditarVenta($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				localStorage.removeItem("rango");

				swal({
					  type: "success",
					  title: "La venta ha sido editada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then((result) => {
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';

			}

		}

	}

		/*=============================================
	ELIMINAR VENTA
	=============================================*/

	static public function ctrEliminarVenta(){

		if(isset($_GET["idVenta"])){

			$tabla = "ventas";

			$item = "id";
			$valor = $_GET["idVenta"];

			$traerVenta = ModeloVentas::mdlMostrarVentas($tabla, $item, $valor);

			/*=============================================
			ACTUALIZAR FECHA ÚLTIMA COMPRA
			=============================================*/
/*
			$tablaClientes = "cliente";

			$itemVentas = null;
			$valorVentas = null;

			$traerVentas = ModeloVentas::mdlMostrarVentas($tabla, $itemVentas, $valorVentas);

			$guardarFechas = array();

			foreach ($traerVentas as $key => $value) {
				
				if($value["id_cliente"] == $traerVenta["id_cliente"]){

					array_push($guardarFechas, $value["fecha"]);

				}

			}

			if(count($guardarFechas) > 1){

				if($traerVenta["fecha"] > $guardarFechas[count($guardarFechas)-2]){

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-2];
					$valorIdCliente = $traerVenta["id_cliente"];

					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

				}else{

					$item = "ultima_compra";
					$valor = $guardarFechas[count($guardarFechas)-1];
					$valorIdCliente = $traerVenta["id_cliente"];

					$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

				}


			}else{

				$item = "ultima_compra";
				$valor = "0000-00-00 00:00:00";
				$valorIdCliente = $traerVenta["id_cliente"];

				$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item, $valor, $valorIdCliente);

			}
*/
			/*=============================================
			FORMATEAR TABLA DE PRODUCTOS Y LA DE CLIENTES
			=============================================*/

			$productos =  json_decode($traerVenta["productos"], true);

			$totalProductosComprados = array();

			foreach ($productos as $key => $value) {

				array_push($totalProductosComprados, $value["cantidad"]);
				
				$tablaProductos = "productos";

				$item = "id";
				$valor = $value["id"];
				$orden = "id";

				$traerProducto = ModeloProductos::mdlMostrarProductos($tablaProductos, $item, $valor, $orden);

				$item1a = "ventas";
				$valor1a = $traerProducto["ventas"] - $value["cantidad"];

				$nuevasVentas = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1a, $valor1a, $valor);

				$item1b = "stock";
				$valor1b = $value["cantidad"] + $traerProducto["stock"];

				$nuevoStock = ModeloProductos::mdlActualizarProducto($tablaProductos, $item1b, $valor1b, $valor);

			}

			$tablaClientes = "clientes";

			$itemCliente = "id";
			$valorCliente = $traerVenta["id_cliente"];

			$traerCliente = ModeloClientes::mdlMostrarClientes($tablaClientes, $itemCliente, $valorCliente);

			$item1a = "compras";
			$valor1a = $traerCliente["compras"] - array_sum($totalProductosComprados);

			$comprasCliente = ModeloClientes::mdlActualizarCliente($tablaClientes, $item1a, $valor1a, $valorCliente);

			/*=============================================
			ELIMINAR VENTA
			=============================================*/

			$respuesta = ModeloVentas::mdlEliminarVenta($tabla, $_GET["idVenta"]);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "La venta ha sido borrada correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "ventas";

								}
							})

				</script>';

			}		
		}

	}

		/*=============================================
			DESCARGAR REPORTE VENTAS EXCEL
		=============================================*/

		static public function ctrDescargarReporteExcel(){

			if(isset($_GET["reporte"])){

				if(isset($_GET["fechaInicial"]) && isset($_GET["fechaFinal"])){

				}
				else{

						$tabla = "ventas";

						$fechaInicio = null;
						$valorVentas = null;

						$ventas = ModeloVentas::mdlMostrarVentas($tabla, $fechaInicio, $valorVentas);					

				}

			}

			/*=============================================
			CREAMOS EL ARCHIVO DE EXCEL
			=============================================*/

			$Name = $_GET["reporte"].'.xls';

			header('Expires: 0');
			header('Cache-control: private');
			header("Content-type: application/vnd.ms-excel"); // Archivo de Excel
			header("Cache-Control: cache, must-revalidate"); 
			header('Content-Description: File Transfer');
			header('Last-Modified: '.date('D, d M Y H:i:s'));
			header("Pragma: public"); 
			header('Content-Disposition:; filename="'.$Name.'"');
			header("Content-Transfer-Encoding: binary");
		
			echo utf8_decode("<table border='0'> 

					<tr> 
					<td style='font-weight:bold; border:1px solid #eee;'>CÓDIGO</td> 
					<td style='font-weight:bold; border:1px solid #eee;'>CLIENTE</td>
					<td style='font-weight:bold; border:1px solid #eee;'>VENDEDOR</td>
					<td style='font-weight:bold; border:1px solid #eee;'>CANTIDAD</td>
					<td style='font-weight:bold; border:1px solid #eee;'>PRODUCTOS</td>
					<td style='font-weight:bold; border:1px solid #eee;'>IMPUESTO</td>
					<td style='font-weight:bold; border:1px solid #eee;'>NETO</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>TOTAL</td>		
					<td style='font-weight:bold; border:1px solid #eee;'>METODO DE PAGO</td	
					<td style='font-weight:bold; border:1px solid #eee;'>FECHA</td>		
					</tr>");

			foreach ($ventas as $row => $item){

				$cliente = ControladorClientes::ctrMostrarClientes("id", $item["id_cliente"]);
				$vendedor = ControladorUsuarios::ctrMostrarUsuario("id", $item["id_vendedor"]);

			 echo utf8_decode("<tr>
			 			<td style='border:1px solid #eee;'>".$item["codigo"]."</td> 
			 			<td style='border:1px solid #eee;'>".$cliente["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>".$vendedor["nombre"]."</td>
			 			<td style='border:1px solid #eee;'>");

			 	$productos =  json_decode($item["productos"], true);

			 	foreach ($productos as $key => $valueProductos) {
			 			
			 			echo utf8_decode($valueProductos["cantidad"]."<br>");
			 		}

			 	echo utf8_decode("</td><td style='border:1px solid #eee;'>");	

		 		foreach ($productos as $key => $valueProductos) {
			 			
		 			echo utf8_decode($valueProductos["descripcion"]."<br>");
		 		
		 		}

		 		echo utf8_decode("</td>
					<td style='border:1px solid #eee;'>$ ".number_format($item["total"],2)."</td>
					<td style='border:1px solid #eee;'>".$item["metodo_pago"]."</td>
					<td style='border:1px solid #eee;'>".substr($item["fecha_venta"],0,10)."</td>		
		 			</tr>");


			}


			echo "</table>";

		}
/*=============================================
			SUMA TOTAL DE VENTAS.
=============================================*/
 
	static public function ctrObtenerSumaTotalDeVentasCantYTransaccion(){

		$respuesta = ModeloVentas::mdlObtenerSumaTotalDeVentasCantYTransaccion();
		return $respuesta ;

	}
/*=============================================
			VENDEDOR CON MÁS VENTAS
=============================================*/
 
	static public function ctrVendedorTop(){

		$respuesta = ModeloVentas::mdlVendedorTop();
		return $respuesta ;

	}
	

/*=============================================
	ACTUALIZACIÓN DE PAGOS PARA LAS VENTAS.
=============================================*/
 
	static public function ctrActualizarPagos($id_sesion_caja,  $listaCobros_par,  $vuelto ,  $id_venta){


	
			$listaCobros = array();
			$listaCobros = json_decode(   $listaCobros_par, true );		
	



 			date_default_timezone_set('America/Bogota');
			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$fecha = $fecha.' '.$hora;


			/*=============================================
			GUARDAR LA COMPRA
			=============================================*/	

			$respuesta = ModeloVentas::mdlActualizarPagos(  $listaCobros  ,$fecha   , $vuelto ,$id_venta,$id_sesion_caja );




	}

	/*=============================================
				CAMBIO COMPROBANTE
	=============================================*/
	 
	static public function ctrCambiarComprobante ( $comprobante , $id_venta , $idLocal ){


		$listaComprobante = array();
		$listaComprobante = json_decode(   $comprobante, true );	

		date_default_timezone_set('America/Bogota');
		$fecha = date('Y-m-d');
		$hora = date('H:i:s');
		$fecha = $fecha.' '.$hora;


		/*=============================================
						CAMBIO COMPROBANTE
		=============================================*/	

		$respuesta = ModeloVentas::mdlCambiarComprobante( $listaComprobante , $id_venta , $idLocal );




	}

	/*=============================================
				MOSTRAR PIE VENTAS
	=============================================*/
	
	static public function ctrPieVentas( $obj ){

		return ModeloVentas::mdlPieVentas( $obj->id_sesion_caja );

	}
	/*=============================================
				MOSTRAR PIE VENTAS
	=============================================*/
	
	static public function ctrPieVentasTipoCobro( $obj ){

		return ModeloVentas::mdlPieVentasTipoCobro( $obj->id_sesion_caja );

	}

	/*=============================================
				MOSTRAR PIE VENTAS
	=============================================*/
	
	static public function ctrPieVentasHistorico( $obj ){

		return ModeloVentas::mdlPieVentasHistorico( );

	}
	/*=============================================
				MOSTRAR PIE VENTAS
	=============================================*/
	
	static public function ctrPieVentasTipoCobroHistorico( $obj ){

		return ModeloVentas::mdlPieVentasTipoCobroHistorico( );

	}

	/*=============================================
				MOSTRAR PRODUCTOS VENTAS
	=============================================*/
	
	static public function ctrGetClienteVentaById( $id_venta ){

		return ModeloVentas::mdlGetClienteVentaById( $id_venta );

	}

	/*=============================================
				MOSTRAR PRODUCTOS VENTAS
	=============================================*/
	
	static public function ctrGetVentaById( $id_venta ){

		return ModeloVentas::mdlGetVentaById( $id_venta );

	}
	/*=============================================
				MOSTRAR PRODUCTOS VENTAS
	=============================================*/
	
	static public function ctrGetComprobanteVentaById( $id_venta ){

		return ModeloVentas::mdlGetComprobanteVentaById( $id_venta );

	}

	/*=============================================
				MOSTRAR PRODUCTOS VENTAS
	=============================================*/
	
	static public function ctrGetTotalVentaById( $id_venta ){

		return ModeloVentas::mdlGetTotalVentaById( $id_venta );

	}
	/*=============================================
				MOSTRAR PRODUCTOS VENTAS
	=============================================*/
	
	static public function ctrCabeceraVentaById( $id_venta ){

		return ModeloVentas::mdlCabeceraVentaById( $id_venta );

	}

	/*=============================================
				MOSTRAR PRODUCTOS POR VENTA
	=============================================*/
	
	static public function ctrMostrarVentaProductos( $obj ){

		return ModeloVentas::mdlMostrarVentaProductos( $obj );

	}

	/*=============================================
				MOSTRAR PRODUCTOS VENTAS
	=============================================*/
	
	static public function ctrEditarVentas( $id_venta, $venta )
	{
		$comprobante    = array();
		$venta_detalles = ModeloVentas::mdlMostrarVentasById( $id_venta );
		$ventaCliente   = ModeloVentas::mdlCabeceraVentaById( $id_venta );
		$comprobante    = json_decode( $venta->comprobante , true );
		$cambioCliente  = ( $ventaCliente["id_documento"] == $comprobante[0]["identificador"]) ? 0 : 1;
		$listaProductos = array();

		$listaProductos = json_decode( $venta->listaProductos , true );

		foreach($listaProductos as $key => $producto)
		{
			$producto_vd = ModeloVentas::mdlVentaProductoDetalleById( $id_venta , $producto["id"] );
			$listaProductos[$key]['cambio'] = $producto["cantidad"] == $producto_vd["cantidad_producto"] ? 0 : 1; 
			$listaProductos[$key]['cant_ant'] = $producto["cantidad"]; 
			$listaProductos[$key]['precio_ant'] = $producto["precio"];
		}
		/*
	
	
		$productos     = json_decode($venta->listaProductos , true);

		// Se agrupan los codigos internos de las ventas de la bd 
		
		$cod_internos  = [];
		$cod_productos = [];
	foreach ($ventas as $venta)
		{
			if( !in_array($venta["comprobante_codigo_interno_producto"],$cod_internos))
			{
				array_push($cod_internos,$venta["comprobante_codigo_interno_producto"]);
			}
		}
		// Se agrupan los codigos internos de las ventas de los formularios 
		foreach ($productos as $producto)
		{
			if( !in_array($producto["codigo_producto_interno"],$cod_productos))
			{
				array_push($cod_productos,$producto["codigo_producto_interno"]);
			}
		}

		$cant_cod_int    = count($cod_internos);
		$cant_prod_venta = count($cod_productos);

		if($cant_cod_int == $cant_prod_venta)
		{
			// Si son iguales se actualiza el registro 
			$cambioProducto = false;
		}
		else if($cant_cod_int > $cant_prod_venta)
		{
			// Si disminuye se borra el registro 
			$cambioProducto = true;
		}
		else if($cant_cod_int < $cant_prod_venta)
		{
			// Si aumenta se inserta el registro 
			$cambioProducto = true;
		}*/

		$eliminado = ModeloVentas::mdlEliminarVentaById( $id_venta, $ventaCliente );
		// Se hace un soft delete y se vuelven a insertar los productos 
		if($eliminado == "ok" )
		{
			date_default_timezone_set('America/Bogota');
			//$fecha = date('Y-m-d', strtotime( $venta->fecha_venta));
			$fecha = explode( "/", $venta->fecha_venta );
			$fecha_tmp = $fecha[2]."-".$fecha[1]."-".$fecha[0];


			//$hora  = date('H:i:s');
			$fecha = $fecha_tmp.' 00:00:00';

			/*=============================================
			GUARDAR LA COMPRA
			=============================================*/	

			$listaCobros    = array();

			if(  $venta->descuento !== '' )
				$venta->descuento 	=  $venta->descuento ;
			else 
				$venta->descuento 	= 0 ;
				
			$id_sesion_caja  = $venta->id_sesion_caja;
			$listaCobros     = json_decode( $venta->listaCobros , true );
			$comprobante     = json_decode( $venta->comprobante , true );
			$descuento       = $venta->descuento;
			$descuentoMotivo = $venta->descuentoMotivo;
			$vuelto          = $venta->vuelto;
			$id_vendedor     = $venta->id_vendedor;
			$nombre_vendedor = $venta->nombre_vendedor;
			$id_local        = $venta->id_local;
			return ModeloVentas::mdlEditarVentaById( $id_venta, $listaProductos, $listaCobros , $descuento , $descuentoMotivo,$fecha ,$id_sesion_caja  , $vuelto , $id_vendedor, $nombre_vendedor, $id_local , $comprobante, $cambioCliente, $ventaCliente, $venta->comentario_venta);

		}


	}


	/*=============================================
				MOSTRAR PRODUCTOS VENTAS
	=============================================*/
	
	static public function ctrCambiarCliente( $venta )
	{
		$comprobante    = array();
		$comprobante = ModeloVentas::mdlCabeceraVentaById( $venta->id_venta );
		return ModeloVentas::mdlCambiarCliente( $venta, $comprobante );

	}

	/*=============================================
					MOSTRAR VENTAS
	=============================================*/
	static public function ctrGetVentas( $obj ){

		return ModeloVentas::mdlGetVentas( $obj );

	}

	/*=============================================
					MOSTRAR VENTAS
	=============================================*/
	static public function ctrGetVentaDetalles( $obj ){

		return ModeloVentas::mdlGetVentaDetalles( $obj );

	}

	/*=============================================
					MOSTRAR VENTAS
	=============================================*/
	static public function ctrGetComprobante( $obj ){	

		return ModeloVentas::mdlGetComprobante( $obj );

	}

	/*=============================================
					MOSTRAR VENTAS
	=============================================*/
	static public function ctrGetTipoCobro( $obj ){	

		return ModeloVentas::mdlGetTipoCobro( $obj );

	}
	/*=============================================
				PAGAR COTIZACION VENTA
	=============================================*/
	static public function ctrPagarCotizacion( $id_sesion_caja, $listaProductos_par , $listaCobros_par, $descuento , $descuentoMotivo , $vuelto , $id_vendedor, $nombre_vendedor,$id_local , $comprobante_par, $id_cotizacion, $comentario_venta){


		$listaProductos = array();
		$listaCobros = array();

		$listaProductos = json_decode(  $listaProductos_par , true);		
		$listaCobros = json_decode(   $listaCobros_par, true );		
		$comprobante= json_decode(   $comprobante_par, true );		



		if(  $descuento !== '' )
			$descuento 	=  $descuento ;
		else 
			$descuento 	= 0 ;


		date_default_timezone_set('America/Bogota');
		$fecha = date('Y-m-d');
		$hora = date('H:i:s');
		$fecha = $fecha.' '.$hora;


		/*=============================================
		GUARDAR LA VENTA COTIZACION
		=============================================*/	

		$respuesta = ModeloVentas::mdlPagarCotizacion( $listaProductos, $listaCobros , $descuento , $descuentoMotivo,$fecha ,$id_sesion_caja  , $vuelto , $id_vendedor, $nombre_vendedor, $id_local , $comprobante, $id_cotizacion, $comentario_venta);

		return $respuesta;



	}

}
	





 