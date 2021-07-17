<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";


require_once "../controladores/tipo-pago.controlador.php";
require_once "../modelos/tipo-pago.modelo.php";

/*
require_once "../controladores/local.controlador.php";
require_once "../modelos/local.modelo.php";*/




class AjaxVentas {

/*=============================================
			MOSTRAR VENTAS
=============================================*/
public $dataInicio;
public $dataFin;
public $MotivoAnulacion;
public $ClaveConfirma;
public $id_cliente;
public $nombre_cliente;
public $email;
public $direccion;
public $id_comprobante;

	public function mostrarVentasTabla(){

		//$dataInicio = $this->dataInicio;
		//$dataFin = $this->dataFin;
		$id_sesion_caja = $this->id_sesion_caja;

		//$ventas = ControladorVentas::ctrMostrarVentasTabla($dataInicio, $dataFin, $id_sesion_caja);
		$ventas = ControladorVentas::ctrMostrarVentasTabla($id_sesion_caja);

	if(count($ventas)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

 		}

		$datosJson = '[';

 			for($i = 0; $i < count($ventas); $i++){
				
				if($ventas[$i]['estado'] != "ANULADO"){
					$btnEstado = " data-toggle='modal' data-target='#modalCambiarComprobante' ";
					$estado    = "<span class='right badge badge-success'>".$ventas[$i]["estado"]."</span>";
					$botones   = "<div class='btn-group'>".

							"<button class='btn btn-info btnImprimir' id_venta='".$ventas[$i]['id']."' id_comprobante='".$ventas[$i]['id_comprobante']."' title='Imprimir'> <i class='fas fa-print'></i></button>".
	
							//"<button class='btn btn-primary btnCambiarComprobante' $btnEstado estado ='".$ventas[$i]['estado']."' codigoVenta='".$ventas[$i]['id']."' id_documento_cliente='".$ventas[$i]['id_documento_cliente']."' cliente='".$ventas[$i]['cliente']."' title='Cambiar Comprobante'> <i class='fas fa-receipt'></i></button>".
							
							"<button type='buttom' class='btn bg-navy modificarModoPago'  codigoVenta='".$ventas[$i]['id']."' data-toggle='modal' data-target='#modalVerPago'  title='Ver pago'> <i class='far fa-money-bill-alt'></i></button>".

							"<button type='buttom' class='btn bg-indigo verProductos'  codigoVenta='".$ventas[$i]['id']."' data-toggle='modal' data-target='#modalVerProductosVentas'  title='Ver Productos'> <i class='fas fa-barcode'></i></button>".

							//"<button class='btn btn-success btnCambioCliente'  codigoVenta='".$ventas[$i]['id']."' id_documento_cliente='".$ventas[$i]['id_documento_cliente']."' cliente='".$ventas[$i]['cliente']."' direccion='".$ventas[$i]['direccion']."' data-toggle='modal' data-target='#modalEditarCliente'  title='Ver pago'> <i class='fa fa-user-alt'></i></button>".
	
							"<button class='btn btn-warning btnEditarVenta' idVenta='".$ventas[$i]['id']."'><i class='fas fa-edit'></i></button>".
	
							"<button class='btn btn-danger btnEliminarVenta' idVenta='".$ventas[$i]['id']."'><i class='fas fa-ban' alt='Anular'></i></button>".
	
						"</div>";
				}
				else{
					$btnEstado = "";
					$estado    = "<span class='right badge badge-danger verComprobanteAnulado'  data-toggle='modal' data-target='#modalVentaAnulada' fechaAnulada='".$ventas[$i]["anulado_fecha"]."' motivoAnulada='".trim(preg_replace('/\s+/', ' ', $ventas[$i]["anulado_motivo"]))."' style='cursor:pointer;'>".$ventas[$i]["estado"]."</span>";
					
					$botones = "<div class='btn-group'>".

						//"<button class='btn btn-info btnCambiarComprobante' $btnEstado estado ='".$ventas[$i]['estado']."' codigoVenta='".$ventas[$i]['id']."' id_documento_cliente='".$ventas[$i]['id_documento_cliente']."' cliente='".$ventas[$i]['cliente']."' title='Cambiar Comprobante'> <i class='fas fa-receipt'></i></button>".

						"<button type='buttom' class='btn btn-primary modificarModoPago'  codigoVenta='".$ventas[$i]['id']."' data-toggle='modal' data-target='#modalVerPago'  title='Ver pago'> <i class='far fa-money-bill-alt'></i></button>".

						"<button type='buttom' class='btn bg-indigo verProductos'  codigoVenta='".$ventas[$i]['id']."' data-toggle='modal' data-target='#modalVerProductosVentas'  title='Ver Productos'> <i class='fas fa-barcode'></i></button>".

						"<button class='btn btn-danger btnEliminarVenta' idVenta='".$ventas[$i]['id']."'><i class='fas fa-ban' alt='Anular'></i></button>".

					"</div>";
				}
 				/*$botones = "<div class='btn-group'>".

				 		"<button class='btn btn-info btnCambiarComprobante' $estado estado =".$ventas[$i]['estado']." codigoVenta=".$ventas[$i]['id']." id_documento_cliente='".$ventas[$i]['id_documento_cliente']."' cliente='".$ventas[$i]['cliente']."' title='Cambiar Comprobante'> <i class='fas fa-receipt'></i></button>".
                      	"<button class='btn btn-info btnVentasImprimirFacturaPDF' codigoVenta=".$ventas[$i]['id']." title='Ver venta'> <i class='fas fa-file-invoice-dollar'></i></button>".
					 	"<button type='buttom' class='btn btn-info modificarModoPago'  codigoVenta='".$ventas[$i]['id']."' data-toggle='modal' data-target='#modalVerPago'  title='Ver pago'> <i class='far fa-money-bill-alt'></i></button>".
						"<button type='buttom' class='btn btn-info ' data-toggle='modal' data-target='#modalAddPago'  title='Agregar pago'> <i class='fas fa-briefcase'></i></button>".

                      "<button class='btn btn-warning btnEditarVenta' idVenta='".$ventas[$i]['id']."'><i class='fas fa-edit'></i></button>".

                      "<button class='btn btn-danger btnEliminarVenta' idVenta='".$ventas[$i]['id']."'><i class='fas fa-ban' alt='Anular'></i></button>".

					"</div>";*/
					
				$boton_descuento = "";
				if($ventas[$i]['descuento'] >0){
					$descuento = number_format($ventas[$i]['descuento'],2,".",",");
					$boton_descuento = "<div class='text-center'><button class='btn btn-success btnVerDescuento' idVenta='".$ventas[$i]['id']."' descuento='".$ventas[$i]['descuento']."' motivo='".$ventas[$i]['descuento_motivo']."' data-toggle='modal' data-target='#modalVerDescuento'  title='Ver Descuentos'>".$descuento."&nbsp;<i class='fas fa-money-bill-wave-alt text-white'></i></button></div>";
				}
				
				//$total = number_format(($ventas[$i]['total'] - $ventas[$i]['descuento']),2,".",",");
				$total = number_format(($ventas[$i]['total'] ),2,".",",");

 				$datosJson .='[
			      "'.$ventas[$i]['id'].'",
			      "'.$ventas[$i]["fecha_venta"].'",
			      "'.$ventas[$i]["id_cliente"].'",
			      "'.$ventas[$i]["cliente"].'",
			      "'.$ventas[$i]["comprobante"].'",
			      "'.$boton_descuento.'",
				  "'.trim(preg_replace('/\s+/', ' ',$ventas[$i]["comentario"])) .'",
			      "'.$total.'",
			      "'.$estado.'",
			      "'.$botones.'"
			    ],';



 			}

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   ']';


		echo  $datosJson;


	}

	public function listadoVentaProductosTabla(){

		$ventas = ControladorVentas::ctrMostrarVentaProductos( $this );

		if(count($ventas)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

 		}
		 
		$datosJson = '[';

 			for($i = 0; $i < count($ventas); $i++){
				//print_r( $ventas );echo '\n';
				
				$subtotal = number_format(($ventas[$i]['subtotal']),2,".",",");
				$descripcion = $ventas[$i]["comentario_producto"] != "" ? $ventas[$i]["nombre_producto"].' ('. $ventas[$i]["comentario_producto"].')' : $ventas[$i]["nombre_producto"] ;

 				$datosJson .='[
			      "'.$ventas[$i]['id_producto'].'",
			      "'.$descripcion.'",
			      "'.$ventas[$i]["unidad_medida_sunat"].'",
			      "'.$ventas[$i]["precio_venta_producto"].'",
			      "'.$ventas[$i]["precio_venta_original"].'",
			      "'.$ventas[$i]["cantidad_producto"].'",
			      "'.$subtotal.'"
			    ],';
 			}

		$datosJson = substr($datosJson, 0, -1);

		$datosJson .=   ']';

		echo  $datosJson;

	}


/*=============================================
			MOSTRAR FORMA DE COBRO VENTAS
=============================================*/
	public $id_venta;

	public function mostrarFormasCobroVentas(){

		$id_venta = $this->id_venta;

		$formas_cobro = ControladorVentas::ctrMostrarFormasDeCobro( $id_venta );

		$tipo_cobro = ControladorTipoCobro::ctrMostrarTipoCobro();

		$conjunto_data = array(  $formas_cobro  , $tipo_cobro );


		echo json_encode($conjunto_data) ;


	}


	public function mostrarVentasGraficoxDia(){

		$dataInicio = $this->dataInicio;
		$dataFin = $this->dataFin;

		$ventas = ControladorVentas::ctrMostrarVentasGraficoVentasxDia($dataInicio, $dataFin);
		echo  $ventas;


	}


 

	public $id_sesion_caja;
	// public $id_venta;
	public $listaCobros;
	public $vuelto;
	
 	
	
	public function actualizarVenta(){

	//	$file ="./jose2.txt";
   	//	file_put_contents($file,  "entre1");


 

		$id_sesion_caja = $this->id_sesion_caja;
		$listaCobros = $this->listaCobros;
		$id_venta = $this->id_venta;
		$vuelto = $this->vuelto;
		
		  
		$respuesta = ControladorVentas::ctrActualizarPagos($id_sesion_caja,  $listaCobros,  $vuelto ,  $id_venta);
		echo json_encode($respuesta);

	}




 	
	
	public function AnularVenta(){
			
			  
			return ControladorVentas::ctrAnularVenta($this);
	
		}
	public function PieVentas(){

		return ControladorVentas::ctrPieVentas($this);

	}
	public function PieVentasTipoCobro(){

		return ControladorVentas::ctrPieVentasTipoCobro($this);

	}

	public function PieVentasHistorico(){

		return ControladorVentas::ctrPieVentasHistorico($this);

	}
	public function PieVentasTipoCobroHistorico(){

		return ControladorVentas::ctrPieVentasTipoCobroHistorico($this);

	}

}

/*=============================================
			MOSTRAR TABLA VENTAS
=============================================*/

if(isset($_POST["mostrarVentas"])){
	$producto = new AjaxVentas();
	//$producto -> dataInicio = $_POST["dataInicio"]; esto no se usa en el listado de ventas
	//$producto -> dataFin = $_POST["dataFin"];esto no se usa en el listado de ventas
	$producto -> id_sesion_caja = $_POST["sesion_caja"];
	$producto -> mostrarVentasTabla();
}


/*=============================================
			MOSTRAR TABLA VENTAS
=============================================*/

if(isset($_POST["listadoVentaProductos"])){
	$producto = new AjaxVentas();
	$producto -> id_venta = $_POST["id_venta"];
	$producto -> listadoVentaProductosTabla();
}


/*=============================================
		MOSTRAR VENTAS GRAFICO X DIA
=============================================*/

if(isset($_POST["mostrarVentasGraficoxDia"])){
	$producto = new AjaxVentas();
	$producto -> dataInicio = $_POST["dataInicio"];
	$producto -> dataFin = $_POST["dataFin"];
	$producto -> mostrarVentasGraficoxDia();
}


/*=============================================
		MOSTRAR VENTAS GRAFICO X DIA
=============================================*/

if(isset($_POST["mostrarFormasCobroVentas"])){
	$producto = new AjaxVentas();
	$producto -> id_venta = $_POST["id_venta"];
	$producto -> mostrarFormasCobroVentas();
}


/*=============================================
		ACTUALIZAR MÉTODO DE PAGO DE UNA VENTA
=============================================*/

if(isset($_POST["actualizacionFormaPagoVenta"])){
	$venta = new AjaxVentas();
	$venta -> id_sesion_caja = $_POST["id_sesion_caja"];
	$venta -> id_venta = $_POST["id_venta"];
	$venta -> listaCobros = $_POST["listaCobros"];
	$venta -> vuelto = $_POST["vuelto"];
	$venta -> actualizarVenta();
}


/*=============================================
		ACTUALIZAR MÉTODO DE PAGO DE UNA VENTA
=============================================*/

if(isset($_POST["confirmarAnulacion"]) AND ($_POST["confirmarAnulacion"] == "yes")){
	$venta = new AjaxVentas();
	$venta -> id_sesion_caja = isset( $_POST["id_sesion_caja"] ) ? $_POST["id_sesion_caja"] : "";
	$venta -> id_venta = isset( $_POST["idVenta"] ) ? $_POST["idVenta"] : "";
	$venta -> MotivoAnulacion = isset( $_POST["MotivoAnulacion"] ) ? $_POST["MotivoAnulacion"] : "";
	$venta -> ClaveConfirma = isset( $_POST["ClaveConfirma"] ) ? $_POST["ClaveConfirma"] : "";
	$respuesta = $venta -> AnularVenta();
	if( $respuesta == "ok" ) {
		$return["data"] = 1;
		$return["mensaje"] = "Se ha anulado la venta correctamente";

	} else {
		$return["data"] = 0;
		$return["mensaje"] = $respuesta;

	}
	echo json_encode($return);
}


/*=============================================
			CONFIRMAR EDITAR VENTA
=============================================*/

if(isset($_POST["confirmarEdicion"]) AND ($_POST["confirmarEdicion"] == "yes")){
	$venta = new AjaxVentas();
	$venta -> ClaveConfirma = isset( $_POST["ClaveConfirma"] ) ? $_POST["ClaveConfirma"] : "";
	$respuesta = ControladorVentas::ctrClaveConfimacion( $venta );
	if( $respuesta )
	{
		$return["data"] = 1;
	}
	else 
	{
		$return["data"] = 0;
		$return["mensaje"] = "Clave Incorrecta";
	}
	echo json_encode($return);
}

/*=============================================
		ACTUALIZAR MÉTODO DE PAGO DE UNA VENTA
=============================================*/

if(isset($_POST["cambioComprobante"]) AND ($_POST["cambioComprobante"] == "yes")){
	$venta = new ControladorVentas();
	
	$listaComprobante = isset($_POST['listaCambioComprobante']) ? $_POST['listaCambioComprobante'] : '' ;
	$codigoVenta = isset($_POST['codigoVenta']) ? $_POST['codigoVenta'] : '' ;
	$idLocal = isset($_POST['idLocal']) ? $_POST['idLocal'] : '' ;

	$respuesta = $venta -> ctrCambiarComprobante( $listaComprobante , $codigoVenta , $idLocal );

	if( $respuesta == "ok" ) {

		$return["data"] = 1;
		$return["mensaje"] = "Se ha anulado la venta correctamente";

	} else {

		$return["data"] = 0;
		$return["mensaje"] = $respuesta;

	}
	echo json_encode($return);
}


/*=============================================
		ACTUALIZAR MÉTODO DE PAGO DE UNA VENTA
=============================================*/

if(isset($_POST["accion"]) AND ($_POST["accion"] == "PieVentas")){
	$venta = new AjaxVentas();
	$venta -> id_sesion_caja = isset( $_POST["id_sesion_caja"] ) ? $_POST["id_sesion_caja"] : "";
	$PieVentas = $venta -> PieVentas();
	echo json_encode($PieVentas);
	return;
}
/*=============================================
		ACTUALIZAR MÉTODO DE PAGO DE UNA VENTA
=============================================*/

if(isset($_POST["accion"]) AND ($_POST["accion"] == "PieVentasTipoCobro")){
	$venta = new AjaxVentas();
	$venta -> id_sesion_caja = isset( $_POST["id_sesion_caja"] ) ? $_POST["id_sesion_caja"] : "";
	$PieVentasTipoCobro = $venta -> PieVentasTipoCobro();
	echo json_encode($PieVentasTipoCobro);
	return;
}
/*=============================================
		ACTUALIZAR MÉTODO DE PAGO DE UNA VENTA
=============================================*/

if(isset($_POST["accion"]) AND ($_POST["accion"] == "PieVentasHistorico")){
	$venta = new AjaxVentas();
	$venta -> id_sesion_caja = isset( $_POST["id_sesion_caja"] ) ? $_POST["id_sesion_caja"] : "";
	$PieVentas = $venta -> PieVentasHistorico();
	echo json_encode($PieVentas);
	return;
}
/*=============================================
		ACTUALIZAR MÉTODO DE PAGO DE UNA VENTA
=============================================*/

if(isset($_POST["accion"]) AND ($_POST["accion"] == "PieVentasTipoCobroHistorico")){
	$venta = new AjaxVentas();
	$venta -> id_sesion_caja = isset( $_POST["id_sesion_caja"] ) ? $_POST["id_sesion_caja"] : "";
	$PieVentasTipoCobro = $venta -> PieVentasTipoCobroHistorico();
	echo json_encode($PieVentasTipoCobro);
	return;
}
/*
=============================================
				CAMBIAR CLIENTE
=============================================
*/
if(isset($_POST["cambioCliente"]) AND ($_POST["cambioCliente"] == "yes")){
	$venta    = new AjaxVentas();
	$ctrVenta = new ControladorVentas();

	$venta->id_cliente     = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : '' ;
	$venta->id_venta       = isset($_POST['codigoventa']) ? $_POST['codigoventa'] : '' ;
	$venta->nombre_cliente = isset($_POST['nombre_cliente']) ? $_POST['nombre_cliente'] : '' ;

	$respuesta = $ctrVenta -> ctrCambiarCliente( $venta );

	if( $respuesta == "ok" ) {

		$return["data"] = 1;
		$return["mensaje"] = "Se ha cambiado el cliente correctamente.";

	} else {

		$return["data"] = 0;
		$return["mensaje"] = $respuesta;

	}
	echo json_encode($return);
	return;
}
/*=============================================
			GET VENTAS Y COMPROBANTES
=============================================*/

if(isset($_POST["getComprobante"]) AND ($_POST["getComprobante"] == "yes")){
	$venta = new AjaxVentas();
			
	$venta -> id_venta       = isset( $_POST["id_venta"] ) ? $_POST["id_venta"] : "";
	$venta -> id_comprobante = isset( $_POST["id_comprobante"] ) ? $_POST["id_comprobante"] : "";

	$return["venta"]       = ControladorVentas::ctrGetVentas( $venta );
	$return["detalle"]     = ControladorVentas::ctrGetVentaDetalles( $venta );
	$return["comprobante"] = ControladorVentas::ctrGetComprobante( $venta );
	$return["tipo_cobro"]  = ControladorVentas::ctrGetTipoCobro( $venta );

	echo json_encode($return);
	return;
}