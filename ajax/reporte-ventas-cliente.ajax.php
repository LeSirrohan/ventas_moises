<?php

require_once "../controladores/clientes.controlador.php";
require_once "../modelos/clientes.modelo.php";

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

date_default_timezone_set('America/Bogota');

class AjaxListadoCliente {
    public $id_documento;
    public $fecha_inicio;
    public $fecha_fin;
    public function ListadoClientes(){
        $cliente = new ControladorClientes();
        $clientes = $cliente->ctrReporteVentasClientesxMes(  );
        //print_r($clientes);exit;
		if(count($clientes)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

		 }

		$datosJson = '[';

 			for($i = 0; $i < count($clientes); $i++){
 				$datosJson .='[
					"'.$clientes[$i]["NOMBRE COMERCIAL"].'",
					"'.$clientes[$i]["MES12"].'",
					"'.$clientes[$i]["MES11"].'",
					"'.$clientes[$i]["MES10"].'",
					"'.$clientes[$i]["MES9"].'",
					"'.$clientes[$i]["MES8"].'",
					"'.$clientes[$i]["MES7"].'",
					"'.$clientes[$i]["MES6"].'",
					"'.$clientes[$i]["MES5"].'",
					"'.$clientes[$i]["MES4"].'",
					"'.$clientes[$i]["MES3"].'",
					"'.$clientes[$i]["MES2"].'",
					"'.$clientes[$i]["MES1"].'",
					"'.$clientes[$i]["MESPRESENTE"].'"
			    ],';



 			}

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   ']';


        echo  $datosJson;
        return;
    }
    
    public function ListadoClienteVentas(){
        $ctrVentas = new ControladorVentas();
        $ventas = $ctrVentas->ctrReporteClienteVentas( $this );
        //print_r($ventas);exit;
		if(count($ventas)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

		 }

		$datosJson = '[';

 			for($i = 0; $i < count($ventas); $i++){

				$precio_venta_producto = number_format($ventas[$i]['precio_venta_producto'],2,".",",");
				$precio_venta_producto_original = number_format($ventas[$i]['precio_venta_original'],2,".",",");
				$subtotal = $precio_venta_producto * $ventas[$i]["cantidad_producto"] ;
				$subtotal = number_format($subtotal,2,".",",");
                $comentario = "";
                
				
                if($ventas[$i]["anulado"]!=1 )
                    $estado = "<span class='right badge badge-success' >PROCESADO</span>";
                else 
                    $estado = "<span class='right badge badge-danger' >ANULADO</span>";
                $fecha = $ventas[$i]["fecha_venta2"];
                $fecha_inicio_caja = $ventas[$i]["fecha_inicio_caja2"];
                $fecha_cierre_caja = $ventas[$i]["fecha_cierre_caja2"];
                $vendedor = "". $ventas[$i]["nombre_vendedor"] . "<br> Fecha Apertura: " .$fecha_inicio_caja. "<br> Fecha Cierre:".$fecha_cierre_caja;
 				$datosJson .='[
			      "'.$fecha.'",
			      "'.$ventas[$i]["id_ventas"].'",
			      "'.$vendedor.'",
			      "'.$ventas[$i]["nombre_producto"].'",
			      "'.$ventas[$i]["comprobante_unidad_medida"].'",
			      "'.$precio_venta_producto_original.'",
			      "'.$precio_venta_producto.'",
			      "'.$ventas[$i]['cantidad_producto'].'",
			      "'.$subtotal.'",
			      "'.$estado.'"
			    ],';
 			}

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   ']';


        echo  $datosJson;
        return;
	}
	static public function TitulosMesesReporte(){
		
        date_default_timezone_set('America/Bogota');
		$fecha_actual = new Datetime(); 

		$fecha_modificada = $fecha_actual->modify("-13 months");
		//$fecha_modificada_temp = $fecha_modificada->modify("last day of self month");

		$mes_actual       = $fecha_modificada->format("Y-m-d");

		//echo $mes_actual;echo "\n";
		$doce_meses = [];
		$mes=0;
		while($mes < 13)
		{
			$fecha_modificada = $fecha_actual->modify("last day of next month");
			$mes_actual       = $fecha_modificada->format("m");
			$mes_letras       = $fecha_modificada->format("M");
			$year             = $fecha_modificada->format("Y");

			$array = array(
				"mes" => $mes,
				"titulo" => $year."-".$mes_letras
			);
			array_push( $doce_meses, $array );
			$mes++;     
		}
		echo json_encode($doce_meses);
	}
}
if(isset($_POST["mostrarListadoClienteVentas"]) AND $_POST["mostrarListadoClienteVentas"] == "yes"){
    $clientes = new AjaxListadoCliente();
    $clientes->ListadoClientes();
}

if(isset($_POST["TitulosMesesReporte"]) AND $_POST["TitulosMesesReporte"] == "yes"){
    $cliente = new AjaxListadoCliente();
    $cliente -> TitulosMesesReporte();
}
?>
