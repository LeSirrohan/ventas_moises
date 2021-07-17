<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";


class AjaxProductos {

	/*=============================================
			MOSTRAR LA TABLA DE PRODUCTOS
	=============================================*/
 	/*=============================================
 	 MOSTRAR LA TABLA DE PRODUCTOS
  	=============================================*/

	public function mostrarTablaProductos( $crear_productos ){

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
  			=============================================*/
			/*if(file_exists(str_replace('\\', '\\\\', $productos[$i]["imagen"])))
			{
				$imagen = "<img src='". str_replace('\\', '\\\\', $productos[$i]["imagen"])."' width='40px'>";
			}
			else
			{
				if(file_exists(str_replace('\\', '\\\\', "..\\".$productos[$i]["imagen"])))
				{
					$imagen = "<img src='". str_replace('\\', '\\\\', $productos[$i]["imagen"])."' width='40px'>";
				}
				else
				{
					$imagen = "<img src='".str_replace('\\', '\\\\', "vistas\img\plantilla\product-anonymous.png")."' width='40px'>";
				}
			}*/
			$imagen ="";

		  	/*=============================================
 	 		STOCK
  			=============================================*/
			if(is_numeric($productos[$i]["stock"]) ){


				$cantidad_stock_aux = 0;
					if(  substr($productos[$i]["stock"], -3) =='.00' ) $cantidad_stock_aux=  (int) $productos[$i]["stock"] ;
						else $cantidad_stock_aux=  $productos[$i]["stock"]   ;


				if($productos[$i]["stock"] <= $productos[$i]["stock"] ){



	  				$stock = "<button class='btn btn-danger'>".$cantidad_stock_aux."</button>";

	  			}else if($productos[$i]["stock"] > $productos[$i]["stock"]  && $productos[$i]["stock"] <=    ($productos[$i]["stock"]*2)   ){

	  				$stock = "<button class='btn btn-warning'>". $cantidad_stock_aux  ."</button>";
	  			}else{

	  				$stock = "<button class='btn btn-success'>". $cantidad_stock_aux  ."</button>";
	  			}
			}else
				$stock = "<button class='btn btn-info'> VAR </button>";

			if($productos[$i]["id_prod"] == "")
			{
				$boton_imprimir = "";
			}
			else{
				$boton_imprimir = "<button type='button' class='btn bg-indigo btnPrintLabel' idProducto='".$productos[$i]["id_prod"]."' ><i class='fas fa-barcode'></i></button>";

			}


		  	/*=============================================
 	 		TRAEMOS LAS ACCIONES
  			=============================================*/
			$botones = "";

			if($crear_productos == 1)
			{
				$botones .=  "<div class='btn-group'><button type='button' class='btn btn-warning btnEditarProducto' idProducto='".$productos[$i]["id_prod"]."' descripcionEditarProducto='".$productos[$i]["descripcion"]."' ";
				$botones .=  "codigoEditarProducto='".$productos[$i]["id_prod"]."'";
				$botones .=  "codigo_producto_sunat='".$productos[$i]["codproducto"]."' precVentaEditarProducto='".$productos[$i]["precio"]."' ";
				$botones .=  "tipo_afectacion_sunat='".$productos[$i]["tipo_afectacion_sunat"]."' unidad_medida_sunat='".$productos[$i]["codunidad"]."' ";
				$botones .=  "data-toggle='modal' data-target='#modalEditarProducto'><i class='fas fa-edit'></i></button>";
			}

			$botones .= "<button type='button' class='btn btn-info btnUnidadMedidaProducto'  idProducto='".$productos[$i]["id_prod"]."'data-toggle='modal' data-target='#modalEnlaceInventario'><i class='fas fa-cubes'></i></button>";
			
			$botones .= $boton_imprimir;


			if($crear_productos == 1)
			{
				$botones .= "<button type='button' class='btn btn-danger btnEliminarProducto' idProducto='".$productos[$i]["id_prod"]."' imagen='".str_replace('\\', '\\\\', $imagen)."'><i class='far fa-trash-alt'></i></button></div>";
			}
		  	$datosJson .='[
			      "'.($i+1).'",
			      "'.$productos[$i]["nomproducto"].'",
			      "'.$stock.'",
			      "'.$productos[$i]["codunidad"].'",
			      "'.$productos[$i]["codmoneda"].' '.$productos[$i]["precio"].'",
			      "'.$productos[$i]["tipo_afectacion_sunat"].'",
			      "'.$botones.'"
			    ],';

		  }

		  $datosJson = substr($datosJson, 0, -1);

		 $datosJson .=   ']

		 }';

		echo $datosJson;


	}





}
$crear_productos = "";
$crear_productos = $_POST["crear_productos"];
$activarProductos = new AjaxProductos();
$activarProductos -> mostrarTablaProductos( $crear_productos );
