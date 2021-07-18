<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

require_once "../controladores/ventas.controlador.php";
require_once "../modelos/ventas.modelo.php";

date_default_timezone_set('America/Bogota');

class AjaxReporProd {
    public $id_producto;
    public $fecha_inicio;
    public $fecha_fin;
    public function ListadoProductos(){
        $producto = new ControladorProductos();
        $productos = $producto->ctrReporteProductos(  );
        //print_r($productos);exit;
		if(count($productos)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

		 }

		$datosJson = '[';

 			for($i = 0; $i < count($productos); $i++){
                $comentario = "";
                
			    $acciones = "<div class='btn-group text-center'><button class='btn bg-lightblue btn-sm btnRptProducto' id_producto='".$productos[$i]["codproducto"]."'  nombre_producto='".$productos[$i]["nomproducto"]."'><i class='fas fa-plus-circle'></i></button></div>";
/*
                if($productos[$i]["estado"]==1 )
                    $estado = "<span class='right badge badge-success' >ACTIVO</span>";
                else 
                    $estado = "<span class='right badge badge-danger' >INACTIVO</span>";*/
 				$datosJson .='[
			      "'.$productos[$i]["codproducto"].'",
			      "'.$productos[$i]["nomproducto"].'",
			      "'.$productos[$i]["precio"].'",
			      "'.$productos[$i]["lote"].'",
			      "'.$productos[$i]["codtipoafectacion"].'",
			      "'.$productos[$i]["codunidad"].'",
			      "'.$acciones.'"
			    ],';



 			}

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   ']';


        echo  $datosJson;
        return;
    }
    
    public function ListadoProductoVentas(){

        $ctrVentas = new ControladorVentas();
        $ventas = $ctrVentas->ctrReporteProductoVentas( $this );
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
			      "'.$ventas[$i]["id_documento"].'",
			      "'.$ventas[$i]["nombre"].'",
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
if(isset($_POST["mostrarListadoProductos"]) AND $_POST["mostrarListadoProductos"] == "yes"){
    $productos = new AjaxReporProd();
    $productos->ListadoProductos();
}

if(isset($_POST["mostrarListadoProductoVentas"]) AND $_POST["mostrarListadoProductoVentas"] == "yes"){
    $producto = new AjaxReporProd();
    $producto-> id_producto =  isset($_POST["id_producto"])?$_POST["id_producto"]:'';
    $producto-> fecha_inicio =  isset($_POST["dataInicio"])?$_POST["dataInicio"]:'';
    $producto-> fecha_fin =  isset($_POST["dataFin"])?$_POST["dataFin"]:'';
    $producto->ListadoProductoVentas();
}
?>
