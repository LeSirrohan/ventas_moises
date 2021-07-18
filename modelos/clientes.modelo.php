<?php

require_once "conexion.php";

class ModeloClientes{

	/*=============================================
	CREAR CLIENTE
	=============================================*/

	static public function mdlIngresarCliente($tabla, $datos){


 		$conn = Conexion::conectar();
		$select = $conn->prepare("SELECT (MAX(codcliente)+1)*1 AS max FROM cliente");
		$select -> execute();

		$codcliente= $select -> fetchAll(PDO::FETCH_ASSOC);
 		$stmt = $conn->prepare("INSERT INTO $tabla(codcliente, nomrznsocial, docidentidad, direccion, codubigeo, codtipodocumento, referencia, codemp) 
		 VALUES (:codcliente, :nomrznsocial, :docidentidad, :direccion, :codubigeo, :codtipodocumento, '', :codemp)");

		$codemp = 1;
		$codubigeo = "010102";
		$codtipodocumento = 1;
		$codemp = 1;
		$stmt->bindParam(":codcliente", $codcliente[0]["max"], PDO::PARAM_STR);
		$stmt->bindParam(":nomrznsocial", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":docidentidad", $datos["documento"], PDO::PARAM_INT);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":codubigeo", $codubigeo, PDO::PARAM_STR);
		$stmt->bindParam(":codtipodocumento", $datos["tipo_doc_id"], PDO::PARAM_STR);
		$stmt->bindParam(":codemp", $codemp, PDO::PARAM_STR);

		if($stmt->execute()){

			//$lastID = $conn->lastInsertId();
 			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
 			return $codcliente[0]["max"];

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
				MOSTRAR  ANOTACIONES CLIENTES
	=============================================*/

	static public function mdlIngresarAnotacionCliente( $datos ){

        date_default_timezone_set('America/Bogota');

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        $fecha_anotacion = $fecha.' '.$hora;

 		$conn = Conexion::conectar();

 		$stmt = $conn->prepare("INSERT INTO cliente_anotacion ( id_documento_cliente, descripcion, id_usuario, fecha) 
								VALUES ( :id_documento_cliente, :descripcion, :id_usuario, :fecha)");

		$stmt->bindParam(":id_documento_cliente", $datos->idCliente , PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos->descripcionNota , PDO::PARAM_STR);
		$stmt->bindParam(":id_usuario", $datos->id_usuario , PDO::PARAM_INT);
		$stmt->bindParam(":fecha", $fecha_anotacion , PDO::PARAM_STR);

		if($stmt->execute()){
 			return "ok";
		}
		else{
			return "error";
		}
	}

	static public function mdlListadoClientesAnotaciones( $clientes )
	{

		$stmt = Conexion::conectar()->prepare("SELECT cliente_anotacion.*, usuario.usuario FROM cliente_anotacion
		INNER JOIN usuario ON cliente_anotacion.id_usuario = usuario.id WHERE id_documento_cliente = :id_documento_cliente;");

		$stmt->bindParam(":id_documento_cliente", $clientes->idCliente , PDO::PARAM_STR);

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlMostrarClientes($tabla, $item, $valor){

		if($item != null){
			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE docidentidad = :$item OR codcliente = :codcliente ;");

			$stmt -> bindParam(":".$item, $valor, PDO::PARAM_STR);
			$stmt -> bindParam(":codcliente", $valor, PDO::PARAM_STR);

			$stmt -> execute();

			return $stmt -> fetch();

		}else{

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla c INNER JOIN tipodocumentoidentidad tdi ON c.codtipodocumento = tdi.codtipodocumento ;");

			$stmt -> execute();

			return $stmt -> fetchAll();

		}

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlReporteClientes(){

		$stmt = Conexion::conectar()->prepare("SELECT 
		codcliente,
		COALESCE(cliente.nomrznsocial, '-') nombre,
		COALESCE(cliente.nomrznsocial, '-') nombre_comercial, 
		 docidentidad id_documento, direccion FROM cliente ;");

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlMostrarTodosClientes(){

		$stmt = Conexion::conectar()->prepare("SELECT * FROM cliente");

		$stmt -> execute();

		return $stmt -> fetchAll(PDO::FETCH_ASSOC);

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
	MOSTRAR CLIENTES
	=============================================*/

	static public function mdlReporteVentasClientesxMes(){

		$stmt = Conexion::conectar()->prepare("SELECT UPPER(X.nombre_comercial) AS 'NOMBRE COMERCIAL',  SUM(X.12 ) AS 'MES12' , SUM(X.11) AS 'MES11' ,  SUM(X.10) AS 'MES10' ,  SUM(X.9) AS 'MES9',  SUM(X.8) AS 'MES8', SUM(X.7) AS 'MES7' , SUM(X.6) AS 'MES6', SUM(X.5) AS 'MES5', SUM(X.4) AS 'MES4' , SUM(X.3) AS 'MES3' , SUM(X.2)  AS 'MES2', SUM(X.1) AS 'MES1' ,SUM(X.0) AS 'MESPRESENTE'
		FROM ( 
		
		SELECT CONCAT( C.nombre_comercial , ' (', COALESCE(C.nota,'') ,')' ) AS nombre_comercial
		,
			SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 12 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '12',
				
					SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 11 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '11',
				
					SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 10 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '10',
				
					SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 9 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '9',
				
					SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 8 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '8',
				
					SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 7 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '7',
				
					SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 6 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '6',
				
					SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 5 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '5',
				
			 SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 4 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '4',
		
			 SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 3 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '3',
			 SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 2 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '2',
				
			 SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( DATE_SUB(curdate(), INTERVAL 1 MONTH), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '1',
				SUM(
				CASE 
					WHEN DATE_FORMAT(V.fecha_venta  , '%b %Y')   = DATE_FORMAT( curdate(), '%b %Y')
					THEN V.total 
					ELSE 0 
				END
			) AS '0'
		FROM    cliente  C
		INNER JOIN ventas V on V.id_documento_cliente = C.id_documento
		WHERE V.anulado = 0 
		GROUP BY CONCAT( C.nombre_comercial , ' (', COALESCE(C.nota,'') ,')' )  ,DATE_FORMAT(fecha_venta  , '%b %Y')  
		) AS X
		GROUP BY X.nombre_comercial
		ORDER BY 1 ASC
		
 ");

		$stmt -> execute();
		return $stmt -> fetchAll(PDO::FETCH_ASSOC);

		$stmt -> close();

		$stmt = null;

	}



	/*=============================================
	MOSTRAR CLIENTES BUSCADOR
	=============================================*/

	static public function mdlMostrarClientesBuscador(){

			$stmt = Conexion::conectar()->prepare("SELECT id_documento,nombre FROM cliente");

			$stmt -> execute();

			return $stmt -> fetchAll();

	

		$stmt -> close();

		$stmt = null;

	}


	/*=============================================
			MOSTRAR CLIENTES REPORTES
	=============================================*/

	static public function mdlRankingClientesReportes(){

			$stmt = Conexion::conectar()->prepare("SELECT id_cliente , cliente.nombre AS nombre_cliente, sum(total) AS total_ventas ,  SUM(total_productos) AS total_productos 
			FROM ventas INNER JOIN cliente ON cliente.id_documento = id_cliente GROUP BY id_cliente, cliente.nombre 
			ORDER BY 2 DESC LIMIT 10");

			$stmt -> execute();

			return $stmt -> fetchAll();

	

		$stmt -> close();

		$stmt = null;

	}

	/*=============================================
			BUSQUEDA DNI y RUC
	=============================================*/

	static public function mdlBusquedaDNIRUC($id){


	

	}




	/*=============================================
	EDITAR CLIENTE
	=============================================*/

	static public function mdlEditarCliente($tabla, $datos){
		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nomrznsocial = :nomrznsocial, direccion = :direccion,  docidentidad = :documento, codtipodocumento = :tipo_doc_id WHERE codcliente = :id");

		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
		$stmt->bindParam(":nomrznsocial", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);
		$stmt->bindParam(":documento", $datos["documento"], PDO::PARAM_INT);
		$stmt->bindParam(":tipo_doc_id", $datos["tipo_doc_id"], PDO::PARAM_STR);
		$stmt->bindParam(":direccion", $datos["direccion"], PDO::PARAM_STR);

		if($stmt->execute()){

			return "ok";

		}else{

			return "error";
		
		}

		$stmt->close();
		$stmt = null;

	}

	/*=============================================
	ELIMINAR CLIENTE
	=============================================*/

	static public function mdlEliminarCliente($tabla, $datos){

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE codcliente = :id");

		$stmt -> bindParam(":id", $datos, PDO::PARAM_INT);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}

		/*=============================================
	ACTUALIZAR CLIENTE
	=============================================*/

	static public function mdlActualizarCliente($tabla, $item1, $valor1, $valor){

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE id = :id");

		$stmt -> bindParam(":".$item1, $valor1, PDO::PARAM_STR);
		$stmt -> bindParam(":id", $valor, PDO::PARAM_STR);

		if($stmt -> execute()){

			return "ok";
		
		}else{

			return "error";	

		}

		$stmt -> close();

		$stmt = null;

	}
 
	static public function mdlImportarClientes ( $clientes )
	{
		try 
		{
			$conn = Conexion::conectar();
			
			$conn->beginTransaction();
			$TXT = "";
				
			$file ="./txt_cliente.txt";
			foreach ($clientes as $cliente)
			{				

				$SQL ="";
				$stmt_cliente = $conn->prepare(" SELECT id_documento as id FROM cliente WHERE id_documento = :doc_id;");
				$stmt_cliente -> bindParam(":doc_id" , $cliente["DOC_CLIENTE"], PDO::PARAM_STR);
				
				$TXT .= json_encode($cliente)."\n";

				$stmt_cliente -> execute();
				$existe_cliente = $stmt_cliente -> fetch( PDO::FETCH_ASSOC );
				
				if(!$existe_cliente)
				{
					
					$SQL = "INSERT INTO cliente 
					( nombre, id_documento, direccion, nombre_comercial, telefono, email ) 
					VALUES 
					( :nombre, :id_docuento2, :direccion, :nombre_comercial, :telefono,	:email );";
				}
				else
				{
					
					$SQL = " UPDATE cliente 
					SET   nombre           = :nombre,
					      direccion        = :direccion,
					      nombre_comercial = :nombre_comercial,
					      telefono         = :telefono,
					      email            = :email
					where id_documento      = :id; ";

				}
				$TXT.=$SQL."\n";
				$stmt_cli = $conn->prepare(" $SQL ");
				
				if(!$existe_cliente)
				{
					$TXT.="cli \n";
					$stmt_cli -> bindParam(":id_docuento2" , $cliente["DOC_CLIENTE"], PDO::PARAM_STR);
				}
				else{
					$TXT.="cli ".$existe_cliente["id"]."\n";
					$stmt_cli->bindParam(":id", $cliente["DOC_CLIENTE"], PDO::PARAM_STR);

				}
				
				
				
				
				
				$stmt_cli->bindParam(":nombre", $cliente["NOMBRE_CLIENTE"], PDO::PARAM_STR);
				$stmt_cli->bindParam(":nombre_comercial", $cliente["NOMBRE_COMERCIAL"], PDO::PARAM_STR);
				$stmt_cli->bindParam(":email", $cliente["EMAIL_CLIENTE"], PDO::PARAM_STR);
				$stmt_cli->bindParam(":telefono", $cliente["TELEFONO_CLIENTE"], PDO::PARAM_STR);
				$stmt_cli->bindParam(":direccion", $cliente["DIRECCION_CLIENTE"], PDO::PARAM_STR);
				$stmt_cli->execute();
				
			}
			file_put_contents($file, $TXT );
			
			//UNA VEZ QUE INSERTAMOS EL PRODUCTO ,  TERMINAMOS INSERTANDO LAS TRANSACCIONES
			
			$conn->commit();
			//echo'<script>console.log("AQUI:'.$lastID.'");</script>';
			return array("data"=>1, "mensaje"=>"Clientes importados exitosamente");
			
		}
		catch(Exception $e)
		{			
			$conn->rollBack();
			return array("data"=>0, "mensaje"=>$e->getMessage());
		}
	}

	

}