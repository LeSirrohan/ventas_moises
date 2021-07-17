<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";



class AjaxCrearVentas {

	/*=============================================
			MOSTRAR LA TABLA DE PRODUCTOS
	=============================================*/

	public function mostrarTablaProductos(){
		$item = null;
    	$valor = null;

  		$productos = ControladorProductos::ctrMostrarProductos($item, $valor);	
		


		if(count($productos)== 0){

 			$datosJson = '{"data": []}';

			echo $datosJson;

			return;

 		}




  		$datosJson = '{
		  "data": [';

		  for($i = 0; $i < count($productos); $i++){

		  	/*=============================================
 	 		TRAEMOS LA IMAGEN
  			=============================================
			if(file_exists("../".$productos[$i]["imagen"]))
			{
			  $imagen = "<img src='". str_replace('\\', '\\\\', $productos[$i]["imagen"])."' width='40px'>";
			}
			else
			{
				$imagen = "<img src='".str_replace('\\', '\\\\', "vistas\img\plantilla\product-anonymous.png")."' width='40px'>";
			}*/ 

		  	/*=============================================
 	 		STOCK
  			=============================================*/ 
/*
  			if($productos[$i]["stock"] <= 10){

  				$stock = "<button class='btn btn-danger'>".$productos[$i]["stock"]."</button>";

  			}else if($productos[$i]["stock"] > 11 && $productos[$i]["stock"] <= 15){

  				$stock = "<button class='btn btn-warning'>".$productos[$i]["stock"]."</button>";

  			}else{

  				$stock = "<button class='btn btn-success'>".$productos[$i]["stock"]."</button>";

  			}*/


		  	/*=============================================
 	 		STOCK
  			=============================================
			if(is_numeric($productos[$i]["cantidad_alerta"]) ){


				$cantidad_stock_aux = 0;
					if(  substr($productos[$i]["stock"], -3) =='.00' ) $cantidad_stock_aux=  (int) $productos[$i]["stock"] ;
						else $cantidad_stock_aux=  $productos[$i]["stock"]   ;


				if($productos[$i]["stock"] <= $productos[$i]["cantidad_alerta"] ){

					

	  				$stock = "<button class='btn btn-danger'>".$cantidad_stock_aux."</button>";

	  			}else if($productos[$i]["stock"] > $productos[$i]["cantidad_alerta"]  && $productos[$i]["stock"] <=    ($productos[$i]["cantidad_alerta"]*2)   ){
	  				
	  				$stock = "<button class='btn btn-warning'>". $cantidad_stock_aux  ."</button>";
	  			}else{
	  				
	  				$stock = "<button class='btn btn-success'>". $cantidad_stock_aux  ."</button>";
	  			}
			}else 
				$stock = "<button class='btn btn-info'> N.I </button>";*/
					if(  substr($productos[$i]["stock"], -3) =='.00' ) $cantidad_stock_aux=  (int) $productos[$i]["stock"] ;
					else $cantidad_stock_aux=  $productos[$i]["stock"]   ;
	

				$stock = "<button class='btn btn-info'>". $cantidad_stock_aux  ."</button>";

		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/ 

		  	$botones =  "<button class='btn btn-primary nuevaVentaAgregarProducto nuevaVentaRecuperarVenta' idProducto ='".$productos[$i]["codproducto"]."' descripcion='".$productos[$i]["nomproducto"]."' precio_venta = '".$productos[$i]["precio"]."' stock= '".$productos[$i]["stock"]."' codigo_producto_sunat='".$productos[$i]["lote"]."'  tipo_afectacion_sunat='' unidad_medida_sunat='".$productos[$i]["codunidad"]."'>Agregar</button>";

		  	$datosJson .='[
			      "'.($i+1).'",
			      "",
			      "'.$productos[$i]["codproducto"].'",
			      "'.$productos[$i]["nomproducto"].'",
			      "'.$productos[$i]["precio"].'",
			      "'.$botones.'"
			    ],';

		  }

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   '] 

		 }';
		
		echo $datosJson;


	}


/*=============================================
	ELIMINAR PRODUCTO
=============================================*/	

	public $idEliminarProducto;

	 public function ajaxEliminarProducto(){

		
		$item = "id";
		$valor = $this->idEliminarProducto;
		  
		$respuesta = ModeloProductos::mdlEliminarProducto($item, $valor);
		echo json_encode($respuesta);

	}


}



$activarProductos = new AjaxCrearVentas();
$activarProductos -> mostrarTablaProductos();

 /*=============================================
 = AGREGANDO PRODUCTOS DESDE EL BOTÓN DE DISPOSITIVOS =
 =============================================*/
 
 