<?php


class ControladorClientes{

	/*=============================================
	CREAR CLIENTES
	=============================================*/

	static public function ctrCrearCliente( $crud){

		if(isset($_POST["nuevoCliente"])){

			
			if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"])  &&$crud ){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡El nombre del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "clientes";
						}
					});
				</script>';
		}

					if(!preg_match('/^[0-9]+$/', $_POST["nuevoDocumentoId"]) &&$crud  ) {
			 	echo '<script>
					swal({
						type: "error",
						title: "¡El Documento del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "clientes";
						}
					});
				</script>';
		}

					if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) &&$crud){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡El correo del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "clientes";
						}
					});
				</script>';
		}


					if(!preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) &&$crud){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡El teléfono del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "clientes";
						}
					});
				</script>';
		}



					if(!preg_match('/^[#\.\-a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDireccion"]) &&$crud){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡La dirección del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "clientes";
						}
					});
				</script>';
		}




/*

			if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"]) &&
			   preg_match('/^[0-9]+$/', $_POST["nuevoDocumentoId"]) &&
			   preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"]) && 
			   preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDireccion"]))*/

			   	$tabla = "cliente";

			   	$datos = array("nombre"=>$_POST["nuevoCliente"],
					           "documento"=>$_POST["nuevoDocumentoId"],
					           "email"=>$_POST["nuevoEmail"],
					           "telefono"=>$_POST["nuevoTelefono"],
					           "direccion"=>$_POST["nuevaDireccion"],
					           "fecha_nacimiento"=>$_POST["nuevaFechaNacimiento"]);

					
			   	$respuesta = ModeloClientes::mdlIngresarCliente($tabla, $datos);


	if(is_numeric($respuesta)   ) {


if($crud)
					echo'<script>

					swal({
						  type: "success",
						  title: "El cliente ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "clientes";

									}
								})

					</script>';


					else  echo $respuesta;

				}

		

		}

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function ctrMostrarClientes($item, $valor){

		$tabla = "cliente";

		$respuesta = ModeloClientes::mdlMostrarClientes($tabla, $item, $valor);

		return $respuesta;

	}

	/*=============================================
	LISTADO REPORTE CLIENTES
	=============================================*/

	static public function ctrReporteClientes(){

		return ModeloClientes::mdlMostrarTodosClientes( );

	}

	/*=============================================
	LISTADO REPORTE CLIENTES VENTAS X MES
	=============================================*/

	static public function ctrReporteVentasClientesxMes(){

		return ModeloClientes::mdlReporteVentasClientesxMes( );

	}

	/*=============================================
	MOSTRAR CLIENTES PARA EL BUSCADOR VENTAS
	=============================================*/

	static public function ctrMostrarClientesBuscador(){

		$respuesta = ModeloClientes::mdlMostrarClientesBuscador();

		return $respuesta;

	}

	/*=============================================
			LISTADO VENTAS
	=============================================*/

	static public function ctrListadoClientes(){

		return ModeloClientes::mdlReporteClientes();

	}


	/*=============================================
	MOSTRAR CLIENTES PARA EL BUSCADOR VENTAS
	=============================================*/

	static public function ctrRankingClientesReportes(){

		$respuesta = ModeloClientes::mdlRankingClientesReportes();

		$array = array();
	
    	foreach ($respuesta as $key => $value) {
      
      		$objeto = array();

      		$nombreCliente = $value["nombre_cliente"] ;
      
      		$cantidad_ventas = $value["total_ventas"] ;
      	
      		$cantidad_productos = $value["total_productos"] ;
 
      		$objeto = array('y'=> $nombreCliente , 'a'=> $cantidad_ventas , 'b' => $cantidad_productos );

			array_push( $array , $objeto ) ;

    	};

		return $array;

	}



	/*=============================================
	EDITAR CLIENTE
	=============================================*/

	static public function ctrEditarCliente(){

		if(isset($_POST["editarCliente"])){

			/*if(preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["editarCliente"]) &&
			   preg_match('/^[0-9]+$/', $_POST["editarDocumentoId"]) &&
			   preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["editarEmail"]) && 
			   preg_match('/^[()\-0-9 ]+$/', $_POST["editarTelefono"]) && 
			   preg_match('/^[#\.\-a-zA-Z0-9 ]+$/', $_POST["editarDireccion"])){*/

				   $tabla = "cliente";

			   	$datos = array("id"=>$_POST["idCliente"],
			   				   "nombre"=>$_POST["editarCliente"],
			   				   "nombre_comercial"=>$_POST["editarNombreComercial"],
					           "documento"=>$_POST["editarDocumentoId"],
					           "email"=>$_POST["editarEmail"],
					           "telefono"=>$_POST["editarTelefono"],
					           "direccion"=>$_POST["editarDireccion"],
					           "nota"=>$_POST["editarNota"],
							   "fecha_nacimiento"=>$_POST["editarFechaNacimiento"]);

			   	$respuesta = ModeloClientes::mdlEditarCliente($tabla, $datos);

			   	if($respuesta == "ok"){

					/*echo'<script>

					swal({
						  type: "success",
						  title: "El cliente ha sido cambiado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									window.location = "clientes";

									}
								})

					</script>';*/echo'<script>

					swal({
						type: "success",
						title: "El cliente ha sido cambiado correctamente",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
						})

				  </script>';

				}

			}else{

				echo'<script>

					swal({
						  type: "error",
						  title: "¡El cliente no puede ir vacío o llevar caracteres especiales!",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
							if (result.value) {

							window.location = "clientes";

							}
						})

			  	</script>';



			//}

		}

	}

	/*=============================================
	ELIMINAR CLIENTE
	=============================================*/

	static public function ctrEliminarCliente(){

		if(isset($_GET["idCliente"])){

			$tabla ="cliente";
			$datos = $_GET["idCliente"];

			$respuesta = ModeloClientes::mdlEliminarCliente($tabla, $datos);

			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El cliente ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "clientes";

								}
							})

				</script>';

			}		

		}

	}


	/*=============================================
	BUSQUEDA DE CLIENTES POR RUC Y DNI
	=============================================*/

	static public function ctrbusquedaDNIRUC($id , $id_local){


			$server = ControladorConfiguracion::ctrServidorConsultas($id_local) ;
			$respuesta = "";

			if( strlen($id) == 11) {

			$ruta = $server."/api/consultaRuc";
			 //   $ruta = "http://localhost/ABCmovilWeb/public/api/guardarDataBI";
			$ruc = 	 $id;

			// Invocamos el servicio a ruc.com.pe
			// Ejemplo para JSON
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ruta);
			curl_setopt(
			    $ch, CURLOPT_HTTPHEADER, array(
			    'Content-Type: application/json',
			    )
			);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$ruc);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$respuesta  = curl_exec($ch);
			curl_close($ch);

		}

		if( strlen($id) == 8) {

			$ruta = $server."/api/consultaDNI";
			 //   $ruta = "http://localhost/ABCmovilWeb/public/api/guardarDataBI";
			$dni = 	 $id ;

			// Invocamos el servicio a ruc.com.pe
			// Ejemplo para JSON
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ruta);
			curl_setopt(
			    $ch, CURLOPT_HTTPHEADER, array(
			    'Content-Type: application/json',
			    )
			);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$dni);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$respuesta  = curl_exec($ch);
			curl_close($ch);
		}







			return $respuesta;
			/*
			if($respuesta == "ok"){

				echo'<script>

				swal({
					  type: "success",
					  title: "El cliente ha sido borrado correctamente",
					  showConfirmButton: true,
					  confirmButtonText: "Cerrar"
					  }).then(function(result){
								if (result.value) {

								window.location = "clientes";

								}
							})

				</script>';

			}	*/	

	}
	/*==================================================
	|				IMPORTAR CLIENTES					|
	====================================================*/
	static public function ctrImportarClientes( $obj )
	{
		$archivo = $obj -> archivo_adjuntar;
		if( ( ( $archivo["size"] )/1024 ) > 1024)
			return array("data"=>0, "mensaje"=>"Tamaño del archivo mayor a 1MB");
		date_default_timezone_set('America/Bogota');
		include("../extensiones/phpexcel/PHPExcel.php");
		include("../extensiones/phpexcel/PHPExcel/Reader/Excel2007.php");
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
			if($hoja->getCell('A1')->getCalculatedValue() != "DOC_CLIENTE"){
				return array("data"=>0, "mensaje"=>"¡Error! Formato de carga no válido.");

			}
			
			$ixx = 2;
			while($hoja->getCell("A".$ixx)->getValue() != "")
			{
				
				$_DATOS_EXCEL[$ixx]['DOC_CLIENTE']       = $hoja->getCell('A'.$ixx)->getCalculatedValue();
				$_DATOS_EXCEL[$ixx]['NOMBRE_CLIENTE']    = $hoja->getCell('B'.$ixx)->getCalculatedValue();
				$_DATOS_EXCEL[$ixx]['NOMBRE_COMERCIAL']  = $hoja->getCell('C'.$ixx)->getCalculatedValue();
				$_DATOS_EXCEL[$ixx]['EMAIL_CLIENTE']     = $hoja->getCell('D'.$ixx)->getCalculatedValue();
				$_DATOS_EXCEL[$ixx]['TELEFONO_CLIENTE']  = $hoja->getCell('E'.$ixx)->getCalculatedValue();
				$_DATOS_EXCEL[$ixx]['DIRECCION_CLIENTE'] = $hoja->getCell('F'.$ixx)->getCalculatedValue();
				if($_DATOS_EXCEL[$ixx]['NOMBRE_COMERCIAL'] == NULL OR is_empty($_DATOS_EXCEL[$ixx]['NOMBRE_COMERCIAL']))
				{
					$_DATOS_EXCEL[$ixx]['NOMBRE_COMERCIAL'] = $_DATOS_EXCEL[$ixx]['NOMBRE_CLIENTE'];
				}
				$ixx++;
				
			}
			if(count($_DATOS_EXCEL) > 0)
			{
				$respuesta = ModeloClientes::mdlImportarClientes( $_DATOS_EXCEL );
				return $respuesta;
			}
			else
			{
				return array("data"=>0, "mensaje"=>"Archivo sin clientes para importar.");
				
			}
		}
		else
		{
			return array("data"=>0, "mensaje"=>"Error al adjuntar documento.");
		}
	}

	static public function ctrIngresarAnotacionCliente( $anotacion )
	{
		return ModeloClientes::mdlIngresarAnotacionCliente( $anotacion );
	}

	static public function ctrListadoClientesAnotaciones( $anotaciones )
	{
		$respuesta = ModeloClientes::mdlListadoClientesAnotaciones( $anotaciones );
		return $respuesta;
	}

}


/*=============================================
	TRAER INFORMACION RUC.
=============================================*/	





/*
		if(isset($_POST["nuevoCliente"])){

			
			if(!preg_match('/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevoCliente"])){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡El nombre del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "productos";
						}
					});
				</script>';
		}

					if(!preg_match('/^[0-9]+$/', $_POST["nuevoDocumentoId"])){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡El Documento del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "productos";
						}
					});
				</script>';
		}

					if(!preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/', $_POST["nuevoEmail"])){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡El correo del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "productos";
						}
					});
				</script>';
		}


					if(!preg_match('/^[()\-0-9 ]+$/', $_POST["nuevoTelefono"])){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡El teléfono del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "productos";
						}
					});
				</script>';
		}



					if(!preg_match('/^[#\.\-a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ ]+$/', $_POST["nuevaDireccion"])){
			 	echo '<script>
					swal({
						type: "error",
						title: "¡La dirección del cliente no puede llevar caracteres especiales o ir vacio!",
						showConfirmButton: true,
						confirmButtonText: "Cerrar"
					}).then(function(result){
						if(result.value){
							window.location = "productos";
						}
					});
				</script>';
		}



 

			   	$tabla = "cliente";

			   	$datos = array("nombre"=>$_POST["nuevoCliente"],
					           "documento"=>$_POST["nuevoDocumentoId"],
					           "email"=>$_POST["nuevoEmail"],
					           "telefono"=>$_POST["nuevoTelefono"],
					           "direccion"=>$_POST["nuevaDireccion"],
					           "fecha_nacimiento"=>$_POST["nuevaFechaNacimiento"]);

			   	$respuesta = ModeloClientes::mdlIngresarCliente($tabla, $datos);

			   	if($respuesta == "ok"){

					echo'<script>

					swal({
						  type: "success",
						  title: "El cliente ha sido guardado correctamente",
						  showConfirmButton: true,
						  confirmButtonText: "Cerrar"
						  }).then(function(result){
									if (result.value) {

									//window.location = "clientes";

									}
								})

					</script>';

				}

			

		}*/