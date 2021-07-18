<?php
class ControladorProductos{

	static public function ctrMostrarProductos( $item , $valor){
		$tabla = "producto";
		$respuesta = ModeloProductos::MdlMostrarProductos($tabla, $item , $valor );
		return $respuesta;
	}//

	static public function ctrReporteProductos(  )
	{
		return ModeloProductos::mdlReporteProductos(  );
	}

	/*=============================================
				CHEQUEAR CODIGO BARRAS
	=============================================*/	
	static public function ctrChequearCodigo( $obj )
	{
		return ModeloProductos::mdlChequearCodigo( $obj );
	}

	static public function ctrCrearProducto(){


		if(isset($_POST["crear_producto"] )){
		


			date_default_timezone_set('America/Bogota');
			$fecha = date('Y-m-d');
			$hora = date('H:i:s');
			$fecha = $fecha.' '.$hora;


		//	echo'<script>console.log("aqui es: '.$_POST["cantidadActual"].'");</script>';
			$crearParejaInventario =0;  
			$costoIdeal =0;
			$cantidad_alerta =0;
		//	$cantidadActual = 0 ;
		//	$costoValorizado = 0 ;
			
 			$datos = array (
				"codproducto" => bd::sanear_strings_especiales( $_POST["nuevaCodProducto"]),
				"descripcion" => bd::sanear_strings_especiales( $_POST["nuevaDescripcion"]),
				"unidad_medida_sunat" => $_POST["nuevoProductoUnidadSunat"],
				"precio_venta" => $_POST["precioVenta"],
				"tipo_afectacion_sunat" => $_POST["nuevoProductoAfectacion"], 			 
				"moneda" => 'PEN');

			$idProductoInsertado = ModeloProductos::mdlIngresarProductos($datos, $crearParejaInventario ,$costoIdeal , 	$cantidad_alerta , $fecha );
 
		//echo'<script>console.log("aqui es: '. $idProductoInsertado .'");</script>';
			if($idProductoInsertado["error"] == 0){

		 		echo '<script>
					swal({
						type: "success",
						title: "El producto ha sido creado correctamente!.",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							 window.location = "productos";
						}
					});
				</script>';

 
			} else 
					echo '<script>
					swal({
						type: "error",
						title: "'.$idProductoInsertado["data"]. '",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "productos";
						}
					});
				</script>';

			 			//Inicializamos la ruta de la foto por si no hay foto.
			$ruta ="";	

		}

	}



/*=============================================
						EDITAR EL PRODUCTO.
=============================================*/


static public function ctrEditarProducto(){


			 

	if(isset($_POST["editarIdProducto"] )){
	


				

 
			
			$tabla = "productos";
 			$datos = array (
 							"codigo_producto_sunat" => $_POST["editarCodigoSunatProducto"],
 							"precio_venta" => $_POST["editarPrecioVenta"],
 							"descripcion" =>  bd::sanear_strings_especiales($_POST["editarDescripcion"]),
 							"codproducto" => $_POST["editarIdProducto"],
 							"codproducto" => $_POST["editarIdProducto"],


 							"unidad_medida_sunat" => $_POST["editarProductoUnidadSunat"],
 							"tipo_afectacion_sunat" => $_POST["editarProductoAfectacion"] );

			$respuesta = ModeloProductos::mdlEditarProductos($tabla,$datos);
 

			if($respuesta == "ok")
 				echo '<script>
					swal({
						type: "success",
						title: "El producto ha sido guardada correctamente!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "productos";
						}
					});
				</script>';
			else
				echo '<script>
					swal({
						type: "error",
						title: "'	.$respuesta.'",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "productos";
						}
					});
				</script>';
 

	

		}

	}


/*=============================================
	MOSTRAR PRODUCTOS GRAFICO RANKING
=============================================*/

	static public function ctrTraerProductosGraficoProductos(){

		$respuesta = ModeloProductos::MdlMostrarProductosGraficoVentas();

		$arreglo= array();

		$colores = array("orange","green","yellow","aqua","purple","blue","cyan","magenta","red","gold");
//light-blue

		foreach ($respuesta as $key => $valor) {
		      
		    	$objeto = array();

		    	$ventaTotal = $valor["ventaTotal"] ;
		    	
		    	$descripcion = $valor["descrip"] ;
		 		
		 		$imagen = $valor["imagen"] ;

		 		$porcentaje = $valor["porcentaje"] ;


		    	$objeto = array('value'=> $ventaTotal , 'color'=> $colores[$key]   , 'highlight' => $colores[$key]    , 'label' => 	$descripcion  ,'imagen' => $imagen , 'porcentaje'=> $porcentaje);

		    	array_push( $arreglo , $objeto ) ;

		    };

		$datos = json_encode($arreglo);

		return $datos;

	}

	

/*=============================================
	PRODUCTO TOP
=============================================*/
/*
	static public function ctrProductoTop(){

		$respuesta = ModeloProductos::mdlProductoTop();
		return $respuesta;

	}	
*/


	static public function ctrEliminarProducto($id){

	
		$tabla = "productos";

		$respuesta = ModeloProductos::mdlEliminarProducto($tabla, $id);

		return $respuesta;

	}




	static public function ctrEliminarUnidadMedidaProducto($idUnidadMedidaProducto ){

	  

		$respuesta = ModeloProductos::mdlEliminarUnidadMedidaProducto($idUnidadMedidaProducto );

		return $respuesta;

	}




	/*=============================================
			UNIDAD DE MEDIDA
	=============================================*/

static public function ctrMostrarUnidadSalidaProducto($id_producto){

 
		$respuesta = ModeloProductos::mdlMostrarUnidadSalidaProducto($id_producto );

		return $respuesta;



}





	static public function ctrCrearUnidadMedidaEnlace(){


			if(isset($_POST["unidadMedidaProductoEnlaceCantidad"])){

 

			   	$datos = array("id_inventario_unidad_medida_salida"=>$_POST["unidadMedidaProductoEnlaceUnidadMedida"],
					           "id_producto"=>$_POST["idProductoUnidadMedida"],
					           "cantidad_inventario"=>$_POST["unidadMedidaProductoEnlaceCantidad"]);

					
			   	$respuesta = ModeloProductos::mdlCrearUnidadMedidaEnlace(  $datos  );


	if(  	$respuesta  == "ok" ) {


 
					echo'<script>

					swal({
						  type: "success",
						  title: "El producto ha sido creado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "productos";

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
							window.location = "productos";
						}
					});
				</script>';

		



			}

	}



/*==================================================
|				IMPORTAR PRODUCTOS					|
====================================================*/
static public function ctrImportarProductos( $obj )
{
	$archivo = $obj -> archivo_adjuntar;
	if( ( ( $archivo["size"] )/1024 ) > 1024)
		return array("data"=>0, "mensaje"=>"Tamaño del archivo mayor a 1MB");
	date_default_timezone_set('America/Bogota');
	include("../extensiones/phpexcel/PHPExcel.php");
	include("../extensiones/phpexcel/PHPExcel/Reader/Excel2007.php");
	require_once "../modelos/bd.modelo.php";
	$fecha = date('Y-m-d H:i:s');
	$name	  = $archivo['name'];
	$tname 	  = $archivo['tmp_name'];
	$type 	  = $archivo['type'];
	
	if($type == 'application/vnd.ms-excel')
	$ext = 'csv'; // Extension excel 97
	else if($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
	{
		$ext = 'xlsx'; // Extension excel 2007 y 2010
	}
	else
	{
		if (function_exists('finfo_file'))
		{
			$type  = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tname);
			if($type == 'application/vnd.ms-excel')
			{
				$ext = 'xls'; // Extension excel 97
			}
			else if($type == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
			{
				$ext = 'xlsx'; // Extension excel 2007 y 2010		
			}
		}
		if( !isset( $ext ) )
		{
			return array("data"=>0, "mensaje"=>"¡Error! Extensión de archivo inválida.");
		}
	}
	if( isset( $ext ) )
	{
		$xls  = 'Excel5';
		$xlsx = 'Excel2007';
		// Creando el lector
		$objReader = PHPExcel_IOFactory::createReader($$ext);
		// Cargamos el archivo
		$objPHPExcel = $objReader->load( $tname );
		$objPHPExcel->setActiveSheetIndex(0);
		$hoja         = $objPHPExcel->getActiveSheet();
		$_DATOS_EXCEL = [];
		if($hoja->getCell('B1')->getCalculatedValue() != "CODIGO_BARRAS")
		{
			return array("data"=>0, "mensaje"=>"¡Error! Formato de carga no válido.");
		}
		
		$ixx = 2;
		while($hoja->getCell("D".$ixx)->getValue() != "")
		{
			$unidad_medida = "";
			switch ( $hoja->getCell('E'.$ixx)->getCalculatedValue() )
			{
				case 'UNIDADES':
					$unidad_medida = "NIU-unidades";
					break;
				case 'OTROS':
					$unidad_medida = "ZZ-otros";
					break;
				case 'KILOGRAMO':
					$unidad_medida = "KGM-kilogramo";
					break;
				case 'CAJA':
					$unidad_medida = "NIU-caja";
					break;
				case 'BOLSA':
					$unidad_medida = "BG-bolsa";
					break;
				case 'DOCENA':
					$unidad_medida = "DZN-docena";
					break;
				case 'GALON':
					$unidad_medida = "WGN-galon";
					break;
				case 'GRAMO':
					$unidad_medida = "GRM-gramo";
					break;
				case 'METRO':
					$unidad_medida = "MTR-metro";
					break;
				case 'PAQUETE':
					$unidad_medida = "PK-paquete";
					break;
				case 'BOTELLA':
					$unidad_medida = "BO-botella";
					break;
				case 'SACO':
					$unidad_medida = "SA-saco";
					break;
				case 'ROLLO':
					$unidad_medida = "RO-rollo";
					break;
				case 'LITRO':
					$unidad_medida = "LTR-litro";
					break;
				default:
					$unidad_medida = "NIU-unidades";
					break;
			}
			switch ($hoja->getCell('F'.$ixx)->getCalculatedValue()) {
				case 'GRAVADO':
					$tipo_afectacion = "Gravado";
					break;

				case 'INAFECTO':
					$tipo_afectacion = "Inafecto";
					break;
				
				case 'EXONERADO':
					$tipo_afectacion = "Exonerado";
					break;
				
				case 'IMPUESTO_BOLSAS':
					$tipo_afectacion = "Impuesto Bolsas";
					break;
					
				default:
					# code...
					break;
			}
			$_DATOS_EXCEL[$ixx]['CATEGORIA_NOMBRE']  = bd::sanear_strings_especiales($hoja->getCell('A'.$ixx)->getCalculatedValue());
			$_DATOS_EXCEL[$ixx]['CODIGO_BARRAS']     = $hoja->getCell('B'.$ixx)->getCalculatedValue();
			$_DATOS_EXCEL[$ixx]['CODIGO_SUNAT']      = $hoja->getCell('C'.$ixx)->getCalculatedValue();
			$_DATOS_EXCEL[$ixx]['NOMBRE_PRODUCTO']   = bd::sanear_strings_especiales($hoja->getCell('D'.$ixx)->getCalculatedValue());
			$_DATOS_EXCEL[$ixx]['UNIDAD_DE_MEDIDA']  = $unidad_medida;
			$_DATOS_EXCEL[$ixx]['TIPO_AFECTACION']   = $tipo_afectacion;
			$_DATOS_EXCEL[$ixx]['PRECIO_VENTA']      = $hoja->getCell('G'.$ixx)->getCalculatedValue();
			$_DATOS_EXCEL[$ixx]['CREAR_INVENTARIO']  = $hoja->getCell('H'.$ixx)->getCalculatedValue();
			$_DATOS_EXCEL[$ixx]['COSTO_REFERENCIAL'] = $hoja->getCell('I'.$ixx)->getCalculatedValue();
			$_DATOS_EXCEL[$ixx]['CANTIDAD_ALERTA']   = $hoja->getCell('J'.$ixx)->getCalculatedValue();
			$_DATOS_EXCEL[$ixx]['ALMACEN']           = 1;
			$_DATOS_EXCEL[$ixx]['IMAGEN']            = "vistas/img/plantilla/product-anonymous.png";
			$_DATOS_EXCEL[$ixx]['LOCAL']             = $obj -> id_local;
			$ixx++;
			
		}
		if(count($_DATOS_EXCEL) > 0)
		{
			$respuesta = ModeloProductos::mdlImportarProductos( $_DATOS_EXCEL, $fecha );
			return $respuesta;
		}
		else
		{
			return array("data"=>0, "mensaje"=>"ARCHIVO SIN PRODUCTOS PARA IMPORTAR.");
			
		}
	}
	else
	{
		return array("data"=>0, "mensaje"=>"Error al adjuntar documento.");
	}
}
	static public function ctrGetProductos( $id_local )
	{
		return ModeloProductos::mdlGetProductos( $id_local );
	}
	static public function ctrGetListProductosApi( $datos )
	{
		return ModeloProductos::mdlGetListProductosApi( $datos );
	}
	static public function enviar_json_api ($data_json, $ruta){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ruta);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            )
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta  = curl_exec($ch);
        curl_close($ch);
        return $respuesta;

    }
	static public function ctrGetProductosApi( $datos )
	{
		
		date_default_timezone_set('America/Bogota');

		$fecha = date('Y-m-d H:i:s');
		
		$array = array( 
			"accion" => "getProductosApi",
			"id_local" => $datos->id_local,
			"nombre_local"=> $datos->nombre_local
		);
		$json      = json_encode($array);
		$respuesta = self::enviar_json_api( $json, $datos->ruta );

		$listas = json_decode( $respuesta , true );
		

		$productos     = $listas["listaProductos"];
		$unidad_medida = $listas["unidad_medida"];
		$um_salida     = $listas["unidad_medida_salida"];
		$inventario    = $listas["inventario"];

		$ixx=0;
		
		$list_productos = [];
		foreach( $productos as $producto )
		{			
			$list_productos[$ixx]['ID']                = $producto['id'];
			$list_productos[$ixx]['CATEGORIA_NOMBRE']  = $producto['categoria'];
			$list_productos[$ixx]['CODIGO_BARRAS']     = $producto['codigo_barras'];
			$list_productos[$ixx]['CODIGO_SUNAT']      = $producto['codigo_producto_sunat'];
			$list_productos[$ixx]['NOMBRE_PRODUCTO']   = $producto['descripcion'];
			$list_productos[$ixx]['UNIDAD_DE_MEDIDA']  = $producto['unidad_medida_sunat'];
			$list_productos[$ixx]['TIPO_AFECTACION']   = $producto['tipo_afectacion_sunat'];
			$list_productos[$ixx]['PRECIO_VENTA']      = $producto['precio_venta'];
			$list_productos[$ixx]['COSTO_REFERENCIAL'] = $producto['precio_venta'];
			$list_productos[$ixx]['CANTIDAD_ALERTA']   = 1;
			$list_productos[$ixx]['ALMACEN']           = 1;
			$list_productos[$ixx]['IMAGEN']            = "vistas/img/plantilla/product-anonymous.png";
			$list_productos[$ixx]['LOCAL']             = $datos -> id_local;
			$ixx++;			
		}
		if(count($list_productos) > 0)
		{
			$respuesta = ModeloProductos::mdlImportarProductosApi( $list_productos,	$fecha,	$unidad_medida,	$um_salida,	$inventario );
			if($respuesta['data'] == 1)
			{
				return $respuesta;	

			}
			else{
				return array("data"=>0, "mensaje"=>"HUBO UN ERROR AL OBTENER LOS PRODUCTOS");			

			}
		}
		else
		{
			return array("data"=>0, "mensaje"=>"ARCHIVO SIN PRODUCTOS PARA IMPORTAR.");			
		}
		//file_put_contents("1ctrGetProductosApi.txt", $return );
	}



}