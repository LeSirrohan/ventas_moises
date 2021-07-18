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
        $clientes = $cliente->ctrReporteClientes(  );
        //print_r($clientes);exit;
		if(count($clientes)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

		 }

		$datosJson = '[';

 			for($i = 0; $i < count($clientes); $i++){
                $comentario = "";
                
			    $acciones = "<div class='btn-group text-center'><button class='btn bg-lightblue btn-sm btnRptClienteVentas' id_documento='".$clientes[$i]["id_documento"]."'  nombre_cliente='".$clientes[$i]["nombre"]."'><i class='fas fa-money-bill-alt'></i></button></div>";

                if($clientes[$i]["estado"] ==1 )
                    $estado = "<span class='right badge badge-success' >ACTIVO</span>";
                else 
                    $estado = "<span class='right badge badge-danger' >ELIMINADO</span>";
 				$datosJson .='[
			      "'.$clientes[$i]["nombre"].'",
			      "'.$clientes[$i]["nombre_comercial"].'",
			      "'.$clientes[$i]["id_documento"].'",
			      "'.$clientes[$i]["telefono"].'",
			      "'.$clientes[$i]["email"].'",
			      "'.$clientes[$i]["direccion"].'",
			      "'.$clientes[$i]["fecha_ultima_compra"].'",
			      "'.$clientes[$i]["compras"].'",
			      "'.$estado.'",
			      "'.$acciones.'"
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
}
if(isset($_POST["mostrarListadoClientes"]) AND $_POST["mostrarListadoClientes"] == "yes"){
    $clientes = new AjaxListadoCliente();
    $clientes->ListadoClientes();
}

if(isset($_POST["mostrarListadoClienteVentas"]) AND $_POST["mostrarListadoClienteVentas"] == "yes"){
    $cliente = new AjaxListadoCliente();
    $cliente-> id_documento =  isset($_POST["id_documento"])?$_POST["id_documento"]:'';
    $cliente-> fecha_inicio =  isset($_POST["dataInicio"])?$_POST["dataInicio"]:'';
    $cliente-> fecha_fin =  isset($_POST["dataFin"])?$_POST["dataFin"]:'';
    $cliente->ListadoClienteVentas();
}
?>
