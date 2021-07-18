<?php

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";


require_once "../controladores/tipo-pago.controlador.php";
require_once "../modelos/tipo-pago.modelo.php";


class AjaxListadoVentas {

/*=============================================
			MOSTRAR VENTAS DETALLE
=============================================*/
	public $dataInicio;
	public $dataFin;
	public $id_sesion_caja;
	public $listaCobros;
	public $vuelto;
	public $id_venta;

	public function mostrarListadoVentasDetalles(){

		$id_sesion_caja = $this->id_sesion_caja;

		$ventas = ControladorVentas::ctrListadoVentasDetalle($this);

		if(count($ventas)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

		 }
		 //print_r($ventas);exit;

		$datosJson = '[';

 			for($i = 0; $i < count($ventas); $i++){
				$precio_venta_producto = number_format($ventas[$i]['precio'],2,".",",");
				$precio_venta_producto_original = number_format($ventas[$i]['valorunitario'],2,".",",");
				$subtotal = $precio_venta_producto * $ventas[$i]["cantidad"] ;
				$subtotal = number_format($subtotal,2,".",",");
				$comentario = "";
 				$datosJson .='[
				"'.$ventas[$i]["codventa"].'",
				"'.$ventas[$i]["cantidad"].'",
			      "'.$ventas[$i]["unidad_medida"].'",
			      "'.$ventas[$i]["nombre_producto"].'",
			      "'.$precio_venta_producto.'",
			      "'.$precio_venta_producto_original.'",
			      "'.$comentario.'",
			      "'.$subtotal.'"
			    ],';



 			}

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   ']';


		echo  $datosJson;


	}

	public function mostrarListadoVentasTabla(){

		$dataInicio = $this->dataInicio;
		$dataFin = $this->dataFin;
		$id_sesion_caja = $this->id_sesion_caja;

		$ventas = ControladorVentas::ctrListadoVentasTabla($dataInicio, $dataFin, $id_sesion_caja);

	if(count($ventas)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

 		}

		$datosJson = '[';

 			for($i = 0; $i < count($ventas); $i++){

 				$botones = "<div class='btn-group'>".

                      "<button class='btn btn-warning btnVerDetalle' idVenta='".$ventas[$i]['codventa']."'><i class='fas fa-eye'></i></button>".

                      //"<button class='btn btn-danger btnVerComprobante' idVenta='".$ventas[$i]['codventa']."'><i class='fas fa-print'></i></button>".

					"</div>";
				$boton_descuento = "";
				
				$total = number_format($ventas[$i]['total'],2,".",",");

 				$datosJson .='[
				"'.$ventas[$i]["codventa"].'",
				"'.$ventas[$i]["fecemision"].'",
			      "'.$ventas[$i]["tip_doc"].'",
			      "'.$ventas[$i]["id_cliente"].'",
			      "'.$ventas[$i]["cliente"].'",
			      "'.$ventas[$i]["comprobante"].'",
				  "'.trim(preg_replace('/\s+/', ' ',$ventas[$i]["comentario"])) .'",
			      "'.$total.'",
			      "'.$botones.'"
			    ],';



 			}

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   ']';


		echo  $datosJson;


	}



/*=============================================
			MOSTRAR FORMA DE COBRO VENTAS
=============================================*/

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






}

/*=============================================
			MOSTRAR TABLA VENTAS
=============================================*/

if(isset($_POST["mostrarListadoVentas"])){
	$producto = new AjaxListadoVentas();
	$producto -> dataInicio = $_POST["dataInicio"];
	$producto -> dataFin = $_POST["dataFin"];
	$producto -> mostrarListadoVentasTabla();
}


/*=============================================
		MOSTRAR VENTAS GRAFICO X DIA
=============================================*/

if(isset($_POST["mostrarVentasGraficoxDia"])){
	$producto = new AjaxListadoVentas();
	$producto -> dataInicio = $_POST["dataInicio"];
	$producto -> dataFin = $_POST["dataFin"];
	$producto -> mostrarVentasGraficoxDia();
}


/*=============================================
		MOSTRAR VENTAS GRAFICO X DIA
=============================================*/

if(isset($_POST["mostrarFormasCobroVentas"])){
	$producto = new AjaxListadoVentas();
	$producto -> id_venta = $_POST["id_venta"];
	$producto -> mostrarFormasCobroVentas();
}


/*=============================================
		ACTUALIZAR MÉTODO DE PAGO DE UNA VENTA
=============================================*/

if(isset($_POST["actualizacionFormaPagoVenta"])){
	$venta = new AjaxListadoVentas();
	$venta -> id_sesion_caja = $_POST["id_sesion_caja"];
	$venta -> id_venta = $_POST["id_venta"];
	$venta -> listaCobros = $_POST["listaCobros"];
	$venta -> vuelto = $_POST["vuelto"];
	$venta -> actualizarVenta();
}

/*=============================================
			MOSTRAR DETALLE VENTAS
=============================================*/

if(isset($_POST["mostrarListadoVentasDetalles"]))
{
	$venta = new AjaxListadoVentas();
	$venta -> id_venta   = $_POST["idVenta"];
	$venta -> mostrarListadoVentasDetalles();
}

 
 


 
 